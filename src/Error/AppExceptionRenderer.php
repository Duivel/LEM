<?php
namespace App\Error;

use Cake\Error\ExceptionRenderer;

class AppExceptionRenderer extends ExceptionRenderer
{
	public function render()
	{
		$exception = $this->error;
		$code = $this->_code($exception);
		if ($code >= 500) {
			//send email
		}
		return parent::render();
	}
}