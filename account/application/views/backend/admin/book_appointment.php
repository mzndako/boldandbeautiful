<div class="row">
	<div class="col-md-12">
		<div class="panel panel-danger" data-collapsed="0">
        	<div class="panel-heading" style="background: green;">
            	<div class="panel-title" style="color: white;">
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase("Book Appointment");?>
            	</div>
            </div>
			<div class="panel-body">
				<div class="row">
					<div class="col-sm-offset-3" style="color: red; font-weight: bold;">
						<?=getIndex($data,'error');?>
					</div>
				</div>
                <?php echo form_open(base_url() . 'index.php?users/book_appointment/create/' , array('class' => 'form-horizontal  validate', 'enctype' => 'multipart/form-data'));?>


				<?php if (!$this->session->hAccess('login')):?>
					<div class="form-group">
						<label class="col-sm-3 control-label">

						</label>
						<div class="col-sm-5">
							Already a customer <label><input type="radio"   data-validate="required" data-message-required="<?php echo get_phrase('Select Login Type?');?>" name="logintype" id="regcustomer" value="registered" onclick="changeType()" <?=getIndex($data,'logintype') == 'registered'?"checked=checked":"";?>>

								<b class="btn btn-info">Login</b>

							</label>
							New customer? <label><input type="radio"  data-validate="required" onclick="changeType()" data-message-required="<?php echo get_phrase('Select Login type?');?>" id="newcustomer" name="logintype" value="new" <?=getIndex($data,'logintype') == 'new'?"checked=checked":"";?>>

								<b class="btn btn-info">Create Account</b>

							</label><br>
						</div>
					</div>
<!--					 (appointment type: salon or Home Service), purpose of appointment (a drop down list of all their services), comment/special instructions, payment method : cash or online payment, terms and conditions. Book Appointment.-->
				<?php endif;?>
				<div style="display: none;"  class="row" id="loginform">
					<h3 class="col-sm-offset-3">Member's Login</h3>
					<div class="form-group">
						<div class="form-group">
							<h4 for="field-1" class="col-sm-3  control-label"><?php echo get_phrase('Email/Phone');?>:</h4>

							<div class="col-sm-5">
								<input class="form-control" placeholder="Enter login Email Address or Phone" name="loginemail" id="loginemail" type="text" data-validate="required" data-message-required="Enter your login email address or phone number" value="<?=getIndex($data,'loginemail');?>" />
							</div>
						</div>
					</div>

					<div class="form-group">
						<div class="form-group">
							<h4 for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Password');?>:</h4>

							<div class="col-sm-5">
								<input class="form-control" data-validate="required" data-message-required="Enter your password" name="loginpassword" id="loginpassword" type="password" />
							</div>
						</div>
					</div>

					<HR>
				</div>

				<div id="regform" style="display: none;" class="row">
					<h3 class="col-sm-offset-3">New Member Registration Form</h3>
					<div class="form-group">
						<div class="form-group">
							<h4 for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Surname');?>:</h4>

							<div class="col-sm-5">
								<input class="form-control" id="surname" type="text" data-validate="required" data-message-required="Enter Surname" name="surname" value="<?=getIndex($data,'surname');?>" />
							</div>
						</div>
					</div>

					<div class="form-group">
						<div class="form-group">
							<h4 for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('First Name');?>:</h4>

							<div class="col-sm-5">
								<input class="form-control" id="fname" type="text" data-validate="required" data-message-required="Enter First Name" name="fname" value="<?=getIndex($data,'fname');?>" />
							</div>
						</div>
					</div>

					<div class="form-group">
						<div class="form-group">
							<h4 for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Email');?>:</h4>

							<div class="col-sm-5">
								<input class="form-control" id="email" type="email" data-validate="required" data-message-required="Enter Valid Email" name="email" value="<?=getIndex($data,'email');?>" />
							</div>
						</div>
					</div>


					<div class="form-group">
						<div class="form-group">
							<h4 for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Password');?>:</h4>

							<div class="col-sm-5">
								<input class="form-control" placeholder="Create a login password" data-validate="required" data-message-required="Enter your password" name="password" id="password" type="password" />
							</div>
						</div>
					</div>

					<div class="form-group">
						<div class="form-group">
							<h4 for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Phone');?>:</h4>

							<div class="col-sm-5">
								<input class="form-control" id="phone" type="number" data-validate="required" data-message-required="Enter Phone Number" name="phone" value="<?=getIndex($data,'phone');?>" />
							</div>
						</div>
					</div>

					<div class="form-group">
						<div class="form-group">
							<h4 for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Address');?>:</h4>

							<div class="col-sm-5">
								<textarea class="form-control" id="address"  name="address" data-validate="required" data-message-required="Address"><?=getIndex($data,'residential_address');?></textarea>
							</div>
						</div>
					</div>


					<div class="form-group">
						<div class="form-group">
							<h4 for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('State');?>:</h4>

							<div class="col-sm-5">
								<input class="form-control"  type="text" data-validate="required" data-message-required="Enter State" name="state" value="<?=getIndex($data,'state');?>" />
							</div>
						</div>
					</div>

					<div class="form-group">
						<div class="form-group">
							<h4 for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Country');?>:</h4>

							<div class="col-sm-5">
								<input class="form-control"  type="text" data-validate="required" data-message-required="Enter Country" name="nationality" value="<?=getIndex($data,'nationality',"Nigeria");?>" />
							</div>
						</div>
					</div>
					<HR>
				</div>

					<div class="form-group">
						<h4 for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('appointment type');?>:</h4>

						<div class="col-sm-5">
							<label><input type="radio"   data-validate="required" data-message-required="<?php echo get_phrase('Appointment Type');?>" name="type" <?=getIndex($data,'type') != ''?"checked=checked":"";?>>

								<b class="label label-success">Salon</b>

								</label>
							<br>
							<label><input type="radio"   data-validate="required" data-message-required="<?php echo get_phrase('Appointment Type');?>" name="type" <?=getIndex($data,'type') != ''?"checked=checked":"";?>>

								<b class="label label-success">Home Service</b>
								</label>
						</div>
					</div>
