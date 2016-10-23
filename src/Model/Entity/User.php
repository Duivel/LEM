<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use App\Lib\Convertor\ConvertValue;

class User extends Entity
{
	public function displayBirthDayinView()
	{
		return date_format($this->birthday, 'd/m/Y');
	}
	
	public function displayStatusInView()
	{
		return ConvertValue::findConvertValue('UserStatus', $this->status);
	}
	
	public function displayBirthDayInEdit() 
	{
		$this->birthday = date_format($this->birthday, 'd/m/Y');
	}
}