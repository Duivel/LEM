<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use App\Lib\Code\DelFlag;
use App\Lib\Code\CodePattern;
use App\Lib\Constants;

class LoginHistoriesTable extends AppTable
{
	public function initialize(array $config)
	{
		parent::initialize($config);
		$this->table('login_histories');
		$this->primaryKey('login_id');
		$this->alias('LoginHistories');
		
		$this->addBehavior('TimeStamp', [
				'events' => [
						'Model.beforeSave' => [
								'login_date' => 'new'
						]
				]
		]);
		$this->addBehavior('LoginHistory');
		
		$this->belongsTo('Users', [
				'className' => 'App\Model\Table\UsersTable',
				'foreignKey' => 'user_id',
				'joinType' => 'inner',
				'conditions' => ['Users.del_flg' => DelFlag::$ALIVE[CodePattern::$CODE]]
		]);
	}

	public function findByUserId($user_id, $isMainMenu = FALSE)
	{
		if ($isMainMenu) {
			$options = [
					'conditions' => [
							parent::eq('LoginHistories.user_id', $user_id),
							parent::eq('LoginHistories.del_flg', DelFlag::$ALIVE[CodePattern::$CODE])
					],
					'order' => [
							'LoginHistories.login_date' => 'DESC'
					],
					'limit' => Constants::MAIN_MENU_LOGIN_HISTORY_COUNT
			];
			$query = $this->find('all', $options);
			return $query->all();
		} else {
			$options = [
					'conditions' => [
							parent::eq('LoginHistories.user_id', $user_id),
							parent::eq('LoginHistories.del_flg', DelFlag::$ALIVE[CodePattern::$CODE])
					],
					'order' => [
							'LoginHistories.login_date' => 'DESC'
					]
			];
			$query = $this->find('all', $options)->contain(['Users']);
			return $query->all();
		}
	}
}
?>