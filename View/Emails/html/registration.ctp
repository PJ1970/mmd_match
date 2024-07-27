<body style="margin:0; padding:0;">
<table cellpadding="0" cellspacing="0" border="0" style="margin:0 auto; width:100%; max-width:600px; font-family:Arial, Helvetica, sans-serif; font-size:13px; line-height:20px; ">
  <tr>
    <td style="background-color:#ffffff;"><img src="http://braintechnosys.biz/mock-ups/eduting/mailer/header.jpg" /></td>
  </tr>
  <tr>
	<td style="padding:25px 15px; background:#fafafa">
		<h1 style="font-size:16px; color:#000000; margin:0 0 5px 0;">Hi, <?php echo ucfirst($first_name);?>!</h1>
		<br/>
		<p style="color:#555555; font-size:13px; margin:0px;">We would like to welcome you to Eduting.com, the most connectable educational site for Korean-Americans and Koreans.<br /><br />
		 Your account details are now as follows:
		 <br /><br />
			
		Email: <?php echo $email;?><br/>
		Password: <?php echo $custom_password;?>	

		<br/><br/>
		Thank you.
		</p>
	</td>
  </tr>
  <tr>
	<td style="background:#e4e4e4; padding:8px 0px; text-align:center;">
		<span style="font-size:12px; color:#555555;">Copyright &copy; 2016 <a style="color:#555" href="http://www.eduting.com/">Eduting.com</a> All Rights Reserved</span>
	</td>
  </tr>
</table>
</body>