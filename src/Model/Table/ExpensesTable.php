<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

use App\Lib\Code\DelFlag;
use App\Lib\ValidationUtil;
use App\Lib\Code\CodePattern;
use Cake\Datasource\ConnectionManager;
use App\Lib\Constants;
use App\Lib\Code\WithdrawType;

class ExpensesTable extends AppTable
{
	public function initialize(array $config)
	{
		parent::initialize($config);
		$this->table('living_expenses');
		$this->primaryKey('expense_id');
		$this->alias('Expenses');
		
		//Add Behaviors
		$this->addBehavior('TimeStamp', [
				'events' => [
						'Model.beforeSave' => [
								'created' => 'new',
								'modified' => 'always'
						]
				]
		]);
		$this->addBehavior('Expense');

		//Add associations
		$this->belongsTo('Users',[
				'className' => 'App\Model\Table\UsersTable',
				'foreignKey' => 'user_id',
				'joinType' => 'INNER',
				'conditions' => ['Users.del_flg' => DelFlag::$ALIVE[CodePattern::$CODE]]
		]);
		$this->belongsTo('ExpenseTypes',[
				'className' => 'App\Model\Table\ExpenseTypesTable',
				'foreignKey' => 'expense_type_id',
				'joinType' => 'INNER',
				'conditions' => ['ExpenseTypes.del_flg' => DelFlag::$ALIVE[CodePattern::$CODE]]
		]);
	}

	public function validationDefault(Validator $validator)
	{
		$validator
			->requirePresence('amount')
			->notBlank('amount', __('Please input amount of money'))
			->add('amount', [
					'isNumber' => [
							'rule' => ['custom', ValidationUtil::CHECK_DECIMAL_NUMBER],
							'message' => __('Please input a valid number!'),
							'last' => true
					]
			]);
		$validator 
			->requirePresence('title')
			->notBlank('title', __('Please input title!'))
			->add('title',[
					'maxLength' => [
							'rule' => ['maxLength', ValidationUtil::TITLE_MAX_LENGTH],
							'message' => __('Please input a valid title!'),
							'last' => true
					]
			]);
		$validator
			->requirePresence('description')
			->notBlank('description', __('Please input description!'))
			->add('description', [
					'maxLength' => [
							'rule' => ['maxLength', ValidationUtil::DESCRIPTION_MAX_LENGTH],
							'message' => __('Please input a valid description'),
							'last' => true
					]
			]);
		$validator
			->requirePresence('date')
			->notBlank('date')
			->add('date', [
					'date' => [
							'rule' => 'date',
							'message' => __('Please input a valid date!')
					]
			]);
		return $validator;
	}

	public function findAllByDelFlg($isMainMenu = false)
	{
		if ($isMainMenu) {
			$options = [
					'fields' => ['Expenses.expense_id', 'Expenses.user_id', 'Expenses.amount', 'Expenses.title', 'Expenses.description'
							,'Expenses.expense_type_id', 'Expenses.created', 'Expenses.date'],
					'conditions' => [
							parent::eq('Expenses.del_flg', DelFlag::$ALIVE[CodePattern::$CODE])
					],
					'order' => [
							'Expenses.date' => 'DESC'
					],
					'limit' => Constants::MAIN_MENU_EXPENSE_LIST_COUNT,
			];
			
			$query = $this->find('all', $options)->contain(['Users', 'ExpenseTypes']);
		} else {
			$options = [
					'fields' => ['Expenses.expense_id', 'Expenses.user_id', 'Expenses.amount', 'Expenses.title', 'Expenses.description'
							,'Expenses.expense_type_id', 'Expenses.created', 'Expenses.date', 'Users.user_name', 'ExpenseTypes.expense_type_name'],
					'conditions' => [
							parent::eq('Expenses.del_flg', DelFlag::$ALIVE[CodePattern::$CODE])
					],
					'order' => [
							'Expenses.date' => 'DESC'
					]
			];
			
			$query = $this->find('all', $options)->contain(['Users', 'ExpenseTypes']);
		}
		return $query;
	}
	
