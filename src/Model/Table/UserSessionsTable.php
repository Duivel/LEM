<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Datasource\ConnectionManager;
use App\Lib\Constants;

class UserSessionsTable extends AppTable
{
	public function initialize(array $config)
	{
		parent::initialize($config);
		$this->table('user_sessions');
		$this->primaryKey('id');
		$this->alias('UserSessions');
		
		$this->addBehavior('TimeStamp',[
				'events' => [
						'Model.beforeSave' => [
								'created' => 'new'
						]
				]
		]);
	}
	
	public function findSessionNoForUpdate($sessionNo)
	{
		$conn = ConnectionManager::get('default');
		$stmt = $conn->execute('Select * from user_sessions where id = ? for update', [$sessionNo]);
		return $stmt;
	}
	
	public function updateSessionNo($oldSessionNo, $newSessionNo)
	{
		$fields = [
				'id' => $newSessionNo,
				'limit_time' => strtotime(Constants::SESSION_VALID_SECOND. ' seconds'.date('Y-m-d H:i:s'))
		];
		$conditions = [
				'id' => $oldSessionNo
		];
		return $this->updateAll($fields, $conditions);
	}
	
	public function deleteExpireSession() {
		$conditions = [
				parent::le('limit_time', strtotime(date('Y-m-d H:i:s')))
		];
		return $this->deleteAll($conditions);
	}
	
	public function findBySessionNo($sessionNo) {
		$options = [
				'conditions' => [
						parent::eq('UserSessions.id', $sessionNo)
				]
		];
		$query = $this->find('all', $options);
		return $query->first();
	}
	
	public function countSessionByValue($sessionNo)
	{
		$options = [
				'conditions' => [
						parent::eq('UserSessions.id', $sessionNo)
				]
		];
		$query = $this->find('all', $options);
		return $query->count();
	}
}