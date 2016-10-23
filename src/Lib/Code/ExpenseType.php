<?php
namespace App\Lib\Code;

class ExpenseType
{
	public static $FOOD;
	public static $TRANSPORT;
	public static $COSTUME;
	public static $HEALTH;
	public static $SUBSISTENCE;
	public static $ENTERTAINMENT;
	public static $OTHERS;
	
	public static function init()
	{
		self::$FOOD = [CodePattern::$CODE => 1, CodePattern::$VALUE => 'Food'];
		self::$COSTUME = [CodePattern::$CODE => 2, CodePattern::$VALUE => 'Costume'];
		self::$TRANSPORT = [CodePattern::$CODE => 3, CodePattern::$VALUE => 'Transport'];
		self::$HEALTH = [CodePattern::$CODE => 4, CodePattern::$VALUE => 'Health'];
		self::$SUBSISTENCE = [CodePattern::$CODE => 5, CodePattern::$VALUE => 'Subsistence'];
		self::$ENTERTAINMENT = [CodePattern::$CODE => 6, CodePattern::$VALUE => 'Entertainment'];
		self::$OTHERS = [CodePattern::$CODE => 7, CodePattern::$VALUE => 'Others'];
	}
}
ExpenseType::init();
?>