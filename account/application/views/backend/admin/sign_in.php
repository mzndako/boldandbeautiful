<div class="row">
	<div class="col-md-12">

		<!------CONTROL TABS START------>
		<ul class="nav nav-tabs bordered">
			<li class="active">
				<a href="#listm" data-toggle="tab"><i class="entypo-menu"></i>
					<?php echo 'List of Sign In';?>
				</a></li>

			<?php if(hAccess("can_sign_in")){?>
			<li>
				<a href="#add" data-toggle="tab"><i class="entypo-plus-circled"></i>
					<?php echo 'Sign In';?>
				</a>
			</li>
			<?php }?>

		</ul>
		<!------CONTROL TABS END------>
		<div class="tab-content">
			<!----TABLE LISTING STARTS-->

			<div class="tab-pane box active" id="listm">
				<h3>List of Sign In </h3>
				<?php if($view_all):?>
					<a href="?admin/manage_sign_in/" class="btn btn-danger" >View All Pending Sign-Outs</a>
				<?php else:?>
					<a href="?admin/manage_sign_in/view_all" class="btn btn-warning" >View All Successful Sign-Outs</a>
				<?php endif;?>

				<a href="#" onclick="window.location.reload()" class="btn btn-info pull-right" >Refresh</a>

				<br>
				<br>
				<table class="table table-bordered datatable table-striped" id="table_export">
					<thead>
					<tr>
						<th>#</th>
						<th><?php echo get_phrase('sign in staff');?></th>
						<th><?php echo get_phrase('customer');?></th>
						<th><?php echo get_phrase('Product/Services');?></th>
						<th><?php echo get_phrase('sign-In time');?></th>
						<?php if($view_all):?>
							<th><?php echo get_phrase('sign-Out time');?></th>
						<?php else:?>
							<?php if(hAccess("can_sign_out")){?>
						<th><?php echo get_phrase('sign-out');?></th>
							<?php }?>
						<?php endif;?>
					</tr>
					</thead>
					<tbody>

					<?php
					$count    = 1;
					$total = 0;
					foreach ($signin as $row):
						?>
						<tr>
							<td><?php echo $count++;?></td>
							<td><?php echo c()->get_full_name($users[$row['staff_id']]);?></td>
							<td>  <?php echo c()->get_full_name($users[$row['user_id']]);?> <label class="label label-info"> <?=get_client_id($row['user_id']);?> </label></td>



							<td>
								<?php
										$product = @json_decode($row['products'],true);
										$x = array();
										foreach($product as $row2){
											$x[] = $row2['name'];
										}
									print implode(", ", $x);
								?>
							</td>

							<td width="200px"><b><label class="label label-danger"><?php echo convert_to_date($row['sign_in'],"g:i A");?></label> </b> <?php echo convert_to_date($row['sign_in']);?></td>

							<?php if($view_all):?>
							<td width="200px"><b><label class="label label-danger"><?php echo convert_to_date($row['sign_out'],"g:i A");?></label> </b> <?php echo convert_to_date($row['sign_out']);?></td>
							<?php else: ?>

							<?php if(hAccess("can_sign_out")){?>
							<td>
								<a class="btn btn-warning" href="?admin/make_payment/signout/<?=$row['id']?>" >Sign-Out</a>
							</td>
							<?php }?>

							<?php endif;?>

						</tr>
					<?php endforeach;?>

					</tbody>
				</table>
			</div>

			<div class="tab-pane box" id="add">
				<h3>Sign In </h3>
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-danger" data-collapsed="0">
							<div class="panel-heading" style="background: green;">
								<div class="panel-title" style="color: white;">
									<i class="entypo-plus-circled"></i>
									<?php echo get_phrase("Add to Sign In");?>
								</div>
							</div>
							<div class="panel-body">

								<?php echo form_open(base_url() . 'index.php?admin/manage_sign_in/create' , array('class' => 'form-horizontal  validate', 'enctype' => 'multipart/form-data'));?>

							<div id="payment_form" class="row">
								<div class="col-sm-7">
							<div class="form-group">
									<h4 for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('type');?>:</h4>

									<div class="col-sm-5">
										<label><input type="radio" value="1" checked="checked" id="type_r" onclick="checkType();"  data-validate="required" data-message-required="<?php echo get_phrase('Type');?>" name="type" <?=getIndex($data,'type') != ''?"checked=checked":"";?>>

											<b class="label label-info">Registered Customers</b>

										</label>
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
												<option value="<?=$row['id'];?>" <?=$row['id']==getIndex($data,'customers')?"selected":"";?> ><?=get_client_id($row['id']).": ".c()->get_full_name($row);?></option>
											<?php endforeach;?>

										</select>
									</div>
							</div>

							<div id="regform" style="display: none;">
								<div class="form-group">
									<div class="form-group">
										<h4 for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Surname');?>:</h4>

										<div class="col-sm-7">
											<input class="form-control" id="surname" type="text" />
										</div>
									</div>
								</div>

								<div class="form-group">
									<div class="form-group">
										<h4 for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('First Name');?>:</h4>

										<div class="col-sm-7">
											<input class="form-control" id="fname" type="text" />
										</div>
									</div>
								</div>

								<div class="form-group">
									<div class="form-group">
										<h4 for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Email');?>:</h4>

										<div class="col-sm-7">
											<input class="form-control" id="email" type="email" />
										</div>
									</div>
								</div>

								<div class="form-group">
									<div class="form-group">
										<h4 for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Phone');?>:</h4>

										<div class="col-sm-7">
											<input class="form-control" id="phone" type="number" />
										</div>
									</div>
								</div>

								<div class="form-group">
									<div class="form-group">
										<h4 for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Address');?>:</h4>

										<div class="col-sm-7">
											<input class="form-control" id="address" type="text" />
										</div>
									</div>
								</div>
								<HR>
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
															<select id="product1" onchange="showAmount()" class="form-control" name="product" data-validate="required"      data-message-required="<?php echo get_phrase('select Product/Service');?>" >

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
								<div class="col-sm-12">
									<button type="button" id="submit" onclick="submitForm()" class="btn btn-danger btn-block "><?php echo get_phrase('sign in');?></button>
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




		</div>
	</div>
