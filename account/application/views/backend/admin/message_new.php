<div class="mail-header" style="padding-bottom: 27px ;">
    <!-- title -->
    <h3 class="mail-title">
        <?php echo get_phrase('write_new_message'); ?>
    </h3>
</div>

<div class=" form-group mail-compose">

    <?php echo form_open(base_url() . 'index.php?admin/message/send_new/', array('class' => 'form-horizontal validate', 'enctype' => 'multipart/form-data',"onsubmit"=>"return confirm('Send messages now?')")); ?>


    <div class="form-group">
        <label for="subject"><?php echo get_phrase('recipient'); ?>:</label>
        <br><br>
        <select  style="padding-left: 4px;" class="form-control" name="reciever[]" multiple="true" ><optgroup label="<?php echo get_phrase('Members'); ?>"><?php
                d()->where("is_admin",0);
                $students = c()->get('users')->result_array();
                foreach ($students as $row):
                    ?><option value="<?php echo $row['id']; ?>"><?php echo c()->get_full_name($row); ?></option><?php endforeach;
                ?></optgroup><optgroup label="<?php echo get_phrase('staffs'); ?>"><?php
                d()->where("is_admin",1);
                $teachers = c()->get('users')->result_array();
                foreach ($teachers as $row):

                ?><option value="<?php echo $row['id']; ?>"><?php echo c()->get_full_name($row); ?></option><?php endforeach; ?></optgroup></select>
    </div>

    <div class="form-group">
        Additional Recipient:
        <div class="col-sm-12">

            <input type="text" style="border: 1px solid #ccc; padding-left: 4px;" name="more" class="form-control"  placeholder="Type into additional email or phone number to send message to" ?>
        </div>
        </div>

    <div class="compose-message-editor">
        Message:<br>
        <textarea row="2" class="form-control wysihtml5 ckeditor" data-stylesheet-url="assets/css/wysihtml5-color.css"
            name="message" placeholder="<?php echo get_phrase('write_your_message'); ?>"
            id="sample_wysiwyg"></textarea>
    </div>

    <hr>
<div class="form-group">
    <div class="col-sm-offset-3 col-sm-5">
        <input type="checkbox" name="send_sms" onclick="changeMe(this,'sms_subject')" value="1" /> Send SMS
        <input type="text" style="border: 1px solid #ccc; display: none; padding-left: 4px;" name="sms_subject" id="sms_subject" maxlength="11" class="form-control" data-validate="required" data-message-required="Enter sender ID" placeholder="Sender ID: maximum 11 characters"  ?>
    </div>

</div>
    <div class="form-group">
    <div class="col-sm-offset-3 col-sm-5">
        <input type="checkbox" name="send_email" onclick="changeMe(this,'email_subject')" value="1" /> Send Email
        <input type="text" style="border: 1px solid #ccc; display: none; padding-left: 4px;" name="email_subject" id="email_subject" class="form-control" data-validate="required" data-message-required="Enter the Email Subject" placeholder="Enter Email Subject" ?>
    </div>

</div>
<hr>
    <button type="submit" class="btn btn-success btn-icon pull-right col-sm-5">
        <?php echo get_phrase('send'); ?>
        <i class="entypo-mail"></i>

    </button>
</form>

</div>

<script type="text/javascript">
    function changeMe(me,id){
        if(!me.checked){
            $("#"+id).hide(100);
        }else{
            $("#"+id).show(100);
        }
    }
</script>