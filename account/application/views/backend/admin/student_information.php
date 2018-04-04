<hr />
<?php if($s_->hAccess("admit_student")): ?>
<a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/student_add/','student_modal_dialog');"
    class="btn btn-primary pull-right">
        <i class="entypo-plus-circled"></i>
        <?php echo get_phrase('add_new_student');?>
    </a>
<?php endif;?>
<br>

<div class="row">
    <div class="col-md-12">
        
        <ul class="nav nav-tabs bordered">
            <li class="active">
                <a href="#home" data-toggle="tab">
                    <span class="visible-xs"><i class="entypo-users"></i></span>
                    <span class="hidden-xs"><?php echo get_phrase('all_students');?></span>
                </a>
            </li>

        </ul>
        
        <div class="tab-content">
            <div class="tab-pane active" id="home">
                
                <table class="table table-bordered datatable" id="table_export">
                    <thead>
                        <tr>
                            <th width="80"><div><?php echo get_phrase('admission_no');?></div></th>
                            <th width="80"><div><?php echo get_phrase('photo');?></div></th>
                            <th><div><?php echo get_phrase('name');?></div></th>
                            <th class="span3"><div><?php echo get_phrase('address');?></div></th>
                            <th><div><?php echo get_phrase('email');?></div></th>
                            <th><div><?php echo get_phrase('options');?></div></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $data = array();
                        if($this->c_->isStudent()){
                            $data["student_id"] = $login_id;
                        }elseif($this->c_->isTeacher()){
                            $ids = $this->c_->get_ids("class",array("teacher_id"=>$login_id),"class_id");
                            $this->db->where_in("class_id",$ids);
                        }elseif($this->c_->isParent()){
                            $ids = $this->c_->get_all_student_for_parent($login_id);
                            $this->db->where_in("class_id",$ids);
                        }
                        $data['class_id'] = $class_id;
                                $students   =   $c_->get_where('student',$data)->result_array();

                                foreach($students as $row):?>
                        <tr>
                            <td><?php echo $row['admission_no'];?></td>
                            <td><img src="<?php echo $this->crud_model->get_image_url('student',$row['student_id']);?>" class="img-circle" width="30" /></td>
                            <td><?php echo $row['surname'],', ',$row['fname'],' ',$row['mname'];?></td>
                            <td><?php echo $row['permanent_address'];?></td>
                            <td><?php echo $row['email'];?></td>
                            <td>
                                
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">

                                        <?php if($this->c_->isStudent() && $login_id == $row['student_id']):?>
                                            <li>
                                                <a href="<?php echo base_url();?>?admin/manage_profile">
                                                    <i class="entypo-book-open"></i>
                                                    <?php echo get_phrase('update_my_account');?>
                                                </a>
                                            </li>
                                            <li class="divider"></li>
                                        <?php endif; ?>

                                        <!-- STUDENT MARKSHEET LINK  -->
<!--                                        <li>-->
<!--                                            <a href="--><?php //echo base_url();?><!--index.php?admin/student_marksheet/--><?php //echo $row['student_id'];?><!--">-->
<!--                                                <i class="entypo-chart-bar"></i>-->
<!--                                                    --><?php //echo get_phrase('mark_sheet');?>
<!--                                                </a>-->
<!--                                        </li>-->

                                        
                                        <!-- STUDENT PROFILE LINK -->
                                        <li>
                                            <a href="#" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/student_add/<?php echo $row['student_id'];?>/show');">
                                                <i class="entypo-user"></i>
                                                    <?php echo get_phrase('profile');?>
                                                </a>
                                        </li>
                                        
                                        <!-- STUDENT EDITING LINK -->
                                       <?php if($s_->hAccess('update_student')): ?>
                                           <li>
                                               <a href="#" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/student_add/<?php echo $row['student_id'];?>','student_modal_dialog');">
                                                   <i class="entypo-pencil"></i>
                                                   <?php echo get_phrase('edit');?>
                                               </a>
                                           </li>
                                        <?php endif; ?>

                                        <?php if($s_->hAccess('delete_student')): ?>
                                            <li class="divider"></li>

                                            <!-- STUDENT DELETION LINK -->
                                            <li>
                                                <a href="#" onclick="confirm_modal('<?php echo base_url();?>index.php?admin/student/<?php echo $class_id;?>/delete/<?php echo $row['student_id'];?>');">
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


        </div>
        
        
    </div>
</div>



<!-----  DATA TABLE EXPORT CONFIGURATIONS ---->                      
<script type="text/javascript">

	jQuery(document).ready(function($)
	{
		

		var datatable = $("#table_export").dataTable({
			"sPaginationType": "bootstrap",
			"sDom": "<'row'<'col-xs-3 col-left'l><'col-xs-9 col-right'<'export-data'T>f>r>t<'row'<'col-xs-3 col-left'i><'col-xs-9 col-right'p>>",
			"oTableTools": {
				"aButtons": [
					
					{
						"sExtends": "xls",
						"mColumns": [0, 2, 3, 4]
					},
					{
						"sExtends": "pdf",
						"mColumns": [0, 2, 3, 4]
					},
					{
						"sExtends": "print",
						"fnSetText"	   : "Press 'esc' to return",
						"fnClick": function (nButton, oConfig) {
							datatable.fnSetColumnVis(1, false);
							datatable.fnSetColumnVis(5, false);
							
							this.fnPrint( true, oConfig );
							
							window.print();
							
							$(window).keyup(function(e) {
								  if (e.which == 27) {
									  datatable.fnSetColumnVis(1, true);
									  datatable.fnSetColumnVis(5, true);
								  }
							});
						},
						
					},
				]
			},
			
		});
		
		$(".dataTables_wrapper select").select2({
			minimumResultsForSearch: -1
		});
	});
		
</script>