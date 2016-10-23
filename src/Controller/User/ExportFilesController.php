<?php
namespace App\Controller\User;
use App\Lib\Constants;
use Cake\ORM\TableRegistry;
use App\Lib\LoginUser;

class ExportFilesController extends UserAppController
{
	public $paginate = ['limit' => Constants::EXPENSE_VIEW_LIMIT];
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Paginator');
		$this->loadComponent('AccessLog');
		$this->loadComponent('LastAccess');
	}
	
	private function _view()
	{
		parent::_move('Export List', Constants::USER_LAYOUT, 'view');
		$this->AccessLog->writeLog();
	}
	
	public function view()
	{
		$this->LastAccess->setLastAccess();
		$exportFileTable = TableRegistry::get('ExportFiles');
		$files = $exportFileTable->findByUserId(LoginUser::getLogin());
		$this->set('files', $files);
		$this->_view();
	}
	
	private function _download($export_id)
	{
		
	}
}
?>