<?php
namespace App\Model\Behavior;

use Cake\ORM\Entity;
use Cake\ORM\Behavior;

use Cake\Event\Event;
use Cake\Utility\Text;
use Cake\Datasource\EntityInterface;

class UserCookieBehavior extends Behavior
{
	public function cookieUpdate(Entity $entity)
	{
		$fields = 'cookie_id';
		$columnList = $this->_table->schema()->columns();
		
		if (!empty($columnList)) {
			foreach ($columnList as $column) {
				if ($column == $fields) {
					$entity->set($fields, Text::uuid());
				}
			}
		}
	}

	public function beforeSave(Event $event, EntityInterface $entity)
	{
		$this->cookieUpdate($entity);
	}
}