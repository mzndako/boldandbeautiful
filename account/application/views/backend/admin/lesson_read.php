<div class="panel-group" id="accordion">
	<?php
	$count = 1;
	d()->order_by("message_id","DESC");
	$messages = c()->get_where('lesson', array('message_thread_code' => $current_message_thread_code))->result_array();
	foreach ($messages as $row):
		$sender = explode('-', $row['sender']);
		$sender_account_type = $sender[0];
		$sender_id = $sender[1];

		if($count == 1){
?>
	<h2><img src="<?php echo $this->crud_model->get_image_url($sender_account_type, $sender_id); ?>"
	         class="img-circle" width="30">
		<span><?php echo c()->get_full_name(c()->get_where($sender_account_type, array($sender_account_type . '_id' => $sender_id))->row()); ?></span></h2>



		<?php } ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$count?>">
			<span><?=$row['title'];?></span>
			<div style="float: right; display: inline-block;" class="mya">
				<?=date("d M, Y", $row['timestamp']); ?>

				<?php if($row['locked'] == 0){
					?>
				<a class="btn btn-warning" href="?admin/lesson/lesson_new/<?=$current_term."/".$row['message_id'];?>">Edit</a>
				<?php } ?>

				<?php if($isadmin) {
					if ($row['locked'] == 0) {
						?>
						<a class="btn btn-warning" href="?admin/lesson/lesson_read/<?=$current_term."/$current_message_thread_code/lock/".$row['message_id']; ?>">Lock</a>
					<?php } else { ?>
						<a class="btn btn-danger" href="?admin/lesson/lesson_read/<?=$current_term."/$current_message_thread_code/unlock/".$row['message_id']; ?>">Un-Lock</a>

					<?php }
				}?>

			</div>

				</a>
			</h4>

		</div>


		<div id="collapse<?=$count?>" class="panel-collapse collapse <?php if($count == 1) echo 'in'?>">
			<div class="panel-body">
				<p> <?php echo $row['message']; ?></p>
			</div>
		</div>

	</div>

	<?php if($count == 1){?>
<!--		</div>-->





<?php } $count++; endforeach; ?>
</div>

<?php echo form_open(base_url() . 'index.php?admin/lesson/send_reply/' . $current_message_thread_code, array('enctype' => 'multipart/form-data')); ?>
<div class="mail-reply">
	<!--    <div class="compose-message-editor">-->
	<!--        <textarea row="5" class="form-control ckeditor" data-stylesheet-url="assets/css/wysihtml5-color.css" name="message"-->
	<!--                  placeholder="-->
	<?php //echo get_phrase('reply_message'); ?><!--" id="sample_wysiwyg"></textarea>-->
	<!--    </div>-->
	<br>
	<!--    <button type="submit" class="btn btn-success btn-icon pull-right">-->
	<!--        --><?php //echo get_phrase('send'); ?>
	<!--        <i class="entypo-mail"></i>-->
	<!--    </button>-->
	<br><br>
</div>
</form>