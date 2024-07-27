<div class="content">
      <div class="">
        <div class="page-header-title">
          <!--<h4 class="page-title">Push Notifications</h4>-->
        </div>
      </div>
      <div class="page-content-wrapper ">
        <div class="container">
          <div class="row">
            <div class="col-sm-12">
              <div class="panel panel-primary">
                <div class="panel-body">
				<?php echo $this->Session->flash()."<br/>";?>
                  <div class="row">
				     <?php echo $this->Form->create('UserNotification', array('novalidate' => true, 'url'=>array('controller'=>'notifications','action'=>'admin_notification_send',$user_id),'id'=>'Notification_Send'));  ?>
                    <div class="col-sm-6 col-xs-12">
                      <div class="m-t-20">
                          <div class="form-group">
                            <label>Push Notifications</label>
								<?php echo $this->Form->input('text',array('type'=>'textarea','id'=>'textareaid','class'=>'form-control','label'=>false)); ?>
                          </div>
                        <div class="form-group m-b-0">
                            <div>
								<button type="submit" class="btn btn-primary waves-effect waves-light"> Submit </button>
								<a class="btn btn-default waves-effect m-l-5" href="<?php echo $this->HTML->url('/admin/staff/staff_listing'); ?>">Back </a>
								
                            </div>
                         </div>
                      </div>
                    </div>
					<?php echo $this->Form->end();?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>