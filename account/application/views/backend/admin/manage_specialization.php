
            <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/specialization_add/create/');"
                class="btn btn-primary pull-right">
                <i class="entypo-plus-circled"></i>
                <?php echo get_phrase('add_new specialization');?>
                </a> 
                <br><br>
               <table class="table table-bordered datatable table-striped" id="table_export">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><div><?php echo get_phrase('specialization_name');?></div></th>
                            <th><div><?php echo 'Sub Categories';?></div></th>
                            <th><div><?php echo get_phrase('options');?></div></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                   $count = 1;
                            foreach($spec as $row):?>
                        <tr>
                            <td width="50px"><?php echo $count++;?></td>
                            <td width="200px"><?php echo $row['name'].$row['id']; ?></td>
                            <td><?php $ser = d()->get_where("services",array("specialization"=>$row['id'],"deleted"=>0))->result();
                                $data = array();
                                foreach($ser as $x)
                                    $data[] = $x->name;
                                echo implode(", ",$data);
                                ;?></td>
                            <td width="150px" >
                                
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                        

                                            <li>
                                                <a href="#" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/specialization_add/update/<?php echo $row['id'];?>');">
                                                    <i class="entypo-pencil"></i>
                                                    <?php echo get_phrase('edit');?>
                                                </a>
                                            </li>

                                        <li>
                                            <a href='<?php echo base_url();?>?admin/services/<?php echo $row['id'];?>' >
                                                <i class="entypo-share"></i>
                                                <?php echo get_phrase('manage product & services');?>
                                            </a>
                                        </li>


                                        <li class="divider"></li>
                                        
                                        <!-- teacher DELETION LINK -->
                                        <li>
                                            <a href="#" onclick="confirm_modal('<?php echo base_url();?>index.php?admin/manage_specialization/delete/<?php echo $row['id'];?>','All appointments and products and services on this branch will be deleted?');">
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




