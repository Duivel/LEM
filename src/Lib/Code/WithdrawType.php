<?php
namespace App\Lib\Code;

class WithdrawType 
{
	public static $CASH;
	public static $TRANSFER;
	
	public static function init()
	{
		self::$CASH = [CodePattern::$CODE => 1, CodePattern::$VALUE => 'Cash'];
		self::$TRANSFER = [CodePattern::$CODE => 2, Codepattern::$VALUE => 'Transfer'];
	}
}
WithdrawType::init();
?>