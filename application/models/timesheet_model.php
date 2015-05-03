<?php
class Timesheet_model extends CI_Model {
	function Timesheet_model() {
		parent::__construct ();
	}
	function SaveTimesheetEntryMobile($WeekNo, $Month, $Year) {
		$this->load->database ();
		$this->load->library ( 'session' );
		
		$MonthName = array (
				
				'01' => 'Jan',
				'02' => 'Feb',
				'03' => 'Mar',
				'04' => 'Apr',
				'05' => 'May',
				'06' => 'Jun',
				'07' => 'Jul',
				'08' => 'Aug',
				'09' => 'Sep',
				'10' => 'Oct',
				'11' => 'Nov',
				'12' => 'Dec',
				'1' => 'Jan',
				'2' => 'Feb',
				'3' => 'Mar',
				'4' => 'Apr',
				'5' => 'May',
				'6' => 'Jun',
				'7' => 'Jul',
				'8' => 'Aug',
				'9' => 'Sep' 
		);
		
		if ($WeekNo != '' && $Month != '' && $Year != '') {
			if ($this->input->post ( 'TimeSheetsId' ) != '') {
				if ($this->input->post ( 'TimeSheetsId' ) == '0') {
					if ($this->input->post ( 'Project' ) != '' && $this->input->post ( 'NoofHours' ) != '') {
						$SubTask = $this->input->post ( 'SubTask' );
						
						if ($SubTask == '')
							$SubTask = NULL;
						
						$data = array (
								
								'ProjectId' => $this->input->post ( 'Project' ),
								'TaskId' => $this->input->post ( 'Task' ),
								'SubTaskId' => $SubTask,
								'Comments' => $this->input->post ( 'Comments' ),
								'NoofHours' => $this->input->post ( 'NoofHours' ),
								'TimesheetWeek' => $WeekNo,
								'TimesheetMonth' => $MonthName [$Month] . '-' . $Year,
								'EntityId' => $this->session->userdata ( 'EntityId' ),
								'InsertedDate' => date ( "Y-m-d" ),
								'InsertedBy' => $this->session->userdata ( 'UserId' ) 
						);
						
						$this->db->insert ( 'timesheetsmaster', $data );
					}
				} else {
					if ($this->input->post ( 'Project' ) != '' && $this->input->post ( 'NoofHours' ) != '' && $this->input->post ( 'Delete' ) == '0') {
						$SubTask = $this->input->post ( 'SubTask' );
						
						if ($SubTask == '')
							$SubTask = NULL;
						
						$data = array (
								
								'ProjectId' => $this->input->post ( 'Project' ),
								'TaskId' => $this->input->post ( 'Task' ),
								'SubTaskId' => $SubTask,
								'Comments' => $this->input->post ( 'Comments' ),
								'NoofHours' => $this->input->post ( 'NoofHours' ),
								'ModifiedOn' => date ( "Y-m-d" ) 
						);
						
						$this->db->where ( 'TimeSheetsId', $this->input->post ( 'TimeSheetsId' ) );
						$this->db->update ( 'timesheetsmaster', $data );
					} 

					else if ($this->input->post ( 'Delete' ) == '0') {
						$this->db->where ( 'TimeSheetsId', $this->input->post ( 'TimeSheetsId' ) );
						$this->db->delete ( 'timesheetsmaster' );
					}
				}
			}
		}
	}
	function TimeSheetDataWeekTotal($Month = 0, $Year = 0) {
		$MonthName = array (
				
				'01' => 'Jan',
				'02' => 'Feb',
				'03' => 'Mar',
				'04' => 'Apr',
				'05' => 'May',
				'06' => 'Jun',
				'07' => 'Jul',
				'08' => 'Aug',
				'09' => 'Sep',
				'10' => 'Oct',
				'11' => 'Nov',
				'12' => 'Dec',
				'1' => 'Jan',
				'2' => 'Feb',
				'3' => 'Mar',
				'4' => 'Apr',
				'5' => 'May',
				'6' => 'Jun',
				'7' => 'Jul',
				'8' => 'Aug',
				'9' => 'Sep' 
		);
		$this->load->database ();
		$this->db->select ( 'sum(NoofHours) as NoofHours, TimesheetWeek, TimesheetMonth' );
		$this->db->from ( 'timesheetsmaster' );
		
		$this->db->where ( 'timesheetsmaster.InsertedBy', $this->session->userdata ['UserId'] );
		$this->db->where ( 'TimesheetMonth', $MonthName [$Month] . '-' . $Year );
		
		$this->db->group_by ( 'TimesheetWeek' );
		$this->db->order_by ( 'TimesheetWeek', 'asc' );
		
		$query = $this->db->get ();
		return $query->result ();
	}
	function TimeSheetDataByWeek($week = 0, $Month = 0, $Year = 0) {
		$MonthName = array (
				
				'01' => 'Jan',
				'02' => 'Feb',
				'03' => 'Mar',
				'04' => 'Apr',
				'05' => 'May',
				'06' => 'Jun',
				'07' => 'Jul',
				'08' => 'Aug',
				'09' => 'Sep',
				'10' => 'Oct',
				'11' => 'Nov',
				'12' => 'Dec',
				'1' => 'Jan',
				'2' => 'Feb',
				'3' => 'Mar',
				'4' => 'Apr',
				'5' => 'May',
				'6' => 'Jun',
				'7' => 'Jul',
				'8' => 'Aug',
				'9' => 'Sep' 
		);
		$this->load->database ();
		
		$this->db->select ( 'timesheetsmaster.*, ProjectId, ProjectName, TaskName, SubTaskName' );
		$this->db->from ( 'timesheetsmaster' );
		
		$this->db->join ( 'ordermaster', 'ordermaster.OrderId = timesheetsmaster.ProjectId' );
		$this->db->join ( 'taskmaster', 'taskmaster.TaskId = timesheetsmaster.TaskId', 'LEFT' );
		$this->db->join ( 'subtaskmaster', 'subtaskmaster.SubTaskId = timesheetsmaster.SubTaskId', 'LEFT' );
		
		$this->db->where ( 'timesheetsmaster.InsertedBy', $this->session->userdata ['UserId'] );
		$this->db->where ( 'TimesheetMonth', $MonthName [$Month] . '-' . $Year );
		$this->db->where ( 'TimesheetWeek', $week );
		
		$this->db->group_by ( 'timesheetsmaster.TimeSheetsId' );
		$this->db->order_by ( 'timesheetsmaster.TimeSheetsId', 'ASC' );
		
		$query = $this->db->get ();
		return $query->result ();
	}
	function TimeSheetDataById($timesheetid = 0) {
		$MonthName = array (
				
				'01' => 'Jan',
				'02' => 'Feb',
				'03' => 'Mar',
				'04' => 'Apr',
				'05' => 'May',
				'06' => 'Jun',
				'07' => 'Jul',
				'08' => 'Aug',
				'09' => 'Sep',
				'10' => 'Oct',
				'11' => 'Nov',
				'12' => 'Dec',
				'1' => 'Jan',
				'2' => 'Feb',
				'3' => 'Mar',
				'4' => 'Apr',
				'5' => 'May',
				'6' => 'Jun',
				'7' => 'Jul',
				'8' => 'Aug',
				'9' => 'Sep' 
		);
		$this->load->database ();
		$this->db->select ( 'timesheetsmaster.*, ProjectId, ProjectName,TaskName,SubTaskName' );
		$this->db->from ( 'timesheetsmaster' );
		
		$this->db->join ( 'ordermaster', 'ordermaster.OrderId = timesheetsmaster.ProjectId' );
		$this->db->join ( 'taskmaster', 'taskmaster.TaskId = timesheetsmaster.TaskId', 'LEFT' );
		$this->db->join ( 'subtaskmaster', 'subtaskmaster.SubTaskId = timesheetsmaster.SubTaskId', 'LEFT' );
		
		$this->db->where ( 'TimeSheetsId', $timesheetid );
		
		$query = $this->db->get ();
		return $query->result ();
	}
}
?>