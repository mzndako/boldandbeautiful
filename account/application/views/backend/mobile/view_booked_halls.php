<div class="row">
<style>



</style>
	<center><h3 style="color: white;">Reserved <?=get_phrase($type);?></h3></center>
	<div style="width: 100%;">

		<!------CONTROL TABS START------>
		<ons-row class="nav nav-tabs bordered">
			<ons-col class="active" align="center" style="text-align: center;">
				<a href="#booked" style="background: red; width: 90%; padding: 5px; text-align: center;" class="btn" onclick="$('#expired').hide(100); $('#booked').show(100);  " data-toggle="tab" class="mybuttom"><i class="entypo-menu"></i>
					<?php echo get_phrase('booked '.$type);?>
				</a></ons-col>
			<ons-col align="center" style="text-align: center;;">
				<a href="#expired" style="background: red; width: 90%; padding: 5px; overflow: hidden; text-align: center;" class="btn" onclick="$('#booked').hide(100);  $('#expired').show(100); " data-toggle="tab"><i class="entypo-plus-circled"></i>
					<?php echo get_phrase('expired booked '.$type);?>
				</a>
			</ons-col>


		</ons-row>
		<!------CONTROL TABS END------>
		<div class="tab-content" >
			<!----TABLE LISTING STARTS-->
			<div class="tab-pane box active" id="booked">
				<h4>Showing <?=count($booked_halls);?>  Reservation(s) </h4>
				<div style="width: 100%; overflow-x: auto; color: white;">
				<table  class="table table-bordered datatable" id="table_export">
					<thead>
					<tr>
						<th><div><?php echo get_phrase('id');?></div></th>
						<th><div><?php echo get_phrase('client name');?></div></th>
						<?php if($type == 'hall'):?>
							<th><div><?php echo get_phrase('event');?></div></th>
						<?php endif;?>
						<th><div><?php echo get_phrase($type.'_name');?></div></th>
						<th><div><?php echo get_phrase('location');?></div></th>
						<th><div><?php echo get_phrase('capacity');?></div></th>
						<th><div><?php echo get_phrase('amount');?></div></th>
						<th><div><?php echo get_phrase('phone');?></div></th>
						<th><div><?php echo get_phrase('FROM (booked date)');?></div></th>
						<th><div><?php echo get_phrase('TO (booked date)');?></div></th>
						<?php if(s()->hAccess("manage_hals")):?>
							<th><div><?php echo get_phrase('action');?></div></th>
						<?php endif;?>
					</tr>
					</thead>
					<tbody>
					<?php
					$count = 0;

					foreach($booked_halls as $row): $count++;?>
						<tr>
							<td><?php echo $count;?></td>
							<td><?php echo $row['surname']." ".$row['fname'];?>
								<?php if($s_->hAccess('manage_halls') && $row['user_id'] == 0): ?>
									<label class="label label-warning">Non Member</label>
								<?php endif;?>
							</td>
							<?php if($type == 'hall'):?>
								<td><?php echo $row['event'];?></td>
							<?php endif;?>
							<td><?php echo $halls[$row['hall_id']]['name'];?></td>
							<td><?php echo $halls[$row['hall_id']]['address'];?></td>
							<td><?php echo $halls[$row['hall_id']]['capacity'];?></td>
							<td>N<span class="format_number"><?php echo $row['amount'];?></span></td>
							<td><?php echo $row['phone'];?></td>
							<td>
								<label class="label label-info"><?=convert_to_date($row['start_date']);?></label>
							</td>
							<td>
								<label class="label label-success"><?=convert_to_date($row['end_date']);?></label>
							</td>
							<?php if(s()->hAccess("manage_hals")):?>
							<td>

								<a href="#" class="btn btn-danger" onclick="confirm_modal('<?php echo base_url();?>index.php?admin/view_booked_halls/delete/<?php echo $row['id'].'/'.$hall_id;?>');">
									<i class="entypo-trash"></i>
									<?php echo get_phrase('delete');?>
								</a>

								<?php endif;?>
						</tr>
					<?php endforeach;?>
					</tbody>
				</table>
					<div>
						&nbsp;
						&nbsp;
						&nbsp;
					</div>
					</div>
			</div>

			<div class="tab-pane box" id="expired" style="display: none;">
				<h4>Showing <?=count($expired_halls);?> Expired Reservation(s) </h4>
				<div style="width: 100%; overflow-x: auto; color: white;">
				<table  class="table table-bordered datatable" id="table_export">
					<thead>
					<tr>
						<th><div><?php echo get_phrase('id');?></div></th>
						<th><div><?php echo get_phrase('client name');?></div></th>
						<?php if($type == 'hall'):?>
							<th><div><?php echo get_phrase('event');?></div></th>
						<?php endif;?>
						<th><div><?php echo get_phrase($type.'_name');?></div></th>
						<th><div><?php echo get_phrase('location');?></div></th>
						<th><div><?php echo get_phrase('capacity');?></div></th>
						<th><div><?php echo get_phrase('amount');?></div></th>
						<th><div><?php echo get_phrase('phone');?></div></th>
						<th><div><?php echo get_phrase('FROM (booked date)');?></div></th>
						<th><div><?php echo get_phrase('TO (booked date)');?></div></th>
						<?php if(s()->hAccess("manage_hals")):?>
							<th><div><?php echo get_phrase('action');?></div></th>
						<?php endif;?>
					</tr>
					</thead>
					<tbody>
					<?php
					$count = 0;

					foreach($expired_halls as $row): $count++;?>
						<tr>
							<td><?php echo $count;?></td>
							<td><?php echo $row['surname']." ".$row['fname'];?>
								<?php if($s_->hAccess('manage_halls') && $row['user_id'] == 0): ?>
									<label class="label label-warning">Non Member</label>
								<?php endif;?>
							</td>
							<?php if($type == 'hall'):?>
								<td><?php echo $row['event'];?></td>
							<?php endif;?>
							<td><?php echo $halls[$row['hall_id']]['name'];?></td>
							<td><?php echo $halls[$row['hall_id']]['address'];?></td>
							<td><?php echo $halls[$row['hall_id']]['capacity'];?></td>
							<td>N<span class="format_number"><?php echo $row['amount'];?></span></td>
							<td><?php echo $row['phone'];?></td>
							<td>
								<label class="label label-info"><?=convert_to_date($row['start_date']);?></label>
							</td>
							<td>
								<label class="label label-success"><?=convert_to_date($row['end_date']);?></label>
							</td>
							<?php if(s()->hAccess("manage_hals")):?>
							<td>

								<a href="#" class="btn btn-danger" onclick="confirm_modal('<?php echo base_url();?>index.php?admin/view_booked_halls/delete/<?php echo $row['id'].'/'.$hall_id;?>');">
									<i class="entypo-trash"></i>
									<?php echo get_phrase('delete');?>
								</a>

								<?php endif;?>
						</tr>
					<?php endforeach;?>
					</tbody>
				</table>
					&nbsp;
					&nbsp;
					&nbsp;
					</div>
			</div>



<br><br><br>
		</div>
	</div>
</div>



<!-----  DATA TABLE EXPORT CONFIGURATIONS ---->
<script type="text/javascript">

	jQuery(document).ready(function($)
	{


		var datatable = $("#table_exported").dataTable();

		$(".dataTables_wrapper select").select2({
			minimumResultsForSearch: -1
		});
	});

</script>