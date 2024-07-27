<div class="main_page_heading">
	<?php //echo $this->Html->link('Add Major / Subject / Module',array('action'=>'admin_add'),array('class'=>"back_btn turn-left",'escape'=>false)); ?>
	<form method="GET" action="<?php echo WWW_BASE ?>/admin/modules">
		<div class="search_box">
		<input type="text" name="search" id="search" placeholder="Search" value="<?php echo @$_GET['search']; ?>">
		<input type="submit"  value="" id="searchbtn" class="btn-common">
		</div>
	</form>
</div>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td class="page-title change_password_title">
			Manage Majors / Subjects / Modules	
		</td>
	</tr>
	<tr>
		<td align="center">
	
	<table border="1" class="table_section">
	<tr class="th_align">
		<th><?php echo $this->Paginator->sort('Module.name','Name'); ?></th>
		<th><?php echo $this->Paginator->sort('Module.user_id','Lecturer Name'); ?></th>
		<th><?php echo $this->Paginator->sort('Module.created','Created On'); ?></th>
		<!--<th style="text-align:center;">Action</th> -->	
	</tr>
	<?php if(!empty($data)) { 
	$row_count = 1;
	foreach ($data as $rec): ?>
	<tr>
		<td><?php echo $rec['Module']['name']; ?></td>
		<td><?php echo $this->Html->link($rec['Module']['user_name'], array('controller' => 'users', 'action' => 'lecturer_view', base64_encode($rec['Module']['user_id'])), array('escape' => false)); ?></td>
		<!--<td><?php echo (strlen($rec['Module']['description']) > 50)?(substr($rec['Module']['description'], 0, 50) . '...'):$rec['Module']['description']; ?></td>-->		
		<td><?php echo date('M d, Y',strtotime($rec['Module']['created'])); ?></td>
		<!--<td style="text-align:center;" class="action_sec">
		<?php echo $this->Html->link('<i class="fa fa-eye"></i>',array('controller'=>'modules','action'=>'admin_view', base64_encode($rec['Module']['id'])),array('escape'=>false));?>
		<?php echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>',array('controller'=>'modules','action'=>'admin_edit', $rec['Module']['id']),array('escape'=>false));?>
		<?php echo $this->Html->link('<i class="fa fa-trash-o"></i>',array('controller'=>'modules','action'=>'admin_delete',base64_encode($rec['Module']['id'])),array('escape'=>false,'class' => 'confirmLink'));?>
		</td>-->
	</tr>
	<?php $row_count++;  endforeach; ?>
		<?php unset($cat); ?>
		<?php if($this->params['paging']['Module']['pageCount']>1){?>
		<tr>
			<td colspan='4' align="center" class="paginat">
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
		<td align="center" colspan="4">No records found.</td>
	</tr>
	<?php } ?>
	</table>
	   </td>
	</tr>
</table>
