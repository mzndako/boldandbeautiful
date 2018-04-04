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



include $page_name.'.php';