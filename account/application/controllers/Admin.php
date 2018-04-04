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

class Admin extends CI_Controller
{
	public $division_id;
	public $c_;

	function __construct()
	{
		parent::__construct();
		$this->load->database();

		$this->load->library('session');


		$this->c_ = $this->crud_model;
		/*cache control*/
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');

		if (!$this->session->hAccess('login')) {
			$this->session->set_flashdata('flash_message', "Please login first");
			redirect(base_url() . "?login", 'refresh');
		}

	}

	public function index()
	{
		redirect(base_url() . "?admin/dashboard", 'refresh');
	}

	/***ADMIN DASHBOARD***/
	function dashboard()
	{
		$page_data['page_name'] = 'dashboard';
		$page_data['page_title'] = get_phrase('dashboard');
		$spec = c()->get('specializations')->result_array();

		$page_data['spec'] = array();
		foreach($spec as $row)
			$page_data['spec'][$row['id']] = $row['name'];

		$this->load->view('backend/index', $page_data);
	}

	/****MANAGE STUDENTS CLASSWISE*****/
	function member_add($update = "")
	{
		$page_data['page_name'] = 'members_add';
		if(is_array($update)){
			$page_data['posted_student'] = $update;
			$page_data['type'] = $update['type'];
			$page_data['update'] = $update['id'] == ""?-1:$update['id'];
		}
		if($update['type'] == 'user')
			$page_data['page_title'] = get_phrase('add_member');
		else
			$page_data['page_title'] = get_phrase('add_admin');

		$this->load->view('backend/index', $page_data);
	}



	function view_members($param1 = 'users',$param2 = '', $param3 = '')
	{
		if($param1 == 'users'){
			$this->session->rAccess('manage_members');
		}else if($param1 == 'admin'){
			$this->session->rAccess('manage_admin');
		}else{
			$this->session->rAccess('???');
		}



		if ($param2 == 'create' || $param2 == 'update') {

			if($param1 == 'users'){
				$this->session->rAccess('manage_members');
			}

			$student_col = $this->c_->get_members_form();
			foreach($student_col as $col => $array){
				if($this->input->post($col) != null){
					$data[$col] = $this->input->post($col);
				}
			}
			if($this->input->post("access") != null){
				$x = array();
				foreach($this->input->post("access") as $access){
					$x[] = strtolower(str_replace(" ","_",$access));
				}
				$data['access'] = implode(",",$x);
			}


			$data['id'] = $param3;

			if(!isset($data['password']) && $param2 == "create"){
				$data['password'] = $this->c_->get_setting("members_default_password","123456");
			}

			$error = "";
			if($this->input->post('fname') == null || $this->input->post('surname') == null){
				$error = "First Name or Surname can not be empty";
			}

			if($this->input->post('phone') == null && $this->input->post('email') == null){
				$error = "Phone Number and Email can not both be empty.";
			}

			$data['type'] = $param1;
			$data['is_admin'] = $param1 == 'users'?0:1;

			if($error != "") {
				$this->session->set_flashdata('flash_message', $error);
				$this->member_add($data);

			}elseif($this->c_->detail_exit($this->input->post('email'),'email',$param3)) {
				$this->session->set_flashdata('flash_message', get_phrase('email_address_already_exit'));
				$this->member_add($data);

			}elseif($this->c_->detail_exit($this->input->post('email'),'email',$param3)) {
				$this->session->set_flashdata('flash_message', get_phrase('email_address_already_exit'));
				$this->member_add($data);

			}elseif($this->c_->detail_exit($this->input->post('phone'),'phone',$param3)) {
				$this->session->set_flashdata('flash_message', get_phrase('phone_number_already_in_use'));
				$this->member_add($data);

			}else{
				unset($data['id']);
				unset($data['type']);

				if ($param2 == "create") {
					$this->c_->insert('users', $data);
					$student_id = $this->db->insert_id();
					$this->c_->move_image("image", "users", $student_id);
					$this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
					try {
						$this->email_model->account_opening_email('users', $data['email']); //SEND EMAIL ACCOUNT OPENING EMAIL
					} catch (Exception $e) {
					}
					redirect(base_url() . '?admin/view_members/' .$param1, 'refresh');
				} else {
					$student_id = $param3;
					$this->db->where("id", $student_id);
					$this->c_->update('users', $data);
					$this->c_->move_image("image", "users", $student_id);
					//$this->email_model->account_opening_email('users', $data['email']);
					$this->session->set_flashdata('flash_message', get_phrase('data_updated_successfully'));
					redirect(base_url() . '?admin/view_members/' . $param1.'/', 'refresh');
				}

			}

		}else {


			if ($param2 == 'delete') {
				if ($param1 == 'users') {
					$this->session->rAccess('manage_members');
					$detail = array("is_admin" => 0);
				} else if ($param1 == 'admin') {
					$detail = array("is_admin" => 1);
				}
				$this->db->where($detail);
				$this->db->where('id', $param3);
				$this->c_->delete('users');

				c()->deleteAll("payments,booked_halls","user_id",$param2,$detail);

				$this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
				redirect(base_url() . '?admin/view_members/' . $param1, 'refresh');
			}

			$page_data['type'] = $param1;
			$page_data['type_'] = $param1 == 'users'?"Members":"Administrators";

			d()->order_by('surname','ASC');
			if ($param1 == 'users') {
				$page_data['members'] = c()->get_where('users', "is_admin", 0)->result_array();
				$page_data['page_title'] = get_phrase('Members');
			} else {
				$page_data['members'] = c()->get_where('users', "is_admin", 1)->result_array();
				$page_data['page_title'] = get_phrase('Admin');
			}

			$page_data['page_title'] = get_phrase($page_data['type_']);
			$page_data['page_name'] = 'view_members';
			d()->order_by('name','ASC');

			$specs = c()->get("specializations")->result_array();
			foreach($specs as $row)
				$page_data['specs'][$row['id']] = $row;

			$this->load->view('backend/index', $page_data);
		}
	}


	function checkout($product_id,$customer){
		$date = gdate();
		$start = d()->escape(database_date($date,0,0,"Y/m/d"));
		d()->where("$start between start_date and end_date");
		d()->where("deleted",0);
		d()->where("active",1);

		d()->where("type","Regular Customers");

		$query = c()->get('promo')->result_array();

		$discount = 0;
		$promo = array();

		foreach($query as $row) {
			if ($row['target'] == 1) {
				$prds = explode(',', $row['products']);
				if (indexOf($product_id, $prds) == -1)
					continue;
			}
			if($discount < $row['discount']){
				$discount = $row['discount'];
				$promo = $row;
			}
		}

		if(count($promo) == 0)
			return;

		static $promo_ids = array();

		if(in_array($promo['id'],$promo_ids))
			return;

		$promo_ids[] = $promo['id'];

		$x = d()->get_where("promo_regular",array("user_id"=>$customer,"promo_id"=>$promo['id']));

		if($x->num_rows() == 0){
			return d()->insert("promo_regular",array("user_id"=>$customer,"promo_id"=>$promo['id'],"sign_out"=>1,"max"=>$promo['days'],"date"=>database_date(gdate(),0,0,"Y/m/d")));
		}

		$myrow = $x-> row();
		$sign_out = $myrow->sign_out;
		$id = $myrow->id;
		$dt = $myrow->date;

		$diff = strtotime(database_date(gdate(),0,0,"Y/m/d")) - strtotime(database_date($dt,0,0,"Y/m/d"));

		$mysignout = $sign_out + 1;
		if($diff >= get_seconds(0,0,$promo['days'])) {
			$dt = database_date(gdate(), 0, 0, "Y/m/d");
			$mysignout = 1;
		}

		if($sign_out+1 >= $promo['sign_in']){
			d()->where("id",$id);
			return d()->update("promo_regular",array("sign_out"=>0,"date"=>database_date(gdate(),0,0,"Y/m/d")));
		}else{
			d()->where("id",$id);
			return d()->update("promo_regular",array("sign_out"=>$mysignout));
		}
	}

