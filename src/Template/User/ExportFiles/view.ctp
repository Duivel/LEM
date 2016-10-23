<!-- Page Heading -->
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			Your export file list
		</h1>
		<ol class="breadcrumb">
			<li>
				<i class="fa fa-home"></i> <?php echo $this->Html->link('Home', ['controller' => 'users', 'action' => 'home'], ['escape' => FALSE])?>
			</li>
			<li><i><?php echo $this->Html->link('Users Management', ['controller' => 'Users', 'action' => 'view'])?></i></li>
			<li class="active">Export Files</li>
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

if (empty($files) || (count($files) == 0)) {
	echo "There is no files!";
} else {
?>

<div class="row">
	<div class="col-lg-12 col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-money fa-fw"></i> Export List</h3>
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th>Index</th>
								<th>Name</th>
								<th>Day</th>
								<th>Time</th>
								<th>Type</th>
								<th>Status</th>
								<th></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						<?php
						$i = 1;
						foreach ($files as $file) {
							$urlDelete = '/'.Constants::USER_PREFIX.'/ExportFiles/Delete/'.$file->export_id;
							$urlDelete = Router::url($urlDelete, TRUE);
						?>
							<tr>
								<td><?= $i?></td>
								<td><?= $file->file_name?></td>
								<td><?= $file->displayDayInView()?></td>
								<td><?= $file->displayTimeInView()?></td>
								<td><?= $file->displayTypeInView()?></td>
								<td><?= $file->displayStatusInView()?></td>
								<td><?= $this->Html->link('<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Download', ['controller' => 'ExportFiles', 'action' => 'download', $file->export_id], ['class' => 'btn btn-primary', 'escape' => FALSE])?></td>
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

<!-- Modal -->
<div class="modal fade" id="confirmDelete" tabindex="-1" role="dialog" aria-labelledby="labelDelete" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Confirm Delete</h4>
			</div>
			<div class="modal-body">
				<p>Do you want to delete this file ?</p>
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

