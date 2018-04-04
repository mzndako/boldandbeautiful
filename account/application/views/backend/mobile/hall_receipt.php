<OK><style>
	.mytable td{
		padding: 10px;
	}
</style>
<div align="center" id="invoice_print">
	<div style="with: 100%; margin-top: 25px; font-size: 18px; color: white;" >
		<h2 class="bold"><?=get_setting("system_name");?></h2>
		<h3 class="bold"><?=$history['hall_name'];?> Booking Slip</h3>
		<div align="right" class="bold">
			Date: <?=convert_to_date($history['date']);?>
		</div>
		<br><br>
		<div align="left">
			<table class="mytable" style="text-align: left;">

				<tr>
					<td>First Name:</td>
					<td><?=$history['fname'];?></td>
				</tr>

				<tr>
					<td>Last Name:</td>
					<td><?=$history['surname'];?></td>
				</tr>

				<tr>
					<td>Phone:</td>
					<td><?=$history['phone'];?></td>
				</tr>

				<tr>
					<td>Location:</td>
					<td><?=$history['location'];?></td>
				</tr>

				<tr>
					<td>Amount:</td>
					<td>N <span class="format_number"><?=$history['amount'];?></span></td>
				</tr>

				<tr>
					<td >Booked Date:</td>
					<td>
						From <b><?=convert_to_date($history['start_date']);?></b>
						To <b><?=convert_to_date($history['end_date']);?></b>
					</td>
				</tr>


				<tr>
					<td>Payment Method:</td>
					<td><?=$history['method'];?></td>
				</tr>

				<?php if($history['method'] == 'Bank'):?>
				<tr>
					<td valign="top">Account Details:</td>
					<td><?=str_replace("\n","<br>",get_setting("bank_details",""));?></td>
				</tr>
				<?php endif;?>


			</table>
		</div>

	</div>
</div>