	function check_product($product_id, $customer){

		$date = gdate();

		$start = d()->escape(database_date($date,0,0,"Y/m/d"));
		$discount = 0;
		$type = "Specific Period";
		$name = "";
		$promo_id = 0;

			d()->where("$start between start_date and end_date");
			d()->where("deleted", 0);
			d()->where("active", 1);
			d()->where("type", "Specific Period");
			$query = c()->get('promo')->result_array();

			foreach ($query as $row) {
				if ($row['target'] == 1) {
					$prds = explode(',', $row['products']);
					if (indexOf($product_id, $prds) == -1)
						continue;
				}

				if ($discount <= $row['discount']) {
					$discount = $row['discount'];
					$name = $row['name'];
					$promo_id = $row['id'];
				}
			}


		d()->where("$start between start_date and end_date");
		d()->where("deleted",0);
		d()->where("active",1);

		d()->where("type","Regular Customers");

		$query = c()->get('promo')->result_array();

		foreach($query as $row){
			if($row['target'] == 1){
				$prds = explode(',',$row['products']);
				if(indexOf($product_id,$prds) == -1)
					continue;
			}

			$x = d()->get_where("promo_regular",array("user_id"=>$customer,"promo_id"=>$row['id']));

			if($x->num_rows() == 0){
				continue;
			}

			$myrow = $x-> row();
			$sign_out = $myrow->sign_out;
			$dt = $myrow->date;

			if(($sign_out+1) != $row['sign_in']){
				continue;
			}

			$diff = strtotime(database_date(gdate(),0,0,"Y/m/d")) - strtotime(database_date($dt,0,0,"Y/m/d"));

			if($diff >= get_seconds(0,0,$row['days']))
				continue;

			if($discount <= $row['discount']) {
				$discount = $row['discount'];
				$type = "Regular Customers";
				$name = $row['name'];
				$promo_id = $row['id'];

				break;
			}
		}

		return array("discount"=>$discount, "type"=>$type, "promo_name"=>$name,"promo_id"=>$promo_id);
	}


	function make_payment($param1 = '',$param2 = '')
	{

		$page_data['data'] = array();
		$page_data['customer'] = 0;
		$page_data['signout_id'] = 0;
		$page_data['mylist'] = "[]";

		if($param1 == 'signout'){
			rAccess("can_sign_out");

			d()->where("is_signout",0);
			$row = c()->get_where("sign_in",'id',$param2);
			if($row->num_rows() > 0){
				$x = $row->row();
				$page_data['signout_id'] = $param2;
				$page_data['customer'] = $x->user_id;
				$page_data['mylist'] = $x->products;
			}else{
				flash_redirect("?admin/manage_sign_in/view_all","Customer has already been signed out");
			}

		}


		if ($param1 == 'create') {
			rAccess("make_payment");

			$data['staff_id'] = s()->userdata("login_user_id");
			$data['user_id'] = $this->input->post('customer');
			$data['type'] = $this->input->post('type');
			$signout_id = $this->input->post('signout_id');
			$products = $this->input->post('products');

			$services = array();
			$prod = c()->get("services")->result_array();
			foreach($prod as $row)
				$services[$row['id']] = $row;

			if($data['type'] == 0) {
				$data['user_id'] = 0;
			}

			$grandtotal = 0;
			foreach($products as $key => $product){
				$products[$key] += $this->check_product($product['id'],$data['user_id']);
				$mine = $products[$key];

				$products[$key]['total'] = $services[$products[$key]['id']]['amount'] * $product['unit'];
				$gt = $products[$key]['total'];
				if($mine['discount'] > 0){
					$gt = round($gt - (($mine['discount']/100) * $gt),get_setting("approximate",2));
				}
				$products[$key]['grandtotal'] = $gt;
				$grandtotal += $gt;
			}
			$str_ = "";
			$str = "";

			$user = c()->get_where("users","id",$data['user_id']);

				if($data['type'] == 1) {
					$name = c()->get_full_name(@$user->row());
					$str_ .= "<h3>Payment by $name</h3>";
				}

				$str .= <<<eof
									<table class="table" style="text-align: left;" >
											<tr>
												<th>S/N</th>
												<th>Product/Service</th>
												<th>Amount</th>
												<th>Quantity</th>
												<th>Total</th>
												<th>Discount (Promo)</th>
												<th>Grand Total</th>
											</tr>
eof;
				$count = 0;
			$sms_str = "";

				foreach($products as $product){
					$count++;
					$str .= "<tr>";
					$str .= "<td>$count</td>";
					$str .= "<td>$product[name]</td>";
					$str .= "<td class='format_number'>$product[amount]</td>";
					$str .= "<td>$product[unit]</td>";
					$str .= "<td class='format_number'>$product[total]</td>";
					if($product['discount'] == 0)
						$str .= "<td>Not Available</td>";
					else
						$str .= "<td><label class='label label-info'><b>$product[discount] % </b></label> ($product[promo_name])</td>";

					$str .= "<td><b  class='format_number'>$product[grandtotal]</b></td>";
					$str .= "</tr>";
					$sms_str .= "\n$product[name] = N".number_format($product['grandtotal']);

				}
				$sms_str = substr($sms_str,1);


				$str .= "<tr><td colspan='6' style='text-align: right;'><b>Grand Total</b></td><td style='font-weight: bold; color: red;'
class='format_number'>
$grandtotal</td></tr>";
				$str .= "</table>";

				$email_str = $str;

				$str = $str_.$str;
			if($param2 == "preview"){
				$str .= "<div
class='form-group'><input type='button' class='btn btn-warning col-md-3 pull-right' value='Submit Payment'
onclick='submitForm(true)' /></div>";
				print $str;
				print "<script type='text/javascript'>format_numbers_now('N',0);</script>";
				exit;
			}

			$date = gdate();
			foreach($products as $product){
				$data = array();
				$data['staff_id'] = s()->userdata("login_user_id");
				$data['user_id'] = $this->input->post('customer');
				$data['type'] = $this->input->post('type');
				$data['amount'] = $product['amount'];
				$data['unit'] = $product['unit'];
				$data['total'] = $product['grandtotal'];
				$data['discount'] = $product['discount'];
				$data['product'] = $product['id'];
				$data['promo_id'] = $product['promo_id'];
				$data['comment'] = $this->input->post('remark');
				$data['date'] = $date;
				d()->insert("income",$data);
				$this->checkout($product['id'],$data['user_id']);
			}

			if($signout_id > 0){
				d()->where("id",$signout_id);
				d()->update("sign_in",array("sign_out"=>gdate(),"is_signout"=>1));
			}

			$str2 = "<h3>PAYMENT SUBMITTED SUCCESSFULLY</h3>";

			$str2 .= "<div
class='form-group'><input type='button' class='btn btn-info col-md-12' value='CLOSE'
onclick='closeForm()'
/></div>";

			if($this->input->post('type') == 1){
				if(!empty(get_setting("sms_receipt")) && $this->input->post("send_sms") == 1){
					$message = replaceV(get_setting("sms_receipt"),login_id(),array("receipt"=>$sms_str,"total"=>format_number($grandtotal)));
					$sender = get_setting("sms_senderid");
					c()->send_sms($message, $sender, user_data($data['user_id'],"phone"),login_id());
				}

				if(!empty(get_setting("email_receipt")) && $this->input->post("send_email") == 1){
					$message = replaceV(get_setting("email_receipt"),login_id(),array("receipt"=>$email_str,"total"=>format_number($grandtotal)));
					$sender = get_setting("email_senderid");
					c()->send_mail($message, $sender, user_data($data['user_id'],"email"),login_id());
				}
			}

			print $str2;
			exit;
		}


		$page_data['page_name'] = 'make_payment';

		$page_data['specs'] = array();
		$specs = c()->get("specializations")->result_array();
		foreach($specs as $row)
			$page_data['specs'][$row['id']] = $row;

		$x = c()->ajaxSpecs();
		$page_data['specs'] = $x[1];
		$page_data['specs_'] = $x[0];

		$page_data['customers'] = c()->get_where("users","is_admin",0)->result_array();
		$page_data['page_title'] = get_phrase('Submit_Payment');
		$this->load->view('backend/index', $page_data);
	}



