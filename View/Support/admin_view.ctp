<script src="https://cdn.ckeditor.com/4.11.2/standard/ckeditor.js"></script>
<div class="content">
      <div class="">
	   <?php echo $this->Html->css(array('admin/custom.css?v=2'));?>
        <div class="page-header-title">
            <h4 class="page-title">View Ticket</h4>
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
                    <div class="row">
                         <div class="col-sm-4 col-xs-4">
                          Reference No: <b><?php echo $data['Support']['refrance_no'] ?></b>
                      </div>
                      <div class="col-sm-4 col-xs-4">
                          Device Serial No: <b><?php echo $data['Support']['device_serial_no'] ?></b>
                      </div>
                      <div class="col-sm-4 col-xs-4">
                          Caregory Id: <b><?php echo $data['Support']['caregory_id'] ?></b>
                      </div>
                      <div class="col-sm-2 col-xs-2">
                      	<a href="<?php echo $this->Html->url(['controller'=>'Support','action'=>'export',$data['Support']['id']]); ?>"   style="float: right;"><i class="fa fa-file-excel-o" style="font-size:24px"></i></a>

                          Status: <b><?php echo $data['Support']['status'] ?></b>
                          <?php 
                          if($data['Support']['status']=='Closed'){
							     echo '<br>Closed by: ';
							     echo $data['CloseUser']['first_name'].' '.$data['CloseUser']['middle_name'].' '.$data['CloseUser']['last_name']; 
							    echo '<br>'.$data['Support']['closed_at'];
							}
							?>
                      </div>
                      <div class="col-sm-2 col-xs-2">
                      	<?php if($data['Support']['status']=='Open'){ ?>
						   
						     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $this->Html->link('<i class="fa fa-reply" aria-hidden="true"></i>',array('controller'=>'Support','action'=>'admin_reply', $data['Support']['id']),array('escape'=>false,'title'=>'Reply'));?>
						     
                           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                        	<a href="javascript:void(0);"  onClick="setTicketId(<?php echo $data['Support']['id'] ?>)"  data-toggle="modal" data-target="#myModal"><i class="fa fa-check-circle" aria-hidden="true"></i></a>
                            <?php } ?>
                      </div>
                    </div>
				 <?php echo $this->Session->flash()."<br/>";?>
                  
              	<div class="post_rows">
              		<div class="row">
					   
					    	<div class="col-sm-12 col-xs-12 posts_info">
              	<?php foreach($datas as $key => $value){ ?>
			 
					
					    	        <div class="post_cont"><?php echo $value['Support']['message'] ?>
					    	        	<div class="post_info">
					    	        Posted By: <?php if($value['Support']['user_id']==$data['User']['id']){  
					    	            echo $value['User']['first_name'].' '.$value['User']['middle_name'].' '.$value['User']['last_name'];
					    	        }else{
					    	           echo $value['User']['first_name'].' '.$value['User']['middle_name'].' '.$value['User']['last_name'];
					    	        } ?>
					    	         <span> <?php echo $value['Support']['created_at']; ?></span>
					    	         <?php if($value['Support']['file']!=''){ ?>
					    	        <a href="https://www.portal.micromedinc.com/support/uploads/<?php echo $value['Support']['file'] ?>" download="">Download attachment</a> 
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
					    	            echo $data['User']['first_name'].' '.$data['User']['middle_name'].' '.$data['User']['last_name'];
					    	        } ?>
					    	         <span> <?php echo $data['Support']['created_at']; ?></span>
					    	         <?php if($data['Support']['file']!=''){ ?>
					    	        <a href="https://www.portal.micromedinc.com/support/uploads/<?php echo $data['Support']['file'] ?>" download="">Download attachment</a> 
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
    <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
       <?php echo $this->Form->create('Support', array('novalidate' => true,'url'=>array('controller'=>'support','action'=>'admin_closed'),'enctype'=>'multipart/form-data'));?>

      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Enter your comment</h4>
        </div>
        <div class="modal-body">
        	<?php echo $this->Form->input('message',array('type'=>'textarea','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Please Enter your comment",'required'=>true)); ?>

        	<?php echo $this->Form->input('parent_id',array('type'=>'hidden','class'=>'form-control','label'=>false,'div'=>false,'placeholder'=>"Content",'required'=>true)); ?> 
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Close Ticket</button>
        </div>
      </div>
      
    </div>
  </div>
	 <script>
	 	function setTicketId(argument) {
		$("#SupportParentId").val(argument);
	}
			//CKEDITOR.replace( 'data[Support][message]' );
	</script>
 