	public function search($data)
	{
		$options = [
				'conditions' => [
						parent::likeContain('Expenses.title', $data['title']),
						parent::eq('Expenses.del_flg', DelFlag::$ALIVE[CodePattern::$CODE]),
						parent::ge('Expenses.amount', $data['amountFrom']),
						parent::le('Expenses.amount', $data['amountTo']),
						parent::ge('Expenses.date', $data['dateFrom']),
						parent::le('Expenses.date', $data['dateTo']),
						parent::eq('Expenses.user_id', $data['user_id']),
						parent::eq('Expenses.expense_type_id', $data['expense_type_id']),
						parent::eq('Expenses.spend_type', $data['spend_type'])
				],
				'order' => [
						'Expenses.date' => 'DESC'
				]
		];
		$query = $this->find('all', $options)->contain(['Users', 'ExpenseTypes']);
		return $query;
	}

	public function findAllByPeriod($fromDate, $toDate)
	{
		$options = [
				'conditions' => [
						parent::eq('Expenses.del_flg', DelFlag::$ALIVE[CodePattern::$CODE]),
						parent::ge('Expenses.date', $fromDate),
						parent::le('Expenses.date', $toDate)
				],
				'order' => [
						'Expenses.created' => 'DESC'
				]
		];
		$query = $this->find('all', $options)->contain(['Users', 'ExpenseTypes']);
		return $query;
	}

	public function findByExpenseId($expense_id)
	{
		$options = [
				'fields' => ['Expenses.expense_id', 'Expenses.user_id', 'Expenses.amount', 'Expenses.title', 'Expenses.spend_type',
						'Expenses.description','Expenses.expense_type_id', 'Expenses.created', 'Expenses.date', 
						'Users.user_name', 'ExpenseTypes.expense_type_name','UserCreate.user_name', 'UserModify.user_name'
				],
				'conditions' => [
						parent::eq('Expenses.expense_id', $expense_id),
						parent::eq('Expenses.del_flg', DelFlag::$ALIVE[CodePattern::$CODE])
				],
				'join' => [
						'getCreateUser' => [
								'type' => 'inner',
								'table' => 'users',
								'alias' => 'UserCreate',
								'conditions' => [
										'Expenses.create_user = UserCreate.user_id',
										parent::eq('UserCreate.del_flg', DelFlag::$ALIVE[CodePattern::$CODE])
								]
						],
						'getModifyUser' => [
								'type' => 'inner',
								'table' => 'users',
								'alias' => 'UserModify',
								'conditions' => [
										'Expenses.modify_user = UserModify.user_id',
										parent::eq('UserModify.del_flg', DelFlag::$ALIVE[CodePattern::$CODE])
								]
						]
				]
		];
		$query = $this->find('all', $options)->contain(['Users', 'ExpenseTypes']);
		return $query->first();
	}
	
	
	public function findExpenseAndIncome($user_id)
	{
		$conn = ConnectionManager::get('default');
// 		$stmt = $conn->execute('
// 			select a.user_id, c.user_name, a.month, ifnull(a.amount,0) as expense_amount, ifnull(b.amount,0) as income_amount from 
// 			(select user_id, date_format(date, "%m/%Y") as month, sum(amount) as amount from living_expenses where user_id = :user_id and del_flg = :del_flg
// 				group by user_id, date_format(date, "%m/%Y")) a
// 			left join
// 			(select user_id, month, sum(amount) as amount from incomes where user_id = :user_id and del_flg = :del_flg
// 				group by user_id, month) b
// 			on (a.user_id = b.user_id and a.month = b.month)
// 			inner join
// 			(select user_id, user_name from users where user_id = :user_id and del_flg = :del_flg) c
// 			on (a.user_id = c.user_id)
// 			order by a.month desc
// 			limit 6', ['user_id' => $user_id, 'del_flg' => DelFlag::$ALIVE[CodePattern::$CODE]])->fetchAll('assoc');
		$stmt = $conn->execute('
			select month, ifnull(expense,0) as expense_amount, ifnull(income, 0) as income_amount, ifnull(saving, 0) as saving_amount  from monthly_savings
			where user_id = :user_id and del_flg = :del_flg order by month desc limit 6', 
			['user_id' => $user_id, 'del_flg' => DelFlag::$ALIVE[CodePattern::$CODE]])->fetchAll('assoc');
		return $stmt;
	}
	
