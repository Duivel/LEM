<?php
namespace App\Controller\User;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Exception\InternalErrorException;
use App\Lib\Constants;
use App\Lib\Crypt;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use App\Lib\LoginUser;
use Cake\Log\Log;
use Cake\Core\Configure;
use Cake\View\Exception\MissingTemplateException;
use Cake\View\Exception\MissingLayoutException;

class UserAppController extends AppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Flash');
		$this->loadComponent('LastAccess');
		$this->loadComponent('AccessLog');
		$this->loadComponent('UserCookie', [
				'path' => '/LEM/user/',
				'domain' => 'localhost',
				'secure' => false,
				'key' => 'y70hHBxwlAtuJzC9NrLYWoo4aOif9chk',
				'httpOnly' => false,
				'encryption' => 'aes',
				'expires' => '+604800 seconds',
		]);
		$this->Session = $this->request->session();
	}
	
	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
		if (!$this->isLogin()) {
			$url = '/'.Constants::LOGIN_PREFIX;
			$url = Router::url($url, TRUE);
			$this->redirect($url);
		} 
	}

	protected function _move($pageTitle, $layout = NULL, $ctpFile = NULL)
	{
		try {
			if (!is_null($layout) && !empty($layout)) {
				$this->viewBuilder()->layout($layout);
			}
			$this->set('title_for_layout', $pageTitle);
			if (!is_null($ctpFile) && !empty($ctpFile)) {
				$this->render($ctpFile);
			}
			//write info log
			$this->AccessLog->writeLog();
		} catch (MissingTemplateException $e) {
			Log::error('Can\'t find template file: '.$e->getMessage());
			Log::error($e->getTraceAsString());
		} catch (MissingLayoutException $e) {
			throw new MissingLayoutException(__($e->getMessage()));
		}
		
	}
	
	private function _setSession($name, $value)
	{
		$this->Session->write($name, $value);
	}
	
	private function _setSessionNo($sessionNo)
	{
		$sessionNo = Crypt::customEncrypt($sessionNo, Configure::read(Crypt::SESSION_NO_KEY_NAME));
		$this->_setSession(Constants::SESSION_USER_NAME, $sessionNo);
	}
	
	private function _getSession($name)
	{
		return $this->Session->read($name);
	}
	
	private function _getSessionNo()
	{
		$value = $this->_getSession(Constants::SESSION_USER_NAME);
		return Crypt::customDecrypt($value, Configure::read(Crypt::SESSION_NO_KEY_NAME));
	}
	
	private function _makeSessionNo()
	{
		$sessionNo =  md5(rand(rand(), rand()));
		$sessionNo .= md5(rand(rand(), rand()));
		$userSessionTable = TableRegistry::get('UserSessions');
		$count = $userSessionTable->countSessionByValue($sessionNo);
		if ($count > 0) {
			$sessionNo = $this->_makeSessionNo();
		}
		return $sessionNo;
	}
	
	/**
	 * Set error message
	 * @param string $message
	 */
	protected function _setErrorMessage($message)
	{
		$this->Flash->error($message);
	}
	
	/**
	 * Set notice message
	 * @param unknown $message
	 */
	protected function _setSuccessMessage($message)
	{
		$this->Flash->success($message);
	}
	
	public function isLogin()
	{
		if (!$this->Session->check(Constants::SESSION_USER_NAME)) {
			return FALSE;
		}

		$oldSessionNo = $this->_getSessionNo();
		$userTable = TableRegistry::get('Users');
		$loginTable = TableRegistry::get('LoginHistories');
		$user = $userTable->findUserBySessionNo($oldSessionNo);
		if (is_null($user) || empty($user)) {
			return FALSE;
		}
		
		try {
			$this->set('loginUser', $user);
			$newSessionNo = $this->_makeSessionNo();
			$userSessionTable = TableRegistry::get('UserSessions');
			$userSessionTable->connection()->transactional(function() use($userSessionTable, $newSessionNo, $oldSessionNo, $user, $loginTable){
				$userSessionTable->findSessionNoForUpdate($newSessionNo);
				$userSessionTable->updateSessionNo($oldSessionNo, $newSessionNo);
				
				$userSessionTable->deleteExpireSession();
				$this->_setSessionNo($newSessionNo);
				$loginHistories = $loginTable->findByUserId(LoginUser::getLogin(), TRUE);
				$this->set('loginHistories', $loginHistories);
				LoginUser::setLogin($user->user_id);
			});
			return TRUE;
		} catch (InternalErrorException $ex) {
			Log::critical('Internal error occured: '.$ex);
			return FALSE;
		}
	}
}
?>