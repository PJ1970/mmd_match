<script>
$(document).ready(function(){
	$('a[rel*=facebox]').facebox();
});
</script>
<div class="right_content">
	<div class="notifiction_block">
	   
		<h5> Push Notifications <a href="<?php echo WWW_BASE.'admin/notifications/notification_send/'; ?>" rel="facebox">New Notification</a></h5>
		<div class="noti_dec">
		   <?php if(!empty($user_notifications)){
			   foreach($user_notifications as $user_notification){
		   ?>
				<div class="notes">
					<div class="note_contain"><span><?php echo date('d F Y H:i',strtotime($user_notification['UserNotification']['created']));?></span>
					<p><?php echo $user_notification['UserNotification']['text'];?></p></div>
					<?php
						echo $this->Html->link($this->Html->image('cross.png',array('height'=>'14px')),"javascript:void(0)",array('alt'=>'Delete','escape'=>false, 'title'=>'delete', 'rel'=>WWW_BASE.'admin/notifications/notification_delete/'.$user_notification['UserNotification']['id'], 'class'=>'resend bt_del_dev','data-toggle'=>'modal','data-target'=>'#myModal1'));
					?>
					<a class="resend" href="<?php echo WWW_BASE.'admin/notifications/notification_resend/'.$user_notification['UserNotification']['id']; ?>">Resend</a>
					
					
				</div>
			<?php 
			   }
			}else{
				?>
				 <div class="notes">
					<span></span>
					<p>Notification not found.</p>
				</div>
			<?php 
			}?>
		</div>
	</div>
</div>
<script>
$(document).ready(function(){ 
	 var targetUrl;
	 $('.resend').click(function(event){   
	   targetUrl = $(this).attr("rel");
	 });
	 $('#delete_order_cnfrm').click(function(){
		 window.location.href = targetUrl; 
	 });
 
});
</script>