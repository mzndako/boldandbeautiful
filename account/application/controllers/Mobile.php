<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*	
 *	@author 	     :    FreePhpSoftwares
 *	date		     :    25 July, 2015
 *	Item             :    FPS School Management System ios Application
 *  Specification    :    Mobile app response, JSON formatted data for iOS & android app
 *	Portfolio        :    http://codecanyon.net/user/FreePhpSoftwares
 *  Website          :    http://www.freephpsoftwares.com
 *	Support          :    http://support.freephpsoftwares.com
 */

class Mobile extends CI_Controller
{
    
    
	function __construct()
	{
		parent::__construct();
        $this->load->database();

        $this->load->library('session');

        $this->c_ = $this->crud_model;

        // Authenticate data manipulation with the user level security key


    }

    
    
    // generate response to home page with all pixels and advertise_id
    function make_payment($param1 = '')
    {
        $x = $this->validate_auth_key();
        if ($x != 'success')
            die($x);

        if ($param1 == 'create') {
            $data['branch_id'] = s()->userdata("branch_id");
            $data['user_id'] = s()->userdata("login_user_id");
            $data['teller_no'] = $this->input->post('teller');
            $data['amount'] = convert2number($this->input->post('amount'));
            $data['purpose'] = $this->input->post('purpose');
            $data['branch_strength'] = $this->input->post('strength');
            $data['phone'] = $this->input->post('phone');
            $data['remark'] = $this->input->post('remark');
            $data['date'] = gdate();

            if(empty($data['branch_id'])){
                die("Invalid branch Id");
            }
            if(empty($data['teller_no'])){
                die("Invalid Teller No");
            }
            if(empty($data['amount'])){
                die("Invalid Amount");
            }


            $this->c_->insert('payments', $data);
            $id = d()->insert_id();

            print "OK ".$id;
        }

    }

    function receipt($param1 = '',$param2 = '')
    {
        $x = $this->validate_auth_key();
        if ($x != 'success')
            die($x);

        d()->where('id',$param1);
        $row = c()->get_where("payments","user_id",s()->userdata("login_user_id"));

        if($row->num_rows() == 0){
            die("Invalid Receipt ID");
        }
        $history = $row->row_array();;
        $history['branch_name'] = c()->get_where('branch','id',$history['branch_id'])->row()->name;;
        $history['name'] = c()->get_where('users','id',$history['user_id'])->row();

        $page_data['history'] = $history;;
        $page_data['page_name'] = "receipt";
        $page_data['page_title'] = "Print E-Receipt";
        $this->load->view('backend/mobile/index', $page_data);
    }

    function hall_receipt($param1 = '',$param2 = '')
    {
        d()->where('id',$param1);
        $row = c()->get("booked_halls");

        if($row->num_rows() == 0){
           die("Invalid Receipt ID");
        }
        $history = $row->row_array();;
        $row = c()->get_where('halls','id',$history['hall_id'])->row();;

        $history['hall_name'] = $row->name;
        $history['capacity'] = $row->capacity;
        $history['location'] = $row->address;

        $page_data['history'] = $history;;
        $page_data['page_name'] = "hall_receipt";
        $page_data['page_title'] = "Print E-Receipt";
        $this->load->view('backend/mobile/index', $page_data);
    }

