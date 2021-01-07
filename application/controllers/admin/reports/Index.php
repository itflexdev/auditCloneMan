<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Index extends CC_Controller 
{
	//////////////////
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Report_Model');
	}
	
	public function index($id='')
	{
		$this->checkUserPermission('32', '1');

		if($id!=''){
			$this->checkUserPermission('32', '2', '1');

			$result = $this->Report_Model->getList('row', ['id' => $id]);
			if($result){
				$pagedata['result'] = $result;
			}else{
				$this->session->set_flashdata('error', 'No Record Found.');
				redirect('admin/reports/index'); 
			}
		}

		if($this->input->post()){
			$this->checkUserPermission('32', '2', '1');
			$requestData 	= 	$this->input->post();
			// print_r($requestData);die;
			if(!isset($requestData['status'])){
				$data 	=  $this->Report_Model->action($requestData);
				if($data) $message = 'Report '.(($id=='') ? 'created' : 'updated').' successfully.';
			}else{
				$data 			= 	$this->Report_Model->changestatus($requestData);
				$message		= 	'Report deleted successfully.';
			}
			if(isset($data)) $this->session->set_flashdata('success', $message);
			else $this->session->set_flashdata('error', 'Try Later.');
			
			redirect('admin/reports/index'); 
		}

		$pagedata['notification'] 	= $this->getNotification();		
		$pagedata['checkpermission'] = $this->checkUserPermission('32', '2');
		
		$data['plugins']			= ['datatables', 'datatablesresponsive', 'sweetalert', 'datepicker', 'inputmask', 'validation'];
		$data['content'] 			= $this->load->view('admin/reports/index', (isset($pagedata) ? $pagedata : ''), true);
		
		$this->layout2($data);		
	}	
	
	public function DTReports()
	{
		
		$post 			= $this->input->post();	
		/////////////
		// if ($post['pagestatus']=='2') {
		// 	$post['pagestatus'] = '0';
		// }
		$totalcount 	= $this->Report_Model->getList('count', ['status' => '1']+$post);
		$results 		= $this->Report_Model->getList('all', ['status' => '1']+$post);
		//print_r($results);die;

		$checkpermission	=	$this->checkUserPermission('32', '2');

		$status = 1;

		$totalrecord 	= [];
		if(count($results) > 0){
			foreach($results as $result){	

					if($checkpermission){
						$action = 	'<div class="table-action">
										<a href="javascript:void(0)" id="executequery" data-id="'.$result['id'].'" data-reportname="'.$result['report_name'].'" data-toggle="tooltip" data-placement="top" title="Execute"><i class="fa fa-exclamation-circle"></i></a>
										<a href="'.base_url().'admin/reports/index/index/'.$result['id'].'" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil-alt"></i></a>
										<a href="javascript:void(0);" data-id="'.$result['id'].'" class="delete" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
									</div>';
					}else{
						$action = '';
					}
							
				$stockcount = 0;
				$totalrecord[] = 	[										
										'name' 			=> 	$result['report_name'],
										'description' 	=> 	$result['short_description'],	
										'action'		=> 	$action
									];
			}
		}
		
		$json = array(
			// "draw"            => intval($post['draw']),   
			"recordsTotal"    => intval($totalcount),  
			"recordsFiltered" => intval($totalcount),
			"data"            => $totalrecord
		);

		echo json_encode($json);
	}

	/*public function download_report(){
		$post = $this->input->post();
		$directory 	 = dirname(__DIR__, 4);
		$limit = ' LIMIT 10000';
		if (isset($post['executeid']) && $post['executeid']!='' && $post['executeid']!='undefined') {
			$this->db->select('re.id, re.result_query');
			$this->db->from('reports re');
			$this->db->where('re.id', $post['executeid']);
			$query1 = $this->db->get();
			$result1 = $query1->row_array();
			$excecution = $result1['result_query'];
		}else{
			$excecution = $post['message'];
		}

		$querydata = $excecution . $limit;
		$query = $this->db->query(''.$querydata.'');
		$result = $query->result_array();
		$phpExcel = new Spreadsheet();
		$row = 1;
		foreach ($result as $key => $value) {
			$changeindex = array_values($value);
			$arraycount  = count($changeindex);
			for($i=0;$i<$arraycount;$i++) {
				// echo $changeindex[$i];die;
				$phpExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$row.'',isset($changeindex[$i]) ? $changeindex[$i] : '')
			        ->setCellValue('B'.$row.'',isset($changeindex[$i+1]) ? $changeindex[$i+1] : '')
			        ->setCellValue('C'.$row.'',isset($changeindex[$i+2]) ? $changeindex[$i+2] : '')
			        ->setCellValue('D'.$row.'',isset($changeindex[$i+3]) ? $changeindex[$i+3] : '')
			        ->setCellValue('E'.$row.'',isset($changeindex[$i+4]) ? $changeindex[$i+4] : '')
			        ->setCellValue('F'.$row.'',isset($changeindex[$i+5]) ? $changeindex[$i+5] : '')
			        ->setCellValue('G'.$row.'',isset($changeindex[$i+6]) ? $changeindex[$i+6] : '')
			        ->setCellValue('H'.$row.'',isset($changeindex[$i+7]) ? $changeindex[$i+7] : '')
			        ->setCellValue('I'.$row.'',isset($changeindex[$i+8]) ? $changeindex[$i+8] : '')
			        ->setCellValue('J'.$row.'',isset($changeindex[$i+9]) ? $changeindex[$i+9] : '')
			        ->setCellValue('K'.$row.'',isset($changeindex[$i+10]) ? $changeindex[$i+10] : '')
			        ->setCellValue('L'.$row.'',isset($changeindex[$i+11]) ? $changeindex[$i+11] : '')
			        ->setCellValue('M'.$row.'',isset($changeindex[$i+12]) ? $changeindex[$i+12] : '')
			        ->setCellValue('N'.$row.'',isset($changeindex[$i+13]) ? $changeindex[$i+13] : '')
			        ->setCellValue('O'.$row.'',isset($changeindex[$i+14]) ? $changeindex[$i+14] : '')
			        ->setCellValue('P'.$row.'',isset($changeindex[$i+15]) ? $changeindex[$i+15] : '')
			        ->setCellValue('Q'.$row.'',isset($changeindex[$i+16]) ? $changeindex[$i+16] : '')
			        ->setCellValue('R'.$row.'',isset($changeindex[$i+17]) ? $changeindex[$i+17] : '')
			        ->setCellValue('S'.$row.'',isset($changeindex[$i+18]) ? $changeindex[$i+18] : '')
			        ->setCellValue('T'.$row.'',isset($changeindex[$i+19]) ? $changeindex[$i+19] : '')
			        ->setCellValue('U'.$row.'',isset($changeindex[$i+20]) ? $changeindex[$i+20] : '')
			        ->setCellValue('V'.$row.'',isset($changeindex[$i+21]) ? $changeindex[$i+21] : '')
			        ->setCellValue('W'.$row.'',isset($changeindex[$i+22]) ? $changeindex[$i+22] : '')
			        ->setCellValue('X'.$row.'',isset($changeindex[$i+23]) ? $changeindex[$i+23] : '')
			        ->setCellValue('Y'.$row.'',isset($changeindex[$i+24]) ? $changeindex[$i+24] : '')
			        ->setCellValue('Z'.$row.'',isset($changeindex[$i+25]) ? $changeindex[$i+25] : '');
			        $row = $row+1;
			        break;
			}
			
		}

	
			
		$writer = new Xlsx($phpExcel);
		$writer->save($directory.'/assets/uploads/temp/Report.xlsx');
		// redirect($directory.'/assets/uploads/temp/Report.xlsx');
		// unlink($directory.'/assets/uploads/temp/Report.xlsx');
		echo "1";
	}*/

	public function download_report(){
		$post = $this->input->post();
		if ($post['reportname'] !='') {
			$filename = $post['reportname'];
		}else{
			$filename = 'Report';
		}

		$directory 	 = dirname(__DIR__, 4);
		$limit = ' LIMIT 10000';
		if (isset($post['executeid']) && $post['executeid']!='' && $post['executeid']!='undefined') {
			$this->db->select('re.id, re.result_query');
			$this->db->from('reports re');
			$this->db->where('re.id', $post['executeid']);
			$query1 = $this->db->get();
			$result1 = $query1->row_array();
			$excecution = $result1['result_query'];
		}else{
			$excecution = $post['message'];
		}

		$querydata 	= $excecution . $limit;
		$query 		= $this->db->query(''.$querydata.'');
		$fieldinfo 	= $query->field_data();
		$result 	= $query->result_array();
		$phpExcel 	= new Spreadsheet();

		/// Headings
		foreach ($fieldinfo as $fieldinfokey => $fieldinfovalue) {
			$topindex[] = $fieldinfovalue->name;
			
		}
		$indexcount = count($topindex);
		for($j=0;$j<$indexcount;$j++) {
			$phpExcel->setActiveSheetIndex(0)
					->setCellValue('A1',isset($topindex[$j]) ? $topindex[$j] : '')
			        ->setCellValue('B1',isset($topindex[$j+1]) ? $topindex[$j+1] : '')
			        ->setCellValue('C1',isset($topindex[$j+2]) ? $topindex[$j+2] : '')
			        ->setCellValue('D1',isset($topindex[$j+3]) ? $topindex[$j+3] : '')
			        ->setCellValue('E1',isset($topindex[$j+4]) ? $topindex[$j+4] : '')
			        ->setCellValue('F1',isset($topindex[$j+5]) ? $topindex[$j+5] : '')
			        ->setCellValue('G1',isset($topindex[$j+6]) ? $topindex[$j+6] : '')
			        ->setCellValue('H1',isset($topindex[$j+7]) ? $topindex[$j+7] : '')
			        ->setCellValue('I1',isset($topindex[$j+8]) ? $topindex[$j+8] : '')
			        ->setCellValue('J1',isset($topindex[$j+9]) ? $topindex[$j+9] : '')
			        ->setCellValue('K1',isset($topindex[$j+10]) ? $topindex[$j+10] : '')
			        ->setCellValue('L1',isset($topindex[$j+11]) ? $topindex[$j+11] : '')
			        ->setCellValue('M1',isset($topindex[$j+12]) ? $topindex[$j+12] : '')
			        ->setCellValue('N1',isset($topindex[$j+13]) ? $topindex[$j+13] : '')
			        ->setCellValue('O1',isset($topindex[$j+14]) ? $topindex[$j+14] : '')
			        ->setCellValue('P1',isset($topindex[$j+15]) ? $topindex[$j+15] : '')
			        ->setCellValue('Q1',isset($topindex[$j+16]) ? $topindex[$j+16] : '')
			        ->setCellValue('R1',isset($topindex[$j+17]) ? $topindex[$j+17] : '')
			        ->setCellValue('S1',isset($topindex[$j+18]) ? $topindex[$j+18] : '')
			        ->setCellValue('T1',isset($topindex[$j+19]) ? $topindex[$j+19] : '')
			        ->setCellValue('U1',isset($topindex[$j+20]) ? $topindex[$j+20] : '')
			        ->setCellValue('V1',isset($topindex[$j+21]) ? $topindex[$j+21] : '')
			        ->setCellValue('W1',isset($topindex[$j+22]) ? $topindex[$j+22] : '')
			        ->setCellValue('X1',isset($topindex[$j+23]) ? $topindex[$j+23] : '')
			        ->setCellValue('Y1',isset($topindex[$j+24]) ? $topindex[$j+24] : '')
			        ->setCellValue('Z1',isset($topindex[$j+25]) ? $topindex[$j+25] : '');
			        break;
		}
		$row = 2;
		foreach ($result as $key => $value) {
			$changeindex = array_values($value);
			$arraycount  = count($changeindex);
			for($i=0;$i<$arraycount;$i++) {
				// echo $changeindex[$i];die;
				$phpExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$row.'',isset($changeindex[$i]) ? $changeindex[$i] : '')
			        ->setCellValue('B'.$row.'',isset($changeindex[$i+1]) ? $changeindex[$i+1] : '')
			        ->setCellValue('C'.$row.'',isset($changeindex[$i+2]) ? $changeindex[$i+2] : '')
			        ->setCellValue('D'.$row.'',isset($changeindex[$i+3]) ? $changeindex[$i+3] : '')
			        ->setCellValue('E'.$row.'',isset($changeindex[$i+4]) ? $changeindex[$i+4] : '')
			        ->setCellValue('F'.$row.'',isset($changeindex[$i+5]) ? $changeindex[$i+5] : '')
			        ->setCellValue('G'.$row.'',isset($changeindex[$i+6]) ? $changeindex[$i+6] : '')
			        ->setCellValue('H'.$row.'',isset($changeindex[$i+7]) ? $changeindex[$i+7] : '')
			        ->setCellValue('I'.$row.'',isset($changeindex[$i+8]) ? $changeindex[$i+8] : '')
			        ->setCellValue('J'.$row.'',isset($changeindex[$i+9]) ? $changeindex[$i+9] : '')
			        ->setCellValue('K'.$row.'',isset($changeindex[$i+10]) ? $changeindex[$i+10] : '')
			        ->setCellValue('L'.$row.'',isset($changeindex[$i+11]) ? $changeindex[$i+11] : '')
			        ->setCellValue('M'.$row.'',isset($changeindex[$i+12]) ? $changeindex[$i+12] : '')
			        ->setCellValue('N'.$row.'',isset($changeindex[$i+13]) ? $changeindex[$i+13] : '')
			        ->setCellValue('O'.$row.'',isset($changeindex[$i+14]) ? $changeindex[$i+14] : '')
			        ->setCellValue('P'.$row.'',isset($changeindex[$i+15]) ? $changeindex[$i+15] : '')
			        ->setCellValue('Q'.$row.'',isset($changeindex[$i+16]) ? $changeindex[$i+16] : '')
			        ->setCellValue('R'.$row.'',isset($changeindex[$i+17]) ? $changeindex[$i+17] : '')
			        ->setCellValue('S'.$row.'',isset($changeindex[$i+18]) ? $changeindex[$i+18] : '')
			        ->setCellValue('T'.$row.'',isset($changeindex[$i+19]) ? $changeindex[$i+19] : '')
			        ->setCellValue('U'.$row.'',isset($changeindex[$i+20]) ? $changeindex[$i+20] : '')
			        ->setCellValue('V'.$row.'',isset($changeindex[$i+21]) ? $changeindex[$i+21] : '')
			        ->setCellValue('W'.$row.'',isset($changeindex[$i+22]) ? $changeindex[$i+22] : '')
			        ->setCellValue('X'.$row.'',isset($changeindex[$i+23]) ? $changeindex[$i+23] : '')
			        ->setCellValue('Y'.$row.'',isset($changeindex[$i+24]) ? $changeindex[$i+24] : '')
			        ->setCellValue('Z'.$row.'',isset($changeindex[$i+25]) ? $changeindex[$i+25] : '');
			        $row = $row+1;
			        break;
			}
			
		}
		$writer = new Xlsx($phpExcel);
		$writer->save($directory.'/assets/uploads/temp/'.$filename.'.xlsx');
		// redirect($directory.'/assets/uploads/temp/Report.xlsx');
		// unlink($directory.'/assets/uploads/temp/Report.xlsx');
		echo "1";
	}

	public function file_unlink(){
		$post = $this->input->post();
		if ($post['reportname'] !='') {
			$filename = $post['reportname'];
		}else{
			$filename = 'Report';
		}
		$directory 	 = dirname(__DIR__, 4);
		if (file_exists($directory.'/assets/uploads/temp/'.$filename.'.xlsx')) {
			unlink($directory.'/assets/uploads/temp/'.$filename.'.xlsx');
		}
		
		echo "1";
	}

	public function queryExecution(){
		$post = $this->input->post();
		$injections = [
					"1=1",";", "delete", "Delete", "truncate", "Truncate", "show", "Show", '""=""', "DROP", "drop", "@", "Repair","repair", "exec", "Exec", "update", "Update", "use", "Use"
					];
		if (isset($post['executeid']) && $post['executeid']!='' && $post['executeid']!='undefined') {
			$this->db->select('re.id, re.result_query');
			$this->db->from('reports re');
			$this->db->where('re.id', $post['executeid']);
			$query = $this->db->get();
			$result = $query->row_array();
			$excecution = $result['result_query'];
		}else{
			$excecution = $post['message'];
		}
		if(0 < count(array_intersect(array_map('strtolower', explode(' ', $excecution)), $injections)))
		{
		  $querystatus = '0';
		}else{
			$querystatus = '1';
			$limit = ' LIMIT 10000';
			$querydata = $excecution . $limit;
			$query1 = $this->db->query(''.$querydata.'');
			$result1 = $query1->result_array();
			// echo "<table>";
			// foreach ($result as $key => $value) {
			// 	echo "<tr>";
			// 	$changeindex= array_values($value);
			// 	$arraycount = count($changeindex);
			// 	for($i=1;$i<=$arraycount;$i++) {
			// 		echo "<td>".@@isset($changeindex[$i]) ? $changeindex[$i] : ''."</td>";
			// 	}
			// 	echo "</tr>";
								
			// }
			// echo "</table>";
			// foreach ($result as $key => $value) {
			// 	$changeindex = array_values($value);
			// 	$arraycount  = count($changeindex);

			// 	for($i=0;$i<$arraycount;$i++) {
			// 		print_r($changeindex[1]);die;
					
			// 	}
			// }
		}
		echo $querystatus;
		
	}


	public function fetchfeilds(){
		$querydata = "SELECT * FROM `users` WHERE `id` > '51' and id<='64'";


		$query = $this->db->query(''.$querydata.'');
		$fieldinfo = $query->field_data();
		$result = $query->result_array();
		foreach ($fieldinfo as $fieldinfokey => $fieldinfovalue) {
			$topindex[] = $fieldinfovalue->name;
		}
echo "<pre>";print_r($topindex);
	}
}

