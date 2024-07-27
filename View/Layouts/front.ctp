<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>MMD</title>
	<?php //echo $this->Html->meta('icon');?>
	
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Bubblegum+Sans' rel='stylesheet' type='text/css'>   
    
	<?php echo $this->Html->css(array('bootstrap','font-awesome','icomoon','jquery.bxslider', 'font-awesome-animation.min', 'jquery-ui', 'custom','developer','facebox'));?>
    <?php echo $this->Html->script(array('jquery_1.11.1.min','jquery-ui','bootstrap.min', 'jquery.bxslider','tinynav','custom','common','facebox')); ?>      
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php if(isset($check_login_user)){ ?>
		<link type="text/css" href="<?php echo WWW_BASE; ?>app/webroot/cometchat/cometchatcss.php" rel="stylesheet" charset="utf-8">
		<script type="text/javascript" src="<?php echo WWW_BASE; ?>app/webroot/cometchat/cometchatjs.php" charset="utf-8"></script>
	<?php } ?>
  </head>
  <body class="active"><div class="page-wrap">
<?php 
if($this->params['action'] == 'dashboard' || $this->params['action'] == 'manageProfile' || $this->params['controller'] == 'products'|| $this->params['controller'] == 'paymentDetails'|| $this->params['controller'] == 'acedemics' || $this->params['controller'] == 'messages' || $this->params['controller'] == 'schedule' || $this->params['controller'] == 'loan' || $this->params['controller'] == 'favorite' || $this->params['controller'] == 'essays' || $this->params['controller'] == 'resume' || $this->params['controller'] == 'careers'|| $this->params['controller'] == 'edutings' || $this->params['controller'] == 'universities' || $this->params['controller'] == 'ryan'|| $this->params['controller'] == 'search' || $this->params['controller'] == 'request_infos') {
	echo $this->Element("header-dashboard");
} else {
	echo $this->Element("header");
}
 ?>
<?php echo $this->fetch('content'); ?>
</div>
<?php echo $this->Element("footer"); ?>
</div>
<div class="pp_overlay" style="display:none;"></div>
<?php echo $this->Element("confirm_box_front"); ?>
<?php if($this->params['action'] == 'view' && $this->params['controller'] == 'universities'){ ?>
		<!-- Go to www.addthis.com/dashboard to customize your tools -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-577f64d250c0735f"></script>

<?php } ?>
  </body>
</html>