<?php
namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use App\Lib\LoginUser;
use Cake\Utility\Text;
use App\Lib\Code\UserStatus;
use App\Lib\Code\CodePattern;
use Cake\Datasource\EntityInterface;
use App\Lib\Code\DelFlag;
use Cake\Event\Event;

class UserBehavior extends Behavior
{
	public function userUpdate(Entity $entity)
	{
		$fields = ['modify_user'];

		if ($entity->isNew()) {
			$fields = ['create_user', 'modify_user', 'del_flg', 'user_id', 'status', 'locked_limit_time', 'log_attempt'];
		}

		//Load current login user
		$user = is_null(LoginUser::getLogin()) ? '0' : LoginUser::getLogin();
		
		$columnList = $this->_table->schema()->columns();
		if (!empty($columnList)) {
			foreach ($columnList as $column) {
				foreach ($fields as $field) {
					if ($field == $column) {
						if ($field == 'create_user' || $field == 'modify_user') {
							$entity->set($field, $user);
						} else if ($field == 'del_flg') {
							$entity->set($field, DelFlag::$ALIVE[CodePattern::$CODE]);
						} else if ($field == 'user_id') {
							$entity->set('user_id', Text::uuid());
						} else if ($field == 'status') {
							$entity->set('status', UserStatus::$UNACTIVATED[CodePattern::$CODE]);
						} else if ($field == 'log_attempt') {
							$entity->set($field, 0);
						} else if ($field == 'locked_limit_time') {
							$entity->set($field, 0);
						}
					}
				}
			}
		}
	}
	
	public function beforeSave(Event $event, EntityInterface $entity)
	{
		$this->userUpdate($entity);
	}
}
?>