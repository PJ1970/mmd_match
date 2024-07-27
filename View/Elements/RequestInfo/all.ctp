<table width="100%" cellpadding="0" cellspacing="0" border="0">

    <tr><td height="10px"> </td></tr>
	<tr>
		<td align="center">
	
	<table border="1" class="table_section">
	<tr class="th_align">
		<th><?php echo $this->Paginator->sort('RequestInformation.first_name','User Name'); ?></th>
		<th style="text-align:left;">Email Address</th>
		<th style="text-align:left;">Module Type</th>
		<th style="text-align:left;">Created On</th>
		<!--<th style="text-align:left;">User Type</th>-->
		
		<th style="text-align:center;">Action</th>	
	</tr>
	<?php if(!empty($data)) { 
	$row_count = 1;
	foreach ($data as $rec): ?>
	<tr class="<?php echo (!$rec['RequestInformation']['isRead'])?'unreadCls':''; ?>">
		<td><?php echo $rec['RequestInformation']['first_name'] . ' ' . $rec['RequestInformation']['last_name']; ?></td>
		<td><?php echo $rec['RequestInformation']['email']; ?></td>
		<td>
			<?php if($rec['RequestInformation']['module_type']=='college'){
				echo 'Colleges/World Universities';
			}elseif($rec['RequestInformation']['module_type']=='boarding'){
				echo 'Boarding Schools';
			}
			?>
		</td>
		<td><?php echo date('M d, Y',strtotime($rec['RequestInformation']['created'])); ?></td>
		<!--<td>
			<?php if($rec['VideoGallery']['user_type']=='admin'){
				echo 'Admin';
			}elseif($rec['VideoGallery']['user_type']=='3'){
				echo 'Ryan';
			}
			?>
		</td>
		
			
			</td>-->
	<td style="text-align:center;"align="center" class="action_sec">
		<?php echo $this->Html->link('<i class="fa fa-eye"></i>',array('action'=>'view',$rec['RequestInformation']['id']),array('escape'=>false,'title'=>'view'));  
		?>  
		<?php echo $this->Html->link('<i class="fa fa-trash-o"></i>',array('controller'=>'request_infos','action'=>'delete',$rec['RequestInformation']['id']),array('escape'=>false,'class'=>'confirmLink'));?>
	</td>

	</tr>
	<?php $row_count++;  endforeach; ?>
		<?php unset($cat); ?>
		<?php if($this->params['paging']['RequestInformation']['pageCount']>1){?>
		<tr>
			<td colspan='6' align="center" class="paginat">
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
		<td align="center" colspan="6">No records found.</td>
	</tr>
	<?php } ?>
	</table>
	   </td>
	</tr>
</table>
