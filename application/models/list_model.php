<?php
class List_model extends CI_Model {
	function List_model() {
		parent::__construct ();
	}
	function ProjectList() {
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
	function TaskList($OrderId) {
		$this->load->database ();
		
		$this->db->select ( 'TaskId, TaskName' );
		$this->db->from ( 'taskmaster' );
		$this->db->where ( 'OrderId', $OrderId );
		
		$this->db->order_by ( 'TaskName', 'ASC' );
		
		$query = $this->db->get ();
		$result = $query->result ();
		
		return $result;
	}
	function SubTaskList($TaskId) {
		$this->load->database ();
		
		$this->db->select ( 'SubTaskId, SubTaskName' );
		$this->db->from ( 'subtaskmaster' );
		$this->db->where ( 'TaskId', $TaskId );
		
		$this->db->order_by ( 'SubTaskName', 'ASC' );
		
		$query = $this->db->get ();
		$result = $query->result ();
		
		return $result;
	}
}
?>