<div class="row">
<h4 class="col-sm-3" style="text-align: right;">Appointments:</h4>
			<div style="box-shadow: 0px 10px 15px #ccc; padding: 10px; margin-top: 5px; margin-bottom: 10px;" class="col-sm-5">
				<div class="form-group">
						<label for="field-1" class="col-sm-4 control-label"><?php echo get_phrase('purpose of appointment');?></label>

						<div class="col-sm-6">
							<select class="form-control" name="specialization" data-validate="required"      data-message-required="<?php echo get_phrase('select purpose of appointment');?>" id="spec1" onchange="list_terms('spec1','user1')" >
								<option value="">Select Categories</option>
								<?php foreach($specs as $row):?>
									<option value="<?=$row['id'];?>" <?=$row['id']==getIndex($data,'specialization')?"selected":"";?> ><?=$row['name'];?></option>
								<?php endforeach;?>

							</select>
						</div>
					</div>

<!--					<div class="form-group">-->
<!--						<label for="field-1" class="col-sm-3 control-label">--><?php //echo get_phrase('available staff');?><!--</label>-->
<!--						<div class="col-sm-5">-->
<!--							<select id="user1" class="form-control" name="hall_id" data-validate="required"      data-message-required="--><?php //echo get_phrase('select hall/room');?><!--" >-->
<!---->
<!--							</select>-->
<!--						</div>-->
<!--					</div>-->

					<div class="form-group">
						<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('first appointment date');?></label>

						<div class="col-sm-3">
							<input type="date" id="fdate1" style="font-weight: bold; color: green;" class="form-control " placeholder="Select Date" data-validate="required" data-message-required="<?php echo get_phrase('First Appointment Date Required');?>"  name="date1" value="<?=getIndex($data,'date1');?>">
						</div>&nbsp;&nbsp;

						<div class="col-sm-3">
							<input type="time" id="ftime1" data-validate="required" data-message-required="<?php echo get_phrase('Select First Appointment Time');?>" placeholder="Select Time" style="font-weight: bold; color: green;" class="form-control" name="time1" value="<?=getIndex($data,'time1');?>">
						</div>

					</div>


				<div class="form-group">
						<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('second appointment date');?></label>

						<div class="col-sm-3">
							<input type="date" id="sdate1" style="font-weight: bold; color: green;" class="form-control " placeholder="Select Date" name="date2" value="<?=getIndex($data,'date2');?>">
							</div>&nbsp;&nbsp;
							<div class="col-sm-3">
							<input type="time" id="stime1" placeholder="Select Time" style="font-weight: bold; color: green;" class="form-control" name="time2" value="<?=getIndex($data,'time2');?>">
						</div>

					</div>


			</div>
</div>



<!--				<div class="form-group">-->
<!--					<label for="field-2" class="col-sm-3 control-label">--><?php //echo get_phrase('payment method');?><!--</label>-->
<!---->
<!--					<div class="col-sm-5">-->
<!--						<select class="form-control" name="method" data-validate="required"      data-message-required="--><?php //echo get_phrase('Select payment method');?><!--">-->
<!--							<option value="" >Select Method</option>-->
<!--							<option value="Cash">Cash</option>-->
<!--							<option value="Bank">Bank</option>-->
<!--						</select>-->
<!--					</div>-->
<!--				</div>-->

				<div class="form-group">
					<h4 for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Comment/Special Instruction');?></h4>

					<div class="col-sm-5">
						<textarea class="form-control" name="comment"></textarea>
					</div>
				</div>






                    <div class="form-group">
						<div class="col-sm-offset-3 col-sm-5">
							<button type="submit" class="btn btn-success btn-block "><?php echo get_phrase('submit');?></button>
						</div>
					</div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

	function changeType(){
		var r = $("#regcustomer").prop("checked");
		var n = $("#newcustomer").prop("checked");

		$("#loginform").hide(50);
		$("#regform").hide(50);

		if(r){
			$("#loginform").show(200);
		}else if(n){
			$("#regform").show(200);
		}

	}

	changeType();

	var currentterm = "',$current_term,'";
	var currentterm = <?=getIndex($data,'hall_id',0);?>;
	var session = <?=json_encode($specs_);?>;
	function list_terms(ses,term){
		return;
			var term_id = $("#spec1").val();
			$el = $("#user1");
		try {
			$el.html("");
			var lop = session[term_id];
			$.each(lop, function (key, value) {
				if(currentterm == value.id){
					$el.append($("<option selected ></option>")
						.attr("value", value.id).text(value.name));
				}else{
					$el.append($("<option ></option>")
						.attr("value", value.id).text(value.name));
				}
			});
		} catch (e) {}

	}
//	list_terms();
	currentterm = "";


	function addForm(){

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



</script>