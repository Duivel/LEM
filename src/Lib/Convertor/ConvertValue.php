<?php
namespace App\Lib\Convertor;

//use App\Lib\Constants;
use App\Lib\Code\CodePattern;
use App\Lib\Constants;

class ConvertValue
{
	public $src;
	
	public function __construct(&$src)
	{
		$this->src = &$src;
	}

	public static function findConvertValue($className, $codeValue)
	{
		$className = Constants::NAMESPACE_CODE_CLASS.$className;
		$vars = get_class_vars($className);
		//pr($vars);exit();
		if (empty($vars)) {
			return null;
		}
		foreach ($vars as $var) {
			if ($var[CodePattern::$CODE] == $codeValue) {
				return $var[CodePattern::$VALUE];
			}
		}
		return null;
	}
}
?>