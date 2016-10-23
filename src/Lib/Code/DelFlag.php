<?php
namespace App\Lib\Code;

class DelFlag
{
	public static $ALIVE;
	public static $DELETED;
	
	public static function init() 
	{
		self::$ALIVE = [CodePattern::$CODE => 1, CodePattern::$VALUE => 'Alive'];
		self::$DELETED = [CodePattern::$CODE => 2, CodePattern::$VALUE => 'Deleted'];
	}
	
}
DelFlag::init();
?>