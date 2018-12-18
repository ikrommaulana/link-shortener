<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Short extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->database();
		$this->load->model('mshort');
        $this->load->helper('url','form');
        $this->load->library('form_validation'); 
    }


	public function create_url($long_url)
	{
		$src = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
		$str = '';
		for ($i= 0;$i<$long_url;$i++){
			$pos = rand(0,strlen($src)-1);
			$str .= $src[$pos];
		}
		return $str;
	}


	public function index()
	{
		$this->load->helper(array('form', 'url'));
		define('DB_SERVER', 'localhost');
		define('DB_USERNAME', 'root');
		define('DB_PASSWORD', '');
		define('DB_DATABASE', 'link_shortener');
		$db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

        $this->form_validation->set_rules('long_url', 'URL','required');  
		
		if($this->form_validation->run() == FALSE){ 
			$data['input_url'] 	= 'Masukkan URL Anda!';
			$data['title'] 		= 'Shortener Link for your URL!';

			$this->load->view('shortener',$data);
		} else {
			$original_link = mysqli_real_escape_string($db,$_POST["long_url"]);

			$short_url = $this->create_url(2).substr(uniqid(), 6, 5);

			$query['original_link']	= $original_link;  
			$query['short_link']	= $short_url; 
			$query['hit']	= '0'; 
			$query['created_date']= $this->input->post('created_date');
			$this->mshort->create($query);



			$data['input_url'] 	= 'Sukses!. Silahkan masukkan URL Anda kembali!';
			$data['title'] 		= base_url().'short/go/'.$short_url;
			$this->load->view('shortener',$data);
		}
	}


	public function go($url)
	{
		$query = $this->mshort->check_link($url);
		$result = $query->result();

		$hitcount = $query->num_rows();

		foreach ($result as $res){
			if($hitcount > 0){
			   	$this->mshort->hit_counter($res->id);

				//$ipaddress = $this->get_client_ip(); //jika diaktifkan di localhost maka akan error
				$ipaddress = '202.147.193.3';
				$details = json_decode(file_get_contents('http://ipinfo.io/'.$ipaddress.'/json'));
				//$details = var_export(unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ipaddress)));
				$data['id_url']	= $res->id; 
				$data['ip']		= $details->ip;  
				$data['city']		= $details->city; 
				$data['country']	= $details->country; 
				$data['visit_date']= date("Y-m-d"); 
				$this->mshort->visitor_detail($data);

			   	header("location: $res->original_link");
			}else{
			    echo "<h1>URL Tidak ditemukan :(</h1>";
			}
		}

	}


	public function get_client_ip() {
	    $ipaddress = '';
	    if (isset($_SERVER['HTTP_CLIENT_IP']))
	        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    else if(isset($_SERVER['HTTP_X_FORWARDED']))
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
	        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	    else if(isset($_SERVER['HTTP_FORWARDED']))
	        $ipaddress = $_SERVER['HTTP_FORWARDED'];
	    else if(isset($_SERVER['REMOTE_ADDR']))
	        $ipaddress = $_SERVER['REMOTE_ADDR'];
	    else
	        $ipaddress = 'UNKNOWN';
	    return $ipaddress;
	}


}
