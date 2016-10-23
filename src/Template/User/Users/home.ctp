<?php 
use App\Lib\Constants;
use Cake\Routing\Router;

$to_day = date('Y-m-d');
$first_day_of_month = date('Y-m-01');
$day = date('w');

$url='/'.Constants::USER_PREFIX.'/Expenses/view?from_day=__FROM__&to_day='.$to_day;
$url = Router::url($url, TRUE);
// pr($panelData['expenses']);exit();
?>
<!-- Page Heading -->
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Home <small>Statistics Overview</small></h1>
			<ol class="breadcrumb">
				<li class="active"><i class="fa fa-home"></i> Home</li>
			</ol>
		</div>
	</div>
	<!-- /.row -->

	<div class="row">
		<div class="col-lg-3 col-md-6">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-3">
							<i class="fa fa-paper-plane fa-5x"></i>
						</div>
						<div class="col-xs-9 text-right">
							<div class="huge">
							<?php echo (empty($status['walletMoney'][0]['wallet_amount']) ? "0" : number_format($status['walletMoney'][0]['wallet_amount']))?> JPY
							</div>
							<div>In your wallet!</div>
						</div>
					</div>
				</div>
				<?php echo $this->Html->link('<div class="panel-footer"><span class="pull-left">View Details</span><span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span><div class="clearfix"></div></div>', ['controller' => 'Incomes', 'action' => 'view'], ['escape' => FALSE])?>
			</div>
		</div>
		<div class="col-lg-3 col-md-6">
			<div class="panel panel-green">
				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-3">
							<i class="fa fa-tasks fa-5x"></i>
						</div>
						<div class="col-xs-9 text-right">
							<div class="huge">
							<?php echo (empty($status['expenseStatus'][0]['income']) ? "0" : number_format($status['expenseStatus'][0]['income']))?>
							</div>
							<div>This month's income!</div>
						</div>
					</div>
				</div>
				<?php echo $this->Html->link('<div class="panel-footer"><span class="pull-left">View Details</span><span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span><div class="clearfix"></div></div>', ['controller' => 'Incomes', 'action' => 'view'], ['escape' => FALSE])?>
			</div>
		</div>
		<div class="col-lg-3 col-md-6">
			<div class="panel panel-yellow">
				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-3">
							<i class="fa fa-shopping-cart fa-5x"></i>
						</div>
						<div class="col-xs-9 text-right">
							<div class="huge"><?php echo (empty($status['expenseStatus'][0]['expense']) ? "0" : number_format($status['expenseStatus'][0]['expense']));?> JPY</div>
							<div>This month's expenses!</div>
						</div>
					</div>
				</div>
				<?php 
				$url = str_replace('__FROM__', $first_day_of_month, $url);
				echo $this->Html->link('<div class="panel-footer"><span class="pull-left">View Details</span><span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span><div class="clearfix"></div></div>', $url, ['escape' => FALSE]);
				?>
			</div>
		</div>
		<div class="col-lg-3 col-md-6">
			<div class="panel panel-red">
				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-3">
							<i class="fa fa-support fa-5x"></i>
						</div>
						<div class="col-xs-9 text-right">
							<div class="huge"><?php echo (empty($status['expenseStatus'][0]['withdraw']) ? "0" : number_format($status['expenseStatus'][0]['withdraw']))?> JPY</div>
							<div>This month's withdraws!</div>
						</div>
					</div>
				</div>
				<?php echo $this->Html->link('<div class="panel-footer"><span class="pull-left">View Details</span><span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span><div class="clearfix"></div></div>', ['controller' => 'Withdraws', 'action' => 'view'], ['escape' => FALSE])?>
			</div>
		</div>
	</div>
	<!-- /.row -->

	<div class="row">
		<div class="col-lg-6 col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><i class="fa fa-bar-chart" aria-hidden="true"></i> Last 6 months</h3>
				</div>
				<div class="panel-body">
					<canvas id="flowChart"></canvas>
					<!-- <div id="area-chart"></div> -->
				</div>
				<!-- <div><h4>Detail: <span id="chartDetail"></span></h4></div> -->
			</div>
		</div>
		<div class="col-lg-6 col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><i class="fa fa-pie-chart" aria-hidden="true"></i> This month</h3>
				</div>
				<div class="panel-body">
					<canvas id="pieChart"></canvas>
					<!-- <div id="pie-chart"></div> -->
				</div>
			</div>
		</div>
	</div>
	<!-- Saving and withdraw part -->
	<div class="row">
		<div class="col-lg-6 col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><i class="fa fa-money fa-fw"></i> Saving Panel (top 6)</h3>
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-bordered table-hover table-striped">
							<thead>
								<tr>
									<th>Index</th>
									<th>Month</th>
									<th>Amount (JPY)</th>
								</tr>
							</thead>
							<tbody>
							<?php 
							$j = 1;
							foreach ($panelData['savings'] as $saving) {
							?>
								<tr>
									<td><?php echo $j?></td>
									<td><?php echo $saving->month?></td>
									<?php
									if ($saving->saving < 0) {
									?>
									<td class="danger"><?php echo $saving->displaySavingFormatAmount()?></td>
									<?php }else {?>
									<td><?php echo $saving->displaySavingFormatAmount()?></td>
									<?php }?>
								</tr>
							<?php 
								$j++;
							}
							?>
							</tbody>
						</table>
					</div>
					<div class="text-right">
						<?php echo $this->Html->link('View All Saving <i class="fa fa-arrow-circle-right"></i>', ['controller' => 'Savings', 'action' => 'view'], ['escape' => FALSE])?>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-6 col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><i class="fa fa-money fa-fw"></i> Withdraw Panel (top 6)</h3>
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-bordered table-hover table-striped">
							<thead>
								<tr>
									<th>Index</th>
									<th>Date</th>
									<th>Title</th>
									<th>Amount (JPY)</th>
								</tr>
							</thead>
							<tbody>
							<?php 
							$j = 1;
							foreach ($panelData['withdraws'] as $withdraw) {
							?>
								<tr>
									<td><?php echo $j?></td>
									<td><?php echo $withdraw->displayDateInView() ?></td>
									<td><?php echo h($withdraw->title)?></td>
									<td><?php echo $withdraw->displayFormatAmount()?></td>
								</tr>
							<?php 
								$j++;
							}
							?>
							</tbody>
						</table>
					</div>
					<div class="text-right">
						<?php echo $this->Html->link('View All Withdraws <i class="fa fa-arrow-circle-right"></i>', ['controller' => 'Withdraws', 'action' => 'view'], ['escape' => FALSE])?>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-6 col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><i class="fa fa-money fa-fw"></i> Income Panel (top 10)</h3>
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-bordered table-hover table-striped">
							<thead>
								<tr>
									<th>Index</th>
									<th>Month</th>
									<th>Amount (JPY)</th>
									<th>Note</th>
								</tr>
							</thead>
							<tbody>
							<?php
							$i = 1;
							foreach ($panelData['incomes'] as $income) {
							?>
								<tr>
									<td><?php echo $i?></td>
									<td><?php echo $income->month?></td>
									<td><?php echo $income->formatAmount()?></td>
									<td><?php echo h($income->note)?></td>
								</tr>
							<?php
								$i++;
							}
							?>
							</tbody>
						</table>
					</div>
					<div class="text-right">
						<?php echo $this->Html->link('View All Incomes <i class="fa fa-arrow-circle-right"></i>', ['controller' => 'Incomes', 'action' => 'view'], ['escape' => FALSE])?>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-6 col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><i class="fa fa-money fa-fw"></i> Expense Panel (top 10)</h3>
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-bordered table-hover table-striped">
							<thead>
								<tr>
									<th>Index</th>
									<th>Date</th>
									<th>Amount (JPY)</th>
									<th>Title</th>
								</tr>
							</thead>
							<tbody>
							<?php 
							$j = 1;
							foreach ($panelData['expenses'] as $expense) {
							?>
								<tr>
									<td><?php echo $j?></td>
									<td><?php echo $expense->displayDateInView()?></td>
									<td><?php echo $expense->displayFormatAmount()?></td>
									<td><?php echo h($expense->title)?></td>
								</tr>
							<?php 
								$j++;
							}
							?>
							</tbody>
						</table>
					</div>
					<div class="text-right">
						<?php echo $this->Html->link('View All Expenses <i class="fa fa-arrow-circle-right"></i>', ['controller' => 'Expenses', 'action' => 'view'], ['escape' => FALSE])?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /.row -->


