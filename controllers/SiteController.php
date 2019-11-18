<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\LeaveForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionHome()
    {
      if (Yii::$app->user->isGuest) {
          return $this->goHome();
      }
      else {
        return $this->render('home');
    }
  }


    public function actionStatus()
    {


if (Yii::$app->user->isGuest) {
    return $this->goHome();
}
else {
  return $this->render('status');
}



    }


    public function actionDbfeatch()
    {

      $users = Yii::$app->db->createCommand('SELECT * FROM users')
     ->queryAll();
foreach($users as $user) {
  echo $user['name'].'<br />';
}
    //  var_dump($users);
    echo $users[0]['name'];
    //print_r($users);
    //echo $users[0][Id];
      return $this->render('dbfeatch');
    }


    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
/*
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
*/      $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
        return $this->redirect(['home']);
}

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }
    /**
     * Displays contact page.
     *
     * @return Response|string
     */

    public function actionLeave()
    {
        $model = new LeaveForm();
        if ($model->load(Yii::$app->request->post()) && $model->leave(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('LeaveFormSubmitted');

            return $this->refresh();
        }
        return $this->render('leave', [
            'model' => $model,
        ]);
    }
    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }


}
