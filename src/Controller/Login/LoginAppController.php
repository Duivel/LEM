<?php
namespace App\Controller\Login;

use App\Controller\AppController;
use App\Lib\Constants;
use App\Lib\Crypt;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

class LoginAppController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Flash');
		$this->loadComponent('LastAccess');
// 		$this->loadComponent('Cookie');
		$this->loadComponent('LoginCookie', [
				'path' => '/LEM/',
				'domain' => 'localhost',
				'secure' => false,
				'key' => 'y70hHBxwlAtuJzC9NrLYWoo4aOif9chk',
				'httpOnly' => false,
				'encryption' => 'aes',
				'expires' => '+604800 seconds',
		]);
		$this->Session = $this->request->session();
	}
	
	protected function _move($title, $layout = null, $ctpFile = null)
	{
		if (is_null($layout) || empty($layout)) {
		
		} else {
			$this->viewBuilder()->layout($layout);
		}
		$this->set('title_for_layout', $title);
		if (!is_null($ctpFile)) {
			$this->render($ctpFile);
		}
		return;
	}
	
	protected function _setErrorMessage($message)
	{
		$this->Flash->error($message);
	}
	
	private function _setSession($name, $value)
	{
		$this->Session->write($name, $value);
	}
	
	protected function _setSessionNo($sessionNo)
	{
		$value = Crypt::customEncrypt($sessionNo, Configure::read(Crypt::SESSION_NO_KEY_NAME));
		$this->_setSession(Constants::SESSION_USER_NAME, $value);
	}
	
	private function _getSession($name)
	{
		return $this->Session->read($name);
	}
	
	protected function _getSessionNo()
	{
		$value = $this->_getSession(Constants::SESSION_USER_NAME);
		return Crypt::customDecrypt($value, Configure::read(Crypt::SESSION_NO_KEY_NAME));
	}

	protected function _makeSessionNo()
	{
		$sessionNo = md5(rand(rand(),rand()));
		$sessionNo .= md5(rand(rand(),rand()));
		$userSessionTable = TableRegistry::get('UserSessions');
		$count = $userSessionTable->countSessionByValue($sessionNo);
		if ($count > 0) {
			$sessionNo = $this->makeSessionNo();
		}
		return $sessionNo;
	}

	protected function deleteSession($name)
	{
		$this->Session->delete($name);
	}
	
}