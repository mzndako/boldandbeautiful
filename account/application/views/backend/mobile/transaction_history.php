
<br><br>
<style>
	.mytable td{
		padding: 10px;
	}
</style>
<?php if($date2 != ""):?>
	<ons-row align="center">
		<ons-col>
			<h4>Showings records from <b><?=convert_to_date($date1);?></b> to <b><?=convert_to_date($date2);?></b></h4>
		</ons-col>
	</ons-row>
<?php endif;?>
<br><br>
<h3>Showing <?=count($history);?>  Transaction Record(s) </h3>
<div style="overflow-x: auto">
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
									<b>N</b><span class="format_number"><?php $total += $row['amount']; echo $row['amount'];?></span>
								</td>
								<td>
									<?php if($row['confirmed'] == 0):
										$total_p += $row['amount'];
										?>

									<label class="label label-danger" style="color: green;">Pending</label>
									<?php else:
										$total_c += $row['amount'];
										?>
									<label class="label label-info" style="color: orange">Confirmed</label>
									<?php endif;?>
								</td>
								<td>
									<a href="#" ng-click="show_receipt(<?=$row['id'];?>)" class="btn btn-success" style="padding: 4px;">View</a>
								</td>
							</tr>
						<?php endforeach;?>

						</tbody>
					</table>
	</div>
<span  style="color: red; text-align: right; margin-top: 15px;">
	<h4 style="color: orange;">TOTAL PENDING: <b style=" margin-right: 5px;">N <span class="format_number"><?=$total_p;?></span></b></h4>
	<h4 style="  color: orange; ">TOTAL CONFIRMED: <b style=" margin-right: 5px;">N <span class="format_number"><?=$total_c;?></span></b></h4>
	<h3 style="color: red;">GRAND TOTAL: <b style=" margin-right: 5px;">N <span class="format_number"><?=$total;?></span></b></h3>
</span>
