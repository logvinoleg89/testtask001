<?php
namespace backend\controllers;

use Yii;
/**
 * Site controller
 */
class SiteController extends \yii\web\Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }
    
    public function beforeAction($action)
    {
        if (!\Yii::$app->user->can('manager')) {
            $this->redirect('/');
        }
        
        $this->layout = Yii::$app->user->isGuest ? 'base' : 'main';
        return parent::beforeAction($action);
    }
}
