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
		function getprojects()
		{
			$this->load->model('listmodel');
			$data = $this->listmodel->get_projects();
			echo json_encode($data);
		}
		function getprojectheads($proj='')
		{
			//$proj=$_GET["proj"];
			$this->load->model('listmodel');
			$data=$this->listmodel->get_heads($proj);
			echo json_encode($data);
		}
		function getcategory($proj='')
		{
			$this->load->model('listmodel');
			$data=$this->listmodel->get_category($proj);
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
		function getdelaylistproj($proj='')
		{
			$this->load->model('listmodel');
			$data=$this->listmodel->get_delayproj($proj);
			echo json_encode($data);
		}
		function getdelaylistcat($cat='')
		{
			$this->load->model('listmodel');
			$data=$this->listmodel->get_delaycat($cat);
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
	
