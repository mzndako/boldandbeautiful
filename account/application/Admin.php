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
		$this->load->model("domain_model");
		$this->load->library('session');

		$domain = null;
		if ($this->session->userdata('superadmin') == 1){
            $domain = $this->session->userdata('domain');
        }
		$this->domain_model->set_school_id($domain);

		$this->domain_model->check_redirect();

		$this->domain_model->set_division();

		$this->c_ = $this->crud_model;
		/*cache control*/
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');


	}

	/***default functin, redirects to login page if no admin logged in yet***/
	public function index()
	{
		redirect(base_url() . "?admin/dashboard", 'refresh');
	}

	/***ADMIN DASHBOARD***/
	function dashboard()
	{
		if (!$this->session->hAccess('login')) {
		$this->session->set_flashdata('flash_message', "Please login first");
		redirect(base_url() . "?login", 'refresh');
	}
//        $this->session->set_flashdata('flash_message' , "hello");
		$page_data['page_name'] = 'dashboard';
		$page_data['page_title'] = get_phrase('admin_dashboard');
		$this->load->view('backend/index', $page_data);
	}

	/****MANAGE STUDENTS CLASSWISE*****/
	function student_add()
	{
		$this->session->rAccess('admit_student');

		$page_data['page_name'] = 'student_add';
		$page_data['page_title'] = get_phrase('add_student');
		$this->load->view('backend/index', $page_data);
	}

	function student_bulk_add($param1 = '')
	{
		$this->session->rAccess('admit_student');

		if ($param1 == 'import_excel') {
			move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/student_import.xlsx');
			// Importing excel sheet for bulk student uploads

			include 'simplexlsx.class.php';

			$xlsx = new SimpleXLSX('uploads/student_import.xlsx');

			list($num_cols, $num_rows) = $xlsx->dimension();
			$f = 0;
			foreach ($xlsx->rows() as $r) {
				// Ignore the inital name row of excel file
				if ($f == 0) {
					$f++;
					continue;
				}
				for ($i = 0; $i < $num_cols; $i++) {
					if ($i == 0) $data['name'] = $r[$i];
					else if ($i == 1) $data['birthday'] = $r[$i];
					else if ($i == 2) $data['sex'] = $r[$i];
					else if ($i == 3) $data['address'] = $r[$i];
					else if ($i == 4) $data['phone'] = $r[$i];
					else if ($i == 5) $data['email'] = $r[$i];
					else if ($i == 6) $data['password'] = $r[$i];
					else if ($i == 7) $data['admission_no'] = $r[$i];
				}
				$data['class_id'] = $this->input->post('class_id');
				$data['division_id'] = $this->session->userdata('division_id');
				$data['school_id'] = $GLOBALS['SCHOOL_ID'];
				$this->c_->insert('student', $data);
				//print_r($data);
			}
			redirect(base_url() . '?admin/student_information/' . $this->input->post('class_id'), 'refresh');
		}
		$page_data['page_name'] = 'student_bulk_add';
		$page_data['page_title'] = get_phrase('add_bulk_student');
		$this->load->view('backend/index', $page_data);
	}

	function student_information($class_id = '')
	{
		$this->session->rAccess('view_students');

		$page_data['page_name'] = 'student_information';
		$page_data['page_title'] = get_phrase('student_information') . " - " . get_phrase('class') . " : " .
		$this->crud_model->get_class_name($class_id);


		$page_data['class_id'] = $class_id;
		$this->load->view('backend/index', $page_data);
	}

	function student_marksheet($student_id = '')
	{
		$this->session->rAccess('view_marksheet');

		$class_id = $this->c_->get_where('student', array('student_id' => $student_id))->row()->class_id;
		$student_name = $this->c_->get_where('student', array('student_id' => $student_id))->row()->name;
		$class_name = $this->c_->get_where('class', array('class_id' => $class_id))->row()->name;
		$page_data['page_name'] = 'student_marksheet';
		$page_data['page_title'] = get_phrase('marksheet_for') . ' ' . $student_name . ' (' . get_phrase('class') . ' ' . $class_name . ')';
		$page_data['student_id'] = $student_id;
		$page_data['class_id'] = $class_id;
		$this->load->view('backend/index', $page_data);
	}

	function student_marksheet_print_view($student_id, $exam_id)
	{
		$this->session->rAccess('view_marksheet');

		$class_id = $this->c_->get_where('student', array('student_id' => $student_id))->row()->class_id;
		$class_name = $this->c_->get_where('class', array('class_id' => $class_id))->row()->name;

		$page_data['student_id'] = $student_id;
		$page_data['class_id'] = $class_id;
		$page_data['exam_id'] = $exam_id;
		$this->load->view('backend/admin/student_marksheet_print_view', $page_data);
	}

	function student($param1 = '', $param2 = '', $param3 = '')
	{

		if ($param1 == 'create' || $param1 == 'update') {
			if($param1 == "create")
				$this->session->rAccess('admit_student');
			else
				$this->session->rAccess('update_student');

			$student_col = $this->c_->get_student_form();
			foreach($student_col as $col => $array){
				if($this->input->post($col) != null){
					$data[$col] = $this->input->post($col);
				}
			}

			unset($data['student_id']);
			if($param1 == "create") {
				$this->c_->insert('student', $data);
				$student_id = $this->db->insert_id();
				$this->c_->move_image("image","student",$student_id);
				$this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
//			$this->email_model->account_opening_email('student', $data['email']); //SEND EMAIL ACCOUNT OPENING EMAIL
				redirect(base_url() . '?admin/student_add/' . $data['class_id'], 'refresh');
			}else{
				$student_id = $param2;
				$this->db->where("student_id",$student_id);
				$this->c_->update("student",$data);
				$this->c_->move_image("image","student",$student_id);
				$this->session->set_flashdata('flash_message', get_phrase('data_updated_successfully'));
				redirect(base_url() . '?admin/student_information/' . $param3, 'refresh');
			}


			$this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
//			$this->email_model->account_opening_email('student', $data['email']); //SEND EMAIL ACCOUNT OPENING EMAIL
			redirect(base_url() . '?admin/student_add/' . $data['class_id'], 'refresh');
		}


		if ($param2 == 'delete') {
			$this->session->rAccess('delete_student');
			$this->db->where('student_id', $param3);
			$this->c_->delete('student');
			$this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
			redirect(base_url() . '?admin/student_information/' . $param1, 'refresh');
		}
	}

	/****MANAGE PARENTS CLASSWISE*****/
	function parent($param1 = '', $param2 = '', $param3 = '')
	{


		if ($param1 == 'create') {
			$this->session->rAccess('manage_parent');
			$data['name'] = $this->input->post('name');
			$data['email'] = $this->input->post('email');
			$data['password'] = $this->input->post('password');
			$data['phone'] = $this->input->post('phone');
			$data['address'] = $this->input->post('address');
			$data['profession'] = $this->input->post('profession');
			$data['division_id'] = $this->session->userdata('division_id');
			$this->c_->insert('parent', $data);
			$this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
			$this->email_model->account_opening_email('parent', $data['email']); //SEND EMAIL ACCOUNT OPENING EMAIL
			redirect(base_url() . '?admin/parent/', 'refresh');
		}
		if ($param1 == 'edit') {
			$this->session->rAccess('manage_parent');
			$data['name'] = $this->input->post('name');
			$data['email'] = $this->input->post('email');
			$data['phone'] = $this->input->post('phone');
			$data['address'] = $this->input->post('address');
			$data['profession'] = $this->input->post('profession');
			$this->db->where('parent_id', $param2);
			$this->c_->update('parent', $data);
			$this->session->set_flashdata('flash_message', get_phrase('data_updated'));
			redirect(base_url() . '?admin/parent/', 'refresh');
		}
		if ($param1 == 'delete') {
			$this->session->rAccess('manage_parent');
			$this->c_->where('parent_id', $param2);
			$this->c_->delete('parent');
			$this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
			redirect(base_url() . '?admin/parent/', 'refresh');
		}

		$this->session->rAccess('view_parents');
		$page_data['page_title'] = get_phrase('all_parents');
		$page_data['page_name'] = 'parent';
		$this->load->view('backend/index', $page_data);
	}


	/****MANAGE TEACHERS*****/
	function teacher($param1 = '', $param2 = '', $param3 = '')
	{
		$this->session->rAccess('view_teachers');

		if ($param1 == 'create' || $param1 == 'update') {
			if($param1 == "create")
				$this->session->rAccess('create_teacher');
			else
				$this->session->rAccess('update_teacher');

			$student_col = $this->c_->get_teacher_form();
			foreach($student_col as $col => $array){
				if($this->input->post($col) != null){
					$data[$col] = $this->input->post($col);
				}
			}

			unset($data['teacher_id']);
			if($param1 == "create") {
				$this->c_->insert('teacher', $data);
				$student_id = $this->db->insert_id();

				$this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
//			$this->email_model->account_opening_email('teacher', $data['email']); //SEND EMAIL ACCOUNT OPENING EMAIL
			}else{
				$student_id = $param2;
				$this->db->where("teacher_id",$student_id);
				$this->c_->update("teacher",$data);
				$this->session->set_flashdata('flash_message', get_phrase('data_updated_successfully'));
			}
			$this->c_->move_image("image","teacher",$student_id);

			$image = "signature,highest_qualification";
			$images = explode(",",$image);
			foreach($images as $img){
				$this->c_->move_image($img,"teacher",$student_id,"$img");
			}

			redirect(base_url() . '?admin/teacher/', 'refresh');
		}
		if ($param1 == 'delete') {
			$this->session->rAccess('delete_teacher');
			$this->c_->where('teacher_id', $param2);
			$this->c_->delete('teacher');
			$this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
			redirect(base_url() . '?admin/teacher/', 'refresh');
		}
		$page_data['teachers'] = $this->c_->get('teacher')->result_array();
		$page_data['page_name'] = 'teacher';
		$page_data['page_title'] = get_phrase('manage_teacher');
		$this->load->view('backend/index', $page_data);
	}

	/****MANAGE SUBJECTS*****/
	function subject($param1 = '', $param2 = '', $param3 = '')
	{
		$this->session->rAccess('view_subjects');

		if ($param1 == 'create') {
			$this->session->rAccess('manage_subject');
			$data['name'] = $this->input->post('name');
			$data['class_id'] = $this->input->post('class_id');
			$data['teacher_id'] = $this->input->post('teacher_id');
			$data['division_id'] = $this->session->userdata('division_id');
			$this->c_->insert('subject', $data);
			$this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
			redirect(base_url() . '?admin/subject/' . $data['class_id'], 'refresh');
		}
		if ($param1 == 'do_update') {
			$this->session->rAccess('manage_subject');
			$data['name'] = $this->input->post('name');
			$data['class_id'] = $this->input->post('class_id');
			$data['teacher_id'] = $this->input->post('teacher_id');

			$this->c_->where('subject_id', $param2);
			$this->c_->update('subject', $data);
			$this->session->set_flashdata('flash_message', get_phrase('data_updated'));
			redirect(base_url() . '?admin/subject/' . $data['class_id'], 'refresh');
		} else if ($param1 == 'edit') {
			$page_data['edit_data'] = $this->c_->get_where('subject', array(
				'subject_id' => $param2
			))->result_array();
		}
		if ($param1 == 'delete') {
			$this->session->rAccess('manage_subject');
			$this->c_->where('subject_id', $param2);
			$this->c_->delete('subject');
			$this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
			redirect(base_url() . '?admin/subject/' . $param3, 'refresh');
		}
		$page_data['class_id'] = $param1;
		$page_data['subjects'] = $this->c_->get_where('subject', array('class_id' => $param1, 'division_id' => $this->session->userdata('division_id')))->result_array();
		$page_data['page_name'] = 'subject';
		$page_data['page_title'] = get_phrase('manage_subject');
		$this->load->view('backend/index', $page_data);
	}

	/****MANAGE CLASSES*****/
	function classes($param1 = '', $param2 = '')
	{
		$this->session->rAccess('view_classes');

		if ($param1 == 'create') {
			$this->session->rAccess('manage_class');
			$data['name'] = $this->input->post('name');

			$result = $this->c_->get_where("class", array("name" => $data['name']));

			if ($result->num_rows() > 0) {
				$this->session->set_flashdata('flash_message', get_phrase('class_already_existed'));
				redirect(base_url() . '?admin/classes/', 'refresh');
			}

			$data['name_numeric'] = $this->input->post('name_numeric');
			$data['teacher_id'] = $this->input->post('teacher_id');
			$data['division_id'] = $this->session->userdata('division_id');
			$this->c_->insert('class', $data);
			$this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
			redirect(base_url() . '?admin/classes/', 'refresh');
		}
		if ($param1 == 'do_update') {
			$this->session->rAccess('manage_class');
			$data['name'] = $this->input->post('name');
			$data['name_numeric'] = $this->input->post('name_numeric');
			$data['teacher_id'] = $this->input->post('teacher_id');

			$this->c_->where('class_id', $param2);
			$this->c_->update('class', $data);
			$this->session->set_flashdata('flash_message', get_phrase('data_updated'));
			redirect(base_url() . '?admin/classes/', 'refresh');
		} else if ($param1 == 'edit') {
			$page_data['edit_data'] = $this->c_->get_where('class', array(
				'class_id' => $param2
			))->result_array();
		}
		if ($param1 == 'delete') {
			$this->session->rAccess('manage_class');
			$this->c_->where('class_id', $param2);
			$this->c_->delete('class');
			$this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
			redirect(base_url() . '?admin/classes/', 'refresh');
		}
		$page_data['classes'] = $this->c_->get_where('class', array('division_id' => $this->session->userdata('division_id')))->result_array();
		$page_data['page_name'] = 'class';
		$page_data['page_title'] = get_phrase('manage_class');
		$this->load->view('backend/index', $page_data);
	}

	/****MANAGE SECTIONS*****/
	function section($class_id = '')
	{
		$this->session->rAccess('view_sections');
		// detect the first class
		if ($class_id == '')
			$class_id = @$this->c_->get('class')->first_row()->class_id;

		$page_data['page_name'] = 'section';
		$page_data['page_title'] = get_phrase('manage_sections');
		$page_data['class_id'] = $class_id;
		$this->load->view('backend/index', $page_data);
	}

	function sections($param1 = '', $param2 = '')
	{
		$this->session->rAccess('manage_section');

		if ($param1 == 'create') {
			$data['name'] = $this->input->post('name');
			$data['nick_name'] = $this->input->post('nick_name');
			$data['class_id'] = $this->input->post('class_id');
			$data['teacher_id'] = $this->input->post('teacher_id');
			$this->c_->insert('section', $data);
			$this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
			redirect(base_url() . '?admin/section/' . $data['class_id'], 'refresh');
		}

		if ($param1 == 'edit') {
			$data['name'] = $this->input->post('name');
			$data['nick_name'] = $this->input->post('nick_name');
			$data['class_id'] = $this->input->post('class_id');
			$data['teacher_id'] = $this->input->post('teacher_id');
			$this->c_->where('section_id', $param2);
			$this->c_->update('section', $data);
			$this->session->set_flashdata('flash_message', get_phrase('data_updated'));
			redirect(base_url() . '?admin/section/' . $data['class_id'], 'refresh');
		}

		if ($param1 == 'delete') {
			$this->c_->where('section_id', $param2);
			$this->c_->delete('section');
			$this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
			redirect(base_url() . '?admin/section', 'refresh');
		}
	}

	function get_class_section($class_id)
	{

		$sections = $this->c_->get_where('section', array(
			'class_id' => $class_id
		))->result_array();
		foreach ($sections as $row) {
			echo '<option value="' . $row['section_id'] . '">' . $row['name'] . '</option>';
		}
	}

	function get_class_subject($class_id)
	{
		$subjects = $this->c_->get_where('subject', array(
			'class_id' => $class_id
		))->result_array();
		foreach ($subjects as $row) {
			echo '<option value="' . $row['subject_id'] . '">' . $row['name'] . '</option>';
		}
	}

	function get_class_students($class_id)
	{
		$students = $this->c_->get_where('student', array(
			'class_id' => $class_id
		))->result_array();
		foreach ($students as $row) {
			echo '<option value="' . $row['student_id'] . '">' . $this->c_->get_full_name($row) . '</option>';
		}
	}

	function get_class_students_mass($class_id)
	{
		$students = $this->c_->get_where('student', array(
			'class_id' => $class_id
		))->result_array();
		echo '<div class="form-group">
                <label class="col-sm-3 control-label">' . get_phrase('students') . '</label>
                <div class="col-sm-9">';
		foreach ($students as $row) {
			echo '<div class="checkbox">
                    <label><input type="checkbox" class="check" name="student_id[]" value="' . $row['student_id'] . '">' . $this->c_->get_full_name($row) . '</label>
                </div>';
		}
		echo '<br><button type="button" class="btn btn-default" onClick="select()">' . get_phrase('select_all') . '</button>';
		echo '<button style="margin-left: 5px;" type="button" class="btn btn-default" onClick="unselect()"> ' . get_phrase('select_none') . ' </button>';
		echo '</div></div>';
	}


	/****MANAGE EXAMS*****/
	function exam($param1 = '', $param2 = '', $param3 = '')
	{
		$this->session->rAccess('login');

		if ($param1 == 'create') {
			$this->session->rAccess('manage_exam');
			$data['name'] = $this->input->post('name');
			$data['mark'] = $this->input->post('mark');
			$data['term_id'] = $this->input->post('term_id');
			$data['date'] = $this->input->post('date');
			$data['comment'] = $this->input->post('comment');
			$classes = $this->input->post('class_id');

			foreach ($classes as $class) {
				$data['class_id'] = $class;
				$this->c_->insert('exam', $data);
			}

			$this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
			redirect(base_url() . '?admin/exam/', 'refresh');
		}
		if ($param1 == 'do_update') {
			$this->session->rAccess('manage_exam');
			$data['name'] = $this->input->post('name');
			$data['mark'] = $this->input->post('mark');
			$data['class_id'] = $this->input->post('class_id');
			$data['term_id'] = $this->input->post('term_id');
			$data['date'] = $this->input->post('date');
			$data['comment'] = $this->input->post('comment');

			$this->c_->where('exam_id', $param2);
			$this->c_->update('exam', $data);
			$this->session->set_flashdata('flash_message', get_phrase('data_updated'));
			redirect(base_url() . '?admin/exam/' . $param3, 'refresh');
		} else if ($param1 == 'edit') {
			exit("hello");
			$page_data['edit_data'] = $this->c_->get_where('exam', array(
				'exam_id' => $param2
			))->result_array();
		}
		if ($param1 == 'delete') {
			$this->session->rAccess('manage_exam');
			$this->c_->where('exam_id', $param2);
			$this->c_->delete('exam');
			$this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
			redirect(base_url() . '?admin/exam/' . $param2 . '/' . $param3, 'refresh');
		}


		if ($param1 == 'select') {
			$term_id = $this->input->post('term_id');
			$class_id = $this->input->post('class_id');
			redirect(base_url() . '?admin/exam/' . $term_id . '/' . $class_id, 'refresh');
		}

		if ($param1 == "") {
			$current_term = $this->c_->get_setting("current_term", 0);
		} else {
			$current_term = $param1;
		}

		$search = Array();
		$page_data['class_id'] = 0;
		if ($param2 != "" && $param2 != 0) {
			$search['class_id'] = $param2;
			$page_data['class_id'] = $param2;
		}
		$this->db->order_by("class_id", "asc");
		$search['term_id'] = $current_term;
		$page_data['exams'] = $this->c_->get_where('exam', $search)->result_array();

		$page_data['current_term'] = $current_term;
		$page_data['session_id'] = @$this->c_->get_where("term", array("term_id" => $current_term))->row()->year_id;
		$page_data['page_name'] = 'exam';
		$page_data['page_title'] = get_phrase('manage_exam');
		$this->load->view('backend/index', $page_data);
	}

	/****** SEND EXAM MARKS VIA SMS ********/
	function exam_marks_sms($param1 = '', $param2 = '')
	{
		$this->session->rAccess('manage_exam');

		if ($param1 == 'send_sms') {

			$exam_id = $this->input->post('exam_id');
			$class_id = $this->input->post('class_id');
			$receiver = $this->input->post('receiver');

			// get all the students of the selected class
			$students = $this->c_->get_where('student', array(
				'class_id' => $class_id
			))->result_array();
			// get the marks of the student for selected exam
			foreach ($students as $row) {
				if ($receiver == 'student')
					$receiver_phone = $row['phone'];
				if ($receiver == 'parent' && $row['parent_id'] != '')
					$receiver_phone = $this->c_->get_where('parent', array('parent_id' => $row['parent_id']))->row()->phone;


				$this->c_->where('exam_id', $exam_id);
				$this->c_->where('student_id', $row['student_id']);
				$marks = $this->c_->get('mark')->result_array();
				$message = '';
				foreach ($marks as $row2) {
					$subject = $this->c_->get_where('subject', array('subject_id' => $row2['subject_id']))->row()->name;
					$mark_obtained = $row2['mark_obtained'];
					$message .= $row2['student_id'] . $subject . ' : ' . $mark_obtained . ' , ';

				}
				// send sms
				$this->sms_model->send_sms($message, $receiver_phone);
			}
			$this->session->set_flashdata('flash_message', get_phrase('message_sent'));
			redirect(base_url() . '?admin/exam_marks_sms', 'refresh');
		}

		$page_data['page_name'] = 'exam_marks_sms';
		$page_data['page_title'] = get_phrase('send_marks_by_sms');
		$this->load->view('backend/index', $page_data);
	}

	/****MANAGE EXAM MARKS*****/
	function marks($exam_id = '', $class_id = '', $subject_id = '', $term_id = '')
	{
		$this->session->rAccess('manage_exam');

		if ($this->input->post('operation') == 'selection') {
//            exit();
			$page_data['exam_id'] = $this->input->post('exam_id');
			$page_data['term_id'] = $this->input->post('term_id');
			$page_data['class_id'] = $this->input->post('class_id');
			$page_data['subject_id'] = $this->input->post('subject_id');

			if ($page_data['exam_id'] > 0 && $page_data['class_id'] > 0 && $page_data['subject_id'] > 0) {
				redirect(base_url() . '?admin/marks/' . $page_data['exam_id'] . '/' . $page_data['class_id'] . '/' . $page_data['subject_id'] . '/' . $page_data['term_id'], 'refresh');
			} else {
				$this->session->set_flashdata('mark_message', 'Choose exam, class and subject');
				redirect(base_url() . '?admin/marks/', 'refresh');
			}
		}


		if ($this->input->post('operation') == 'update') {
			$students = $this->c_->get_where('student', array('class_id' => $class_id))->result_array();
			foreach ($students as $row) {
				$data['mark_obtained'] = $this->input->post('mark_obtained_' . $row['student_id']);
				$data['comment'] = $this->input->post('comment_' . $row['student_id']);

				$this->c_->where('mark_id', $this->input->post('mark_id_' . $row['student_id']));
				$this->c_->update('mark', array('mark_obtained' => $data['mark_obtained'], 'comment' => $data['comment']));
			}
			$this->session->set_flashdata('flash_message', get_phrase('data_updated'));
			redirect(base_url() . '?admin/marks/' . $this->input->post('exam_id') . '/' . $this->input->post('class_id') . '/' . $this->input->post('subject_id') . '/' . $this->input->post('term_id'), 'refresh');
		}


		$page_data['term_id'] = $term_id == "" ? $this->c_->get_setting("current_term") : $term_id;
		$page_data['session_id'] = $this->c_->get_where("term", array("term_id" => $page_data['term_id']))->row()->year_id;
		$page_data['exam_id'] = $exam_id;
		$page_data['class_id'] = $class_id;
		$page_data['subject_id'] = $subject_id;

		$page_data['page_info'] = 'Exam marks';

		$page_data['page_name'] = 'marks';
		$page_data['page_title'] = get_phrase('manage_exam_marks');
		$this->load->view('backend/index', $page_data);
	}

	// TABULATION SHEET
	function tabulation_sheet($class_id = '', $exam_id = '')
	{
		$this->session->rAccess('login');

		if ($this->input->post('operation') == 'selection') {
			$page_data['exam_id'] = $this->input->post('exam_id');
			$page_data['class_id'] = $this->input->post('class_id');

			if ($page_data['exam_id'] > 0 && $page_data['class_id'] > 0) {
				redirect(base_url() . '?admin/tabulation_sheet/' . $page_data['class_id'] . '/' . $page_data['exam_id'], 'refresh');
			} else {
				$this->session->set_flashdata('mark_message', 'Choose class and exam');
				redirect(base_url() . '?admin/tabulation_sheet/', 'refresh');
			}
		}
		$page_data['exam_id'] = $exam_id;
		$page_data['class_id'] = $class_id;

		$page_data['page_info'] = 'Exam marks';

		$page_data['page_name'] = 'tabulation_sheet';
		$page_data['page_title'] = get_phrase('tabulation_sheet');
		$this->load->view('backend/index', $page_data);

	}

	function tabulation_sheet_print_view($class_id, $exam_id)
	{
		$this->session->rAccess('login');

		$page_data['class_id'] = $class_id;
		$page_data['exam_id'] = $exam_id;
		$this->load->view('backend/admin/tabulation_sheet_print_view', $page_data);
	}


	function view_results($term_id = "", $class_id = "")
	{
		$this->session->rAccess('view_result');
		if ($this->input->post("operation") != "") {
			$class_id = $this->input->post("class_id");
			$term_id = $this->input->post("term_id");
			redirect(base_url() . "?admin/view_results/$term_id/$class_id", "refresh");
		}
		$page_data['term_id'] = $page_data['term_id'] = $term_id == "" ? $this->c_->get_setting("current_term") : $term_id;
		$page_data['session_id'] = $this->c_->get_where("term", array("term_id" => $page_data['term_id']))->row()->year_id;
		$page_data['class_id'] = $class_id;
		$page_data['page_info'] = 'Results';
		$page_data['page_name'] = 'view_results';
		$page_data['page_title'] = get_phrase('view_results');
		$this->load->view('backend/index', $page_data);
	}

	/****MANAGE GRADES*****/
	function grade($param1 = '', $param2 = '')
	{
		$this->session->rAccess('view_grades');

		if ($param1 == 'create') {
			$this->session->rAccess('manage_grade');
			$data['name'] = $this->input->post('name');
			$data['grade_point'] = $this->input->post('grade_point');
			$data['mark_from'] = $this->input->post('mark_from');
			$data['mark_upto'] = $this->input->post('mark_upto');
			$data['comment'] = $this->input->post('comment');
			$this->c_->insert('grade', $data);
			$this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
			redirect(base_url() . '?admin/grade/', 'refresh');
		}
		if ($param1 == 'do_update') {
			$this->session->rAccess('manage_grade');
			$data['name'] = $this->input->post('name');
			$data['grade_point'] = $this->input->post('grade_point');
			$data['mark_from'] = $this->input->post('mark_from');
			$data['mark_upto'] = $this->input->post('mark_upto');
			$data['comment'] = $this->input->post('comment');

			$this->c_->where('grade_id', $param2);
			$this->c_->update('grade', $data);
			$this->session->set_flashdata('flash_message', get_phrase('data_updated'));
			redirect(base_url() . '?admin/grade/', 'refresh');
		} else if ($param1 == 'edit') {
			$page_data['edit_data'] = $this->c_->get_where('grade', array(
				'grade_id' => $param2
			))->result_array();
		}
		if ($param1 == 'delete') {
			$this->session->rAccess('manage_grade');
			$this->c_->where('grade_id', $param2);
			$this->c_->delete('grade');
			$this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
			redirect(base_url() . '?admin/grade/', 'refresh');
		}
		$page_data['grades'] = $this->c_->get('grade')->result_array();
		$page_data['page_name'] = 'grade';
		$page_data['page_title'] = get_phrase('manage_grade');
		$this->load->view('backend/index', $page_data);
	}

	/**********MANAGING CLASS ROUTINE******************/
	function class_routine($param1 = '', $param2 = '', $param3 = '')
	{
		$this->session->rAccess('view_routines');

		if ($param1 == 'create') {
			$this->session->rAccess('manage_routine');
			$data['class_id'] = $this->input->post('class_id');
			$data['subject_id'] = $this->input->post('subject_id');
			$data['time_start'] = $this->input->post('time_start') + (12 * ($this->input->post('starting_ampm') - 1));
			$data['time_end'] = $this->input->post('time_end') + (12 * ($this->input->post('ending_ampm') - 1));
			$data['time_start_min'] = $this->input->post('time_start_min');
			$data['time_end_min'] = $this->input->post('time_end_min');
			$data['day'] = $this->input->post('day');
			$this->c_->insert('class_routine', $data);
			$this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
			redirect(base_url() . '?admin/class_routine/', 'refresh');
		}
		if ($param1 == 'do_update') {
			$this->session->rAccess('manage_routine');
			$data['class_id'] = $this->input->post('class_id');
			$data['subject_id'] = $this->input->post('subject_id');
			$data['time_start'] = $this->input->post('time_start') + (12 * ($this->input->post('starting_ampm') - 1));
			$data['time_end'] = $this->input->post('time_end') + (12 * ($this->input->post('ending_ampm') - 1));
			$data['time_start_min'] = $this->input->post('time_start_min');
			$data['time_end_min'] = $this->input->post('time_end_min');
			$data['day'] = $this->input->post('day');

			$this->c_->where('class_routine_id', $param2);
			$this->c_->update('class_routine', $data);
			$this->session->set_flashdata('flash_message', get_phrase('data_updated'));
			redirect(base_url() . '?admin/class_routine/', 'refresh');
		} else if ($param1 == 'edit') {
			$page_data['edit_data'] = $this->c_->get_where('class_routine', array(
				'class_routine_id' => $param2
			))->result_array();
		}
		if ($param1 == 'delete') {
			$this->session->rAccess('manage_routine');
			$this->c_->where('class_routine_id', $param2);
			$this->c_->delete('class_routine');
			$this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
			redirect(base_url() . '?admin/class_routine/', 'refresh');
		}
		$page_data['page_name'] = 'class_routine';
		$page_data['page_title'] = get_phrase('manage_class_routine');
		$this->load->view('backend/index', $page_data);
	}

	/****** DAILY ATTENDANCE *****************/
	function manage_attendance($date = '', $month = '', $year = '', $class_id = '')
	{
		$this->session->rAccess('view_attendances');

		$active_sms_service = $this->c_->get_where('settings', array('type' => 'active_sms_service'))->row()->description;


		if ($_POST) {
			// Loop all the students of $class_id
			$this->session->rAccess('manage_attendance');
			$students = $this->c_->get_where('student', array('class_id' => $class_id))->result_array();
			foreach ($students as $row) {
				$attendance_status = $this->input->post('status_' . $row['student_id']);

				$this->c_->where('student_id', $row['student_id']);
				$this->c_->where('date', $this->input->post('date'));

				$this->c_->update('attendance', array('status' => $attendance_status));

				if ($attendance_status == 2 && false) {

					if ($active_sms_service != '' || $active_sms_service != 'disabled') {
						$student_name = $this->c_->get_where('student', array('student_id' => $row['student_id']))->row()->name;
						$receiver_phone = $this->c_->get_where('parent', array('parent_id' => $row['parent_id']))->row()->phone;
						$message = 'Your child' . ' ' . $student_name . 'is absent today.';
						$this->sms_model->send_sms($message, $receiver_phone);
					}
				}

			}

			$this->session->set_flashdata('flash_message', get_phrase('data_updated'));
			redirect(base_url() . '?admin/manage_attendance/' . $date . '/' . $month . '/' . $year . '/' . $class_id, 'refresh');
		}
		$page_data['date'] = $date;
		$page_data['month'] = $month;
		$page_data['year'] = $year;
		$page_data['class_id'] = $class_id;

		$page_data['page_name'] = 'manage_attendance';
		$page_data['page_title'] = get_phrase('manage_daily_attendance');
		$this->load->view('backend/index', $page_data);
	}

	function attendance_selector()
	{
		$this->session->rAccess('view_attendances');
		redirect(base_url() . '?admin/manage_attendance/' . $this->input->post('date') . '/' .
			$this->input->post('month') . '/' .
			$this->input->post('year') . '/' .
			$this->input->post('class_id'), 'refresh');
	}

	/******MANAGE BILLING / INVOICES WITH STATUS*****/
	function invoice($param1 = '', $param2 = '', $param3 = '')
	{
		$this->session->rAccess('view_invoices');


		if ($param1 == 'create') {
			$this->session->rAccess('create_invoice');
			$data['student_id'] = $this->input->post('student_id');
			$data['title'] = $this->input->post('title');
			$data['description'] = $this->input->post('description');
			$data['amount'] = $this->input->post('amount');
			$data['amount_paid'] = $this->input->post('amount_paid');
			$data['due'] = $data['amount'] - $data['amount_paid'];
			$data['status'] = $this->input->post('status');
			$data['creation_timestamp'] = strtotime($this->input->post('date'));

			$this->c_->insert('invoice', $data);
			$invoice_id = $this->c_->insert_id();

			$data2['invoice_id'] = $invoice_id;
			$data2['student_id'] = $this->input->post('student_id');
			$data2['title'] = $this->input->post('title');
			$data2['description'] = $this->input->post('description');
			$data2['payment_type'] = 'income';
			$data2['method'] = $this->input->post('method');
			$data2['amount'] = $this->input->post('amount_paid');
			$data2['timestamp'] = strtotime($this->input->post('date'));

			$this->c_->insert('payment', $data2);

			$this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
			redirect(base_url() . '?admin/student_payment', 'refresh');
		}

		if ($param1 == 'create_mass_invoice') {
			$this->session->rAccess('create_invoice');
			if ($this->input->post('student_id') !== null) {
				foreach ($this->input->post('student_id') as $id) {

					$data['student_id'] = $id;
					$data['title'] = $this->input->post('title');
					$data['description'] = $this->input->post('description');
					$data['amount'] = $this->input->post('amount');
					$data['amount_paid'] = $this->input->post('amount_paid');
					$data['due'] = $data['amount'] - $data['amount_paid'];
					$data['status'] = $this->input->post('status');
					$data['creation_timestamp'] = strtotime($this->input->post('date'));

					$this->c_->insert('invoice', $data);
					$invoice_id = $this->c_->insert_id();

					$data2['invoice_id'] = $invoice_id;
					$data2['student_id'] = $id;
					$data2['title'] = $this->input->post('title');
					$data2['description'] = $this->input->post('description');
					$data2['payment_type'] = 'income';
					$data2['method'] = $this->input->post('method');
					$data2['amount'] = $this->input->post('amount_paid');
					$data2['timestamp'] = strtotime($this->input->post('date'));

					$this->c_->insert('payment', $data2);

				}
			}
			$this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
			redirect(base_url() . '?admin/student_payment', 'refresh');
		}

		if ($param1 == 'do_update') {
			$this->session->rAccess('create_invoice');
			$data['student_id'] = $this->input->post('student_id');
			$data['title'] = $this->input->post('title');
			$data['description'] = $this->input->post('description');
			$data['amount'] = $this->input->post('amount');
			$data['status'] = $this->input->post('status');
			$data['creation_timestamp'] = strtotime($this->input->post('date'));

			$this->c_->where('invoice_id', $param2);
			$this->c_->update('invoice', $data);
			$this->session->set_flashdata('flash_message', get_phrase('data_updated'));
			redirect(base_url() . '?admin/invoice', 'refresh');
		} else if ($param1 == 'edit') {
			$page_data['edit_data'] = $this->c_->get_where('invoice', array(
				'invoice_id' => $param2
			))->result_array();
		}
		if ($param1 == 'take_payment') {
			$this->session->rAccess('accept payment');
			$data['invoice_id'] = $this->input->post('invoice_id');
			$data['student_id'] = $this->input->post('student_id');
			$data['title'] = $this->input->post('title');
			$data['description'] = $this->input->post('description');
			$data['payment_type'] = 'income';
			$data['method'] = $this->input->post('method');
			$data['amount'] = $this->input->post('amount');
			$data['timestamp'] = strtotime($this->input->post('timestamp'));
			$this->c_->insert('payment', $data);

			$data2['amount_paid'] = $this->input->post('amount');
			$this->c_->where('invoice_id', $param2);
			$this->db->set('amount_paid', 'amount_paid + ' . $data2['amount_paid'], FALSE);
			$this->db->set('due', 'due - ' . $data2['amount_paid'], FALSE);
			$this->c_->update('invoice');

			$this->session->set_flashdata('flash_message', get_phrase('payment_successfull'));
			redirect(base_url() . '?admin/invoice', 'refresh');
		}

		if ($param1 == 'delete') {
			$this->session->rAccess('delete_invoice');
			$this->db->where('invoice_id', $param2);
			$this->c_->delete('invoice');
			$this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
			redirect(base_url() . '?admin/invoice', 'refresh');
		}
		$page_data['page_name'] = 'invoice';
		$page_data['page_title'] = get_phrase('manage_invoice/payment');
		$this->db->order_by('creation_timestamp', 'desc');
		$page_data['invoices'] = $this->c_->get('invoice')->result_array();
		$this->load->view('backend/index', $page_data);
	}

	/**********ACCOUNTING********************/
	function income($param1 = '', $param2 = '')
	{
		$this->session->rAccess('view_invoices');

		$page_data['page_name'] = 'income';
		$page_data['page_title'] = get_phrase('student_payments');
		$this->db->order_by('creation_timestamp', 'desc');
		$page_data['invoices'] = $this->c_->get('invoice')->result_array();
		$this->load->view('backend/index', $page_data);
	}

	function student_payment($param1 = '', $param2 = '', $param3 = '')
	{

		$this->session->rAccess('view_invoices');

		$page_data['page_name'] = 'student_payment';
		$page_data['page_title'] = get_phrase('create_student_payment');
		$this->load->view('backend/index', $page_data);
	}

	function expense($param1 = '', $param2 = '')
	{
		$this->session->rAccess('view_expenses');
		if ($param1 == 'create') {
			$this->session->rAccess('manage_expense');
			$data['title'] = $this->input->post('title');
			$data['expense_category_id'] = $this->input->post('expense_category_id');
			$data['description'] = $this->input->post('description');
			$data['payment_type'] = 'expense';
			$data['method'] = $this->input->post('method');
			$data['amount'] = $this->input->post('amount');
			$data['timestamp'] = strtotime($this->input->post('timestamp'));
			$this->c_->insert('payment', $data);
			$this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
			redirect(base_url() . '?admin/expense', 'refresh');
		}

		if ($param1 == 'edit') {
			$this->session->rAccess('manage_expense');
			$data['title'] = $this->input->post('title');
			$data['expense_category_id'] = $this->input->post('expense_category_id');
			$data['description'] = $this->input->post('description');
			$data['payment_type'] = 'expense';
			$data['method'] = $this->input->post('method');
			$data['amount'] = $this->input->post('amount');
			$data['timestamp'] = strtotime($this->input->post('timestamp'));
			$this->db->where('payment_id', $param2);
			$this->c_->update('payment', $data);
			$this->session->set_flashdata('flash_message', get_phrase('data_updated'));
			redirect(base_url() . '?admin/expense', 'refresh');
		}

		if ($param1 == 'delete') {
			$this->session->rAccess('manage_expense');
			$this->db->where('payment_id', $param2);
			$this->c_->delete('payment');
			$this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
			redirect(base_url() . '?admin/expense', 'refresh');
		}

		$page_data['page_name'] = 'expense';
		$page_data['page_title'] = get_phrase('expenses');
		$this->load->view('backend/index', $page_data);
	}

	function expense_category($param1 = '', $param2 = '')
	{
		$this->session->rAccess('view_expenses');
		if ($param1 == 'create') {
			$this->session->rAccess('manage_expense');
			$data['name'] = $this->input->post('name');
			$this->c_->insert('expense_category', $data);
			$this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
			redirect(base_url() . '?admin/expense_category');
		}
		if ($param1 == 'edit') {
			$this->session->rAccess('manage_expense');
			$data['name'] = $this->input->post('name');
			$this->db->where('expense_category_id', $param2);
			$this->c_->update('expense_category', $data);
			$this->session->set_flashdata('flash_message', get_phrase('data_updated'));
			redirect(base_url() . '?admin/expense_category');
		}
		if ($param1 == 'delete') {
			$this->session->rAccess('manage_expense');
			$this->db->where('expense_category_id', $param2);
			$this->c_->delete('expense_category');
			$this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
			redirect(base_url() . '?admin/expense_category');
		}

		$page_data['page_name'] = 'expense_category';
		$page_data['page_title'] = get_phrase('expense_category');
		$this->load->view('backend/index', $page_data);
	}

	/**********MANAGE LIBRARY / BOOKS********************/
	function book($param1 = '', $param2 = '', $param3 = '')
	{
		$this->session->rAccess('view_books');
		if ($param1 == 'create') {
			$this->session->rAccess('manage_book');
			$data['name'] = $this->input->post('name');
			$data['description'] = $this->input->post('description');
			$data['price'] = $this->input->post('price');
			$data['author'] = $this->input->post('author');
			$data['class_id'] = $this->input->post('class_id');
			$data['status'] = $this->input->post('status');
			$this->c_->insert('book', $data);
			$this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
			redirect(base_url() . '?admin/book', 'refresh');
		}
		if ($param1 == 'do_update') {
			$this->session->rAccess('manage_book');
			$data['name'] = $this->input->post('name');
			$data['description'] = $this->input->post('description');
			$data['price'] = $this->input->post('price');
			$data['author'] = $this->input->post('author');
			$data['class_id'] = $this->input->post('class_id');
			$data['status'] = $this->input->post('status');

			$this->db->where('book_id', $param2);
			$this->c_->update('book', $data);
			$this->session->set_flashdata('flash_message', get_phrase('data_updated'));
			redirect(base_url() . '?admin/book', 'refresh');
		} else if ($param1 == 'edit') {
			$page_data['edit_data'] = $this->c_->get_where('book', array(
				'book_id' => $param2
			))->result_array();
		}
		if ($param1 == 'delete') {
			$this->session->rAccess('manage_book');
			$this->db->where('book_id', $param2);
			$this->c_->delete('book');
			$this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
			redirect(base_url() . '?admin/book', 'refresh');
		}
		$page_data['books'] = $this->c_->get('book')->result_array();
		$page_data['page_name'] = 'book';
		$page_data['page_title'] = get_phrase('manage_library_books');
		$this->load->view('backend/index', $page_data);

	}

	/**********MANAGE TRANSPORT / VEHICLES / ROUTES********************/
	function transport($param1 = '', $param2 = '', $param3 = '')
	{
		$this->session->rAccess('view_transports');

		if ($param1 == 'create') {
			$this->session->rAccess('manage_transport');
			$data['route_name'] = $this->input->post('route_name');
			$data['number_of_vehicle'] = $this->input->post('number_of_vehicle');
			$data['description'] = $this->input->post('description');
			$data['route_fare'] = $this->input->post('route_fare');
			$this->c_->insert('transport', $data);
			$this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
			redirect(base_url() . '?admin/transport', 'refresh');
		}
		if ($param1 == 'do_update') {
			$this->session->rAccess('manage_transport');
			$data['route_name'] = $this->input->post('route_name');
			$data['number_of_vehicle'] = $this->input->post('number_of_vehicle');
			$data['description'] = $this->input->post('description');
			$data['route_fare'] = $this->input->post('route_fare');

			$this->db->where('transport_id', $param2);
			$this->c_->update('transport', $data);
			$this->session->set_flashdata('flash_message', get_phrase('data_updated'));
			redirect(base_url() . '?admin/transport', 'refresh');
		} else if ($param1 == 'edit') {
			$page_data['edit_data'] = $this->c_->get_where('transport', array(
				'transport_id' => $param2
			))->result_array();
		}
		if ($param1 == 'delete') {
			$this->session->rAccess('manage_transport');
			$this->db->where('transport_id', $param2);
			$this->c_->delete('transport');
			$this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
			redirect(base_url() . '?admin/transport', 'refresh');
		}
		$page_data['transports'] = $this->c_->get('transport')->result_array();
		$page_data['page_name'] = 'transport';
		$page_data['page_title'] = get_phrase('manage_transport');
		$this->load->view('backend/index', $page_data);

	}

	/**********MANAGE DORMITORY / HOSTELS / ROOMS ********************/
	function dormitory($param1 = '', $param2 = '', $param3 = '')
	{
		$this->session->rAccess('view_dormitories');
		if ($param1 == 'create') {
			$this->session->rAccess('manage_dormitory');
			$data['name'] = $this->input->post('name');
			$data['number_of_room'] = $this->input->post('number_of_room');
			$data['description'] = $this->input->post('description');
			$this->c_->insert('dormitory', $data);
			$this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
			redirect(base_url() . '?admin/dormitory', 'refresh');
		}
		if ($param1 == 'do_update') {
			$this->session->rAccess('manage_dormitory');
			$data['name'] = $this->input->post('name');
			$data['number_of_room'] = $this->input->post('number_of_room');
			$data['description'] = $this->input->post('description');

			$this->db->where('dormitory_id', $param2);
			$this->c_->update('dormitory', $data);
			$this->session->set_flashdata('flash_message', get_phrase('data_updated'));
			redirect(base_url() . '?admin/dormitory', 'refresh');
		} else if ($param1 == 'edit') {

			$page_data['edit_data'] = $this->c_->get_where('dormitory', array(
				'dormitory_id' => $param2
			))->result_array();
		}
		if ($param1 == 'delete') {
			$this->session->rAccess('manage_dormitory');
			$this->db->where('dormitory_id', $param2);
			$this->c_->delete('dormitory');
			$this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
			redirect(base_url() . '?admin/dormitory', 'refresh');
		}
		$page_data['dormitories'] = $this->c_->get('dormitory')->result_array();
		$page_data['page_name'] = 'dormitory';
		$page_data['page_title'] = get_phrase('manage_dormitory');
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
			$this->session->rAccess('manage_settings');

			$data['description'] = $this->input->post('system_name');
			$this->c_->set_setting("system_name",$this->input->post('system_name'));
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

	// MANAGE SEGMENT
	function segment($param1 = "", $param2 = "")
	{
		$this->session->rAccess('view_divisions');

		if ($param1 == 'create') {
			$this->session->rAccess('manage_division');
			$data['name'] = $this->input->post('name');
			$data['comment'] = $this->input->post('comment');
			$data['school_id'] = $GLOBALS['SCHOOL_ID'];
			$this->db->insert('division', $data);
			$this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
			redirect(base_url() . '?admin/segment', 'refresh');
		}
		if ($param1 == 'do_update') {
			$this->session->rAccess('manage_division');
			$data['name'] = $this->input->post('name');
			$data['comment'] = $this->input->post('comment');
			$data['school_id'] = $GLOBALS['SCHOOL_ID'];
			$this->db->where('division_id', $param2);
			$this->db->update('division', $data);
			$this->session->set_flashdata('flash_message', get_phrase('data_updated'));
			redirect(base_url() . '?admin/segment', 'refresh');
		} else if ($param1 == 'edit') {
			$this->session->rAccess('manage_division');
			$page_data['edit_data'] = $this->c_->get_where('division', array(
				'division_id' => $param2
			))->result_array();
		}
		if ($param1 == 'delete') {
			$this->session->rAccess('manage_division');
			$this->db->where('division_id', $param2);
			$this->db->where('school_id', $GLOBALS['SCHOOL_ID']);
			$this->db->delete('division');
			$this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
			redirect(base_url() . '?admin/segment', 'refresh');
		}

		if ($param1 == 'change') {
			$did = $this->input->post('division_id');
			$this->session->set_userdata('division_id', $did);
			session_commit();

			$page_name = $this->input->post('page_name');
			if (!method_exists($this, $page_name)) $page_name = "dashboard";
//			echo $this->session->userdata("division_id");
//			exit;
			$this->session->set_flashdata('flash_message', "division changed");
			redirect(base_url() . '?admin/' . $page_name, 'refresh');
		}

		$page_data['division'] = $this->c_->get('division')->result_array();
		$page_data['page_name'] = 'segment';
		$page_data['page_title'] = get_phrase('manage_segments');
		$this->load->view('backend/index', $page_data);
	}

	//  MANAGE SEMESTER
	function session($param1 = "", $param2 = "")
	{
		$this->session->rAccess('view_terms');

		if ($param1 == 'create') {
			$this->session->rAccess('manage_terms');
			$data['name'] = $this->input->post('name');
			$this->c_->insert('year', $data);
			$this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
			redirect(base_url() . '?admin/session', 'refresh');
		}

		if ($param1 == 'create_term') {
			$this->session->rAccess('manage_terms');
			$data['name'] = $this->input->post('name');
			$data['year_id'] = $this->input->post('year_id');
			$data['start'] = strtotime($this->input->post('start'));
			$data['end'] = strtotime($this->input->post('end'));
			$data['division_id'] = $this->session->userdata('division_id');
			$this->c_->insert('term', $data);
			$this->session->set_flashdata('flash_message', get_phrase('data_added_successfully'));
			redirect(base_url() . '?admin/session', 'refresh');
		}
		if ($param1 == 'do_update') {
			$this->session->rAccess('manage_terms');
			$data['name'] = $this->input->post('name');
			$data['myorder'] = $this->input->post('mysort');
			$this->db->where('year_id', $param2);
			$this->c_->update('year', $data);

			$this->session->set_flashdata('flash_message', get_phrase('data_updated'));
			redirect(base_url() . '?admin/session', 'refresh');
		} else if ($param1 == 'edit') {
			$page_data['edit_data'] = $this->c_->get_where('term', array(
				'term_id' => $param2
			))->result_array();
		}
		if ($param1 == 'do_update_term') {
			$this->session->rAccess('manage_terms');
			$data['name'] = $this->input->post('name');
			$data['start'] = strtotime($this->input->post('start'));
			$data['end'] = strtotime($this->input->post('end'));

			$this->db->where('term_id', $param2);
			$this->c_->update('term', $data);
			$this->session->set_flashdata('flash_message', get_phrase('data_updated'));
			redirect(base_url() . '?admin/session', 'refresh');
		} else if ($param1 == 'edit') {
			$page_data['edit_data'] = $this->c_->get_where('term', array(
				'term_id' => $param2
			))->result_array();
		}
		if ($param1 == 'delete') {
			$this->session->rAccess('manage_terms');
			$this->db->where('year_id', $param2);
			$this->c_->delete('year');
			$this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
			redirect(base_url() . '?admin/session', 'refresh');
		}

		if ($param1 == 'delete_term') {
			$this->session->rAccess('manage_terms');
			$this->db->where('term_id', $param2);
			$this->c_->delete('term');
			$this->session->set_flashdata('flash_message', get_phrase('data_deleted'));
			redirect(base_url() . '?admin/session', 'refresh');
		}

		if ($param1 == 'activate') {
			$this->session->rAccess('manage_terms');
			$term_id = $param2;
			$this->c_->set_setting("current_term", $term_id);
			$this->session->set_flashdata('flash_message', get_phrase('term_selected_successfully'));
			redirect(base_url() . '?admin/session', 'refresh');
		}

		$page_data['session_list'] = $this->c_->get('year')->result_array();
		$page_data['term_list'] = $this->c_->get('term')->result_array();
		$page_data['page_name'] = 'session';
		$page_data['page_title'] = get_phrase('manage_session');
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

			$per = $this->input->post("astaff");
			$this->c_->set_setting("academic_staff_access",@implode(",",@$per));

			$per = $this->input->post("nstaff");
			$this->c_->set_setting("non_academic_staff_access",@implode(",",$per));

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
		$this->session->rAccess('login');
		$login_as = $this->session->userdata("login_as");
		$login_id = $this->session->userdata("login_user_id");
		$login_as_id = $login_as."_id";

		if ($param1 == 'update_profile_info') {
			if($this->c_->isStudent()){
				$data['surname'] = $this->input->post('surname');
				$data['fname'] = $this->input->post('fname');
				$data['mname'] = $this->input->post('mname');
			}else{
				$data['name'] = $this->input->post('name');
			}
			$data['email'] = $this->input->post('email');
			$data['phone'] = $this->input->post('phone');

			$this->db->where($login_as_id, $login_id);
			$this->c_->update($login_as, $data);
			$this->c_->move_image("image",$login_as,$login_id);
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
		$page_data['edit_data'] = $this->c_->get_where($login_as, array(
			$login_as_id => $login_id
		))->result_array();
		$this->load->view('backend/index', $page_data);
	}

	function launch_cbt($param="manage")
	{
		$this->session->rAccess('access_cbt');
		$id = session_id();
		$this->session->set_userdata("base_url",base_url());
		$this->session->set_userdata("school_id",$GLOBALS['SCHOOL_ID']);
		if($param == "manage")
			header("Location: cbt/admin/index.php?session_id=$id");
		else
			header("Location: cbt/index.php?session_id=$id");
	}

}
