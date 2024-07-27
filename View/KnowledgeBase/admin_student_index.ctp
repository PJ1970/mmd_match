<div class="main_page_heading">
	<form method="GET" action="<?php echo WWW_BASE ?>/admin/knowledgeBase/student_index">
		<div class="search_box">
		<input type="text" name="search" id="search" placeholder="Search" value="<?php echo @$_GET['search']; ?>">
		<input type="submit"  value="" id="searchbtn" class="btn-common">
		</div>
	</form>
</div>
<div class="outer-table-responsive">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td class="page-title change_password_title">
			Manage Knowledge Base (For Student) 	
		</td>
	</tr>
	<tr>
		<td class="pull-left">
			<a href="javascript:void(0)" data-value="delete-sel" data-module="knowledgeBase" class="back_btn back_btn_custom buttonDelete" title="">Delete Selected</a>
		</td>
		<td class="pull-right">
			<?php echo $this->Html->link('Add Knowledge Base',array('action'=>'admin_student_add'),array('class'=>"back_btn turn-left",'escape'=>false)); ?>
		</td>
	</tr>	
	<tr>
		<td align="center">
	
	<table border="1" class="table_section">
	<tr class="th_align">
		<th style="width:3%"><?php echo $this->Form->input(false,array('div'=>false,'label'=>false,'type'=>'checkbox','id'=>'selectAll','hiddenField'=>false))?></th>
		<th><?php echo $this->Paginator->sort('KnowledgeBase.title','Title'); ?></th>
		<!--<th><?php echo $this->Paginator->sort('KnowledgeBase.description','Description'); ?></th>
		<th><?php echo $this->Paginator->sort('KnowledgeBase.youtube_id','youtube Link'); ?></th>		
		<th style="text-align:center;">Status</th>-->
		<th><?php echo $this->Paginator->sort('KnowledgeBase.created','Created On'); ?></th>
		<th style="text-align:center;">Action</th>	
	</tr>
	<?php if(!empty($data)) { 
	$row_count = 1;
	foreach ($data as $rec): ?>
	<tr>
			<td align="center">
		<?php echo $this->Form->input('',array('div'=>false,'label'=>false,'type'=>'checkbox','class'=>'selectProduct','hiddenField'=>false,'name'=>'id[]','value'=>$rec['KnowledgeBase']['id'], 'id' => 'id_' . $rec['KnowledgeBase']['id']))?>
		</td>
		<td><?php echo $rec['KnowledgeBase']['title']; ?></td>
		<!--<td><?php echo $rec['KnowledgeBase']['description']; ?></td>		
		<td><?php echo $rec['KnowledgeBase']['youtube_id']; ?></td>
		<td style="text-align:center;"><a href="<?php if($rec['KnowledgeBase']['status']=='Active'){$status = 'Inactive';}else{$status = 'Active';}echo WWW_BASE."admin/knowledgeBase/changestatus/".$rec['KnowledgeBase']['id']."/status:".$status; ?>"><?php echo $rec['KnowledgeBase']['status']; ?></a></td>-->
		<td><?php echo date('M d, Y',strtotime($rec['KnowledgeBase']['created'])); ?></td>
		
		
		<td style="text-align:center;" class="action_sec">
		<?php echo $this->Html->link('<i class="fa fa-eye"></i>',array('controller'=>'knowledgeBase','action'=>'admin_student_view', base64_encode($rec['KnowledgeBase']['id'])),array('escape'=>false));?>
		<?php echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>',array('controller'=>'knowledgeBase','action'=>'admin_student_edit', $rec['KnowledgeBase']['id']),array('escape'=>false));?>
		<?php echo $this->Html->link('<i class="fa fa-trash-o"></i>',array('controller'=>'knowledgeBase','action'=>'admin_delete',base64_encode($rec['KnowledgeBase']['id'])),array('escape'=>false,'class'=>'confirmLink'));?>
		</td>
	</tr>
	<?php $row_count++;  endforeach; ?>
		<?php unset($cat); ?>
		<?php if($this->params['paging']['KnowledgeBase']['pageCount']>1){?>
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
	</table>
	   </td>
	</tr>
</table>
</div>
<div id="at_least_one" style="display:none;">
  <div class="di_icon"><i class="fa fa-warning faa-flash animated"></i></div>
  <p>Please select at least one Knowledge Base.</p>
</div>
<div id="delete_selected" style="display:none;">
  <div class="di_icon"><i class="fa fa-warning faa-flash animated"></i></div>
  <p>Do you want to delete selected Knowledge Base?</p>
</div>