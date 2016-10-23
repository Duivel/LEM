<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use App\Lib\Code\DelFlag;
use App\Lib\Code\CodePattern;
use Cake\Core\App;

class SavingsTable extends AppTable
{
	public function initialize(array $config) 
	{
		parent::initialize($config);
		$this->table('monthly_savings');
		$this->primaryKey('saving_id');
		$this->alias('Savings');

		$this->addBehavior('TimeStamp', [
				'events' => [
						'Model.beforeSave' => [
								'created' => 'new',
								'modified' => 'always'
						]
				]
		]);
		
		$this->belongsTo('Users', [
				'className' => 'App\Model\Table\UsersTable',
				'foreignKey' => 'user_id',
				'joinType' => 'inner',
				'conditions' => ['Users.del_flg' => DelFlag::$ALIVE[CodePattern::$CODE]]
		]);
	}
	
	public function findAllByUser($user_id, $isHomePage = FALSE)
	{
		if ($isHomePage) {
			$options = [
					'conditions' => [
							parent::eq('Savings.user_id', $user_id),
							parent::eq('Savings.del_flg', DelFlag::$ALIVE[CodePattern::$CODE])
					],
					'fields' => ['Savings.month', 'Savings.user_id', 'Savings.expense', 'Savings.income', 'Savings.saving'],
					'order' => ['Savings.month' => 'desc'],
					'limit' => '6'
			];
		} else {
			$options = [
					'conditions' => [
							parent::eq('Savings.user_id', $user_id),
							parent::eq('Savings.del_flg', DelFlag::$ALIVE[CodePattern::$CODE])
					],
					'fields' => ['Savings.month', 'Savings.user_id', 'Savings.expense', 'Savings.income', 'Savings.saving'],
					'order' => ['Savings.month' => 'desc']
			];
		}
		
		$query = $this->find('all', $options);
		return $query->all();
	}
}