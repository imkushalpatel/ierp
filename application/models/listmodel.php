<?php
class listmodel extends CI_Model {
	function listmodel() {
		parent::__construct ();
	}
	function projectslist() {
		$data = array ();
		
		$this->load->database ();
		$this->db->distinct ();
		
		$this->db->select ( 'OrderId as ProjectId, ProjectName' );
		
		$this->db->from ( 'ordermaster' );
		
		$this->db->where ( 'IfProjectClosed', 0 );
		$this->db->where ( 'ParentOrderId', 0 );
		
		$this->db->order_by ( 'ProjectName', 'ASC' );
		
		$query = $this->db->get ();
		
		return $query->result ();
	}
	function projectheadslist($projectid = '') {
		$this->load->database ();
		$this->db->distinct ( 'UserId' );
		$this->db->order_by ( 'associatemaster.FirstName', 'ASC' );
		$this->db->select ( 'associatemaster.AssociateId' );
		$this->db->select ( 'concat(associatemaster.FirstName," ",associatemaster.LastName) as Name', false );
		
		$this->db->from ( 'profileprojectmapping' );
		$this->db->where ( 'ordermaster.OrderId', $projectid );
		$this->db->join ( 'ordermaster', 'ordermaster.OrderId=profileprojectmapping.ProjectId' );
		$this->db->join ( 'associatemaster', 'associatemaster.AssociateId=profileprojectmapping.UserId' );
		$this->db->join ( 'profilemaster', 'profilemaster.ProfileId=profileprojectmapping.ProfileId' );
		$this->db->where ( 'profilemaster.ProfileName', 'Client Servicing/ Project Head' );
		$query = $this->db->get ();
		return $query->result ();
		// var_dump($result);
	}
	function categorylist($projectid = '') {
		$this->load->database ();
		$this->db->order_by ( 'categorymaster.CategoryName', 'ASC' );
		$this->db->select ( 'categorymaster.CategoryId,categorymaster.CategoryName' );
		$this->db->from ( 'categorymaster' );
		$this->db->where ( 'ordermaster.OrderId', $projectid );
		$this->db->join ( 'ordermaster', 'ordermaster.OrderId=categorymaster.OrderId' );
		$query = $this->db->get ();
		return $query->result ();
	}
	function locationlist($projectid = '') {
		$this->load->database ();
		$this->db->order_by ( 'locationmaster.LocationName', 'ASC' );
		$this->db->select ( 'locationmaster.LocationId,locationmaster.LocationName' );
		$this->db->from ( 'locationmaster' );
		$this->db->where ( 'ordermaster.OrderId', $projectid );
		$this->db->join ( 'ordermaster', 'ordermaster.OrderId=locationmaster.OrderId' );
		
		$query = $this->db->get ();
		return $query->result ();
	}
	function issueslist() {
		$this->load->database ();
		$this->db->distinct ( 'TypeOfIssue' );
		$this->db->select ( 'TypeOfIssue as Issue' );
		$this->db->from ( 'dailyworkmaster' );
		$query = $this->db->get ();
		return $query->result ();
	}
	function listhead() {
		$this->load->database ();
		$this->db->distinct ( 'UserId' );
		$this->db->order_by ( 'associatemaster.FirstName', 'ASC' );
		$this->db->select ( 'associatemaster.AssociateId' );
		$this->db->select ( 'concat(associatemaster.FirstName," ",associatemaster.LastName) as Name', false );
		
		$this->db->from ( 'profileprojectmapping' );
		$this->db->join ( 'profilemaster', 'profilemaster.ProfileId=profileprojectmapping.ProfileId' );
		$this->db->where ( 'profilemaster.ProfileName', 'Client Servicing/ Project Head' );
		// $this->db->where_in('profileprojectmapping.ProfileId',array(8,13,22));
		$this->db->join ( 'associatemaster', 'associatemaster.AssociateId=profileprojectmapping.UserId' );
		$query = $this->db->get ();
		return $query->result ();
	}
}
?>