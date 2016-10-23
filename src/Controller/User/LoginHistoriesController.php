<?php
namespace App\Controller\User;

use App\Lib\Constants;

class LoginHistoriesController extends UserAppController
{
	public function initialize()
	{
		parent::initialize();
	}
	
	public function _view()
	{
		parent::_move('Login History List', Constants::USER_LAYOUT, 'view');
	}
	
	public function view()
	{
		
	}
}