<!DOCTYPE html>
<html>
<?php
use App\Lib\LoginUser;
use App\Lib\Constants;
$loginId = LoginUser::getLogin();
if (!empty($loginId)) {
?>
	<head>
		<?php
		echo $this->element('user_head');
		?>
	</head>
	<body>
		<div id="wrapper">
			<!-- Navigation -->
			<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<?= $this->Html->link(Constants::SYSTEM_NAME, ['controller'=>'users', 'action'=>'home'], ['class' => 'navbar-brand'])?>
				</div>
				<!-- Top Menu Items -->
				<ul class="nav navbar-right top-nav">
				<?php if (!empty($loginHistories)) {?>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-lock"></i> Login History <b class="caret"></b></a>
						<ul class="dropdown-menu message-dropdown">
							<?php 
							foreach($loginHistories as $history) {
							?>
							<li class="message-preview">
								<a href="#">
									<div class="media">
										<div class="media-body">
											<h5 class="media-heading"><strong><?php echo $history->displayDayOfWeek()?></strong></h5>
											<p class="small text-muted"><i class="fa fa-clock-o"></i> <?php echo $history->displayDate()?></p>
											<p>IP Address: <?php echo $history->IP_Address?></p>
										</div>
									</div>
								</a>
							</li>
							<?php }?>
							<li class="message-footer">
								<?php echo $this->Html->link('View All', ['prefix' => Constants::USER_PREFIX, 'controller' => 'LoginHistories', 'action' => 'view'])?>
							</li>
						</ul>
					</li> 
					<?php }?>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $loginUser['user_name']?> <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li>
								<a href="#"><i class="fa fa-fw fa-pencil-square-o"></i> Profile</a>
							</li>
							<li>
								<?php echo $this->Html->link('<i class="fa fa-fw fa-cog"></i> Export Files', ['prefix' => Constants::USER_PREFIX, 'controller' => 'ExportFiles', 'action' => 'view'], ['escape' => FALSE])?>
							</li>
							<li>
								<?php echo $this->Html->link('<i class="fa fa-fw fa-unlock"></i> Log Out', ['prefix' => Constants::LOGIN_PREFIX, 'controller' => 'Logins', 'action' => 'logout'], ['escape' => FALSE])?>
							</li>
						</ul>
					</li>
				</ul>
				
				<!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
				<div class="collapse navbar-collapse navbar-ex1-collapse">
					<ul class="nav navbar-nav side-nav">
						<li class="active">
							<?= $this->Html->link('<i class="fa fa-fw fa-home"></i> Home', ['controller' => 'Users', 'action' => 'home'], ['escape' => FALSE])?>
						</li>
						<li>
							<a href="javascript:;" data-toggle="collapse" data-target="#users"><i class="fa fa-fw fa-users"></i> User <i class="fa fa-fw fa-caret-down"></i></a>
							<ul id="users" class="collapse">
								<li>
									<?= $this->Html->link('<i class="fa fa-fw fa-users"></i> User List', ['controller' => 'Users', 'action' => 'view'], ['escape' => FALSE])?>
								</li>
								<li>
									<?= $this->Html->link('<i class="fa fa-fw fa-user-plus"></i> Create User', ['controller' => 'Users', 'action' => 'edit'], ['escape' => FALSE])?>
								</li>
							</ul>
						</li>
						<li>
							<a href="javascript:;" data-toggle="collapse" data-target="#expenseType"><i class="fa fa-fw fa-eur"></i> Saving<i class="fa fa-fw fa-caret-down"></i></a>
							<ul id="expenseType" class="collapse">
								<li>
									<?= $this->Html->link('<i class="fa fa-fw fa-eur"></i> Saving List', ['controller' => 'Savings', 'action' => 'view'], ['escape' => FALSE])?>
								</li>
							</ul>
						</li>
						<li>
							<a href="javascript:;" data-toggle="collapse" data-target="#income"><i class="fa fa-fw fa-jpy"></i> Income <i class="fa fa-fw fa-caret-down"></i></a>
							<ul id="income" class="collapse">
								<li>
									<?= $this->Html->link('<i class="fa fa-fw fa-usd"></i> Income List', ['controller' => 'Incomes', 'action' => 'view'], ['escape' => FALSE])?>
								</li>
								<li>
									<?= $this->Html->link('<i class="fa fa-fw fa-cart-plus"></i> Create Income', ['controller' => 'Incomes', 'action' => 'edit'], ['escape' => FALSE])?>
								</li>
							</ul>
						</li>
						<li>
							<a href="javascript:;" data-toggle="collapse" data-target="#expense"><i class="fa fa-fw fa-money"></i> Expense <i class="fa fa-fw fa-caret-down"></i></a>
							<ul id="expense" class="collapse">
								<li>
									<?= $this->Html->link('<i class="fa fa-fw fa-usd"></i> Expense List', ['controller' => 'Expenses', 'action' => 'view'], ['escape' => FALSE])?>
								</li>
								<li>
									<?= $this->Html->link('<i class="fa fa-fw fa-cart-plus"></i> Create Expense', ['controller' => 'Expenses', 'action' => 'edit'], ['escape' => FALSE])?>
								</li>
							</ul>
						</li>
						<li>
							<a href="javascript:;" data-toggle="collapse" data-target="#withdraw"><i class="fa fa-fw fa-android"></i> Withdraw <i class="fa fa-fw fa-caret-down"></i></a>
							<ul id="withdraw" class="collapse">
								<li>
									<?= $this->Html->link('<i class="fa fa-fw fa-usd"></i> Withdraw List', ['controller' => 'Withdraws', 'action' => 'view'], ['escape' => FALSE])?>
								</li>
								<li>
									<?= $this->Html->link('<i class="fa fa-fw fa-cart-plus"></i> Create Withdraw', ['controller' => 'Withdraws', 'action' => 'edit'], ['escape' => FALSE])?>
								</li>
							</ul>
						</li>
					</ul>
				</div>
				<!-- /.navbar-collapse -->
			</nav>

			<div id="page-wrapper">
				<div class="container-fluid">
					<?= $this->fetch('content');?>
				</div>
				<!-- /.container-fluid -->
			</div>
			<!-- /#page-wrapper -->
		</div>
		<!-- /#wrapper -->
	</body>
	<?= $this->fetch('script') ?>
</html>
<?php
} 
?>