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

        // Authenticate data manipulation with the user level security key
        if ($this->validate_auth_key() != 'success')
            die;
    }

    function index(){

    }

    function trigger(){
        $this->db->list
    }

    // forgot password link
    function reset_password() {
        $response               = array();
        $response['status']     = 'false';
        $email                  = $_POST["email"];
        $reset_account_type     = '';

        //resetting user password here
        $new_password           =   substr( rand(100000000,20000000000) , 0,7);

        // Checking credential for admin
        $query = $this->db->get_where('admin' , array('email' => $email));
        if ($query->num_rows() > 0) 
        {
            $reset_account_type     =   'admin';
            $this->db->where('email' , $email);
            $this->db->update('admin' , array('password' => $new_password));
            $response['status']         = 'true';
        }
        // Checking credential for student
        $query = $this->db->get_where('student' , array('email' => $email));
        if ($query->num_rows() > 0) 
        {
            $reset_account_type     =   'student';
            $this->db->where('email' , $email);
            $this->db->update('student' , array('password' => $new_password));
            $response['status']         = 'true';
        }
        // Checking credential for teacher
        $query = $this->db->get_where('teacher' , array('email' => $email));
        if ($query->num_rows() > 0) 
        {
            $reset_account_type     =   'teacher';
            $this->db->where('email' , $email);
            $this->db->update('teacher' , array('password' => $new_password));
            $response['status']         = 'true';
        }
        // Checking credential for parent
        $query = $this->db->get_where('parent' , array('email' => $email));
        if ($query->num_rows() > 0) 
        {
            $reset_account_type     =   'parent';
            $this->db->where('email' , $email);
            $this->db->update('parent' , array('password' => $new_password));
            $response['status']         = 'true';
        }

        // send new password to user email  
        $this->email_model->password_reset_email($new_password , $reset_account_type , $email);


        echo json_encode($response);
    }

    // authentication_key validation
    function validate_auth_key() {

        /*
        * Ignore the authentication and returns success by default to constructor 
        * For pubic calls: login, forget password.
        * Pass post parameter 'authenticate' = 'false' to ignore the user level authentication
        */
        if ($this->input->post('authenticate') == 'false')
            return 'success';

        $response                       = array();
        $authentication_key             = $this->input->post("authentication_key");
        $user_type                      = $this->input->post("user_type");

        $query = $this->db->get_where($user_type, array('authentication_key' => $authentication_key));
        if ($query->num_rows() > 0) {
            $row = $query->row();

            $response['status']         =   'success';
            $response['login_type']     =   'admin';

            if ( $user_type == 'admin' )
                $response['login_user_id']  =   $row->admin_id;
            if ( $user_type == 'teacher' )
                $response['login_user_id']  =   $row->teacher_id;
            if ( $user_type == 'student' )
                $response['login_user_id']  =   $row->student_id;
            if ( $user_type == 'parent' )
                $response['login_user_id']  =   $row->parent_id;
            $response['authentication_key']=$authentication_key;


        }

        else {
            $response['status']         =   'failed';
        }

        //return json_encode($response);
        return $response['status'];
    }
    
}