	function manage_sign_in($param1 = '',$param2 = '')
	{



		$page_data['data'] = array();
		$page_data['view_all'] = false;

		if($param1 == "view_all"){
			$page_data['view_all'] = true;
		}

		if ($param1 == 'create') {
			s()->rAccess('can_sign_in');

			$type = $this->input->post('type');

			$products = $this->input->post('products');

			$registration = $this->input->post('registration');

			if($type == 0) {
				$result = c()->register($registration, true);
				if (!is_numeric($result)) {
					print "<h3>$result</h3>";
					exit;
				}
				$return = "User also created successfully. User ID = ".get_client_id($result);
			}else{
				$result = $this->input->post('customer');
				$return = "";
			}

			$date = gdate();
			$data = array();
			$data['staff_id'] = s()->userdata("login_user_id");
			$data['user_id'] = $result;
			$data['products'] = json_encode(is_array($products)?$products:array());
			$page_data['customers'] = c()->get_where("users","is_admin",0)->result_array();
			$data['sign_in'] = $date;
			d()->insert("sign_in",$data);

			$str2 = "<h3>SIGN IN SUCCESSFULLY</h3>$return";

			$str2 .= "<div class='form-group'><input type='button' class='btn btn-info col-md-12' value='CLOSE' onclick='closeForm()'/></div>";

			print $str2;
			exit;
		}


		$page_data['page_name'] = 'sign_in';

		$page_data['specs'] = array();
		$specs = c()->get("specializations")->result_array();
		foreach($specs as $row)
			$page_data['specs'][$row['id']] = $row;

		$x = c()->ajaxSpecs();
		$page_data['specs'] = $x[1];
		$page_data['specs_'] = $x[0];


		$page_data['users'] = array();
		$u = c()->get("users")->result_array();
		foreach($u as $row)
			$page_data['users'][$row['id']] = $row;

		if(!$page_data['view_all']){
			d()->where('is_signout', 0);
			d()->order_by("sign_in","DESC");
		}else{
			d()->where('is_signout', 1);
			d()->order_by("sign_out","DESC");
		}
		$page_data['signin'] = c()->get("sign_in")->result_array();
		$page_data['page_title'] = get_phrase('Sign-in Customers');

		$page_data['customers'] = c()->get_where("users","is_admin",0)->result_array();
		$this->load->view('backend/index', $page_data);
	}

	function manage_promos($param1 = '',$param2 = '')
	{
		rAccess("manage_promos");

		$page_data['data'] = array();

		if ($param1 == 'create') {
			set_time_limit(0);
			$data['name'] = $this->input->post('name');
			$data['type'] = $this->input->post('type');
			$data['discount'] = $this->input->post('discount');
			$data['start_date'] = database_date($this->input->post('start_date'));
			$data['end_date'] = database_date($this->input->post('end_date'));
			$data['target'] = $this->input->post('target');
			$data['target'] = $data['target'] == 0?0:1;

			if($data['target'] == 1){
				$data['products'] = $this->input->post('products');
			}

			if($data['type'] == "Regular Customers"){
				$data['days'] = $this->input->post('days');
				$data['sign_in'] = $this->input->post('sign_in');
			}

			$data['date'] = gdate();

			if(empty($data['name'])){
				flash_redirect("?admin/manage_promos","Please enter name");
			}

			$this->c_->insert('promo', $data);
			$id = d()->insert_id();

			if(!empty($this->input->post("smsbox")) && $this->input->post("send_sms") == 1){
				$message = $this->input->post("smsbox");
				$sender = get_setting("sms_senderid");
				$numbers = array();
				$rows = c()->get_where("users","is_admin",0)->result_array();
				foreach($rows as $row){
					$numbers .= ",".$row['phone'];
				}
				$all_numbers = numbers($numbers);
				c()->send_sms($message, $sender, $all_numbers);
			}

			if(!empty($this->input->post("emailbox")) && $this->input->post("send_email") == 1){
				$message = str_replace("\n","<br>",$this->input->post("emailbox"));
				$sender = get_setting("email_senderid");

				$rows = c()->get_where("users","is_admin",0)->result_array();
				foreach($rows as $row) {
					c()->send_mail($message, $sender, $row['email'], $row['id']);
				}
			}




			$this->session->set_flashdata('flash_message', get_phrase('promo created successfully'));
			redirect(base_url() . '?admin/manage_promos/', 'refresh');
		}

		if($param1 == 'activate'){
			$data['active'] = 1;
			d()->where("id",$param2);
			d()->update("promo",$data);
			flash_redirect("?admin/manage_promos","Promo/Offer Enabled Successfully");
		}

		if($param1 == 'deactivate'){
			$data['active'] = 0;
			d()->where("id",$param2);
			d()->update("promo",$data);
			flash_redirect("?admin/manage_promos","Promo/Offer Disabled");
		}

		if($param1 == 'delete'){
			$data['deleted'] = 1;
			d()->where("id",$param2);
			d()->update("promo",$data);
			flash_redirect("?admin/manage_promos","Promo/Offer Deleted");
		}




		$page_data['page_name'] = 'manage_promos';

		$page_data['products'] = array();
		$prod = c()->get("services")->result_array();
		foreach($prod as $row)
			$page_data['products'][$row['id']] = $row['name'];

		d()->where("deleted", 0);
		d()->order_by("id","DESC");
		$page_data['promos'] = c()->get("promo")->result_array();

		$page_data['specs'] = array();
		$specs = c()->get("specializations")->result_array();
		foreach($specs as $row)
			$page_data['specs'][$row['id']] = $row;

		$x = c()->ajaxSpecs();
		$page_data['specs'] = $x[1];
		$page_data['specs_'] = $x[0];


		$page_data['customers'] = c()->get_where("users","is_admin",0)->result_array();

		$page_data['page_title'] = get_phrase('List of Promos/Services');
		$this->load->view('backend/index', $page_data);
	}

