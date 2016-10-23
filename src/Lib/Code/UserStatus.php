<?php
namespace App\Lib\Code;

class UserStatus 
{
	public static $LOCKED;
	public static $UNACTIVATED;
	public static $NORMAL;
	public static $RESET_PASSWORD;
	
	public static function init()
	{
		self::$UNACTIVATED = [CodePattern::$CODE => 1, CodePattern::$VALUE => 'Unactivate'];
		self::$NORMAL = [CodePattern::$CODE => 2, CodePattern::$VALUE => 'Normal'];
		self::$LOCKED = [CodePattern::$CODE => 3, CodePattern::$VALUE => 'Locked'];
		self::$RESET_PASSWORD = [CodePattern::$CODE => 4, CodePattern::$VALUE => 'Reset password'];
	}
}
UserStatus::init();
?>