    function book_hall($type = 'hall',$param1 = '')
    {
        $x = $this->validate_auth_key();

        $data = array();
        if ($param1 == 'create') {

            $data['user_id'] = s()->userdata("login_user_id") != ""?s()->userdata("login_user_id"):0;
            $data['hall_id'] = $this->input->post('hall_id');
            $data['amount'] = convert2number($this->input->post('amount'));
            $data['event'] = $type == 'hall'?$this->input->post('event'):"";
            $data['phone'] = $this->input->post('phone');
            $data['fname'] = $this->input->post('fname');
            $data['surname'] = $this->input->post('surname');
            $data['method'] = $this->input->post('mymethod');
            $data['date'] = gdate();

            $error = "";
            if(empty($data['hall_id'])){
                $error = "Please select a valid hall";
            }

            if(empty($data['fname']) || empty($data['surname'])){
                $error = "Enter your name";
            }
            if(empty($data['amount'])){
                $error = "No hall select or hall is not available";
            }

            if($this->input->post('mydate') == null){
                $error = "Invalid date selected";
            }
            $date = $this->input->post('mydate');
            $days = $this->input->post('days');

            if($this->check_hall($data['hall_id'],$date,$days,$type,false) != "OK"){
                $error = "Hall is not available for the specified period. Please click on check availability to confirm";
            }

            if($error == ""){
                $data['start_date'] = database_date($date);
                $data['end_date'] = database_date($date,(86400 * ($days - 1)));
                d()->insert('booked_halls', $data);
                $id = d()->insert_id();
                $this->session->set_flashdata('flash_message', get_phrase('booked successfully'));
                return $this->hall_receipt($id);
            }

            $data['days'] = $this->input->post('days');
            $data['date'] = $this->input->post('date');
            $data['branch_id'] = $this->input->post('branch_id');;
//            $this->session->set_flashdata('flash_message', $error);

            die($error);
        }else{
            $row = d()->get_where('users',array("id"=>s()->userdata('login_user_id')));;
            if($row->num_rows()>0){
                $row = $row->row();
                $data['fname'] = $row->fname;
                $data['surname'] = $row->surname;
                $data['phone'] = $row->phone;
            }
        }


        $page_data['data'] = $data;
        $page_data['type'] = $type;
        $page_data['page_name'] = 'book_hall';
        $x = c()->ajaxBranch($type);
        $page_data['branch'] = $x[1];
        $page_data['branch_'] = $x[0];
        $page_data['page_title'] = get_phrase("$type reservation");
        $this->load->view('backend/mobile/index', $page_data);
    }


    function check_hall($hall_id=0,$date="",$days=0,$type = 'hall',$print=true){


        $type = ucwords($type);
        $halls = array();
        for($i = 0; $i < $days; $i++){
            $mydate = database_date($date,(86400 * $i));
            d()->order_by("date","ASC");
            d()->where("".d()->escape($mydate)." between start_date and end_date");
            $query = c()->get_where('booked_halls',"hall_id",$hall_id)->result_array();
            foreach($query as $row){
                $halls[$row['id']] = $row;
            }
        }

        if(count($halls) == 0) {
            if ($print) print "<b style='color: green;'>$type is <b style='color:red;'>available</b> to booking for the period
specified</b>"; else return "OK";
            return;
        }

        $str = "<h5>This $type is already booked for the period specified as follows:</h5>
<table class='table'>
<tr>";

        if($type == 'Hall')
            $str .= "<th>Event</th>";

        $str .= "<th>Start Date</th>
<th>End Date</th>
</tr>
";
        $count = 1;
        foreach($halls as $row){
            $str.= "<tr>";
            if($type == 'Hall')
                $str.= "<td>Event ".$count."</td>";
            $str.= "<td>".convert_to_date($row['start_date'])."</td>";
            $str.= "<td>".convert_to_date($row['end_date'])."</td>";
            $str.= "</tr>";
            $count++;
        }
        $str .= "</table><br><b>Please choose another day or another $type</b>";

        if($print) print $str; else return $str;
    }

    function transaction_history($param1 = '',$param2 = '')
    {
        $x = $this->validate_auth_key();
        if ($x != 'success')
            die($x);

        if($param1 == 'search'){
            $date1 = $this->input->post("date1");
            $date2 = $this->input->post("date2");
            return $this->transaction_history($date1,$date2);
        }

        $page_data['page_name'] = 'transaction_history';
        $page_data['date1'] = '';
        $page_data['date2'] = '';

        d()->order_by("date","DESC");
        if($param2 != ""){
            d()->where("date >=",database_date($param1)." 00:00:00");
            d()->where("date <=",database_date($param2)." 23:59:59");
            $page_data['date1'] = $param1;
            $page_data['date2'] = $param2;

        }else{
            d()->limit(100);
        }

        $page_data['history'] = c()->get_where("payments","user_id",s()->userdata("login_user_id"))->result_array();

        $page_data['page_title'] = get_phrase('transaction history');
        $this->load->view('backend/mobile/index', $page_data);
    }


