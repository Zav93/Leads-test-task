<?php
namespace app\utilities;

class UtilityHelper
{
	public static function generateRandomString($email)
	{
		return md5($email . time() . rand(0, 1000));
	}

	public static function getModelErrorMessagesAsHtml($errors)
	{
		$html = '<ul>';

		foreach ($errors as $field => $fieldErrors) {
			foreach ($fieldErrors as $error) {
				$html .= '<li>' . $error . '</li>';
			}
		}

		$html .= '</ul>';

		return $html;
	}
}