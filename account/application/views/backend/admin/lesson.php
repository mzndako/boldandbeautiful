<hr />
<?php if($isadmin): ?>
<div>
    <?php
    echo form_open(base_url() . '?admin/lesson/'.$message_inner_page_name."/change" , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top',"method"=>"post"));
    ?>
    <table border="0" cellspacing="0" cellpadding="0" class="table table-bordered">
        <tr>
            <td>
                <select onchange="list_terms()" id="session" name="session_id" class="form-control"
                        data-validate="required"
                        data-message-required="<?php echo get_phrase('value_required'); ?>">
                    <option value=""><?php echo get_phrase('select'); ?></option>
                    <?php

                    $sessions = $this->c_->get('year')->result_array();
                    foreach ($sessions as $row2):
                        ?>
                        <option <?php echo $session_id == $row2['year_id'] ? "selected" : ""; ?>
                            value="<?php echo $row2['year_id']; ?>">
                            <?php echo $row2['name'];; ?>
                        </option>
                        <?php
                    endforeach;
                    ?>
                </select>
            </td>
            <td>
                <select id="term" name="term_id" class="form-control"
                        data-validate="required"
                        data-message-required="<?php echo get_phrase('value_required'); ?>">

                </select>
            </td>



            <td>
                <input type="hidden" name="operation" value="selection"/>
                <input type="submit" value="<?php echo get_phrase('select'); ?>"
                       class="btn btn-info"/>
            </td>
        </tr>
    </table>
    </form>
</div>
<?php endif; ?>
<div class="mail-env">

    <!-- Mail Body -->
    <div class="mail-body">

        <!-- message page body -->
        <?php include $message_inner_page_name . '.php'; ?>
    </div>

    <!-- Sidebar -->
    <div class="mail-sidebar" style="min-height: 800px;">

        <!-- compose new email button -->
        <div class="mail-sidebar-row hidden-xs">
            <a href="<?php echo base_url(); ?>index.php?admin/lesson/lesson_new/<?=$current_term;?>" class="btn btn-success btn-icon btn-block">
                <?php echo get_phrase('new'); ?>
                <i class="entypo-pencil"></i>
            </a>
        </div>

        <!-- message user inbox list -->
        <ul class="mail-menu">

            <?php

            if($isadmin){
                $current_user = 'admin';
                d()->where('reciever', $current_user);
                $type = "reciever";
            }else {
                $current_user = $this->session->userdata('login_as') . '-' . $this->session->userdata('login_user_id');
                d()->where('sender', $current_user);
                $type = "sender";
            }

            d()->where('term_id', $current_term);


            $message_threads = c()->get('lesson_thread')->result_array();
            foreach ($message_threads as $row):

                // defining the user to show

                if ($row['sender'] == $current_user)
                    $user_to_show = explode('-', $row['sender']);
                else
                    $user_to_show = explode('-', $row['sender']);
//                if ($row['reciever'] == $current_user)
//                    $user_to_show = explode('-', $row['sender']);
//print $current_user;
//                print_r($user_to_show);
                $user_to_show_type = $user_to_show[0];
                $user_to_show_id = $user_to_show[1];
                $unread_message_number = $this->crud_model->count_unread_lesson_of_thread($row['message_thread_code'],$isadmin);
                ?>
                <li class="<?php if (isset($current_message_thread_code) && $current_message_thread_code == $row['message_thread_code']) echo 'active'; ?>">
                    <a href="<?php echo base_url(); ?>index.php?admin/lesson/lesson_read/<?php echo $current_term."/".$row['message_thread_code']; ?>" style="padding:12px;">
                        <i class="entypo-dot"></i>

                        <?php echo c()->get_full_name(c()->get_where($user_to_show_type, array($user_to_show_type . '_id' => $user_to_show_id))->row()); ?>

                        <span class="badge badge-default pull-right" style="color:#aaa;"><?php echo $user_to_show_type; ?></span>

                        <?php if ($unread_message_number > 0): ?>
                            <span class="badge badge-secondary pull-right">
                                <?php echo $unread_message_number; ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

    </div>

</div>
<script type="text/javascript">
    <?php  $this->c_->print_list_terms($term_id); ?>
</script>