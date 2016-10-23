<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use App\Lib\Code\DelFlag;
use App\Lib\Code\CodePattern;

class UserCookiesTable extends AppTable
{
	public function initialize(array $config)
	{
		parent::initialize($config);
		$this->table('user_cookies');
		$this->alias('UserCookies');
		$this->primaryKey('cookie_id');
		
		$this->addBehavior('TimeStamp', [
				'events' => [
						'Model.beforeSave' => [
								'created' => 'new'
						]
				]
		]);
// 		$this->addBehavior('UserCookie');
		
// 		$this->belongsTo('Users', [
// 				'className' => 'App\Model\Table\UsersTable',
// 				'foreignKey' => 'user_id',
// 				'join' => 'innerType',
// 				'conditions' => ['Users.del_flg' => DelFlag::$ALIVE[CodePattern::$CODE]]
// 		]);
	}
	
	public function findByUserIdAndCookieId($user_id, $cookie_id)
	{
		$options = [
				'conditions' => [
						parent::eq('UserCookies.user_id', $user_id),
						parent::eq('UserCookies.cookie_id', $cookie_id),
						parent::ge('UserCookies.limit_time', strtotime(date('Y-m-d H:i:s')))
				]
		];
		$query = $this->find('all', $options);
		return $query->first();
	}
	
	public function deleteExpireCookie()
	{
		$conditions = [
				parent::le('UserCookies.limit_time', strtotime(date('Y-m-d H:i:s')))
		];
		return $this->deleteAll($conditions);
	}
	
	public function deleteCookieByUserId($user_id)
	{
		$conditions = [
				parent::eq('UserCookies.user_id', $user_id)
		];
		return $this->deleteAll($conditions);
	}
}