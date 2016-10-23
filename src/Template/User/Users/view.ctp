<!-- Page Heading -->
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Your user list
		</h1>
		<ol class="breadcrumb">
			<li>
				<i class="fa fa-home"></i><?php echo $this->Html->link(' Home', ['controller' => 'users', 'action' => 'home'])?>
			</li>
			<li class="active"><?php echo $this->Html->link('Users Management', ['controller' => 'Users', 'action' => 'view'])?></li>
		</ol>
	</div>
</div>
<!-- /.row -->

<div class="row">
	<div class="col-sm-12 col-md-12 col-lg-12">
		<?php echo $this->Flash->render()?>
	</div>
</div>

<?php
use App\Lib\Constants;
use Cake\Routing\Router;

if (empty($users) || (count($users) == 0)) {
	echo "There is no users!";
} else {
?>

<div class="row">
	<div class="col-lg-12 col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-money fa-fw"></i> User List</h3>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th>Index</th>
								<th>Name</th>
								<th>BirthDay</th>
								<th>Email</th>
								<th>Status</th>
								<th></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						<?php
						$i = 1;
						foreach ($users as $user) {
							$urlDelete = '/'.Constants::USER_PREFIX.'/Users/Delete/'.$user->user_id;
							$urlDelete = Router::url($urlDelete, TRUE);
						?>
							<tr>
								<td><?= $i?></td>
								<td><?= $user->user_name?></td>
								<td><?= $user->displayBirthDayinView()?></td>
								<td><?= h($user->email)?></td>
								<td><?= $user->displayStatusInView()?></td>
								<td><?= $this->Html->link('<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit', ['controller' => 'Users', 'action' => 'edit', $user->user_id], ['class' => 'btn btn-primary', 'escape' => FALSE])?></td>
								<td><a data-toggle="modal" data-target="#confirmDelete" data-href="<?php echo $urlDelete?>" class="btn btn-danger"><i class="fa fa-times" aria-hidden="true"></i> Delete</a></td>
							</tr>
						<?php
							$i++;
						}
						?>
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

<div class="row">
	<div class="col-lg-12 col-sm-12 col-md-12">
		<?php echo $this->Html->link('<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Add a new user', ['controller' => 'Users', 'action' => 'edit'], ['class' => 'btn btn-primary', 'escape' => FALSE])?>
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
				<p>Do you want to delete this user ?</p>
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
	//delete button on modal
	$('#confirmDelete').on('show.bs.modal', function(e) {
		$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
	});
});
<?php echo $this->Html->scriptEnd();?>

