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

class Users extends CI_Controller
{
	public $division_id;
	public $c_;

	function __construct()
	{
		parent::__construct();
		$this->load->database();
//		$this->load->model("domain_model");
		$this->load->library('session');



		$this->c_ = $this->crud_model;
		/*cache control*/
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		s()->set_userdata("login_type",'admin');

	}

	/***default functin, redirects to login page if no admin logged in yet***/
	public function index()
	{
		redirect(base_url() . "?admin/dashboard", 'refresh');
	}

	function book_appointment($param1 = '')
	{
		$data = array();
		if ($param1 == 'create') {
			$error = "";

			$regdata = array();

			if(!s()->hAccess("login")){
				$logintype = $this->input->post('logintype');


				if($logintype == "registered"){
					$email = $this->input->post('loginemail');
					$password = $this->input->post('loginpassword');
					if(!$this->validate_login($email, $password)){
						$error = "Invalid Email/Phone or Password Entered";
					}
				}else if($logintype == "new"){
					$regdata['email'] = $this->input->post('email');
					$regdata['password'] = $this->input->post('password');
					$regdata['surname'] = $this->input->post('surname');
					$regdata['fname'] = $this->input->post('fname');
					$regdata['phone'] = $this->input->post('phone');
					$regdata['residential_address'] = $this->input->post('address');
					$regdata['state'] = $this->input->post('state');
					$regdata['nationality'] = $this->input->post('nationality');

					$result = c()->register($regdata);

					if(!is_numeric($result)){
						$error = $result;
					}else{
						$this->validate_login($regdata['email'], $regdata['password']);
					}
				}else{
					$error = "Please select 'New Customer' or 'Already Registered Customer'";
				}
			}

			$data['user_id'] = s()->userdata("login_user_id") != ""?s()->userdata("login_user_id"):0;
			$data['type'] = $this->input->post('type');
			$data['specialization'] = $this->input->post('specialization');
			$date1 = $this->input->post('date1');
			$time1 = $this->input->post('time1');

			$date2 = ""; $time2 = "";
			if($this->input->post('date2') != null && $this->input->post('time2') != null){
				$date2 = $this->input->post('date2');
				$time2 = $this->input->post('time2');
				$date = "$date2 $time2:00";
				$data['second_from'] = database_date($date);
				$data['second_to'] = database_date($date, 60 * 60 * 2);
			}

			$data['comment'] = $this->input->post('comment');


			if(empty($data['type'])){
				$error = "Please select a valid appointment type";
			}

			if(empty($data['specialization'])){
				$error = "Enter your specialization";
			}

			if($this->input->post('date1') == null || $this->input->post('time1') == null){
				$error = "Invalid date selected";
			}

			$date = "$date1 $time1:00";
			$data['first_from'] = database_date($date);
			$data['first_to'] = database_date($date, 60 * 60 * 2);

			$data['date'] = date("Y/m/d H:i:s");
			if($error == ""){
				d()->insert('appointments', $data);
				$id = d()->insert_id();

				if(get_setting("booking_send_sms") == 1 && s()->userdata("login_user_id")> 0){
					$message = replaceV(get_setting("booking_sms"),login_id(),array("first_date"=>convert_to_datetime($data['first_from'])));
					$sender = get_setting("sms_senderid");
					c()->send_sms($message, $sender, user_data(s()->userdata("login_user_id"),"phone"),login_id());
				}

				if(get_setting("booking_send_email") == 1 ){
					$message = replaceV(get_setting("booking_email"),login_id(),array("first_date"=>convert_to_datetime($data['first_from'])));
					$sender = get_setting("email_senderid");
					c()->send_mail($message, $sender, user_data(s()->userdata("login_user_id"),"email"),login_id());
				}


				$this->session->set_flashdata('flash_message', get_phrase('appointment successfully'));

				redirect(base_url() . '?users/receipt/'.$id, 'refresh');
			}
			$data += $regdata;
			$data['logintype'] = $this->input->post('logintype');
			$data['loginemail'] = $this->input->post('loginemail');
			$data['date1'] = $date1;
			$data['time1'] = $time1;
			$data['date2'] = $date2;
			$data['time2'] = $time2;

			$data['error'] = $error;

			$this->session->set_flashdata('flash_message', $error);

		}else{
//			$row = d()->get_where('users',array("id"=>s()->userdata('login_user_id')));;
//			if($row->num_rows()>0){
//				$row = $row->row();
//				$data['fname'] = $row->fname;
//				$data['surname'] = $row->surname;
//				$data['phone'] = $row->phone;
//			}
		}


		$page_data['data'] = $data;
		$page_data['page_name'] = 'book_appointment';
		$x = c()->ajaxSpecs("hall");
		$page_data['specs'] = $x[1];
		$page_data['specs_'] = $x[0];
		$page_data['page_title'] = get_phrase("Appointment");
		$this->load->view('backend/index', $page_data);
	}

function register_user($param1 = '')
	{
		if(s()->hAccess("login")){
			flash_redirect("?admin/dashboard","You can not register while log in");
		}

		$regdata = array();
		if ($param1 == 'create') {
			$error = "";

					$x = "surname,fname,mname,birthday,sex,phone,email,password,nationality,state,lga,residential_address";
					$y = explode(",",$x);

					foreach($y as $v){
						$regdata[$v] = $this->input->post($v);
					}
					$result = c()->register($regdata);

					if(!is_numeric($result)){
						$error = $result;
					}else{
						$this->email_model->account_opening_email('users', $regdata['email']);
						$this->validate_login($regdata['email'], $regdata['password']);
						flash_redirect("?users/book_appointment");
					}



			$regdata['error'] = $error;

			$this->session->set_flashdata('flash_message', $error);

		}
		$page_data['posted_student'] = $regdata;

		$page_data['page_name'] = 'register';

			$page_data['page_title'] = get_phrase('Members Registration');

		$this->load->view('backend/index', $page_data);
	}


