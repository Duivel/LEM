<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class LoginHistory extends Entity
{
	public function displayDayOfWeek()
	{
		return date('l', strtotime($this->login_date));
	}
	
	public function displayDate()
	{
		return date_format($this->login_date, 'd/m/Y H:i:s');
	}
}