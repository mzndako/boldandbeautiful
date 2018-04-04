<div class="row">
	<div class="col-md-12">

		<!------CONTROL TABS START------>
		<ul class="nav nav-tabs bordered">
			<li class="active">
				<a href="#offers" data-toggle="tab"><i class="entypo-menu"></i>
					<?php echo 'Offers/Promos';?>
				</a></li>
			<li>
				<a href="#add" data-toggle="tab"><i class="entypo-plus-circled"></i>
					<?php echo 'Add Offer/Promo';?>
				</a>
			</li>


		</ul>
		<!------CONTROL TABS END------>
		<div class="tab-content">
			<!----TABLE LISTING STARTS-->
			<div class="tab-pane box active" id="offers">
<!--				<h3>Showing --><?//=count($expired_appointments);?><!-- expired Reservation(s) </h3>-->
				<table  class="table table-bordered datatable" id="table_export">
					<thead>
					<tr>
						<th><div><?php echo get_phrase('s/n');?></div></th>
						<th><div><?php echo get_phrase('name');?></div></th>
						<th><div><?php echo get_phrase('type');?></div></th>

						<th><div align="center">To Qualify<br>Days/Sign-in</div></th>
						<th><div>Discount (%)</div></th>
						<th><div>Products</div></th>
						<th><div>Period</div></th>
<!--						<th><div>Date</div></th>-->

						<th><div><?php echo get_phrase('status');?></div></th>
						<th><div><?php echo get_phrase('Delete');?></div></th>
					</tr>
					</thead>
					<tbody>
					<?php
					$count = 0;

					foreach($promos as $row): $count++;?>
						<tr>
							<td><?php echo $count;?></td>
							<td><?php echo $row['name'];?></td>
							<td><?php echo $row['type'];?></td>
							<td>
								<?php if($row['days'] > 0 || $row['sign_in'] > 0):?>
									<label class="label label-danger"><b><?=$row['days'];?> Days</b></label>
									<label class="label label-warning"><b><?=$row['sign_in'];?> Sign in</b></label>
								<?php else:?>
									Not Applicable
								<?php endif;?>
							</td>
							<td>
								<label class="label label-success"><b><?php echo $row['discount'];?> %</b></label>
							</td>
							<td>
								<?php if($row['target'] == 1):?>
									<?php $array = explode(",",$row['products']);
										$y = array();
										foreach($array as $x){
											if(isset($products[$x])){
												$y[] = $products[$x];
											}
										}
									print implode(", ",$y);
									?>
								<?php else:?>
									All Products/Services
								<?php endif;?>
							</td>
							<td>
								<label class="label label-danger"><b><?=convert_to_date($row['start_date']);?> </b></label> -
									<label class="label label-danger"><b><?=convert_to_date($row['end_date']);?></b></label>
							</td>
							<td>
								<?php if($row['active'] == 1):?>
									<a class="label label-success" href="?admin/manage_promos/deactivate/<?=$row['id'];?>">Disable</a>
								<?php else:?>
									<a class="label label-warning" href="?admin/manage_promos/activate/<?=$row['id'];?>">Enable</a>
								<?php endif;?>
							</td>

							<td>
								<a href="#" class="btn btn-danger" onclick="confirm_modal('<?php echo base_url();?>index.php?admin/manage_promos/delete/<?php echo $row['id'];?>');">
									<i class="entypo-trash"></i>
									<?php echo get_phrase('delete');?>
								</a>
							</td>

						</tr>
					<?php endforeach;?>
					</tbody>
				</table>	</div>


			<div class="tab-pane box" id="add">
				<h3>Create Offers/Promos </h3>
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-danger" data-collapsed="0">
							<div class="panel-heading" style="background: green;">
								<div class="panel-title" style="color: white;">
									<i class="entypo-plus-circled"></i>
									<?php echo get_phrase("Offers/Promos");?>
								</div>
							</div>
							<div class="panel-body">

								<?php echo form_open(base_url() . 'index.php?/admin/manage_promos/create' , array('class' => 'form-horizontal  validate', 'enctype' => 'multipart/form-data'));?>

							<div class="row">
								<div class="col-sm-7">
									<div class="form-group">
										<h4 for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Offer/Promo Name');?>:</h4>
										<div class="col-sm-7">
											<input class="form-control" name="name" data-validate="required"  type="text"    data-message-required="<?php echo get_phrase('Enter Name');?>" >
										</div>
							</div>



							<div class="form-group">
									<h4 for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('type of offer/promo');?>:</h4>

									<div class="col-sm-5">
										<label><input type="radio" checked="checked" id="type_r" onclick="checkType();"  data-validate="required" data-message-required="<?php echo get_phrase('Type');?>" name="type" checked="checked" value="Specific Period"/>

											<b class="label label-success">Specific Period Offer</b>

										</label>
										<br><br>
										<label><input type="radio" id="type_u"  onclick="checkType()" data-validate="required" data-message-required="<?php echo get_phrase('Type');?>" name="type" value="Regular Customers" >

											<b class="label label-success">Regular Customer Offer</b>
										</label>
									</div>
								</div>
