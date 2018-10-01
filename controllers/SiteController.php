<?php

namespace app\controllers;

use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\ContactForm;

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
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
    	$model = new User();
		if ($model->load(Yii::$app->request->post()) && $user = User::auth($model->email)) {
			$link = Url::base(true) . '/auth/index/'. $user->token;
			var_dump($link);
            # if user successfully saved send an email
			Yii::$app->mailer->compose()
				->setFrom('admin@domain.com')
				->setTo(Yii::$app->request->post('email'))
				->setSubject('Authentication')
				->setHtmlBody('<b>Please follow the <a href="'.$link.'">link</a> for authorization</b>')
				->send();

			Yii::$app->session->setFlash('success', "Please check your email for authorization");
        }

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

		return Yii::$app->getResponse()->redirect('/site/login');
    }
}
