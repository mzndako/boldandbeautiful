<aside class="main-sidebar" >

    <section class="sidebar">
        <?php if($s_->hAccess('login')): ?>
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?php echo $this->crud_model->get_image_url($login_as , $login_id);?>"  class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?php echo $this->session->userdata('name');?></p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
<?php endif;?>

    <ul  class="sidebar-menu">
       <!-- DASHBOARD -->
        <?php if($s_->hAccess('login')): ?>
       <li class="<?php if ($page_name == 'dashboard') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>?admin/dashboard">
                <i class="entypo-gauge"></i>
                <span><?php echo get_phrase('dashboard'); ?></span>
            </a>
        </li>
        <?php endif;?>


        <?php if(!hAccess("login")){?>
            <li class="<?php
            if ($page_name == 'register')
                echo 'active';
            ?> ">
                <a href="<?php echo base_url(); ?>?users/register_user">
                    <span><i class="entypo-users "></i> <?php echo get_phrase('register'); ?></span>

                </a>
            </li>
        <?php } ?>

        <li class="<?php
        if ($page_name == 'book_appointment')
            echo 'active';
        ?> ">
            <a href="<?php echo base_url(); ?>?users/book_appointment">
                <span><i class="entypo-book-open "></i> <?php echo get_phrase('book appointment'); ?></span>

            </a>
        </li>

        <?php if($s_->hAccess('login') && !is_admin()): ?>
        <!-- SUBJECT -->
            <li class="<?php if ($page_name == 'view_appointments') echo 'active'; ?> ">
                <a href="?admin/view_appointments">
                    <i class="entypo-docs"></i>
                    <span><?php echo get_phrase('view appointments'); ?></span>
                </a>
            </li>
<?php endif; ?>


