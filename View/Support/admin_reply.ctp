<script src="https://cdn.ckeditor.com/4.11.2/standard/ckeditor.js"></script>
<div class="content">
      <div class="">
	   <?php echo $this->Html->css(array('admin/custom.css?v=2'));?>
        <div class="page-header-title">
            <h4 class="page-title">Reply Ticket</h4>
		 <?php /*if(!isset($this->request->data['Office']['id'])):?>
          <h4 class="page-title">Add Office</h4>
		  <?php else: ?>
		  <h4 class="page-title">Edit Page</h4>
		  <?php endif; */?>
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
                      <div class="col-sm-6 col-xs-6">
                          Reference No: <b><?php echo $data['Support']['refrance_no'] ?></b>
                      </div>
                      <div class="col-sm-6 col-xs-6">
                          Device Serial No: <b><?php echo $data['Support']['device_serial_no'] ?></b>
                      </div>
				    <?php echo $this->Form->create('Support', array('novalidate' => true,'url'=>array('controller'=>'support','action'=>'admin_reply',$data['Support']['id']),'enctype'=>'multipart/form-data'));?>
                    
					<div class="col-sm-12 col-xs-12">
						<div class="m-t-20">
					  
						 
							
						 
							<div class="form-group">
								<label>Message</label>
								<?php echo $this->Form->input('message',array('type'=>'textarea','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Content",'required'=>true)); ?>
							</div>
							<div class="form-group">
								<label>Add attach file</label>
								<?php echo $this->Form->input('file',array('type'=>'file','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"File",'required'=>true)); ?>
							</div>
							<div class="form-group m-b-0">
								<div>
									<button type="submit" class="btn btn-primary waves-effect waves-light"> Submit </button>
								</div>
							</div>
						</div>
					<?php echo $this->Form->end();?>
					</div>
				
					
              </div>
              	<div class="post_rows">
              			<div class="row">
					    	<div class="col-sm-12 col-xs-12 posts_info">
              	<?php foreach($datas as $key => $value){ ?>
				
				
					    	        <div class="post_cont"><?php echo $value['Support']['message'] ?>
					    	        	<div class="post_info">
					    	        Posted By: <?php if($value['Support']['user_id']==$data['User']['id']){  
					    	            echo $data['User']['first_name'].' '.$data['User']['middle_name'].' '.$data['User']['last_name'];
					    	        }else{
					    	            echo $value['User']['first_name'].' '.$value['User']['middle_name'].' '.$value['User']['last_name'];
					    	        } ?>
					    	         <span> <?php echo $data['Support']['created_at'] ?></span>
					    	         <?php if($value['Support']['file']!=''){ ?>
					    	        <a href="<?php echo WWW_BASE.'support/uploads/'.$value['Support']['file'] ?>" download="">Download attachment</a> 
					    	    <?php } ?>
					    	        </div>
					    	        </div>
					    	        
					    	    
					    	
					<?php } ?>
				</div>
					</div>
					<div class="row" >
					    	<div class="col-sm-12 col-xs-12 posts_info">
					    	     <div class="post_cont"><?php echo $data['Support']['message'] ?>
					    	     	<div class="post_info">
					    	        Posted By: <?php if($data['Support']['user_id']==$data['User']['id']){  
					    	            echo $data['User']['first_name'].' '.$data['User']['middle_name'].' '.$data['User']['last_name'];
					    	        }else{
					    	           // echo "Admin";
					    	        	 echo $data['User']['first_name'].' '.$data['User']['middle_name'].' '.$data['User']['last_name'];
					    	        } ?>
					    	         <span> <?php echo $data['Support']['created_at'] ?></span>
					    	         <?php if($data['Support']['file']!=''){ ?>
					    	        <a href="<?php echo WWW_BASE.'support/uploads/'.$data['Support']['file']; ?>" download="">Download attachment</a> 
					    	    <?php } ?>
					    	        </div>
					    	     </div>
					    	        
					    	     
					    	</div>
					</div>
						</div>
            </div>
          </div>
        </div>
      </div>
    </div>
	 <script>
			CKEDITOR.replace( 'data[Support][message]' );
	</script>
 