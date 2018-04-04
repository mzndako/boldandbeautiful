<div class="row">
	<div class="col-md-12">
    
    	<!------CONTROL TABS START------>
		<ul class="nav nav-tabs bordered">
			<li class="active">
            	<a href="#list" data-toggle="tab"><i class="entypo-menu"></i> 
					<?php echo get_phrase('schools');?>
                    	</a></li>

                <li>
                    <a href="#add" data-toggle="tab"><i class="entypo-plus-circled"></i>
                        <?php echo get_phrase('add_school');?>
                    </a></li>


		</ul>
    	<!------CONTROL TABS END------>
		<div class="tab-content">
            <!----TABLE LISTING STARTS-->
            <div class="tab-pane box active" id="list">
                  <table  class="table table-bordered datatable"  id="table_export">
                	<thead>
                		<tr>
                    		<th><div><?php echo get_phrase('school_id');?></div></th>
                    		<th><div><?php echo get_phrase('name');?></div></th>
                    		<th><div><?php echo get_phrase('domain');?></div></th>
                    		<th><div><?php echo get_phrase('students');?></div></th>
                    		<th><div><?php echo get_phrase('staff');?></div></th>
                    		<th><div><?php echo get_phrase('parents');?></div></th>
                    		<th><div><?php echo get_phrase('status');?></div></th>
                    		<th><div><?php echo get_phrase('options');?></div></th>
						</tr>
					</thead>
                    <tbody>
                    	<?php foreach($schools as $row):?>
                        <tr>
							<td><?php echo $row['school_id'];?></td>
							<td><?php echo $row['name'];?></td>
							<td><?php echo $row['domain_name'];?></td>
							<td><?php echo $this->db->get_where("student",array("school_id",$row['school_id']))->num_rows();?></td>
							<td><?php echo $this->db->get_where("teacher",array("school_id",$row['school_id']))->num_rows();?></td>
							<td><?php echo $this->db->get_where("parent",array("school_id",$row['school_id']))->num_rows();?></td>
							<td><?php echo $row['active'] == 1?"<b style='color: blue'>ACTIVE</b>":"<b style='color: red;'>DEACTIVATED</b>";?></td>
							<td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                    Action <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-default pull-right" role="menu">


                                        <!-- EDITING LINK -->
                                        <li>
                                            <a href="#" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/super/modal_edit_school/<?php echo $row['school_id'];?>');">
                                                <i class="entypo-pencil"></i>
                                                <?php echo get_phrase('edit');?>
                                            </a>
                                        </li>
                                        <li class="divider"></li>

                                    <li>
                                            <a href="<?php echo base_url();?>?superadmin/login_as/superadmin/<?php echo $row['domain_name'];?>/<?php echo $row['school_id'];?>">
                                                <i class="entypo-direction"></i>
                                                <?php echo get_phrase('login_as_super_admin');?>
                                            </a>
                                        </li>
                                    <li>
                                            <a href="#" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/super/modal_show_admin/<?php echo $row['school_id'];?>');">
                                                <i class="entypo-adjust"></i>
                                                <?php echo get_phrase('login_as_admin');?>
                                            </a>
                                        </li>
                                        <li class="divider"></li>

                                        <!-- DELETION LINK -->
                                        <li>
                                            <a href="#" onclick="confirm_modal('<?php echo base_url();?>index.php?superadmin/schools/delete/<?php echo $row['school_id'];?>');">
                                                <i class="entypo-trash"></i>
                                                <?php echo get_phrase('delete');?>
                                            </a>
                                        </li>


                                </ul>
                            </div>
        					</td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
			</div>
            <!----TABLE LISTING ENDS--->
            
            
			<!----CREATION FORM STARTS---->
			<div class="tab-pane box" id="add" style="padding: 5px">
                <div class="box-content">
                	<?php echo form_open(base_url() . 'index.php?superadmin/schools/create' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                        
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('school_name');?></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="name" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
                                </div>
                            </div>

                    <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('domain');?></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="domain" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
                                </div>
                            </div>


                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('permission\'s_enabled');?></label>
                        <div class="col-sm-5">
                            <select name="permissions[]" class="form-control select3" multiple="multiple" data-validate="required" data-placeholder="Select Permissions" data-message-required="<?php echo get_phrase
                            ('value_required');?>">

                                <?php
                                $access = $this->c_->all_access();
                                foreach($access as $a):
                                    ?>
                                    <option selected value="<?php echo $a;?>" >
                                        <?php echo $this->c_ ->convert_permission($a);?>
                                    </option>
                                    <?php
                                endforeach;
                                ?>
                            </select>
                        </div>
                    </div>






                        		<div class="form-group">
                              	<div class="col-sm-offset-3 col-sm-5">
                                  <button type="submit" class="btn btn-info"><?php echo get_phrase('add_school');?></button>
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
    var datatable;
	jQuery(document).ready(function($)
	{
		$(".select3").select2();

		datatable = $("#table_export").dataTable();
		
		$(".dataTables_wrapper select").select2({
			minimumResultsForSearch: -1
		});
	});
</script>