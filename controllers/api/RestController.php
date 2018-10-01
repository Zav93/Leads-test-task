<?php
namespace app\controllers\api;

use app\utilities\UtilityHelper;
use Yii;

class RestController extends \yii\rest\Controller
{
	private function sendResponse($data, $statusCode)
	{
		Yii::$app->response->statusCode = $statusCode;

		return $data;
	}

	public function actionDeposit()
	{
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$request = Yii::$app->request->post();
		$apiKey = $request['apiKey'] ?? false;
		$amount = $request['depositAmount'] ?? false;

		if ($apiKey) {
			$user = \app\models\User::find()
				->where(['api_key' => $apiKey])
				->one();

			if ($user) {
				$user->setScenario('deposit');
				$user->depositAmount = $amount;
				$user->balance = $user->balance - (int)$amount;

				if (!$user->save()) {
					return $this->sendResponse(['message' => UtilityHelper::getModelErrorMessagesAsHtml($user->getErrors())], 400);
				}

				return $this->sendResponse([
					'message' => 'Deposited Successfully',
					'balance' => $user->balance,
				], 200);
			} else {
				return $this->sendResponse(['message' => 'Unauthorized User'], 401);
			}
		} else {
			return $this->sendResponse(['message' => 'Invalid Api Key'], 403);
		}
	}
}