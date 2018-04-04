<?php
/**
 * Created by PhpStorm.
 * User: MZ
 * Date: 20/8/2016
 * Time: 8:15 PM
 */

if ( ! function_exists('get_setting'))
{
		function get_setting($key,$default = "",$division_id = null){
			static $settings = null;
			if(!isset($settings[$key])){
				$q = d()->get_where('settings' , array('type'=>$key));

				if($q -> num_rows() > 0){
					$settings[$key] = $q -> row() -> description;;
				}else {
					$data['type'] = $key;
					$data['description'] = $default;
					if(d()->insert("settings", $data)){
						$settings[$key] = $default;
					}
				}
			}


			return $settings[$key];
		}
}



if ( ! function_exists('save_setting'))
{
	function save_setting($key,$value="",$division_id = null){


		if(!is_array($key)){
			$key = array($key=>$value);
		}
		$success = 0;
		foreach($key as $k => $v) {
			$q = d()->get_where('settings', array('type' => $k));

			if ($q->num_rows() > 0) {
				d()->where("type",$k);
				if(d()->update("settings",array("description"=>$v)))
					$success++;
			} else {
				$data['type'] = $k;
				$data['description'] = $v;
				if (d()->insert("settings", $data)) {
					$success++;
				}
			}

		}

		if($success == count($key))
			return true;

		if($success == 0)
			return false;

		return $success;
	}
}


function checkAccess($config=Array()){
	return s()->checkAccess($config);
}

function hAccess($function=null){
	$config = Array("redirect"=>false);
	if(!empty($function))
		$config['function'] = $function;

	return checkAccess($config);
}

function rAccess($function=null,$refresh = false){

	$config = Array("redirect"=>true);
	if(!empty($function)) {
		$config['function'] = $function;
	}
	$config['refresh'] = $refresh;
	return checkAccess($config);
}


function s(){
	$CI =& get_instance();
	return $CI->session;
}

function c(){
	$CI =& get_instance();
	return $CI->crud_model;
}

if(!function_exists('d')) {
	function d()
	{
		$CI =& get_instance();
		return $CI->db;
	}
}
function &this(){
	$CI =& get_instance();
	return $CI;
}

function user_id(){
	return this()->session->userdata('user_id');
}



function in_arrayi($needle, $haystack) {
	return in_array(strtolower($needle), array_map('strtolower', $haystack));
}

function indexOf($search,$array){
	$search = strtolower($search);
	foreach($array as $k => $v){
		if(strtolower($v) == $search)
			return $k;
	}
	return -1;
}

function get_max_index($assoc_array){
	$max = 0;
	$maxkey = 0;
	foreach($assoc_array as $key=>$value){
		if($value > $max){
			$max = $value;
			$maxkey = $key;
		}
	}

	return $maxkey;
}

function database_date($date,$addition = 0, $substration = 0, $format = "Y/m/d H:i:s"){
	$x = (strtotime($date) + $addition) - $substration;
	return date($format,$x);
}

function gdate($minute=0,$hour=0) {
	date_default_timezone_set('Africa/Lagos');
	$dt = Date("d-m-Y H:i:s");
	$datetime1 = new DateTime($dt);
	$datetime1 -> add(date_interval_create_from_date_string("$hour hours"));
	$datetime1 -> add(date_interval_create_from_date_string("$minute minutes"));
	return date_format($datetime1, "Y-m-d H:i:s");
}

function get_status($status){
if ($status == 1)
	return '<span class="badge badge-success">'. get_phrase('present').'</span>';
return '<span class="badge badge-danger">'. get_phrase('absent').'</span>';
}

function getIndex($array,$str_index,$default = ""){
	$ex = explode(",",$str_index);

	if(count($ex) > 0){
		if(count($ex) == 1){
			return isset($array[$ex[0]])?$array[$ex[0]]:$default;
		}else{
			if(isset($array[$ex[0]])){
				$array2 = $array[$ex[0]];
				array_shift($ex);
				return getIndex($array2,implode(",",$ex),$default);
			}else
				return $default;
		}
	}
		return $default;
}

function flash_redirect($link,$flash){
	s()->set_flashdata('flash_message', $flash);
	redirect(base_url() . $link, 'refresh');
}

function convert_to_date($time,$format = "j F, Y")
{
	if(!is_numeric($time))
		$time = strtotime($time);
	return date($format, $time);
}

function convert_to_datetime($time,$format = "j F, Y. g:i A")
{
	if(!is_numeric($time))
		$time = strtotime($time);
	return date($format, $time);
}


function convert2number($number){
	return str_replace(array("N"," ",","),"",$number);
}

