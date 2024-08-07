<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>EDUTING</title>
	<?php echo $this->Html->meta('icon');?>
	
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Bubblegum+Sans' rel='stylesheet' type='text/css'>   
    
	<?php echo $this->Html->css(array('bootstrap','font-awesome','icomoon','jquery.bxslider','custom','developer', 'jquery-ui'));?>
    <?php echo $this->Html->script(array('jquery_1.11.1.min','jquery-ui','bootstrap.min', 'jquery.bxslider','custom')); ?>      
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    
  </head>
  <body class="active">
<?php echo $this->Element("header-dashboard"); ?>
<?php echo $this->fetch('content'); ?>
<?php echo $this->Element("footer"); ?>
</div>
  </body>
</html>