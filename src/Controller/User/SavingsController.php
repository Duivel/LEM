<?php
namespace App\Controller\User;

use App\Lib\Constants;
use Cake\ORM\TableRegistry;
use App\Lib\LoginUser;

class SavingsController extends UserAppController
{
	public function initialize()
	{
		parent::initialize();
	}
	
	private function _view()
	{
		parent::_move('Saving list', Constants::USER_LAYOUT, 'view');
		$this->AccessLog->writeLog();
	}
	
	public function view()
	{
		$this->LastAccess->setLastAccess();
		$savingTable = TableRegistry::get('Savings');
		$savings = $savingTable->findByUserId(LoginUser::getLogin(), FALSE);
		$this->set('savings', $savings);
		$this->_view();
	}
}