	public function findMoneyInUserWallet($user_id, $from_date, $to_date)
	{
		$conn = ConnectionManager::get('default');
		$stmt = $conn->execute('select a.user_id, (a.withdraw_cash_amount-b.expense_cash_amount) as wallet_amount from
			(select user_id, sum(amount) as withdraw_cash_amount from withdraws where user_id = :user_id and date <= :to_date and date >= :from_date
			and withdraw_type_id = :withdraw_type_id and del_flg = :del_flg group by user_id) a 
			left join
			(select user_id, sum(amount) as expense_cash_amount from living_expenses where user_id = :user_id and date <= :to_date and date >= :from_date
			and spend_type = :withdraw_type_id group by user_id) b
			on a.user_id = b.user_id', 
				['user_id' => $user_id, 'del_flg' => DelFlag::$ALIVE[CodePattern::$CODE],'to_date' => $to_date, 'from_date' => $from_date,
				'withdraw_type_id' => WithdrawType::$CASH[CodePattern::$CODE]])->fetchall('assoc');
		return $stmt;
	}
	
	public function findUserExpenseStatus($user_id, $from_date, $to_date, $month)
	{
		$conn = ConnectionManager::get('default');
		$stmt = $conn->execute('select a.user_id, ifnull(income,0) as income, ifnull(expense, 0) as expense, ifnull(withdraw,0) as withdraw from 
			(select user_id, sum(amount) as expense from living_expenses where user_id = :user_id and date <= :to_date and date >= :from_date 
			and del_flg = :del_flg group by user_id) a left join 
			(select user_id, sum(amount) as withdraw from withdraws where user_id = :user_id and date <= :to_date and date >= :from_date and del_flg = :del_flg group by user_id) b
			on a.user_id = b.user_id left join
			(select user_id, amount as income from incomes where month = :month and del_flg = :del_flg group by user_id) c
			on a.user_id = c.user_id', ['user_id' => $user_id, 'del_flg' => DelFlag::$ALIVE[CodePattern::$CODE],
			'to_date' => $to_date, 'from_date' => $from_date, 'month' => $month])->fetchall('assoc');
		return $stmt;
	}
	
	public function findExpenseAmountForPieChart($user_id, $from_date, $to_date)
	{
		$conn = ConnectionManager::get('default');
		$stmt = $conn->execute('select b.expense_type_name, a.amount from
			(select user_id, expense_type_id, sum(amount) as amount from living_expenses where user_id = :user_id and date <= :to_date and date >= :from_date
			and del_flg = :del_flg group by user_id, expense_type_id) a
			left join expense_types b on a.expense_type_id = b.expense_type_id',
				['user_id' => $user_id, 'del_flg' => DelFlag::$ALIVE[CodePattern::$CODE],'to_date' => $to_date, 'from_date' => $from_date])
		->fetchall('assoc');
		return $stmt;
	}
	
	public function findSessionNoForUpdate($sessionNo)
	{
		$conn = ConnectionManager::get('default');
		$stmt = $conn->execute('Select * from user_sessions where id = ? for update', [$sessionNo]);
		return $stmt;
	}

	public function deleteByExpenseId($expense_id)
	{
		$fields = [
				'del_flg' => DelFlag::$DELETED[CodePattern::$CODE]
		];
		$conditions = [
				'expense_id' => $expense_id,
				'del_flg' => DelFlag::$ALIVE[CodePattern::$CODE]
		];
		return $this->updateAll($fields, $conditions);
	}
}
?>