<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>MMD</title>
	<!-- Bootstrap CSS -->
	 
	  <?php echo $this->Html->css(array('admin/download-custom.css'));?>
	</head>
<body style="background: #3292e0;">
	<div class="mmd text-center"> 
		<img src="<?php echo WWW_BASE.'img/admin/logo2.png';?>" >
		<h2>Welcome To MMD</h2>
	 	<p><?php echo $name ?></p>
		<a href="https://www.portal.micromedinc.com/files/video/uploads/<?php echo $fileName ?>" download=""><img src="<?php echo WWW_BASE.'img/admin/video-download.png';?>">Download </a>
	   	<P style="margin-top: 10px;">To see video for more detail</P>
	    <button   onclick="showvideo();" style=" width: 150px;  height: 41px; margin-top: 0px;  border-radius: 25px;  background: #156eb7;  border: 0; color: #fff; font-size: 16px; letter-spacing: 1px; font-weight: 500; line-height: 41px;">View Video</button>
        
		<video   controls id="video-player"  style=" width: 100%; margin-top: 10px; display:none; visibility:hidden;">
          <source  src="<?php echo WWW_BASE ?>/files/apk/video/<?php echo $fileName ?>" type="video/mp4">  
        </video>

	</div>
	<script>
	 
	    function showvideo(){  
	          document.getElementById("video-player").style.display = "";
	          document.getElementById("video-player").style.visibility = "visible";
	    }
	</script>
</body>
</html>