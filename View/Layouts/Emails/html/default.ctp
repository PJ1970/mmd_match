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
 * @package       app.View.Layouts.Email.html
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
	<title><?php echo $this->fetch('title'); ?></title>
</head>

<body style="margin:0; padding:0; background-color:#f2f2f2;">
	<table width="600" border="0" cellpadding="0" cellspacing="0" bgcolor="#fff" style="margin:0 auto">
	  <tr>
	    <td width="50px">&nbsp;</td>
	  </tr>
	  
		  
			<?php echo $this->fetch('content'); ?>

		
	  <tr>
	    <td colspan="3" align="center" style="background:#f2f2f2; font-family:Arial, Helvetica, sans-serif; font-size:10px; line-height:14px; color:#999999; padding:15px 0 20px;"></td>
	  </tr>  
	</table>
</body>

</html>