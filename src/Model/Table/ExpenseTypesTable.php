<?php
namespace App\Model\Table;

use Cake\ORM\Table;

use App\Lib\Code\DelFlag;
use App\Lib\Code\CodePattern;
use App\Lib\Constants;

class ExpenseTypesTable extends AppTable
{
	public function initialize(array $config)
	{
		parent::initialize($config);
		$this->table('expense_types');
		$this->primaryKey('expense_type_id');
		$this->alias('ExpenseTypes');

		$this->addBehavior('TimeStamp', [
				'events' => [
						'Model.beforeSave' => [
								'created' => 'new',
								'modified' => 'always'
						]
				]
		]);
		$this->addBehavior('ExpenseType');

		//add association
		$this->hasMany('Expenses', [
				'className' => 'App\Model\Table\ExpensesTable',
				'foreignKey'=> 'expense_type_id',
				'joinType' => 'INNER',
				'conditions' => ['Expenses.del_flg' => DelFlag::$ALIVE[CodePattern::$CODE]]
		]);
	}

	public function findAllByDelFlg()
	{
		$options = [
				'conditions' => [
						parent::eq('ExpenseTypes.del_flg', DelFlag::$ALIVE[CodePattern::$CODE])
				],
				'order' => [
						'ExpenseTypes.created' => 'DESC'
				]
		];
		$query = $this->find('all', $options);
		$query->cache(Constants::CACHE_EXPENSE_TYPE_ALL, 'long');
		return $query->all();
	}
}