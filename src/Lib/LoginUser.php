<?php
namespace App\Lib;

class LoginUser 
{
	private static $LOGIN_USER;
	
	public static function setLogin($userId)
	{
		self::$LOGIN_USER = $userId;
	}
	
	public static function getLogin()
	{
		return self::$LOGIN_USER;
	}
}
?>