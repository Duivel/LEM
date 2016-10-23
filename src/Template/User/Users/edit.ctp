<?php
use App\Lib\Constants;

$title = 'Add a new user';
$breadScrum = 'Add';
$btnValue = 'Add';
$btnName = 'Add';

$isUpdate = empty($user->user_id) ? FALSE : TRUE;
if ($isUpdate) {
	$title = 'Edit an user';
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
			<li><?php echo $this->Html->link('Expense Management', ['controller' => 'users', 'action' => 'view'])?></li>
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
		echo $this->ExForm->create($user, ['url' => ['action' => 'edit'], 'class' => 'form-horizontal']);
		echo $this->ExForm->hidden('user_id');
		?>
		<fieldset>
			<div class="form-group">
				<label for="user_name" class="col-lg-3 col-sm-4 control-label">User Name</label>
				<?php if (empty($this->ExForm->error('user_name'))) {?>
				<div class="col-lg-9 col-sm-8">
				<?php } else {?>
				<div class="col-lg-9 col-sm-8 has-error">
				<?php } 
					echo $this->ExForm->text('user_name', ['id' => 'user_name', 'class' => 'form-control', 'placeholder' => 'User Name...']);
					echo $this->ExForm->error('user_name');
				?>
				</div>
			</div>
			<div class="form-group">
				<label for="password" class="col-lg-3 col-sm-4 control-label">Password</label>
				<?php if (empty($this->ExForm->error('password'))) {?>
				<div class="col-lg-9 col-sm-8">
				<?php } else {?>
				<div class="col-lg-9 col-sm-8 has-error">
				<?php }
					echo $this->ExForm->password('password', ['id' => 'password',  'class' => 'form-control', 'placeholder' => 'Password...']);
					echo $this->ExForm->error('password');
				?>
				</div>
			</div>
			<div class="form-group">
				<label for="email" class="col-lg-3 col-sm-4 control-label">Email</label>
				<?php if (empty($this->ExForm->error('email'))) {?>
				<div class="col-lg-9 col-sm-8">
				<?php } else {?>
				<div class="col-lg-9 col-sm-8 has-error">
				<?php } 
					echo $this->ExForm->text('email', ['id' => 'email', 'class' => 'form-control', 'placeholder' => 'Email...']);
					echo $this->ExForm->error('email');
				?>
				</div>
			</div>
			<div class="form-group">
				<label for="birthday" class="col-lg-3 col-sm-4 control-label">Birthday</label>
				<?php if (empty($this->ExForm->error('birthday'))) {?>
				<div class="col-lg-9 col-sm-8">
				<?php } else {?>
				<div class="col-lg-9 col-sm-8 has-error">
				<?php } ?>
				<!-- <div class="col-lg-9 col-sm-8 date"> -->
					<div class="input-group date" id="datePicker">
						<?php 
						echo $this->ExForm->text('birthday', ['class' => 'form-control', 'id' => 'birthday', 'readonly']);
						echo $this->ExForm->error('birthday');
						?>
						<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
					</div>
				</div>
			</div>
			<?php if ($isUpdate) {?>
			<div class="form-group">
				<label for="status" class="col-lg-3 col-sm-4 control-label">Status</label>
				<div class="col-lg-9 col-sm-8">
					<?php echo $this->ExForm->userStatus('status', ['id' => 'status', 'empty' => FALSE, 'class' => 'form-control'])?>
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
			startView: 3,
			maxViewMode: 2,
			clearBtn: true
	});
});
<?php echo $this->Html->scriptEnd()?>