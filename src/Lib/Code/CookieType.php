<?php
namespace App\Lib\Code;

class CookieType
{
	public static $LOGIN;
	public static $EXPENSE;
	public static $WITHDRAW;
	public static $INCOME;
	
	public static function init()
	{
		self::$LOGIN = [CodePattern::$CODE => 1, CodePattern::$VALUE => 'Login'];
		self::$EXPENSE = [CodePattern::$CODE => 2, CodePattern::$VALUE => 'Expense'];
		self::$WITHDRAW = [CodePattern::$CODE => 3, CodePattern::$VALUE => 'Withdraw'];
		self::$INCOME = [CodePattern::$CODE => 4, CodePattern::$VALUE => 'Income'];
		
	}
}
CookieType::init();