<div id="showcustomer">
							<div class="form-group" >
								<h4 for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('number of days interval');?>:</h4>

							<div class="col-sm-7">
										<input name="days" type="number" value="30" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('number of days interval');?>" />
									</div>
							</div>

							<div class="form-group" >
								<h4 for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('number of sign-in to qualify');?>:</h4>

							<div class="col-sm-7">
										<input type="number" name="sign_in" value="3" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('number of sign-in to qualify');?>" />
									</div>
							</div>
</div>

								<div class="form-group">
														<h4 for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('offer start date');?>:</h4>
														<div class="col-sm-7">
															<input class="form-control" name="start_date" data-validate="required"  type="date"    data-message-required="<?php echo get_phrase('select start date');?>" >
													</div>
								</div>

								<div class="form-group">
														<h4 for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('offer end date');?>:</h4>
														<div class="col-sm-7">
															<input class="form-control" name="end_date" data-validate="required"  type="date"    data-message-required="<?php echo get_phrase('select end date');?>" >
													</div>
								</div>

								<div class="form-group">
														<h4 for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Discount (%)');?>:</h4>
														<div class="col-sm-7">
															<input id="discount" value="30"  class="form-control" name="discount" data-validate="required" type="number"     data-message-required="<?php echo get_phrase('enter unit');?>" >
													</div>
								</div>

									<div class="form-group">
										<h4 for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Target');?>:</h4>
										<div class="col-sm-7">
											<select name="target" onchange="changeTarget()" class="form-control" id="target">
												<option value="0">All Product/Services</option>
												<option value="1">Specific Product/Services</option>
											</select>
										</div>
									</div>

							<div id="targetlist">


							<div class="form-group">
								<h4 for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('category');?>:</h4>
								-
								<div class="col-sm-7">
									<select class="form-control" name="specialization" datavalidate="required"      data-message-required="<?php echo get_phrase('select category');?>" id="spec1" onchange="list_terms('spec1','product1')" >
										<option value="" data-name="">Select Categories</option>
										<?php foreach($specs as $row):?>
											<option value="<?=$row['id'];?>" <?=$row['id']==getIndex($data,'specialization')?"selected":"";?> ><?=$row['name'];?></option>
										<?php endforeach;?>

									</select>
								</div>

							</div>


							<div class="form-group">
								<h4 for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Product/Service');?>:</h4>
								<div class="col-sm-7">
									<select id="product1" onchange="$('#unit').val(1);showAmount()" class="form-control" name="product" data-validate="required"      data-message-required="<?php echo get_phrase('select Product/Service');?>" >

									</select>
								</div>
							</div>

								<div class="form-group">
									<div class="col-sm-offset-3 col-sm-7">
										<button type="button" onclick="add2list()" class="btn btn-success btn-block "><?php echo get_phrase('add to list');?></button>
									</div>
								</div>

