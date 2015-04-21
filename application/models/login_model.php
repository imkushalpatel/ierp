<?php
class Login_model extends CI_Model {
	function Login_model() {
		parent::__construct ();
	}
	function login_mobile($Username = '', $Password = '', $Company = 0) {
		$this->load->database ();
		$this->load->library ( 'session' );
		
		$data = array ();
		
		$this->db->select ( 'usermaster.UserNo, usermaster.UserId, usermaster.Username, usermaster.UserType, usermaster.Ifdeactivated, usermaster.DivisionId, usermaster.EntityId' );
		
		$this->db->from ( 'usermaster' );
		
		$this->db->where ( 'Username', $Username );
		$this->db->where ( 'Password', md5 ( $Password ) );
		$this->db->where ( 'IfDeactivated', '1' );
		$this->db->where ( 'EntityId', $Company );
		
		$query = $this->db->get ();
		
		$result = $query->result ();
		
		if (count ( $result ) > 0) {
			$data ['EmpId'] = $result [0]->UserId;
			$data ['UserId'] = $result [0]->UserNo;
			$data ['EntityId'] = $result [0]->EntityId;
			$data ['Username'] = $result [0]->Username;
			$data ['UserType'] = $result [0]->UserType;
			$data ['DivisionId'] = $result [0]->DivisionId;
			$data ['Login'] = 1;
		} else {
			$data ['Login'] = 0;
		}
		
		return $data;
	}
	function get_formname($UserId, $EmpId) {
		$this->load->database ();
		$this->load->library ( 'session' );
		
		// //////////////////////////////////
		
		$this->db->select ( 'UserType' );
		$this->db->from ( 'usermaster' );
		$this->db->where ( "usermaster.UserNo", $UserId );
		
		$query = $this->db->get ();
		$UserType = $query->result ();
		
		$profiles = array ();
		
		if ($UserType [0]->UserType == 'SuperAdmin') {
			$this->db->select ( 'ProfileId' );
			$this->db->from ( 'profilemaster' );
			$this->db->where ( "profilemaster.ProfileType", 0 );
			
			$query = $this->db->get ();
			$profiledata = $query->result ();
			
			$profiles [0] = $profiledata [0]->ProfileId;
		} elseif ($UserType [0]->UserType == 'ITAdmin') {
			$this->db->select ( 'ProfileId' );
			$this->db->from ( 'profilemaster' );
			$this->db->where ( "profilemaster.ProfileType", '-2' );
			$this->db->where ( "profilemaster.ProfileName", 'IT Admin' );
			
			$query = $this->db->get ();
			$profiledata = $query->result ();
			
			$profiles [0] = $profiledata [0]->ProfileId;
		} elseif ($UserType [0]->UserType == 'ResearchAdmin') {
			$this->db->select ( 'ProfileId' );
			$this->db->from ( 'profilemaster' );
			$this->db->where ( "profilemaster.ProfileType", '-2' );
			$this->db->where ( "profilemaster.ProfileName", 'Research Admin' );
			
			$query = $this->db->get ();
			$profiledata = $query->result ();
			
			$profiles [0] = $profiledata [0]->ProfileId;
		} elseif ($UserType [0]->UserType == 'OverheadAdmin') {
			$this->db->select ( 'ProfileId' );
			$this->db->from ( 'profilemaster' );
			$this->db->where ( "profilemaster.ProfileType", '-2' );
			$this->db->where ( "profilemaster.ProfileName", 'Overhead Admin' );
			
			$query = $this->db->get ();
			$profiledata = $query->result ();
			
			$profiles [0] = $profiledata [0]->ProfileId;
		} else {
			$this->db->select ( 'ProfileId' );
			$this->db->from ( 'profileprojectmapping' );
			$this->db->where ( "profileprojectmapping.UserId", $EmpId );
			
			$query = $this->db->get ();
			$profiledata = $query->result ();
			
			for($i = 0; $i < count ( $profiledata ); $i ++) {
				$profiles [$i] = $profiledata [$i]->ProfileId;
			}
			
			$this->db->select ( 'ProfileId' );
			$this->db->from ( 'profilemaster' );
			$this->db->where ( "profilemaster.ProfileType", - 1 );
			
			$query = $this->db->get ();
			$profiledata = $query->result ();
			
			$profiles [count ( $profiles )] = $profiledata [0]->ProfileId;
		}
		
		// echo '<pre>';print_r($profiles); exit;
		
		$this->db->select ( 'formmaster.FormId, FormName' );
		
		$this->db->from ( 'menumaster' );
		
		$this->db->join ( 'formmaster', 'formmaster.FormId = menumaster.FormId' );
		
		$this->db->join ( 'accesscontrol', 'accesscontrol.FormId = menumaster.FormId' );
		
		$this->db->where_in ( 'accesscontrol.ProfileId', $profiles );
		
		$this->db->where ( '(accesscontrol.ReadRight <> 0 OR accesscontrol.WriteRight <> 0 OR accesscontrol.ModifyRight <> 0 OR accesscontrol.DeleteRight <> 0)' );
		
		$this->db->group_by ( 'menumaster.MenuId' );
		
		$query = $this->db->get ();
		
		$menuresult = $query->result ();
		
		$FormName = array ();
		
		foreach ( $menuresult as $row ) {
			$FormName [] = $row->FormName;
		}
		
		return $FormName;
	}
}
?>
