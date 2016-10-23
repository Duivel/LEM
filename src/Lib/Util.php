<?php
namespace App\Lib;

class Util {
	public static $token = 'YNo7UHQCSv65c2Qs';

	public static function makeRandomString($stringLeng)
	{
		$characters = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
// 		$stringLeng = Constants::RANDOM_STRING_LENGTH;
		$randomString = '';
		for ($i = 0; $i < $stringLeng; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}
	
	public static function codeGenerator()
	{
		$temp = self::makeRandomString();
		$code = base64_encode($temp);
		if (strlen($code) < Constants::CODE_LENGTH) {
			$code = self::codeGenerator();
		}
		return $code;
	}
	
	public static function stringGenerator($type)
	{
		switch ($type) {
			case 'login_cookie':
				$length = Constants::LOGIN_TOKEN_LENGTH;
				break;
			case 'reissue_password':
				$length = Constants::REISSUE_PASSWORD_CODE_LENGTH;
				break;
			default:
				$length = 10;
				break;
		}
		$temp = self::makeRandomString($length);
		$code = base64_encode($temp);
		if (strlen($code) < $length) {
			$code = self::codeGenerator();
		}
		return $code;
	}
	
	/**
	 * return Y-m-d H:i:s,u
	 * To milisecond
	 */
	public static function getCurrentDateTime()
	{
		$timeStampData = microtime();
		list($microSec, $date) = explode(' ', $timeStampData);
		$date = date("Y-m-d H:i:s", $date).','.mb_substr($microSec, mb_stripos($microSec, '.')+1, 3);
		return $date;
	}
	
	function maskEmail($email)
	{
		$em = explode("@",$email);
		$name = implode(array_slice($em, 0, count($em)-1), '@');
		$len  = floor(strlen($name)/2);
	
		return substr($name,0, $len) . str_repeat('*', $len) . "@" . end($em);
	
	}
}
?>