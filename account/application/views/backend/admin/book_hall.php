<div class="row">
	<div class="col-md-12">
		<div class="panel panel-danger" data-collapsed="0">
        	<div class="panel-heading" style="background: green;">
            	<div class="panel-title" style="color: white;">
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase("$type reservation");?>
            	</div>
            </div>
			<div class="panel-body">
				
                <?php echo form_open(base_url() . 'index.php?users/book_hall/'.$type.'/create/' , array('class' => 'form-horizontal  validate', 'enctype' => 'multipart/form-data'));?>

				<?php if($type == 'hall'):?>
					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('event name');?></label>

						<div class="col-sm-5">
							<input type="text" class="form-control"  data-validate="required" data-message-required="<?php echo get_phrase('Enter Event Name');?>" name="event"
							       autofocus                       	value="<?=getIndex($data,'event');?>">
						</div>
					</div>
<?php endif;?>
<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('first name');?></label>

						<div class="col-sm-5">
							<input type="text" class="form-control"  data-validate="required" data-message-required="<?php echo get_phrase('your first name');?>" name="fname"
                            	value="<?=getIndex($data,'fname');?>">
						</div>
					</div>

				<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('last name');?></label>

						<div class="col-sm-5">
							<input type="text" class="form-control" name="surname" data-validate="required" data-message-required="<?php echo get_phrase('Your last name');?>"
                            	value="<?=getIndex($data,'surname');?>">
						</div>
					</div>

					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('phone');?></label>
						<div class="col-sm-5">
							<input type="text" class="form-control" name="phone"
    data-validate="required"      data-message-required="<?php echo get_phrase('enter phone number');?>"                  	value="<?=getIndex($data,'phone');?>">
						</div>
					</div>

					<div class="form-group" >
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('select branch');?></label>

						<div class="col-sm-5">
							<select class="form-control" name="branch_id" data-validate="required"      data-message-required="<?php echo get_phrase('select branch');?>" id="branch_id" onchange="list_terms()" >
								<option value="">Select Branch</option>
								<?php foreach($branch as $row):?>
									<option value="<?=$row['id'];?>" <?=$row['id']==getIndex($data,'branch_id')?"selected":"";?> ><?=$row['name'];?></option>
								<?php endforeach;?>

							</select>
						</div>
					</div>

					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo $type == 'hall'? get_phrase('select hall'): get_phrase('select room');?></label>

						<div class="col-sm-5">
							<select id="hall_id" onchange="showAmount()" class="form-control" name="hall_id" data-validate="required"      data-message-required="<?php echo get_phrase('select hall/room');?>" >

							</select>
						</div>
					</div>

					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('capacity');?></label>

						<div class="col-sm-5">
							<input type="text" id="capacity" readonly style="font-weight: bold; " class="form-control" name="capacity" value="">
						</div>
					</div>

					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('amount');?></label>

						<div class="col-sm-5">
							<input type="text" id="amount" readonly style="font-weight: bold; color: red;" class="form-control" name="amount" value="<?=getIndex($data,'amount');?>">
						</div>
					</div>

					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('book date');?></label>

						<div class="col-sm-5">
							<input type="date" id="date" style="font-weight: bold; color: green;" class="form-control" name="date" value="<?=getIndex($data,'date');?>">
						</div>
					</div>

					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('days');?></label>

						<div class="col-sm-5">
							<input type="number" id="days" ondurationchange="showAmount()" style="color: green;" class="form-control number" name="days" value="<?=getIndex($data,'days',1);?>">
						</div>
						<a class="btn btn-warning " onclick="checkHall()">Check <?=ucwords($type);?> Availability</a>
					</div>

				<div class="form-group">
					<label for="field-2" class="col-sm-3 control-label"></label>

					<div class="col-sm-5" id="response">

					</div>


				</div>




				<div class="form-group">
					<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('payment method');?></label>

					<div class="col-sm-5">
						<select class="form-control" name="method" data-validate="required"      data-message-required="<?php echo get_phrase('Select payment method');?>">
							<option value="" >Select Method</option>
							<option value="Cash">Cash</option>
							<option value="Bank">Bank</option>
						</select>
					</div>
				</div>


				<div class="form-group">
					<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('total');?></label>

					<div class="col-sm-5">
						<input type="text" id="total"  readonly style="font-weight: bold; color: red;" class="form-control" name="total" value="<?=getIndex($data,'total');?>">
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
	var currentterm = "',$current_term,'";
	var currentterm = <?=getIndex($data,'hall_id',0);?>;
	var session = <?=json_encode($branch_);?>;
	function list_terms(ses,term){
			var term_id = $("#branch_id").val();
			$el = $("#hall_id");
		try {
			$el.html("");
			var lop = session[term_id];
			$.each(lop, function (key, value) {
				if(currentterm == value.id){
					$el.append($("<option selected data-amount='"+value.amount+"' data-capacity='"+value.capacity+"'></option>")
						.attr("value", value.id).text(value.name));
				}else{
					$el.append($("<option data-capacity='"+value.capacity+"' data-amount='"+value.amount+"'></option>")
						.attr("value", value.id).text(value.name));
				}
			});
		} catch (e) {}
		showAmount();

	}
	list_terms();
	currentterm = "";

	function showAmount(){
		$el = $("#hall_id option:selected").data('amount');

		$ca = $("#hall_id option:selected").data('capacity');

		$("#capacity").val($ca);

		$("#amount").val(format_number($el));
		$total = parseInt($('#days').val()) * parseInt($el);
		$ans = format_number($total);
		$("#total").val($ans);
	}
	$("#days").bind('keyup change click mouseup',function(){
		showAmount();
	});

	function format_number($number){
		if($number == undefined || isNaN($number) || ($number+"").trim() == "")
			return "";

		return parseFloat(($number+"").replace(/,/g, "").replace("N",""))
			.toFixed(2)
			.toString()
			.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}


	function checkHall(){
		var date = $("#date").val();
		var day = $("#days").val();
		var hall_id = $("#hall_id").val();
		if(date == '' || day == '' | hall_id == ''){
			alert('Please select a date, day and hall/room');
			return;
		}
		jQuery('#response').html('<b>Checking hall. Please wait.....</b>');
		var url = '<?=base_url()."?users/check_hall/";?>'+hall_id+"/"+date+"/"+day+"/<?=$type;;?>";
		$.ajax({
			url: url,
			success: function(response)
			{
				jQuery('#response').html(response);
			}
		});
	}



</script>