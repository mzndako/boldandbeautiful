<style>
	.mytable td{
		padding: 10px;
	}
</style>
<div align="center" id="invoice_print">
	<div style="background: white;max-width: 700px; margin-top: 50px; font-size: 18px; color: black;" >
		<h1 class="bold"><?=get_setting("system_name");?></h1>
		<h2 class="bold">Appointment Slip</h2>
		<div align="right" class="bold">
			Date: <?=convert_to_datetime($history['date']);?>
		</div>
		<div align="left">
			<table class="mytable" style="text-align: left;">

				<tr>
					<td>Appointment ID:</td>
					<td><?=$history['app_id'];?></td>
				</tr>

				<tr>
					<td>Name:</td>
					<td><?=$history['name'];?></td>
				</tr>

				<tr>
					<td>Last Name:</td>
					<td><?=$history['email'];?></td>
				</tr>

				<tr>
					<td>Phone:</td>
					<td><?=$history['phone'];?></td>
				</tr>




				<tr>
					<td >First Appointment:</td>
					<td>
						From <b><?=convert_to_datetime($history['first_from']);?></b><br>
						To <b><?=convert_to_datetime($history['first_to']);?></b>
					</td>
				</tr>

				<?php if(isset($history['second_from'])):?>
				<tr>
					<td >Second Appointment:</td>
					<td>
						From <b><?=convert_to_datetime($history['second_from']);?></b><br>
						To <b><?=convert_to_datetime($history['second_to']);?></b>
					</td>
				</tr>
				<?php endif;?>

				<tr>
					<td>Comment/Special Instruction:</td>
					<td><?=$history['comment'];?></td>
				</tr>

<!--				<tr>-->
<!--					<td>Payment Method:</td>-->
<!--					<td>--><?//=$history['method'];?><!--</td>-->
<!--				</tr>-->




			</table>
		</div>
		<center>
			<a onClick="PrintElem('#invoice_print')" class="btn btn-success btn-icon icon-left hidden-print pull-right">
				Print Invoice
				<i class="entypo-print"></i>
			</a>
		</center>
	</div>
</div>

<script type="text/javascript">

	// print invoice function
	function PrintElem(elem)
	{
		window.print();
	}



</script>
