<?php

namespace app\models;

use app\utilities\UtilityHelper;

class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
	public $depositAmount;
     /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return self::findOne(array('id'=>$id));
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::find()->where(['token' => $token])->one();
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return false;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return false;
    }

	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'users';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			['email', 'required', 'when' => function($model) {
				return $model->first_name == null;
			}],
			[['first_name', 'last_name'], 'string', 'max' => 50],
			[['email'], 'string', 'max' => 64],
			[['email'], 'email'],
			[['balance', 'depositAmount'], 'integer', 'min' => 0],
			[['token'], 'string', 'max' => 32],
			['token_expiration_date', 'datetime', 'format' => 'php:Y-m-d H:i:s'],
			[['api_key'], 'string', 'max' => 32],
			[['email'], 'unique'],
			[['depositAmount'], 'safe'],
			[['depositAmount'], 'required', 'on' => ['deposit']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'email' => 'Email',
			'token' => 'Token',
		];
	}

	public static function auth($email)
	{
		$user = self::find()
			->where(['email' => $email])
			->one();

		if (!$user) {
			$user = new self();
			$user->email = $email;
			$user->api_key = UtilityHelper::generateRandomString($email);
			$user->balance = 1000;
		}

		$user->token = UtilityHelper::generateRandomString($email);
		// add 1 day for token expiration
		$user->token_expiration_date = date('Y-m-d H:i:s', strtotime('+1 day', time()));

		if ($user->save()) {
			return $user;
		}

		return false;
	}
}
