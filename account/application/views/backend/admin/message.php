<hr />
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
            <a href="<?php echo base_url(); ?>index.php?admin/message/message_new" class="btn btn-success btn-icon btn-block">
                <?php echo get_phrase('new_message'); ?>
                <i class="entypo-pencil"></i>
            </a>
        </div>

        <!-- message user inbox list -->
        <ul class="mail-menu">

            <?php
            $current_user = $this->session->userdata('login_user_id');
            d()->where('sender', $current_user);
            d()->or_where('reciever', $current_user);
            $message_threads = c()->get('message_thread')->result_array();
            foreach ($message_threads as $row):

                // defining the user to show
                if ($row['sender'] == $current_user)
                    $user_to_show = explode('-', $row['reciever']);
                if ($row['reciever'] == $current_user)
                    $user_to_show = explode('-', $row['sender']);

                $user_to_show_type = "users";
                $user_to_show_id = $user_to_show[0];
                $unread_message_number = $this->crud_model->count_unread_message_of_thread($row['message_thread_code']);
                ?>
                <li class="<?php if (isset($current_message_thread_code) && $current_message_thread_code == $row['message_thread_code']) echo 'active'; ?>">
                    <a href="<?php echo base_url(); ?>index.php?admin/message/message_read/<?php echo $row['message_thread_code']; ?>" style="padding:12px;">
                        <i class="entypo-dot"></i>

                        <?php echo c()->get_full_name(c()->get_where($user_to_show_type, array('id' => $user_to_show_id))->row()); ?>

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