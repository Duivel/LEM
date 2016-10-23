<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use App\Lib\Code\DelFlag;
use App\Lib\Code\CodePattern;
use Cake\Validation\Validator;
use App\Lib\ValidationUtil;
use App\Lib\Constants;

class IncomesTable extends AppTable
{
	public function initialize(array $config)
	{
		parent::initialize($config);
		$this->table('incomes');
		$this->primaryKey('income_id');
		$this->alias('Incomes');
		
		//Add Behaviors
		$this->addBehavior('TimeStamp', [
				'events' => [
						'Model.beforeSave' => [
								'created' => 'new',
								'modified' => 'always'
						]
				]
		]);
		$this->addBehavior('Income');

		//Add associations
		$this->belongsTo('Users', [
				'className' => 'App\Model\Table\UsersTable',
				'foreignKey' => 'user_id',
				'joinType' => 'INNER',
				'conditions' => ['Users.del_flg' => DelFlag::$ALIVE[CodePattern::$CODE]]
		]);
		$this->belongsTo('IncomeTypes', [
				'className' => 'App\Model\Table\IncomeTypesTable',
				'foreignKey' => 'income_type_id',
				'joinType' => 'inner',
				'conditions' => ['IncomeTypes.del_flg' => DelFlag::$ALIVE[CodePattern::$CODE]]
		]);
	}
	
	public function validationDefault(Validator $validator)
	{
		$validator->requirePresence('month')
			->notBlank('month', __('Please input your month!'));
		$validator->requirePresence('amount')
			->notBlank('amount', __('Please input amount!'))
			->add('amount', [
					'isNumber' => [
							'rule' => ['custom', ValidationUtil::CHECK_DECIMAL_NUMBER],
							'message' => 'Please input a valid number',
							'last' => true
					]
			]);
		$validator->requirePresence('note')
			->notBlank('note', __('Please input!'))
			->add('note', [
					'maxLength' => [
							'rule' => ['maxLength', ValidationUtil::INCOME_NOTE_MAX_LENGTH],
							'message' => __('Note is too long!'),
							'last' => true
					]
			]);
		$validator->requirePresence('user_id')
			->add('user_id', [
					'isUnique' => [
							'rule' => 'isUniqueMonthAndUser',
							'message' => __('Please choose another month and user'),
							'provider' => 'table',
							'last' => TRUE
					]
			]);
		return $validator;
	}

	public function findAllByDelFlg($isMainMenu = FALSE)
	{
		if ($isMainMenu) {
			$options = [
					'fields' => ['Incomes.income_id','Incomes.month','Incomes.amount', 'Incomes.created', 'Incomes.income_type_id',
							'Incomes.user_id', 'Incomes.note'],
					'conditions' => [
							parent::eq('Incomes.del_flg', DelFlag::$ALIVE[CodePattern::$CODE])
					],
					'order' => ['Incomes.month' => 'DESC'],
					'limit' => Constants::MAIN_MENU_INCOME_LIST_COUNT
			];
			$query = $this->find('all', $options);
		} else {
			$options = [
					'fields' => ['Incomes.income_id','Incomes.month','Incomes.amount', 'Incomes.created', 'Incomes.income_type_id',
							'Incomes.user_id', 'Incomes.note', 'Users.user_name', 'IncomeTypes.income_type_name'],
					'conditions' => [
							parent::eq('Incomes.del_flg', DelFlag::$ALIVE[CodePattern::$CODE])
					],
					'order' => ['Incomes.month' => 'DESC']
			];
			$query = $this->find('all', $options)->contain(['Users', 'IncomeTypes']);
		}
		
		return $query->all();
	}
	
	public function isUniqueMonthAndUser($value, array $context)
	{
		$income_id = $context['data']['income_id'];
		$month = $context['data']['month'];
		$user_id = $context['data']['user_id'];
		$type = $context['data']['income_type_id'];
		$options = [
				'conditions' => [
						parent::eq('Incomes.user_id', $user_id),
						parent::eq('Incomes.month', $month),
						parent::eq('Incomes.income_type_id', $type),
						parent::eq('Incomes.del_flg', DelFlag::$ALIVE[CodePattern::$CODE])
				]
		];
		$query = $this->find('all', $options);

		if ($query->count() > 0) {
			$income = $query->first();
			if ($income->income_id == $income_id)
			{
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return TRUE;
		}
	}

	public function findByIncomeId($income_id)
	{
		$options = [
				'conditions' => [
						parent::eq('Incomes.income_id', $income_id),
						parent::eq('Incomes.del_flg', DelFlag::$ALIVE[CodePattern::$CODE])
				]
		];
		$query = $this->find('all', $options)->contain(['Users']);;
		return $query->first();
	}

	public function deleteByIncomeId($income_id)
	{
		$conditions = [
				'Incomes.income_id' => $income_id,
				'Incomes.del_flg' => DelFlag::$ALIVE[CodePattern::$CODE]
		];
		$fields = [
				'Incomes.del_flg' => DelFlag::$DELETED[CodePattern::$CODE]
		];
		return $this->updateAll($fields, $conditions);
	}
}