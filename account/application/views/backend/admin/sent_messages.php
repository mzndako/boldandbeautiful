
<br><br>
<style>
	.mytable th, .mytable thead, .mytable thead tr{
		background: black !important;
		color: white !important;
	}
	.mytable td{
		/*padding: 10px;*/
	}
</style>
<div align="right">
	<?php echo form_open(base_url() . '?admin/sent_messages/search/' , array('class' => 'form-horizontal  validate','method'=>"post"));?>
	<table cellpadding="0" cellspacing="0" border="0" class="table  table-bordered mytable">
		<tr>
			<th>Staff Records</th>
			<th>Customer Records</th>
			<th>View Type</th>
			<th>Type</th>
			<th>View</th>
		</tr>
		<tbody>


		<tr class="gradeA">
			<td>
				<select class="form-control" name="staff" id="staff" data-validate="required"      data-message-required="<?php echo get_phrase('select customer');?>" >
					<option value="0">All Staffs</option>
					<?php foreach($staffs as $row):
						?>
						<option value="<?=$row['id'];?>" <?=$row['id']==$staff?"selected":"";?> ><?=c()->get_full_name($row);?></option>
					<?php endforeach;?>

				</select>
			</td>
			<td>
				<select class="form-control" name="customer" id="customer" data-validate="required"      data-message-required="<?php echo get_phrase('select customer');?>" >
					<option value="0">All Customer</option>
					<option value="-1" <?=$customer==-1?"selected='selected'":"";?>>Non Registered Members</option>
					<?php foreach($customers as $row):
						?>
						<option value="<?=$row['id'];?>" <?=$row['id']==$customer?"selected":"";?> ><?=get_client_id($row['id']).": ".c()->get_full_name($row);?></option>
					<?php endforeach;?>

				</select>
			</td>

			<td>
				<select name="view_type" id="view_type" class="form-control"  required>

					<?php
					$op	=	c()->get_view_type2();

					foreach($op as $k => $v):?>
						<option value="<?php echo $k;?>"
							<?php if(isset($view_type) && $view_type==$k)echo 'selected="selected"';?>>
							<?php echo $v;?>
						</option>
					<?php endforeach;?>
				</select>
			</td>

			<td>
				<select name="type" id="type" class="form-control" onchange="showDate(this)"  required>

					<?php
					$op	=	c()->get_option_type();

					foreach($op as $k => $v):?>
						<option value="<?php echo $k;?>"
							<?php if(isset($type) && $type==$k)echo 'selected="selected"';?>>
							<?php echo $v;?>
						</option>
					<?php endforeach;?>
				</select>

			</td>


			<td align="center"><input type="submit" value="View Records" class="btn btn-info"/></td>
		</tr>

		<tr id="showDate">
			<td colspan="3"></td>
			<td >
				<B>FROM:</B>
				<input type="datetime" class="form-control "  data-validate="required" name="date1"  data-message-required="<?php echo get_phrase('value_required');?>"
				       value="<?php echo $date1;?>">
			</td>

			<td >
				<B>TO:</B>
				<input type="datetime" name="date2" class="form-control"  data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"
				       value="<?php echo $date2;?>">
			</td>
		</tr>
		</tbody>
	</table>
	</form>

	</div>
<br>