	function expenditure()
	{
		rAccess("manage_expenditures");

			$data['staff_id'] = s()->userdata("login_user_id");
			$data['name'] = $this->input->post("name");
			$data['total'] = convert2number($this->input->post("amount"));
			$data['remark'] = $this->input->post("remark");
			$data['date'] = gdate();

			d()->insert("expenditure",$data);
			Print "Expenditure submitted successfully";
	}

	function view_payments($staff = 0, $customer = '', $view_type = 0, $type = 0, $param1 = '',$param2 = '')
	{

		if($staff === "search"){
			$date1 = $this->input->post("date1");
			$date2 = $this->input->post("date2");
			$staff = $this->input->post("staff");
			$customer = $this->input->post("customer");
			$view_type = $this->input->post("view_type");
			$type = $this->input->post("type");
			$add = "";

			if($type == c()->get_option_type("specific date")){
				$add = $date1."/$date2";
			}
			redirect(base_url() . "?admin/view_payments/$staff/$customer/$view_type/$type/$add", 'refresh');
		}

		$page_data['staff'] = $staff;
		$page_data['customer'] = $customer;
		$page_data['view_type'] = $view_type;
		$page_data['type'] = $type;
		$page_data['date1'] = $param1;
		$page_data['date2'] = $param2;
		$page_data['show'] = false;

		if(!hAccess("view_all_payments"))
			c()->where("id",login_id());

		$page_data['staffs'] = c()->get_where("users","is_admin",1)->result_array();

		$page_data['customers'] = c()->get_where("users","is_admin",0)->result_array();

		if($customer != ''){
			$page_data['show'] = true;

			if($param2 == ""){
				$param1 = date("d-m-Y 00:00");
				$param2 = date("d-m-Y 23:59");
			}

			$param1 = urldecode($param1);
			$param2 = urldecode($param2);

//			d()->where("date >=",database_date($param1));
//			d()->where("date <=",database_date($param2));
			$page_data['date1'] = $param1;
			$page_data['date2'] = $param2;

			$page_data['show'] = true;






		}

		$services = d()->get("services")->result_array();
		$x = array();

		foreach($services as $row){
			$x[$row['id']] = $row['name'];
		}
		$page_data['services'] = $x;




		$page_data['page_name'] = 'view_payments';

		$page_data['page_title'] = get_phrase('payment history');
		$this->load->view('backend/index', $page_data);
	}

function sent_messages($staff = 0, $customer = '', $view_type = 0, $type = 0, $param1 = '',$param2 = '')
	{
		if($staff === "search"){
			$date1 = $this->input->post("date1");
			$date2 = $this->input->post("date2");
			$staff = $this->input->post("staff");
			$customer = $this->input->post("customer");
			$view_type = $this->input->post("view_type");
			$type = $this->input->post("type");
			$add = "";

			if($type == c()->get_option_type("specific date")){
				$add = $date1."/$date2";
			}
			redirect(base_url() . "?admin/sent_messages/$staff/$customer/$view_type/$type/$add", 'refresh');
		}

		$page_data['staff'] = $staff;
		$page_data['customer'] = $customer;
		$page_data['view_type'] = $view_type;
		$page_data['type'] = $type;
		$page_data['date1'] = $param1;
		$page_data['date2'] = $param2;
		$page_data['show'] = false;
		$page_data['staffs'] = c()->get_where("users","is_admin",1)->result_array();
		$page_data['customers'] = c()->get_where("users","is_admin",0)->result_array();

		if($customer != ''){
			$page_data['show'] = true;

			if($param2 == ""){
				$param1 = date("d-m-Y 00:00");
				$param2 = date("d-m-Y 23:59");
			}

			$param1 = urldecode($param1);
			$param2 = urldecode($param2);

			$page_data['date1'] = $param1;
			$page_data['date2'] = $param2;

			$page_data['show'] = true;

		}

		$services = d()->get("services")->result_array();
		$x = array();

		foreach($services as $row){
			$x[$row['id']] = $row['name'];
		}
		$page_data['services'] = $x;




		$page_data['page_name'] = 'sent_messages';

		$page_data['page_title'] = get_phrase('sent_messages');
		$this->load->view('backend/index', $page_data);
	}



	function manage_transactions($branch_id='',$param1 = '',$param2 = '',$user = '')
	{
		if($param1 == 'search'){
			$branch_id = $this->input->post("branch_id");
			$date1 = $this->input->post("date1");
			$date2 = $this->input->post("date2");
			redirect(base_url() . "?admin/manage_transactions/$branch_id/$date1/$date2", 'refresh');
		}



		$page_data['page_name'] = 'manage_transactions';
		$page_data['date1'] = '';
		$page_data['date2'] = '';

		if($param2 != ""){
			d()->where("date >=",database_date($param1)." 00:00:00");
			d()->where("date <=",database_date($param2)." 23:59:59");
			$page_data['date1'] = $param1;
			$page_data['date2'] = $param2;

		}else{
			d()->limit(100);
		}
		$credentials = array();

		if(s()->hAccess('overall_admin') && $branch_id != ''){
			if($branch_id != 0){
				$credentials['branch_id'] = $branch_id;
			}
		}else{
			$credentials['branch_id'] = s()->userdata("branch_id");
		}

		d()->where($credentials);
		d()->order_by('date','DESC');
		$page_data['history'] = c()->get("payments")->result_array();

		if($param1 == 'approve'){
			d()->where('id',$param2);
			d()->where($credentials);
			d()->update("payments",array('confirmed'=>1));
			$this->session->set_flashdata('flash_message', get_phrase('payment_confirmed'));
			redirect(base_url() . "?admin/manage_transactions/$branch_id", 'refresh');

		}

		if ($param1 == 'delete') {
			$this->session->rAccess('overall_admin');
			$this->db->where('id', $param2);
			$this->c_->delete('payments');
			$this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
			redirect(base_url() . '?admin/manage_transactions/' . $branch_id, 'refresh');
		}

		$page_data['branch_id'] = isset($credentials['branch_id'])?$credentials['branch_id']:0;
		d()->order_by('name','ASC');
		$page_data['branch'] = c()->get("branch")->result_array();
		$page_data['page_title'] = get_phrase('transaction history');
		$this->load->view('backend/index', $page_data);
	}