    function view_booked_halls($type = 'hall',$hall_id = '', $param1 = '', $param2 = '')
    {
        $x = $this->validate_auth_key();
        if ($x != 'success')
            die($x);



        $credentials = array();
        if(!s()->hAccess('manage_halls')){
            $credentials['user_id'] = s()->userdata("login_user_id");
        }else {
            if($hall_id == '')
                $credentials['user_id'] = s()->userdata("login_user_id");
            else
                $credentials['hall_id'] = $hall_id;

        }



        if ($hall_id == 'delete') {
            unset($credentials['hall_id']);
            $credentials['id'] = $param1;
            $this->c_->where($credentials);
            $this->c_->delete('booked_halls');
            $this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
            redirect(base_url() . '?admin/view_booked_halls/'.$param2, 'refresh');
        }

        if($type == "hall"){

        }

        $page_data['page_title'] = get_phrase("reserved $type".'(s)');
        $page_data['page_name'] = 'view_booked_halls';
        $page_data['page_name'] = 'view_booked_halls';
        $page_data['type'] = $type;



        d()->where($credentials);
        d()->where('end_date <', date("Y/m/d"));
        d()->order_by('date','DESC');
        $expired = c()->get('booked_halls')->result_array();

        d()->where($credentials);
        d()->where('end_date >=', date("Y/m/d"));
        d()->order_by('date','DESC');
        $booked = c()->get('booked_halls')->result_array();

        $rows = c()->get_where('halls',"is_hall",$type == 'hall'?1:0)->result_array();
        foreach($rows as $row)
            $page_data['halls'][$row['id']] = $row;

        $page_data['booked_halls'] = array();
        $page_data['expired_halls'] = array();
        foreach($booked as $row){
            if(isset($page_data['halls'][$row['hall_id']]))
                $page_data['booked_halls'][] = $row;
        }

        foreach($expired as $row){
            if(isset($page_data['halls'][$row['hall_id']]))
                $page_data['expired_halls'][] = $row;
        }
        $this->load->view('backend/mobile/index', $page_data);
    }


    // authentication_key validation
    function validate_auth_key() {
            $email = $this->post_("username");
            $password = $this->post_("password");

            if(empty($email) || empty($password)){
                return "Invalid Email/Phone or Password";
            }
            $credential = array( 'password' => $password);

            if(c()->is_email($email)){
                $credential['email'] = $email;
            }else{
                $credential['phone'] = $email;
            }


            // Checking login credential for teacher
            $query = $this->c_->get_where('users', $credential);
            if ($query->num_rows() > 0) {
                $row = $query->row();

                $this->session->set_userdata('login_user_id', $row->id);
                $this->session->set_userdata('branch_id', $row->branch_id);
                $this->session->set_userdata('name',  $this->c_->get_full_name($row));
                $this->session->set_userdata('login_type', 'admin');
                $this->session->set_userdata('is_admin', $row->is_admin);
                $this->session->set_userdata('login_as', $row->is_admin?"admin":"member");
                $this->session->set_userdata('access', "login");
                $this->session->set_userdata('specific_access', $row->access);
                if ($row->disabled == 1){
                    $this->session->sess_destroy();
                    return "not allowed";
                }
                return 'success';
            }



            return 'Invalid Email/Phone or Password';
        }

    function post_($key){
        return $this->input->post($key);
    }

    function get_($key){
        return $this->input->get($key);
    }
    
}




