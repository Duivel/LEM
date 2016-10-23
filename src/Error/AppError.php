<?php
namespace App\Error;

use Cake\Error\BaseErrorHandler;

class AppError extends BaseErrorHandler
{
	public function handleFatalError($code, $description, $file, $line)
	{
// 		parent::handleFatalError($code, $description, $file, $line);
		pr($code);exit();
	}
	
// 	public function handleError($code, $description, $file = NULL, $line = NULL, $context = NULL) 
// 	{
//  		parent::handleError($code, $description, $file, $line, $context);
// 		pr('error code: '.$code);exit();
// 	}
	
// 	public function _displayError($error, $debug) 
// 	{
// 		return 'test error';
// 	}
	
// 	public function _displayException($exception)
// 	{
// 		return 'test exception!';
// 	}
	
	
	
}