	function receipt($param1 = '',$param2 = '')
	{
		d()->where('id',$param1);
		$row = c()->get_where("payments","user_id",s()->userdata("login_user_id"));

		if($row->num_rows() == 0){
			flash_redirect("?admin/dashboard","Invalid Receipt ID");
		}
		$history = $row->row_array();;
		$history['branch_name'] = c()->get_where('branch','id',$history['branch_id'])->row()->name;;
		$history['name'] = c()->get_where('users','id',$history['user_id'])->row();

		$page_data['history'] = $history;;
		$page_data['page_name'] = "receipt";
		$page_data['page_title'] = "Print E-Receipt";
		$this->load->view('backend/load', $page_data);
	}


function manage_specialization($param1 = '', $param2 = '', $param3 = '')
	{
		$this->session->rAccess('manage_products');

		if ($param1 == 'create') {
			$data['name'] = $this->input->post('name');
			$this->c_->insert('specializations', $data);
			$this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
			redirect(base_url() . '?admin/manage_specialization/', 'refresh');
		}

		if ($param1 == 'update') {
			$data['name'] = $this->input->post('name');
			$this->db->where('id', $param2);
			$this->c_->update('specializations', $data);
			$this->session->set_flashdata('flash_message', get_phrase('data_updated'));
			redirect(base_url() . '?admin/manage_specialization/', 'refresh');
		}

		if ($param1 == 'delete') {
			$this->c_->where('id', $param2);
			$this->c_->delete('specializations');

			c()->where("specialization",$param2);
			c()->update("services",array("deleted"=>1));

			$this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
			redirect(base_url() . '?admin/manage_specialization/', 'refresh');
		}

		$page_data['page_title'] = get_phrase('specialization_list');
		$page_data['page_name'] = 'manage_specialization';
		d()->order_by('name','ASC');
		$page_data['spec'] = c()->get('specializations')->result_array();
		$this->load->view('backend/index', $page_data);
	}

