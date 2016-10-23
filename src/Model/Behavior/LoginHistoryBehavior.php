<?php
namespace App\Model\Behavior;

use Cake\ORM\Entity;
use Cake\ORM\Behavior;
use Cake\Event\Event;
use Cake\Utility\Text;
use Cake\Datasource\EntityInterface;

use App\Lib\LoginUser;
use App\Lib\Code\DelFlag;
use App\Lib\Code\CodePattern;

class LoginHistoryBehavior extends Behavior
{
	public function historyUpdate(Entity $entity)
	{
		$fields = ['login_id', 'del_flg'];
		$user = is_null(LoginUser::getLogin()) ? '0' : LoginUser::getLogin();
		
		$columnList = $this->_table->schema()->columns();
		if (!empty($columnList)) {
			foreach ($columnList as $column) {
				foreach ($fields as $field) {
					if ($field == $column) {
						if ($field == 'login_id') {
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
		$this->historyUpdate($entity);
	}
	
}