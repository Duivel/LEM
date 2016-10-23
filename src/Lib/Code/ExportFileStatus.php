<?php
namespace App\Lib\Code;

class ExportFileStatus 
{
	public static $WAIT;
	public static $SUCCESS;
	public static $FAILED;
	
	public static function init()
	{
		self::$WAIT = [CodePattern::$CODE => 1, CodePattern::$VALUE => 'Waiting'];
		self::$SUCCESS = [CodePattern::$CODE => 2, CodePattern::$VALUE => 'Success'];
		self::$FAILED = [CodePattern::$CODE => 3, CodePattern::$VALUE => 'Failed'];
	}
}
ExportFileStatus::init();
?>