<?php

class RightClickMenu extends \ls\pluginmanager\PluginBase
{

    /**
     * @var string
     */
    protected $storage = 'DbStorage';

    /**
     * @var string
     */
    static protected $description = 'Adds a right-click menu to LimeSurvey';

    /**
     * @var string
     */
    static protected $name = 'Right-click menu';

    /**
     * @return void
     */
    public function init()
    {
        $config = include Yii::app()->basePath . '/config/version.php';
        $this->lsVersion = $config['versionnumber'];

        if ((version_compare($this->lsVersion, '2.62.3')) === -1) {
            App()->setFlashMessage('Plugin right-click menu requires at least version 2.62.3', 'error');
            return;
        }

        $this->subscribe('beforeControllerAction');
        $this->subscribe('beforeAdminMenuRender');
    }

    /**
     * @return void
     */
    public function beforeControllerAction()
    {
        $event = $this->getEvent();
        $controller = $event->get('controller');
        if ($controller === 'admin') {
            $assetsUrl = Yii::app()->assetManager->publish(dirname(__FILE__));
            App()->clientScript->registerScriptFile("$assetsUrl/js/bootstrap-contextmenu.js");
            App()->clientScript->registerScriptFile("$assetsUrl/js/rightclickmenu.js");
            App()->clientScript->registerCssFile("$assetsUrl/css/submenu.css");
        }
    }

    /**
     * @return void
     */
    public function beforeAdminMenuRender()
    {
        $event = $this->getEvent();
        $data = $event->get('data');
        $data['groupChunks'] = array();

        // No survey id = no question explorer
        if (!isset($data['surveyid'])) {
            $data = array('questionGroups' => array());
            $data['groupChunks'] = array();
            $content = $this->renderPartial('menu', $data, true);
            echo $content;
            return;
        }

        $surveyId = $data['surveyid'];

        $survey = $this->getSurvey($surveyId);

        $questionGroups = $this->getGroups($survey);

        if ($questionGroups) {

            if (count($questionGroups) > 15) {
                $data['groupChunks'] = array_chunk($questionGroups, 15);
            }

            foreach ($questionGroups as $group) {
                $group->questions = Question::model()->findAllByAttributes(
                    array(
                        "sid"        =>$surveyId,
                        "gid"        =>$group['gid'],
                        "language"   =>$survey->defaultlanguage->surveyls_language,
                        'parent_qid' => '0'
                    ),
                    array('order'=>'question_order ASC')
                );

                foreach ($group->questions as $question) {
                    $question->question = viewHelper::flatEllipsizeText($question->question, true, 60, '[...]', 0.5);
                }

                $data['groupUrls'][$group->gid] = $this->api->createUrl(
                    'admin/questiongroups',
                    array(
                        'sa' => 'view',
                        'surveyid' => $data['surveyid'],
                        'gid' => $group->gid
                    )
                );

            }
        }

        $questions = Question::model()->findAllByAttributes(
            array(
                'sid' => $data['surveyid'],
                'parent_qid' => '0'
            )
        );


        $this->createUrls($questions, $data);

        $data['questionGroups'] = $questionGroups;

        $content = $this->renderPartial('menu', $data, true);
        echo $content;
    }

    /**
     * Create URLs for question menu buttons
     */
    protected function createUrls($questions, &$data)
    {
        $qtypelist = getQuestionTypeList('', 'array');
        $data['questionurls'] = array();
        $data['editurls'] = array();
        $data['conditionsUrls'] = array();
        $data['deleteUrls'] = array();
        $data['typeDescriptions'] = array();
        foreach ($questions as $question) {
            $data['questionurls'][$question->qid] = $this->api->createUrl(
                '/admin/questions',
                array(
                    'sa' => 'view',
                    'surveyid' => $data['surveyid'],
                    'qid' => $question->qid
                )
            );

            $data['editurls'][$question->qid] = $this->api->createUrl(
                '/admin/questions',
                array(
                    'sa' => 'editquestion',
                    'surveyid' => $data['surveyid'],
                    'qid' => $question->qid,
                    'gid' => $question->gid
                )
            );

            $data['conditionsUrls'][$question->qid] = $this->api->createUrl(
                '/admin/conditions',
                array(
                    'sa' => 'index',
                    'subaction' => 'editconditionsform',
                    'surveyid' => $data['surveyid'],
                    'qid' => $question->qid,
                    'gid' => $question->gid
                )
            );

            $data['deleteUrls'][$question->qid] = $this->api->createUrl(
                'admin/questions/',
                array(
                    'sa' => 'delete',
                    'surveyid' => $data['surveyid'],
                    'gid' => $question->gid,
                    'qid' => $question->qid,
                )
            );

            if ($qtypelist[$question->type]['hasdefaultvalues'] > 0) {
                $data['defaultAnswersUrls'][$question->qid] = $this->api->createUrl(
                    'admin/questions',
                    array(
                        'sa' => 'editdefaultvalues',
                        'surveyid' => $data['surveyid'],
                        'gid' => $question->gid,
                        'qid' => $question->qid
                    )
                );
            }

            if ($qtypelist[$question->type]['subquestions'] > 0) {
                $data['subquestionsUrls'][$question->qid] = $this->api->createUrl(
                    'admin/questions',
                    array(
                        'sa' => 'subquestions',
                        'surveyid' => $data['surveyid'],
                        'qid' => $question->qid,
                        'gid' => $question->gid
                    )
                );
            }

            if ($qtypelist[$question->type]['answerscales'] > 0) {
                $data['answerOptionsUrls'][$question->qid] = $this->api->createUrl(
                    'admin/questions',
                    array(
                        'sa' => 'answeroptions',
                        'surveyid' => $data['surveyid'],
                        'qid' => $question->qid,
                        'gid' => $question->gid
                    )
                );
            }

            $data['typeDescriptions'][$question->qid] = $qtypelist[$question->type];
        }
    }

    protected function getSurvey($sid)
    {
        return Survey::model()
            ->with(array('languagesettings' => array('condition'=>'surveyls_language=language')))
            ->find('sid = :surveyid', array(':surveyid' => $sid)); //$sumquery1, 1) ; //Checked
    }

    protected function getGroups($survey)
    {
        return QuestionGroup::model()
            ->findAllByAttributes(
                array('sid' => $survey->sid, "language" => $survey->defaultlanguage->surveyls_language),
                array('order'=>'group_order ASC')
            );
    }
}
