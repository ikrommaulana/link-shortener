<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Api extends REST_Controller {

	function __construct($config = 'rest')
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


	function index_get() {
        $get = $this->get('get');
        if ($get == 'url') {
			$this->db->where('status', 1);
            $area = $this->db->get('url')->result();
        } else {
            $this->db->where('status', 0);
            $area = $this->db->get('url')->result();
        }
        $this->response($area, 200);
    }


    function index_post() {

        define('DB_SERVER', 'localhost');
        define('DB_USERNAME', 'root');
        define('DB_PASSWORD', '');
        define('DB_DATABASE', 'link_shortener');
        $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

        $long = $this->post('url'); 
        if ($long !== '') {
            $original_link = mysqli_real_escape_string($db,$long);

            $short = $this->create_url(2).substr(uniqid(), 6, 5);

            $query['original_link'] = $original_link;  
            $query['short_link']    = $short; 
            $query['hit']   = '0'; 
            $query['created_date']= date('Y-m-d');
            $area = $this->mshort->create($query);
        }
        echo json_encode($query);
    }
}
