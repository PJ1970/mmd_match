<tr>
	<td style="padding:25px 15px; background:#fafafa">
		<h1 style="font-size:16px; color:#000000; margin:0 0 5px 0;">Hi Admin !</h1>
		<br/>
		<p style="color:#555555; font-size:13px; margin:0px;">You have recieved new support ticket from <?php echo $Office['name'] ; ?><br /> 
		<p style="color:#555555; font-size:13px; margin:0px;">Ticket added by <?php echo $User['username'] ; ?><br /><br /> 
		<b>Posted By:</b> <?php	echo $User['first_name'].' '.$User['middle_name'].' '.$User['last_name'];?><br/>
		<b>Title:</b> <?php echo $Support['title'] ;?><br/>
		<b>Category:</b> <?php echo $Category['name'];?><br/>
        <b>Device Serial No.:</b>  <?php if(!empty($Support['device_serial_no'])){ echo $Support['device_serial_no']; }?><br/>
		<b>Model:</b>  <?php if(!empty($Support['model'])){ echo $Support['model']; }?><br/>
        <b>Message:</b> <?php echo $Support['message'] ;?><br/>
        <b>File:</b> <?php if(!empty($Support['file'])){ echo $Support['file']; }?><br/>
        <br/><br/>
			
		</p>
	</td>
  </tr>
  <tr>
	<td style="background:#e4e4e4; padding:8px 0px; text-align:center;">
		<span style="font-size:12px; color:#555555;">Copyright &copy; <?php echo date("Y");?> <a style="color:#555" href="https://micromedinc.com/">UnityEye</a> All Rights Reserved</span>
	</td>
  </tr>