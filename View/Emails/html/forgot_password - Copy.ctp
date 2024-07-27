  <tr>
    <td colspan="3" style="height:4px; background-color:#f6921e;"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="100%" style="font-family:Arial, Helvetica, sans-serif; color:#3f5e73; font-size:12px; line-height:16px; padding:40px 0 0;">
	    Dear <?php echo $name;?>,
		<br /><br />
		Thanks for registering with us.Your new paswword is:- <strong><?php echo $password;?></strong>
		<br /><br />
		Thanks & Regards<br />
		<?php echo SIGNATURE; ?>
    </td>
    <td>&nbsp;</td>
  </tr>
  
  <body style="margin:0; padding:0;">
<table cellpadding="0" cellspacing="0" border="0" style="margin:0 auto; width:100%; max-width:600px; font-family:Arial, Helvetica, sans-serif; font-size:13px; line-height:20px; ">
  <tr>
    <td style="background-color:#ffffff;"><img src="http://braintechnosys.biz/mock-ups/eduting/mailer/header.jpg" /></td>
  </tr>
  <tr>
	<td style="padding:25px 15px; background:#fafafa">
		<h1 style="font-size:16px; color:#000000; margin:0 0 5px 0;">Hi, <?php echo ucfirst($name);?>!</h1>
		<br/>
		<p style="color:#555555; font-size:13px; margin:0px;">We have reset your account password based on your Forgotten Password request. Your account details are now as follows:<br /><br /> 
		
		Email: <?php echo $email;?>
		Password: <?php echo $user['User']['custom_password'];?>	

		<br/><br/>
		Thank you.
		</p>
	</td>
  </tr>
  <tr>
	<td style="background:#e4e4e4; padding:8px 0px; text-align:center;">
		<span style="font-size:12px; color:#555555;">Copyright © 2016 <a style="color:#555" href="http://www.eduting.com/">Eduting.com</a> All Rights Reserved</span>
	</td>
  </tr>
    
  
</table>
</body>
