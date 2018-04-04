<style>
	input, select{
		width: 100%;
		margin-bottom: 10px;;
	}
</style>
<input type="hidden" ng-init="book.currentterm = <?=getIndex($data,'hall_id',0);?>"/>
<div style="width: 100%">
					<h3><?php echo get_phrase("$type reservation");?></h3>


				<?php if($type == 'hall'):?>
					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('event name');?></label>

						<div class="col-sm-5">
							<input type="text" class="form-control" required data-validate="required" ng-model="book.event" data-message-required="<?php echo get_phrase('Enter Event Name');?>" name="event"
							       autofocus                       	value="<?=getIndex($data,'event');?>">
						</div>
					</div>
<?php endif;?>
<div class="form-group">
						<label for="field-1"  class="col-sm-3 control-label"><?php echo get_phrase('first name');?></label>

						<div class="col-sm-5">
							<input type="text" ng-model="book.fname" class="form-control"  data-validate="required" data-message-required="<?php echo get_phrase('your first name');?>" name="fname"
                            	value="<?=getIndex($data,'fname');?>">
						</div>
					</div>

				<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('last name');?></label>

						<div class="col-sm-5">
							<input type="text" ng-model="book.surname" required class="form-control" name="surname" data-validate="required" data-message-required="<?php echo get_phrase('Your last name');?>"
                            	value="<?=getIndex($data,'surname');?>">
						</div>
					</div>

					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('phone');?></label>
						<div class="col-sm-5">
							<input type="text" class="form-control" ng-model="book.phone" name="phone"
    data-validate="required"      data-message-required="<?php echo get_phrase('enter phone number');?>"                  	value="<?=getIndex($data,'phone');?>">
						</div>
					</div>

					<div class="form-group" >
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('select branch');?></label>

						<div class="col-sm-5">
							<select class="form-control" name="branch_id" ng-model="book.branch" data-validate="required"      data-message-required="<?php echo get_phrase('select branch');?>" id="branch_id" onchange="list_terms()" >
								<option value="">Select Branch</option>
								<?php foreach($branch as $row):?>
									<option value="<?=$row['id'];?>"  ><?=$row['name'];?></option>
								<?php endforeach;?>

							</select>
						</div>
					</div>

					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo $type == 'hall'? get_phrase('select hall'): get_phrase('select room');?></label>

						<div class="col-sm-5">
							<select id="hall_id" ng-model="book.hall_id" onchange="showAmount()" class="form-control" name="hall_id" data-validate="required"      data-message-required="<?php echo get_phrase('select hall/room');?>" >

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
							<input type="date" ng-model="book.date" id="date" style="font-weight: bold; color: green;" class="form-control" name="date" value="<?=getIndex($data,'date');?>">
						</div>
					</div>

					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('days');?></label>

						<div class="col-sm-5">
							<input type="number" id="days" ng-model="book.days" ondurationchange="showAmount()" onchange="showAmount()" onkeydown="showAmount()" onclick="showAmount" style="color: green;" class="form-control number" name="days" value="1">
						</div>
						<a style="float: right; background: white; color: black;"  class="btn btn-warning " ng-click="checkHall()">Check <?=ucwords($type);?> Availability</a>

					</div>

				<div class="form-group" style="width: 100%;">


					<div class="col-sm-5" style="width: 100%;" id="response">

					</div>


				</div>

<br>


				<div class="form-group">
					<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('payment method');?></label>

					<div class="col-sm-5">
						<select class="form-control" name="method" ng-model="book.method" data-validate="required"      data-message-required="<?php echo get_phrase('Select payment method');?>">
							<option value="" >Select Method</option>
							<option value="Cash">Cash</option>
							<option value="Bank">Bank</option>
						</select>
					</div>
				</div>


				<div class="form-group">
					<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('total');?></label>

					<div class="col-sm-5">
						<input type="text" id="total" ng-model="book.total"  readonly style="font-weight: bold; color: red;" class="form-control" name="total" value="<?=getIndex($data,'total');?>">
					</div>
				</div>

<div ng-bind="book.error" style="color: orange; font-weight: bold;"></div>

                    <div class="form-group">
						<div class="col-sm-offset-3 col-sm-5">
							<div class="mybutton mybcurve" style="height: 30px; " ng-click="book_now('<?=$type;?>')" ><span><i class="fa fa-send"></i> Book </span></div>
						</div>
					</div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" ng-init="book.session = <?=str_replace("\"","'",json_encode($branch_));?>"/>

