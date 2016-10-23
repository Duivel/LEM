<?php

use App\Lib\Constants;
use Cake\Routing\Router;

if (empty($incomes) || (count($incomes) == 0)) {
	echo "There is no incomes!";
} else {
?>
<!-- Page Heading -->
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Your income list
		</h1>
		<ol class="breadcrumb">
			<li>
				<i class="fa fa-home"></i>  <?php echo $this->Html->link('Home', ['controller' => 'users', 'action' => 'home'])?>
			</li>
			<li class="active"><?php echo $this->Html->link('Income Management', ['controller' => 'Incomes', 'action' => 'view'])?></li>
		</ol>
	</div>
</div>
<!-- /.row -->

<!-- Charts -->
<div class="row">
	<div class="col-lg-12 col-sm-12">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-bar-chart" aria-hidden="true"></i>Last 6 months</h3>
			</div>
			<div class="panel-body">
				<div id="area-chart"></div>
			</div>
			<div><h4>&nbsp;Detail: <span id="barChartDetail"></span></h4></div>
		</div>
	</div>
</div>
<!--End of charts  -->

<!-- Flash message -->
<div class="row">
	<div class="col-sm-12 col-md-12 col-lg-12">
		<?php echo $this->Flash->render()?>
	</div>
</div>
<!-- End of Flash message -->

<div class="row">
	<div class="col-lg-12 col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-money fa-fw"></i> Income List</h3>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th>Index</th>
								<th>Month</th>
								<th>Amount (JPY)</th>
								<th>User</th>
								<th>Type</th>
								<th>Create Date</th>
								<th></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						<?php
						$i = 1;
						foreach ($incomes as $income) {
							$urlDel = '/'.Constants::USER_PREFIX.'/Incomes/Delete/'.$income->income_id;
							$urlDel = Router::url($urlDel, TRUE);
						?>
							<tr>
								<td><?= $i?></td>
								<td><?= $income->month?></td>
								<td><?= $income->formatAmount()?></td>
								<td><?= $income->user->user_name?></td>
								<td><?= $income->income_type->income_type_name?></td>
								<td><?= $income->displayCreateDateInView()?></td>
								<td><?= $this->Html->link('<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit', ['controller' => 'Incomes', 'action' => 'edit', $income->income_id], ['class' => 'btn btn-primary', 'escape' => FALSE])?></td>
								<td><a data-toggle="modal" data-target="#confirmDelete" data-href="<?php echo $urlDel?>" class="btn btn-danger"><i class="fa fa-times" aria-hidden="true"></i> Delete</a></td>
							</tr>
						<?php
							$i++;
						}
						?>
						</tbody>
					</table>
				</div>
				<?php echo $this->Html->link('<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Add a new income', ['controller' => 'Incomes', 'action' => 'edit'], ['class' => 'btn btn-primary', 'escape' => FALSE])?>
			</div>
		</div>
	</div>
</div>
<!-- /.row -->
<?php 
}
?>

<!-- Modal -->
<div class="modal fade" id="confirmDelete" tabindex="-1" role="dialog" aria-labelledby="labelDelete" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Confirm Delete</h4>
			</div>
			<div class="modal-body">
				<p>Do you want to delete this income ?</p>
			</div>
			<div class="modal-footer">
				<a class="btn btn-danger btn-ok">Yes</a>
				<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>
<!-- End of Modal -->
<?php $this->Html->scriptStart(['block' => TRUE])?>
$(document).ready(function() {
	//delete button on modal
	$('#confirmDelete').on('show.bs.modal', function(e) {
		$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
	});
});
<?php $this->Html->scriptEnd();?>

