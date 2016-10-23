<?php
use App\Lib\Constants;
use Cake\Routing\Router;
?>
<!-- Page Heading -->
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Your withdraw list
		</h1>
		<ol class="breadcrumb">
			<li>
				<i class="fa fa-home"></i><?php echo $this->Html->link(' Home', ['controller' => 'users', 'action' => 'home'])?>
			</li>
			<li class="active"><?php echo $this->Html->link('Withdraw Management', ['controller' => 'Withdraws', 'action' => 'view'])?></li>
		</ol>
	</div>
</div>
<!-- /.row -->

<!-- Charts -->
<div class="row">
	<div class="col-lg-12 col-sm-12">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-bar-chart" aria-hidden="true"></i>This month's withdraws</h3>
			</div>
			<div class="panel-body">
				<div id="area-chart"></div>
			</div>
			<div><h4>&nbsp;Detail: <span id="barChartDetail"></span></h4></div>
		</div>
	</div>
</div>
<!--End of charts  -->
<div class="row">
	<div class="col-sm-12 col-md-12 col-lg-12">
		<?php echo $this->Flash->render()?>
	</div>
</div>

<!-- Search conditions -->
<div class="row" >
	<div class="col-lg-12 col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading" id="search-where" >
				<h3 class="panel-title"><i class="fa fa-search fa-fw"></i> Search</h3>
			</div>
			<div class="panel-body" style="display: none">
				<div class="row">
					<div class="col-lg-8 col-sm-12 col-md-8">
						<?php echo $this->ExForm->create('', ['url' => ['action' =>'search'], 'class' => 'form-horizontal'])?>
						<fieldset>
							<div class="form-group">
								<label for="title" class="col-lg-2 col-md-2 col-sm-2 control-label">Title</label>
								<div class="col-lg-4 col-sm-4 col-md-4">
									<?php echo $this->ExForm->text('title', ['id' => 'title', 'class' => 'form-control', 'placeholder' => 'Title...']);?>
								</div>
							</div>
							<div class="form-group">
								<label for="title" class="col-lg-2 col-md-2 col-sm-4 control-label">Amount</label>
								<div class="col-lg-4 col-sm-4 col-md-4">
									<?php echo $this->ExForm->text('amountFrom', ['id' => 'amountFrom', 'class' => 'form-control', 'placeholder' => 'Amount is greater than...']);?>
								</div>
								<div class="col-lg-4 col-sm-4 col-md-4">
									<?php echo $this->ExForm->text('amountTo', ['id' => 'amountTo', 'class' => 'form-control', 'placeholder' => 'Amount is less than...']);?>
								</div>
							</div>
							<div class="form-group">
								<label for="date" class="col-lg-2 col-sm-4 col-md-2 control-label">Date</label>
								<div class="col-lg-4 col-sm-4 col-md-4">
									<div class="input-group date" id="datePickerFrom">
										<?php 
										echo $this->ExForm->text('dateFrom', ['class' => 'form-control', 'id' => 'date', 'readonly']);
										?>
										<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
									</div>
								</div>
								<div class="col-lg-4 col-sm-4 col-md-4">
									<div class="input-group date" id="datePickerTo">
										<?php 
										echo $this->ExForm->text('dateTo', ['class' => 'form-control', 'id' => 'dateTo', 'readonly']);
										?>
										<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="user_id" class="col-lg-2 col-sm-4 col-md-2 control-label">Type</label>
								<div class="col-lg-4 col-sm-8 col-md-4">
									<?php 
									echo $this->ExForm->withdrawType('user_id', ['id' => 'user_id', 'empty' => '--Select--', 'class' => 'form-control']);
									?>
								</div>
							</div>
							<div class="form-group">
								<label for="user_id" class="col-lg-2 col-md-2 col-sm-4 control-label">User</label>
								<div class="col-lg-4 col-sm-8 col-md-4">
									<?php 
									echo $this->ExForm->user('user_id', ['id' => 'user_id', 'empty' => '--Select--', 'class' => 'form-control']);
									?>
								</div>
							</div>
							<div class="form-group">
								<div class="col-lg-2 col-sm-4 col-md-2"></div>
								<div class="col-lg-4 col-sm-8 col-md-8">
									<?php echo $this->ExForm->button('Search', ['class' => 'btn btn-primary', 'value' => 'Submit', 'type' => 'submit'])?>
								</div>
							</div>
						</fieldset>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End of search -->
