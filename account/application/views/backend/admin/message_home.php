<div class="mail-header">
    <!-- title -->
    <h3 class="mail-title">
        <?php echo get_phrase('messages'); ?>
    </h3>

    <!-- search -->
    <form method="get" role="form" class="mail-search">
        <div class="input-group">
            <input type="text" class="form-control" name="s" placeholder="Search for mail..." />

            <div class="input-group-addon">
                <i class="entypo-search"></i>
            </div>
        </div>
    </form>
</div>

<div style="width:100%; text-align:center;padding:100px;color:#aaa;">

    <img src="<?php echo base_url(); ?>assets/images/inbox.png" width="70">
    <br><br>
    <div>
        Select a message to read
    </div>
</div>