
            <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/service_add/<?=$param1;?>/create/');"
                class="btn btn-primary pull-right">
                <i class="entypo-plus-circled"></i>
                <?php echo get_phrase('add_new product/service');?>
                </a> 
                <br><br>

            <form action="?admin/services/select" method="post">
            <table>
                <tr>
                    <td><select name="specialization" class="form-control">
                            <option <?=$param1==0?"selected":"";?> value="0" >All Products</option>
                        <?php foreach($spec as $k => $v):?>
                            <option <?=$k==$param1?"selected":"";?> value="<?=$k;?>" ><?=$v;?></option>
                            <?php endforeach;?>
                        </select>
                    </td>
                    <td>&nbsp;&nbsp;&nbsp;<input type="submit" value="View" class="btn btn-danger"/></td>
                    <td></td>
                </tr>

            </table>
            </form>


            <br><br>
            <?php if($param1 != 0){?>
                <h3><?php echo $spec[$param1];?> Product/Services</h3>
            <?php };?>
               <table class="table table-bordered datatable table-striped" id="table_export">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><div><?php echo get_phrase('name');?></div></th>
                            <?php if($param1 == 0){ ?>
                            <th><div><?php echo get_phrase('specialization');?></div></th>
                            <?php }?>

                            <th><div><?php echo 'Amount';?></div></th>
                            <th><div><?php echo get_phrase('options');?></div></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                   $count = 1;
                            foreach($services as $row):?>
                        <tr>
                            <td><?php echo $count++;?></td>


                            <td><?php echo $row['name'];?></td>

                            <?php if($param1 == 0){?>
                                <td><?php echo $spec[$row['specialization']];?></td>
                            <?php };?>

                            <td>N <span class="format_number"><?php echo $row['amount'];?></span></td>
                            <td width="150px" >
                                
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                        

                                            <li>
                                                <a href="#" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/service_add/<?=$param1;?>/update/<?php echo $row['id'];?>/');">
                                                    <i class="entypo-pencil"></i>
                                                    <?php echo get_phrase('edit');?>
                                                </a>
                                            </li>



                                        <li class="divider"></li>
                                        
                                        <!-- teacher DELETION LINK -->
                                        <li>
                                            <a href="#" onclick="confirm_modal('<?php echo base_url();?>index.php?admin/services/delete/<?php echo $row['id'];?>/<?=$param1;?>');">
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




