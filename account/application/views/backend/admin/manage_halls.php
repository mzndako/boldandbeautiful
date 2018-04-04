
            <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/hall_add/<?=$type.'/create/'.$branch_id;?>');" class="btn btn-primary pull-right">
                <i class="entypo-plus-circled"></i>
<?php echo get_phrase('add '.$type.' to the viewed branch');?>
                </a>
                <br><br>

<br><br>
<style>
    .mytable td{
        padding: 10px;
    }
</style>
            <div>
<table class="mytable " >
    <?php echo form_open(base_url() . '?admin/manage_halls/'.$type.'/'.$branch_id.'/search/' , array('class' => 'form-horizontal  validate'));?>

    <tr>

        <td >

                <h4 style="color: red">SHOWS <?=get_phrase($type);?> FOR </h4>

        </td>

        <td>
            <select class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('select a branch');?>"   name="branch_id" <?php if(!s()->hAccess('overall_admin')) echo "disabled='disabled'";?> >
                <option value="">SELECT BRANCH</option>
                <?php foreach($branch as $row){;?>
                    <option <?=$branch_id==$row['id']?'selected':'';?> value="<?=$row['id'];?>"><?=$row['name'];?></option>
                <?php } ;?>
            </select>
        </td>

        <td >

            <h4 style="color: red">BRANCH </h4>

        </td>
        <td >

            <input type="submit" <?php if(!s()->hAccess('overall_admin')) echo 'style="display:none;"';?> class="form-control btn btn-info"
                   value="View">
        </td>

    </tr>

    </form>
</table>
            </div>
<br><br>
<table class="table table-bordered datatable table-striped" id="table_export">
    <thead>
    <tr>
        <th>#</th>
        <th><?php echo get_phrase('name');?></th>
        <th><?php echo get_phrase('address');?></th>
        <th><?php echo get_phrase('capacity');?></th>
        <th><?php echo get_phrase('amount');?></th>
        <th><?php echo get_phrase('reserved '.$type);?></th>
        <th><?php echo get_phrase('options');?></th>
    </tr>
    </thead>
    <tbody>

    <?php
    $count    = 1;

    foreach ($halls as $row):
        ?>
        <tr>
            <td><?php echo $count++;?></td>
            <td><?php echo $row['name'];?></td>
            <td><?php echo $row['address'];?></td>
            <td>
                <?php echo $row['capacity'];?>
            </td>
            <td >
                <b>N</b> <span class="format_number"><?php echo $row['amount'];?></span>
            </td>
            <td>
                <?php
                d()->where('end_date >=', date("Y/m/d"));
                    $number = c()->get_where("booked_halls","hall_id",$row['id'])->num_rows();

                ?>

                    <label class="label label-success"><?=$number;?> Reserved <?=get_phrase($type);?></label>
                    <a class="btn btn-warning"   href='<?='?admin/view_booked_halls/'.$type.'/'.$row['id'];?>'>View</a>

            </td>
            <td>
                <div class="input-group-btn">
                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                        Action <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">


                        <li>
                            <a href="#" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/hall_add/<?=$type;?>/update/<?php echo $row['id'];?>');">
                                <i class="entypo-pencil"></i>
                                <?php echo get_phrase('edit');?>
                            </a>
                        </li>



                        <li class="divider"></li>

                        <!-- teacher DELETION LINK -->
                        <li>
                            <a href="#" onclick="confirm_modal('<?php echo base_url();?>index.php?admin/manage_halls/<?=$type.'/'.$branch_id;?>/delete/<?php echo $row['id'];?>');">
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





