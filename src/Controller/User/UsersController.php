<?php
namespace App\Controller\User;

use App\Lib\Constants;
use Cake\ORM\TableRegistry;
use App\Lib\LoginUser;
use Cake\Network\Exception\NotFoundException;
use Cake\Cache\Cache;

class UsersController extends UserAppController
{
	public function initialize()
	{
		parent::initialize();
	}
	
	private function _home()
	{
		$this->_move('Home', Constants::USER_LAYOUT, 'home');
		$this->AccessLog->writeLog();
	}
	
	private function _view()
	{
		$this->_move('User List', Constants::USER_LAYOUT, 'view');
		$this->AccessLog->writeLog();
	}
	
	private function _edit()
	{
		$this->_move('Add\'Edit User', Constants::USER_LAYOUT, 'edit');
		$this->AccessLog->writeLog();
	}

	public function home()
	{
		$to_day = date('Y-m-d');
		$last_day_of_month = date('Y-m-t');
		$first_day_of_month = date('Y-m-01');
		$this_month = mb_substr(date('m/Y'), 0, 7);

		$expenseTable = TableRegistry::get('Expenses');
		$incomeTable = TableRegistry::get('Incomes');
		$loginTable = TableRegistry::get('LoginHistories');
		$savingTable = TableRegistry::get('Savings');
		$withdrawTable = TableRegistry::get('Withdraws');
		
		$savings = $savingTable->findAllByUser(LoginUser::getLogin(), TRUE);
		$withdraws = $withdrawTable->findByUserId(LoginUser::getLogin(), TRUE);
		$pieDatas = $expenseTable->findExpenseAmountForPieChart(LoginUser::getLogin(), $first_day_of_month, $last_day_of_month);
		$walletMoney = $expenseTable->findMoneyInUserWallet(LoginUser::getLogin(), $first_day_of_month, $to_day);
		$expenseStatus = $expenseTable-> findUserExpenseStatus(LoginUser::getLogin(), $first_day_of_month, $to_day, $this_month);
		$expenses = $expenseTable->findAllByDelFlg(TRUE);
		$incomes = $incomeTable->findAllByDelFlg(TRUE);

		$expense = [];
		$income = [];
		$month = [];

		foreach ($savings as $saving) {
			array_push($expense, $saving->expense);
			array_push($income, $saving->income);
			array_push($month, $saving->month);
		}
		
		$expense_type_name = [];
		$expense_amount = [];
		foreach ($pieDatas as $pie) {
			array_push($expense_type_name, $pie['expense_type_name']);
			array_push($expense_amount, $pie['amount']);
		}

		$this->set('panelData', compact('expenses', 'incomes', 'savings', 'withdraws'));
		$this->set('pieChart', $pieDatas);
		$this->set('flowChart', compact('expense', 'income', 'month'));
		$this->set('pieChart', compact('expense_type_name', 'expense_amount'));
		$this->set('status', compact('walletMoney', 'expenseStatus'));
		$this->_home();
	}

	public function view()
	{
		$userTable = TableRegistry::get('Users');
		$users = $userTable->findAllByDelFlg();
		$this->set('users', $users);
		$this->_view();
	}

	public function edit($user_id = NULL)
	{
		$userTable = TableRegistry::get('Users');
		$succMsg = 'A new user was created successfully!';
		$failMsg = 'Unable to create a new user!';
		
		if (is_null($user_id) || empty($user_id)) {
			$user = $userTable->newEntity();
		} else {
			$user = $userTable->findByUserId($user_id);
			if (empty($user)) {
				throw new NotFoundException(__('Oops! Seems this user doesn\'t exist!'));
			}
			if (isset($user->password)) {
				unset($user->password);
			}
			$user->birthday = $user->displayBirthdayInView();
			$succMsg = 'An user was edited successfully!';
			$failMsg = 'Unable to edit this user!';
		}
		$this->set('user', $user);
		
		if ($this->request->is(['put', 'post'])) {
			$data = $this->request->data;
			$this->AccessLog->writeLog($data);
			if (is_null($data['password']) || empty($data['password'])) {
				unset($data['password']);
			}
			$data['birthday'] = \DateTime::createFromFormat('d/m/Y', $data['birthday'])->format('Y-m-d');
			$user = $userTable->patchEntity($user, $data);
			if ($userTable->save($user)) {
				Cache::delete(Constants::CACHE_USER_ALL, 'long');
				parent::_setSuccessMessage($succMsg);
				$this->redirect(['controller' => 'Users', 'action' => 'view']);
				return;
			} else {
				$this->set('user', $user);
				parent::_setErrorMessage($failMsg);
			}
		}
		$this->_edit();
	}
}
?>