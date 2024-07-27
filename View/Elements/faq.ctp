<?php
$arrChunk = 5;
if(isset($chunk)){
	$arrChunk = $chunk;
}
if(!empty($faqs)){
	$chunk_array=array_chunk($faqs,$arrChunk);
	//pr($chunk_array);
 ?>
 <div class="faq_container">
	<div class="col-sm-6">
		<div class="faq_block">
			<ul class="left">
			  <?php 
			  $i=1;
			  if(!empty($chunk_array[0])){
				  foreach($chunk_array[0] as $faq1){?>
					<li class="<?php if($i==1){ echo 'white_space';}?>">
						<span class="<?php if($i==1){ echo 'active';}?>"><?php echo $faq1['Faq']['question'];?></span>
						<div class="text_block"  style="<?php if($i==1){ echo 'display:block';}?>">
							<?php echo $faq1['Faq']['answer'];?>
						</div>
					</li>
					 
		    <?php
					 $i++;
				  } 
			  }
			?>
				
			</ul>
			<a class="readmore_btn" href="<?php echo WWW_BASE.'faqs/view_faq' ?>">Read more</a>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="faq_block">
			<ul class="right">
			   <?php
               $j=1;
               if(!empty($chunk_array[1])){			   
				   foreach($chunk_array[1] as $faq2){?>
						<li class="<?php if($j==1){ echo 'green';}?>">
							<span class="<?php if($j==1){ echo 'active';}?>"><?php echo $faq2['Faq']['question'];?></span>
							<div class="text_right_block" style="<?php if($j==1){ echo 'display:block';}?>">
								<?php echo $faq2['Faq']['answer'];?>
							</div>
						</li>
					   <?php 
					   $j++;
				   }
			   } 
			   if(!empty($chunk_array[2])){	
			   ?>
			    <li>
					<span><?php echo $chunk_array[2][0]['Faq']['question'];?></span>
					<div class="text_right_block">
						<?php echo $chunk_array[2][0]['Faq']['answer'];?>
					</div>
				</li>
			   <?php 
			   }
			   ?>
			</ul>
		</div>
	</div>
</div>
<?php } ?>