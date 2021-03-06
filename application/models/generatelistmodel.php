<?php
class generatelistmodel extends CI_Model {
	function generatelistmodel() {
		parent::__construct ();
	}
	function get_daylist($proj = '') {
		$this->load->database ();
		$this->db->order_by ( 'Date', 'asc' );
		$this->db->select ( 'Date,WorkDone,ordermaster.TotalWork,ordermaster.ProjectDeadline' );
		$this->db->from ( 'dailyworkmaster' );
		$this->db->join ( 'ordermaster', 'ordermaster.OrderId=dailyworkmaster.OrderId' );
		$this->db->where ( 'ordermaster.OrderId', $proj );
		$query = $this->db->get ();
		$result = $query->result ();
		$j = 0;
		$temp = null;
		$total = 0;
		if (count ( $result ) > 0) {
			while ( count ( $result ) > $j ) {
				$date = new DateTime ( $result [$j]->Date );
				
				$date = $date->format ( 'M-Y' );
				if ($temp != $date) {
					$i = 0;
					$sum = 0;
					while ( count ( $result ) > $i ) {
						
						$temp2 = new DateTime ( $result [$i]->Date );
						$temp2 = $temp2->format ( 'M-Y' );
						
						if ($date == $temp2) {
							$temp3 = new DateTime ( $result [$i]->Date );
							$temp3 = $temp3->format ( 'd-D' );
							$list [] = array (
									'date' => $temp3,
									'work' => number_format ( $result [$i]->WorkDone, 1 ) 
							);
							$sum = ( float ) $sum + $result [$i]->WorkDone;
						}
						$i ++;
					}
					$data [] = array (
							'month' => $date,
							'sum' => number_format ( $sum, 1 ),
							'list' => $list 
					);
					$total = $total + $sum;
					unset ( $list );
					$temp = $date;
				}
				$j ++;
			}
			// $data['Date']=$result[0]->Date;
			$data1 ['status'] = true;
			$data1 ['DayWise'] = $data;
			$from = date ( 'Y-m-d' );
			$till = $result [0]->ProjectDeadline;
			// if ($till < $from) {
			// trigger_error ( "The date till is before the date from", E_USER_NOTICE );
			// }
			$from = explode ( '-', $from );
			$till = explode ( '-', $till );
			$from = gregoriantojd ( $from [1], $from [2], $from [0] );
			$till = gregoriantojd ( $till [1], $till [2], $till [0] );
			$days = ($till - $from);
			// $datediff1 = new DateTime ( date ( 'd-M-Y' ) );
			$datediff2 = new DateTime ( $result [0]->ProjectDeadline );
			$data1 ['deadline'] = $datediff2->format ( 'd-M-Y' );
			// $datediff1 = new DateTime ( date ( 'd-M-Y' ) );
			// $interval = date_diff ( $datediff1, $datediff2 );
			// $rrr = ($result [0]->TotalWork - $total) / $interval->format ( '%R%a' );
			$rrr = ($result [0]->TotalWork - $total) / $days;
			$data1 ['rrr'] = number_format ( $rrr, 2 );
			$data1 ['totalreq'] = $result [0]->TotalWork;
			$data1 ['totaldone'] = number_format ( $total, 1 );
			return $data1;
		} else
			$data1 ['status'] = false;
		return $data1;
	}
	function get_delayproj($proj = '', $from = '', $to = '') {
		$this->load->database ();
		$this->db->order_by ( 'TypeOfIssue', 'asc' );
		$this->db->select ( 'dailyworkmaster.*' );
		$this->db->from ( 'dailyworkmaster' );
		$this->db->where ( 'dailyworkmaster.WorkDone', 0 );
		if ($proj != 'ALL') {
			$this->db->join ( 'ordermaster', 'ordermaster.OrderId=dailyworkmaster.OrderId' );
			$this->db->where ( 'ordermaster.ProjectName', $proj );
		}
		if ($from != '') {
			$this->db->where ( 'dailyworkmaster.Date >=', $from );
		}
		if ($to != '') {
			$this->db->where ( 'dailyworkmaster.Date <=', $to );
		}
		$query = $this->db->get ();
		$result = $query->result ();
		if (count ( $result ) > 0) {
			$j = 0;
			$temp = null;
			$sum = 0;
			$list = array ();
			while ( count ( $result ) > $j ) {
				$cat = $result [$j]->TypeOfIssue;
				if ($cat != $temp) {
					$i = 0;
					$counter = 0;
					while ( count ( $result ) > $i ) {
						$cat1 = $result [$i]->TypeOfIssue;
						if ($cat == $cat1) {
							$counter ++;
						}
						$i ++;
					}
					$list [] = array (
							'cat' => $cat,
							'values' => $counter 
					);
					$sum = $sum + $counter;
				}
				$temp = $cat;
				$j ++;
			}
			$data ['status'] = true;
			$data [$proj] = $list;
			$data ['nocat'] = count ( $result ) - $sum;
			$data ['total'] = count ( $result );
			return $data;
		} else
			$data ['status'] = false;
		return $data;
	}
	function get_delaycat($cat = '', $from = '', $to = '') {
		$this->load->database ();
		$this->db->order_by ( 'dailyworkmaster.OrderId', 'asc' );
		// $this->db->select('Date,WorkDone,ordermaster.TotalWork,ordermaster.ProjectDeadline');
		$this->db->select ( 'dailyworkmaster.*,ordermaster.ProjectName' );
		$this->db->from ( 'dailyworkmaster' );
		$this->db->join ( 'ordermaster', 'ordermaster.OrderId=dailyworkmaster.OrderId' );
		$this->db->where ( 'dailyworkmaster.WorkDone', 0 );
		if ($cat != 'ALL') {
			if ($cat == 'null')
				$this->db->where ( 'dailyworkmaster.TypeOfIssue', NULL );
			else
				$this->db->where ( 'dailyworkmaster.TypeOfIssue', $cat );
		}
		if ($from != '') {
			$this->db->where ( 'dailyworkmaster.Date >=', $from );
		}
		if ($to != '') {
			$this->db->where ( 'dailyworkmaster.Date <=', $to );
		}
		
		$query = $this->db->get ();
		$result = $query->result ();
		if (count ( $result ) > 0) {
			$j = 0;
			$temp = null;
			$sum = 0;
			$list = array ();
			while ( count ( $result ) > $j ) {
				$proj = $result [$j]->ProjectName;
				if ($proj != $temp) {
					$i = 0;
					$counter = 0;
					while ( count ( $result ) > $i ) {
						$proj1 = $result [$i]->ProjectName;
						if ($proj == $proj1) {
							$counter ++;
						}
						$i ++;
					}
					$list [] = array (
							'proj' => $proj,
							'values' => $counter 
					);
					// $sum=$sum+$counter;
				}
				$temp = $proj;
				$j ++;
			}
			$data ['status'] = true;
			$data [$cat] = $list;
			// $data['nocat']=count($result)-$sum;
			$data ['total'] = count ( $result );
			return $data;
		} else
			$data ['status'] = false;
		return $data;
	}
	function get_headwise($headid = '', $from = '', $to = '') {
		$this->load->database ();
		$this->db->order_by ( 'dailyworkmaster.OrderId', 'asc' );
		$this->db->select ( 'dailyworkmaster.WorkDone' );
		$this->db->select ( 'ordermaster.ProjectName,ordermaster.CostPerUnit' );
		$this->db->from ( 'dailyworkmaster' );
		$this->db->join ( 'ordermaster', 'ordermaster.OrderId=dailyworkmaster.OrderId' );
		$this->db->where ( 'dailyworkmaster.HeadId', $headid );
		if ($from != '') {
			$this->db->where ( 'dailyworkmaster.Date >=', $from );
		}
		if ($to != '') {
			$this->db->where ( 'dailyworkmaster.Date <=', $to );
		}
		
		$query = $this->db->get ();
		$result = $query->result ();
		if (count ( $result ) > 0) {
			$j = 0;
			$temp = null;
			$sumwork = 0;
			$sumvalue = 0;
			$list = array ();
			while ( count ( $result ) > $j ) {
				$proj = $result [$j]->ProjectName;
				if ($proj != $temp) {
					$i = 0;
					$work = 0;
					while ( count ( $result ) > $i ) {
						$proj1 = $result [$i]->ProjectName;
						if ($proj == $proj1) {
							$work += $result [$i]->WorkDone;
						}
						$i ++;
					}
					$list [] = array (
							'proj' => $proj,
							'workdone' => number_format ( $work, 2 ),
							'value' => round ( $work * $result [$j]->CostPerUnit ) 
					);
					$sumwork += $work;
					$sumvalue += round ( $work * $result [$j]->CostPerUnit );
				}
				$temp = $proj;
				$j ++;
			}
			$data ['status'] = true;
			$data ['headwise'] = $list;
			$data ['totalwork'] = number_format ( $sumwork, 2 );
			$data ['totalvalue'] = $sumvalue;
			
			return $data;
		} else
			$data ['status'] = false;
		return $data;
	}
}
?>