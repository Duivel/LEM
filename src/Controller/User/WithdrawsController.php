<?php
namespace App\Controller\User;

use App\Lib\Constants;
use Cake\ORM\TableRegistry;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\BadRequestException;

class WithdrawsController extends UserAppController
{
	public $paginate = ['limit' => Constants::WITHDRAW_VIEW_LIMIT];

	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Paginator');
		$this->Session = $this->request->session();
	}
	
	private function _view() {
		parent::_move('Withdraw List', Constants::USER_LAYOUT, 'view');
		$this->Session->write(Constants::SESSION_URL_WITHDRAW_VIEW, "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
	}
	
	private function _edit() {
		parent::_move('Add\Edit a withdraw', Constants::USER_LAYOUT, 'edit');
	}
	
	public function view() 
	{
		$withdrawTable = TableRegistry::get('Withdraws');
		$withdraws = $withdrawTable->findAllByDelFlg();
		$this->set('withdraws', $this->paginate($withdraws));
		$this->_view();
	}

	public function edit($withdraw_id = NULL)
	{
		$withdrawTable = TableRegistry::get('Withdraws');
		$errorMessage = 'Can\'t add withdraw!';
		$successMessage = 'A new withdraw was added!';
		
		if (empty($withdraw_id) || (mb_strlen($withdraw_id) == 0)) {
			$withdraw = $withdrawTable->newEntity();
		} else {
			$withdraw = $withdrawTable->findByWithdrawId($withdraw_id);
			if (is_null($withdraw) || (count($withdraw) == 0)) {
				throw new NotFoundException(__('This withdraw doesn\'t exist!'));
			}
			$withdraw->displayDateInEdit();
			$errorMessage = 'Can\'t edit withdraw!';
			$successMessage = 'A withdraw was edited!';
		}
		$this->set('withdraw', $withdraw);
		
		if ($this->request->is(['post', 'put'])) {
			$data = $this->request->data;
			$this->AccessLog->writeLog($data);
			$data['date'] = \DateTime::createFromFormat('d/m/Y', $data['date'])->format('Y-m-d');
			
			$withdraw = $withdrawTable->patchEntity($withdraw, $data);
			if ($withdrawTable->save($withdraw)) {
				$url = $this->Session->read(Constants::SESSION_URL_WITHDRAW_VIEW);
				if (!is_null($url)) {
					$this->redirect($url);
				} else {
					$this->redirect(['controller' => 'Withdraws', 'action' => 'view']);
				}
				parent::_setSuccessMessage($successMessage);
				return;
			} else {
				parent::_setErrorMessage($errorMessage);
				$this->set('withdraw', $withdraw);
			}
		}
		$this->_edit();
	}
	
	public function delete($withdraw_id)
	{
		$this->AccessLog->writeLog();
		if (!empty($withdraw_id)) {
			$withdrawTable = TableRegistry::get('Withdraws');
			$withdrawTable->deleteByWithdrawId($withdraw_id);
			parent::_setSuccessMessage('An withdraw was deleted successfully!');
			$this->redirect(['controller' => 'Withdraws', 'action' => 'view']);
		} else {
			throw new BadRequestException('This is really a bad request');
		}
	}
}