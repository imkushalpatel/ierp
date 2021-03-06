<?php
class loginmodel extends CI_Model {
	function loginmodel() {
		parent::__construct ();
	}
	function login_check($user = '', $pass = '', $company = '') {
		$this->load->database ();
		$this->db->select ( 'usermaster.UserId,usermaster.UserNo,usermaster.EntityId,usermaster.Username,usermaster.UserType,usermaster.DivisionId' );
		// $this->db->select('concat(associatemaster.FirstName," ",associatemaster.LastName) as Name',false);
		$this->db->from ( 'usermaster' );
		// $this->db->join('associatemaster','associatemaster.AssociateId=usermaster.UserId');
		$this->db->where ( 'usermaster.Username', $user );
		$this->db->where ( 'usermaster.Password', $pass );
		$this->db->where ( 'usermaster.IfDeactivated', '1' );
		$this->db->where ( 'usermaster.EntityId', $company );
		$query = $this->db->get ();
		$result = $query->result ();
		if (count ( $result ) > 0) {
			$data ['EmpId'] = $result [0]->UserNo;
			
			$data ['EntityId'] = $result [0]->EntityId;
			$data ['Username'] = $result [0]->Username;
			if ($result [0]->UserType != 'Normal') {
				$data ['UserType'] = 1;
			} else {
				$data ['UserType'] = 0;
			}
			$data ['DivisionId'] = $result [0]->DivisionId;
			// $data['Name']=$result[0]->Name;
			$data ['Login'] = true;
			$this->session->set_userdata ( "UserId", $result [0]->UserId );
			$this->session->set_userdata ( "EntityId", $result [0]->EntityId );
		} else
			$data ['Login'] = false;
		return $data;
	}
}
?>