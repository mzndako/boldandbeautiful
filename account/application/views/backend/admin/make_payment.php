<div class="row">
	<div class="col-md-12">

		<!------CONTROL TABS START------>
		<ul class="nav nav-tabs bordered">

			<li class="active">
				<a href="#income" data-toggle="tab"><i class="entypo-menu"></i>
					<?php echo 'INCOME';?>
				</a></li>

			<?php if(hAccess("manage_expenditures")){?>
			<li>
				<a href="#expenditure" data-toggle="tab"><i class="entypo-plus-circled"></i>
					<?php echo 'EXPENDITURE';?>
				</a>
			</li>
			<?php }?>


		</ul>
		<!------CONTROL TABS END------>
		<div class="tab-content">
			<!----TABLE LISTING STARTS-->
			<div class="tab-pane box active" id="income">
				<h3>Enter Sales </h3>
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-danger" data-collapsed="0">
							<div class="panel-heading" style="background: green;">
								<div class="panel-title" style="color: white;">
									<i class="entypo-plus-circled"></i>
									<?php echo get_phrase("Make Payment");?>
								</div>
							</div>
							<div class="panel-body">

								<?php echo form_open(base_url() . 'index.php?admin/make_payment' , array('class' => 'form-horizontal  validate', 'enctype' => 'multipart/form-data'));?>

							<div id="payment_form" class="row">
								<div class="col-sm-7">
							<div class="form-group">
									<h4 for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('type');?>:</h4>

									<div class="col-sm-5">
										<label><input type="radio" value="1" checked="checked" id="type_r" onclick="checkType();"  data-validate="required" data-message-required="<?php echo get_phrase('Type');?>" name="type" <?=getIndex($data,'type') != ''?"checked=checked":"";?>>

											<b class="label label-info">Registered Customers</b>

										</label>
										<br>
										<br>
										<label><input type="radio" id="type_u" value="0"  onclick="checkType()" data-validate="required" data-message-required="<?php echo get_phrase('Type');?>" name="type" <?=getIndex($data,'type') != ''?"checked=checked":"";?>>

											<b class="label label-info">Unregistered Customer</b>
										</label>
									</div>
								</div>

							<div class="form-group" id="showcustomer">
								<h4 for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('customer');?>:</h4>

							<div class="col-sm-7">
										<select class="form-control" name="customer" id="customer" data-validate="required"      data-message-required="<?php echo get_phrase('select customer');?>" >
											<option value="0">Select Customer</option>
											<?php foreach($customers as $row):
												?>
												<option value="<?=$row['id'];?>" <?=$row['id']==$customer?"selected":"";?> ><?=get_client_id($row['id']).": ".c()->get_full_name($row);?></option>
											<?php endforeach;?>

										</select>
									</div>
							</div>

							<div class="form-group">
									<h4 for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('category');?>:</h4>

									<div class="col-sm-7">
										<select class="form-control" name="specialization" data-validate="required"      data-message-required="<?php echo get_phrase('select category');?>" id="spec1" onchange="list_terms('spec1','product1')" >
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
														<h4 for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('amount');?>:</h4>
														<div class="col-sm-7">
															<input readonly="readonly" id="amount" class="form-control" name="amount" data-validate="required"      data-message-required="<?php echo get_phrase('select Product/Service to display amount');?>" >
													</div>
								</div>


								<div class="form-group">
														<h4 for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Quantity/Unit');?>:</h4>
														<div class="col-sm-7">
															<input id="unit" value="1"  class="form-control" name="unit" data-validate="required" type="number"     data-message-required="<?php echo get_phrase('enter unit');?>" >
													</div>
								</div>

								<div class="form-group">
									<h4 for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('total');?>:</h4>
									<div class="col-sm-7">
										<input id="total" class="form-control" name="total" data-validate="required"  style="color: red; font-weight: bold;" readonly="readonly"    data-message-required="<?php echo get_phrase('total');?>" >
									</div>
								</div>

									<div class="form-group">
										<div class="col-sm-offset-3 col-sm-7">
											<button type="button" onclick="add2list()" class="btn btn-info btn-block "><?php echo get_phrase('add to list');?></button>
										</div>
									</div>
						</div>


						<div class="col-sm-5">
							<div class="form-group">

								<div style="box-shadow: 0px 10px 15px #ccc;  height: 250px; padding: 10px; margin-top: 5px; margin-bottom: 10px;" class="col-sm-11">
									<div id="list" style="height: 90%; overflow-y: auto;">
										<table width="100%" class="table">
										<tr>
											<th width="10px"><b>S/n</b></th>
											<th><b>Product/Service</b></th>
											<th><b>Amount</b></th>
											<th width="10px"><b>Quantity</b></th>
											<th><b>Total</b></th>
											<th></th>
										</tr>
										</table>
									</div>
									<div style="position: absolute; bottom: 0px; text-align: right; width: 90%; ">
									<table width="100%;" style="border-top: 1px solid red;">
										<tr>
											<th><b>Total</b></th>
											<th style="padding-right: 5px; color: red; text-align: right;"><b id="grandtotal">N 0</b></th>
											<th width="5px"><a href="#list" onclick="mylist = []; reloadList();">Clear</a></th>
										</tr>
									</table>
									</div>

								</div>

							</div>



							<div class="form-group">
								<h4 for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Remark');?></h4>

								<div class="col-sm-8">
									<textarea class="form-control" name="remark" id="remark"></textarea>
								</div>
							</div>

							<div class="form-group">
								<h4 for="field-2" class="col-sm-3 control-label"></h4>

								<div class="col-sm-8">
									<label><input type="checkbox" checked="checked" id="send_sms" value="1"/> Send Receipt via SMS </label><br>
									<label><input type="checkbox" checked="checked" id="send_email" value="1"/> Send Receipt via Email </label>
								</div>
							</div>


							<div class="form-group">
								<div class="col-sm-12">
									<button type="button" id="submit" onclick="submitForm()" class="btn btn-danger btn-block "><?php echo get_phrase('pay');?></button>
								</div>
							</div>

						</div>

					</div>

							<div class="row" id="payment_preview" align="center" style="padding: 20px; display: none;">
								<h1 id="pp_loading"><i class="fa fa-spinner fa-spin fa-3x"></i> LOADING. PLEASE WAIT....</h1>
								<div id="pp_preview">
									<div class="form-group">
										<div class="col-sm-2">
									<input type="button"  class="form-control btn btn-warning" value="Back" onclick="loadMe('form')">
											</div>
									</div>
									<div id="pp_response">
										<table class="table" style="text-align: left;" >
											<tr>
												<th>S/N</th>
												<th>Product/Service</th>
												<th>Amount</th>
												<th>Quantity</th>
												<th>Total</th>
												<th>Discount</th>
												<th>Grand Total</th>
											</tr>
										</table>
									</div>
								</div>
							</div>

								<?php echo form_close();?>
							</div>
						</div>
					</div>
				</div>


			</div>

			<div class="tab-pane box" id="expenditure">
				<h3>Enter Expenditures</h3>
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-danger" data-collapsed="0">
							<div class="panel-heading" style="background: green;">
								<div class="panel-title" style="color: white;">
									<i class="entypo-plus-circled"></i>
									<?php echo get_phrase("Expenditures");?>
								</div>
							</div>
							<div class="panel-body">

								<?php echo form_open(base_url() . 'index.php?admin/make_payment' , array('class' => 'form-horizontal  validate', 'enctype' => 'multipart/form-data'));?>

								<div id="expenditure_form" class="row">
									<div class="col-sm-7">

										<div class="form-group">
											<h4 for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('name');?>:</h4>
											<div class="col-sm-7">
												<input  id="ex_name" class="form-control" name="ex_name" data-validate="required"      data-message-required="<?php echo get_phrase('enter name');?>" >
											</div>
										</div>


										<div class="form-group">
											<h4 for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Quantity/Unit');?>:</h4>
											<div class="col-sm-7">
												<input id="ex_amount" value=""  class="form-control number" name="ex_amount" data-validate="required"      data-message-required="<?php echo get_phrase('enter amount');?>" >
											</div>
										</div>

										<div class="form-group">
											<h4 for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Remark');?></h4>

											<div class="col-sm-8">
												<textarea class="form-control" name="remark" id="ex_remark"></textarea>
											</div>
										</div>

										<div class="form-group">
											<div id="ex_response" style="color: red;">

											</div>
										</div>

										<div class="form-group">
											<div class="col-sm-offset-3 col-sm-7">
												<button type="button" id="ex_button" onclick="submitExpenditure()" class="btn btn-info btn-block "><?php echo get_phrase('Save');?></button>
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

	var mylist = <?php echo $mylist;?>;
	var signout_id = <?php echo $signout_id;?>;

	if(signout_id > 0){
		$("#customer").attr("disabled","disabled");
		$("#type_u").attr("disabled","disabled");
		reloadList();
	}

	function loadMe(value){
		$("#payment_form").hide();
		$("#payment_preview").hide();
		if(value == "form"){
			$("#payment_form").show(200);
		}else{
			previewShow("loading");
			$("#payment_preview").show(200);
		}
	}
