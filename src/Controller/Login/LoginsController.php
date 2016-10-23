<?php
namespace App\Controller\Login;
use App\Controller\Login\LoginAppController;
use App\Lib\Constants;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Network\Exception\ForbiddenException;
use App\Lib\Code\CookieType;
use App\Lib\Code\CodePattern;
use App\Lib\Crypt;
use App\Lib\Util;
use App\Lib\LoginUser;
use Cake\Log\Log;
use App\Lib\Code\UserStatus;

class LoginsController extends LoginAppController
{
	private function _login() 
	{
		parent::_move('Please login', Constants::LOGIN_LAYOUT, 'login');
	}
	
	public function login() 
	{
		//Check auto login 
		$this->_autoLogin();

		if ($this->request->is('post')) {
			$email = $this->request->data['email'];
			$password = $this->request->data['password'];
			$rememberMe = $this->request->data['rememberMe'];
			//debug($rememberMe);exit();
			$userTable = TableRegistry::get('Users');

			if (empty($email) || empty($password)) {
				throw ForbiddenException('Email or Password can\'t be blank!');
				return;
			}
			
			$user = $userTable->findUserByEmail($email);
			if (empty($user)) {
				parent::_setErrorMessage(__('This user doesn\'t exist!'));
				$this->redirect(['controller' => 'Logins', 'action' => 'login']);
				return;
			}
			
			$logAttempt = $user->log_attempts;
			$user_id = $user->user_id;
			
			

			$user = $userTable->login($email, $password);
			if (is_null($user) || empty($user)) {
				$logAttempt++;
				if ($logAttempt <= Constants::USER_LOCKED_ATTEMPT) {
					$userTable->updateUserLogAttempt($user_id, $logAttempt);
				}
				parent::_setErrorMessage('User and password are not correct!');
				if ($logAttempt == Constants::USER_LOCKED_ATTEMPT) {
					$userTable->updateUserStatus($user_id, UserStatus::$LOCKED[CodePattern::$CODE]);
					parent::_setErrorMessage(__('Your user has been locked! Please try to log in again after 10 minutes!'));
				}
				$this->redirect(['controller' => 'Logins', 'action' => 'login']);
				return;
			}

			switch ($user->status) {
				case UserStatus::$LOCKED[CodePattern::$CODE]:
					if ($user->locked_limit_time > strtotime(date('Y-m-d H:i:s'))) {
						parent::_setErrorMessage('Your user has been locked!');
						$this->redirect(['controller' => 'Logins', 'action' => 'login']);
						return;
					} else {
						$userTable->updateUserStatus($user_id, UserStatus::$NORMAL[CodePattern::$CODE]);
						$userTable->updateUserLogAttempt($user_id, 0);
					}
					break;
				case UserStatus::$UNACTIVATED[CodePattern::$CODE]:
					parent::_setErrorMessage('Your user hasn\'t been activated!');
					$this->redirect(['controller' => 'Logins', 'action' => 'login']);
					return;
					break;
				case UserStatus::$RESET_PASSWORD[CodePattern::$CODE]:
					parent::_setErrorMessage('You have to change your password!');
					$this->redirect(['controller' => 'Logins', 'action' => 'changePassword']);
					return;
					break;
				case UserStatus::$NORMAL[CodePattern::$CODE]:
					if ($user->log_attempts > 0) {
						$userTable->updateUserLogAttempt($user_id, 0);
					}
					break;
			}

			if ($rememberMe == Constants::CHECKBOX_ON) {
				//set cookie
				unset($this->request->data['rememberMe']);
				$this->LoginCookie->deleteCookie();
				$cookieTable = TableRegistry::get('UserCookies');
				$cookie = $cookieTable->newEntity();
				$cookieNo = Util::stringGenerator('login_cookie');
				$cookie->user_id = $user->user_id;
				$cookie->cookie_id = Crypt::customHash($cookieNo);
				$cookie->cookie_type_id = CookieType::$LOGIN[CodePattern::$CODE];
				$cookie->limit_time = strtotime(Constants::COOKIE_VALID_SECOND. 'seconds '.date('Y-m-d H:i:s'));
				if ($cookieTable->save($cookie)) {
					$this->LoginCookie->writeCookie($user->user_id, $cookieNo);
				}
			}

			if ($this->_saveSession($user->user_id)) {
				//Save login history
				$loginTable = TableRegistry::get('LoginHistories');
				$login = $loginTable->newEntity();
				$login->user_id = $user->user_id;
				$login->IP_Address = $this->request->clientIp();
				$loginTable->save($login);
				
				$url = $this->LastAccess->getLastAccess();
				if (is_null($url) || empty($url)) {
					$url = '/'.Constants::USER_PREFIX.'/';
					$url = Router::url($url, TRUE);
				}
				$this->redirect($url);
			}
		}
		$this->_login();
	}

	public function logout()
	{
		$this->deleteSession(Constants::SESSION_USER_NAME);
		$this->deleteSession(Constants::SESSION_LAST_ACCESS);
		$this->deleteSession(Constants::SESSION_EXPENSE_SEARCH_CONDITIONS);
		$this->deleteSession(Constants::SESSION_INCOME_SEARCH_CONDITIONS);
		$cookieTable = TableRegistry::get('UserCookies');
		$cookieTable->deleteCookieByUserId(LoginUser::getLogin());
		$this->LoginCookie->deleteCookie();
		$this->redirect('/'.Constants::LOGIN_PREFIX);
	}

	private function _autoLogin()
	{
		$cookieValue = $this->LoginCookie->readCookie();
		if (empty($cookieValue)) {
			return;
		}
		//Get user_id and cookieNo(raw value)
		parse_str($cookieValue);
		$cookieNo = Crypt::customHash($cookieNo);
		$cookieTable = TableRegistry::get('UserCookies');

		$cookie = $cookieTable->findByUserIdAndCookieId($user_id, $cookieNo);
		if (!empty($cookie)) {
		if ($this->_saveSession($user_id)) {
				$url = $this->LastAccess->getLastAccess();
				if (is_null($url) || empty($url)) {
					$url = '/'.Constants::USER_PREFIX.'/';
					$url = Router::url($url, TRUE);
				}
				$this->redirect($url);
			}
		} 
		return;
	}

	private function _saveSession($user_id)
	{
		//Save Session for login users
		$sessionNo = $this->_makeSessionNo();
		$userSessionTable = TableRegistry::get('UserSessions');
		$userSession = $userSessionTable->newEntity();
		$userSession->id = $sessionNo;
		$userSession->user_id = $user_id;
		$userSession->limit_time = strtotime(Constants::SESSION_VALID_SECOND. 'seconds '.date('Y-m-d H:i:s'));

		try {
			$userSessionTable->connection()->transactional(function() use($userSessionTable, $userSession, $sessionNo){
				$userSessionTable->save($userSession);
				$this->_setSessionNo($sessionNo);
			});
			return TRUE;
		} catch (InternalErrorException $ex) {
			Log::critical('Internal error occured: '.$ex);
			return FALSE;
		}
	}
}
?>