<?php if($show):?>

	<?php
	$customers_ = array();
	foreach($customers as $row)
		$customers_[$row['id']] = $row;

	foreach($staffs as $row)
		$customers_[$row['id']] = $row;

	$startdate = $date1;
	$enddate = $date2;
	if(c()->get_option_type("today") == $type){
		$startdate = date("Y/m/d 00:00:00");
		$enddate = date("Y/m/d 23:59:59");
	}

	if(c()->get_option_type("this week") == $type){
		$monday = strtotime('last monday', strtotime('tomorrow'));
		$startdate = date("Y/m/d H:i:s", $monday);
		$enddate = date("Y/m/d H:i:s");
	}

	if(c()->get_option_type("this month") == $type){
		$startdate = date("Y/m")."/01 00:00:00";
		$enddate = date("Y/m/d 23:59:59");
	}

	d()->order_by("date","DESC");

	d()->where("date >=",database_date($startdate));
	d()->where("date <=",database_date($enddate));


	if($staff > 0) d()->where("staff_id", $staff);



	if($customer > 0)	d()->where("user_id", $customer);

	if($customer == -1) d()->where("user_id", 0);



	?>

	<?php if(c()->get_view_type2('SMS') == $view_type):



		$payments = c()->get("sent_sms")->result_array();

		?>
		<h3>Showings <?=count($payments);?> SMS Sent from <b><?=convert_to_datetime($startdate,"g:i A (j F, Y)");?></b> to <b><?=convert_to_datetime($enddate, "g:i A (j F, Y)");?></b></h3>
					<table class="table  table-bordered datatable table-striped" id="table_export">
						<thead>
						<tr>
							<th>#</th>
							<th><?php echo get_phrase('date');?></th>
							<?php if($staff == 0):?>
								<th><?php echo get_phrase('staffs');?></th>
							<?php endif;?>
							<th><?php echo get_phrase('members');?></th>
							<th><?php echo get_phrase('sender ID');?></th>
							<th><?php echo get_phrase('message');?></th>
							<th><?php echo get_phrase('recipient');?></th>
							<th><?php echo get_phrase('status');?></th>
						</tr>
						</thead>
						<tbody>

						<?php
						$count    = 1;
						$total = 0;
						foreach ($payments as $row):
							?>
							<tr>
								<td><?php echo $count++;?></td>
								<td width="220px"><b><label class="label label-danger"><?php echo convert_to_date($row['date'],"g:i A");?></label> <label class="label label-warning"><?php echo convert_to_date($row['date']);?></label> </b></td>
								<?php if($staff == 0):?>
									<td><?php echo c()->get_full_name($customers_[$row['staff_id']]);?></td>
								<?php endif;?>
								<td><?php
									if(!isset($customers_[$row['user_id']])){
										echo "<label class='label label-info'>Non Member</label>";
									}else
										echo c()->get_full_name($customers_[$row['user_id']]);?></td>
								<td><?php echo $row['sender_id'];?></td>
								<td><?php echo str_replace("\n","<br>",$row['message']);?></td>
								<td><?php echo $row['recipients'];?></td>

								<td width="120px"><?php
									if(stripos($row['status'],"OK") !== false)
										echo "<label class='label label-info'>SENT</label> ".$row['status'];
									else
										echo "<label class='label label-danger'>ERROR</label> ".$row['status'];

									?></td>

							</tr>
						<?php endforeach;?>

						</tbody>
					</table>


	<?php else:

		$expenses = c()->get("sent_mail")->result_array();
		?>

		<h3>Showings <?=count($expenses);?> SMS Sent from <b><?=convert_to_datetime($startdate,"g:i A (j F, Y)");?></b> to <b><?=convert_to_datetime($enddate, "g:i A (j F, Y)");?></b></h3>
		<table class="table table-bordered datatable table-striped" id="table_export">
			<thead>
			<tr>
				<th>#</th>
				<th><?php echo get_phrase('date');?></th>
				<?php if($staff == 0):?>
					<th><?php echo get_phrase('staffs');?></th>
				<?php endif;?>
				<th><?php echo get_phrase('members');?></th>
				<th><?php echo get_phrase('sender ID');?></th>
				<th><?php echo get_phrase('message');?></th>
				<th><?php echo get_phrase('recipient');?></th>
				<th><?php echo get_phrase('status');?></th>
			</tr>
			</thead>
			<tbody>

			<?php
			$count    = 1;
			$total = 0;
			foreach ($expenses as $row):
				?>
				<tr>
					<td><?php echo $count++;?></td>
					<td width="220px"><b><label class="label label-danger"><?php echo convert_to_date($row['date'],"g:i A");?></label> <label class="label label-warning"><?php echo convert_to_date($row['date']);?></label> </b></td>
					<?php if($staff == 0):?>
						<td><?php echo @c()->get_full_name($customers_[$row['staff_id']]);?></td>
					<?php endif;?>
					<td><?php
						if(!isset($customers_[$row['user_id']])){
							echo "<label class='label label-info'>Non Member</label>";
						}else
							echo @c()->get_full_name($customers_[$row['user_id']]);?></td>
					<td><?php echo $row['subject'];?></td>
					<td><?php echo $row['message'];?></td>
					<td><?php echo $row['recipients'];?></td>

					<td ><?php
							echo "<label class='label label-info'>SENT</label> ";

						?></td>

				</tr>
			<?php endforeach;?>

			</tbody>
		</table>


	<?php endif;?>
<?php endif;?>

<script type="text/javascript">
	function showDate(me){
		if(me != undefined)
			var num = me.selectedIndex;
		else
			var num = document.getElementById("type").selectedIndex;
		if(num == <?php echo c()->get_option_type("specific date");?>){
			$("#showDate").show(500);
		}else{
			$("#showDate").hide(500);
		}
	}

	showDate();
	$(document).ready(function(){
		format_numbers_now();
	})
</script>