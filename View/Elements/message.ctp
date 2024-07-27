<div class="message">
  <strong><?php echo @$message;?></strong>
</div>

<script type="text/javascript">
   $(".message").delay(3000).fadeOut("slow", function () { $(this).remove(); });
</script>