<?php

namespace app\controllers;

use Yii;

class AuthController extends \yii\web\Controller
{
    public function actionIndex($token)
    {
		Yii::$app->user->loginByAccessToken($token, 0);
		if (Yii::$app->user->identity && Yii::$app->user->identity->token_expiration_date < date('Y-m-d H:i:s')) {
			Yii::$app->user->logout();
			Yii::$app->session->setFlash('error', "Token has expired!");
			return Yii::$app->getResponse()->redirect('/site/login');
		}

		return Yii::$app->getResponse()->redirect('/user/index');
    }
}
