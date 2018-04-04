

	           <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>?modal/popup/modal_staff_categories/');"
	              class="btn btn-primary pull-right">
		           <i class="entypo-plus-circled"></i>
		           <?php echo get_phrase('add_new_category');?>
	           </a>

                <br><br>

            <ul class="nav nav-tabs bordered">
	            <li class="active">
		            <a href="#academic" data-toggle="tab"><i class="entypo-book-open"></i>
			            <?php echo get_phrase('staff_categories');?>
		            </a>
	            </li>

            </ul>

            <div class="tab-content">
	            <!----TABLE LISTING STARTS-->
	            <div class="tab-pane box active" id="academic">
               <table class="table table-bordered datatable" id="table_export">
                    <thead>
                        <tr>
                            <th><div><?php echo get_phrase('name');?></div></th>
                            <th><div><?php echo get_phrase('access');?></div></th>
                            <th><div><?php echo get_phrase('options');?></div></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                               foreach($categories as $row):?>
                        <tr>

                            <td><?php echo $row['name'];?></td>
                            <td><?php echo $this->c_->convert_permission($row['access']); ?>         </td>
                            <td>
                                
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                        Action <span class="caret"></span>
                                    </button>
	                                <ul class="dropdown-menu dropdown-default pull-right" role="menu">

			                                <li>
				                                <a href="#" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_staff_categories/<?php echo $row['category_id'];?>');">
					                                <i class="entypo-pencil"></i>
					                                <?php echo get_phrase('edit');?>
				                                </a>
			                                </li>
			                                <li class="divider"></li>

		                                <!-- teacher DELETION LINK -->
			                                <li>
				                                <a href="#" onclick="confirm_modal('<?php echo base_url();?>index.php?admin/staffs/delete/<?php echo $row['category_id'];?>');">
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


	            <!----TABLE LISTING STARTS NON-ACADEMIC-->
	            <div class="tab-pane box" id="nonacademic">
               <table class="table table-bordered datatable" id="table_export">
                    <thead>
                        <tr>
                            <th width="80"><div><?php echo get_phrase('photo');?></div></th>
                            <th><div><?php echo get_phrase('name');?></div></th>
                            <th><div><?php echo get_phrase('email');?></div></th>
                            <th><div><?php echo get_phrase('profession');?></div></th>
                            <th><div><?php echo get_phrase('options');?></div></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                                $teachers	=	$this->c_->get_where('teacher',"is_academic", 0)->result_array();
                                foreach($teachers as $row):?>
                        <tr>
                            <td><img src="<?php echo $this->crud_model->get_image_url('teacher',$row['teacher_id']);?>" class="img-circle" width="30" /></td>
                            <td><?php echo $this->c_->get_full_name($row);?></td>
                            <td><?php echo $row['email'];?></td>
                            <td><?php echo $this->c_->teacher_profession($row['profession']);?></td>
                            <td>

                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">

                                        <!-- teacher EDITING LINK -->
                                        <?php if($s_->hAccess('view_teachers')): ?>
	                                        <li>
		                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_teacher_add/<?php echo $row['teacher_id'];?>/show');">
			                                        <i class="entypo-pencil"></i>
			                                        <?php echo get_phrase('view');?>
		                                        </a>
	                                        </li>
	                                        <li class="divider"></li>
		                                <?php endif; ?>


	                                    <?php if($s_->hAccess('update_teacher')): ?>
	                                        <li>
		                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_teacher_add/<?php echo $row['teacher_id'];?>');">
			                                        <i class="entypo-pencil"></i>
			                                        <?php echo get_phrase('edit');?>
		                                        </a>
	                                        </li>

		                                <?php endif; ?>



                                        <!-- teacher DELETION LINK -->
                                        <?php if($s_->hAccess('delete_teacher')): ?>
	                                        <li class="divider"></li>
	                                        <li>
		                                        <a href="#" onclick="confirm_modal('<?php echo base_url();?>index.php?admin/teacher/delete/<?php echo $row['teacher_id'];?>');">
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
						"mColumns": [1,2]
					},
					{
						"sExtends": "pdf",
						"mColumns": [1,2]
					},
					{
						"sExtends": "print",
						"fnSetText"	   : "Press 'esc' to return",
						"fnClick": function (nButton, oConfig) {
							datatable.fnSetColumnVis(0, false);
							datatable.fnSetColumnVis(3, false);
							
							this.fnPrint( true, oConfig );
							
							window.print();
							
							$(window).keyup(function(e) {
								  if (e.which == 27) {
									  datatable.fnSetColumnVis(0, true);
									  datatable.fnSetColumnVis(3, true);
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

