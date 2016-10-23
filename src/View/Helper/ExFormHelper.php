<?php
namespace App\View\Helper;

use Cake\View\Helper\FormHelper;
use Cake\ORM\TableRegistry;
use App\Lib\Constants;
use App\Lib\Code\CodePattern;

class ExFormHelper extends FormHelper
{
	public function user($name, $array = array())
	{
		$userTable = TableRegistry::get('Users');
		$users = $userTable->findAllByDelflg();
		$options = array();
		foreach ($users as $user) {
			$userId = $user->user_id;
			$userName = $user->user_name;
			$options[$userId] = $userName;
		}
		return $this->_make($options, $name, $array);
	}

	public function expenseType($name, $array = array())
	{
		$expenseTypeTable = TableRegistry::get('ExpenseTypes');
		$expenseTypes = $expenseTypeTable->findAllByDelFlg();
		$options = array();
		foreach ($expenseTypes as $expenseType) {
			$expenseTypeId = $expenseType->expense_type_id;
			$expenseTypeName = $expenseType->expense_type_name;
			$options[$expenseTypeId] = $expenseTypeName;
		}
		return $this->_make($options, $name, $array);
	}

	public function incomeType($name, $array = array())
	{
		$incomeTypeTable = TableRegistry::get('IncomeTypes');
		$incomeTypes = $incomeTypeTable->findAllByDelFlg();
		$options = array();
		foreach ($incomeTypes as $incomeType) {
			$incomeTypeId = $incomeType->income_type_id;
			$incomeTypeName = $incomeType->income_type_name;
			$options[$incomeTypeId] = $incomeTypeName;
		}
		return $this->_make($options, $name, $array);
	}
	
	public function userStatus($name, $array = array())
	{
		return $this->_makeHTML('UserStatus', $name, $array);
	}
	
	public function withdrawType($name, $array = array())
	{
		return $this->_makeHTML('WithdrawType', $name, $array);
	}
	
	private function _makeHTML($className, $name, $array)
	{
		$className = Constants::NAMESPACE_CODE_CLASS.$className;
		$options = $this->_getKeyValueByClassName($className);
		return $this->_make($options, $name, $array);
	}

	private function _make($options, $name, $array)
	{
		if ($array == null) {
			$array = array();
		}
		if (!empty($array['type'])) {
			$type = $array['type'];
			$array['type'] = null;
		}
		if (empty($type) || $type == 'select') {
			return parent::select($name, $options, $array);
	
		} else if ($type == 'radio') {
			$array['type'] = 'radio';
			$array['label'] = false;
			$array['options'] = $options;
			return parent::input($name, $array);
	
		} else if ($type == 'checkbox') {
			$array['type'] = 'select';
			$array['multiple'] = 'checkbox';
			$array['options'] = $options;
			$array['label'] = false;
	
			return parent::input($name, $array);
		}
	}
	
	private function _getKeyValueByClassName($className)
	{
		$vars = get_class_vars($className);
		$array = array();
		foreach ($vars as $var) {
			$code = $var[CodePattern::$CODE];
			$value = $var[CodePattern::$VALUE];
			$array[$code] = $value;
		}
		return $array;
	}

	private function _getKeyValueByDBData($datas, $keyName, $valueName) {
		$array = array();
		foreach ($datas as $data) {
			foreach ($data as $model) {
				$setKey = null;
				$setValue = null;
				foreach($model as $key=> $value) {
					if ($key == $keyName) {
						$setKey = $value;
					} else {
						if (is_array($valueName)) {
							$setValue = '';
							foreach ($valueName as $name) {
								if ($key == $name) {
									$setValue .= $value;
								}
							}
						} else if ($key == $valueName) {
							$setValue = $value;
						}
					}
					if ($key != null && $setValue != null) {
						break;
					}
				}
				if ($setKey != null && $setValue != null) {
					$array[$setKey] = $setValue;
				}
			}
		}
		return $array;
	}
}