<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

$x = 44;
$y = "444";

$zm = $x + $y;




if ( ! function_exists('get_phrase'))
{
	function get_phrase($phrase = '') {

		$CI	=&	get_instance();
		$CI->load->database();

		$current_language	=	$CI->crud_model->get_setting("current_language","english");

		if ( $current_language	==	'') {
			$current_language	=	'english';
			$CI->session->set_userdata('current_language' , $current_language);
		}


		/** insert blank phrases initially and populating the language db ***/
//		$q	=	$CI->db->get_where('language' , array('phrase' => $phrase, 'school_id'=>$GLOBALS['SCHOOL_ID']));

		static $rows = null;

		if($rows == null){
			$CI->db->select("$current_language,phrase");
			$rows	=	$CI->db->get('language' )->result_array();

		}

		foreach($rows as $row){
			if($row['phrase'] == $phrase && $row[$current_language] != "")
				return ucwords(str_replace('_', ' ', $row[$current_language]));
		}

//		if($q -> num_rows() == 0) {
				$CI->db->insert('language', array('phrase' => $phrase, $current_language => ucwords(str_replace('_', ' ', $phrase))));
				return ucwords(str_replace('_', ' ', $phrase));
//		}
		return;
		// query for finding the phrase from `language` table
		$row   	=	$q->row();
		
		// return the current sessioned language field of according phrase, else return uppercase spaced word
		if (isset($row->$current_language) && $row->$current_language !="")
			return ucwords(str_replace('_', ' ', $row->$current_language));
		else 
			return ucwords(str_replace('_',' ',$phrase));
	}
}



// ------------------------------------------------------------------------
/* End of file language_helper.php */
/* Location: ./system/helpers/language_helper.php */