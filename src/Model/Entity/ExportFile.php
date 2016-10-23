<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use App\Lib\Convertor\ConvertValue;

class ExportFile extends Entity
{
	public function displayStatusInView() 
	{
		return ConvertValue::findConvertValue('ExportFileStatus', $this->status);
// 		return ConvertValue::findConvertValue('UserStatus', $this->status);
	}
	
	public function displayTypeInView()
	{
		return ConvertValue::findConvertValue('ExportFileType', $this->export_type_id);
	}
	
	public function displayDayInView() 
	{
		return date_format($this->created, 'd/m/Y');
	}
	
	public function displayTimeInView()
	{
		return date_format($this->created, 'H:i:s');
	}
}