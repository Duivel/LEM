<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class Expense extends Entity
{
	protected  $_accessible = [
			'*' => TRUE,
			'expense_id' => FALSE
	];
	
	public function displayDateInView()
	{
		return date_format($this->date, 'd/m/Y');
	}
	
	public function displayUserName()
	{
		return $this->Users['user_name'];
	}
	
	public function displayFormatAmount()
	{
		return number_format($this->amount);
	}
	
	public function displayCreateUserName()
	{
		return $this->UserCreate['user_name'];
	}
	
	public function displayModifyUserName()
	{
		return $this->UserModify['user_name'];
	}
	
	public function displayExpenseType()
	{
		return $this->ExpenseTypes['expense_type_name'];
	}
	
	public function changeDateFormatForSave() 
	{
		if (isset($this->date)) {
			$temp = \DateTime::createFromFormat('d/m/Y', $this->date)->format('Y-m-d');
			$this->date = $temp;
		} else {
			$this->date = 'linh tinh';
		}
	}
	
	public function displayDateInEdit()
	{
		$date = new \DateTime($this->date);
		$this->date = $date->format('d/m/Y');
	}
	
}