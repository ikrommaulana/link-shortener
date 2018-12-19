<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->database();
		$this->load->model('mshort');
        $this->load->helper('url','form');
        $this->load->library('form_validation'); 
    }


	public function index()
	{
		$data['url']	= $this->mshort->get_url()->result();
		$this->load->view('dashboard',$data);
	}


	public function visitor()
	{
		$data['url']	= $this->mshort->get_visitor()->result();
		$this->load->view('visitor',$data);
	}


}
