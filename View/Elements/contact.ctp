<?php 
	if(!empty($data)) { 
	$row_count = 1;
	foreach ($data as $rec): 
	?>
	<tr>
		<td align="center">
		<?php echo $this->Form->input('',array('div'=>false,'label'=>false,'type'=>'checkbox','class'=>'selectProduct','hiddenField'=>false,'name'=>'id[]','value'=>$rec['Contact']['id'], 'id' => 'id_' . $rec['Contact']['id']))?>
		</td>
		<td><?php echo $rec['Contact']['name']; ?></td>		
		<td><?php echo $rec['Contact']['email']; ?></td>
		<td><?php echo $rec['Contact']['company_name']; ?></td>
		<td><?php if(!empty($rec['Contact']['interests'])){
			
                if(strlen($rec['Contact']['interests'])<25){
				    echo implode(',  ',explode(',',$rec['Contact']['interests']));
				}else{
					
					echo substr(implode(',  ',explode(',',$rec['Contact']['interests'])),0,30).'...';
				}
			} ?></td>		
		<td style="text-align:center;" class="action_sec">
		<?php echo $this->Html->link('<i class="fa fa-eye"></i>',array('action'=>'view',$rec['Contact']['id']),array('escape'=>false,'title'=>'view'));  
		?>  
		<?php echo $this->Html->link('<i class="fa fa-trash-o"></i>',array('controller'=>'contacts','action'=>'delete',base64_encode($rec['Contact']['id'])),array('escape'=>false,'class'=>'confirmLink'));?>
		</td>
	</tr>
	<?php $row_count++;  endforeach; ?>
		<?php unset($cat); ?>
		<?php if($this->params['paging']['Contact']['pageCount']>1){?>
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