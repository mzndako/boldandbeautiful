<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*	
 *	@author : Joyonto Roy
 *	date	: 4 August, 2014
 *	FPS School  Management System
 *	http://codecanyon.net/user/FreePhpSoftwares
 */

class Superadmin extends CI_Controller
{
	public $c_;

	function __construct()
	{
		parent::__construct();
		$GLOBALS['SCHOOL_ID'] = 0;
		define("DIVISION_ID", 0);

		$this->load->database();
		$this->load->library('session');
//		$this->session->set_userdata("mz",array(333));
//		session_start();
//		print_r($_SESSION);
//		print session_id();
//
		/*cache control*/
		$this->c_ = $this->crud_model;
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	}

	/***default functin, redirects to login page if no teacher logged in yet***/
	public function index()
	{
		if ($this->session->userdata('superadmin') == 1)
			redirect(base_url() . '?superadmin/dashboard', 'refresh');

		$this->load->view('backend/superadminlogin');
	}

	/***TEACHER DASHBOARD***/
	function dashboard()
	{

		if ($this->session->userdata('superadmin') != 1)
			redirect(base_url() . "?superadmin", 'refresh');

		$page_data['page_name'] = 'dashboard';
		$page_data['page_title'] = get_phrase('dashboard');
		$this->load->view('backend/superadmin', $page_data);
	}


	/*ENTRY OF A NEW STUDENT*/

	function ajax_login()
	{
		$response = array();

		//Recieving post input of email, password from ajax request
		$email = $_POST["email"];
		$password = $_POST["password"];
		$response['submitted_data'] = $_POST;

		//Validating login
		$login_status = $this->validate_login($email, $password);
		$response['login_status'] = $login_status;
		if ($login_status == 'success') {
			$response['redirect_url'] = '';
		}

		//Replying ajax request with validation response
		echo json_encode($response);
	}

	//Validating login from ajax request
	function validate_login($email = '', $password = '')
	{
		$credential = array('email' => $email, 'password' => $password);


		// Checking login credential for admin
		$query = $this->db->get_where('superadmin', $credential);
		if ($query->num_rows() > 0) {
			$row = $query->row();
			$this->session->set_userdata('superadmin_id', $row->superadmin_id);
			$this->session->set_userdata('superadmin', 1);
			$this->session->set_userdata('name', $row->name);
			$this->session->set_userdata('access', $row->access);
			session_commit();
			return 'success';
		}

		return 'invalid';
	}


	/*****BACKUP / RESTORE / DELETE DATA PAGE**********/
	function backup_restore($operation = '', $type = '')
	{
		if ($this->session->userdata('superadmin') != 1)
			redirect(base_url(), 'refresh');

		if ($operation == 'create') {
			$this->crud_model->create_backup($type);
		}
		if ($operation == 'restore') {
			$this->crud_model->restore_backup();
			$this->session->set_flashdata('backup_message', 'Backup Restored');
			redirect(base_url() . 'index.php?superadmin/backup_restore/', 'refresh');
		}
		if ($operation == 'delete') {
			$this->crud_model->truncate($type);
			$this->session->set_flashdata('backup_message', 'Data removed');
			redirect(base_url() . 'index.php?superadmin/backup_restore/', 'refresh');
		}

		$page_data['page_info'] = 'Create backup / restore from backup';
		$page_data['page_name'] = 'backup_restore';
		$page_data['page_title'] = get_phrase('manage_backup_restore');
		$this->load->view('backend/index', $page_data);
	}

