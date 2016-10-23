<?php
namespace App\Controller\User;

use App\Lib\Constants;
use Cake\ORM\TableRegistry;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\BadRequestException;

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Cake\Core\Exception\Exception;
use App\Lib\Code\ExportFileType;
use App\Lib\Code\CodePattern;
use Cake\Log\Log;
use App\Lib\Util;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use App\Lib\LoginUser;


class ExpensesController extends UserAppController
{
	public $paginate = ['limit' => Constants::EXPENSE_VIEW_LIMIT];
	
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Paginator');
		$this->loadComponent('RbQueue');
		$this->Session = $this->request->session();
	}
	
	private function _view() 
	{
		parent::_move('Expense List', Constants::USER_LAYOUT, 'view');
		$this->Session->write(Constants::SESSION_URL_EXPENSE_VIEW, "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
	}
	
	public function _search()
	{
		parent::_move('Expense Search Result', Constants::USER_LAYOUT, 'search');
		$this->Session->write(Constants::SESSION_URL_EXPENSE_VIEW, "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
	}
	
	private function _add()
	{
		parent::_move('Add/Edit an expense', Constants::USER_LAYOUT, 'edit');
		
	}
	
	private function _detail() 
	{
		parent::_move('Expense Detail', Constants::USER_LAYOUT, 'detail');
	}
	
	public function view()
	{
		$this->LastAccess->setLastAccess();
		$fromDate = $this->request->query('from_day');
		$toDate = $this->request->query('to_day');
		$this->Session->delete(Constants::SESSION_EXPENSE_SEARCH_CONDITIONS);

		$expenseTable = TableRegistry::get('Expenses');
		
		if (is_null($fromDate) && is_null($toDate)) {
			$expenses = $expenseTable->findAllByDelFlg();
		} else {
			$expenses = $expenseTable->findAllByPeriod($fromDate, $toDate);
		}
		$this->set('expenses', $this->paginate($expenses));
		$this->_view();
	}
	
	public function search() {
		if (!empty($this->request->data)) {
			$data = $this->request->data;
			if (!empty($data['dateFrom'])) {
				$data['dateFrom'] = \DateTime::createFromFormat('d/m/Y', $data['dateFrom'])->format('Y-m-d');
			}
			if (!empty($data['dateTo'])) {
				$data['dateTo'] = \DateTime::createFromFormat('d/m/Y', $data['dateTo'])->format('Y-m-d');
			}
			//delete old sessions
			if ($this->Session->check(Constants::SESSION_EXPENSE_SEARCH_CONDITIONS)) {
				$this->Session->delete(Constants::SESSION_EXPENSE_SEARCH_CONDITIONS);
			}
			//Save new sessions
			$this->Session->write(Constants::SESSION_EXPENSE_SEARCH_CONDITIONS, $data);
		} else {
			if ($this->Session->check(Constants::SESSION_EXPENSE_SEARCH_CONDITIONS)) {
				//Read saved sessions
				$data = $this->Session->read(Constants::SESSION_EXPENSE_SEARCH_CONDITIONS);
				$this->request->data = $data;
			} else {
				$this->redirect(['controller' => 'Expenses', 'action' => 'view']);
			}
		}
		$expenseTable = TableRegistry::get('Expenses');
		$expenses = $expenseTable->search($data);
		$this->set('expenses', $this->paginate($expenses));
		$this->_search();
	}

	public function detail($seoUrl, $expense_id = NULL)
	{
		$this->LastAccess->setLastAccess();
		if (is_null($expense_id) || empty($expense_id)) {
			throw new NotFoundException(__('There is no exense'));
		}

		$this->Session->delete(Constants::SESSION_EXPENSE_SEARCH_CONDITIONS);
		$expenseTable = TableRegistry::get('Expenses');
		$expense = $expenseTable->findByExpenseId($expense_id);

		if (is_null($expense) || (count($expense) == 0)) {
			throw new NotFoundException(__('There is no exense'));
		}

		$this->set('expense', $expense);
		$this->_detail();
	}

	public function edit($expense_id = null)
	{
		$this->LastAccess->setLastAccess();
		$errorMessage = 'Can\'t add expense!';
		$successMessage = 'A new expense was added!';

		$expenseTable = TableRegistry::get('Expenses');
		
		if (empty($expense_id) || (mb_strlen($expense_id) == 0)) {
			$expense = $expenseTable->newEntity();
		} else {
			$expense = $expenseTable->findByExpenseId($expense_id);
			if (is_null($expense) || (count($expense) == 0)) {
				throw new NotFoundException(__('Can\'t find this expense', 400));
				return;
			}
			$errorMessage = 'Can\'t edit expense!';
			$successMessage = 'An expense was edited!';
			$expense->displayDateInEdit();
		}
		$this->set('expense', $expense);

		//When an user press the button
		if ($this->request->is(['post', 'put'])) {
			$data = $this->request->data;
			$this->AccessLog->writeLog($data);
			$data['date'] = \DateTime::createFromFormat('d/m/Y', $data['date'])->format('Y-m-d');

			$expense = $expenseTable->patchEntity($expense, $data);
			$insertRMQ = ($expense->dirty('amount')) || $expense->isNew();

			if ($expenseTable->save($expense)) {
				if ($insertRMQ) {
					$msg = ['user_id'=>$expense->user_id, 'month' => mb_substr($this->request->data['date'], 3, 7)];
					$this->AccessLog->writeLog($msg);
					$this->RbQueue->sendMessage(json_encode($msg, JSON_UNESCAPED_UNICODE), Constants::RABBITMQ_CALCULATE_SAVING_QUEUE_NAME, Constants::RABBITMQ_CALCULATE_SAVING_ROUTE_KEY);
				}
				parent::_setSuccessMessage($successMessage);
				if (!empty($data['Add_another_expense']) && ($data['Add_another_expense'] == Constants::CHECKBOX_ON)) {
					$this->redirect(['controller' => 'Expenses', 'action' => 'edit']);
				} else {
					$url = $this->Session->read(Constants::SESSION_URL_EXPENSE_VIEW);
					if (empty($url)) {
						$this->redirect(['controller' => 'Expenses', 'action' => 'view']);
					} else {
						$this->redirect($url);
					}
					return;
				}
			} else {
				parent::_setErrorMessage($errorMessage);
				$this->set('expense', $expense);
			}
		} 
		$this->_add();
	}

	public function delete($expense_id) {
		$this->AccessLog->writeLog();
		if (!empty($expense_id)) {
			$expenseTable = TableRegistry::get('Expenses');
			$expense = $expenseTable->findByExpenseId($expense_id);

			if ($expenseTable->deleteByExpenseId($expense_id) > 0) {
				$msg = ['user_id'=>$expense->user_id, 'month'=>mb_substr($expense->displayDateInView(), 3,7)];
				$this->AccessLog->writeLog($msg);
				$this->RbQueue->sendMessage(json_encode($msg, JSON_UNESCAPED_UNICODE), Constants::RABBITMQ_CALCULATE_SAVING_QUEUE_NAME, Constants::RABBITMQ_CALCULATE_SAVING_ROUTE_KEY);
			}
			parent::_setSuccessMessage('An expense was deleted successfully!');
			$this->redirect(['controller' => 'Expenses', 'action' => 'view']);
		} else {
			throw new BadRequestException('This action is really a bad request!');
		}
	}

	public function exportExcel() {
		$this->AccessLog->writeLog();
		if ($this->Session->check(Constants::SESSION_EXPENSE_SEARCH_CONDITIONS)) {
			$data = $this->Session->read(Constants::SESSION_EXPENSE_SEARCH_CONDITIONS);
			try {
				$exportTable = TableRegistry::get('ExportFiles');
				$export = $exportTable->newEntity();
				$export->export_type_id = ExportFileType::$EXPENSE[CodePattern::$CODE];
				$export->file_name = 'export_'.Util::makeRandomString(6).'.txt';
				$exportTable->connection()->transactional(function() use($exportTable, $export, $data){
					$exportTable->save($export);
					//save log file
					$this->AccessLog->writeLog($data);

					$amqpMsg = json_encode(array('export_id' => $export->export_id, 'file_name' => $export->file_name, 'type' => $export->export_type_id, 'conditions' => $data), JSON_UNESCAPED_UNICODE);
					$this->RbQueue->sendMessage($amqpMsg, Constants::RABBITMQ_EXPORT_EXPENSE_QUEUE_NAME, Constants::RABBITMQ_EXPORT_EXPENSE_ROUTE_KEY);

					parent::_setSuccessMessage(__('Your request is being processed! Please kindly wait for a while!'));
				});
			} catch (Exception $e) {
				Log::error($e->getMessage());
				parent::_setErrorMessage(__('Your request can\'t be proccessed! Please contact your administrator for more information!'));
			}
		} else {
			parent::_setErrorMessage(__('There is no search conditions!'));
		}
		$this->redirect(['controller' => 'Expenses', 'action' => 'view']);
	}
}
?>