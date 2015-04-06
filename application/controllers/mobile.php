<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class mobile extends CI_Controller
	{
		function mobile()
		{
			parent::__construct();
			$this->load->library('table');
			$this->load->library('session');
			$this->load->helper('html');
			$this->load->helper('url');
		}
		function Login()
		{
			if(isset($_POST['user']) && isset($_POST['pass'])){
			$company=1;
			$this->load->model('loginmodel');
			$data=$this->loginmodel->login_check($_POST['user'],$_POST['pass'],$company);
			echo json_encode($data);
			}
		}
		
		function getProjectsList()
		{			
			
			$this->load->model('listmodel');
			$data = $this->listmodel->projectslist();
			$data1['ProjectList']=$data;
			//$data1['temp']=$this->session->userdata('EntityId');
			echo json_encode($data1);
		}
		function getProjectHeadsList($projectid)
		{
			//$proj=$_POST["projid"];
			$this->load->model('listmodel');
			$data=$this->listmodel->projectheadslist($projectid);
			$data1['ProjectHeadsList']=$data;
			echo json_encode($data1);
		}
		function getCategoryList($projectid='')
		{
			$this->load->model('listmodel');
			$data=$this->listmodel->categorylist($projectid);
			$data1['CategoryList']=$data;
			echo json_encode($data1);
		}
		function getLocationList($projectid='')
		{
			$this->load->model('listmodel');
			$data=$this->listmodel->locationlist($projectid);
			$data1['LocationList']=$data;
			echo json_encode($data1);
		}
		function insertDailyWork()
		{
			
			$this->load->model('insertmodel');
			$data=$this->insertmodel->dailywork($_POST['proj'],$_POST['user'],$_POST['cat'],$_POST['loc'],$_POST['date'],$_POST['work'],$_POST['comm']);
			echo json_encode($data);
		}
		function getDate()
		{
			//$data['Date']=date("Y.m.d.h.i.s");
			$data['Date']=date("Y-M-d");
			echo json_encode($data);
		}
		function getdaylist($proj='')
		{
			$this->load->model('generatelistmodel');
			$data=$this->generatelistmodel->get_daylist($proj);
			echo json_encode($data);
		}
		function getdelaylistproj($proj='',$from='',$to='')
		{
			$this->load->model('generatelistmodel');
			$data=$this->generatelistmodel->get_delayproj($proj,$from,$to);
			echo json_encode($data);
		}
		function getdelaylistcat($cat='',$from='',$to='')
		{
			$this->load->model('generatelistmodel');
			$data=$this->generatelistmodel->get_delaycat($cat,$from,$to);
			echo json_encode($data);
		}
		function getIssueList()
		{
			$this->load->model('listmodel');
			$data=$this->listmodel->issueslist();
			$data1['IssuesList']=$data;
			echo json_encode($data1);
		}
	}
?>
	