<?php if($s_->hAccess('login')): ?>
        <!-- SETTINGS -->
       <?php if($s_->hAccess('manage_settings') ||
           $s_->hAccess('manage_members') ||
           $s_->hAccess('view_appointments') ||
           $s_->hAccess('sign_in') ||
           $s_->hAccess('sign_out') ||
           $s_->hAccess('manage_admin') ||
           $s_->hAccess('manage_language') ||
           $s_->hAccess('manage_products') ||
           $s_->hAccess('manage_members') ||
           $s_->hAccess('manage_alerts') ||
           $s_->hAccess('make_payment') ||
           $s_->hAccess('view_payments') ||
           $s_->hAccess('login') ||
           $s_->hAccess('manage_expenditures') ||
           $s_->hAccess('manage_promos')): ?>
           <li class="treeview <?php
           if ($page_name == 'system_settings' ||
               $page_name == 'view_members' ||
               $page_name == 'view_appointments' ||
               $page_name == 'manage_specialization' ||
               $page_name == 'services' ||
               $page_name == 'sign_in' ||
               $page_name == 'manage_admin' ||
               $page_name == 'manage_members' ||
               $page_name == 'manage_language' ||
               $page_name == 'alerts' ||
               $page_name == 'make_payment' ||
               $page_name == 'view_payments' ||
               $page_name == 'manage_promos')
               echo 'opened active';
           ?> ">
               <a href="#">
                   <i class="entypo-lifebuoy"></i>
                   <span><?php echo get_phrase('admin'); ?></span>
                   <i class="fa fa-angle-left pull-right"></i>
               </a>
               <ul class="treeview-menu">
                   <?php if($s_->hAccess('can_sign_in') || $s_->hAccess('can_sign_out')): ?>
                       <li class="<?php if ($page_name == 'sign_in') echo 'active'; ?> ">
                           <a href="<?php echo base_url(); ?>?admin/manage_sign_in">
                               <span><i class="entypo-record"></i> <?php echo get_phrase('Sign In/Out'); ?></span>
                           </a>
                       </li>
               <?php endif; ?>

                   <?php if($s_->hAccess('manage_settings')): ?>
                       <li class="<?php if ($page_name == 'system_settings') echo 'active'; ?> ">
                           <a href="<?php echo base_url(); ?>?admin/system_settings">
                               <span><i class="entypo-globe"></i> <?php echo get_phrase('general_settings'); ?></span>
                           </a>
                       </li>
               <?php endif; ?>

                   <?php if($s_->hAccess('manage_alerts')): ?>
                       <li class="<?php if ($page_name == 'alerts') echo 'active'; ?> ">
                           <a href="<?php echo base_url(); ?>?admin/alerts">
                               <span><i class="entypo-signal"></i> <?php echo get_phrase('sms & email settings'); ?></span>
                           </a>
                       </li>
               <?php endif; ?>


                   <?php if($s_->hAccess('manage_members')): ?>
                       <li class="<?php if ($page_name == 'view_members' && $type == 'users') echo 'active'; ?> ">
                           <a href="<?php echo base_url(); ?>?admin/view_members/users">
                               <span><i class="entypo-users"></i> <?php echo get_phrase('Members'); ?></span>
                           </a>
                       </li>
                   <?php endif; ?>


                   <?php if($s_->hAccess('manage_admin')): ?>
                       <li class="<?php if ($page_name == 'view_members' && $type == 'admin') echo 'active'; ?> ">
                           <a href="<?php echo base_url(); ?>?admin/view_members/admin">
                               <span><i class="entypo-user"></i> <?php echo get_phrase('Admin'); ?></span>
                           </a>
                       </li>
                   <?php endif; ?>



                   <?php if($s_->hAccess('make_payment')): ?>
                       <li class="<?php if ($page_name == 'make_payment') echo 'active'; ?> ">
                           <a href="<?php echo base_url(); ?>?admin/make_payment">
                               <span><i class="entypo-share"></i> <?php echo get_phrase('Make Payments'); ?></span>
                           </a>
                       </li>
                   <?php endif; ?>

                   <?php if($s_->hAccess('view_payments')): ?>
                       <li class="<?php if ($page_name == 'view_payments') echo 'active'; ?> ">
                           <a href="<?php echo base_url(); ?>?admin/view_payments">
                               <span><i class="entypo-book-open"></i> <?php echo get_phrase('View Payments'); ?></span>
                           </a>
                       </li>
                   <?php endif; ?>

                   <?php if($s_->hAccess('manage_promos')): ?>
                       <li class="<?php if ($page_name == 'manage_promos') echo 'active'; ?> ">
                           <a href="<?php echo base_url(); ?>?admin/manage_promos">
                               <span><i class="entypo-network"></i> <?php echo get_phrase('Manage Offers/Promos'); ?></span>
                           </a>
                       </li>
                   <?php endif; ?>

                   <?php if($s_->hAccess('view_appointments')): ?>
                       <li class="<?php if ($page_name == 'view_appointments') echo 'active'; ?> ">
                           <a href="?admin/view_appointments">
                               <i class="entypo-docs"></i>
                               <span><?php echo get_phrase('view appointments'); ?></span>
                           </a>
                       </li>
                   <?php endif; ?>





                   <?php if($s_->hAccess('manage_products')): ?>
                       <li class="<?php if ($page_name == 'manage_specialization') echo 'active'; ?> ">
                           <a href="<?php echo base_url(); ?>?admin/manage_specialization">
                               <span><i class="entypo-attach"></i> <?php echo get_phrase('manage_specialization'); ?></span>
                           </a>
                       </li>
                   <?php endif; ?>

                   <?php if($s_->hAccess('manage_products')): ?>
                       <li class="<?php if ($page_name == 'services') echo 'active'; ?> ">
                           <a href="<?php echo base_url(); ?>?admin/services">
                               <span><i class="entypo-basket"></i> <?php echo get_phrase('product & services'); ?></span>
                           </a>
                       </li>
                   <?php endif; ?>


               </ul>
           </li>
        <?php endif; ?>

        <?php if($s_->hAccess('view_sent_messages') ||
                $s_->hAccess('send_message')): ?>
        <li class="treeview
        <?php if($page_name == "sent_messages" || $page_name == "message")
            echo 'opened active';
        ?> ">
            <a href="#">
                <i class="entypo-lifebuoy"></i>
                <span><?php echo get_phrase('Messages'); ?></span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <?php if($s_->hAccess('send_message')): ?>
                    <li class="<?php if ($page_name == 'message') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>?admin/message">
                            <span><i class="entypo-popup"></i> <?php echo get_phrase('Send SMS/Emails'); ?></span>
                        </a>
                    </li>
                <?php endif;?>

                <?php if($s_->hAccess('view_sent_messages')): ?>
                    <li class="<?php if ($page_name == 'sent_messages') echo 'active'; ?> ">
                        <a href="<?php echo base_url(); ?>?admin/sent_messages">
                            <span><i class="entypo-mobile"></i> <?php echo get_phrase('Sent Messages'); ?></span>
                        </a>
                    </li>
                <?php endif;?>


<!--                --><?php //if($s_->hAccess('view_noticeboard')): ?>
<!--                    <li class="--><?php //if ($page_name == 'sign_in') echo 'active'; ?><!-- ">-->
<!--                        <a href="--><?php //echo base_url(); ?><!--?admin/sent_sms">-->
<!--                            <span><i class="entypo-mobile"></i> --><?php //echo get_phrase('Sent Messages'); ?><!--</span>-->
<!--                        </a>-->
<!--                    </li>-->
<!--                --><?php //endif;?>
             </ul>
            </li>

        <?php endif;?>



        <!-- ACCOUNT -->
        <li class="<?php if ($page_name == 'manage_profile') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>?admin/manage_profile">
                <i class="entypo-lock"></i>
                <span><?php echo get_phrase('account'); ?></span>
            </a>
        </li>
<?php endif;?>
    </ul>
    </section>
</aside>