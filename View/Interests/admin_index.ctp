<div class="main_page_heading">Manage Interests
	<form method="GET" action="<?php echo WWW_BASE ?>admin/interests/index">
		<div class="search_box">
		<input type="text" name="search" id="search" placeholder="Search By Name" value="<?php echo @$_GET['search']; ?>">
		<input type="submit"  value="" id="searchbtn" class="btn-common">
		</div>
	</form>
</div>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	
    <tr><td height="10px"> </td></tr>
	<tr>
		<td align="center">
	
	<table border="1" class="table_section">
	<h2><?php //echo $this->Session->flash();?></h2>
	<thead>
	<tr class="th_align">
		<!--<th style="width:3%"><?php echo $this->Form->input(false,array('div'=>false,'label'=>false,'type'=>'checkbox','id'=>'selectAll','hiddenField'=>false))?></th>-->
		<th><?php echo $this->Paginator->sort('Interest.name','Name'); ?></th>	
		<th style="text-align:center;"><?php echo $this->Paginator->sort('Interest.status','Status'); ?></th>	
		<th style="text-align:center;">Action</th>	
	</tr>
	</thead>
	<tbody id="contact_del">
		<?php 
	if(!empty($data)) { 
	$row_count = 1;
	foreach ($data as $rec): 
	?>
	<tr>
		
		<td><?php echo $rec['Interest']['name']; ?></td>
        <td style="text-align:center;" class="action_sec">
		<a href="<?php if($rec['Interest']['status']=='1'){$status = '0';}else{$status = '1';}echo WWW_BASE."admin/interests/changestatus/".$rec['Interest']['id']."/status:".$status; ?>"><?php if($rec['Interest']['status']=='1'){ echo "Active";}else{ echo "Inactive";}?></a>
		</td>		
		<td style="text-align:center;" class="action_sec">
		<?php echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>',array('controller'=>'interests','action'=>'edit',$rec['Interest']['id']),array('escape'=>false));?>
		<?php echo $this->Html->link('<i class="fa fa-trash-o"></i>',array('controller'=>'interests','action'=>'delete',base64_encode($rec['Interest']['id'])),array('escape'=>false,'class'=>'confirmLink'));?>
		</td>
	</tr>
	<?php $row_count++;  endforeach; ?>
		<?php unset($cat); ?>
		<?php if($this->params['paging']['Interest']['pageCount']>1){?>
		<tr>
			<td colspan='7' align="center" class="paginat">
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
		<?php } ?>
		<?php } else {?>
	<tr>
		<td align="center" colspan="7">No records found.</td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
	   </td>
	</tr>
</table>
<button type="button" id="show_mod" style="display:none;"class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Send Email</h4>
		<div id="res_status" class=""></div>
      </div>
      <div class="modal-body">
		<?php 
			echo $this->Form->create('Contact', array('id' => 'email_form'));
			echo $this->Form->input('subject', array('type' => 'text', 'label' => 'Subject', 'class' => 'form-control'));
			echo $this->Form->input('body', array('type' => 'textarea', 'label' => 'Message', 'class' => 'form-control'));
			echo $this->Form->input('from_email', array('type' => 'text', 'label' => 'From Email', 'class' => 'form-control'));
			echo $this->Form->input('to_email', array('type' => 'hidden', 'id' => 'to_email'));
			echo $this->Form->button('Send Email', array('type' => 'button', 'id' => 'send_email_content', 'class' => 'btn btn-success'));
			echo $this->Form->end();
		?>		
      </div>
    </div>

  </div>
</div>		