	/******MANAGE OWN PROFILE AND CHANGE PASSWORD***/
	function manage_profile($param1 = '', $param2 = '', $param3 = '')
	{
		if ($this->session->userdata('superadmin') != 1)
			redirect(base_url() . '?superadmin', 'refresh');

		if ($param1 == 'update_profile_info') {
			$data['name'] = $this->input->post('name');
			$data['email'] = $this->input->post('email');

			$this->db->where('superadmin_id', $this->session->userdata('superadmin_id'));
			$this->db->update('superadmin', $data);

			$this->session->set_flashdata('flash_message', get_phrase('account_updated'));
			redirect(base_url() . '?superadmin/manage_profile/', 'refresh');
		}
		if ($param1 == 'change_password') {
			$data['password'] = $this->input->post('password');
			$data['new_password'] = $this->input->post('new_password');
			$data['confirm_new_password'] = $this->input->post('confirm_new_password');

			$current_password = $this->db->get_where('superadmin', array(
				'superadmin_id' => $this->session->userdata('superadmin_id')
			))->row()->password;
			if ($current_password == $data['password'] && $data['new_password'] == $data['confirm_new_password']) {
				$this->db->where('superadmin_id', $this->session->userdata('superadmin_id'));
				$this->db->update('superadmin', array(
					'password' => $data['new_password']
				));
				$this->session->set_flashdata('flash_message', get_phrase('password_updated'));
			} else {
				$this->session->set_flashdata('flash_message', get_phrase('password_mismatch'));
			}
			redirect(base_url() . '?superadmin/manage_profile/', 'refresh');
		}
		$page_data['page_name'] = 'manage_profile';
		$page_data['page_title'] = get_phrase('manage_profile');
		$page_data['edit_data'] = $this->db->get_where('superadmin', array(
			'superadmin_id' => $this->session->userdata('superadmin_id')
		))->result_array();
		$this->load->view('backend/superadmin', $page_data);
	}

	function schools($param1 = '', $param2 = '')
	{
		if ($this->session->userdata('superadmin') != 1)
			redirect(base_url() . "?superadmin", 'refresh');

		if ($param1 == "create") {
			$data['name'] = $this->input->post("name");
			$data['domain_name'] = $this->input->post("domain");
			$data['access'] = @implode(",", $this->input->post("permissions"));
			$this->db->insert("schools", $data);
			$this->session->set_flashdata('flash_message', get_phrase('school_added_successfully'));
			redirect(base_url() . "?superadmin/schools", "refresh");
		}

		if ($param1 == "update") {
			$data['name'] = $this->input->post("name");
			$data['domain_name'] = $this->input->post("domain");
			$x = @implode(",", $this->input->post("permissions"));
			$data['access'] = $x == null?"":$x;
			$this->db->where("school_id",$param2);
			$this->db->update("schools", $data);
			$this->session->set_flashdata('flash_message', get_phrase('update_successfully'));
			redirect(base_url() . "?superadmin/schools", "refresh");
		}

		if ($param1 == 'delete') {
			$this->db->where('school_id', $param2);
			$this->db->delete('schools');
			$this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
			redirect(base_url() . '?superadmin/schools', 'refresh');
		}


		$page_data['page_name'] = 'schools';
		$page_data['schools'] = $this->db->get("schools")->result_array();
		$page_data['page_title'] = get_phrase('schools');

		$this->load->view('backend/superadmin', $page_data);
	}

	function login_as($as = "", $domain = "", $school_id = "")
	{
		if ($this->session->userdata('superadmin') != 1)
			redirect(base_url() . "?superadmin", 'refresh');

		$GLOBAL['SCHOOL_ID'] = $school_id;

		if ($as == "superadmin") {
			$this->session->set_userdata('login_user_id', 0);
			$this->session->set_userdata('name', "Super Admin");
			$this->session->set_userdata('login_type', 'admin');
			$this->session->set_userdata('login_as', 'super_admin');
			$this->session->set_userdata('division_id', -1);
			$this->session->set_userdata('domain', $domain);
			$this->session->set_userdata('access', implode(",", $this->c_->all_access()));
		} else {
			$admin = $this->db->get_where("admin", array("school_id" => $school_id, "email" => $as));
			if ($admin->num_rows() != 1)
				exit("Error logging in as $as");

			$row = $admin->row();
			$this->session->set_userdata('login_user_id', $row->admin_id);
			$this->session->set_userdata('name', $row->name);
			$this->session->set_userdata('login_type', 'admin');
			$this->session->set_userdata('login_as', 'admin');
			$this->session->set_userdata('division_id', $row->division_id);
			$this->session->set_userdata('access', $row->access);
		}

		$this->session->set_flashdata('flash_message', "Login as $as successful");
		redirect(base_url() . '?admin/dashboard', 'refresh');
	}

}