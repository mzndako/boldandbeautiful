<div class="row">
	<div class="col-md-12">
    
    	<!------CONTROL TABS START------>
		<ul class="nav nav-tabs bordered">
			<li class="active">
            	<a href="#list_session" data-toggle="tab"><i class="entypo-menu"></i>
					<?php echo get_phrase('session');?>
                    	</a></li>
            <?php if($s_->hAccess('manage_session')): ?>
                <li>
                    <a href="#addsession" data-toggle="tab"><i class="entypo-plus-circled"></i>
                        <?php echo get_phrase('create_session');?>
                    </a>
                </li>
                <?php
            endif;
            ?>

            <?php if($s_->hAccess('manage_session')): ?>
                <li>
                    <a href="#addterm" data-toggle="tab"><i class="entypo-plus-circled"></i>
                        <?php echo get_phrase('add_term');?>
                    </a>
                </li>

            <?php endif; ?>

		</ul>
    	<!------CONTROL TABS END------>
		<div class="tab-content">
            <!----TABLE LISTING STARTS-->
            <div class="tab-pane box active" id="list_session">
                <table  class="table table-bordered datatable" id="table_export">
                	<thead>
                		<tr>
                    		<th><div><?php echo get_phrase('session');?></div></th>
                    		<th><div><?php echo get_phrase('terms');?></div></th>
                    		<th><div><?php echo get_phrase('options');?></div></th>
						</tr>
					</thead>
                    <tbody>
                    	<?php
                        $current_year = $this->c_->get_setting("current_term");
                        foreach($session_list as $row):?>
                        <tr>
							<td><?php echo $row['name'];?></td>
							<td>

<!--                                START OF TERM LISTING INSIDE SESSION-->
                                <table  class="table table-bordered datatable" id="table_export">
                                    <thead>
                                    <tr>
                                        <th><div><?php echo get_phrase('terms');?></div></th>
                                        <th><div><?php echo get_phrase('start');?></div></th>
                                        <th><div><?php echo get_phrase('end');?></div></th>
                                        <th><div><?php echo get_phrase('options');?></div></th>
                                        <th><div><?php echo get_phrase('mark_current');?></div></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $term_list = $this->c_->get_where("term",array("year_id"=>$row['year_id']))->result_array();
                                    $term_list = $this->c_->rearrange($term_list,$row['myorder'],"term_id");
                                    foreach($term_list as $term):?>
                                        <tr>
                                            <td><?php echo $term['name'];?></td>
                                            <td><?php echo  date("j F, Y",$term['start']);?></td>
                                            <td><?php echo date("j F, Y",$term['end']);?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                                        Action <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">

                                                        <!-- EDITING LINK -->
                                                        <?php if($s_->hAccess('manage_term')): ?>
                                                            <li>
                                                                <a href="#" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_edit_term/<?php echo $term['term_id'];?>');">
                                                                    <i class="entypo-pencil"></i>
                                                                    <?php echo get_phrase('edit');?>
                                                                </a>
                                                            </li>
                                                        <?php endif; ?>



                                                        <!-- DELETION LINK -->
                                                        <?php if($s_->hAccess('manage_term')): ?>
                                                            <li class="divider"></li>
                                                            <li>
                                                                <a href="#" onclick="confirm_modal('<?php echo base_url();?>index.php?admin/session/delete_term/<?php echo $term['term_id'];?>');">
                                                                    <i class="entypo-trash"></i>
                                                                    <?php echo get_phrase('delete');?>
                                                                </a>
                                                            </li>
                                                        <?php endif; ?>

                                                    </ul>
                                                </div>
                                            </td>
                                            <td>
                                                <?php
                                                if($current_year != $term['term_id'])
                                            print "<a href='".base_url()."index.php?admin/session/activate/".$term['term_id']."' style='text-decoration: none; color: blue;'>Mark As Current</a>";
                                                else
                                                    print "<span style='color: red;'>CURRENT TERM</span>";
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                </table>
<!--                                END OF TERM LISTING-->

                                </td>

							<td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                    Action <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-default pull-right" role="menu">

                                    <!-- EDITING LINK -->
                                    <?php if($s_->hAccess('manage_term')): ?>
                                        <li>
                                            <a href="#" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_edit_session/<?php echo $row['year_id'];?>');">
                                                <i class="entypo-pencil"></i>
                                                <?php echo get_phrase('edit');?>
                                            </a>
                                        </li>
                                    <?php endif; ?>



                                    <!-- DELETION LINK -->
                                   <?php if($s_->hAccess('manage_term')): ?>
                                       <li class="divider"></li>
                                       <li>
                                           <a href="#" onclick="confirm_modal('<?php echo base_url();?>index.php?admin/session/delete/<?php echo $row['year_id'];?>');">
                                               <i class="entypo-trash"></i>
                                               <?php echo get_phrase('delete');?>
                                           </a>
                                       </li>
                                <?php endif; ?>

                                </ul>
                            </div>
        					</td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
			</div>
            <!----TABLE LISTING ENDS--->



			<!----CREATION FORM CREATE SESSION STARTS---->
			<div class="tab-pane box" id="addsession" style="padding: 5px">
                <div class="box-content">
                	<?php echo form_open(base_url() . 'index.php?admin/session/create' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>

                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('name');?></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" required name="name" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
                                </div>
                            </div>




                        		<div class="form-group">
                              	<div class="col-sm-offset-3 col-sm-5">
                                  <button type="submit" class="btn btn-info"><?php echo get_phrase('add_session');
                                      ?></button>
                              	</div>
								</div>
                    </form>
                </div>
			</div>
			<!----CREATION FORM ENDS-->


            <!----CREATION FORM ADD TERM STARTS---->
            <div class="tab-pane box" id="addterm" style="padding: 5px">
                <div class="box-content">
                    <?php echo form_open(base_url() . 'index.php?admin/session/create_term' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>

                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('name');?></label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" required name="name" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('term_start_date');?></label>
                        <div class="col-sm-5">
                            <input type="text" class="datepicker form-control" name="start"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('term_end_date');?></label>
                        <div class="col-sm-5">
                            <input type="text" class="datepicker form-control" name="end"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('session');?></label>
                        <div class="col-sm-5">
                            <select name="year_id" class="form-control selectboxit"  data-validate="required" data-placeholder="Select Session" data-message-required="<?php echo get_phrase
                            ('select_session');?>">
<option value="">Select Session</option>
                                <?php
                                $year = $this->c_->get('year')->result_array();
                                foreach($year as $row2):
                                    ?>
                                    <option value="<?php echo $row2['year_id'];?>" >
                                        <?php echo $row2['name'];?>
                                    </option>
                                    <?php
                                endforeach;
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" class="btn btn-info"><?php echo get_phrase('add_term');
                                ?></button>
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
		

		var datatable = $("#table_export").dataTable();
		
		$(".dataTables_wrapper select").select2({
			minimumResultsForSearch: -1
		});
	});
		
</script>