<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*
 *	@author 	: Joyonto Roy
 *	date		: 27 september, 2014
 *	FPS School Management System Pro
 *	http://codecanyon.net/user/FreePhpSoftwares
 *	support@freephpsoftwares.com
 */

class Database extends CI_Controller
{
	public $division_id;
	public $c_;

	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model("domain_model");
		$this->load->library('session');

	}

	/***default functin, redirects to login page if no admin logged in yet***/
	public function index()
	{
		list_details();
	}


	/***ADMIN DASHBOARD***/
	function dashboard()
	{

//		$this->load->view('backend/index', $page_data);
	}


}

if(!function_exists('d')) {
	function d()
	{
		$CI =& get_instance();
		return $CI->db;
	}
}

function list_details(){
	print "<div>";


	print "</div>";
}
