<style>
.coach_view td {padding-right:30px}
</style>
<div class="main_page_heading">
	<a href="<?php echo $refererUrl; ?>" class="back_btn">Back</a>
</div>
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="coach_view">
	<tr>
		<td class="page-title change_password_title">
			Knowledge Base Information
		</td>
	</tr>
	<tr><td height="10px"> </td></tr>
	<tr>
		<td align="center">
			<table cellpadding="0" cellspacing="0" border="0" class="change-password form_section form_section_popup">
				<tr>
					<td> <b class="tag_bold">Title</b>
						<span class="format">:</span>
						<div class="clr10"></div>
					</td>
					<td>
						<label><?php echo $data['KnowledgeBase']['title'];?></label>
						<div class="clr10"></div>
					</td>
				</tr>
				<tr>
					<td> <b class="tag_bold">Description</b><span class="format">:</span>
					<div class="clr10"></div>
					</td>
					<td>
						<label><?php echo $data['KnowledgeBase']['description'];?></label>
						<div class="clr10"></div>
					</td>
					
				</tr>
				<tr>
					<td> <b class="tag_bold">Introduction Video</b><span class="format">:</span>
					<div class="clr10"></div>
					</td>
					<td>
						<label class="intro-video"><a href="<?php echo $data['KnowledgeBase']['youtube_id']; ?>" target="__blank"><?php echo $data['KnowledgeBase']['youtube_id'];?></a></label>
						<div class="clr10"></div>
					</td>
				</tr>
				<tr>
					<td> <b class="tag_bold">Upload Media</b><span class="format">:</span>
					<div class="clr10"></div>
					</td>
					<td>
						<label class="upload-media-view">
						<?php 
						if(!empty($data['KnowledgeBase']['media'])){
						$fileName = WWW_ROOT . 'img/uploads/knowledgebase/' . $data['KnowledgeBase']['media'];
							if(file_exists($fileName)){?>
							<a href="<?php echo $this->Html->url(array('controller' => 'download', 'action' => 'download', base64_encode($fileName))); ?>"><?php echo $data['KnowledgeBase']['media']; ?></a>
							<?php }
						}
						?>
						</label>
						<div class="clr10"></div>
					</td>
					
				
				</tr>
			</table>
	   </td>
	</tr>
</table>
