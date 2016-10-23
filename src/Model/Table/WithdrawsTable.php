<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use App\Lib\Code\DelFlag;
use App\Lib\Code\CodePattern;
use Cake\Validation\Validator;
use App\Lib\ValidationUtil;

class WithdrawsTable extends AppTable
{
	public function initialize(array $config)
	{
		parent::initialize($config);
		$this->table('withdraws');
		$this->primaryKey('withdraw_id');
		$this->alias('Withdraws');
		
		$this->addBehavior('TimeStamp', [
				'events' => [
						'Model.beforeSave' => [
								'created' => 'new',
								'modified' => 'always'
						]
				]
		]);
		$this->addBehavior('Withdraw');
		
		$this->belongsTo('Users', [
				'className' => 'App\Model\Table\UsersTable',
				'foreignKey' => 'user_id',
				'innerType' => 'inner',
				'conditions' => ['Users.del_flg' => DelFlag::$ALIVE[CodePattern::$CODE]]
		]);
	}
	
	public function validationDefault(Validator $validator)
	{
		$validator->requirePresence('title')
			->notBlank('title', __('Title can\'t be blank!'))
			->add('title', [
					'maxLength' => [
							'rule' => ['maxLength', ValidationUtil::WITHDRAW_TITLE_MAX_LENGTH],
							'message' => __('Title can\'t be over '.ValidationUtil::WITHDRAW_TITLE_MAX_LENGTH. 'characters!')
					]
			]);
		$validator->requirePresence('description')
			->notBlank('description', __('Description can\'t be blank!'))
			->add('description', [
					'maxLength' => [
							'rule' => ['maxLength', ValidationUtil::WITHDRAW_DESCRIPTION_MAX_LENGTH],
							'message' => __('Description can\'t be over '.ValidationUtil::WITHDRAW_DESCRIPTION_MAX_LENGTH. ' characters')
					]
			]);
		$validator->requirePresence('amount')
			->notBlank('amount', __('Amount can\t be blank!'))
			->add('amount', [
					'isNumber' => [
							'rule' => ['custom', ValidationUtil::CHECK_DECIMAL_NUMBER],
							'message' => __('Please input a valid number!')
					]
			]);
		$validator->requirePresence('date')
			->notBlank('date', __('Date can\'t be blank!'))
			->add('date', [
					'isDate' => [
							'rule' => 'date',
							'message' => 'Please input a valid date!'
					]
			]);
		return $validator;
	}

	public function findAllByDelFlg()
	{
		$options = [
				'conditions' => [
						parent::eq('Withdraws.del_flg', DelFlag::$ALIVE[CodePattern::$CODE])
				],
				'order' => [
						'Withdraws.date' => 'desc'
				]
		];
		return $this->find('all', $options)->contain('Users');
	}
	
	public function findByUserId($user_id, $isHomepage = FALSE)
	{
		if ($isHomepage) {
			$options = [
					'conditions' => [
							parent::eq('Withdraws.user_id', $user_id),
							parent::eq('Withdraws.del_flg', DelFlag::$ALIVE[CodePattern::$CODE])
					],
					'order' => ['Withdraws.date' => 'desc'],
					'limit' => 6
			];
		} else {
			$options = [
					'conditions' => [
							parent::eq('Withdraws.user_id', $user_id),
							parent::eq('Withdraws.del_flg', DelFlag::$ALIVE[CodePattern::$CODE])
					],
					'order' => ['Withdraws.date' => 'desc'],
			];
		}
		$query = $this->find('all', $options);
		return $query->all();
		
	}

	public function findByWithdrawId($withdraw_id)
	{
		$options = [
				'conditions' => [
						parent::eq('Withdraws.withdraw_id', $withdraw_id),
						parent::eq('Withdraws.del_flg', DelFlag::$ALIVE[CodePattern::$CODE])
				]
		];
		$query = $this->find('all', $options)->contain('Users');
		return $query->first();
	}

	public function deleteByWithdrawId($withdraw_id)
	{
		if (!empty($withdraw_id)) {
			$fields= [
					'del_flg' => DelFlag::$DELETED[CodePattern::$CODE]
			];
			$conditions = [
					'del_flg' => DelFlag::$ALIVE[CodePattern::$CODE],
					'withdraw_id' => $withdraw_id
			];
			return $this->updateAll($fields, $conditions);
		}
	}
}