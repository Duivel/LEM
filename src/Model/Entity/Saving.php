<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class Saving extends Entity
{
	public function displaySavingFormatAmount()
	{
		return number_format($this->saving);
	}
	
	public function displayExpenseFormatAmount()
	{
		return number_format($this->expense);
	}
	
	public function displayIncomeFormatAmount()
	{
		return number_format($this->income);
	}
	
	public function displayCreateDateInView()
	{
		return date_format($this->created, 'd/m/Y H:i:s');
	}
	
	public function displayModifyDateInView()
	{
		return date_format($this->modified, 'd/m/Y H:i:s');
	}
	
	
}
?>