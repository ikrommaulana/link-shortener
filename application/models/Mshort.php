<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Mshort extends CI_Model {
	function __construct()
    {
        parent::__construct();
    }
	
	function create($data) {  
         $query = $this->db->insert('url',$data);  
    }  
	
	
	function check_link($short_link) {
        $query = $this->db->get_where('url', array('short_link' => $short_link));
        return $query;
    } 
	
	
	function hit_counter($id) {
        $this->db->set('hit', '`hit`+1', FALSE);
		$this->db->where('id', $id);
		$this->db->update('url');
    } 
	
	
	function visitor_detail($data) {
         $query = $this->db->insert('visitor',$data);  
    } 
	
	

}