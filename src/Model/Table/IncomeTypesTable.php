<?php
namespace App\Model\Table;

use Cake\ORM\Table;

use App\Lib\Code\DelFlag;
use App\Lib\Code\CodePattern;

class IncomeTypesTable extends AppTable
{
	public function initialize(array $config) 
	{
		parent::initialize($config);
		$this->table('income_types');
		$this->primaryKey('income_type_id');
		$this->alias('IncomeTypes');
		
		$this->addBehavior('TimeStamp', [
				'events' => [
						'Model.beforeSave' => [
								'created' => 'new',
								'modified' => 'always'
						]
				]
		]);
		$this->addBehavior('IncomeType');
		
		$this->addAssociations([
				'hasMany' => [
						'Incomes' => [
								'className' => 'App\Model\Table\IncomesTable',
								'foreignKey' => 'income_type_id',
								'joinType' => 'INNER',
								'conditions' => ['Incomes.del_flg' => DelFlag::$ALIVE[CodePattern::$CODE]]
						]
				],
		]);
	}
	
	public function findAllByDelFlg()
	{
		$options = [
				'conditions' => [
						parent::eq('IncomeTypes.del_flg', DelFlag::$ALIVE[CodePattern::$CODE])
				],
				'order' => [
						'IncomeTypes.income_type_id' => 'DESC'
				]
				
		];
		$query = $this->find('all', $options);
		return $query->all();
	}
}
?>