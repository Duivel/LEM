<?php
if (empty($expense) || (count($expense) == 0)) {
	echo "There is no expenses!";
} else {
?>
<!-- Page Heading -->
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Expense Detail
		</h1>
		<ol class="breadcrumb">
			<li>
				<i class="fa fa-home"></i>  <?php echo $this->Html->link('Home', ['controller' => 'users', 'action' => 'home'])?>
			</li>
			<li class="active"><?php echo $this->Html->link('Expense Management', ['controller' => 'expenses', 'action' => 'view'])?></li>
			<li class="active">Detail</li>
		</ol>
	</div>
</div>
<!-- /.row -->

<!-- Enter detail fields -->
<div class="row">
	<div class="col-lg-6 col-sm-8">
		<fieldset>
			<div class="form-group">
				<label for="title" class="col-lg-3 col-sm-4 control-label">Title</label>
				<p for="title" class="ol-lg-9 col-sm-8 form-control-static">
					<?php echo h($expense->title)?>
				</p>
			</div>
			<div class="form-group">
				<label for="description" class="col-lg-3 col-sm-4 control-label">Description</label>
				<p for="description" class="ol-lg-9 col-sm-8 form-control-static">
					<?php echo h($expense->description)?>
				</p>
			</div>
			<div class="form-group">
				<label for="amount" class="col-lg-3 col-sm-4 control-label">Amount</label>
				<p for="amount" class="ol-lg-9 col-sm-8 form-control-static">
					<?php echo h($expense->displayFormatAmount())?>
				</p>
			</div>
			<div class="form-group">
				<label for="date" class="col-lg-3 col-sm-4 control-label">Date</label>
				<p for="date" class="ol-lg-9 col-sm-8 form-control-static">
					<?php echo h($expense->displayDateInView())?>
				</p>
			</div>
			<div class="form-group">
				<label for="user_id" class="col-lg-3 col-sm-4 control-label">User</label>
				<p for="user_id" class="ol-lg-9 col-sm-8 form-control-static">
					<?php echo h($expense->user->user_name)?>
				</p>
			</div>
			<div class="form-group">
				<label for="expense_type_id" class="col-lg-3 col-sm-4 control-label">Type</label>
				<p for="expense_type" class="ol-lg-9 col-sm-8 form-control-static">
					<?php echo h($expense->expense_type->expense_type_name)?>
				</p>
			</div>
			<div class="form-group">
				<label for="create_user" class="col-lg-3 col-sm-4 control-label">Create User</label>
				<p for="create_user" class="ol-lg-9 col-sm-8 form-control-static">
					<?php echo h($expense->displayCreateUserName())?>
				</p>
			</div>
			<div class="form-group">
				<label for="modify_user" class="col-lg-3 col-sm-4 control-label">Modify User</label>
				<p for="modify_user" class="ol-lg-9 col-sm-8 form-control-static">
					<?php echo h($expense->displayModifyUserName())?>
				</p>
			</div>
			<div class="form-group">
				<div class="col-lg-3 col-sm-4"></div>
				<div class="col-lg-9 col-sm-8">
					<?php echo $this->Html->link('<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit', ['controller' => 'Expenses', 'action' => 'edit', $expense->expense_id], ['class' => 'btn btn-primary', 'escape' => FALSE])?>
					<!-- <button type="button" class="btn btn-primary">Add</button> -->
				</div>
			</div>
		</fieldset>
	</div>
</div>
<!-- End of detail fields -->
<?php 
}
?>