<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class Income extends Entity
{
	public function displayCreateDateInView()
	{
		return date_format($this->created, 'd/m/Y');
	}
	
	public function displayUserNameInView()
	{
		return $this->Users['user_name'];
	}
	
	public function formatAmount()
	{
		return number_format($this->amount);
	}
}
?>