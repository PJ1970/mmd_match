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
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $cakeDescription ?>:
		<?php echo $this->fetch('title'); ?>
	</title>
	 <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('bootstrap.min');
		echo $this->Html->css('https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css');
			echo $this->Html->css('https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css');
		echo $this->Html->css('bootstrap.min');
		echo $this->Html->css('AdminLTE.css');
		echo $this->Html->css('skin-blue.min');
		echo $this->Html->script('jquery.min');
		echo $this->Html->script('bootstrap.min');
		echo $this->Html->script('app.min');
		//echo $this->Html->css('bootstrap.min');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
	<div class="wrapper">

	  

			<?php echo $this->element('header');?>
<?php echo $this->element('left');?>

		
		 <!-- Main content -->
        <section class="content">
		<h1>
					Sorry! you dont have permission to view this page
		</h1>


    </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
	</div>
	<?php // echo $this->element('sql_dump'); ?>
</body>
</html>
