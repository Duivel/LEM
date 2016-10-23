<?php
namespace App\Lib\Code;

class IncomeType
{
	public static $SALARY;
	public static $BONUS;
	public static $OTHERS;
	
	public static function init()
	{
		self::$SALARY = [CodePattern::$CODE => 1, CodePattern::$VALUE => 'Salary'];
		self::$BONUS = [CodePattern::$CODE => 2, CodePattern::$VALUE => 'Bonus'];
		self::$OTHERS = [CodePattern::$CODE => 3, CodePattern::$VALUE => 'Others'];
	}
}
IncomeType::init();
?>