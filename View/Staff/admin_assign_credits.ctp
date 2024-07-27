
<div class="content">
      <div class="">
        <div class="page-header-title">
          <h4 class="page-title">Assign Credits to Staff</h4>
		  
        </div>
		 
      </div>
      <div class="page-content-wrapper ">
       <div class="container">
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-primary">
                <div class="panel-body">
				<?php echo $this->Session->flash()."<br/>";?>
				<div class="col-md-12">
					<?php echo $this->Form->create('User',array('type' => 'post','url' => array('controller' => 'staff','action' => 'assign_credits'))); ?>
					
					 <div class="col-md-4">  
						<?php echo $this->Form->input('id',array('div' => false,'label' => false,'type' =>'select','empty'=>'Select Staff Name','class' => 'form-control','options' => @$staff)); ?>
						 
					</div>
					<div class="col-md-2">  
						<?php echo $this->Form->input('credits',array('div' => false,'label' => false,'value' => @$search,'type' =>'text','class' => 'input_credit form-control','placeholder' => 'Enter Credits')); ?>
						 
					</div>
					<div class="form-group m-b-0 col-md-2" style="width:auto;">
						<button type="submit" class="btn btn-primary waves-effect waves-light searchBtn" > Submit </button>	
					</div>
					<div class="col-md-4" style="padding-left:0;">  
						<h4><span>Total Availabe Credits:  </span><strong data="<?php echo ($avl_crd>0)? $avl_crd : '0';  ?>" class="avl_credit"><?php echo ($avl_crd>0)? $avl_crd : '0';  ?></strong></h4>
					</div>
					<?php echo $this->Form->end(); ?>
					 
				</div>
				<div class="col-md-12" style="text-align:center;">
					<h6 class="errorSpan" style="text-align:center;color:red;padding-top:5px;padding-bottom:10px;"> </h6>
				</div>
				
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <table id="" class="table table-striped table-bordered">
                        <thead>
                          <tr>
							<th style="width:34px;">S.No</th>
							<th> <?php echo $this->Paginator->sort('User.first_name','Name'); ?></th>
                            <th> <?php echo $this->Paginator->sort('User.email','Email'); ?></th>
                            <th> <?php echo $this->Paginator->sort('User.credits','Credits'); ?></th>
                            <th>Remove Credits</th>
                          </tr>
                        </thead>
                        <tbody>
						
						<?php if(!empty($datas)) {foreach($datas as $key=>$data){ ?>
						<tr>
							<td data-order="<?php echo $data['User']['id']; ?>"><?php echo $key+1; ?></td>
							<td><?php echo $data['User']['first_name'].' '.$data['User']['last_name']; ?></td>
							
							<td><?php echo $data['User']['email'];?></td>		
							<td><?php echo $data['User']['credits']; ?></td>
							
							<td class="action_sec">
						 
							<?php echo $this->Html->link('<i class="fa fa-trash" aria-hidden="true"></i>',array('controller'=>'staff','action'=>'admin_remove_credits', $data['User']['id']),array('escape'=>false,'title'=>'Remove Credits','id'=>'delete-button'));?>
							&nbsp;&nbsp;	
						
							</td>
						</tr>
						<?php }
						  if(isset($this->params['paging']['User']['pageCount'])){ ?>
							<tr> 
								<td colspan='9' align="center" class="paginat">
									<div class="pagi_nat">
									 <!-- Shows the next and previous links -->
									 <?php echo $this->Paginator->prev('<'); ?>
									 <?php echo $this->Paginator->numbers(
										 array(
										  'separator'=>''
										  )
										  ); ?>
									 <?php echo $this->Paginator->next('>'); ?><br>
									 <!-- prints X of Y, where X is current page and Y is number of pages -->
									 </div>
									<div class="pagi"><?php echo $this->Paginator->counter();echo "&nbsp Page"; ?></div>
								</td>
							</tr>
						<?php }  
						}else{echo "<tr><td colspan='7' style='text-align:center;'>No record found.</td></tr>";} ?>
                         
                        </tbody>
                      </table>
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

$("#delete-button").click(function(){
    if(confirm("If you will remove credits of this staff. All of the credits will be taken back and added to respective office\'s credits. Are you sure want to remove credits?")){
       return true;
    }
    else{
        return false;
    }
});
 $(document).on('keypress','.input_credit',function (e) {
 if (e.which != 8 && e.which != 0 && e.which  != 46  && (e.which < 48 || e.which > 57)) {
   return false;
	}
}); 
$('.input_credit').bind('keyup change',function(){
	var credit  = parseFloat($(this).val().trim()); 
	var avl_credit  = parseFloat($('.avl_credit').attr('data').trim());
	if(avl_credit =='0'){
		$('.errorSpan').text('You have no remaining credits for assign to staff. Please purchase credits.');
	}else{
		$('.errorSpan').text('');
	}
	if(isNaN(credit) || avl_credit=='0'){
		credit =0;
		$(this).val('');
	}
	 
	var remaining_credit = avl_credit-credit;
	if(remaining_credit < 0){
		remaining_credit =0;
		$('.errorSpan').text('You have no enough credits to assign. Please assign credits upto '+avl_credit);
		$(this).val('');
		$('.avl_credit').text($('.avl_credit').attr('data').trim());
	}else if(avl_credit!='0'){
		$('.errorSpan').text('');
		$('.avl_credit').text(remaining_credit);
		 
	}
});
/* $('.input_credit').change(function(){
	var credit  = parseFloat($(this).val().trim()); 
	var avl_credit  = parseFloat($('.avl_credit').attr('data').trim());
	if(avl_credit =='0'){
		$('.errorSpan').text('You have no remaining credits for assign to staff. Please purchase credits.');
	}else{
		$('.errorSpan').text('');
	}
	if(isNaN(credit) || avl_credit=='0'){
		credit =0;
		$(this).val('');
	}
	 
	var remaining_credit = avl_credit-credit;
	if(remaining_credit < 0){
		remaining_credit =0;
		$('.errorSpan').text('You have no enough credits to assign. Please assign credits upto '+avl_credit);
		$(this).val('');
		$('.avl_credit').text($('.avl_credit').attr('data').trim());
	}else if(avl_credit!='0'){
		$('.errorSpan').text('');
		$('.avl_credit').text(remaining_credit);
		 
	}
}); */

jQuery(document).ready(function(){
	jQuery(document).on('click',".SubAdminDetail",function() {
	//jQuery('#myPleaseWait').modal('show');
 var subAdminId = jQuery(this).attr("subAdminId");
 jQuery("#subadmin_detail").load("<?php echo WWW_BASE; ?>admin/staff/staffView/"+subAdminId+ "?" + new Date().getTime(), function(result) {
  //jQuery('#myPleaseWait').modal('hide');
  jQuery("#subAdminView").modal("show");
 });
});	
});

</script>	
          