<input type="hidden" name="products" id="products" value="" />

								<div class="form-group">

									<div style="box-shadow: 0px 10px 15px #ccc;  height: 200px; padding: 10px; margin-top: 5px; margin-bottom: 10px;" class="col-sm-7 col-sm-offset-3">
										<div id="list" style=" height: 90%; overflow-y: auto;">
											<table width="100%" class="table">
												<tr>
													<th width="10px"><b>S/n</b></th>
													<th style="text-align: center;"><b>Product/Service</b></th>
													<th width="10px"></th>
												</tr>
											</table>
										</div>


									</div>

								</div>

							</div>

									<div class="form-group">
										<h4 for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Send SMS');?>:</h4>

										<div class="col-sm-7">
											<input onclick="this.checked?$('#smsbox').show():$('#smsbox').hide();" name="send_sms" type="checkbox" value="1"  /> Send SMS to all customer's about this offers  <br>
											<textarea style="display: none;" id="smsbox" class="form-control" name="smsbox" data-validate="required" data-message-required="<?php echo get_phrase('Enter SMS to send to customer phones');?>" ><?php echo "Good day,\nWe have activated a new offer for you with 30% discount.\nPlease patronize us to enjoy this offer";?></textarea>


										</div>
									</div>

									<div class="form-group">
										<h4 for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Send Email');?>:</h4>

										<div class="col-sm-7">
											<input onclick="this.checked?$('#emailbox').show():$('#emailbox').hide();" name="send_email" value="1" type="checkbox"  /> Send Email to all customer's about this offers  <br>
											<textarea style="display: none;" id="emailbox" class="form-control" name="emailbox" data-validate="required" data-message-required="<?php echo get_phrase('Enter E-mail to send to customer phones');?>" ><?php echo "Good day,\nWe have activated a new offer for you with 30% discount.\nPlease patronize us to enjoy this offer";?></textarea>


										</div>
									</div>




									<div class="form-group">
								<span style="color: red;" id="response"></span>
							</div>


							<div class="form-group">
								<div class="col-sm-12">
									<button type="submit" id="submit" class="btn btn-danger btn-block "><?php echo get_phrase('Create Offer/Promo');?></button>
								</div>
							</div>

						</div>

					</div>



								<?php echo form_close();?>
							</div>
						</div>
					</div>
				</div>


			</div>

		</div>
	</div>
