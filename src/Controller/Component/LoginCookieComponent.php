<?php
namespace App\Controller\Component;

use Cake\Controller\Component\CookieComponent;
use App\Lib\Constants;

class LoginCookieComponent extends CookieComponent
{
	public function initialize(array $config)
	{
		parent::initialize($config);
	}
	
	public function writeCookie($user_id, $cookieNo)
	{
		$cookieValue = 'user_id='.$user_id.'&cookieNo='.$cookieNo;
		$this->write(Constants::COOKIE_LOGIN_ID, $cookieValue);
	}
	
	public function readCookie()
	{
		return $this->read(Constants::COOKIE_LOGIN_ID);
	}
	
	public function deleteCookie()
	{
		$this->delete(Constants::COOKIE_LOGIN_ID);
	}
	
	public function checkCookie()
	{
		return $this->check(Constants::COOKIE_LOGIN_ID);
	}
	
	
}
?>