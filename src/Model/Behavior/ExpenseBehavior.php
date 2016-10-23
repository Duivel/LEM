<?php
namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use Cake\Event\Event;
use Cake\Datasource\EntityInterface;
use App\Lib\LoginUser;
use App\Lib\Code\DelFlag;
use App\Lib\Code\CodePattern;
use Sluggable\Utility\Slug;
use ArrayObject;

class ExpenseBehavior extends Behavior
{
	public function expenseUpdate(Entity $entity)
	{
		$fields = ['modify_user', 'title_seo'];

		if ($entity->isNew()) {
			$fields = ['create_user', 'modify_user', 'del_flg', 'title_seo'];
		}

		$user = is_null(LoginUser::getLogin()) ? '0' : LoginUser::getLogin();

		$columnList = $this->_table->schema()->columns();
		if (!empty($columnList)) {
			foreach ($columnList as $column) {
				foreach ($fields as $field) {
					if ($field == $column) {
						if (($field == 'create_user') || ($field == 'modify_user')) {
							$entity->set($field, $user);
						}
						if ($field == 'del_flg') {
							$entity->set('del_flg', DelFlag::$ALIVE[CodePattern::$CODE]);
						}
						if ($field == 'title_seo') {
							$entity->set('title_seo', Slug::generate($entity->title));
						}
					}
				}
			}
		}
	}
	
	public function beforeSave(Event $event, EntityInterface $entity) 
	{
		$this->expenseUpdate($entity);
	}
	
// 	public function beforeMarshall(Event $event, ArrayObject $data, ArrayObject $options)
// 	{
// 		if (isset($data['date'])) {
			
// 		}
// 	}
}
?>