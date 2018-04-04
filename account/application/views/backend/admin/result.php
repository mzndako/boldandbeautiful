<style>
	.mytable td{
		padding: 10px;
	}
</style>
<div align="center" id="invoice_print">
	<div style="background: white;max-width: 800px; margin-top: 50px; font-size: 18px; color: black;" >
		<h1 class="bold"><?=get_setting("system_name");?></h1>
		<h2 class="bold">Acknowledgement Slip (E-Receipt)</h2>
		<div align="right" class="bold">
			Transaction Date: <?=convert_to_date($history['date']);?>
		</div>
		<div align="left">
			<table class="mytable" style="text-align: left;">
				<tr>
					<td style="min-width: 300px;">Name:</td>
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
