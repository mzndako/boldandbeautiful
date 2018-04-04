<div class="container">
	<?php
	$link = "create";
	if(isset($update)){
		$link = "update/$id";
	}
	echo form_open(base_url() . 'index.php?registration/register/'.$link , array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));
	?>
	<center>
		<img src="uploads/logo2.png" width="120px"/>

		<h2>NUT ENDWELL MODEL SCIENCE SCHOOL</h2>
		<div>P.O BOX 2993, BESIDE PW COMPANY, CHANCHAGA, MINNA-NIGER STATE NIGERIA
		</div>
		<?php

			$myid = isset($update)?$id:-1;
		echo $this->c_->construct_image(array("type"=>"student" , "id"=>$myid,"onlyshow"=>isset($app_id)));
		?>
	</center>
	<?php

	if(!isset($app_id)):

			if(isset($onlyshow) && $onlyshow){
				$onlyshow = true;
			}else{
				$onlyshow = false;
			}

	?>

	<?php

	foreach($myform as $header => $footer) {
		$footer = explode(",",$footer);
		?>
	<div class="panel  col-xs-offset-2 col-xs-8 print-no-margin">
		<div class="panel-heading bg-info text-bold hidden-print" style="padding: 5px; color: black;">
			<?php echo $header;?>:
		</div>
		<div class="panel-body no-margin">
		<?php
		foreach ($footer as $value) {
			if(!isset($form[$value])){
				continue;
			}
			$col = $value;
			$array = $form[$value];
			$array['name'] = $col;
			$array['onlyshow'] = $onlyshow;
			$show = $array['type'] == "hidden"?"style='display: none'":"";
			?>

			<div class="form-group" <?php echo $show;?>>
				<label  class="control-label col-xs-2" for='<?php echo $col;?>'><?php echo $array['label']; ?>:</label>
				<div class="col-xs-8">
					<?php echo $this->c_->create_input($array);?>

				</div>
			</div>
		<?php }
		?>
		</div>
	</div>
<?php

	}?>
		<div class="col-xs-offset-2 col-xs-8 text-center">
		<?php if($onlyshow){?>
			<input type="button" onclick="print()" class="btn btn-danger " value="Print" />
		<?php }else{
			if(isset($update)){
		?>
			<input type="button" onclick="location = '<?php echo base_url() . 'index.php?registration/register/print/'.$id; ?>';" class="btn btn-danger " value="Print" />

		<?php
			}
		?>
			<input type="submit" class="btn btn-success " value="Submit" />
		<?php

		}?>
		</div>

	<?php else: ?>
	<center>
	<h2>YOUR APPLICATION HAS BEEN SUBMITTED SUCCESSFULLY</h2>
	<h3 class="text-info">YOUR APPLICATION ID = <span class="text-danger"><?PHP echo $app_id;?></span></h3>
	<br><br>
	<input type="button" onclick="location = '<?php echo base_url() . 'index.php?registration/register/print/'.$id; ?>';" class="btn btn-danger " value="Print" />
		</center>
	<?php endif; ?>
	</form>
</div>