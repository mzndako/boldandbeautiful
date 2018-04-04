<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Crud_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function clear_cache() {
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    function get_type_name_by_id($type, $type_id = '', $field = 'name') {
          return $this->get_where($type, array($type . '_id' => $type_id        ))->row()->$field;
    }

    function get_parent_info($parent_id) {
        $query = $this->get_where('parent', array('parent_id' => $parent_id));
        return $query->row_array();
    }

    function get_all_students_for_parent($parent_id,$what = "class_id"){
        $students = $this->get_where("student",array("parent_id"=>$parent_id))->result_array();
        $ids = array();
        foreach($students as $row){
            $ids[] = $row[$what];
        }
        return $ids;
    }

    function get_ids($table,$where,$what){
        $students = $this->get_where($table,$where)->result_array();
        $ids = array();
        foreach($students as $row){
            $ids[] = $row[$what];
        }
        return $ids;
    }
    ////////STUDENT/////////////

    function get_students($class_id) {
        $query = $this->get_where('student', array('class_id' => $class_id));
        return $query->result_array();
    }

    function get_student_info($student_id) {
        $query = $this->get_where('student', array('student_id' => $student_id));
        return $query->row_array();
    }

    /////////TEACHER/////////////
    function get_teachers() {
        $query = $this->get('teacher');
        return $query->result_array();
    }

    function get_teachers_by($academic = true) {
        $query = $this->get_where('teacher',array(""));
        return $query->result_array();
    }

    function get_teacher_name($teacher_id) {
        $query = $this->get_where('teacher', array('teacher_id' => $teacher_id));
        $res = $query->result_array();
        foreach ($res as $row)
            return $this->get_full_name($row);
    }

    function get_teacher_info($teacher_id) {
        $query = $this->get_where('teacher', array('teacher_id' => $teacher_id));
        return $query->result_array();
    }

    //////////SUBJECT/////////////
    function get_subjects() {
        $query = $this->get('subject');
        return $query->result_array();
    }

    function get_subject_info($subject_id) {
        $query = $this->get_where('subject', array('subject_id' => $subject_id));
        return $query->result_array();
    }

    function get_subjects_by_class($class_id) {
        $query = $this->get_where('subject', array('class_id' => $class_id));
        return $query->result_array();
    }

    function get_subject_name_by_id($subject_id) {
        $query = $this->get_where('subject', array('subject_id' => $subject_id))->row();
        return $query->name;
    }

    ////////////CLASS///////////
    function get_class_name($class_id) {
        $query = $this->get_where('class', array('class_id' => $class_id));
        $res = $query->result_array();
        foreach ($res as $row)
            return $row['name'];
    }

    function get_class_name_numeric($class_id) {
        $query = $this->get_where('class', array('class_id' => $class_id));
        $res = $query->result_array();
        foreach ($res as $row)
            return $row['name_numeric'];
    }

    function get_classes() {
        $query = $this->get_where('class',array('school_id'=>$GLOBALS['SCHOOL_ID'],"division_id"=>DIVISION_ID));
        return $query->result_array();
    }

    function get_class_info($class_id) {
        $query = $this->get_where('class', array('class_id' => $class_id));
        return $query->result_array();
    }

    //////////EXAMS/////////////
    function get_exams() {
        $query = $this->get('exam');
        return $query->result_array();
    }

    function get_exam_info($exam_id) {
        $query = $this->get_where('exam', array('exam_id' => $exam_id));
        return $query->result_array();
    }

    function get_term($term_id) {
        $query = $this->where('term_id',$term_id);
        return $this->get("term");
    }

    //////////GRADES/////////////
    function get_grades() {
        $query = $this->get('grade');
        return $query->result_array();
    }

    function get_grade_info($grade_id) {
        $query = $this->get_where('grade', array('grade_id' => $grade_id));
        return $query->result_array();
    }

    function get_obtained_marks( $exam_id , $class_id , $subject_id , $student_id) {
        $marks = $this->get_where('mark' , array(
                                    'subject_id' => $subject_id,
                                        'exam_id' => $exam_id,
                                            'class_id' => $class_id,
                                                'student_id' => $student_id))->result_array();
                                        
        foreach ($marks as $row) {
            echo $row['mark_obtained'];
        }
    }

    function get_highest_marks( $exam_id , $class_id , $subject_id ) {
        $this->db->where('exam_id' , $exam_id);
        $this->db->where('class_id' , $class_id);
        $this->db->where('subject_id' , $subject_id);
        $this->db->select_max('mark_obtained');
        $highest_marks = $this->get('mark')->result_array();
        foreach($highest_marks as $row) {
            echo $row['mark_obtained'];
        }
    }

    function get_grade($mark_obtained) {
        $query = $this->get('grade');
        $grades = $query->result_array();
        foreach ($grades as $row) {
            if ($mark_obtained >= $row['mark_from'] && $mark_obtained <= $row['mark_upto'])
                return $row;
        }
    }

    function create_log($data) {
        $data['timestamp'] = strtotime(date('Y-m-d') . ' ' . date('H:i:s'));
        $data['ip'] = $_SERVER["REMOTE_ADDR"];
        $location = new SimpleXMLElement(file_get_contents('http://freegeoip.net/xml/' . $_SERVER["REMOTE_ADDR"]));
        $data['location'] = $location->City . ' , ' . $location->CountryName;
        $this->db->insert('log', $data);
    }

    function get_system_settings() {
        $query = $this->get('settings');
        return $query->result_array();
    }

    ////////BACKUP RESTORE/////////
    function create_backup($type) {
        $this->load->dbutil();


        $options = array(
            'format' => 'txt', // gzip, zip, txt
            'add_drop' => TRUE, // Whether to add DROP TABLE statements to backup file
            'add_insert' => TRUE, // Whether to add INSERT data to backup file
            'newline' => "\n"               // Newline character used in backup file
        );


        if ($type == 'all') {
            $tables = array('');
            $file_name = 'system_backup';
        } else {
            $tables = array('tables' => array($type));
            $file_name = 'backup_' . $type;
        }

        $backup = & $this->dbutil->backup(array_merge($options, $tables));


        $this->load->helper('download');
        force_download($file_name . '.sql', $backup);
    }

    /////////RESTORE TOTAL DB/ DB TABLE FROM UPLOADED BACKUP SQL FILE//////////
    function restore_backup() {
        move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/backup.sql');
        $this->load->dbutil();


        $prefs = array(
            'filepath' => 'uploads/backup.sql',
            'delete_after_upload' => TRUE,
            'delimiter' => ';'
        );
        $restore = & $this->dbutil->restore($prefs);
        unlink($prefs['filepath']);
    }

    /////////DELETE DATA FROM TABLES///////////////
    function truncate($type) {
        if ($type == 'all') {
            $this->db->truncate('student');
            $this->db->truncate('mark');
            $this->db->truncate('teacher');
            $this->db->truncate('subject');
            $this->db->truncate('class');
            $this->db->truncate('exam');
            $this->db->truncate('grade');
        } else {
            $this->db->truncate($type);
        }
    }

    function get_image_path($type){
        $x = $type == ""?"/":"/$type/";
        return "uploads/".$x;
    }

    function get_file_name($type,$id,$id_prefix = null){
        if($id_prefix == null || $id_prefix == ""){
            $id = $type."_".$id;
        }else
            $id = $id_prefix."_".$id;
        return $id.".jpg";
    }
    ////////IMAGE URL//////////
    function get_image_url($type = '', $id = '',$id_prefix = null) {

        $path = $this->get_image_path($type);
        $file = $this->get_file_name($type,$id,$id_prefix);

        $image = $path.$file;
        if (file_exists($image))
            $image_url = base_url() . $image;
        else {
            if($type == "") $type = "logo";
            $image_url = base_url() . 'uploads/' . $type . ".jpg";
        }

        return $image_url;
    }

    function move_image($source,$type,$id,$id_prefix=null){
        $path = $this->get_image_path($type);
        if(!is_dir($path)){
            mkdir($path, 0777, true);
        }
        $file = $this->get_file_name($type,$id,$id_prefix);
        return move_uploaded_file($_FILES[$source]['tmp_name'], $path.$file);
    }
    function construct_image($options){
        $type = isset($options['type'])?$options['type']:"student";
        $id = isset($options['id'])?$options['id']:-1;
        $id_prefix = isset($options['id_prefix'])?$options['id_prefix']:null;
        $onlyshow = isset($options['onlyshow'])?$options['onlyshow']:false;
        $name = isset($options['name'])?$options['name']:"image";
        $image_link = $this->get_image_url($type,$id,$id_prefix);
        if($onlyshow){
            return '
            <div>
            <a href="'.$image_link.'" title="Click to View" target="_blank"><img style="width: 100px; height: 100px;"
            src="'.$image_link.'"
            alt="'.$type.'"></a>
            </div>
            ';
        }
        return '
        <div class="fileinput fileinput-new" data-provides="fileinput" >
								<div data-validate=required data-message-required=Required class="fileinput-new thumbnail" style="width: 100px; height: 100px;" data-trigger="fileinput">
									<img src="'.$image_link.'" alt="...">
								</div>
								<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
								<div>
									<span class="btn btn-white btn-file">
										<span class="fileinput-new">Select image</span>
										<span class="fileinput-exists">Change</span>
										<input type="file" name="'.$name.'" accept="image/*">
									</span>
									<a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
								</div>
							</div>

        ';
    }

    ////////STUDY MATERIAL//////////
    function save_study_material_info()
    {
        $data['timestamp']      = strtotime($this->input->post('timestamp'));
        $data['title'] 		= $this->input->post('title');
        $data['description']    = $this->input->post('description');
        $data['file_name'] 	= $_FILES["file_name"]["name"];
        $data['file_type'] 	= $this->input->post('file_type');
        $data['class_id'] 	= $this->input->post('class_id');
        
        $this->insert('document',$data);
        
        $document_id            = $this->db->insert_id();
        move_uploaded_file($_FILES["file_name"]["tmp_name"], "uploads/document/" . $_FILES["file_name"]["name"]);
    }
    
    function select_study_material_info()
    {
        $this->db->order_by("timestamp", "desc");
        return $this->get('document')->result_array();
    }
    
    function select_study_material_info_for_student()
    {
        $student_id = $this->session->userdata('student_id');
        $class_id   = $this->get_where('student', array('student_id' => $student_id))->row()->class_id;
        $this->db->order_by("timestamp", "desc");
        return $this->get_where('document', array('class_id' => $class_id))->result_array();
    }
    
    function update_study_material_info($document_id)
    {
        $data['timestamp']      = strtotime($this->input->post('timestamp'));
        $data['title'] 		= $this->input->post('title');
        $data['description']    = $this->input->post('description');
        $data['class_id'] 	= $this->input->post('class_id');
        
        $this->db->where('document_id',$document_id);
        $this->db->update('document',$data);
    }
    
    function delete_study_material_info($document_id)
    {
        $this->db->where('document_id',$document_id);
        $this->db->delete('document');
    }

    function convert2text($message){
        $message = str_ireplace(array("<br>"),"\n",$message);
        return strip_tags($message);
    }

    ////////private message//////
    function send_new_private_message() {
        $message    = $this->input->post('message');
        $textmessage    = $this->convert2text($message);
        $timestamp  = strtotime(date("Y-m-d H:i:s"));

        $recievers   = $this->input->post('reciever');
        if(!is_array($recievers)) $recievers = array();

        $more   = $this->input->post('more');


        $email_subject   = $this->input->post('email_subject');
        $sms_subject   = $this->input->post('sms_subject');

        $send_email = $this->input->post('send_email') == 1?true:false;
        $send_sms = $this->input->post('send_sms') == 1?true:false;

        foreach($recievers as $reciever) {
            $sender = $this->session->userdata('login_user_id');

            //check if the thread between those 2 users exists, if not create new thread
            $num1 = $this->get_where('message_thread', array('sender' => $sender, 'reciever' => $reciever))->num_rows();
            $num2 = $this->get_where('message_thread', array('sender' => $reciever, 'reciever' => $sender))->num_rows();

            if ($num1 == 0 && $num2 == 0) {
                $message_thread_code = substr(md5(rand(100000000, 20000000000)), 0, 15);
                $data_message_thread['message_thread_code'] = $message_thread_code;
                $data_message_thread['sender'] = $sender;
                $data_message_thread['reciever'] = $reciever;
                $this->insert('message_thread', $data_message_thread);
            }
            if ($num1 > 0)
                $message_thread_code = $this->get_where('message_thread', array('sender' => $sender, 'reciever' => $reciever))->row()->message_thread_code;
            if ($num2 > 0)
                $message_thread_code = $this->get_where('message_thread', array('sender' => $reciever, 'reciever' => $sender))->row()->message_thread_code;


            $data_message['message_thread_code'] = $message_thread_code;
            $data_message['message'] = $message;
            $data_message['sender'] = $sender;
            $data_message['timestamp'] = $timestamp;
            $this->insert('message', $data_message);

            if($send_email){
                $email = user_data($reciever,"email");
                if($this->is_email($email))
                    $this->send_mail($this->replace_values($message,$reciever), $email_subject,$email,$reciever);
            }

            if($send_sms){
                $phone = user_data($reciever,"phone");
                if(is_numeric($phone))
                    $this->send_sms($this->replace_values($textmessage,$reciever), $sms_subject,$phone,$reciever);
            }
            // notify email to email reciever
            //$this->email_model->notify_email('new_message_notification', $this->db->insert_id());
        }
        if($more != null){
            $add = explode(",",$more);
            foreach($add as $number){
                $is_email = $this->is_email($number);
                if($is_email && $send_email){
                    $email = $number;
                    $this->send_mail($message, $email_subject,$email);
                }elseif($send_sms){
                    $phone = $number;
                    if(is_numeric($phone))
                        $this->send_sms($textmessage, $sms_subject,$phone);
                }
            }
        }

        return $message_thread_code;
    }

    function send_new_private_lesson($current_term) {
        $message    = $this->input->post('message');
        $timestamp  = strtotime(date("Y-m-d H:i:s"));

        $reciever   = "admin";
        $title    = $this->input->post('title');
        $sender     = $this->session->userdata('login_as') . '-' . $this->session->userdata('login_user_id');

        //check if the thread between those 2 users exists, if not create new thread
        $num1 = $this->get_where('lesson_thread', array('sender' => $sender, 'reciever' => $reciever,"term_id"=>$current_term))->num_rows();
        $num2 = $this->get_where('lesson_thread', array('sender' => $reciever, 'reciever' => $sender,"term_id"=>$current_term))->num_rows();

        if ($num1 == 0 && $num2 == 0) {
            $message_thread_code                        = substr(md5(time().rand(100000000, 20000000000)), 0, 15);
            $data_message_thread['message_thread_code'] = $message_thread_code;
            $data_message_thread['sender']              = $sender;
            $data_message_thread['reciever']            = $reciever;
            $data_message_thread['term_id']            = $current_term;
            $this->insert('lesson_thread', $data_message_thread);
        }

        if ($num1 > 0)
            $message_thread_code = $this->get_where('lesson_thread', array('sender' => $sender, 'reciever' => $reciever,"term_id"=>$current_term))->row()->message_thread_code;
        if ($num2 > 0)
            $message_thread_code = $this->get_where('lesson_thread', array('sender' => $reciever, 'reciever' => $sender,"term_id"=>$current_term))->row()->message_thread_code;


        $data_message['message_thread_code']    = $message_thread_code;
        $data_message['message']                = $message;
        $data_message['sender']                 = $sender;
        $data_message['title']                  = $title;
        $data_message['timestamp']              = $timestamp;
        $data_message['term_id']              = $current_term;
        $this->insert('lesson', $data_message);

        // notify email to email reciever
        //$this->email_model->notify_email('new_message_notification', $this->db->insert_id());

        return $message_thread_code;
    }

    function send_reply_message($message_thread_code) {
        $message    = $this->input->post('message');
        $timestamp  = strtotime(date("Y-m-d H:i:s"));
        $sender     = $this->session->userdata('login_as') . '-' . $this->session->userdata('login_user_id');


        $data_message['message_thread_code']    = $message_thread_code;
        $data_message['message']                = $message;
        $data_message['sender']                 = $sender;
        $data_message['timestamp']              = $timestamp;
        $this->insert('message', $data_message);

        // notify email to email reciever
//        $this->email_model->notify_email('new_message_notification', $this->db->insert_id());
    }

    function send_reply_lesson($message_thread_code) {
        $message    = $this->input->post('message');
        $timestamp  = strtotime(date("Y-m-d H:i:s"));
        $sender     = $this->session->userdata('login_as') . '-' . $this->session->userdata('login_user_id');


        $data_message['message_thread_code']    = $message_thread_code;
        $data_message['message']                = $message;
        $data_message['sender']                 = $sender;
        $data_message['timestamp']              = $timestamp;
        $this->insert('lesson', $data_message);

        // notify email to email reciever
//        $this->email_model->notify_email('new_message_notification', $this->db->insert_id());
    }

    function mark_thread_messages_read($message_thread_code) {
        // mark read only the oponnent messages of this thread, not currently logged in user's sent messages
        $current_user = $this->session->userdata('login_as') . '-' . $this->session->userdata('login_user_id');
        $this->db->where('sender !=', $current_user);
        $this->db->where('message_thread_code', $message_thread_code);
        $this->db->update('message', array('read_status' => 1));
    }

    function count_unread_message_of_thread($message_thread_code) {
        $unread_message_counter = 0;
        $current_user = $this->session->userdata('login_as') . '-' . $this->session->userdata('login_user_id');
        $messages = $this->get_where('message', array('message_thread_code' => $message_thread_code))->result_array();
        foreach ($messages as $row) {
            if ($row['sender'] != $current_user && $row['read_status'] == '0')
                $unread_message_counter++;
        }
        return $unread_message_counter;
    }

    function mark_thread_lesson_read($current_term,$message_thread_code,$isadmin) {
        // mark read only the oponnent messages of this thread, not currently logged in user's sent messages
        if($isadmin)
            $type = "read_status";
        else
            $type = "user_read_status";

        $current_user = $this->session->userdata('login_as') . '-' . $this->session->userdata('login_user_id');
//        $this->db->where('sender !=', $current_user);
        $this->db->where('message_thread_code', $message_thread_code);
        $this->db->where('term_id', $current_term);
        $this->db->update('lesson', array($type => 1));
    }

    function count_unread_lesson_of_thread($message_thread_code,$isadmin) {
        $unread_message_counter = 0;

        $current_user = $this->session->userdata('login_as') . '-' . $this->session->userdata('login_user_id');

        $messages = $this->get_where('lesson', array('message_thread_code' => $message_thread_code))->result_array();
        foreach ($messages as $row) {
            if($isadmin) {
                if ($row['sender'] != $current_user && $row['read_status'] == '0')
                    $unread_message_counter++;
            }else{
                if($row['user_read_status'] == '0')
                    $unread_message_counter++;
            }
        }
        return $unread_message_counter;
    }

    function get_setting($key,$value="",$division_id=-1){
        if($division_id == null){
            $division_id = DIVISION_ID == null?-1:DIVISION_ID;
        }


        $q = $this->db->get_where('settings' , array('type'=>$key));
        if($q -> num_rows() > 0){
            return $q -> row() -> description;
        }
        $data['type'] = $key;
        $data['description'] = $value;
        $this->db->insert("settings",$data);
        return $value;
    }

    function set_setting($key,$value,$division_id=-1){
        if($division_id == null){
            $division_id = DIVISION_ID == null?-1:DIVISION_ID;
        }
        $value = $value == null?"":$value;
        $q = $this->db->get_where('settings' , array('type'=>$key));
        if($q -> num_rows() > 0){
            $this->db->where("type",$key);
            $this->db->update("settings",array('description'=>$value));
            return true;
        }
        $data['type'] = $key;
        $data['description'] = $value;
        $this->db->insert("settings",$data);
        return true;
    }

    function where($key,$value=null){
        return $this->db->where($key,$value);
    }

    function get($table){
        return $this->get_where($table,array());
    }

    function get_where($table,$config,$value=null){
        if(!is_array($config)){
            $key = $config;
            $config = array();
            $config[$key] = $value;
        }


        return $this->db->get_where($table,$config);
    }

    function insert($table,$config){

        return $this->db->insert($table,$config);
    }

    function update($table,$data){
        return $this->db->update($table,$data);
    }

    function delete($table){
       return $this->db->delete($table);
    }

    function insert_id(){
        return $this->db->insert_id();
    }


    function insert_batch($table,$data){
        return $this->db->insert_batch($table,$data);
    }

    function previous_term($term_id){
        $session_id = $this->get_where("term", array("term_id" => $term_id))->row()->year_id;

        $sessions = $this->get_where("year",array("year_id"=>$session_id))->row();

        $my_order = $sessions -> myorder;

        $terms = $this->get_where("term",array("year_id"=>$session_id))->result_array();

        $terms = $this->rearrange($terms,$my_order,"term_id");

        $count = 0;
        foreach($terms as $term){
            if($term['term_id'] == $term_id){
                break;
            }
            $count++;
        }
        $count--;
        return isset($terms[$count])?$terms[$count]['term_id']:0;
    }

    function get_students_result($term_id,$class_id){

        $exams = $this->get_where("exam",array("class_id"=>$class_id))->result_array();

        $session_id = $this->get_where("term", array("term_id" => $term_id))->row()->year_id;

        $sessions = $this->get_where("year",array("year_id"=>$session_id))->row();

        $my_order = $sessions -> myorder;

        $terms = $this->get_where("term",array("year_id"=>$session_id))->result_array();

        $terms = $this->rearrange($terms,$my_order,"term_id");

        $subjects = $this->get_where("subject",array("class_id"=>$class_id))->result_array();

        $students = $this->get_where("student",array("class_id"=>$class_id))->result_array();


        $my_mark = array();
        $marks = 	$this->get_where('mark' , array(
            'class_id' => $class_id
        ))->result_array();

        $my_exams = array();
        foreach($exams as $exam){
            $my_exams[$exam['exam_id']] = $exam['term_id'];
        }

        foreach($marks as $mark){
            $score = $mark['mark_obtained'];
            if(isset($my_exams[$mark['exam_id']])){
                $my_mark[$mark['student_id']][$my_exams[$mark['exam_id']]][$mark['subject_id']][$mark['exam_id']] = $score;
            }
        }

        $real_exams = array();
        foreach($exams as $exam){
            $real_exams[$exam['term_id']][] = $exam;
        }

        $pos = array();
        $myp = array();
        foreach($students as $student){
            $total = 0;

            foreach($terms as $term) {
                $term_total = 0;
                $my_mark[$student['student_id']][$term['term_id']]['per_total'] = 0;
                foreach ($subjects as $subject) {
                    $subtotal = 0;
                    $real_myexam = isset($real_exams[$term['term_id']])?$real_exams[$term['term_id']]:array();
                    foreach ($real_myexam as $exam) {
                        if (!isset($my_mark[$student['student_id']][$term['term_id']][$subject['subject_id']][$exam['exam_id']])) {
                            $my_mark[$student['student_id']][$term['term_id']][$subject['subject_id']][$exam['exam_id']] = 0;
                        }
                        $score = $my_mark[$student['student_id']][$term['term_id']][$subject['subject_id']][$exam['exam_id']];

                        $subtotal += $score;
                        $total += $score;
                        $term_total += $score;
                        @$my_mark[$student['student_id']][$term['term_id']]['per_total'] += $exam['mark'];

                    }

                    $my_mark[$student['student_id']][$term['term_id']][$subject['subject_id']]['total'] = $subtotal;

                }

                $myp['term_'.$term['term_id']][$student['student_id']] = $term_total;
                $myp['com_term_'.$term['term_id']][$student['student_id']] = $total;

                $my_mark[$student['student_id']][$term['term_id']]['total'] = $term_total;
                $my_mark[$student['student_id']][$term['term_id']]['com_total'] = $total;

                $grade_total = $my_mark[$student['student_id']][$term['term_id']]['per_total'];
                $my_mark[$student['student_id']][$term['term_id']]['per_total'] = $this->percentage($term_total,
                    $grade_total);
            }

            $my_mark[$student['student_id']]['total'] = $total;

        }


        foreach($terms as $term) {
            $this->getPosition($my_mark,$myp,'term_'.$term['term_id']);
            $this->getPosition($my_mark,$myp,'com_term_'.$term['term_id']);
        }

        return $my_mark;
        arsort($pos);

        $newp = 0;
        $prevp = 0;
        $pt = 0;
        foreach($pos as $id => $p){
            $newp++;
            if($pt == $p){
                $my_mark[$id]['position'] = $prevp;
            }else{
                $my_mark[$id]['position'] = $newp;
                $prevp = $newp;
            }

            $pt = $p;
        }

    }

    function getPosition(&$my_mark,$myp,$suffix){
        $pos = $myp[$suffix];
        arsort($pos);
        $newp = 0;
        $prevp = 0;
        $pt = 0;
        foreach($pos as $id => $p){
            $newp++;
            if($pt == $p){
                $my_mark[$id][$suffix] = $prevp;
            }else{
                $my_mark[$id][$suffix] = $newp;
                $prevp = $newp;
            }

            $pt = $p;
        }
    }

    function percentage($top,$botton){
        if($botton == 0 || $top == 0)
            return 0;
        return round(($top/$botton * 100),2);
    }

    function rearrange($array, $order, $id){
        if(!is_array($order))
            $order = explode(",",$order);

        $new_array = array();

        foreach($order as $x){
            foreach($array as $row){
                if(isset($row[$id]) && $row[$id] == $x){
                    $new_array[] = $row;
                    break;
                }
            }
        }

        if(count($new_array) != count($array)){
            foreach($array as $row){
                $p = false;
                foreach($new_array as $row_new){
                    if($row_new[$id] == $row[$id]){
                        $p = true;
                        break;
                    }
                }
                if(!$p)
                    $new_array[] = $row;
            }
        }

        return $new_array;
    }

    function ajaxSession(){
        $ajaxSession = Array();
        $myyears = $this->get('year')->result_array();
        foreach ($myyears as $year) {
            $terms = $this->get_where('term',array('year_id'=>$year['year_id']))->result_array();
            $terms = $this->rearrange($terms,$year['myorder'],"term_id");
            foreach($terms as $term){
                $ajaxSession[$year['year_id']][] = array("id"=>$term['term_id'],"name"=>$term['name']);;
            }
        }
        return $ajaxSession;
    }

    function ajaxBranch($type){
        $ajaxSession = Array();
        d()->order_by("name","ASC");
        $myyears = $this->get('branch')->result_array();
        $branch = array();
        foreach ($myyears as $year) {
            d()->order_by("name","ASC");
            d()->where("is_hall",$type=='hall'?1:0);
            $terms = $this->get_where('halls',array('branch_id'=>$year['id']))->result_array();
            $branch[$year['id']] = $year;
            foreach($terms as $term){
//                $branch[$year['id']] = $year;
                $ajaxSession[$year['id']][] = array("id"=>$term['id'],"name"=>$term['name'],"capacity"=>$term['capacity'],"amount"=>$term['amount']);;
            }
        }


        return array($ajaxSession,$branch);
    }

 function ajaxSpecs(){
        $ajaxSession = Array();
        d()->order_by("name","ASC");
        $myyears = $this->get('specializations')->result_array();
        $branch = array();
        foreach ($myyears as $year) {
            d()->order_by("name","ASC");
            $terms = $this->get_where('services',array('specialization'=>$year['id'],"deleted"=>0))->result_array();
            $branch[$year['id']] = $year;
            foreach($terms as $term){
//                $branch[$year['id']] = $year;
                $ajaxSession[$year['id']][] = array("id"=>$term['id'],"name"=>$term['name'], "amount"=>$term['amount']);;
            }
        }


        return array($ajaxSession,$branch);
    }

    function print_list_terms($current_term = ""){
        echo '
        var currentterm = "',$current_term,'";
        var currentterm2 = currentterm;
        var session = ', json_encode($this->ajaxSession()),';
        function list_terms(ses,term){
            if(ses === undefined)
                var term_id = $("#session").val();
            else
                var term_id = $("#"+ses).val();
            if(term === undefined)
                 $el = $("#term");
            else
                 $el = $("#"+term);
            try {
                $el.html("");
                var lop = session[term_id];
                $.each(lop, function (key, value) {
                    if(currentterm == value.id){
                        $el.append($("<option selected></option>")
                            .attr("value", value.id).text(value.name));
                    }else{
                        $el.append($("<option></option>")
                            .attr("value", value.id).text(value.name));
                    }
                });
            } catch (e) {}
            }
            list_terms();
            currentterm = "";
        ';
    }

    function all_access($id = null){
        $array = "login,view_appointments,can_sign_in,can_sign_out,manage_settings,manage_alerts,manage_members,manage_admin,make_payment,manage_expenditures,view_payments,view_all_payments,manage_promos,manage_products,send_message,view_messages,view_sent_messages";
        $array = explode(",",$array);
        if($id != null){
            return isset($array[$id])?$array[$id]:"";
        }
        return $array;
    }

    function convert_permission($perm,$asArray=false){
        $x = explode(",",$perm);
        foreach($x as &$p){
            $p = ucwords(str_replace("_"," ",$p));
        }
        return !$asArray?implode(", ",$x):$x;
    }

    function isStudent(){
        return $this->session->userdata("login_as") == "student";
    }

    function isTeacher($check_is_not_admin = false){
        if($check_is_not_admin)
            return $this->session->userdata("login_as") == "teacher" && $this->session->userdata("is_admin") == 0;

        return $this->session->userdata("login_as") == "teacher";
    }

    function isAdmin(){
        return $this->session->userdata("login_as") == "admin" || $this->session->userdata("is_admin") == 1;
    }

    function isParent(){
        return $this->session->userdata("login_as") == "parent";
    }

    function get_members_form2($options = array("required_all"=>false)){
        $division_id = isset($options['required_all'])?$options['required_all']:DIVISION_ID;
        $class = $this->get_where("branch")->result_array();
        $class_ids = array();
        foreach($class as $row){
            $class_ids[$row['class_id']] = $row['name'];
        }

        $parent = $this->get("users")->result_array();
        $parent_id = array();
        foreach($parent as $row){
            $parent_id[$row['parent_id']] = $row['name'];
        }
        $required_all = isset($options['required_all'])?$options['required_all']:true;

        $except = isset($options['except'])?explode(",",$options['except']):array();

        $fields = $this->db->list_fields("users");
        $change = array(
            "id"=>array("type"=>"hidden"),
            "surname" => array("label"=>"Surname","required"=>true),
            "fname" => array("label"=>"First Name","required"=>true),
            "mname" => array("label"=>"Middle Name"),
//            "parent_id" => array("label"=>"Parent","type"=>"select","options"=>$parent_id),
            "sex" => array("type"=>"radio", "options"=>array("male","female")),
            "lga" => array("label"=>"LGA"),
            "password" => array("type"=>"password"),
            "others" => array("label"=>"Other Relevant Information"),
            "permanent_address" => array("type"=>"textarea"),
            "primary_language" => array("type"=>"text"),
            "religion" => array("type"=>"select","options"=>array("muslim"=>"Muslim","christian"=>"Christian","others"=>"Others")),
            "last_school" => array("type"=>"text", "label"=>"Name and Address of Last School Attended"),
            "last_school_duration" => array("type"=>"text", "label"=>"Duration in last school"),
            "last_school_reason" => array("type"=>"text", "label"=>"Reason for leaving"),
            "class_id" => array("type"=>"select", "label"=>"Class Appling For", "options"=>$class_ids),
        );

        $skip = array("school_id","division_id","dormitory_id","dormitory_room_number","access","dirty","deleted","rowversion","transport_id");

        $studentform = array();

        foreach($fields as $field){

            if(in_array($field,$skip))
                continue;

            if(isset($options['student'])){
                $student = $options['student'];
                $studentform[$field]['value'] = isset($student[$field])?$student[$field]:"";
            }
            $studentform[$field]['type'] = "text";
            $studentform[$field]['label'] = ucwords(str_replace(array("c1","c2","_","address1","address2"),array("",""," ","Home Address","Office Address"),$field));

            if($required_all){
                $studentform[$field]['required'] = !in_array($field,$except);
            }else{
                $studentform[$field]['required'] = in_array($field,$except);
            }
            if(isset($change[$field])){
                $row = $change[$field];
                if(isset($row['type'])){
                    $studentform[$field]['type'] = $row['type'];
                }
                if(isset($row['label'])){
                    $studentform[$field]['label'] = $row['label'];
                }
                if(isset($row['options'])){
                    $studentform[$field]['options'] = $row['options'];
                }

                if(isset($row['required'])){
                    $studentform[$field]['required'] = $row['required'];
                }


            }
        }
        return $studentform;

    }

    function create_input($options){
        $name = isset($options['name'])?$options['name']:"";
        $label = isset($options['label'])?$options['label']:"Value";
        $type = isset($options['type'])?$options['type']:"text";
        $value = isset($options['value'])?$options['value']:"";
        $class = isset($options['class'])?$options['class']:"form-control";
        $showclass = isset($options['showclass'])?$options['showclass']:"text-warning";
        $required = isset($options['required']) && $options['required']?"data-validate='required' data-message-required='$label Required'":"";
        $op = isset($options['options'])?$options['options']:array();
        $onlyshow = isset($options['onlyshow'])?$options['onlyshow']:false;

        if($onlyshow){
            $show = $value;
            if($type == "select" || $type == "radio" || $type == "checkbox"){
                $show = "";
                foreach($op as $k => $v){
                    if($k == $value){
                        $show = ucwords($v); break;
                    }
                }
            }

            if($type == "password"){
                $show = "*********";
            }

            if($type == "image"){
                $options['type'] = $options['type_'];
                return $this->construct_image($options);
            }

            return "<span class='$showclass form-control' style='border: none; color: #4e1c1c;'>$show</span>";
        }

        $value = is_array($value)?$value:htmlspecialchars($value);

        if($type == "textarea"){
            return "<textarea class='$class' rows='4' name='$name' $required>$value</textarea>";
        }

        if($type == "select"){
            $multiple = "";
            if(isset($options['multiple']) && $options['multiple']){
                $multiple = "multiple='multiple'";
                $class .= " select2";
            }
            $str = "<select class='$class' $multiple $required name='$name'>";
            if($multiple == ""){
                $str .= "<option value=''>Select $label</option>";
            }
            foreach($op as $k => $v){
                if(is_array($value)){
                    $s = in_array(strtolower(str_replace(" ","_",$v)),$value)?"selected":"";
                }else
                    $s = strtolower($k) == strtolower($value)?"selected":"";

                $str .= "<option $s value='$k'>".ucwords($v)."</option>";
            }
            $str .= "</select>";
            return $str;
        }

        if($type == "checkbox" || $type == "radio"){
            $str = "";
            $name = isset($options['multiple'])?$name."[]":$name."";
            foreach($op as $v){
                $v1 = ucwords($v);
                if(is_array($value)){
                    $s = in_array($v,$value)?"checked=checked":"";
                }else
                    $s = $v == $value?"checked=checked":"";
                $str .= "<div class='$type'><label><input $s type='$type' name='$name'
value='$v'
 $required> $v1 </label></div>";
            }
            return $str."";
        }

        if($type == "password"){
            $width = 70;
            if($value == ""){
                $width = 100;
            }
            return "<input style='width: $width%; display: inline-block' ".($value== ""?"":"disabled='disabled'")." type='$type'
 value='**********'
name='$name'
id='$name' class='$class' $required >".($value == ""?"":"<input style='width: 29%; margin-left: 1%; display: inline-block; '
type='button'
class='btn btn-success' onclick=\"$('#$name').removeAttr('disabled').val('').attr('required','required');\"
value='change'>");
        }

        if($type == "image"){
            $options['type'] = $options['type_'];
            return $this->construct_image($options);
        }

        return "<input type='$type' value='$value' name='$name' id='$name' class='$class'
$required >";
    }

    function app2student_id($appid){
        return (int) str_replace("app-","",strtolower($appid));
    }

    function app_id($student_id){
        return "APP-".$student_id;
    }

    function get_full_name($row){
        if(is_object($row)) {
            if(!isset($row->surname)){
                return ucwords($row->name);
            }
            return ucwords($row->surname . " " . $row->fname . " " . $row->mname);
        }else if(is_array($row)) {
            if (!isset($row['surname']))
                return ucwords($row['name']);
            return ucwords($row['surname'] . " " . $row['fname'] . " " . $row['mname']);
        }
        return $row;
    }

    function get_short_name($row){
        if(is_object($row)) {
            if(!isset($row->surname)){
                return ucwords($row->name);
            }
            return ucwords($row->surname . ", " . $this->short_name($row->fname)  . $this->short_name($row->mname));
        }
        if(!isset($row['surname']))
            return ucwords($row['name']);

        return ucwords($row['surname'].", ".$this->short_name($row['fname']).$this->short_name($row['mname']));
    }

    function short_name($name){
        if(strlen($name) > 0)
            return strtoupper($name[0]).".";
        return "";

    }

    function get_members_form($options = array("required_all"=>false,'is_admin'=>false)){

        $my_access = $this->all_access();
        foreach($my_access as $k => $v)
            $my_access[$k] = str_replace("_"," ",ucwords($v));


        $specs = $this->get("specializations")->result_array();
        $specs_id = array();
        foreach($specs as $row){
            $specs_id[$row['id']] = $row['name'];
        }

        $required_all = isset($options['required_all'])?$options['required_all']:true;

        $years = array();

        for($i = 1959; $i<=date("Y");$i++){
            $years[$i] = $i;
        }

        $except = isset($options['except'])?explode(",",$options['except']):array();

        $fields = $this->db->list_fields("users");
        $change = array(
            "id"=>array("type"=>"hidden"),
            "surname" => array("label"=>"Surname","required"=>true),
            "fname" => array("label"=>"First Name","required"=>true),
            "mname" => array("label"=>"Middle Name"),
            "specialization" => array("required"=>true,"label"=>"Specialization","type"=>"select","options"=>$specs_id),
            "sex" => array("type"=>"radio", "options"=>array("male","female")),
//            "marital_status" => array("type"=>"radio", "options"=>array("single","married")),
            "year_of_enrolment" => array("type"=>"select", "options"=>$years,"default"=>date('Y')),
            "lga" => array("label"=>"LGA"),
            "date_registered" => array("type"=>"date"),
            "birthday" => array("type"=>"date"),
            "password" => array("type"=>"password"),
            "birthday" => array("type"=>"date"),
            "address" => array("type"=>"textarea"),
            "residential_address" => array("type"=>"textarea"),
            "permanent_address" => array("type"=>"textarea"),
            "access" => array("type"=>"checkbox","name"=>"access[]","options"=>$my_access,"multiple"=>true, "label"=>"Specific Access")


        );

        $skip = array("school_id","division_id","documents","dormitory_id","dormitory_room_number","dirty","deleted","rowversion","transport_id");

        $studentform = array();

        foreach($fields as $field){

            if(in_array($field,$skip))
                continue;

            if(isset($options['members'])){
                $student = $options['members'];
                $studentform[$field]['value'] = isset($student[$field])?$student[$field]:"";
                if($field == "access"){
                    $studentform[$field]['value'] = explode(",",isset($student[$field])?$student[$field]:"");
                    foreach($studentform[$field]['value'] as $k => $v)
                        $studentform[$field]['value'][$k] = str_replace("_"," ",ucwords($v));
                }
            }

            $studentform[$field]['type'] = "text";
            $studentform[$field]['label'] = ucwords(str_replace(array("c1","c2","_","address1","address2"),array("",""," ","Home Address","Office Address"),$field));

            $studentform[$field]['label'] = ucwords(str_replace(array("Wife1 ","Wife2 ","wWife3 ","Wife4 "),array("","","",""), $studentform[$field]['label']));

            $studentform[$field]['name'] = $field;
            if($required_all){
                $studentform[$field]['required'] = !in_array($field,$except);
            }else{
                $studentform[$field]['required'] = in_array($field,$except);
            }

            if(isset($change[$field])){
                $row = $change[$field];
                if(isset($row['type'])){
                    $studentform[$field]['type'] = $row['type'];
                }
                if(isset($row['label'])){
                    $studentform[$field]['label'] = $row['label'];
                }
                if(isset($row['options'])){
                    $studentform[$field]['options'] = $row['options'];
                }

                if(isset($row['required'])){
                    $studentform[$field]['required'] = $row['required'];
                }

                if(isset($row['multiple'])){
                    $studentform[$field]['multiple'] = $row['multiple'];
                }

                if(isset($row['name'])){
                    $studentform[$field]['name'] = $row['name'];
                }

                if(isset($row['default']) && !isset($studentform[$field]['value'])){
                    $studentform[$field]['value'] = $row['default'];
                }

            }
 }
        return $studentform;

    }

    function teacher_profession($id){
        $x = "Guardner/nursery,Minder,Head Teacher,Head Master,Administrative Secretary,Security,Liberian,Teacher,Admin & Account,Transporter";
        $p = explode(",",$x);
        if($id != "all")
            return @$p[$id];
        return $p;
    }

    function get_session($term_id){
        $row = $this->c_->get_where("term", array("term_id" =>   $term_id));
        if($row->num_rows() > 0){
            return $row->row()->year_id;
        }
        return "";
    }

    function detail_exit($detail,$where = "email",$andid=""){
        $credential = array($where => $detail);


        if($detail == null || $detail == ""){
            return true;
        }

           // Checking login credential for teacher
        if($andid != "") $this->where('id !=', $andid);

        $query = $this->get_where('users', $credential);
        if ($query->num_rows() > 0) {
            return true;
        }

        return false;
    }

    function is_email($email){
        return !(filter_var(trim($email),FILTER_VALIDATE_EMAIL) === false);
    }

    function get_option_type($value = ""){
        $x = array("Today","This Week","This Month","Specific Date");
        if($value == "")
            return $x;
        return indexOf($value,$x);
    }

    function get_view_type($value = ""){
        $x = array("Income","Expenditure", "Income & Expenditure");
        if($value == "")
            return $x;
        return indexOf($value,$x);
    }

    function get_view_type2($value = ""){
        $x = array("SMS","Emails");
        if($value == "")
            return $x;
        return indexOf($value,$x);
    }

    function get_attendance($mtype,$term_id,$type,$startdate,$enddate){
        $atd = array();

        if($mtype == 'student'){
            $table = "attendance";
            $myid = 'student_id';
        }else{
            $table = "attendance_staff";
            $myid = 'teacher_id';
        }
        if($this->get_option_type("today") == $type){
            $startdate = date("Y/m/d");
            $enddate = date("Y/m/d");
        }

        if($this->get_option_type("this week") == $type){
            $monday = strtotime('last monday', strtotime('tomorrow'));
            $startdate = date("Y/m/d",$monday);
            $enddate = date("Y/m/d");
        }

        if($this->get_option_type("this month") == $type){
            $startdate = date("Y/m")."/01";
            $enddate = date("Y/m/d");
        }

        d()->order_by("date","ASC");

        if($this->get_option_type("this term") == $type){
            $query = $this->get_where($table,"term_id",$term_id)->result_array();
        }else{
            $this->where("date >=",database_date($startdate));
            $this->where("date <",database_date($enddate,86400));
            $query = $this->get_where($table,"term_id",$term_id)->result_array();
        }

        $date = array();
        $total = array();
        foreach($query as $row){
            $status = $row['status'] == 1?1:0;
            $atd[$row['date']][$row[$myid]] = $status;

            if(!in_array($row['date'],$date))
                $date[] = $row['date'];


            $total[$row[$myid]] = isset($total[$row[$myid]])?$total[$row[$myid]] + $status:$status;
        }

        return array("dates"=>$date,"attendance"=>$atd,"total"=>$total,"max"=>@max($total));
    }

    function is_hall($hall_id){
        $is_hall = getIndex(d()->get_where("halls",'hall_id',$hall_id)->row_array(),'is_hall');
        return $is_hall == 1;
    }

    function deleteAll($tables,$search,$id,$cred = array()){
        $tables_ = explode(",",$tables);
        foreach($tables_ as $table){
            if(!empty($cred)) d()->where($cred);

            d()->where($search, $id);
            d()->delete($table);
        }
    }

    function deleteBookedHalls(){
        d()->query("DELETE FROM booked_halls  WHERE NOT EXISTS (SELECT *
            FROM halls
            WHERE id=booked_halls.hall_id)");
    }

    function register($values,$allowNullEmail = false){
        $data = array();
        foreach($values as $k => $v){
            $data[$k] = $v;
        }

        if(empty($data['surname']) && empty($data['fname'])){
            return "Surname or First Name can not both be empty";
        }

        if(isset($data['email'])){

            if(!$this->is_email($data['email'])){
                return "Invalid Email Address Registered";
            }

            if(strlen($data['email']) > 0)
                if($this->detail_exit($data['email'],"email")) return "Email Address Already Exit";
            elseif(strlen($data['email']) == 0 && !$allowNullEmail)
                if($this->detail_exit($data['email'],"email")) return "Email Address Already Exit";
        }

        if(isset($data['phone'])){
            $data['phone'] = numbers($data['phone'],$no);

            if($no != 1)
                return "Invalid Phone Number Entered";

            if($this->detail_exit($data['phone'],"phone")) return "Phone Number Already Exit";
        }else{
            return "Please enter a valid phone number";
        }

        if(!isset($data['password'])){
            $data['password'] = c()->get_setting("members_default_password","123456");
        }

        if(empty($data['password'])){
            return "Password can not be empty";
        }

        d()->insert("users",$data);

        return d()->insert_id();

    }

    function replace_values($text,$member,$addition=array(),$bracket=array("[","]")){
        $x = $bracket[0];
        $y = $bracket[1];

        foreach($addition as $key => $value){
            $text = str_ireplace("$x$key$y",$value,$text);
        }

        d()->where("id",$member);
        $z = d()->get("users");

        if($z->num_rows() > 0){
            $fields = $z->row_array();

            foreach($fields as $key => $value){
                if(empty($value))
                    $value = "";
                $text = str_ireplace($x.$key.$y,$value,$text);
            }
        }


        return $text;
    }

    function send_mail($message,$subject,$to,$customer = 0,$from = null,$save = true){
        if($message == "")
            return "";

        $x =  $this->email_model->do_email($message,$subject,$to,$from);
        $staff_id = $this->session->userdata("login_user_id");
        if($save) {
            $data['staff_id'] = $staff_id > 0?$staff_id:0;
            $data['user_id'] = $customer;
            $data['subject'] = $subject;
            $data['message'] = $message;
            $data['recipients'] = $to;
            $data['status'] = "Sent";
            $data['date'] = gdate();
            d()->insert("sent_mail",$data);
        }
        return $x;
    }

    function send_sms($message, $sender_id,$to,$customer = 0, $save = true){
        if($message == "")
            return false;
        $to = numbers($to);

        $api = replaceV(get_setting("sms_api"),$customer,array("message"=>urlencode($message),"sender"=>urlencode($sender_id),"recipient"=>$to));
        $result = file_get_contents($api);
        if($save) {
            $data['staff_id'] = $this->session->userdata("login_user_id");
            $data['user_id'] = $customer;
            $data['sender_id'] = $sender_id;
            $data['message'] = $message;
            $data['recipients'] = $to;
            $data['status'] = $result;
            $data['date'] = gdate();
            d()->insert("sent_sms",$data);
        }
        return $result;
    }


}