function generate_app_id($id){
	return "BB00".$id;
}

function get_client_id($id){
	return "B-0".$id;
}

function get_seconds($min = 0, $hrs = 0, $days = 0){
	return ($min * 60) + ($hrs * 60 * 60) + ($days * 24 * 60 * 60);
}

function user_data($id,$value,$default = ""){

	static $users = array();
	if(count($users) == 0){
		$x = d()->get("users")->result_array();
		foreach($x as $row){
			$users[$row['id']] = $row;
		}
	}

	if(isset($users[$id][$value])){
		return $users[$id][$value];
	}

	return $default;
}

function is_admin($user_id = 0){
	if($user_id == 0)
		$user_id = s()->userdata("login_user_id");

	return user_data($user_id,"is_admin",0) == 1;
}

function replaceV($text,$member,$addition=array()){
	return c()->replace_values($text,$member,$addition);
}

function login_id(){
	return s()->userdata("login_user_id");
}

function format_number($number, $prefix = "N ",$decimal = 0){
	return $prefix.number_format($number,$decimal);
}


function numbers($numbers,&$no = 0,&$units=0,$filter = false, $max = 0){
	$units = 0;
	$numbers = trim($numbers);
	$text = "";
	$numbers = urlencode($numbers);
	$numbers = str_replace("%5Cr%5Cn", "%2C", $numbers);
	$numbers = str_replace("-", "", $numbers);
	$numbers = urldecode($numbers);
	$numbers = str_replace("+", " +", $numbers);

	$array = array("<",'>','(',')');
	$t = str_replace($array, " ", $numbers);

	$array = array(",",' ',"a","\n","\r");
	$t = str_replace($array, ",", $t);

	$array = explode(",", $t);
	foreach ($array as $key => $value) {
		$value = trim($value);
		$plus = strpos($value, "+");
		if($plus === 0){
			$value = substr($value, 1);
		}
		$len = strlen($value);

		if($len>6 && is_numeric($value) && $len < 18){
			$pos = strpos($value, "0");
			$pos1 = strpos($value, "234");
			if($pos===0 & $len == 11){
				$value = "234".substr($value, 1);
			}else if($pos1===0){
				if($len != 13)
					continue;
			}else if(!countryExit($value)){
				$value = "234$value";
				if(strlen($value) != 13)
					continue;
			}else if($len == 10 & $plus !== 0){
				$value = "234$value";
			}

			$text .= ",$value";

		}
	}

	if(strlen($text)>3){
		$text = substr ($text, 1);
		$array = explode(",", $text);
		$no = 0;
		$text = "";
		foreach ($array as $value) {
			if(strpos($text, $value)===false){
				$name = "OTHERS";
				if(countryExit($value,$x,$name,$filter)){
					$no++;
					$units  += $x;
					$text .= ",$value";
					if($no >= $max && $max != 0)
						break;
				}
			}
		}
		if(strlen($text)>3)
			$text = substr ($text, 1);
	}else {
		$no = 0;
		$units  = 0;
	}
	return $text;
}

