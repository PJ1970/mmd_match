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

$cakeDescription = __d('cake_dev', 'S2SApp :: Admin Panel');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>MMD</title>
<?php
echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');
echo $this->Html->Script(array('admin/jquery.min'));
 
?>
<script>
		var ajax_url = "<?php echo WWW_BASE; ?>";
</script>
<?php echo $this->Html->Script(array('admin/bootstrap.min','admin/modernizr.min','admin/detect','admin/fastclick','admin/jquery.slimscroll','admin/jquery.blockUI','admin/waves','admin/wow.min','admin/recaptcha__en','admin/jquery.nicescroll','admin/jquery.scrollTo.min','admin/app'));?>    
<?php echo $this->Html->css(array('admin/bootstrap.min','admin/icons','admin/style','admin/styles__ltr','admin/new_main.css?v=1'));?>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<link rel="shortcut icon" href=<?php echo WWW_BASE.'img/admin/favicon1.ico';?>>
<link href="<?php echo WWW_BASE.'css/admin/fontfamily.css';?>" rel="stylesheet">
</head>
<body>
<div class="accountbg"></div>
<div class="wrapper-page">

  <?php echo $this->fetch('content'); ?>
  <?php echo (!empty($this->request->query['bt_debug']))?$this->element('sql_dump'):""; ?>
</div>
</body>
</html>