	function check_appointment($user_id=0,$date="",$time=0,$print=true){

			$date = "$date $time:00";
			$hrs = 2;
			$app = array();
			$start = d()->escape(database_date($date));
			$end = d()->escape(database_date($date,(3600 * $hrs)));
			d()->order_by("date","ASC");
			d()->where("($start <= first_from and $end >= first_from) OR ($start between first_from and first_to)");
			$query = c()->get_where('appointments',"staff_id",$user_id)->result_array();

			foreach($query as $row){
				$app[$row['id']]['start_date'] = $row['first_from'];
				$app[$row['id']]['end_date'] = $row['first_to'];
			}

			d()->order_by("date","ASC");
			d()->where("($start <= second_from and $end >= second_from) OR ($start between second_from and second_to)");
			$query = c()->get_where('appointments',"staff_id",$user_id)->result_array();

			foreach($query as $row){
				$app[$row['id']]['start_date'] = $row['second_from'];
				$app[$row['id']]['end_date'] = $row['second_to'];
			}

		if(count($app) == 0) {
			if ($print) print "<b style='color: green;'>Staff is <b style='color:red;'>available</b> for booking for the period
specified</b>"; else return "OK";
			return;
		}

		$str = "<h5>The Staff is already booked for the period specified as follows:</h5>
<table class='table'>
<tr>";


$str .= "<th>Date</th>";

$str .= "<th>Time</th>

</tr>
";
		$count = 1;
		foreach($app as $row){
			$str.= "<tr>";
			$str.= "<td>".convert_to_date($row['start_date'])."</td>";
			$str.= "<td>".convert_to_date($row['start_date'],"h:i A")." - ".convert_to_date($row['end_date'],'h:i A')."</td>";
			$str.= "</tr>";
			$count++;
		}
		$str .= "</table><br><b>Please choose another date/time or another staff</b>";

		if($print) print $str; else return $str;
	}

	function receipt($param1 = '',$param2 = '')
	{
		d()->where('id',$param1);
		$row = c()->get("appointments");

		if($row->num_rows() == 0){
			flash_redirect("?users/book_appointment","Invalid Receipt ID");
		}

		$row = $row->row();;

		$credentials = array();
		if(is_admin()){
			$credentials['id'] = $row->user_id;
		}else{
			$credentials["id"] = s()->userdata("login_user_id");
		}
		c()->where($credentials);
		$row2 = c()->get('users')->row();;

		$history['name'] = c()->get_full_name($row2);
		$history['email'] = $row2->email;
		$history['phone'] = $row2->phone;
		$history['first_from'] = $row->first_from;
		$history['first_to'] = $row->first_to;

		if($row->second_from != null && $row->second_from != "0000-00-00 00:00:00"){
			$history['second_from'] = $row->second_from;
			$history['second_to'] = $row->second_to;
		}

		$history['comment'] = $row->comment;
		$history['app_id'] = generate_app_id($row->id);
		$history['date'] = $row->date;
		$page_data['history'] = $history;
		$page_data['page_name'] = "appointment_receipt";
		$page_data['page_title'] = "Print E-Receipt";
		$this->load->view('backend/load', $page_data);
	}

	/***ADMIN DASHBOARD***/

	function validate_login($email = '', $password = '') {

		if(empty($email) || empty($password)){
			return false;
		}
		$credential = array( 'password' => $password);

		if(c()->is_email($email)){
			$credential['email'] = $email;
		}else{
			$credential['phone'] = numbers($email);

			if(empty($credential['phone']))
				return false;
		}


		// Checking login credential for teacher
		$query = $this->c_->get_where('users', $credential);
		if ($query->num_rows() > 0) {
			$row = $query->row();

			$this->session->set_userdata('login_user_id', $row->id);
			$this->session->set_userdata('name',  ucwords($row->surname." ".$row->fname));
			$this->session->set_userdata('login_type', 'admin');
			$this->session->set_userdata('is_admin', $row->is_admin);
			$this->session->set_userdata('login_as', $row->is_admin?"admin":"member");
			$this->session->set_userdata('access', "login");
			$this->session->set_userdata('specific_access', $row->access);
			if ($row->disabled == 1){
				$this->session->sess_destroy();
				return false;
			}
			return true;
		}



		return false;
	}




}
