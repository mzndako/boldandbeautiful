<?php if(is_admin()):?>
<div align="right">
	<?php echo form_open(base_url() . '?admin/view_appointments/select' , array('class' => 'form-horizontal  validate','method'=>"post"));?>
	<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered" >
		<tbody>


		<tr >

			<td width="20%"> <b>Customers:</b>
				</td>
			<td>
				<select class="form-control" name="customer" id="customer" data-validate="required"      data-message-required="<?php echo get_phrase('select customer');?>" >
					<option value="0">All Customer</option>
					<?php foreach($customers as $row):
						?>
						<option value="<?=$row['id'];?>" <?=$row['id']==$customer?"selected":"";?> ><?=get_client_id($row['id']).": ".c()->get_full_name($row);?></option>
					<?php endforeach;?>

				</select>
			</td>


			<td align="center"><input type="submit" value="View Records" class="btn btn-info"/></td>
		</tr>

		</tbody>
	</table>
	</form>

</div>
<br>
<?php endif;?>
<div class="row">
	<div class="col-md-12">
    
    	<!------CONTROL TABS START------>
		<ul class="nav nav-tabs bordered">
			<li class="active">
            	<a href="#booked" data-toggle="tab"><i class="entypo-menu"></i>
					<?php echo get_phrase('booked appointments');?>
                    	</a></li>
                <li>
                    <a href="#expired" data-toggle="tab"><i class="entypo-plus-circled"></i>
                        <?php echo get_phrase('expired booked appointments');?>
                    </a>
                </li>


		</ul>
    	<!------CONTROL TABS END------>
		<div class="tab-content">
            <!----TABLE LISTING STARTS-->
            <div class="tab-pane box active" id="booked">
	            <h3>Showing <?=count($booked_appointments);?>  Appointment(s) </h3>
                <table  class="table table-bordered datatable" id="table_export">
                	<thead>
                		<tr>
                    		<th><div><?php echo get_phrase('id');?></div></th>
                    		<th><div><?php echo get_phrase('client id');?></div></th>
                    		<th><div><?php echo get_phrase('client name');?></div></th>

                    		<th><div><?php echo get_phrase('purpose');?></div></th>
                    		<th><div>1st Appointment Date</div></th>
                    		<th><div>2nd Appointment Date</div></th>
                    		<th><div><?php echo get_phrase('phone');?></div></th>
                    		<th><div><?php echo get_phrase('action');?></div></th>
                    		<th><div><?php echo get_phrase('re-print');?></div></th>
						</tr>
					</thead>
                    <tbody>
                    	<?php
                       $count = 0;

                        foreach($booked_appointments as $row): $count++;?>
                        <tr>
							<td><?php echo $count;?></td>
							<td><?php echo get_client_id($row['user_id']);?>

                            </td>

	                        <td><?php echo c()->get_full_name($row['user']);?></td>
							<td><?php echo $spec[$row['specialization']];?></td>
                            <td>
	                            <label class="label label-danger"><b><?=convert_to_date($row['first_from'], "g:i A");?> - <?=convert_to_date($row['first_to'],"g:i A");?></b></label>	                           <b><?=convert_to_date($row['first_from']);?>  </b>
                            </td>
	                        <td>
		                        <?php if($row['second_to'] != "0000-00-00 00:00:00"):?>
		                        <label class="label label-danger"><b><?=convert_to_date($row['second_from'], "g:i A");?> - <?=convert_to_date($row['second_to'],"g:i A");?></b></label>	                           <b><?=convert_to_date($row['second_from']);?>  </b>
		                        <?php else:
		                        echo 'Nill';
		                        endif;
		                        ?>
	                        </td>
	                        <td><?php echo getIndex($row,'user,phone');?></td>

                            <td>
                               <a href="#" class="btn btn-danger" onclick="confirm_modal('<?php echo base_url();?>index.php?admin/view_appointments/delete/<?php echo $row['id']."/$customer";?>');">
                                                                    <i class="entypo-trash"></i>
                                                                    <?php echo get_phrase('delete');?>
                                </a>
                            </td>
                            <td>
                               <a href="?users/receipt/<?php echo $row['id'];?>'" class="btn btn-info" >
                                                                    <i class="entypo-trash"></i>
                                                                    <?php echo get_phrase('view');?>
                                </a>
                            </td>
	                        </tr>
                                    <?php endforeach;?>
                    </tbody>
                </table>
			</div>

            <div class="tab-pane box" id="expired">
	            <h3>Showing <?=count($expired_appointments);?> expired Reservation(s) </h3>
	            <table  class="table table-bordered datatable" id="table_export">
		            <thead>
		            <tr>
			            <th><div><?php echo get_phrase('id');?></div></th>
			            <th><div><?php echo get_phrase('client id');?></div></th>
			            <th><div><?php echo get_phrase('client name');?></div></th>

			            <th><div><?php echo get_phrase('purpose');?></div></th>
			            <th><div>1st Appointment Date</div></th>
			            <th><div>2nd Appointment Date</div></th>
			            <th><div><?php echo get_phrase('phone');?></div></th>
				            <th><div><?php echo get_phrase('action');?></div></th>
				            <th><div><?php echo get_phrase('re-print');?></div></th>
		            </tr>
		            </thead>
		            <tbody>
		            <?php
		            $count = 0;

		            foreach($expired_appointments as $row): $count++;?>
			            <tr>
				            <td><?php echo $count;?></td>
				            <td><?php echo get_client_id($row['user_id']);?>

				            </td>

				            <td><?php echo c()->get_full_name($row['user']);?></td>
				            <td><?php echo $spec[$row['specialization']];?></td>
				            <td>
					            <label class="label label-danger"><b><?=convert_to_date($row['first_from'], "g:i A");?> - <?=convert_to_date($row['first_to'],"g:i A");?></b></label>	                           <b><?=convert_to_date($row['first_from']);?>  </b>
				            </td>
				            <td>
					            <?php if($row['second_to'] != "0000-00-00 00:00:00"):?>
						            <label class="label label-danger"><b><?=convert_to_date($row['second_from'], "g:i A");?> - <?=convert_to_date($row['second_to'],"g:i A");?></b></label>	                           <b><?=convert_to_date($row['second_from']);?>  </b>
					            <?php else:
						            echo 'Nil  l';
					            endif;
					            ?>
				            </td>
				            <td><?php  echo getIndex($row,'user,phone');?></td>

				            <td>

					            <a href="#" class="btn btn-danger" onclick="confirm_modal('<?php echo base_url();?>index.php?admin/view_appointments/delete/<?php echo $row['id']."/$customer";?>');">
						            <i class="entypo-trash"></i>
						            <?php echo get_phrase('delete');?>
					            </a>
							</td>
					            <td>
						            <a href="?users/receipt/<?php echo $row['id'];?>" class="btn btn-info" >
							            <i class="entypo-trash"></i>
							            <?php echo get_phrase('view');?>
						            </a>
					            </td>
			            </tr>
		            <?php endforeach;?>
		            </tbody>
	            </table>	</div>



            
		</div>
	</div>
</div>



<!-----  DATA TABLE EXPORT CONFIGURATIONS ---->                      
<script type="text/javascript">

	jQuery(document).ready(function($)
	{
		

		var datatable = $("#table_exported").dataTable();
		
		$(".dataTables_wrapper select").select2({
			iDisplayPage: 100,
			minimumResultsForSearch: -1
		});
	});
		
</script>