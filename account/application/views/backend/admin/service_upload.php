<?php

$spec = c()->get('specializations')->result_array();
$spec_ = array();
foreach($spec as $row)
	$spec_[$row['id']] = $row['name'];

	if($param3 == 'update'){
		$row= d()->get_where("services",array("id"=>$param4))->row();
		$name = $row->name;
		$amount = $row->amount;
	}else{
		$name = "";
		$amount = "";
	}
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-warning" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title">
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('Upload Product/Service');?>
            	</div>
            </div>
			<div class="panel-body">
				
                <?php echo form_open(base_url() . 'index.php?admin/services/upload' , array('class' => 'form-horizontal  validate', 'enctype' => 'multipart/form-data'));?>
                    
					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('specialization');?></label>
                        
						<div class="col-sm-5">
							<select name="specialization" class="form-control">

								<?php foreach($spec_ as $k => $v):?>
									<option <?=$k==$param2?"selected":"";?> value="<?=$k;?>" ><?=$v;?></option>
								<?php endforeach;?>
							</select>
						</div>
					</div>

                    <div class="form-group">
						<div class="col-sm-offset-3 col-sm-5">
							<button type="submit" class="btn btn-warning"><?php echo get_phrase('upload');?></button>
						</div>
					</div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>