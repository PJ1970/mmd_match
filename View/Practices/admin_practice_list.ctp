<div class="content">
      <div class="">
        <div class="page-header-title">
          <h4 class="page-title">Manage Practices</h4>
        </div>
		 
      </div>
      <div class="page-content-wrapper ">
       <div class="container">
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-primary">
                <div class="panel-body">
				 <?php echo $this->Session->flash()."<br/>";?>
				<div class="col-md-12">
					<div class="col-md-6">
						<h4 class="m-b-30 m-t-0"></h4>
					</div>
					<div align="right" class="col-md-6">
						<a href="<?php echo $this->HTML->url('/admin/practices/addPractice'); ?>" class="btn btn-large btn-primary" >Add Practice</a>
							<h4 class="m-b-30 m-t-0"></h4>
					</div>
				</div>
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                          <tr>
                            <th>Name</th>
                            <th>Address</th>
                            <th class="nosort">Phone</th>
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody>
						
						<?php if(!empty($datas)) {foreach($datas as $data){ ?>
						<tr>
							<td><?php echo $data['Practices']['name']; ?></td>
							<td><?php echo substr($data['Practices']['address'],0,50); ?></td>
							<td><?php echo $data['Practices']['phone'];?></td>	
							<td class="action_sec">
							
							&nbsp;&nbsp;<?php echo $this->Html->link('<i class="fa fa-pencil" aria-hidden="true"></i>',array('controller'=>'practices','action'=>'admin_edit', $data['Practices']['id']),array('escape'=>false,'title'=>'Edit'));?>
							
							&nbsp;&nbsp;<?php echo $this->Html->link('<i class="fa fa-trash-o"></i>',array('controller'=>'practices','action'=>'admin_delete',$data['Practices']['id']),array('escape'=>false,'title'=>'Delete','confirm'=>'Are you sure you want to delete?'));?>
							
							</td>
						</tr>
						<?php }} ?>
                         
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          </div>
        </div>
     </div>
	
          