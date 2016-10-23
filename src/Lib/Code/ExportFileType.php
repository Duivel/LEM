<?php
namespace App\Lib\Code;

class ExportFileType
{
	public static $EXPENSE;
	public static $INCOME;
	public static $WITHDRAW;
	public static $USER;
	
	public static function init()
	{
		self::$EXPENSE = [CodePattern::$CODE => 1, CodePattern::$VALUE => 'Expense'];
		self::$INCOME = [CodePattern::$CODE => 2, CodePattern::$VALUE => 'Income'];
		self::$WITHDRAW = [CodePattern::$CODE => 3, CodePattern::$VALUE => 'Withdraw'];
		self::$USER = [CodePattern::$CODE => 4, CodePattern::$VALUE => 'User'];
	}
}
ExportFileType::init();
?>