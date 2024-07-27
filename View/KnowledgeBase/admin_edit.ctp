<div class="main_page_heading">
	<a href="<?php echo $refererUrl; ?>" class="back_btn">Back</a>
</div>
<?php echo $this->Form->create('KnowledgeBase', array('novalidate' => true, 'url'=>array('controller'=>'knowledgeBase','action'=>'admin_edit'), 'type' => 'file'));
?>
<input style="display:none">
<input type="hidden" style="display:none">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td class="page-title change_password_title">
			Edit Knowledge Base (For Coach)
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
						<?php echo $this->Form->input('id',array('type'=>'hidden','class' => 'form-control','label'=>false)); ?>
						<?php echo $this->Form->input('knowledge_base_type',array('type'=>'hidden','class' => 'form-control','label'=>false, 'value' => 'Coach')); ?>
						<?php echo $this->Form->input('description',array('type'=>'textarea','class' => 'form-control','label'=>false)); ?>
						<div class="clr10"></div>
					</td>
				</tr>
				<tr>
					<td>Upload Media</td>
					<td>
						<div class="upload_assign">
						<?php echo $this->Form->input('media',array('type'=>'file','class' => 'form-control form-media-upload','label'=>false, 'accept' => 'image/*,.pdf,.txt,.doc,.docx', 'div' => false)); ?>
						<?php 
						if(!empty($data['KnowledgeBase']['media'])){
						$fileName = WWW_ROOT . 'img/uploads/knowledgebase/' . $data['KnowledgeBase']['media'];
							if(file_exists($fileName)){?>
							<a href="<?php echo $this->Html->url(array('controller' => 'download', 'action' => 'download', base64_encode($fileName))); ?>"><?php echo $data['KnowledgeBase']['media']; ?></a>
							<?php }
						}
						?>
						</div>
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