<?php echo $this->Html->scriptStart(['block' => TRUE])?>
$(document).ready(function(){
	//Flow chart
	var ctx = document.getElementById("flowChart").getContext('2d');
	var myChart = new Chart(ctx, {
		type: 'bar',
		display: true,
		data: {
			labels: <?php echo json_encode($flowChart['month'])?>,
			datasets: [
			{
				label: 'Expense',
				data: <?php echo json_encode($flowChart['expense'])?>,
				borderWidth: 1,
				backgroundColor: "rgba(255, 99, 132, 0.2)",
				borderColor: "rgba(255,99,132,1)"
			}, 
			{
				label: 'Income',
				data: <?php echo json_encode($flowChart['income'])?>,
				borderWidth: 1,
				backgroundColor: "rgba(54, 162, 235, 0.2)",
				borderColor: "rgba(54, 162, 235, 1)"
			}
		]
		}
	});

	//Pie chart
	var ctx = document.getElementById("pieChart").getContext('2d');
	var myChart = new Chart(ctx, {
		type: 'pie',
		data: {
			labels: <?php echo json_encode($pieChart['expense_type_name'])?>,
			datasets: [{
				backgroundColor: [
					"#2ecc71",
					"#3498db",
					"#95a5a6",
					"#9b59b6",
					"#f1c40f",
					"#e74c3c",
					"#34495e",
					"#aaee75",
					"#58b4e1"
				],
				data: <?php echo str_replace('"', '', json_encode($pieChart['expense_amount'])) ?>
			}]
		},
		/*Display percentage for each expense*/
		options: {
			responsive: true,
			legend: {
				position: 'top',
			},
			title: {
				display: false,
				text: 'Your Chart'
			},
			animation: {
				animateScale: true,
				animateRotate: true
			},
			tooltips: {
				callbacks: {
					label: function(tooltipItem, data) {
						var dataset = data.datasets[tooltipItem.datasetIndex];
						var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
							return previousValue + currentValue;
						});
						var currentValue = dataset.data[tooltipItem.index];
						var precentage = Math.floor(((currentValue/total) * 100)+0.5);         
						return precentage + "%";
					}
				}
			}
		}
	});
});
<?php echo $this->Html->scriptEnd()?>
