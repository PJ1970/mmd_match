<div class="main_page_heading">
	<a href="<?php echo $refererUrl; ?>" class="back_btn">Back</a>
</div>
<?php echo $this->Form->create('KnowledgeBase', array('novalidate' => true, 'url'=>array('controller'=>'knowledgeBase','action'=>'admin_student_add'), 'type' => 'file'));
?>
<input style="display:none">
<input type="hidden" style="display:none">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td class="page-title change_password_title">
			Add Knowledge Base (For Student)
		</td>
	</tr>
	<tr><td height="10px"> </td></tr>
	<tr>
		<td align="center">
			<table cellpadding="0" cellspacing="0" border="0" class="change-password form_section">
				<h2><?php //echo $this->Session->flash();?></h2>
				<tr>
					<td>Title<span class="required">*</span> </td>
					<td>
						<?php echo $this->Form->input('title',array('type'=>'text','class' => 'form-control','label'=>false)); ?>
						<div class="clr10"></div>
					</td>
				</tr>
				<tr>
					<td>Introduction Video</td>
					<td>
						<?php echo $this->Form->input('youtube_id',array('type'=>'text','class' => 'form-control','label'=>false, 'placeholder' => 'https://www.youtube.com/watch?v=Hka0Zr8Dikc')); ?>
						<div class="clr10"></div>
					</td>
				</tr>
				<tr>
					<td>Description</td>
					<td>
						<?php echo $this->Form->input('knowledge_base_type',array('type'=>'hidden','class' => 'form-control','label'=>false, 'value' => 'Student')); ?>
						<?php echo $this->Form->input('description',array('type'=>'textarea','class' => 'form-control','label'=>false)); ?>
						<div class="clr10"></div>
					</td>
				</tr>
				<tr>
					<td>Upload Media</td>
					<td>
						<?php echo $this->Form->input('media',array('type'=>'file','class' => 'form-control','label'=>false, 'accept' => 'image/*,.pdf,.txt,.doc,.docx')); ?>
						<div class="clr10"></div>
					</td>
				</tr>
				<tr>
				   <td></td>
				   <td><button  class="btn-common" type="submit" >Update</button>
				   <?php echo $this->Form->end(); ?></td>
				</tr>
			</table>
	   </td>
	</tr>
</table>
<?php echo $this->Form->end(); ?>