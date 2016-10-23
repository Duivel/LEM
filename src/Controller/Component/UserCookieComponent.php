<?php
namespace App\Controller\Component;

use Cake\Controller\Component\CookieComponent;
use App\Lib\Constants;

class UserCookieComponent extends CookieComponent
{
// 	protected $_defaultConfig = [
// 			'path' => '/LEM/login/',
// 			'domain' => 'localhost',
// 			'secure' => false,
// 			'key' => 'y70hHBxwlAtuJzC9NrLYWoo4aOif9chk',
// 			'httpOnly' => false,
// 			'encryption' => 'aes',
// 			'expires' => '+604800 seconds',
// 	];
	
	
	
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