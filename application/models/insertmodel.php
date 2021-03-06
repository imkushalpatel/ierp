<?php
class insertmodel extends CI_Model {
	function listmodel() {
		parent::__construct ();
	}
	function dailywork($proj = '', $user = '', $cat = '', $loc = '', $date = '', $work = '', $com = '') {
		$this->load->database ();
		$this->db->distinct ( 'UserId' );
		$this->db->select ( 'ordermaster.OrderId,associatemaster.AssociateId,locationmaster.LocationId,categorymaster.CategoryId' );
		$this->db->from ( 'profileprojectmapping' );
		$this->db->join ( 'ordermaster', 'ordermaster.OrderId=profileprojectmapping.ProjectId' );
		$this->db->join ( 'associatemaster', 'associatemaster.AssociateId=profileprojectmapping.UserId' );
		$this->db->join ( 'locationmaster', 'locationmaster.OrderId=profileprojectmapping.ProjectId' );
		$this->db->join ( 'categorymaster', 'categorymaster.OrderId=profileprojectmapping.ProjectId' );
		
		$this->db->where ( 'ordermaster.ProjectName', $proj );
		$this->db->like ( 'concat(associatemaster.FirstName," ",associatemaster.LastName)', $user );
		$this->db->where ( 'categorymaster.CategoryName', $cat );
		$this->db->where ( 'locationmaster.LocationName', $loc );
		
		$query = $this->db->get ();
		$result = $query->result ();
		if (count ( $result ) > 0) {
			
			$data ['OrderId'] = $result [0]->OrderId;
			$data ['HeadId'] = $result [0]->AssociateId;
			$data ['LocationId'] = $result [0]->LocationId;
			$data ['CategoryId'] = $result [0]->CategoryId;
			$data ['Date'] = $date;
			$data ['WorkDone'] = $work;
			$data ['Comments'] = $com;
			
			$this->db->insert ( 'dailyworkmaster', $data );
			$temp ['status'] = true;
			
			return true;
		} else
			return false;
	}
}
?>