<?php

namespace frontend\controllers;

use Yii;
use common\models\User;
use common\components\AccessRule;
use yii\filters\AccessControl;
use common\models\search\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * UsersController implements the CRUD actions for User model.
 */
class UsersController extends Controller
{
    public function actions()
    {
        return [
            'oauth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'successOAuthCallback'],
                'successUrl' => '/users/profile'
            ]
        ];
    }
    
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [
                            User::ROLE_ADMIN
                        ],
                    ],
                    [
                        'actions' => ['oauth'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['profile'],
                        'allow' => true,
                        'roles' => [
                            '@'
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        } else {
            $searchModel = new UserSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider]);
        }

    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->getSession()->setFlash('succeeded', 'Редактирование прошло успешно!');
            return $this->redirect(['index', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    
    /**
    * @return string|\yii\web\Response
    */
    public function actionProfile()
    {
        $model = User::findOne(Yii::$app->user->id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash(
                'alert', [
                    'options' => ['class' => 'alert-success'],
                    'body' => 'Редактирование прошло успешно!'
                ]
            );
            return $this->refresh();
        }
        
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Ничего не найдено');
        }
    }
    
    /**
     * @param $client \yii\authclient\BaseClient
     * @return bool
     * @throws Exception
     */
    public function successOAuthCallback($client)
    {
        $attributes = $client->getUserAttributes();
       
        $user = User::find()->where([
                'oauth_client'=>$client->getName(),
                'oauth_client_user_id'=>ArrayHelper::getValue($attributes, 'id')
            ])
            ->one();
        
        if (!$user) {
            $user = new User();
            $user->scenario = 'oauth_create';
            $user->email = ArrayHelper::getValue($attributes, 'email');
            $user->username = ArrayHelper::getValue($attributes, 'login', $user->email);
            $user->oauth_client = $client->getName();
            $user->oauth_client_user_id = ArrayHelper::getValue($attributes, 'id');
            $password = Yii::$app->security->generateRandomString(8);
            
            
            $user->setPassword($password);
             
            if ($user->save()) {
                Yii::$app->getSession()->setFlash(
                    'alert',
                    [
                        'options'=>['class'=>'alert-success'],
                        'body'=> 'Добро пожаловать'
                    ]
                );
            } else {
                if (User::find()->where(['email'=>$user->email])->count()) {
                    Yii::$app->getSession()->setFlash(
                        'alert',
                        [
                            'options'=>['class'=>'alert-danger'],
                            'body'=>'У нас уже есть пользователь с электронной почтой ' . $user->email
                        ]
                    );
                } else {
                    Yii::$app->getSession()->setFlash(
                        'alert',
                        [
                            'options'=>['class'=>'alert-danger'],
                            'body'=>'Ошибка при OAuth авторизации'
                        ]
                    );
                }
            };
        }
        if (Yii::$app->user->login($user, 3600 * 24 * 30)) {
            return true;
        } else {
            throw new Exception('OAuth error');
        }
    }
}
