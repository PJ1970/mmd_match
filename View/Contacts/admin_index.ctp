<script>
$('body').on('change','#selectAll',function(){
		if(this.checked){
			$(".selectProduct").prop('checked', true);
		} else {
			$(".selectProduct").prop('checked', false);
		}
		
});
$('body').on('click','.buttonChange',function(){
	var arr = [];
	var action = $(this).attr('data-value');
	var i = 0;
	$('.selectProduct').each(function(){
		if(this.checked){
			arr[i] = $(this).val();
			i = i + 1;
		}
		
	});
	var show_n=$("#filter_visitor_n option:selected").val();
	if(arr != ''){
		if(action == 'export'){
			 document.location.href = BASE_URL+ 'admin/contacts/export?data='+arr+'&show='+show_n;
		} else if(action == 'email'){
			 $('#show_mod').click();
	
			 $.ajax({
				url: BASE_URL+ 'admin/contacts/getEmailIds',
				data: 'data=' + arr,
				method: 'post',
				success: function(response){
					var objJson = $.parseJSON(response);
					if(objJson.status == true){
						var html = '<ul id="ul_email">';
						var data = objJson.data.values;
						var keys = objJson.data.keys;
						for(var i= 0; i < objJson.data.values.length; i++){
							console.log(data[i]);
							html = html + '<li id="'+ keys[i] + '"><span>'+ data[i] + '</span><a href="javascript:void(0);"  class="deleLi" id="id_'+ keys[i] +'"><i class="fa fa-times-circle-o" aria-hidden="true"></i></a></li>';
						}
						html = html + '</ul>';
						var arrEmail = '';
						$('#res_status').html(html);
						getEmailList();	
						
						
					}
				}
			});
					 $('#to_email').val(arr);
		}
	} else {
		alert('please select atleast one record');
	}
	
});
$('body').on('click','#send_email_content',function(){
	var form_data = $('#email_form').serialize();
	$.ajax({
		url: BASE_URL+ 'admin/contacts/sendEmail',
		data:form_data,
		method: 'post',
		success: function(response){
			var objJson = $.parseJSON(response);
			
			var css_cls = 'alert alert-danger';
			if(objJson.status == true){
				css_cls = 'alert alert-success';
				$('#email_form').trigger('reset');
				resetCheck();
			}else{
				$('#res_status').addClass(css_cls);
				$('#res_status').text(objJson.message);
				$( "#myModal" ).slideUp( 1000 ).fadeOut( 1500 );
	            $( ".close" ).click();
				
			}
			
		}
	});
		
});
$('body').on('click','.deleLi',function(){
	$(this).closest('li').remove();	
	getEmailList();
		
});

function getEmailList(){
var arrEmail = '';
var flg = false;
$('#ul_email li').each(function(){
	flg = true;
	arrEmail = arrEmail + $(this).find('span').text() + ',';
});
$('#to_email').val(arrEmail);
if(flg == false){
	resetCheck();
}
}
function resetCheck(){
	$( "#myModal" ).slideUp( 300 ).fadeOut( 400 );
	$( ".close" ).click();
	$('input[type=checkbox]').attr('checked', false);
}
</script>

<div class="main_page_heading">Manage Visitors
	<form method="GET" action="<?php echo WWW_BASE ?>admin/contacts">
		<div class="search_box">
	     <select name="show" class="filter_visitor" id="filter_visitor_n">
		  <option value="">All</option>
		  <?php if(!empty($all_shows)){ foreach($all_shows as $all_show){?>
		  <option value="<?php echo $all_show['Show']['id']; ?>" <?php if(!empty($_GET['show'])&&($_GET['show']==$all_show['Show']['id'])){ echo 'selected';} ?>><?php echo $all_show['Show']['name']; ?></option>
		  <?php }} ?>
		 </select>
		<input type="text" name="search" class="search_visitor" id="search" placeholder="Search" value="<?php echo @$_GET['search']; ?>">
		<input type="submit"  value="" id="searchbtn" class="btn-common">
		</div>
	</form>
</div>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	
    <tr><td height="10px"> </td></tr>
	<tr >					   
		 <td class="pull-right">
			<a href="javascript:void(0)" data-value="export" class="btn-common buttonChange showNoti">Export To Excel</a>
			<a href="javascript:void(0)" data-value="email" class="btn-common buttonChange showNoti">Send Email</a>
		</td>
	</tr>
	 <tr><td height="10px"> </td></tr>
	<tr>
		<td align="center">
	
	<table border="1" class="table_section">
	<h2><?php //echo $this->Session->flash();?></h2>
	<thead>
	<tr class="th_align">
		<th style="width:3%"><?php echo $this->Form->input(false,array('div'=>false,'label'=>false,'type'=>'checkbox','id'=>'selectAll','hiddenField'=>false))?></th>
		<th><?php echo $this->Paginator->sort('Contact.name','Visitor Name'); ?></th>	
		<th><?php echo $this->Paginator->sort('Contact.email','Email'); ?></th>		
		<th><?php echo $this->Paginator->sort('Contact.company_name','Company Name'); ?></th>	
		<!--<th><?php echo $this->Paginator->sort('Contact.company_website','Company Website'); ?></th>	-->	
		<!--<th><?php echo $this->Paginator->sort('Contact.mobile','Mobile'); ?></th>	
		<th><?php echo $this->Paginator->sort('Contact.phone','Phone'); ?></th>-->	
		<!--<th><?php echo $this->Paginator->sort('Contact.interest','Interested In'); ?></th>-->	
		<th>Interested In </th>	
		<th style="text-align:center;">Action</th>	
	</tr>
	</thead>
	<tbody id="contact_del">
		<?php echo $this->element('contact'); ?>
	</tbody>
	</table>
	   </td>
	</tr>
</table>
<button type="button" id="show_mod" style="display:none;"class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content send_email">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Send Email</h4>
		<div id="res_status" class="send_to"><p>To</p></div>
      </div>
      <div class="modal-body">
		<?php 
			echo $this->Form->create('Contact', array('id' => 'email_form'));
			echo $this->Form->input('subject', array('type' => 'text', 'label' => 'Subject', 'class' => 'form-control'));
			echo $this->Form->input('body', array('type' => 'textarea', 'label' => 'Message', 'class' => 'form-control'));
			//echo $this->Form->input('from_email', array('type' => 'text', 'label' => 'From Email 1', 'class' => 'form-control'));
			echo $this->Form->input('to_email', array('type' => 'hidden', 'id' => 'to_email'));
			echo $this->Form->button('Send Email', array('type' => 'button', 'id' => 'send_email_content', 'class' => 'btn btn-success'));
			echo $this->Form->end();
		?>		
      </div>
    </div>

  </div>
</div>		