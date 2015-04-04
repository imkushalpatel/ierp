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
			$user=$_POST['user'];
			$pass=$_POST['pass'];
			$company=1;
			$this->load->model('loginmodel');
			$data=$this->loginmodel->login_check($user,$pass,$company);
			
			echo json_encode($data);
			}
		}
		
		function getProjectsList()
		{
			$this->load->model('listmodel');
			$data = $this->listmodel->projectslist();
			$data1['ProjectList']=$data;
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
			echo json_encode($data);
		}
		function getlocation($proj='')
		{
			$this->load->model('listmodel');
			$data=$this->listmodel->get_location($proj);
			echo json_encode($data);
		}
		function insertdailywork($proj='',$user='',$cat='',$loc='',$date='',$work='',$com='')
		{
			$this->load->model('insertmodel');
			$data=$this->insertmodel->insertdaily(urldecode($proj),urldecode($user),urldecode($cat),urldecode($loc),urldecode($date),urldecode($work),urldecode($com));
			echo json_encode($data);
		}
		function getdate()
		{
			//$data['Date']=date("Y.m.d.h.i.s");
			$data['Date']=date("Y-M-d");
			echo json_encode($data);
		}
		function getdaylist($proj='')
		{
			$this->load->model('listmodel');
			$data=$this->listmodel->get_daylist($proj);
			echo json_encode($data);
		}
		function getdelaylistproj($proj='',$from='',$to='')
		{
			$this->load->model('listmodel');
			$data=$this->listmodel->get_delayproj($proj,$from,$to);
			echo json_encode($data);
		}
		function getdelaylistcat($cat='',$from='',$to='')
		{
			$this->load->model('listmodel');
			$data=$this->listmodel->get_delaycat($cat,$from,$to);
			echo json_encode($data);
		}
		function getissues()
		{
			$this->load->model('listmodel');
			$data=$this->listmodel->get_Issues();
			echo json_encode($data);
		}
	}
?>
	
