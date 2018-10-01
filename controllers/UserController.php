<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class UserController extends \yii\web\Controller
{
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => ['index', 'update'],
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

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionUpdate()
	{
		$user = Yii::$app->user->identity;
		$user->first_name = Yii::$app->request->post('first_name', '');

		return $user->save()
			? Yii::$app->session->setFlash('success', "First Name has been updated!")
			: Yii::$app->session->setFlash('error', "Something went wrong!");
	}
}
