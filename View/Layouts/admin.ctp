<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<?php //die(WWW_BASE); ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>MMD</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
	<link rel="shortcut icon" href=<?php echo WWW_BASE.'img/favicon1.ico';?>>
	

	<script type="text/javascript" src="<?php echo WWW_BASE; ?>js/admin/jquery.min.js"></script>
	<script>
		var ajax_url = "<?php echo WWW_BASE; ?>";
	</script>
	<script type="text/javascript" src="<?php echo WWW_BASE; ?>js/admin/facebox.js"></script>
	<?php
		//echo $this->Html->meta('icon');
		echo $this->Html->css(array(
									//'plugins/morris/morris.css',
									//'plugins/datatables/jquery.dataTables.min.css',
									//'plugins/datatables/buttons.bootstrap.min.css',
									//'plugins/datatables/fixedHeader.bootstrap.min.css',
									//'plugins/datatables/responsive.bootstrap.min.css',
									//'plugins/datatables/dataTables.bootstrap.min.css',
									//'plugins/datatables/scroller.bootstrap.min.css'
		));
		echo $this->Html->css(array('admin/bootstrap.min','admin/icons','admin/style','admin/new_main','admin/facebox'));
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
	
	
	
</head>
<style>
td.action_sec {
    cursor: pointer;
}

</style>
<body class="fixed-left">
<?php #header('Access-Control-Allow-Origin: *'); ?>
	<div id="wrapper">
		<?php echo $this->element('admin_header');?>
		<?php echo $this->element('admin_left_nav');?>
		<div class="content-page">
			<?php echo $this->fetch('content'); ?>
			<?php echo $this->element('admin_footer');?>
		</div>
		
	</div>
	<?php echo $this->Html->Script(array('admin/bootstrap.min','admin/modernizr.min','admin/detect','admin/fastclick','admin/jquery.slimscroll','admin/jquery.blockUI','admin/waves','admin/wow.min','admin/jquery.nicescroll','admin/jquery.scrollTo.min')); ?>
	<?php echo $this->Html->Script(array(
		//'plugins/morris/morris.min.js',
										 //'plugins/raphael/raphael-min.js',
										 //'pages/dashborad.js',
										 //'plugins/datatables/jquery.dataTables.min.js',
										 //'plugins/datatables/dataTables.bootstrap.js',
										 //'plugins/datatables/dataTables.buttons.min.js',
										 //'plugins/datatables/buttons.bootstrap.min.js',
										 //'plugins/datatables/pdfmake.min.js',
										 //'plugins/datatables/vfs_fonts.js',
										 //'plugins/datatables/buttons.html5.min.js',
										 //'plugins/datatables/buttons.print.min.js',
										 //'plugins/datatables/dataTables.fixedHeader.min.js',
										 //'plugins/datatables/dataTables.keyTable.min.js',
										 //'plugins/datatables/dataTables.responsive.min.js',
										 //'plugins/datatables/responsive.bootstrap.min.js',
										 //'plugins/datatables/dataTables.scroller.min.js',
										 //'pages/datatables.init.js'
										 )); ?>
	
	
	
	
	
	<!--<script src="<?php //echo WWW_BASE; ?>plugins/datatables/jszip.min.js"></script>-->

	
	<?php echo $this->Html->script('admin/app');?>
	<?php echo (!empty($this->request->query['bt_debug']))?$this->element('sql_dump'):""; ?>
	</body>
</html>
<script>
$(document).ready(function (){
 // $('#datatable').dataTable({
 //   destroy: true,
 //  'aoColumnDefs': [{
	//    'bSortable': false,
	// 	  'aTargets': [-1,'nosort'],
	// }],
	
	
	// "order": [[ 0, "DESC" ]]
 //  });
  
	// $('#datatable_1').dataTable({ 
	// 	destroy: true,
	// 	"searching": true,
	// 	'aoColumnDefs': [{
	// 		'bSortable': false,
	// 		'aTargets': ['notification','nosort']
	// 	}]
	// 	//"order": [[ 0, "DESC" ]]
	// });
});

$('.page-header-title .page-title').append("<span class='pull-right' style='color:white'><?php $office=$this->Session->read('office_name');if(!empty($office)){echo "Office: ".$office;}?></span>");
</script>