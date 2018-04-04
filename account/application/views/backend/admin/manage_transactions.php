
<br><br>
<style>
	.mytable td{
		padding: 10px;
	}
</style>
<div align="right">
<table class="mytable" >
	<?php echo form_open(base_url() . '?admin/manage_transactions/'.$branch_id.'/search/' , array('class' => 'form-horizontal  validate'));?>
<tr>

<td >
	<?php if($date2 == ""):?>
	<h3 style="color: red">SEARCH TRANSACTION RECORDS</h3>
	<?php else:?>
	<h3>Showings records from <b><?=convert_to_date($date1);?></b> to <b><?=convert_to_date($date2);?></b></h3>
	<?php endif;?>
</td>

	<td><br>
<select class="form-control"  name="branch_id" <?php if(!s()->hAccess('overall_admin')) echo "disabled='disabled'";?> >
	<option>SELECT BRANCH</option>
	<?php foreach($branch as $row){;?>
		<option <?=$branch_id==$row['id']?'selected':'';?> value="<?=$row['id'];?>"><?=$row['name'];?></option>
	<?php } ;?>
</select>
	</td>

<td >
	<B>FROM:</B>
			<input type="date" class="form-control "  data-validate="required" name="date1"  data-message-required="<?php echo get_phrase('value_required');?>"
			       value="<?php echo $date1;?>">
</td>

	<td >
		<B>TO:</B>
		<input type="date" name="date2" class="form-control"  data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"
		       value="<?php echo $date2;?>">
	</td>
	<td >
		<br>
		<input type="submit" class="form-control btn btn-info"
		       value="Search">
	</td>

</tr>

		</form>
</table>
</div>
<br><br>
<h3>Showing <?=count($history);?>  Transaction Record(s) </h3>
					<table class="table table-bordered datatable table-striped" id="table_export">
						<thead>
						<tr>
							<th>#</th>
							<th><?php echo get_phrase('date');?></th>
							<th><?php echo get_phrase('teller_no');?></th>
							<th><?php echo get_phrase('purpose');?></th>
							<th><?php echo get_phrase('amount');?></th>
							<th><?php echo get_phrase('payment_status');?></th>
							<th><?php echo get_phrase('options');?></th>
						</tr>
						</thead>
						<tbody>

						<?php
						$count    = 1;
$total_p = 0;
$total_c = 0;
$total = 0;
						foreach ($history as $row):
							?>
							<tr>
								<td><?php echo $count++;?></td>
								<td><?php echo convert_to_date($row['date']);?></td>
								<td><?php echo $row['teller_no'];?></td>
								<td>
									<?php echo $row['purpose'];?>
								</td>
								<td >
									<b>N</b> <span class="format_number"><?php $total += $row['amount']; echo $row['amount'];?></span>
								</td>
								<td>
									<?php if($row['confirmed'] == 0):
										$total_p += $row['amount'];
										?>

									<label class="label label-danger">Pending</label>
										<a class="btn btn-warning"  onclick="return confirm('Approve Payment')" href='<?='?admin/manage_transactions/'.$branch_id.'/approve/'.$row['id'];?>'>Approve</a>
									<?php else:
										$total_c += $row['amount'];
										?>
									<label class="label label-info">Confirmed</label>
									<?php endif;?>
								</td>
								<td>
									<a href="?admin/receipt/<?=$row['id'];?>" class="btn btn-success">Reprint</a>
									<?php if(s()->hAccess('overall_admin')):?>
										<a href="#" class="btn btn-danger" onclick="confirm_modal('<?php echo base_url();?>index.php?admin/manage_transactions/<?php echo $branch_id;?>/delete/<?php echo $row['id'];?>');">
											<i class="entypo-trash"></i>
											<?php echo get_phrase('delete');?>
										</a>
									<?php endif;?>
								</td>
							</tr>
						<?php endforeach;?>

						</tbody>
					</table>
<span  style="color: red; text-align: right; margin-top: 15px;">
	<h4 style="color: PURPLE;">TOTAL PENDING: <b style=" margin-right: 5px;">N <span class="format_number"><?=$total_p;?></span></b></h4>
	<h4 style="color: purple;">TOTAL CONFIRMED: <b style=" margin-right: 5px;">N <span class="format_number"><?=$total_c;?></span></b></h4>
	<h3 style="color: red;">GRAND TOTAL: <b style=" margin-right: 5px;">N <span class="format_number"><?=$total;?></span></b></h3>
</span>
