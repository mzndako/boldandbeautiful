<style>
	.mytable td{
		padding: 10px 5px;
	}
</style>
<div align="center" id="invoice_print" style="width: 100%;">
	<div style="overflow-x: auto; margin-top: 20px; font-size: 18px; color: white;" >
		<h2 class="bold"><?=get_setting("system_name");?></h2>
		<h4 class="bold">Acknowledgement Slip (E-Receipt)</h4>
		<div align="right" class="bold">
			Transaction Date: <?=convert_to_date($history['date']);?>
		</div>
		<br><br>
		<div align="left">
			<table class="mytable" style="text-align: left;">
				<tr>
					<td >Name:</td>
					<td><?=c()->get_full_name($history['name']);?></td>
				</tr>

				<tr>
					<td>Branch:</td>
					<td><?=$history['branch_name'];?></td>
				</tr>

				<tr>
					<td>Amount Paid:</td>
					<td>N <span class="format_number"><?=$history['amount'];?></span></td>
				</tr>

				<tr>
					<td>Phone  Number:</td>
					<td><?=$history['phone'];?></td>
				</tr>


				<tr>
					<td>Purpose of Payment:</td>
					<td><?=$history['purpose'];?></td>
				</tr>

				<tr>
					<td>Branch Numeric Strength:</td>
					<td><?=$history['branch_strength'];?></td>
				</tr>
				<tr>
					<td>Remark:</td>
					<td><?=$history['remark'];?></td>
				</tr>
				<tr>
					<td>Total Amount Paid:</td>
					<td class="bold">N <span class="format_number"><?=$history['amount'];?></span></td>
				</tr>


			</table>
		</div>
		<center>
		</center>
	</div>
</div>

