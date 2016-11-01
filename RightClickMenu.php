<?php

class RightClickMenu extends \ls\pluginmanager\PluginBase {

    protected $storage = 'DbStorage';
    static protected $description = 'Adds a right-click menu to LimeSurvey';
    static protected $name = 'Right-click menu';


    /**
     * 
     * @return 
     */
    public function init()
    {
        $this->subscribe('beforeControllerAction');
        $this->subscribe('beforeAdminMenuRender');
    }

    /**
     * 
     * @return 
     */
    public function beforeControllerAction()
    {
        $assetsUrl = Yii::app()->assetManager->publish(dirname(__FILE__));
        App()->clientScript->registerScriptFile("$assetsUrl/js/bootstrap-contextmenu.js");
        App()->clientScript->registerScriptFile("$assetsUrl/js/rightclickmenu.js");
        App()->clientScript->registerCssFile("$assetsUrl/css/submenu.css");
    }

    /**
     * @return 
     */
    public function beforeAdminMenuRender()
    {
        $event = $this->getEvent();
        $data = $event->get('data');

        // No survey id = no question explorer
        if (!isset($data['surveyid'])) {
            $data = array('questionGroups' => array());
            $content = $this->renderPartial('testmenu', $data, true);
            echo $content;
            return;
        }

        $iSurveyID = $data['surveyid'];

        $survey = Survey::model()->with(array(
            'languagesettings' => array('condition'=>'surveyls_language=language'))
        )->find('sid = :surveyid', array(':surveyid' => $data['surveyid'])); //$sumquery1, 1) ; //Checked
        $questionGroups = QuestionGroup::model()->findAllByAttributes(array('sid' => $iSurveyID, "language" => $survey->defaultlanguage->surveyls_language),array('order'=>'group_order ASC'));
        if(count($questionGroups))
        {
            foreach($questionGroups as $group)
            {
                $group->questions = Question::model()->findAllByAttributes(array(
                    "sid"=>$iSurveyID,
                    "gid"=>$group['gid'],
                    "language"=>$survey->defaultlanguage->surveyls_language,
                    'parent_qid' => '0'
                ),
                array('order'=>'question_order ASC'));

                foreach($group->questions as $question)
                {
                    $question->question = viewHelper::flatEllipsizeText($question->question,true,60,'[...]',0.5);
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
        $questions = Question::model()->findAllByAttributes(array(
            'sid' => $data['surveyid'],
            'parent_qid' => '0'
        ));

        $qtypelist = getQuestionTypeList('', 'array');

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
                '/admin/conditions',
                array(
                    'sa' => 'index',
                    'subaction' => 'editconditionsform',
                    'surveyid' => $data['surveyid'],
                    'qid' => $question->qid,
                    'gid' => $question->gid
                )
            );

            $data['conditionsUrls'][$question->qid] = $this->api->createUrl(
                '/admin/questions',
                array(
                    'sa' => 'editquestion',
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

            $data['typeDescriptions'][$question->qid] = $qtypelist[$question->type];
        }

        $data['questionGroups'] = $questionGroups;

        $content = $this->renderPartial('testmenu', $data, true);
        echo $content;
    }
}
