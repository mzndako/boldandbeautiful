<div class="row">
	<div class="col-md-12">
    
    	<!------CONTROL TABS START------>
		<ul class="nav nav-tabs bordered">
			<li class="active">
            	<a href="#listadmin" data-toggle="tab"><i class="entypo-menu"></i>
					<?php echo get_phrase('admin_users');?>
                    	</a></li>
			<li>

            <li >
            	<a href="#permission" data-toggle="tab"><i class="entypo-menu"></i>
					<?php echo get_phrase('general_permission');?>
                </a>
            </li>


            <?php if($this->session->hAccess('manage_permissions')): ?>
			<li>
            	<a href="#add" data-toggle="tab"><i class="entypo-plus-circled"></i>
					<?php echo get_phrase('add_admin');?>
                </a>
            </li>
            <?php endif; ?>
		</ul>
    	<!------CONTROL TABS END------>
        
		<div class="tab-content">
            <!----TABLE LISTING STARTS-->
            <div class="tab-pane box active" id="listadmin">
				
                <table class="table table-bordered datatable" id="table_export">
                	<thead>
                		<tr>
                    		<th><div>#</div></th>
                    		<th><div><?php echo get_phrase('name');?></div></th>
                    		<th><div><?php echo get_phrase('email');?></div></th>
                    		<th><div><?php echo get_phrase('permission');?></div></th>
                    		<th><div><?php echo get_phrase('options');?></div></th>
						</tr>
					</thead>
                    <tbody>
                    	<?php  $count = 1;foreach($admins as $row):?>
                        <tr>
                            <td><?php echo $count++;?></td>
							<td><?php echo $row['name'];?></td>
							<td><?php echo $row['email'];?></td>
							<td><?php echo $this->c_->convert_permission($row['access']);?></td>
							<td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                    Action <span class="caret"></span>
                                </button>
                            <?php if($this->session->hAccess('manage_permissions')): ?>
                                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                    
                                    <!-- EDITING LINK -->

                                    <li>
                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_edit_security/<?php echo $row['admin_id'];?>');">
                                            <i class="entypo-pencil"></i>
                                                <?php echo get_phrase('edit');?>
                                            </a>
                                                    </li>
                                    <li class="divider"></li>
                                    
                                    <!-- DELETION LINK -->
                                    <li>
                                        <a href="#" onclick="confirm_modal('<?php echo base_url();?>index.php?admin/security/delete/<?php echo $row['admin_id'];?>');">
                                            <i class="entypo-trash"></i>
                                                <?php echo get_phrase('delete');?>
                                            </a>
                                    </li>
                                </ul>
                            <?php endif;?>
                            </div>
        					</td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
			</div>
            <!----TABLE LISTING ENDS--->

            <!----TABLE LISTING STARTS-->
            <div class="tab-pane box" id="permission">
                <?php echo form_open(base_url() . 'index.php?admin/security/update' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                <table class="table table-bordered datatable">
                	<thead>
                		<tr>
                    		<th rowspan="2"><div><?php echo get_phrase('permission');?></div></th>
                    		<th><div><?php echo get_phrase('student\'s');?></div></th>
                    		<th><div><?php echo get_phrase('parent\'s');?></div></th>
                            <?php
                                $t = c()->get("teacher_categories")->result_array();
                                foreach($t as $row){
                            ?>
                                    <th><div><?php echo get_phrase($row['name']);?></div></th>
                            <?php } ?>
						</tr>
					</thead>
                    <tbody>

                    	<?php
                        $access = $this->c_->all_access();
                        $student_perm = explode(",",$this->c_->get_setting("students_access",""));
                        $parent_perm = explode(",",$this->c_->get_setting("parents_access",""));
//                        $astaff_perm = explode(",",$this->c_->get_setting("academic_staff_access",""));
//                        $nstaff_perm = explode(",",$this->c_->get_setting("non_academic_staff_access",""));

                        foreach($access as $x):
                            ?>
                        <tr>
                            <td>
                                <?php echo $this->c_->convert_permission($x);?>
                            </td>
							<label><td ><?php
                                $checked = in_array($x,$student_perm)?"checked":"";
                                echo "<input type=checkbox name='students[]' $checked value='$x'/> ";
                                ?>
                            </td></label>
                            <td><?php
                                $checked = in_array($x,$parent_perm)?"checked":"";
                                echo "<input type=checkbox name='parents[]' $checked value='$x'/> ";
                                ?>
                            </td>

                            <?php

                            foreach($t as $row){ ?>
                            <td>
                                <?php
                                $myperm = explode(",",$row['access']);
                                $checked = in_array($x,$myperm)?"checked":"";
                                echo "<input type=checkbox name='tc".$row['category_id']."[]' $checked value='$x'/> ";
                                ?>

                            </td>
                          <?php  } ?>


                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                <div class="form-group">
                    <div class="col-sm-offset-4 col-sm-4" align="center">
                        <button type="submit" class="btn btn-info"><?php echo get_phrase('save_settings');?></button>
                    </div>
                </div>
                </form>
			</div>
            <!----TABLE LISTING ENDS--->
            



            <!----CREATION FORM STARTS---->
			<div class="tab-pane box" id="add" style="padding: 5px">
                <div class="box-content">
                	<?php echo form_open(base_url() . 'index.php?admin/security/create' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                        <div class="padded">
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('name');?></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="name" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('email');?></label>
                                <div class="col-sm-5">
                                    <input type="email" class="form-control" name="email" required/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('password');?></label>
                                <div class="col-sm-5">
                                    <input type="password" class="form-control" name="password" required/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('permissions');?></label>
                                <div class="col-sm-5">
                                    <select name="permissions[]" class="form-control select3" multiple="multiple" data-validate="required" data-placeholder="Select Permissions" data-message-required="<?php echo get_phrase
                                    ('value_required');?>">

                                        <?php
                                        $access = $this->c_->all_access();
                                        foreach($access as $a):
                                            ?>
                                            <option value="<?php echo $a;?>" >
                                                <?php echo $this->c_ ->convert_permission($a);?>
                                            </option>
                                            <?php
                                        endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                              <div class="col-sm-offset-3 col-sm-5">
                                  <button type="submit" class="btn btn-info"><?php echo get_phrase('add_class');?></button>
                              </div>
							</div>
                    </form>
                </div>
			</div>
			<!----CREATION FORM ENDS-->
		</div>
	</div>
</div>



<!-----  DATA TABLE EXPORT CONFIGURATIONS ---->                      
<script type="text/javascript">

	jQuery(document).ready(function($)
	{
		
        $(".select3").select2();
		var datatable = $("#table_export").dataTable();
		
		$(".dataTables_wrapper select").select2({
			minimumResultsForSearch: -1
		});
	});
		
</script>