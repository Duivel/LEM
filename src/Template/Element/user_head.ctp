<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta name="ROBOTS" content="NOINDEX, NOFOLLOW" />
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="Expires" content="-1" />
<meta name="author" content="Quan">

<title><?php echo $title_for_layout; ?></title>

<!-- Start: CSS -->
<!-- <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500"> -->
<?php 
	echo $this->Html->css('/css/user/bootstrap.css');
	echo $this->Html->css('/css/user/sb-admin.css');
	echo $this->Html->css('/css/user/font-awesome.css');
	echo $this->Html->css('/css/user/bootstrap-datepicker.css');
	//echo $this->Html->css('/css/user/jquery.jqplot.css');
	
 ?>
<!-- End: CSS -->

<!-- Start: JavaScript -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.js"></script>
<?php 
	echo $this->Html->script('/js/user/bootstrap.js');
	echo $this->Html->script('/js/user/bootstrap-datepicker.min.js');
	echo $this->Html->script('/js/user/Chart.js');
	
	
	//JQPlot Charts JavaScript
// 	echo $this->Html->script('/js/user/excanvas.js');
// 	echo $this->Html->script('/js/user/jquery.jqplot.min.js');
// 	echo $this->Html->script('/js/user/plugins/jqplot.barRenderer.js');
// 	echo $this->Html->script('/js/user/plugins/jqplot.pieRenderer.js');
// 	echo $this->Html->script('/js/user/plugins/jqplot.categoryAxisRenderer.js');
?>
<!-- End: JavaScript -->

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->