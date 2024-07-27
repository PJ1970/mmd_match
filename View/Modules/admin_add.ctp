<div class="main_page_heading">
	<a href="<?php echo $refererUrl; ?>" class="back_btn">Back</a>
</div>
<?php echo $this->Form->create('Module', array('novalidate' => true, 'url'=>array('controller'=>'modules','action'=>'admin_add')));
?>
<input style="display:none">
<input type="hidden" style="display:none">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td class="page-title change_password_title">
			Add Major / Subject / Module
		</td>
	</tr>
	<tr><td height="10px"> </td></tr>
	<tr>
		<td align="center">
			<table cellpadding="0" cellspacing="0" border="0" class="change-password form_section">
				<h2><?php //echo $this->Session->flash();?></h2>
				<tr>
					<td>Name<span class="required">*</span> </td>
					<td>
						<?php echo $this->Form->input('name',array('type'=>'text','class' => 'form-control','label'=>false)); ?>
						<div class="clr10"></div>
					</td>
				</tr>
				<tr>
					<td>Description</td>
					<td>
						<?php echo $this->Form->input('description',array('type'=>'textarea','class' => 'form-control','label'=>false)); ?>
						<div class="clr10"></div>
					</td>
				</tr>
				<tr>
				   <td></td>
				   <td><button  class="btn-common" type="submit" >Save</button></td>
				</tr>
			</table>
	   </td>
	</tr>
</table>
<?php echo $this->Form->end(); ?>