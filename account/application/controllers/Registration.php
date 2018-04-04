<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*	
 *	@author : Joyonto Roy
 *	date	: 4 August, 2014
 *	FPS School  Management System
 *	http://codecanyon.net/user/FreePhpSoftwares
 */

class Registration extends CI_Controller
{
	public $c_;

	function __construct()
	{
		parent::__construct();
		$GLOBALS['SCHOOL_ID'] = 0;


		$this->load->database();
		$this->load->model("domain_model");
		$this->load->library('session');
		$this->c_ = $this->crud_model;

		$domain = null;
		if ($this->session->userdata('superadmin') == 1){
			$domain = $this->session->userdata('domain');
		}
		$this->domain_model->set_school_id($domain);

		$this->domain_model->check_redirect();

		$this->domain_model->set_division();
//		define("DIVISION_ID", -1);

		/*cache control*/
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	}

	/***default functin, redirects to login page if no teacher logged in yet***/
	public function index()
	{

//		if ($this->session->userdata('registration') == 1)
//			redirect(base_url() . '?registration/register', 'refresh');
		if($this->input->post("continue") == "Continue"){
			$app_id = $this->input->post("app_id");
			$app_id = $this->c_->app2student_id($app_id);
			$this->db->where("school_id",$GLOBALS['SCHOOL_ID']);
			$this->db->where("division_id",-1);
			$this->db->where("student_id",$app_id);
			$student = $this->db->get("student");
			if($student->num_rows() > 0){
				redirect(base_url() . '?registration/register/select/'.$app_id, 'refresh');
			}else{
				$this->session->set_flashdata('flash_message', 'Invalid Application ID: '.$this->input->post("app_id"));

				redirect(base_url() . '?registration', 'refresh');
			}
		}
		$page_data['page'] = 'register';
		$page_data['page_name'] = 'Registration';
		$page_data['page_title'] = "Application Form";
		$this->load->view('backend/registration', $page_data);
	}

	/***TEACHER DASHBOARD***/
	function register($param1 = "", $param2 = "")
	{
//		if ($this->session->userdata('superadmin') != 1)
//			redirect(base_url() . "?superadmin", 'refresh');
		$options = array("required_all"=>true,"except"=>"others,mname,last_school,last_school_duration,last_school_reason,c1_address2,c2_name,c2_address1,c2_address2,c2_phone,c2_relationship,hear_about_us");
		$form = $this->c_->get_student_form($options);
		$myform['Biodata'] = "surname,fname,mname,birthday,sex";
		$myform['Address'] = "nationality,state,lga,permanent_address,country_of_origin,primary_language";
		$myform['Previous School'] = "last_school,last_school_duration,last_school_reason";
		$myform['Class Applying For'] = "class_id";
		$myform['Primary Contact/Referees'] = "c1_name,c1_address1,c1_address2,c1_phone,c1_relationship";
		$myform['Secondary Contact/Referees'] = "c2_name,c2_address1,c2_address2,c2_phone,c2_relationship";
		$myform['Other Details'] = "hear_about_us,others";

		if($param1 == "create" || $param1 == "update"){
			foreach($myform as $key => $value) {
				$cols = explode(",", $value);
				foreach($cols as $col){
					$data[$col] = $this->input->post($col);
				}
			}
			$data['school_id'] = $GLOBALS['SCHOOL_ID'];
			$data['division_id'] = -1;
			unset($data['student_id']);

			if($param1 == "create") {
				$this->db->insert("student", $data);
				$id = $this->db->insert_id();
				$this->c_->move_image("image","student",$id);
				$this->session->set_flashdata('flash_message', 'Your application has been received');
				redirect(base_url() . '?registration/register/completed/'.$id, 'refresh');
			}else{
				$id = $this->input->post['student_id'];
				$this->db->where("student_id", $param2);
				$this->db->where("division_id",-1);
				$this->db->update("student",$data);
				$this->c_->move_image("image","student",$param2);
				$this->session->set_flashdata('flash_message', 'Update Successful');
				redirect(base_url() . '?registration/register/select/'.$param2, 'refresh');
			}
		}

		if($param1 == "completed"){
			if(!is_numeric($param2))
				redirect(base_url() . '?registration/register', 'refresh');
			$page_data['app_id'] = "APP-".$param2;
			$page_data['id'] = $param2;
		}

		if($param1 == "select" || $param1 == "print"){
			$this->db->where("school_id",$GLOBALS['SCHOOL_ID']);
			$this->db->where("division_id",-1);
			$this->db->where("student_id",$param2);
			$student = $this->db->get("student")->row_array();

			$options['student'] = $student;
			$page_data['id'] = $student['student_id'];
			$form = $this->c_->get_student_form($options);
			$page_data['update'] = true;
			if($param1 == "print")
				$page_data['onlyshow'] = true;
		}




		$page_data['form'] = $form;
		$page_data['myform'] = $myform;
		$page_data['page'] = 'registration';
		$page_data['page_name'] = 'Registration';
		$page_data['page_title'] = "Registration";
		$this->load->view('backend/registration', $page_data);
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




}