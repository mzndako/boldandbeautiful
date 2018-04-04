<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Domain_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function set_school_id($name = null){
        if($name == null){
            $name = isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:(isset($_SERVER['SERVER_NAME'])?$_SERVER['SERVER_NAME']:"");
        }

        $name = $this->pure_url($name);
        $schools = $this->db->get_where("schools",array("domain_name"=>$name));

        if($schools->num_rows() == 1){
            $GLOBALS['SCHOOL_ID'] = $schools->row()->school_id;
        }else{
            $GLOBALS['SCHOOL_ID'] = -1;
        }
    }

    function pure_url($url) {
        $url = preg_replace('/https?:\/\/|www./', '', strtolower($url));
        if ( strpos($url, '/') !== false ) {
            $ex = explode('/', $url);
            $url = $ex['0'];
        }
        return $url;
    }

    public function set_division($division_id = null){
        if($division_id == null)
            $division_id = $this->session->userdata('division_id');

        if(is_numeric($division_id)){
            $search['division_id'] = $division_id;
            $search['school_id'] = $GLOBALS['SCHOOL_ID'];
            $q = $this->db->get_where("division",$search);
            if($q -> num_rows() > 0){
                define("DIVISION_ID",$division_id);
                return;
            }
        }

        $q = $this->db->get_where("division",Array("school_id"=>$GLOBALS['SCHOOL_ID']));

        if($q -> num_rows() > 0){
            $division_id = $q -> row() -> division_id;
            $this->session->set_userdata('division_id', $division_id);
            define("DIVISION_ID",$division_id);
            return;
        }

        $this->db->insert("division",Array("name"=>"Default","school_id"=>$GLOBALS['SCHOOL_ID']));

        $this->set_division();
    }

    public function check_redirect($die = false){
        if($GLOBALS['SCHOOL_ID'] == -1)
            if($die)
                die("Access Denied");
            else
                redirect(base_url() . "?superadmin", 'refresh');
    }






}