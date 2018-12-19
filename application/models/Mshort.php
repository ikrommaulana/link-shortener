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

    function get_url(){
    	$query = $this->db->get('url');
    	return $query;
    } 

    function get_visitor(){
    	$this->db->select('*');
		$this->db->from('visitor');
		$this->db->join('url', 'url.id = visitor.id_url');
		$query = $this->db->get();
    	return $query;
    } 
	
	

}