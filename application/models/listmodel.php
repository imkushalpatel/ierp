<?php
class listmodel extends CI_Model
	{
		function listmodel()
		{
			parent::__construct();
			
			
		}
		function projectslist()
		{
			$data=array();
			
			$this->load->database();
			$this->db->distinct();
		
		$this->db->select('OrderId as ProjectId, ProjectName');
		
		$this->db->from('ordermaster');
		
		$this->db->where('IfProjectClosed', 0);
		$this->db->where('ParentOrderId', 0);
		
		$this->db->order_by('ProjectName','ASC');
		
		$query = $this->db->get();
		
		return $query->result();
	
		}
		function projectheadslist($projectid='')
		{
			$this->load->database();
			$this->db->distinct('UserId');
			$this->db->order_by('associatemaster.FirstName','ASC');
			$this->db->select('associatemaster.AssociateId');
			$this->db->select('concat(associatemaster.FirstName," ",associatemaster.LastName) as Name',false);
			
			$this->db->from('profileprojectmapping');
			$this->db->where('ordermaster.OrderId',$projectid);
			$this->db->join('ordermaster','ordermaster.OrderId=profileprojectmapping.ProjectId');
			$this->db->join('associatemaster','associatemaster.AssociateId=profileprojectmapping.UserId');
			$query = $this->db->get();
			return  $query->result();
			//var_dump($result);
			
		}
		function categorylist($projectid='')
		{
			$this->load->database();
			$this->db->order_by('categorymaster.CategoryName','ASC');
			$this->db->select('categorymaster.CategoryId,categorymaster.CategoryName');
			$this->db->from('categorymaster');
			$this->db->where('ordermaster.OrderId',$projectid);
			$this->db->join('ordermaster','ordermaster.OrderId=categorymaster.OrderId');
			$query = $this->db->get();
			return $query->result();
			
		}
		function locationlist($projectid='')
		{
			$this->load->database();
			
			$this->db->select('locationmaster.LocationName');
			$this->db->from('locationmaster');
			$this->db->where('ordermaster.OrderId',$projectid);
			$this->db->join('ordermaster','ordermaster.OrderId=locationmaster.OrderId');
			
			$query = $this->db->get();
			return $query->result();
			
		}
		function get_daylist($proj='')
		{
			$this->load->database();
			$this->db->order_by('Date','asc');
			$this->db->select('Date,WorkDone,ordermaster.TotalWork,ordermaster.ProjectDeadline');
			$this->db->from('dailyworkmaster');
			$this->db->join('ordermaster','ordermaster.OrderId=dailyworkmaster.OrderId');
			$this->db->where('ordermaster.ProjectName',$proj);
			$query=$this->db->get();
			$result=$query->result();
			$j=0;
			$temp=null;
			$total=0;
			if(count($result)>0){
			while(count($result)>$j){
				$date=new DateTime($result[$j]->Date);
				
				$date=$date->format('M-Y');
				if($temp!=$date){
					$i=0;
					$sum=0;
					while(count($result) > $i)
					{
						
						$temp2=new DateTime($result[$i]->Date);
						$temp2=$temp2->format('M-Y');
						
						if($date==$temp2){
							$temp3=new DateTime($result[$i]->Date);
							$temp3=$temp3->format('d-D');
							$list[]=array('date'=>$temp3,'work'=>number_format($result[$i]->WorkDone,1));
							$sum=(float)$sum+$result[$i]->WorkDone;
						}
						$i++;
					}
				$data[]=array('month'=>$date,'sum'=>number_format($sum,1),'list'=>$list);
				$total=$total+$sum;
				unset($list);
				$temp=$date;
				}
				$j++;
			}
			//$data['Date']=$result[0]->Date;
			$data1['status']=true;
			$data1[$proj]=$data;
			$datediff1=new DateTime(date('d-M-Y'));
			$datediff2=new DateTime($result[0]->ProjectDeadline);
			$data1['deadline']=$datediff2->format('d-M-Y');
			
			$datediff1=new DateTime(date('d-M-Y'));
			
			$interval = date_diff($datediff1,$datediff2);
			$rrr=($result[0]->TotalWork-$total)/$interval->format('%R%a');
			$data1['rrr']=number_format($rrr,2);
			$data1['totalreq']=$result[0]->TotalWork;
			$data1['totaldone']=number_format($total,1);
			return $data1;
			}
			else
				$data1['status']=false;
			return $data1;
		}
		function get_delayproj($proj='',$from='',$to='')
		{
			$this->load->database();
			$this->db->order_by('TypeOfIssue','asc');
			//$this->db->select('Date,WorkDone,ordermaster.TotalWork,ordermaster.ProjectDeadline');
			$this->db->select('dailyworkmaster.*');
			$this->db->from('dailyworkmaster');
			if ($proj!='ALL') {
				$this->db->join('ordermaster','ordermaster.OrderId=dailyworkmaster.OrderId');
				$this->db->where('ordermaster.ProjectName',$proj);
			}
			if($from!='')
			{
				$this->db->where('dailyworkmaster.Date >=',$from);
			}
			if($to!='')
			{
				$this->db->where('dailyworkmaster.Date <=',$to);
			}
			$query=$this->db->get();
			$result=$query->result();
			if (count($result)>0)
			{
			$j=0;
			$temp=null;
			$sum=0;
			$list=array();
			while(count($result)>$j){
				$cat=$result[$j]->TypeOfIssue;
				if($cat!=$temp){
					$i=0;
					$counter=0;
					while(count($result)>$i){
						$cat1=$result[$i]->TypeOfIssue;
						if ($cat==$cat1){
							$counter++;
							
						}
						$i++;
					}
					$list[]=array('cat'=>$cat,'values'=>$counter);
					$sum=$sum+$counter;
				}
				$temp=$cat;
				$j++;
			}
			$data['status']=true;
			$data[$proj]=$list;
			$data['nocat']=count($result)-$sum;
			$data['total']=count($result);
			return $data;
			}
			else 
				$data['status']=false;
				return $data;	
		}
		function get_delaycat($cat='',$from='',$to='')
		{
			$this->load->database();
			$this->db->order_by('dailyworkmaster.OrderId','asc');
			//$this->db->select('Date,WorkDone,ordermaster.TotalWork,ordermaster.ProjectDeadline');
			$this->db->select('dailyworkmaster.*,ordermaster.ProjectName');
			$this->db->from('dailyworkmaster');
			$this->db->join('ordermaster','ordermaster.OrderId=dailyworkmaster.OrderId');
			if ($cat!='ALL') {
				if ($cat=='null')
				$this->db->where('dailyworkmaster.TypeOfIssue',NULL);
				else 
				$this->db->where('dailyworkmaster.TypeOfIssue',$cat);
			}
			if($from!='')
			{
				$this->db->where('dailyworkmaster.Date >=',$from);
			}
			if($to!='')
			{
				$this->db->where('dailyworkmaster.Date <=',$to);
			}
			
			$query=$this->db->get();
			$result=$query->result();
			if (count($result)>0)
			{
				$j=0;
				$temp=null;
				$sum=0;
				$list=array();
				while(count($result)>$j){
					$proj=$result[$j]->ProjectName;
					if($proj!=$temp){
						$i=0;
						$counter=0;
						while(count($result)>$i){
							$proj1=$result[$i]->ProjectName;
							if ($proj==$proj1){
								$counter++;
									
							}
							$i++;
						}
						$list[]=array('proj'=>$proj,'values'=>$counter);
						//$sum=$sum+$counter;
					}
					$temp=$proj;
					$j++;
				}
				$data['status']=true;
				$data[$cat]=$list;
				//$data['nocat']=count($result)-$sum;
				$data['total']=count($result);
				return $data;
			}
			else
				$data['status']=false;
			return $data;
		}
		function get_issues(){
			$this->load->database();
			$this->db->distinct('TypeOfIssue');
			$this->db->select('TypeOfIssue');
			$this->db->from('dailyworkmaster');
			$query = $this->db->get();
			$result = $query->result();
			$i=0;
			while(count($result) > $i)
			{
				
				$list[]=array($i+1=>$result[$i]->TypeOfIssue);
				
				$i++;
			}
			$data['issues']=$list;
			
			return $data;
			
		}
	}
?>