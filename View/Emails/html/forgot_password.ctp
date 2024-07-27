
  <tr>
	<td style="padding:25px 15px; background:#fafafa">
		<h1 style="font-size:16px; color:#000000; margin:0 0 5px 0;">Hi <?php echo ucfirst(@$first_name).' '.ucfirst(@$last_name); ?>!</h1>
		<br/>
		<p style="color:#555555; font-size:13px; margin:0px;">We have reset your account password based on your Forgotten Password request. Your account details are now as follows:<br /><br /> 
		
		Username: <?php echo @$username;?><br/>
		<!-- Password: <b><?php //echo @$custom_password;?></b> -->
		Reset Link: <b><a href="<?php echo @$reset_link;?>" target="_blank">Click here to reset your password</a></b>

		<br/><br/>
		Thank you
		
		</p>
	</td>
  </tr>
  <tr>
	<td style="background:#e4e4e4; padding:8px 0px; text-align:center;">
		<span style="font-size:12px; color:#555555;">Copyright &copy; <?php echo date("Y");?> <a style="color:#555" href="https://micromedinc.com/">UnityEye</a> All Rights Reserved</span>
	</td>
  </tr>