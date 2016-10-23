<?php
namespace App\Model\Behavior;

use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\ORM\Behavior;
use Cake\Utility\Text;
use Cake\Datasource\EntityInterface;

use App\Lib\LoginUser;
use App\Lib\Code\DelFlag;
use App\Lib\Code\CodePattern;

class ExpenseTypeBehavior extends Behavior
{
	public function expenseTypeUpdate(Entity $entity)
	{
		$fields = ['modify_user'];
		
		if ($entity->isNew()) {
			$fields = ['expense_type_id', 'create_user', 'modify_user', 'del_flg'];
		}
		
		$user = is_null(LoginUser::getLogin()) ? '0' : LoginUser::getLogin();
		$columnList = $this->_table->schema()->columns();
		
		if (!empty($columnList)) {
			foreach ($columnList as $column) {
				foreach ($fields as $field) {
					if ($field == $column) {
						if (($field == 'modify_user') || ($field == 'create_user')) {
							$entity->set($field, $user);
						}
						if ($field = 'expense_type_id') {
							$entity->set($field, Text::uuid());
						}
						if ($field == 'del_flg') {
							$entity->set($field, DelFlag::$ALIVE[CodePattern::$CODE]);
						}
					}
					
				}
			}
		}
	}

	public function beforeSave(Event $event, EntityInterface $entity) 
	{
		$this->expenseTypeUpdate($entity);
	}
}