</div>
<script type="text/javascript">

	var mylist = [];

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

	function previewShow(value){
		$("#pp_loading").hide();
		$("#pp_preview").hide();
		$("#pp_"+value).show(200);
	}

	function closeForm(){
		mylist = [];
		reloadList();
		loadMe("form");
		$("#type_r").prop("checked","checked");
		checkType();
		$("#fname").val("");
		$("#surname").val("");
		$("#phone").val("");
		$("#email").val("");
		$("#address").val("");


	}


	function submitForm(value){
		var customer = $("#customer").val();
		var type_u = $("#type_u").prop("checked");
		var type_r = $("#type_r").prop("checked");

		if(type_r && customer == 0){
			return alert("Please select a customer");
		}
		var type = type_r?1:0;

		if(mylist.length == 0)
			return alert("Please add Product/Services to list");

		var reg = {};
		if(type == 0){
			var fname = $("#fname").val();
			var surname = $("#surname").val();
			var email = $("#email").val();
			var phone = $("#phone").val();
			var address = $("#address").val();

			if(fname == "" || surname == "" || phone == ""){
				return alert("First Name, Surname or Phone Number can not be empty");
			}
			reg = {fname:fname, surname: surname, email:email, phone: phone, residential_address: address};
		}
		loadMe("preview");
		var url = '<?=base_url()."?admin/manage_sign_in/create/";?>';
		$.ajax({
			url: url,
			type: "post",
			data: {customer:customer, type: type, products: mylist, registration: reg},
			success: function(response)
			{
				$('#pp_response').html(response);
				previewShow("preview");
			},
			error: function(error){
				$('#pp_response').html("Error Signing In. Please go back and resubmit again");
				previewShow("preview");
			}
		});

	}

	function checkType(){
		var type_u = $("#type_u").prop("checked");
		if(type_u){
			$("#showcustomer").hide(100);
			$("#regform").show(100);
		}else{
			$("#showcustomer").show(100);
			$("#regform").hide(100);
		}
	}

	function reloadList(){
		$gt = 0;
		var str = '<table width="100%" class="table"><tr><th><b>S/N</b></th><th><b>Product/Service</b></th>	<th><b>Amount</b></th><th></th></tr>';
		for(var i = 0; i < mylist.length ; i++){
			var x = mylist[i];
			str += "<tr>";
			str += "<td>"+ (i+1) + "</td>";
			str += "<td>"+ x.name + "</td>";
			str += "<td>"+ format_number(x.amount,"N",0) + "</td>";
			str += "<td style='cursor: pointer;' onclick='remove4rmlist("+ i+")'>x</td>";
			str += "</tr>";
			$gt += parseInt(getNumber(x.total));
		}
		str +="</table>"
		$("#list").html(str);
		$("#grandtotal").html(format_number($gt));
	}

	function add2list(){
		var name = $("#product1 option:selected").data("name");
		var id = $("#product1").val();
		var amount = getNumber($("#amount").val());
		var total = 1 * amount;

		if(name==undefined || name == ''){
			return alert("Please select a Product");
		}

		if(total == 0 || total == "")
			return alert("Please enter a valid quantity");

		var x = {id:id ,name:name,amount: amount, unit: 1, total: total};
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
return;
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


</script>