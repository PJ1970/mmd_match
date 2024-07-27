<div id="dialog" class="conf_bx_custom" style="display:none;">
  <div class="di_icon"><i class="fa fa-warning faa-flash animated"></i></div>
  <p id="confrm_box_txt"><?php echo @$delete_text;?></p>
</div>
<script>
$('document').ready(function(){
	$('.conf_bx_custom').closest('.ui-dialog').addClass('parent_bx_custom');
});

</script>