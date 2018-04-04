<?php

	$onlyshow = false;
$update = -1;
$options = array("required_all"=>false,"except"=>"password,email,phone");
if(isset($posted_student)){
	$options['members'] = $posted_student;
	$options['members']['password'] = "";
	$student = $posted_student;
}

$form = $this->c_->get_members_form($options);
$type = 'users';
if($type == 'users') {
	$myform['User Information'] = "surname,fname,mname,birthday,sex,phone,email,password,nationality,state,lga,residential_address";
}
?>
	<h2><?php
	if($update > -1){
		echo ucwords(@$student['surname'].", ".@$student['fname']." ". @$student['mname']);
	}else
		echo "Fill the form below";
?></h2>
	<ul class="nav nav-tabs bordered">
		<?php
		$active = "class='active'";
		foreach($myform as $header => $footer):
			$tag = str_replace(" ","",$header);
			$tag = str_replace("/","",$tag);
		?>
		<li <?php echo $active;?>><a data-toggle="tab" href="#<?php echo $tag;?>"><?php echo $header;?></a></li>
		<?php
			$active = "";
		endforeach;?>

	</ul>
<?php
$link = "create";
if($update > -1){
	$link = "update/$update";
}

echo form_open(base_url() . '?users/register_user/create', array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data', 'id'=>'myform'));
?>
	<div class="tab-content">
		<div class="row">
			<div class="col-sm-offset-3" style="color: red; font-weight: bold;">
				<?=getIndex($posted_student,'error');?>
			</div>
		</div>
		<?php


		$active = "in active";
			foreach($myform as $header => $footer):
				$tag = str_replace(" ","",$header);
				$tag = str_replace("/","",$tag);
		?>
		<div id="<?php echo $tag;?>" class="tab-pane fade <?php echo $active;?>">
			<?php
				if(is_array($footer)){
					echo '<ul class="nav nav-tabs bordered">';
					$act = "class='active'";
					foreach($footer as $h => $f){
						$tag = str_replace(" ","",$h);
						$tag = str_replace("/","",$tag);
						echo "
						<li $act><a data-toggle='tab' href='#$tag'>$h</a></li>
						";
						$act = '';
					}
					echo '</ul>';
				}else{
					echo '<h3>'.$header.'</h3>';
				}
			?>

				<div class="tab-content row">
			<?php
			$loopf = array();
			if(is_array($footer)){
				$loopf = $footer;
			}else{
				$loopf["test"] = $footer;
			}
			$active2 = "in active";
			foreach($loopf as $h => $footer) {
				$tag = str_replace(" ","",$h);
				$tag = str_replace("/","",$tag);
				echo "<div id='$tag' class='tab-pane fade $active2'><div class='col-md-8'>";
				$footer = explode(",", $footer);
				foreach ($footer as $value) {
					if (!isset($form[$value])) {
						if ($value == "myimage") {
							$options = array("type" => "users", "id" => $update, "onlyshow" => $onlyshow);
							echo "<center>" . $this->c_->construct_image($options) . "</center>";
						}
						continue;
					}
					$col = $value;
					$array = $form[$value];
					$array['name'] = $col;
					$array['onlyshow'] = $onlyshow;
					$show = $array['type'] == "hidden" ? "style='display: none'" : "";
					?>

					<div class="form-group" <?php echo $show; ?>>
						<label class="control-label col-xs-3" for='<?php echo $col; ?>'><?php echo $array['label']; ?>:</label>

						<div class="col-xs-8">
							<?php echo $this->c_->create_input($array); ?>

						</div>
					</div>

				<?php } ?>
				<center>
					<input type="submit" value=" Register " name="register" class="btn btn-warning">
				</center>
				<?php
				$active2 = "";
				echo '</div></div>';
			}
			$active = "";

			?>

				</div>
		</div>
		<?php endforeach;

		if(!$onlyshow):
		?>

		<?php endif; ?>
	</div>
</form>
<script type="text/javascript">
	jQuery(document).ready(function($)
	{

		$(".select3").select2();
		var datatable = $("#table_export").dataTable();


	});
</script>