function getCountries(){
	return  Array(93 => Array('5','Afghanistan'),213 => Array('5','Algeria'),244 => Array('5','Angola'),374 => Array('5','Armenia'),61 => Array('5','Australia'),43 => Array('5','Austria'),994 => Array('5','Azerbaijan'),1242 => Array('5','Bahamas'),973 => Array('5','Bahrain'),880 => Array('1','Bangladesh'),1246 => Array('5','Barbados'),375 => Array('5','Belarus'),32 => Array('5','Belgium'),501 => Array('5','Belize'),229 => Array('5','Benin'),1441 => Array('5','Bermuda'),387 => Array('5','Bosnia and Herzegovina'),267 => Array('5','Botswana'),673 => Array('5','Brunei'),359 => Array('5','Bulgaria'),226 => Array('5','Burkina Faso'),257 => Array('5','Burundi'),855 => Array('5','Cambodia'),237 => Array('5','Cameroon'),235 => Array('5','Chad'),225 => Array('5','Cote D\'Ivoire'),385 => Array('5','Croatia'),357 => Array('5','Cyprus'),420 => Array('5','Czech Republic'),45 => Array('5','Denmark'),20 => Array('5','Egypt'),372 => Array('5','Estonia'),358 => Array('5','Finland'),879 => Array('5','France'),241 => Array('5','Gabon'),220 => Array('5','Gambia'),995 => Array('5','Georgia'),49 => Array('5','Germany'),233 => Array('2','Ghana'),30 => Array('5','Greece'),852 => Array('5','Hong Kong'),36 => Array('5','Hungary'),354 => Array('5','Iceland'),91 => Array('2','India'),62 => Array('5','Indonesia'),98 => Array('5','Iran'),964 => Array('5','Iraq'),353 => Array('5','Ireland'),972 => Array('5','Israel'),39 => Array('5','Italy'),962 => Array('5','Jordan'),7 => Array('5','Kazakhstan'),254 => Array('5','Kenya'),965 => Array('5','Kuwait'),371 => Array('5','Latvia'),961 => Array('5','Lebanon'),231 => Array('5','Liberia'),218 => Array('5','Libya'),423 => Array('5','Liechtenstein'),370 => Array('5','Lithuania'),352 => Array('5','Luxembourg'),853 => Array('5','Macao'),389 => Array('5','Macedonia'),261 => Array('5','Madagascar'),265 => Array('5','Malawi'),60 => Array('2','Malaysia'),960 => Array('5','Maldives'),223 => Array('5','Mali'),356 => Array('5','Malta'),596 => Array('5','Martinique'),222 => Array('5','Mauritania'),230 => Array('5','Mauritius'),373 => Array('5','Moldova'),377 => Array('5','Monaco'),976 => Array('5','Mongolia'),1664 => Array('5','Montserrat'),212 => Array('6','Morocco'),258 => Array('5','Mozambique'),264 => Array('5','Namibia'),977 => Array('5','Nepal'),31 => Array('5','Netherlands'),599 => Array('5','Netherlands Antilles'),64 => Array('5','New Zealand'),505 => Array('5','Nicaragua'),227 => Array('3','Niger'),234 => Array('1','Nigeria'),47 => Array('5','Norway'),968 => Array('5','Oman'),92 => Array('1','Pakistan'),680 => Array('5','Palau'),972 => Array('5','Palestine w/Israel'),507 => Array('5','Panama'),63 => Array('5','Philippines'),48 => Array('5','Poland'),351 => Array('5','Portugal'),974 => Array('5','Qatar'),262 => Array('5','Reunion'),40 => Array('5','Romania'),7 => Array('1','Russian Federation'),250 => Array('5','Rwanda'),378 => Array('5','San Marino'),966 => Array('5','Saudi Arabia'),221 => Array('5','Senegal'),248 => Array('5','Seychelles'),232 => Array('5','Sierra Leone'),65 => Array('5','Singapore'),421 => Array('5','Slovakia'),386 => Array('5','Slovenia'),252 => Array('5','Somalia'),27 => Array('2','South Africa'),34 => Array('5','Spain'),94 => Array('5','Sri Lanka'),249 => Array('5','Sudan'),597 => Array('5','Suriname'),268 => Array('5','Swaziland'),46 => Array('5','Sweden'),41 => Array('5','Switzerland'),963 => Array('5','Syria'),886 => Array('5','Taiwan'),992 => Array('5','Tajikistan'),255 => Array('5','Tanzania'),66 => Array('3','Thailand'),216 => Array('5','Tunisia'),90 => Array('5','Turkey'),256 => Array('5','Uganda'),380 => Array('5','Ukraine'),971 => Array('2','United Arab Emirates'),44 => Array('5','United Kingdom'),1 => Array('2','United States'),598 => Array('5','Uruguay'),998 => Array('5','Uzbekistan'),678 => Array('5','Vanuatu'),58 => Array('5','Venezuela'),84 => Array('5','Vietnam'),967 => Array('5','Yemen'),381 => Array('5','Yugoslavia'),260 => Array('5','Zambia'),263 => Array('5','Zimbabwe'));
}

function removeZero($phone){
	if(stripos($phone, "0") !== false){
		$x = stripos($phone, "0");
		if($x == 0){
			return substr($phone, 1);
		}
	}
	return $phone;
}

function countryExit($phone,&$unit=5,&$name="OTHERS",$filter=false){
	if(strlen($phone) <= 5)
		return false;
	$array = getCountries();
	$istrue = false;
	$a3 = substr($phone, 0, 3);
	$a2 = substr($phone, 0, 2);
	$a1 = substr($phone, 0, 1);
	if(isset($array[$a3]))
		$istrue = $a3;
	else if(isset($array[$a2]))
		$istrue = $a2;
	else if(isset($array[$a1]))
		$istrue = $a1;

	if($istrue === false)
		return false;

	$unit = $array[$istrue][0];
	$name =  $array[$istrue][1];

	if($filter !== false){
		if(strlen($filter) <= 3){
			if($filter != $istrue)
				return false;
		}else if(strtolower($name) != strtolower($filter)){
			return false;
		}
	}
	return true;
}