<?php
if (empty($withdraws) || (count($withdraws) == 0)) {
	echo "There is no withdraws!";
} else {
?>

<div class="row">
	<div class="col-lg-12 col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-money fa-fw"></i> Withdraw List</h3>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th>Index</th>
								<th>Date</th>
								<th>Title</th>
								<th>Description</th>
								<th>Type</th>
								<th>User</th>
								<th>Amount (JPY)</th>
								<th></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						<?php
						$i = 1;
						foreach ($withdraws as $withdraw) {
							$urlDel = '/'.Constants::USER_PREFIX.'/withdraws/delete/'.$withdraw->withdraw_id;
							$urlDel = Router::url($urlDel, TRUE);
						?>
							<tr>
								<td><?= $i?></td>
								<td><?= $withdraw->displayDateInView()?></td>
								<td><?= $withdraw->title?></td>
								<td><?= h($withdraw->description)?></td>
								<td><?= $withdraw->displayWithdrawTypeInView()?></td>
								<td><?= $withdraw->user->user_name?></td>
								<td><?= $withdraw->displayFormatAmount()?></td>
								<td><?= $this->Html->link('<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit', ['controller' => 'withdraws', 'action' => 'edit', $withdraw->withdraw_id], ['class' => 'btn btn-primary', 'escape' => FALSE])?></td>
								<td><a data-toggle="modal" data-target="#confirmDelete" data-href="<?php echo $urlDel?>" class="btn btn-danger"><i class="fa fa-times" aria-hidden="true"></i> Delete</a></td>
							</tr>
						<?php
							$i++;
						}
						?>
						</tbody>
					</table>
					<nav align="center">
						<ul class="pagination">
							<?= $this->Paginator->prev('&larr;  ' . __('previous'), ['escape' => false]) ?>
							<?= $this->Paginator->numbers(['after' => '</li>', 'before' => '<li class="pagination">']) ?>
							<?= $this->Paginator->next(__('next') . ' &rarr;' , ['escape' => false]) ?>
						</ul>
					</nav>
				</div>				
			</div>
		</div>
	</div>
</div>
<!-- /.row -->
<?php 
}
?>
<div class="row">
	<div class="col-lg-12 col-sm-12 col-md-12">
		<?php echo $this->Html->link('<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Add a new withdraw', ['controller' => 'withdraws', 'action' => 'edit'], ['class' => 'btn btn-primary', 'escape' => FALSE])?>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="confirmDelete" tabindex="-1" role="dialog" aria-labelledby="labelDelete" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Confirm Delete</h4>
			</div>
			<div class="modal-body">
				<p>Do you want to delete this withdraw ?</p>
			</div>
			<div class="modal-footer">
				<a class="btn btn-danger btn-ok">Yes</a>
				<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>
<!-- End of Modal -->
<?php echo $this->Html->scriptStart(['block' => TRUE]);?>
$(document).ready(function() {
	$('#search-where').click(function() {
		$(this).next().slideToggle('fast');
	});
	$('#datePickerFrom')
		.datepicker({
			autoclose: true,
			format: 'dd/mm/yyyy',
			todayHighlight: true
	});
	$('#datePickerTo')
		.datepicker({
			autoclose: true,
			format: 'dd/mm/yyyy',
			todayHighlight: true
	});
	//delete button on modal
	$('#confirmDelete').on('show.bs.modal', function(e) {
		$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
	});
});
<?php echo $this->Html->scriptEnd();?>

