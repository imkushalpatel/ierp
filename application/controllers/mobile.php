<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class mobile extends CI_Controller {
	function mobile() {
		parent::__construct ();
		$this->load->library ( 'table' );
		$this->load->library ( 'session' );
		$this->load->helper ( 'html' );
		$this->load->helper ( 'url' );
	}
	function login_check($Username = '', $Password = '', $Company = 0) {
		$this->load->model ( 'login_model' );
		
		$data = $this->login_model->login_mobile ( $Username, $Password, $Company );
		
		if ($data ['Login'] == 1) {
			
			// $data['FormName'] = $this->login_model->get_formname($data['UserId'], $data['EmpId']);
			
			$json_response = json_encode ( $data );
			
			if (isset ( $_GET ["callback"] )) {
				$json_response = $_GET ["callback"] . "(" . $json_response . ")";
			}
			
			echo $json_response;
		} else {
			$data ['Error'] = "Invalid";
			
			$json_response = json_encode ( $data );
			
			if (isset ( $_GET ["callback"] )) {
				$json_response = $_GET ["callback"] . "(" . $json_response . ")";
			}
			
			echo $json_response;
		}
	}
	function timesheet_insert($WeekNo, $Month, $Year, $EntityId = 1, $UserId = 0) {
		$this->load->model ( 'timesheet_model' );
		
		$this->session->set_userdata ( 'UserId', $UserId );
		
		$this->session->set_userdata ( 'EntityId', $EntityId );
		
		$this->load->model ( 'timesheet_model' );
		
		$this->timesheet_model->SaveTimesheetEntryMobile ( $WeekNo, $Month, $Year );
		
		$this->session->unset_userdata ( 'UserId' );
		
		$this->session->unset_userdata ( 'EntityId' );
		
		$data ['Success'] = 1;
		$json_response = json_encode ( $data );
		echo $json_response;
		// echo 1;
	}
	function getProjectList() {
		$this->load->model ( 'list_model' );
		
		$data1 = $this->list_model->ProjectList ();
		
		$data ['ProjectList'] = $data1;
		
		$json_response = json_encode ( $data );
		
		echo $json_response;
	}
	function getTaskList($orderid = 0) {
		$this->load->model ( 'list_model' );
		
		$data1 = $this->list_model->TaskList ( $orderid );
		
		$data ['TaskList'] = $data1;
		
		$json_response = json_encode ( $data );
		
		echo $json_response;
	}
	function getSubTaskList($taskid = 0) {
		$this->load->model ( 'list_model' );
		
		$data1 = $this->list_model->SubTaskList ( $taskid );
		
		$data ['SubTaskList'] = $data1;
		
		$json_response = json_encode ( $data );
		
		echo $json_response;
	}
	function getTimeSheetDataWeekTotal($Month = NULL, $Year = NULL, $UserId = 0) {
		$this->load->model ( 'timesheet_model' );
		
		$this->session->set_userdata ( 'UserId', $UserId );
		
		$data = $this->timesheet_model->TimeSheetDataWeekTotal ( $Month, $Year );
		
		$json_response = json_encode ( $data );
		
		if (isset ( $_GET ["callback"] )) {
			$json_response = $_GET ["callback"] . "(" . $json_response . ")";
		}
		
		$this->session->unset_userdata ( 'UserId' );
		
		echo $json_response;
	}
	function getTimeSheetDataByWeek($Week = NULL, $Month = NULL, $Year = NULL, $UserId = 0) {
		$this->load->model ( 'timesheet_model' );
		
		$this->session->set_userdata ( 'UserId', $UserId );
		
		$data = $this->timesheet_model->TimeSheetDataByWeek ( $Week, $Month, $Year );
		
		$json_response = json_encode ( $data );
		
		if (isset ( $_GET ["callback"] )) {
			$json_response = $_GET ["callback"] . "(" . $json_response . ")";
		}
		
		$this->session->unset_userdata ( 'UserId' );
		
		echo $json_response;
	}
	function getTimeSheetDataById($timesheetid = 0) {
		$this->load->model ( 'timesheet_model' );
		$data = $this->timesheet_model->TimeSheetDataById ( $timesheetid );
		$json_response = json_encode ( $data );
		if (isset ( $_GET ["callback"] )) {
			$json_response = $_GET ["callback"] . "(" . $json_response . ")";
		}
		echo $json_response;
	}
}

?>