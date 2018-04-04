<?php 
$edit_data		=	$this->db->get_where('schools' , array('school_id' => $param2) )->result_array();
foreach ( $edit_data as $row):
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">

            <div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('edit_session');?>
            	</div>
            </div>
			<div class="panel-body">
				
                <?php echo form_open(base_url() . 'index.php?superadmin/schools/update/'.$row['school_id'] , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>

                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('name');?></label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="name"  value="<?php echo $row['name'];?>"/>
                        </div>
                    </div>

                <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('domain');?></label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="domain"  value="<?php echo $row['domain_name'];?>"/>
                        </div>
                    </div>



                <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('permissions');?></label>
                        <div class="col-sm-9">
                            <select name="permissions[]"  class="form-control select2" multiple="multiple" data-placeholder="Select permissions">

                                <?php
                                $access = $this->c_->all_access();
                                $myaccess = @explode(",",$row['access']);
                                foreach($access as $x):
                                    ?>
                                    <option <?php echo in_array($x,$myaccess)?"selected":"";?> value="<?php echo $x;?>" ><?php echo  $this->c_->convert_permission($x);?></option>
                                    <?php
                                endforeach;
                                ?>
                            </select>


                        </div>
                    </div>


            		<div class="form-group">
						<div class="col-sm-offset-3 col-sm-5">
							<button type="submit" class="btn btn-info"><?php echo get_phrase('save');
                                ?></button>
						</div>
					</div>
        		</form>
            </div>
        </div>
    </div>
</div>

<?php
endforeach;
?>



