<?php
use App\Lib\Constants;

$title = 'Add a new expense';
$breadScrum = 'Add';
$btnValue = 'Add';
$btnName = 'Add';

$isUpdate = empty($expense->expense_id) ? FALSE : TRUE;
if ($isUpdate) {
	$title = 'Edit an expense';
	$breadScrum = 'Edit';
	$btnValue = 'Edit';
	$btnName = 'Edit';
}
$this->loadHelper('ExForm', [
		'templates' => 'app_form'
]);
?> 
<!-- Page Heading -->
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			<?php echo $title?>
		</h1>
		<ol class="breadcrumb">
			<li>
				<i class="fa fa-home"></i>  <?php echo $this->Html->link('Home', ['controller' => 'users', 'action' => 'home'])?>
			</li>
			<li><?php echo $this->Html->link('Expense Management', ['controller' => 'expenses', 'action' => 'view'])?></li>
			<li class="active"><?php echo $breadScrum?></li>
		</ol>
	</div>
</div>
<!-- /.row -->
<div class="row">
	<div class="col-sm-12 col-md-12 col-lg-12">
		<?php echo $this->Flash->render()?>
	</div>
</div>
<!-- Enter edit fields -->
<div class="row">
	<div class="col-lg-6 col-sm-8">
		<?php 
		echo $this->ExForm->create($expense, ['url' => ['action' => 'edit'], 'class' => 'form-horizontal']);
		echo $this->ExForm->hidden('expense_id');
		?>
		<fieldset>
			<div class="form-group">
				<label for="title" class="col-lg-3 col-sm-4 control-label">Title</label>
				<?php if (empty($this->ExForm->error('title'))) {?>
				<div class="col-lg-9 col-sm-8">
				<?php } else {?>
				<div class="col-lg-9 col-sm-8 has-error">
				<?php } 
					echo $this->ExForm->text('title', ['id' => 'title', 'class' => 'form-control', 'placeholder' => 'Title...']);
					echo $this->ExForm->error('title');
				?>
				</div>
			</div>
			<div class="form-group">
				<label for="description" class="col-lg-3 col-sm-4 control-label">Description</label>
				<?php if (empty($this->ExForm->error('description'))) {?>
				<div class="col-lg-9 col-sm-8">
				<?php } else {?>
				<div class="col-lg-9 col-sm-8 has-error">
				<?php }
					echo $this->ExForm->textarea('description', ['id' => 'description', 'row' => 10,  'class' => 'form-control', 'placeholder' => 'Description...']);
					echo $this->ExForm->error('description');
				?>
				</div>
			</div>
			<div class="form-group">
				<label for="amount" class="col-lg-3 col-sm-4 control-label">Amount</label>
				<?php if (empty($this->ExForm->error('amount'))) {?>
				<div class="col-lg-9 col-sm-8">
				<?php } else {?>
				<div class="col-lg-9 col-sm-8 has-error">
				<?php } 
					echo $this->ExForm->text('amount', ['id' => 'amount', 'class' => 'form-control', 'placeholder' => 'Amount...']);
					echo $this->ExForm->error('amount');
				?>
				</div>
			</div>
			<div class="form-group">
				<label for="date" class="col-lg-3 col-sm-4 control-label">Date</label>
				<?php if (empty($this->ExForm->error('date'))) {?>
				<div class="col-lg-9 col-sm-8">
				<?php } else {?>
				<div class="col-lg-9 col-sm-8 has-error">
				<?php } ?>
				<!-- <div class="col-lg-9 col-sm-8 date"> -->
					<div class="input-group date" id="datePicker">
						<?php 
						echo $this->ExForm->text('date', ['class' => 'form-control', 'id' => 'date', 'readonly']);
						echo $this->ExForm->error('date');
						?>
						<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label for="user_id" class="col-lg-3 col-sm-4 control-label">User</label>
				<div class="col-lg-9 col-sm-8">
					<?php 
					echo $this->ExForm->user('user_id', ['id' => 'user_id', 'empty' => false, 'class' => 'form-control']);
					?>
				</div>
			</div>
			<div class="form-group">
				<label for="expense_type_id" class="col-lg-3 col-sm-4 control-label">Type</label>
				<div class="col-lg-9 col-sm-8">
					<?php echo $this->ExForm->expenseType('expense_type_id', ['id' => 'expense_type_id', 'empty' => false, 'class' => 'form-control']);?>
				</div>
			</div>
			<div class="form-group">
				<label for="spend_type" class="col-lg-3 col-sm-4 control-label">Spend Type</label>
				<div class="col-lg-9 col-sm-8">
					<?php echo $this->ExForm->withdrawType('spend_type', ['id' => 'spend_type', 'empty' => false, 'class' => 'form-control']);?>
				</div>
			</div>
			<?php if (!$isUpdate) {?>
			<div class="form-group">
				<div class="col-lg-3 col-sm-4"></div>
				<div class="col-lg-9 col-sm-8">
					<?php echo $this->ExForm->input('Add another expense', ['id' => 'add_another_expense', 'type' => 'checkbox', 'require' => FALSE, 'value' => Constants::CHECKBOX_ON])?>
				</div>
			</div>
			<?php }?>
			<div class="form-group">
				<div class="col-lg-3 col-sm-4"></div>
				<div class="col-lg-9 col-sm-8">
					<?php echo $this->ExForm->button($btnName, ['class' => 'btn btn-primary', 'value' => $btnValue, 'type' => 'submit', 'name' => $btnName])?>
				</div>
			</div>
		</fieldset>
		<?php echo $this->ExForm->end()?>
	</div>
</div>
<!-- End of edit fields -->
<?php echo $this->Html->scriptStart(['block' => TRUE])?>
$(document).ready(function() {
	$('#datePicker')
		.datepicker({
			autoclose: true,
			format: 'dd/mm/yyyy',
			todayHighlight: true
	});
});
<?php echo $this->Html->scriptEnd()?>