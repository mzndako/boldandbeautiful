<?php
	if($param2 == 'update'){
		$branch_name = d()->get_where("specializations",array("id"=>$param3))->row()->name;
	}else{
		$branch_name = "";
	}
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-warning" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title">
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('Specialization');?>
            	</div>
            </div>
			<div class="panel-body">
				
                <?php echo form_open(base_url() . 'index.php?admin/manage_specialization/'.($param2 == "update"?'update/'.$param3:'create') , array('class' => 'form-horizontal  validate', 'enctype' => 'multipart/form-data'));?>
                    
					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('specialization');?></label>
                        
						<div class="col-sm-5">
							<input type="text" class="form-control" name="name" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"  autofocus
                            	value="<?=$branch_name;?>">
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