//	loadMe("form");

	function previewShow(value){
		$("#pp_loading").hide();
		$("#pp_preview").hide();
		$("#pp_"+value).show(200);
	}

	function closeForm(){
		mylist = [];
		reloadList();
		$("#remark").val("");
		signout_id = 0;
		loadMe("form");
		$("#customer").removeAttr("disabled");
		$("#type_u").removeAttr("disabled");
	}

	function submitExpenditure(){
		var name = $("#ex_name").val();
		var remark = $("#ex_remark").val();
		var amount = $("#ex_amount").val();

		if(name == "" || amount == "" || amount == 0){
			return alert("Please fill the form");
		}

		$('#ex_response').html('<b><i class="fa fa-spin spin"></i>Submitting Payment. Please wait.....</b>');
		$('#ex_button').attr("disabled","disabled");

		var url = '<?=base_url()."?admin/expenditure/create";?>';
		$.ajax({
			url: url,
			type: "post",
			data: {name:name, remark: remark, amount: amount},
			success: function(response)
			{
				$('#ex_response').html(response);
				$('#ex_button').removeAttr("disabled");
				$('#ex_amount').val("");
			},
			error: function(error){
				$('#ex_response').html("Error Making Payment. Please go back and resubmit again");
				$('#ex_button').removeAttr("disabled");
			}
		});

	}

	function submitForm(value){
		$add = "";
		if(value == undefined)
			$add = "preview";

		var customer = $("#customer").val();
		var remark = $("#remark").val();
		var type_u = $("#type_u").prop("checked");
		var type_r = $("#type_r").prop("checked");

		if(type_r && customer == 0){
			return alert("Please select a customer");
		}
		var type = type_r?1:0;

		var send_sms = $("#send_sms").prop("checked")?1:0;
		var send_email = $("#send_email").prop("checked")?1:0;

		if(mylist.length == 0)
			return alert("Please add Product/Services to list");

//		$('#response').html('<b><i class="fa fa-spin spin"></i>Sending Payment. Please wait.....</b>');
//		$('#submit').attr("disabled","disabled");

		loadMe("preview");
		var url = '<?=base_url()."?admin/make_payment/create/";?>'+$add;
		$.ajax({
			url: url,
			type: "post",
			data: {customer:customer, type: type, products: mylist, send_sms: send_sms, send_email: send_email,remark: remark,signout_id: signout_id},
			success: function(response)
			{
				$('#pp_response').html(response);
				previewShow("preview");
			},
			error: function(error){
				$('#pp_response').html("Error Making Payment. Please go back and resubmit again");
				previewShow("preview");
			}
		});

	}

	function checkType(){
		var type_u = $("#type_u").prop("checked");
		if(type_u){
			$("#showcustomer").hide(200);
		}else{
			$("#showcustomer").show(200);
		}
	}

	function reloadList(){
		$gt = 0;
		var str = '<table width="100%" class="table"><tr><th><b>S/N</b></th><th><b>Product/Service</b></th>	<th><b>Amount</b></th>	<th><b>Quantity</b></th><th><b>Total</b></th><th></th></tr>';
		for(var i = 0; i < mylist.length ; i++){
			var x = mylist[i];
			str += "<tr>";
			str += "<td>"+ (i+1) + "</td>";
			str += "<td>"+ x.name + "</td>";
			str += "<td>"+ format_number(x.amount,"N",0) + "</td>";
			str += "<td>"+ x.unit + "</td>";
			str += "<td>"+ format_number(x.total,"N ",0) + "</td>";
			str += "<td style='cursor: pointer;' onclick='remove4rmlist("+ i+")'>x</td>";
			str += "</tr>";
			$gt += parseFloat(x.total);
		}
		str +="</table>"
		$("#list").html(str);
		$("#grandtotal").html(format_number($gt));
	}

	function add2list(){
		var name = $("#product1 option:selected").data("name");
		var id = $("#product1").val();
		var amount = getNumber($("#amount").val());
		var unit = getNumber($("#unit").val());
		var total = unit * amount;

		if(name==undefined || name == ''){
			return alert("Please select a Product");
		}

		if(total == 0 || total == "")
			return alert("Please enter a valid quantity");

		var x = {id:id ,name:name,amount: amount, unit: unit, total: total};
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



</script>