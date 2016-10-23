<?php
namespace App\Model\Table;

use Cake\ORM\Table;
//use Cake\Form\Schema;
use Cake\Database\Schema\Table as Schema;

use App\Lib\Code\DelFlag;
use App\Lib\Code\CodePattern;
use Cake\Validation\Validator;
use App\Lib\ValidationUtil;
use App\Lib\Code\UserStatus;
use App\Lib\Constants;

class UsersTable extends AppTable
{
	public function initialize(Array $config)
	{
		parent::initialize($config);
		$this->table('users');
		$this->primaryKey('user_id');
		$this->alias('Users');
		
		$this->addBehavior('TimeStamp', [
				'events' => [
						'Model.beforeSave' => [
								'created' => 'new',
								'modified' => 'always'
						]
				]
		]);
		$this->addBehavior('User');
		
		//Add Asscociations
		$this->hasMany('Expenses', [
				'className' => 'Expenses',
				'foreignKey' => 'user_id',
				'joinType' => 'INNER',
				'conditions' => ['del_flg' => DelFlag::$ALIVE[CodePattern::$CODE]]
		]);
	}
	
	public function validationDefault(Validator $validator)
	{
		$validator->requirePresence('user_name')
			->notBlank('user_name', __('Please input user name!'))
			->add('user_name', 'maxLength', [
					'rule' => ['maxLength', ValidationUtil::USER_NAME_MAX_LENGTH],
					'message' => __('You can\'t input more than '.ValidationUtil::USER_NAME_MAX_LENGTH . ' characters')
			]);
// 		$validator->requirePresence('password', 'create')
// 			->notBlank('password', __('Please input password'), 'create')
// 			->add('password', 'length', [
// 					'rule' => ['lengthBetween', ValidationUtil::USER_PASSWORD_MIN_LENGTH, ValidationUtil::USER_PASSWORD_MAX_LENGTH],
// 					'message' => __('Password\'s length must be greater than {App\Lib\ValidationUtil::USER_PASSWORD_MIN_LENGTH} or less than {App\Lib\ValidationUtil::USER_PASSWORD_MAX_LENGTH} characters'),
// 					'on' => 'create'
// 			]);
		$validator->requirePresence('email', ['create'])
			->notBlank('email', __('Please input email'), 'create')
			->add('email', 'validEmail', [
					'rule' => 'email',
					'message' => 'Email is not valid!',
					'when' => 'create'
			]);
		$validator->requirePresence('birthday')
			->notBlank('birthday', __('Please input birthday'))
			->add('birthday', 'validDate', [
					'rule' => 'date',
					'message' => 'Please input a valid date',
					'last' => TRUE
			]);
		return $validator;
	}

	/**
	 * Always encrypt password value automatically
	 * {@inheritDoc}
	 * @see \Cake\ORM\Table::_initializeSchema()
	 */
	public function _initializeSchema(Schema $table)
	{
		$table->columnType('password', 'crypted');
		return $table;
	}

	public function login($email, $password) 
	{
		$options = [
				'conditions' => [
						parent::eq('Users.email', $email),
						parent::eq('Users.password', $password),
						parent::eq('Users.del_flg', DelFlag::$ALIVE[CodePattern::$CODE])
				],
				'fields' => ['Users.email', 'Users.user_name', 'Users.status', 'Users.user_id', 'Users.locked_limit_time', 'Users.log_attempts']
		];
		$query = $this->find('all', $options);
		return $query->first();
	}
	
	public function findUserByEmail($email)
	{
		$options = [
				'conditions' => [
						parent::eq('Users.email', $email),
						parent::eq('Users.del_flg', DelFlag::$ALIVE[CodePattern::$CODE])
				],
				'fields' => ['Users.email', 'Users.status', 'Users.log_attempts', 'Users.user_id']
		];
		$query = $this->find('all', $options);
		return $query->first();
	}

	public function findAllByDelFlg()
	{
		$options = [
				'conditions' => [
						parent::eq('Users.del_flg', DelFlag::$ALIVE[CodePattern::$CODE])
				],
				'order' => [
						'Users.created' => 'DESC'
				]
		];
		$query = $this->find('all', $options);
		$query->cache(Constants::CACHE_USER_ALL, 'long');
		return $query->all();
	}

	public function findUserBySessionNo($sessionNo) {
		if (empty($sessionNo)) {
			return NULL;
		}
		$options = [
				'fields' => ['Users.user_id', 'Users.user_name'],
				'conditions' => [
						parent::eq('Users.del_flg', DelFlag::$ALIVE[CodePattern::$CODE])
				],
				'join' => [
						[
								'type' => 'inner',
								'table' => 'user_sessions',
								'alias' => 'UserSession',
								'conditions' => [
										'Users.user_id = UserSession.user_id',
										parent::eq('UserSession.id', $sessionNo),
										parent::ge('UserSession.limit_time', strtotime(date('Y-m-d H:i:s')))
								]
						]
				]
		];
		$query = $this->find('all', $options);
		return $query->first();
	}
	
	public function updateUserStatus($user_id, $status) {
		$conditions = [
				'user_id' => $user_id,
				'del_flg' => DelFlag::$ALIVE[CodePattern::$CODE]
		];
		if ($status == UserStatus::$LOCKED[CodePattern::$CODE]) {
			$fields = [
					'status' => UserStatus::$LOCKED[CodePattern::$CODE],
					'locked_limit_time' => strtotime(Constants::USER_LOCKED_SECOND. ' seconds '.date('Y-m-d H:i:s'))
			];
		} else {
			$fields = [
					'status' => $status
			];
		}
		return $this->updateAll($fields, $conditions);
	}
	
	public function updateUserLogAttempt($user_id, $attempt)
	{
		$conditions = [
				'user_id' => $user_id,
				'del_flg' => DelFlag::$ALIVE[CodePattern::$CODE]
		];
		$fields = [
				'log_attempts' => $attempt
		];
		return $this->updateAll($fields, $conditions);
	}
	
	public function findByUserId($user_id) 
	{
		$options = [
				'conditions' => [
						parent::eq('Users.user_id', $user_id),
						parent::eq('Users.del_flg', DelFlag::$ALIVE[CodePattern::$CODE])
				]
		];
		$query = $this->find('all', $options);
		return $query->first();
	}
}
?>
