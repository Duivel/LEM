<?php

use App\Lib\Constants;
use Cake\Routing\Router;

if (empty($savings) || (count($savings) == 0)) {
	echo "There is no savings!";
} else {
?>
<!-- Page Heading -->
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Your saving list
		</h1>
		<ol class="breadcrumb">
			<li>
				<i class="fa fa-home"></i>  <?php echo $this->Html->link('Home', ['controller' => 'users', 'action' => 'home'])?>
			</li>
			<li class="active"><?php echo $this->Html->link('Saving Management', ['controller' => 'savings', 'action' => 'view'])?></li>
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
				<h3 class="panel-title"><i class="fa fa-money fa-fw"></i> saving List</h3>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th>Index</th>
								<th>Month</th>
								<th>Create Date</th>
								<th>Modify Date</th>
								<th>Income (JPY)</th>
								<th>Expense (JPY)</th>
								<th>Saving (JPY)</th>
							</tr>
						</thead>
						<tbody>
						<?php
						$i = 1;
						$income = 0; $expense = 0; $saved = 0;
						foreach ($savings as $saving) {
						?>
							<tr>
								<td><?= $i?></td>
								<td><?= $saving->month?></td>
								<td><?= $saving->displayCreateDateInView()?></td>
								<td><?= $saving->displayModifyDateInView()?></td>
								<td><?= $saving->displayIncomeFormatAmount()?></td>
								<td><?= $saving->displayExpenseFormatAmount()?></td>
								<td><?= $saving->displaySavingFormatAmount()?></td>
							</tr>
						<?php
							$income += $saving->income;
							$expense += $saving->expense;
							$saved += $saving->saving;
							$i++;
						}
						?>
							<tr class="success">
								<td colspan="4">Sum: </td>
								<td><?php echo number_format($income)?></td>
								<td><?php echo number_format($expense)?></td>
								<td><?php echo number_format($saved)?></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /.row -->
<?php 
}
?>
