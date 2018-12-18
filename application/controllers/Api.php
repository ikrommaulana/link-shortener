<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->database();
		$this->load->model('mshort');
        $this->load->helper('url','form');
        $this->load->library('form_validation'); 
    }


	public function index($short_url)
	{
		$query = $this->mshort->check_link($short_url);
		$result = $query->result();
		$hitcount = $query->num_rows();

		$url_row 	= mysql_fetch_object($result);

		 if($hitcount > 0){
		    $this->mshort->hit_counter($short_url);
		    header("location: $url_row->url_asli");
		  }else{
		    echo "<h1>URL Tidak ditemukan :(</h1>";
		  }
	}
}
