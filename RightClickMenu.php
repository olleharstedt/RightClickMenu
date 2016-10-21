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
        $assetsUrl = Yii::app()->assetManager->publish(dirname(__FILE__) . '/js');
        App()->clientScript->registerScriptFile("$assetsUrl/bootstrap-contextmenu.js");
        App()->clientScript->registerScriptFile("$assetsUrl/rightclickmenu.js");
    }

    /**
     * @return 
     */
    public function beforeAdminMenuRender()
    {
        $event = $this->getEvent();
        $data = $event->get('data');

        if (!isset($data['surveyid'])) {
            return;
        }

        $questions = Question::model()->findAllByAttributes(array(
            'sid' => $data['surveyid'],
            'parent_qid' => '0'
        ));

        foreach ($questions as $question) {
            $data['questionurls'][$question->qid] = $this->api->createUrl(
                '/admin/questions',
                array(
                    'sa' => 'view',
                    'surveyid' => $data['surveyid'],
                    'qid' => $question->qid
                )
            );
        }

        $data['questions'] = $questions;

        $content = $this->renderPartial('testmenu', $data, true);
        echo $content;
    }
}
