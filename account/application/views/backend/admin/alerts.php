<hr /> Please use the following options for user predefine text:<br>
<small>
    <?php
        $x = array();
        foreach($fields as $key => $value){
            $x[] = "[$value]";
        }
    print implode(", ",$x);
    ?>
</small>
<hr />

    <div class="row">
    <?php echo form_open(base_url() . 'index.php?admin/alerts/do_update' ,
      array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
        <div class="col-md-12 ">
            
            <div class="panel panel-primary" >
            
                <div class="panel-heading">
                    <div class="panel-title">
                        <?php echo get_phrase('settings');?>
                    </div>
                </div>

                <div class="panel-body ">
                  <div class="form-group">
                      <label  class="col-sm-3 control-label"><?php echo 'SMS API';?></label>
                      <div class="col-sm-5">
                          <input type="text" class="form-control" name="sms_api"
                              value="<?=$this->c_->get_setting('sms_api'); ?>">
                      </div>
                  </div>

                  <div class="form-group">
                      <label  class="col-sm-3 control-label"><?php echo 'SMS Sender ID';?></label>
                      <div class="col-sm-5">
                          <input type="text" class="form-control" name="sms_senderid"
                              value="<?=$this->c_->get_setting('sms_senderid'); ?>">
                      </div>
                  </div>

                    <div class="form-group">
                      <label  class="col-sm-3 control-label"><?php echo 'Email Sender ID';?></label>
                      <div class="col-sm-5">
                          <input type="text" class="form-control" name="email_senderid"
                              value="<?=$this->c_->get_setting('email_senderid'); ?>">
                      </div>
                  </div>
<hr>
                  <div class="form-group">
                      <label  class="col-sm-3 control-label"><?php echo get_phrase('send sms on booking');?></label>
                      <div class="col-sm-5">
                          <input type="checkbox" name="booking_send_sms"
                              value="1" <?php $x =  $this->c_->get_setting('booking_send_sms'); echo $x == 1?"checked=checked":""; ?>> Send SMS on member booking
                      </div>
                  </div>

                  <div class="form-group">
                      <label  class="col-sm-3 control-label"><?php echo 'SMS';?></label>
                      <div class="col-sm-5">
                          <textarea name="booking_sms" style="height: 100px;" rows="4" class="form-control"><?php echo $this->c_->get_setting('booking_sms');?></textarea>                      </div>
                  </div>

                    <hr>
                    <div class="form-group">
                        <label  class="col-sm-3 control-label"><?php echo get_phrase('send email on booking');?></label>
                        <div class="col-sm-5">
                            <input type="checkbox"  name="booking_send_email"
                                   value="1" <?php $x =  $this->c_->get_setting('booking_send_email'); echo $x == 1?"checked=checked":""; ?>> Send Email on member booking
                        </div>
                    </div>

                    <div class="form-group">
                        <label  class="col-sm-3 control-label"><?php echo 'EMAIL';?></label>
                        <div class="col-sm-9">
                            <textarea name="booking_email" class="ckeditor"><?php echo $this->c_->get_setting('booking_email');?></textarea>                      </div>
                    </div>

                    <hr>


<!--REMINDER-->

                    <h3>APPOINTMENT REMINDER</h3>


                  <div class="form-group">
                      <label  class="col-sm-3 control-label"><b>SMS <?php echo get_phrase('appointment reminder');?></b></label>
                      <div class="col-sm-5">
                          <input type="checkbox" name="booking_reminder_send_sms"
                              value="1" <?php $x =  $this->c_->get_setting('booking_reminder_send_sms'); echo $x == 1?"checked=checked":""; ?>> Send SMS reminder <br><input type="text" name="booking_reminder_sms_hours" value="<?=get_setting('booking_reminder_sms_hours',24) ?>" /> hours left before sending reminder
                      </div>
                  </div>

                  <div class="form-group">
                      <label  class="col-sm-3 control-label"><?php echo 'SMS';?></label>
                      <div class="col-sm-5">
                          <textarea name="booking_reminder_sms" rows="4" class="form-control"><?php echo $this->c_->get_setting('booking_reminder_sms');?></textarea>                      </div>
                  </div>

                    <hr>
                    <div class="form-group">
                        <label  class="col-sm-3 control-label"><b><?php echo get_phrase('email appointment reminder');?></b></label>
                        <div class="col-sm-5">
                            <input type="checkbox"  name="booking_reminder_send_email"
                                   value="1" <?php $x =  $this->c_->get_setting('booking_reminder_send_email'); echo $x == 1?"checked=checked":""; ?>> Send Email reminder
                            <br><input type="text" name="booking_reminder_hours_email" value="<?=get_setting('booking_reminder_hours_email',24) ?>" /> hours left before sending reminder
                        </div>
                    </div>

                    <div class="form-group">
                        <label  class="col-sm-3 control-label"><?php echo 'EMAIL';?></label>
                        <div class="col-sm-9">
                            <textarea name="booking_reminder_email" class="ckeditor"><?php echo $this->c_->get_setting('booking_email');?></textarea>
                        </div>
                    </div>

                    <hr>
                    <h3>PAYMENT RECEIPT</h3>
                    <small> <b>[payment]</b> for list of payments</small><br>
                    <small> <b>[total]</b> for total</small>
                    <div class="form-group">
                        <label  class="col-sm-3 control-label">SMS <?php echo get_phrase('receipt');?></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="sms_receipt" ><?php echo $this->c_->get_setting('sms_receipt');?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label  class="col-sm-3 control-label"><?php echo 'Email Receipt';?></label>
                        <div class="col-sm-9">
                            <textarea name="email_receipt" class="ckeditor"><?php echo $this->c_->get_setting('email_receipt');?></textarea>
                        </div>
                    </div>

                    <hr>
<input type="submit" name="submit" value="SAVE" class="btn btn-danger col-md-9 col-md-offset-3"/>

                    <?php echo form_close();?>

                </div>

            </div>



      <?php
        $skin = $this->c_->get_setting('skin_colour');
      ?>
    

    </div>

<script type="text/javascript">

</script>