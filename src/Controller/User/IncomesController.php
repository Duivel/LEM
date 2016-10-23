<?php
namespace App\Controller\User;

use App\Lib\Constants;
use Cake\ORM\TableRegistry;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\BadRequestException;

class IncomesController extends UserAppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('RbQueue');
	}
	
	private function _view()
	{
		parent::_move('Income List', Constants::USER_LAYOUT, 'view');
	}
	
	private function _edit()
	{
		parent::_move('Add\Edit Income', Constants::USER_LAYOUT, 'edit');
	}
	
	public function view() 
	{
		$incomeTable = TableRegistry::get('Incomes');
		$incomes = $incomeTable->findAllByDelFlg();

		if (is_null($incomes) || empty($incomes)) {
			throw new NotFoundException(__('There is no incomes'));
			return;
		}
		
		$this->set('incomes', $incomes);
		$this->_view();
	}

	public function edit($income_id = NULL)
	{
		$this->LastAccess->setLastAccess();
		$succMess = 'A new income was save sucessfully!';
		$errorMess = 'Unable to save this income!';

		$incomeTable = TableRegistry::get('Incomes');
		if (is_null($income_id) || empty($income_id)) {
			$income = $incomeTable->newEntity();
		} else {
			$income = $incomeTable->findByIncomeId($income_id);
			if (is_null($income) || empty($income)) {
				throw new NotFoundException(__('There is no expense'));
				return;
			}
			$succMess = 'An income was edited successfully!';
			$errorMess = 'Unable to edit this income!';
		}
		$this->set('income', $income);
		
		if ($this->request->is(['put', 'post'])) {
			$this->AccessLog->writeLog($this->request->data);
			$income = $incomeTable->patchEntity($income, $this->request->data);
			$insertRMQ = $income->dirty('amount') || $income->isNew();
			if ($incomeTable->save($income)) {
				if ($insertRMQ) {
					$this->_sendMessageToRMQ($income);
				}
				parent::_setSuccessMessage($succMess);
				$this->redirect(['controller' => 'Incomes', 'action' => 'view']);
				return;
			} else {
				$this->set('income', $income);
				parent::_setErrorMessage($errorMess);
			}
		}
		$this->_edit();
	}

	public function delete($income_id)
	{
		$this->AccessLog->writeLog($data);
		$incomeTable = TableRegistry::get('Incomes');
		if (!empty($income_id)) {
			$income = $incomeTable->findByIncomeId($income_id);
			if ($incomeTable->deleteByIncomeId($income_id) > 0) {
				$this->_sendMessageToRMQ($income);
			}
			parent::_setSuccessMessage(__('An income was deleted successfully!'));
			$this->redirect(['controller' => 'Incomes', 'action' => 'view']);
		} else {
			throw new BadRequestException(__('This is a really bad request!'));
		}
	}
	
	private function _sendMessageToRMQ($income)
	{
		$msg = ['user_id' => $income->user_id, 'month' => $income->month];
		$this->AccessLog->writeLog($msg);
		$this->RbQueue->sendMessage(json_encode($msg, JSON_UNESCAPED_UNICODE), Constants::RABBITMQ_CALCULATE_SAVING_QUEUE_NAME, Constants::RABBITMQ_CALCULATE_SAVING_ROUTE_KEY);
	}
}