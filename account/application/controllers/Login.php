<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* 	
 * 	@author : Joyonto Roy
 * 	30th July, 2014
 * 	Creative Item
 * 	www.freephpsoftwares.com
 * 	http://codecanyon.net/user/joyontaroy
 */

class Login extends CI_Controller {
    public $c_;

    function __construct() {
        parent::__construct();
        $this->load->model('crud_model');
        $this->load->database();
//        $this->load->model("domain_model");
        $this->load->library('session');
        $this->c_ = $this->crud_model;

//        $domain = null;
//        if ($this->session->userdata('superadmin') == 1){
//            $domain = $this->session->userdata('domain');
//        }
//        $this->domain_model->set_school_id($domain);

//        $this->domain_model->check_redirect();

//        $this->domain_model->set_division();
        /* cache control */
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 2010 05:00:00 GMT");
    }

    //Default function, redirects to logged in user area
    public function index() {
  
        if ($this->session->hAccess("login")){
            redirect(base_url() . '?admin/dashboard', 'auto');
        }

        $this->load->view('backend/login');
    }

    //Ajax login function 
    function ajax_login() {
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
    function validate_login($email = '', $password = '') {

        if(empty($email) || empty($password)){
            return "invalid";
        }
        $credential = array( 'password' => $password);

        if(c()->is_email($email)){
            $credential['email'] = $email;
        }else{
            $credential['phone'] = numbers($email);
            if(empty($credential['phone']))
                return "invalid";
        }


       // Checking login credential for teacher
        $query = $this->c_->get_where('users', $credential);
        if ($query->num_rows() > 0) {
            $row = $query->row();

            $this->session->set_userdata('login_user_id', $row->id);
            $this->session->set_userdata('name',  $this->full_name($row));
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



        return 'invalid';
    }

    function full_name($row){
        return ucwords($row->surname." ".$row->fname);
    }

    /*     * *DEFAULT NOR FOUND PAGE**** */

    function four_zero_four() {
        $this->load->view('four_zero_four');
    }

    // PASSWORD RESET BY EMAIL
    function forgot_password()
    {
        $this->load->view('backend/forgot_password');
    }

    function ajax_forgot_password()
    {
        $resp                   = array();
        $resp['status']         = 'false';
        $email                  = $_POST["email"];
        $reset_account_type     = '';
        //resetting user password here
        $new_password           =   substr( md5( rand(100000000,20000000000) ) , 0,7);

        // Checking credential for admin
        $credentials = array();
        if(c()->is_email($email)){
            $credentials['email'] = $email;
        }else{
            $email = numbers($email);;
            $credentials['phone'] = $email;
        }

        c()->where($credentials);

        $query	=	$this->db->get('users' );

        if ($query->num_rows() > 0 && !empty($email))
        {
            $reset_account_type     =   'users';
            $this->c_->where($credentials);
            $this->c_->update('users' , array('password' => $new_password));
            $this->email_model->password_reset_email($new_password , $reset_account_type , $email);


            $resp['status']         = 'true';
        }

        $resp['submitted_data'] = $_POST;
        // send new password to user email  


        echo json_encode($resp);
    }

    /*     * *****LOGOUT FUNCTION ****** */

    function logout() {
        $url = base_url();
            if ($this->session->userdata('superadmin') == 1 && $this->session->userdata('access') != ""){
                $url .= "?superadmin";

                $this->session->set_userdata("access","");
                $this->session->set_flashdata('logout_notification', 'logged_out_successfully_as_superadmin');
            }else {
                if ($this->session->userdata('superadmin') == 1)
                    $url .= "?superadmin";
                $this->session->sess_destroy();
                $this->session->set_flashdata('logout_notification', 'logged_out_successfully');

            }

        if($this->session->userdata('session_id') != ""){
            $this->c_->where("id",$this->session->userdata('session_id'));
            $this->c_->delete("ci_sessions");
        }

        redirect($url, 'refresh');
    }

}
