<?php
namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use App\Lib\LoginUser;
use Cake\Utility\Text;
use App\Lib\Code\DelFlag;
use App\Lib\Code\CodePattern;
use Cake\Event\Event;
use Cake\Datasource\EntityInterface;

class IncomeBehavior extends Behavior
{
	public function incomeUpdate(Entity $entity)
	{
		$fields = ['modify_user'];
		if ($entity->isNew()) {
			$fields = ['income_id', 'create_user', 'modify_user', 'del_flg'];
		}

		$user = empty(LoginUser::getLogin()) ? '0' : LoginUser::getLogin();
		
		$columnList = $this->_table->schema()->columns();
		if (!is_null($columnList) && count($columnList) > 0) {
			foreach ($columnList as $column) {
				foreach ($fields as $field) {
					if ($field == $column) {
						if ($field == 'income_id') {
							$entity->set($field, Text::uuid());
						}
						if (($field == 'create_user') || ($field == 'modify_user')) {
							$entity->set($field, $user);
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
		$this->incomeUpdate($entity);
	}
}
?>