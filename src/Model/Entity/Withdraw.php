<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use App\Lib\Convertor\ConvertValue;

class Withdraw extends Entity
{
	public function displayWithdrawTypeInView()
	{
		return ConvertValue::findConvertValue('WithdrawType', $this->withdraw_type_id);
	}
	
	public function displayDateInView()
	{
		return date_format($this->date, 'd/m/Y');
	}
	
	public function displayDateInEdit()
	{
		$date = new \DateTime($this->date);
		$this->date = $date->format('d/m/Y');
	}
	
	public function displayFormatAmount()
	{
		return number_format($this->amount);
	}
}