</div>
<script type="text/javascript">

	var mylist = [];

	function changeTarget(){
		if($('#target').val() == 1){
			$('#targetlist').show();
		}else{
			$('#targetlist').hide();
		}
	}

	function submitForm(){
		var customer = $("#customer").val();
		var type_u = $("#type_u").prop("checked");
		var type_r = $("#type_r").prop("checked");

		if(type_r && customer == 0){
			return alert("Please select a customer");
		}
		var type = type_r?0:1;

		if(mylist.length == 0)
			return alert("Please add Product/Services to list");

		$('#response').html('<b><i class="fa fa-spin spin"></i>Sending Payment. Please wait.....</b>');
		$('#submit').attr("disabled","disabled");

		var url = '<?=base_url()."?users/make_payment";?>'+customer+"/"+type;
		$.ajax({
			url: url,
			success: function(response)
			{
				jQuery('#response').html(response);
				$('#submit').removeAttr("disabled");
				alert(response);
			},
			error: function(error){
				$('#response').html("Error Making Payment. Please resubmit again");
				$('#submit').attr("disabled","disabled");
			}
		});

	}

	function checkType(){
		var type_u = $("#type_r").prop("checked");
		if(type_u){
			$("#showcustomer").hide();
		}else{
			$("#showcustomer").show();
		}
	}

	function reloadList(){
		$gt = 0;
		var str = '<table width="100%" class="table"><tr><th width="20px"><b>S/N</b></th><th style="text-align: center;"><b>Product/Service</b></th>	<th></th></tr>';

		$y = [];
		for(var i = 0; i < mylist.length ; i++){
			var x = mylist[i];
			str += "<tr>";
			str += "<td>"+ (i+1) + "</td>";
			str += "<td>"+ x.name + "</td>";
			str += "<td style='cursor: pointer;' onclick='remove4rmlist("+ i+")'>x</td>";
			str += "</tr>";
			$y += ","+ x.id
		}

		str +="</table>";
		$("#list").html(str);
		if($y.length > 0) $y = $y.substr(1);
		$("#products").val($y);
	}

	function add2list(){
		var name = $("#product1 option:selected").data("name");
		var id = $("#product1").val();

		if(name==undefined || name == ''){
			return alert("Please select a Product");
		}

		var x = {id:id ,name:name};
		mylist.push(x);
		reloadList();
	}

	function remove4rmlist(id){
		var newlist = [];
		for(var i = 0; i < mylist.length ; i++) {
			var x = mylist[i];
			if(i != id)
				newlist.push(x);
		}

		mylist = newlist;
		reloadList();
	}




	var currentterm = "',$current_term,'";
	var currentterm = <?=getIndex($data,'hall_id',0);?>;
	var session = <?=json_encode($specs_);?>;
	function list_terms(ses,term){
		var term_id = $("#spec1").val();
		$el = $("#product1");
		try {
			$el.html("");
			var lop = session[term_id];
			$el.append("<option data-name=''>Select Product/Services</option>")
			$.each(lop, function (key, value) {
				if(currentterm == value.id){
					$el.append($("<option selected data-amount='"+value.amount+"' data-name='"+value.name+"'></option>")
						.attr("value", value.id).text(value.name));
				}else{
					$el.append($("<option data-name=\""+value.name+"\" data-amount='"+value.amount+"'></option>")
						.attr("value", value.id).text(value.name));
				}
			});
		} catch (e) {}

		$el.select2();
		$("#unit").val(1);
		showAmount();


	}
	//	list_terms();
	currentterm = "";

	function getNumber($number){
		if($number == undefined || ($number+"").trim() == "")
			return 0;

		return parseFloat(($number+"").replace(/,/g, "").replace("N",""))
			.toString()
			.replace(/\B(?=(\d{3})+(?!\d))/g, "");
	}

	function showAmount(){
		$el = $("#product1 option:selected").data('amount');

		if($el == undefined || $el == null)
			$el = 0;

		$("#amount").val(format_number($el));

		$total = parseInt(getNumber($('#unit').val())) * parseInt(getNumber($el));

		$ans = format_number($total);
		$("#total").val($ans);
	}

	$("#unit").bind('keyup change click mouseup',function(){
		showAmount();
	});

	function format_number($number,$default,$fixed){
		if($default == undefined)
			$default = "N ";

		if($fixed == undefined)
			$fixed = 2;

		if($number == undefined || isNaN($number) || ($number+"").trim() == "")
			return "";

		return $default+parseFloat(($number+"").replace(/,/g, "").replace("N",""))
			.toFixed($fixed)
			.toString()
			.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}


	function checkApp(prefix,suffix){
		var date = $("#"+prefix+"date"+suffix).val();
		var time = $("#"+prefix+"time"+suffix).val();
		var user_id = $("#user"+suffix).val();
		if(date == '' || time == '' | user_id == ''){
			alert('Please select a Date, Time and Staff');
			return;
		}

		jQuery('#'+prefix+'response'+suffix).html('<b><i class="fa fa-spin spin"></i>Checking Staff Availability. Please wait.....</b>');
		var url = '<?=base_url()."?users/check_appointment/";?>'+user_id+"/"+date+"/"+time;
		$.ajax({
			url: url,
			success: function(response)
			{
				jQuery('#'+prefix+'response'+suffix).html(response);
			}
		});
	}

	function clear($what,$default){
		if($default == undefined)
			$default = "";
		$("#"+$what).val($default);
	}

	function clearSelect($what){
		$("#"+$what).select2("val","");
	}

	function clearAll(){
		clear("unit",1);
		clear("amount");
		clear("total");
		clearSelect("product1");
		showAmount();
	}

	changeTarget();
	checkType();


</script>