	function services_upload($param1 = '')
	{

		if($param1 == 'create') {


			$this->session->rAccess('manage_products');
			$spec = $this->input->post('specialization');

			include 'Simplexlsx.class.php';

			$xlsx = new SimpleXLSX($_FILES['file']['tmp_name']);

			list($num_cols, $num_rows) = $xlsx->dimension();

			$f = -1;
			$error = "";

			foreach ($xlsx->rows() as $r) {
				// Ignore the inital name row of excel file
				$f++;
				if ($f == 0) {
					continue;
				}

				$data['specialization'] = $spec;
				$data['name'] = $r[0];
				$data['amount'] = $r[1];
				$alldata[] = $data;
			}

			if (!empty($alldata)) {
				$this->c_->insert_batch('student', $alldata);
			}else{
				redirect(base_url() . '?admin/services/' . $spec, 'refresh');
			}


				flash_redirect('?admin/services/' . $spec , count($alldata).' Uploaded
				Successfully');



		}

	}


	function clients_upload()
	{
die();

			$this->session->rAccess('manage_products');
			$spec = $this->input->post('specialization');

			include 'Simplexlsx.class.php';


			$xlsx = new SimpleXLSX('users.xlsx',false,true);

			list($num_cols, $num_rows) = $xlsx->dimension();

			$f = -1;
			$error = "";
			$alldata = array();
			foreach ($xlsx->rows() as $r) {
				// Ignore the inital name row of excel file
				$f++;
				if ($f == 0) {
					continue;
				}

				$data['fname'] = $r[0];
				$data['surname'] = $r[1];
				$data['residential_address'] = $r[2];
				$p1 = trim($r[3]);
				$p2 = trim($r[4]);
				$email1 = trim($r[5]);
				$email2 = trim($r[6]);

				if(strlen($p1) > 5){
					$data['phone'] = $this->screen_number($p1);
				}else if(!empty($p1) && !empty($p2)){
					$x = $this->screen_number($p2);
					if(empty($x)){
						$data['phone'] = $this->screen_number($p1.$p2);
					}
				}


				if(strlen($p2) > 5){
					$data['mobile_phone'] = $this->screen_number($p2);
				}



				if(!empty($email1) && strpos($email1,"@") !== false){
					$data['email'] = $email1;
				}elseif(!empty($email2) && strpos($email2,"@") !== false){
					$data['email'] = $email2;
				}

				if(empty($data['phone']) && empty($data['mobile_phone']) && empty($data['email'])) {
					print "<br>skipping = ";
					print_r($r);
					continue;
				}

				if(empty($data['fname']) && empty($data['surname']))
					continue;
				d()->insert("users",$data);
				print "inserted($f)-";
				$alldata[] = $data;
			}
			print count($alldata)." pages";
		die("end");
			if (!empty($alldata)) {
				$this->c_->insert_batch('users', $alldata);

			}else{
				print "error, nothing to upload";
			}



	}

	function screen_number($no){
		$x = 0;
		$no = str_replace(array("-","(",")"," "),"",$no);
		return numbers($no,$x);
	}

	function services($param1 = '0', $param2 = '', $param3 = '')
	{
		$this->session->rAccess('manage_products');

		if ($param1 == 'create') {
			$data['name'] = $this->input->post('name');
			$data['specialization'] = $this->input->post('specialization');
			$data['amount'] = $this->input->post('amount');
			$this->c_->insert('services', $data);
			$this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
			redirect(base_url() . '?admin/services/'.$data['specialization'], 'refresh');
		}

		if($param1 == "select"){
			$id = $this->input->post('specialization');
			redirect(base_url() . '?admin/services/'.$id, 'refresh');
		}

		if ($param1 == 'update') {
			$data['name'] = $this->input->post('name');
			$data['specialization'] = $this->input->post('specialization');
			$data['amount'] = $this->input->post('amount');
			$this->db->where('id', $param2);
			$this->c_->update('services', $data);
			$this->session->set_flashdata('flash_message', get_phrase('data_updated'));
			redirect(base_url() . '?admin/services/'.$param3, 'refresh');
		}

		if ($param1 == 'delete') {
			$this->c_->where('id', $param2);
			$this->c_->update('services',array("deleted"=>1));
			$this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
			redirect(base_url() . '?admin/services/'.$param3, 'refresh');
		}

		$page_data['page_title'] = get_phrase('products and services');
		$page_data['page_name'] = 'services';
		if($param1 == 0){
			d()->order_by('name','ASC');
		}else{
			d()->order_by('name','ASC');
			d()->where('specialization',$param1);
		}
		d()->where('deleted',0);
		$page_data['services'] = c()->get('services')->result_array();
		$page_data['param1'] = $param1;
		$spec = c()->get('specializations')->result_array();
		$page_data['spec'] = array();
		foreach($spec as $row)
			$page_data['spec'][$row['id']] = $row['name'];

		$this->load->view('backend/index', $page_data);
	}



	function view_appointments( $param1 = '0', $param2 = '',$param3 = '')
	{
		$credentials = array();
		if(is_admin()){
			s()->rAccess('view_appointments');
			if($param1 != 0)
				$credentials['user_id'] = $param1;
		}else{
			$credentials['user_id'] = login_id();
		}

		if($param1 == 'select'){
			s()->rAccess('view_appointments');
			$customer = $this->input->post("customer");
			redirect(base_url() . '?admin/view_appointments/'.$customer, 'refresh');
		}
		if ($param1 == 'delete') {
			if(is_admin()){
				s()->rAccess('view_appointments');
			}else{
				$credentials['user_id'] = login_id();
			}
			$this->c_->where($credentials);
			$this->c_->where("id",$param2);
			$this->c_->delete('appointments');
			$this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
			redirect(base_url() . '?admin/view_appointments/'.$param3, 'refresh');
		}


//print_r($credentials);

		$page_data['page_title'] = get_phrase("appointments".'(s)');
		$page_data['page_name'] = 'view_appointments';

		$date = gdate();
		d()->where($credentials);
		d()->where("(first_to < '$date' OR (second_to < '$date' and second_to != '0000/00/00 00:00:00'))");
		d()->order_by('id','DESC');
		$expired = c()->get('appointments')->result_array();

		d()->where($credentials);
		d()->where("(first_to >= '$date' OR (second_to >= '$date' and second_to != '0000/00/00 00:00:00'))");
		d()->order_by('id','DESC');
		$booked = c()->get('appointments')->result_array();

		$page_data['booked_appointments'] = array();
		$page_data['expired_appointments'] = array();

		$users = c()->get('users')->result_array();
		$users_ = array();
		foreach($users as $row)
			$users_[$row['id']] = $row;

		foreach($booked as $row){
			$row['user'] = getIndex($users_, $row['user_id'], "User Deleted");
			$page_data['booked_appointments'][] = $row;
		}

		foreach($expired as $row){
				$row['user'] = getIndex($users_, $row['user_id'], "User Deleted");
				$page_data['expired_appointments'][] = $row;
		}

		$spec = c()->get('specializations')->result_array();
		$page_data['spec'] = array();
		foreach($spec as $row)
			$page_data['spec'][$row['id']] = $row['name'];

		$page_data['customers'] = c()->get_where("users","is_admin",0)->result_array();
		$page_data['customer'] = $param1;
		$this->load->view('backend/index', $page_data);
	}


	/***MANAGE EVENT / NOTICEBOARD, WILL BE SEEN BY ALL ACCOUNTS DASHBOARD**/
	function noticeboard($param1 = '', $param2 = '', $param3 = '')
	{
		$this->session->rAccess('view_board');

		if ($param1 == 'create') {
			$this->session->rAccess('manage_board');
			$data['notice_title'] = $this->input->post('notice_title');
			$data['notice'] = $this->input->post('notice');
			$data['create_timestamp'] = strtotime($this->input->post('create_timestamp'));
			$this->c_->insert('noticeboard', $data);

			$check_sms_send = $this->input->post('check_sms');

			if ($check_sms_send == 1) {
				// sms sending configurations
				$parents = $this->c_->get('parent')->result_array();
				$students = $this->c_->get('student')->result_array();
				$teachers = $this->c_->get('teacher')->result_array();
				$date = $this->input->post('create_timestamp');
				$message = $data['notice_title'] . ' ';
				$message .= get_phrase('on') . ' ' . $date;
				foreach ($parents as $row) {
					$reciever_phone = $row['phone'];
					$this->sms_model->send_sms($message, $reciever_phone);
				}
				foreach ($students as $row) {
					$reciever_phone = $row['phone'];
					$this->sms_model->send_sms($message, $reciever_phone);
				}
				foreach ($teachers as $row) {
					$reciever_phone = $row['phone'];
					$this->sms_model->send_sms($message, $reciever_phone);
				}
			}

			$this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
			redirect(base_url() . '?admin/noticeboard/', 'refresh');
		}
		if ($param1 == 'do_update') {
			$this->session->rAccess('manage_board');
			$data['notice_title'] = $this->input->post('notice_title');
			$data['notice'] = $this->input->post('notice');
			$data['create_timestamp'] = strtotime($this->input->post('create_timestamp'));
			$this->db->where('notice_id', $param2);
			$this->c_->update('noticeboard', $data);

			$check_sms_send = $this->input->post('check_sms');

			if ($check_sms_send == 1) {
				// sms sending configurations

				$parents = $this->c_->get('parent')->result_array();
				$students = $this->c_->get('student')->result_array();
				$teachers = $this->c_->get('teacher')->result_array();
				$date = $this->input->post('create_timestamp');
				$message = $data['notice_title'] . ' ';
				$message .= get_phrase('on') . ' ' . $date;
				foreach ($parents as $row) {
					$reciever_phone = $row['phone'];
					$this->sms_model->send_sms($message, $reciever_phone);
				}
				foreach ($students as $row) {
					$reciever_phone = $row['phone'];
					$this->sms_model->send_sms($message, $reciever_phone);
				}
				foreach ($teachers as $row) {
					$reciever_phone = $row['phone'];
					$this->sms_model->send_sms($message, $reciever_phone);
				}
			}

			$this->session->set_flashdata('flash_message', get_phrase('data_updated'));
			redirect(base_url() . '?admin/noticeboard/', 'refresh');
		} else if ($param1 == 'edit') {
			$page_data['edit_data'] = $this->c_->get_where('noticeboard', array(
				'notice_id' => $param2
			))->result_array();
		}
		if ($param1 == 'delete') {
			$this->session->rAccess('manage_board');
			$this->db->where('notice_id', $param2);
			$this->c_->delete('noticeboard');
			$this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
			redirect(base_url() . '?admin/noticeboard/', 'refresh');
		}
		$page_data['page_name'] = 'noticeboard';
		$page_data['page_title'] = get_phrase('manage_noticeboard');
		$page_data['notices'] = $this->c_->get('noticeboard')->result_array();
		$this->load->view('backend/index', $page_data);
	}

	/* private messaging */

	function message($param1 = 'message_home', $param2 = '', $param3 = '')
	{
		$this->session->rAccess('view_messages');

		if ($param1 == 'send_new') {
			$this->session->rAccess('send_message');
			$message_thread_code = $this->crud_model->send_new_private_message();
			$this->session->set_flashdata('flash_message', get_phrase('message_sent!'));
			redirect(base_url() . '?admin/message/message_read/' . $message_thread_code, 'refresh');
		}

		if ($param1 == 'send_reply') {
			$this->session->rAccess('send_message');
			$this->crud_model->send_reply_message($param2);  //$param2 = message_thread_code
			$this->session->set_flashdata('flash_message', get_phrase('message_sent!'));
			redirect(base_url() . '?admin/message/message_read/' . $param2, 'refresh');
		}

		if ($param1 == 'message_read') {
			$page_data['current_message_thread_code'] = $param2;  // $param2 = message_thread_code
			$this->crud_model->mark_thread_messages_read($param2);
		}

		$page_data['message_inner_page_name'] = $param1;
		$page_data['page_name'] = 'message';
		$page_data['page_title'] = get_phrase('private_messaging');
		$this->load->view('backend/index', $page_data);
	}


	/*****SITE/SYSTEM SETTINGS*********/
	function system_settings($param1 = '', $param2 = '', $param3 = '')
	{
		$this->session->rAccess('manage_settings');

		if ($param1 == 'do_update') {

			$data['description'] = $this->input->post('system_name');
			$this->c_->set_setting("system_name",$this->input->post('system_name'));
			$this->c_->set_setting("members_default_password",$this->input->post('members_default_password'));
			$this->c_->set_setting("bank_details",$this->input->post('bank_details'));
			$this->c_->set_setting("system_title",$this->input->post('system_title'));
			$this->c_->set_setting("address",$this->input->post('address'));
			$this->c_->set_setting("phone",$this->input->post('phone'));
			$this->c_->set_setting("paypal_email",$this->input->post('paypal_email'));
			$this->c_->set_setting("currency",$this->input->post('currency'));
			$this->c_->set_setting("system_email",$this->input->post('system_email'));
			$this->c_->set_setting("language",$this->input->post('language'));
			$this->c_->set_setting("text-align",$this->input->post('text-align'));


			$this->session->set_flashdata('flash_message', get_phrase('data_updated'));
			redirect(base_url() . '?admin/system_settings/', 'refresh');
		}
		if ($param1 == 'upload_logo') {
			$this->session->rAccess('manage_settings');
			$this->c_->move_image("userfile","",-1,"logo");
			$this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
			redirect(base_url() . '?admin/system_settings/', 'refresh');
		}
		if ($param1 == 'change_skin') {
			$this->session->rAccess('manage_settings');
			$description = $param2;
			$this->c_->set_setting("skin_colour",$description);
			$this->session->set_flashdata('flash_message', get_phrase('theme_selected'));
			redirect(base_url() . '?admin/system_settings/', 'refresh');
		}
		$page_data['page_name'] = 'system_settings';
		$page_data['page_title'] = get_phrase('system_settings');
		$page_data['settings'] = $this->c_->get('settings')->result_array();
		$this->load->view('backend/index', $page_data);
	}

	/*****SITE/SYSTEM SETTINGS*********/
	function alerts($param1 = '', $param2 = '', $param3 = '')
	{
		$this->session->rAccess('manage_alerts');

		if ($param1 == 'do_update') {

			save_setting("booking_send_sms",0);
			save_setting("booking_send_email",0);

			save_setting("booking_reminder_send_sms",0);
			save_setting("booking_reminder_send_email",0);

			foreach($_POST as $key => $value){
				save_setting($key,$this->input->post($key));
			}


			$this->session->set_flashdata('flash_message', get_phrase('data_updated'));
			redirect(base_url() . '?admin/alerts/', 'refresh');
		}

		$page_data['fields'] = $this->db->list_fields("users");
		$page_data['page_name'] = 'alerts';
		$page_data['page_title'] = get_phrase('sms & email settings');
		$page_data['settings'] = $this->c_->get('settings')->result_array();
		$this->load->view('backend/index', $page_data);
	}

	/***** UPDATE PRODUCT *****/

	function update($task = '', $purchase_code = '')
	{

		if ($this->session->userdata('admin_login') != 1)
			redirect(base_url(), 'refresh');

		// Create update directory.
		$dir = 'update';
		if (!is_dir($dir))
			mkdir($dir, 0777, true);

		$zipped_file_name = $_FILES["file_name"]["name"];
		$path = 'update/' . $zipped_file_name;

		move_uploaded_file($_FILES["file_name"]["tmp_name"], $path);

		// Unzip uploaded update file and remove zip file.
		$zip = new ZipArchive;
		$res = $zip->open($path);
		if ($res === TRUE) {
			$zip->extractTo('update');
			$zip->close();
			unlink($path);
		}

		$unzipped_file_name = substr($zipped_file_name, 0, -4);
		$str = file_get_contents('./update/' . $unzipped_file_name . '/update_config.json');
		$json = json_decode($str, true);


		// Run php modifications
		require './update/' . $unzipped_file_name . '/update_script.php';

		// Create new directories.
		if (!empty($json['directory'])) {
			foreach ($json['directory'] as $directory) {
				if (!is_dir($directory['name']))
					mkdir($directory['name'], 0777, true);
			}
		}

		// Create/Replace new files.
		if (!empty($json['files'])) {
			foreach ($json['files'] as $file)
				copy($file['root_directory'], $file['update_directory']);
		}

		$this->session->set_flashdata('flash_message', get_phrase('product_updated_successfully'));
		redirect(base_url() . '?admin/system_settings');
	}

	/*****SMS SETTINGS*********/
	function sms_settings($param1 = '', $param2 = '')
	{
		$this->session->rAccess('manage_sms');

		if ($param1 == 'clickatell') {

			$data['description'] = $this->input->post('clickatell_user');
			$this->db->where('type', 'clickatell_user');
			$this->c_->update('settings', $data);

			$data['description'] = $this->input->post('clickatell_password');
			$this->db->where('type', 'clickatell_password');
			$this->c_->update('settings', $data);

			$data['description'] = $this->input->post('clickatell_api_id');
			$this->db->where('type', 'clickatell_api_id');
			$this->c_->update('settings', $data);

			$this->session->set_flashdata('flash_message', get_phrase('data_updated'));
			redirect(base_url() . '?admin/sms_settings/', 'refresh');
		}

		if ($param1 == 'twilio') {

			$data['description'] = $this->input->post('twilio_account_sid');
			$this->db->where('type', 'twilio_account_sid');
			$this->c_->update('settings', $data);

			$data['description'] = $this->input->post('twilio_auth_token');
			$this->db->where('type', 'twilio_auth_token');
			$this->c_->update('settings', $data);

			$data['description'] = $this->input->post('twilio_sender_phone_number');
			$this->db->where('type', 'twilio_sender_phone_number');
			$this->c_->update('settings', $data);

			$this->session->set_flashdata('flash_message', get_phrase('data_updated'));
			redirect(base_url() . '?admin/sms_settings/', 'refresh');
		}

		if ($param1 == 'active_service') {

			$data['description'] = $this->input->post('active_sms_service');
			$this->db->where('type', 'active_sms_service');
			$this->c_->update('settings', $data);

			$this->session->set_flashdata('flash_message', get_phrase('data_updated'));
			redirect(base_url() . '?admin/sms_settings/', 'refresh');
		}

		$page_data['page_name'] = 'sms_settings';
		$page_data['page_title'] = get_phrase('sms_settings');
		$page_data['settings'] = $this->c_->get('settings')->result_array();
		$this->load->view('backend/index', $page_data);
	}

	/*****LANGUAGE SETTINGS*********/
	function manage_language($param1 = '', $param2 = '', $param3 = '')
	{
		$this->session->rAccess('manage_languages');

		if ($param1 == 'edit_phrase') {
			$page_data['edit_profile'] = $param2;
		}
		if ($param1 == 'update_phrase') {
			$language = $param2;
			$total_phrase = $this->input->post('total_phrase');
			for ($i = 1; $i < $total_phrase; $i++) {
				//$data[$language]	=	$this->input->post('phrase').$i;
				$this->db->where('phrase_id', $i);
				$this->c_->update('language', array($language => $this->input->post('phrase' . $i)));
			}
			redirect(base_url() . '?admin/manage_language/edit_phrase/' . $language, 'refresh');
		}
		if ($param1 == 'do_update') {
			$language = $this->input->post('language');
			$data[$language] = $this->input->post('phrase');
			$this->db->where('phrase_id', $param2);
			$this->c_->update('language', $data);
			$this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
			redirect(base_url() . '?admin/manage_language/', 'refresh');
		}
		if ($param1 == 'add_phrase') {
			$data['phrase'] = $this->input->post('phrase');
			$this->c_->insert('language', $data);
			$this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
			redirect(base_url() . '?admin/manage_language/', 'refresh');
		}
		if ($param1 == 'add_language') {
			$this->session->rAccess('add_language');
			$language = $this->input->post('language');
			$this->load->dbforge();
			$fields = array(
				$language => array(
					'type' => 'LONGTEXT'
				)
			);
			$this->dbforge->add_column('language', $fields);

			$this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
			redirect(base_url() . '?admin/manage_language/', 'refresh');
		}
		if ($param1 == 'delete_language') {
			$language = $param2;
			$this->load->dbforge();
			$this->dbforge->drop_column('language', $language);
			$this->session->set_flashdata('flash_message', get_phrase('settings_updated'));

			redirect(base_url() . '?admin/manage_language/', 'refresh');
		}
		$page_data['page_name'] = 'manage_language';
		$page_data['page_title'] = get_phrase('manage_language');
		//$page_data['language_phrases'] = $this->c_->get('language')->result_array();
		$this->load->view('backend/index', $page_data);
	}


	function convert_to_date($time)
	{
		return date("j F, Y", $time);
	}

	function security($param1 = '', $param2 = ''){
		$this->session->rAccess('view_permissions');

		if($param1 == "create"){
			$this->session->rAccess('manage_permissions');
			$data['name'] = $this->input->post("name");
			$data['email'] = $this->input->post("email");
			$data['password'] = $this->input->post("password");
			$data['access'] = @implode(",",$this->input->post("permissions"));

			$this->c_->insert("admin",$data);

			$this->session->set_flashdata('flash_message', get_phrase('admin_created_successfully'));
			redirect(base_url()."?admin/security","refresh");
		}

		if ($param1 == 'delete') {
			$this->session->rAccess('manage_permissions');
			$this->db->where('admin_id', $param2);
			$this->db->where("school_id",$GLOBALS['SCHOOL_ID']);
			$this->db->delete('admin');
			$this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
			redirect(base_url() . '?admin/security', 'refresh');
		}

		if($param1 == "update"){
			$this->session->rAccess('manage_permissions');

			$per = $this->input->post("students");
			$this->c_->set_setting("students_access",@implode(",",@$per));

			$per = $this->input->post("parents");
			$this->c_->set_setting("parents_access",@implode(",",@$per));

			$t = c()->get('teacher_categories')->result_array();
			foreach($t as $row){
				if($this->input->post("tc".$row['category_id']) == null)
					continue;
				$per = $this->input->post("tc".$row['category_id']);
				$myper = @implode(",",@$per);
				c()->where('category_id',$row['category_id']);
				c()->update('teacher_categories',array('access'=>$myper));
			}

			$this->session->set_flashdata('flash_message', get_phrase('update_successful'));
			redirect(base_url()."?admin/security","refresh");
		}

		if($param1 == "update_admin"){
			$this->session->rAccess('manage_permissions');

			$data['name'] = $this->input->post("name");
			$data['email'] = $this->input->post("email");
			$data['email'] = $this->input->post("email");
			$data['access'] = @implode(",",$this->input->post("permissions"));
			if($this->input->post("password") != null){
				$data['password'] = $this->input->post("password");
			}
			$this->db->where("school_id",$GLOBALS['SCHOOL_ID']);
			$this->db->where("admin_id",$param2);
			$this->db->update("admin",$data);

			$this->session->set_flashdata('flash_message', get_phrase('update_successful'));
			redirect(base_url()."?admin/security","refresh");
		}
		$this->db->where("school_id",$GLOBALS['SCHOOL_ID']);
		$page_data['admins'] = $this->db->get('admin')->result_array();
		$page_data['page_name'] = 'security';
		$page_data['page_title'] = get_phrase('manage_permission');
		$this->load->view('backend/index', $page_data);
	}

	/*****BACKUP / RESTORE / DELETE DATA PAGE**********/
	function backup_restore($operation = '', $type = '')
	{
		$this->session->rAccess('manage_settings');

		if ($operation == 'create') {
			$this->crud_model->create_backup($type);
		}
		if ($operation == 'restore') {
			$this->crud_model->restore_backup();
			$this->session->set_flashdata('backup_message', 'Backup Restored');
			redirect(base_url() . '?admin/backup_restore/', 'refresh');
		}
		if ($operation == 'delete') {
			$this->crud_model->truncate($type);
			$this->session->set_flashdata('backup_message', 'Data removed');
			redirect(base_url() . '?admin/backup_restore/', 'refresh');
		}

		$page_data['page_info'] = 'Create backup / restore from backup';
		$page_data['page_name'] = 'backup_restore';
		$page_data['page_title'] = get_phrase('manage_backup_restore');
		$this->load->view('backend/index', $page_data);
	}

	/******MANAGE OWN PROFILE AND CHANGE PASSWORD***/
	function manage_profile($param1 = '', $param2 = '', $param3 = '')
	{

		$login_as = "users";
		$login_id = $this->session->userdata("login_user_id");
		$login_as_id = "id";

		if ($param1 == 'update_profile_info') {

				$data['surname'] = $this->input->post('surname');
				$data['fname'] = $this->input->post('fname');
				$data['mname'] = $this->input->post('mname');

			$data['email'] = $this->input->post('email');
			$data['phone'] = numbers($this->input->post('phone'));

			if(empty($data['phone'])){
				flash_redirect("?admin/manage_profile","Invalid Phone Number");
			}

			if($this->c_->detail_exit($this->input->post('email'),'email',$login_id)) {
				$this->session->set_flashdata('flash_message', get_phrase('invalid or email_address_already_exit'));
				redirect(base_url() . '?admin/manage_profile/', 'refresh');

			}elseif($this->c_->detail_exit($data['phone'],'phone',$login_id)) {
				$this->session->set_flashdata('flash_message', $this->input->post('phone'). " Phone Number Already in use");
				redirect(base_url() . '?admin/manage_profile/', 'refresh');

			}

			$this->db->where($login_as_id, $login_id);
			$this->c_->update($login_as, $data);
			$this->c_->move_image("image",$this->session->userdata("login_as"),$login_id);
			$this->session->set_flashdata('flash_message', get_phrase('account_updated'));
			redirect(base_url() . '?admin/manage_profile/', 'refresh');
		}

		if ($param1 == 'change_password') {
			$data['password'] = $this->input->post('password');
			$data['new_password'] = $this->input->post('new_password');
			$data['confirm_new_password'] = $this->input->post('confirm_new_password');

			$current_password = $this->c_->get_where($login_as, array(
				$login_as_id => $login_id
			))->row()->password;
			if ($current_password == $data['password'] && $data['new_password'] == $data['confirm_new_password']) {
				$this->db->where($login_as_id, $login_id);
				$this->c_->update($login_as, array(
					'password' => $data['new_password']
				));
				$this->session->set_flashdata('flash_message', get_phrase('password_updated'));
			} else {
				$this->session->set_flashdata('flash_message', get_phrase('password_mismatch'));
			}
			redirect(base_url() . '?admin/manage_profile/', 'refresh');
		}

		$page_data['page_name'] = 'manage_profile';
		$page_data['page_title'] = get_phrase('manage_profile');
		
		$page_data['edit_data'] = $this->c_->get_where("users", array(
			"id" => $login_id
		))->result_array();
		$this->load->view('backend/index', $page_data);
	}


}
