<?php

if($param3 == 'update'){
	$halls = d()->get_where("halls",array("id"=>$param4))->row_array();
}else{
	$halls = array('branch_id'=>$param4);
}
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-warning" data-collapsed="0">
			<div class="panel-heading">
				<div class="panel-title">
					<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('Manage '.$param2);?>
				</div>
			</div>
			<div class="panel-body">

				<?php echo form_open(base_url() . 'index.php?admin/manage_halls/'.$param2.'/'.($param3 == "update"?'update/'.$param4:'create') , array('class' => 'form-horizontal  validate', 'enctype' => 'multipart/form-data'));?>

				<div class="form-group">
					<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('branch');?></label>

					<div class="col-sm-5">
						<input type="text" class="form-control" disabled="disabled" name="b" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"  autofocus
						       value="<?=getIndex(c()->get_where('branch','id',getIndex($halls,"branch_id"))->row_array(),"name");?>">

						<input type="hidden" name="branch_id" value="<?=getIndex($halls,"branch_id");?>">
					</div>
				</div>

<div class="form-group">
					<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase($param2.'_name');?></label>

					<div class="col-sm-5">
						<input type="text" class="form-control" name="name" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"  autofocus
						       value="<?=getIndex($halls,"name");?>">
					</div>
				</div>


<div class="form-group">
					<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('location');?></label>

					<div class="col-sm-5">
						<input type="text" class="form-control" name="address" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"  autofocus
						       value="<?=getIndex($halls,"address");?>">
					</div>
				</div>


<div class="form-group">
					<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('capacity');?></label>

					<div class="col-sm-5">
						<input type="text" class="form-control" name="capacity" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"  autofocus
						       value="<?=getIndex($halls,"capacity");?>">
					</div>
				</div>


<div class="form-group">
					<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('amount');?></label>

					<div class="col-sm-5">
						<input type="text" class="form-control number" name="amount" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"  autofocus
						       value="<?=getIndex($halls,"amount");?>">
					</div>
				</div>


				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-5">
						<button type="submit" class="btn btn-warning"><?php echo get_phrase('save');?></button>
					</div>
				</div>
				<?php echo form_close();?>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$('.number').blur(function(){
		if(this.value.trim() == "")
			return;
		this.value = parseFloat(this.value.replace(/,/g, "").replace("N",""))
			.toFixed(2)
			.toString()
			.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

		// document.getElementById("display").value = this.value.replace(/,/g, "")

	});
</script>