<?php
namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use Cake\Event\Event;
use Cake\Datasource\EntityInterface;
use Cake\Utility\Text;
use App\Lib\Code\DelFlag;
use App\Lib\Code\CodePattern;
use App\Lib\LoginUser;
use App\Lib\Code\ExportFileStatus;

class ExportFileBehavior extends Behavior
{
	public function exportFileUpdate(Entity $entity)
	{
		if ($entity->isNew()) {
			$fields = ['export_id', 'del_flg', 'status', 'user_id'];
			$user = empty(LoginUser::getLogin()) ? '0' : LoginUser::getLogin();
			$columnList = $this->_table->schema()->columns();
			if (!empty($columnList)) {
				foreach ($columnList as $column) {
					foreach ($fields as $field) {
						if ($field == $column) {
							if ($field == 'export_id') {
								$entity->set($field, Text::uuid());
							}
							if ($field == 'del_flg') {
								$entity->set($field, DelFlag::$ALIVE[CodePattern::$CODE]);
							}
							if ($field == 'status') {
								$entity->set($field, ExportFileStatus::$WAIT[CodePattern::$CODE]);
							}
							if ($field == 'user_id') {
								$entity->set('user_id', $user);
							}
						}
					}
				}
			}
		}
	}

	public function beforeSave(Event $event, EntityInterface $entity)
	{
		$this->exportFileUpdate($entity);
	}
}
?>