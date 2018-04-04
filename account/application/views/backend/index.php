<?php
$s_ = $this->session;
$d_ = $this->db;
$c_ = $this->crud_model;
	$system_name        =	$this->c_->get_setting('system_name');
	$system_title       =	$this->c_->get_setting('system_title');
	$text_align         =	$this->c_->get_setting('text_align');
	$division         	=	(int) $this->c_->get_setting('division_id');
	$account_type       =	$this->session->userdata('login_type');
	$skin_colour        =   $this->c_->get_setting('skin_color');
	$active_sms_service =   $this->c_->get_setting('active_sms_service');
	$login_as = $this->session->userdata("login_as");
	$login_id = $this->session->userdata("login_user_id");

	?>
<!DOCTYPE html>
<html lang="en" dir="<?php if ($text_align == 'right-to-left') echo 'rtl';?>">
<head>
	
	<title><?php echo $page_title;?> | <?php echo $system_title;?></title>
    
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="Quicktel Solution" />
	<meta name="author" content="mzndako" />
	
	

	<?php include 'includes_top.php';?>
	
</head>
<body class="<?php if ($skin_colour != '') echo 'skin-' . $skin_colour;?>" >
<!--	<div class="page-container --><?php //if ($text_align == 'right-to-left') echo 'right-sidebar';?><!--" >-->
	<div class="wrapper" >
		<?php include 'header.php';?>

		<?php include $account_type.'/navigation.php';?>

		<div class="content-wrapper" style="min-height: 620px; background: white; padding-left: 10px;">

			<section class="content-header">
				<h1>
					<i class="entypo-right-circled"></i>
					<?php echo $page_title;?>

				</h1>
<!--				<ol class="breadcrumb">-->
<!--					<li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>-->
<!--					<li class="active">Here</li>-->
<!--				</ol>-->
			</section>


			<?php include $account_type.'/'.$page_name.'.php';?>

			<?php include 'footer.php';?>
			<?php include 'control_sidebar.php';?>
			<div id="dtBox"></div>

		</div>
		<?php //include 'chat.php';?>

	</div>
    <?php include 'modal.php';?>
    <?php include 'includes_bottom.php';?>

</body>
</html>