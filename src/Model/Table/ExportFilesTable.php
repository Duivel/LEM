<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use App\Lib\Code\DelFlag;
use App\Lib\Code\CodePattern;

class ExportFilesTable extends AppTable
{
	public function initialize(array $config) 
	{
		parent::initialize($config);
		$this->table('export_files');
		$this->primaryKey('export_id');
		$this->alias('ExportFiles');
		
		$this->addBehavior('TimeStamp', [
				'events' => [
						'Model.beforeSave' => [
								'created' => 'new'
						]
				]
		]);
		$this->addBehavior('ExportFile');
		
		$this->belongsTo('Users', [
				'className' => 'App\Model\Table\UsersTable',
				'foreignKey' => 'user_id',
				'joinType' => 'inner',
				'conditions' => ['Users.del_flg' => DelFlag::$ALIVE[CodePattern::$CODE]]
		]);
	}
	
	public function findAllByDelFlg()
	{
		$options = [
				'conditions' => [
						parent::eq('ExportFiles.del_flg', DelFlag::$ALIVE[CodePattern::$CODE])
				],
				'order' => ['ExportFiles.created' => 'DESC'],
				'fields' => ['ExportFiles.user_id', 'ExportFiles.export_type_id', 'ExportFiles.password', 'ExportFiles.status', 'ExportFiles.file_name', 'ExportFiles.export_id', 'ExportFiles.created', 'Users.user_name'],
		];
		$query = $this->find('all', $options);
		return $query;
	}
	
	public function findByUserId($user_id)
	{
		$options = [
				'conditions' => [
						parent::eq('ExportFiles.user_id', $user_id),
						parent::eq('ExportFiles.del_flg', DelFlag::$ALIVE[CodePattern::$CODE])
				],
				'order' => ['ExportFiles.created' => 'DESC'],
				'fields' => ['ExportFiles.user_id', 'ExportFiles.export_type_id', 'ExportFiles.password', 'ExportFiles.file_name', 'ExportFiles.status', 'ExportFiles.export_id', 'ExportFiles.created'],
		];
		$query = $this->find('all', $options);
		return $query;
	}
}
?>