<?php
$title = 'Add a new income';
$breadScrum = 'Add';
$btnValue = 'Add';
$btnName = 'Add';

$isUpdate = empty($income->income_id) ? FALSE : TRUE;
if ($isUpdate) {
	$title = 'Edit an income';
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
			<li class="active"><?php echo $this->Html->link('Income Management', ['controller' => 'Incomes', 'action' => 'view'])?></li>
			<li class="active"><?php echo $breadScrum?></li>
		</ol>
	</div>
</div>
<!-- /.row -->

<!-- Flash message -->
<div class="row">
	<div class="col-sm-12 col-md-12 col-lg-12">
		<?php echo $this->Flash->render()?>
	</div>
</div>
<!-- End of Flash message -->

<!-- Enter edit fields -->
<div class="row">
	<div class="col-lg-6 col-sm-8">
		<?php 
		echo $this->ExForm->create($income, ['url' => ['action' => 'edit'], 'class' => 'form-horizontal']);
		echo $this->ExForm->hidden('income_id');
		?>
		<fieldset>
			<div class="form-group">
				<label for="month" class="col-lg-3 col-sm-4 control-label">Month</label>
				<?php if (empty($this->ExForm->error('month'))) {?>
				<div class="col-lg-9 col-sm-8">
				<?php } else {?>
				<div class="col-lg-9 col-sm-8 has-error">
				<?php } ?>
					<div class="input-group date" id="datePicker">
						<?php 
						echo $this->ExForm->text('month', ['class' => 'form-control', 'id' => 'month', 'readonly']);
						echo $this->ExForm->error('month');
						?>
						<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
					</div>
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
				<label for="note" class="col-lg-3 col-sm-4 control-label">Note</label>
				<?php if (empty($this->ExForm->error('note'))) {?>
				<div class="col-lg-9 col-sm-8">
				<?php } else {?>
				<div class="col-lg-9 col-sm-8 has-error">
				<?php } 
					echo $this->ExForm->textarea('note', ['id' => 'note', 'class' => 'form-control', 'row' => 5, 'placeholder' => 'Amount...']);
					echo $this->ExForm->error('note');
				?>
				</div>
			</div>
			<div class="form-group">
				<label for="user_id" class="col-lg-3 col-sm-4 control-label">User</label>
				<?php if (empty($this->ExForm->error('user_id'))) {?>
				<div class="col-lg-9 col-sm-8">
				<?php } else {?>
				<div class="col-lg-9 col-sm-8 has-error">
				<?php } 
					echo $this->ExForm->user('user_id', ['id' => 'user_id', 'empty' => false, 'class' => 'form-control']);
					echo $this->ExForm->error('user_id');
				?>
				</div>
			</div>
			<div class="form-group">
				<label for="income_type_id" class="col-lg-3 col-sm-4 control-label">Type</label>
				<div class="col-lg-9 col-sm-8">
					<?php echo $this->ExForm->incomeType('income_type_id', ['id' => 'income_type_id', 'empty' => false, 'class' => 'form-control']);?>
				</div>
			</div>
			<div class="form-group">
				<div class="col-lg-3 col-sm-4"></div>
				<div class="col-lg-9 col-sm-8">
					<?php echo $this->ExForm->button($btnName, ['class' => 'btn btn-primary', 'name' => $btnName, 'value' => $btnValue, 'type' => 'submit'])?>
					<!-- <button type="button" class="btn btn-primary">Add</button> -->
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
			format: 'mm/yyyy',
			minViewMode: 1,
			maxViewMode: 1,
	});
});
<?php echo $this->Html->scriptEnd()?>