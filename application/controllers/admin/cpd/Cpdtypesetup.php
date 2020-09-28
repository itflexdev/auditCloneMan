<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Cpdtypesetup extends CC_Controller 
{
	public function __construct()
	{
		parent::__construct();
		

		
		$this->load->model('Cpdtypesetup_Model');
		$this->load->model('CC_Model');
		$this->load->model('Plumber_Model');

		$this->checkUserPermission('16', '1');
		
	}
	
	public function index($pagestatus='',$id='')
	{
		
		if($id!=''){

			$this->checkUserPermission('16', '2', '1');


			$result = $this->Cpdtypesetup_Model->getList('row', ['id' => $id, 'status' => ['0','1']]);
			//
			if($result){
				$pagedata['result'] = $result;
			}else{
				$this->session->set_flashdata('error', 'No Record Found.');
				redirect('admin/cpd/Cpdtypesetup'); 
			}
		}
		
		if($this->input->post()){

			$this->checkUserPermission('16', '2', '1');

			$requestData 	= 	$this->input->post();

			if($requestData['submit']=='submit'){
				$check_code 	= $this->productCode();
				if ($id=='') {
					if ($check_code!='') {
						$full_code = $check_code;
						// QR CODE
						$text 								= $full_code;
						$file_name 							= $text ."-Qrcode.png";
						$Qrcode_path 						= FCPATH."assets/qrcode/".$file_name;
						define('IMAGE_WIDTH',1000);
						define('IMAGE_HEIGHT',1000);
						QRcode::png($text,$Qrcode_path,'L', '10', '10');
						$requestData['qrcode']				= $file_name;
					}
				}else{
					$full_code 	= 	$requestData['productcode'];
				}
				
				$requestData['productcode']			= $full_code;
				$data 	=  $this->Cpdtypesetup_Model->action($requestData);
				if($data) $message = 'CPD Type '.(($id=='') ? 'created' : 'updated').' successfully.';
			}else{
				$data 			= 	$this->Cpdtypesetup_Model->changestatus($requestData);
				$message		= 	'CPD Type deleted successfully.';
			}

			if(isset($data)) $this->session->set_flashdata('success', $message);
			else $this->session->set_flashdata('error', 'Try Later.');
			
			redirect('admin/cpd/Cpdtypesetup'); 
		}
		
		$pagedata['notification'] 	= $this->getNotification();
		$pagedata['cpdstreamID'] 	= $this->config->item('cpdstream');
		$pagedata['checkpermission'] = $this->checkUserPermission('16', '2');
		$pagedata['pagestatus'] 	= $this->getPageStatus($pagestatus);
		$pagedata['id'] 			= $this->getUserID();
		$data['plugins']			= ['datatables', 'datatablesresponsive', 'sweetalert', 'validation', 'datepicker'];
		$data['content'] 			= $this->load->view('admin/cpd/cpdtypesetup/index', (isset($pagedata) ? $pagedata : ''), true);
		$this->layout2($data);
	}

	public function productCode(){
		$result = $this->db->order_by('id',"desc")->get('cpdtypes')->row_array();
		if ($result) {
			$sequence_number  = explode("-",$result['productcode']);
			$product_code = $sequence_number[1]+1;						
			$code 		=  str_pad($product_code,6,'0',STR_PAD_LEFT);
			$full_code = "CPD-".$code;
			return $full_code;
		}else{
			$cpd = 'CPD-000001';
			return $cpd;
		}
	}

	public function DTCpdType()
	{
		$post 			= $this->input->post();

		$totalcount 	= $this->Cpdtypesetup_Model->getList('count', ['status' => [$post['pagestatus']]]+$post);
		$results 		= $this->Cpdtypesetup_Model->getList('all', ['status' => [$post['pagestatus']]]+$post);

		$checkpermission	=	$this->checkUserPermission('16', '2');
		
		$totalrecord 	= [];
		if(count($results) > 0){
			foreach($results as $result){
				if ($result['cpdstream'] !='0') {
					if ($checkpermission) {
						$action = '<div class="table-action">
								<a href="'.base_url().'admin/cpd/cpdtypesetup/index/'.$post['pagestatus'].'/'.$result['id'].'" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil-alt"></i></a>
								</div>';
					}else{
						$action = '';
					}

					$totalrecord[] = 	[
						'productcode' 		=> 	$result['productcode'],
						'activity' 			=> 	$result['activity'],
						'startdate' 		=> 	date('m-d-Y',strtotime($result['startdate'])),
						'enddate' 			=> 	date('m-d-Y',strtotime($result['enddate'])),
						'cpdstream' 		=> 	$this->config->item('cpdstream')[$result['cpdstream']],
						'points' 			=> 	$result['points'],
											//'status' 	=> 	$this->config->item('statusicon')[$result['status']],
						'action'			=> 	$action
					];
				}
				
			}
		}
		
		$json = array(
			"draw"            => intval($post['draw']),   
			"recordsTotal"    => intval($totalcount),  
			"recordsFiltered" => intval($totalcount),
			"data"            => $totalrecord
		);

		echo json_encode($json);
	}

	public function getPDF($id){

		if($id!=''){
			$rowData = $this->Cpdtypesetup_Model->getList('row', ['id' => $id, 'status' => ['0','1']]);
			if($rowData){
				$fileName = $rowData['productcode'];

				$html = '<!DOCTYPE html>
				<html>
				<head>
				<title>CPD PDF</title>
				</head>
				<body>

				<table style="width: 80%; display: table; margin: 0 auto; ">
				<tbody>
				<tr style="text-align: center;">
				<td colspan="2" style="font-family:Helvetica;"><img style="width: 200px;" src="'.$this->base64conversion(base_url()."assets/images/pitrb-logo.png").'"></td>
				</tr>
				<tr style="text-align: center;">
				<td style="width: 50%; text-align: right;padding: 60px 20px 10px 0; font-weight: 700; font-family:Helvetica;">ACTIVITY NAME:</td>
				<td style="font-family:Helvetica; text-align: left; padding: 60px 0 10px 0;">'.$rowData['activity'].'</td>
				</tr>
				<tr style=" font-family:Helvetica; text-align: center;">
				<td style=" font-family:Helvetica;width: 60%; text-align: right; padding: 10px 20px 10px 0; font-weight: 700;">CPD POINTS FOR THIS ACTVITY:</td>
				<td style="font-family:Helvetica; text-align: left; padding: 10px 0 10px 0;">'.$rowData['points'].'</td>
				</tr>
				<tr style="font-family:Helvetica; text-align: center;">
				<td style="font-family:Helvetica; width: 50%; text-align: right; padding: 10px 20px 10px 0; font-weight: 700;">CPD STREAM:</td>
				<td style="font-family:Helvetica; text-align: left; padding: 10px 0 10px 0;">'.$this->config->item('cpdstream')[$rowData['cpdstream']].'</td>
				</tr>
				<tr style="text-align: center;">
				<td style="font-family:Helvetica; width: 50%; text-align: right; padding: 10px 20px 10px 0; font-weight: 700;">CPD ACTIVITY END DATE:</td>
				<td style="font-family:Helvetica; text-align: left; padding: 10px 0 10px 0;">'.date('d-m-Y',strtotime($rowData['enddate'])).'</td>
				</tr>
				<tr style="text-align: center;">
				<td style="font-family:Helvetica; width: 50%; text-align: right; padding: 10px 20px 10px 0; font-weight: 700;">CPD PRODUCT CODE:</td>
				<td style="font-family:Helvetica; text-align: left; padding: 10px 0 10px 0;">'.$rowData['productcode'].'</td>
				</tr>
				<tr style="text-align: center;">
				<td colspan="2" style="font-family:Helvetica;">
				<img style="width: 210px; padding-top: 70px;" src="'.$this->base64conversion(base_url()."assets/qrcode/".$rowData['qrcode']).'">
				<p style="font-family:Helvetica; font-size: 12px">Use App Plumber to Scan this QR Code</p>
				</td>
				</tr>
				</tbody>
				</table>
				</body>
				</html>';
				$pdfFilePath = "".$fileName.".pdf";
				$this->pdf->loadHtml($html);
				$this->pdf->setPaper('A4', 'portrait');
				$this->pdf->render();
				$this->pdf->stream($pdfFilePath);
			}
		}		
	}

	// CPD Queue:

	public function index_queue($pagestatus='',$id=''){
		
		if($id!='' && !$this->input->post()){

			$this->checkUserPermission('17', '2', '1');

			$result = $this->Cpdtypesetup_Model->getQueueList('row', ['id' => $id, 'pagestatus' => [$pagestatus]]);
			if($result){
				$pagedata['result'] = $result;
				if ($result['cpd_activity']!='') {
					$pagedata['strem_id'] = isset($this->config->item('cpdstream')[$pagedata['result']['cpd_stream']]) ? $this->config->item('cpdstream')[$pagedata['result']['cpd_stream']] : '';
				}else{
					$pagedata['strem_id'] = '';
				}
				
			}else{
				$this->session->set_flashdata('error', 'No Record Found.');
				redirect('admin/cpd/cpdtypesetup/index_queue'); 
			}
		}
		
		if($this->input->post()){

			$this->checkUserPermission('17', '2', '1');

			$requestData 	= 	$this->input->post();
			if($requestData['submit']=='submit'){
				// echo "<pre>";
				// print_r($requestData);die;

				$data 	=  $this->Cpdtypesetup_Model->queue_action($requestData);
				if($data) $message = 'CPD activity '.(($id=='') ? 'submitted.' : 'updated');
			}else{
				$data 			= 	$this->Installationtype_Model->changestatus($requestData);
				$message		= 	'CPD activity deleted.';
			}

			if(isset($data)) $this->session->set_flashdata('success', $message);
			else $this->session->set_flashdata('error', 'Try Later.');
			
			redirect('admin/cpd/cpdtypesetup/index_queue'); 
		}
		
		$pagedata['notification'] 	= $this->getNotification();
		$pagedata['cpdstreamID'] 	= $this->config->item('cpdstream');
		$status 				 	= $this->getPageStatus($pagestatus);

		if ($status == '1') {
			$pagedata['pagestatus'] = '0';
		}else{
			$pagedata['pagestatus'] = '1';
		}

		$pagedata['id'] 			= $this->getUserID();
		$pagedata['checkpermission'] = $this->checkUserPermission('17', '2');
		$pagedata['approvalstatus'] = $this->config->item('approvalstatus');
		$data['plugins']			= ['datatables', 'datatablesresponsive', 'sweetalert', 'validation', 'datepicker'];
		$data['content'] 			= $this->load->view('admin/cpd/cpdqueue/index', (isset($pagedata) ? $pagedata : ''), true);
		$this->layout2($data);
	}

	// Plumber Reg number search
	public function userRegDetails()
	{

		$postData = $this->input->post();		  
		if($postData['type'] == 3)
		{
			$data 	=   $this->Cpdtypesetup_Model->autosearchPlumberReg($postData);
		}

	  	// echo json_encode($data); exit;

		if(!empty($data) && count($data)>0 ) {
		?>
			<ul id="name-list">
			<?php
			foreach($data as $key=>$val) {
				$reg_no = $val["registration_no"];
				$name_surname = $val["name"].' '.$val["surname"];
				// if(isset($val["surname"])){
				// 	$name = $name.' '.$val["surname"];
				// }
			?>
			<li onClick="selectuser('<?php echo $reg_no; ?>','<?php echo $val["id"]; ?>','<?php echo $name_surname; ?>');"><?php echo $name_surname; ?></li>
			<?php } ?>
			</ul>
<?php 	} 
	}

		//CPD Activity search
	public function activityDetails()
	{
		$postData = $this->input->post();	

		$cpdverify = $this->Cpdtypesetup_Model->cpdverification($postData);
		if (count($cpdverify) > 0) {
			foreach ($cpdverify as $cpdverifykey => $cpdverifyvalue) {
				if ($cpdverifyvalue['cpdtype_id']!='0') {
					$postData['cpdidarray'][] = $cpdverifyvalue['cpdtype_id'];
				}
			}
		}
		if($postData)
		{
			$data 	=   $this->Cpdtypesetup_Model->autosearchActivity($postData);
		}
	  	// echo json_encode($data); exit;

		if(!empty($data)) {
		?>
			<ul id="name-list1">
			<?php
			foreach($data as $key=>$val) {
				//print_r($val['startdate']);die;
				if ($val['startdate']) {
					$startDate1 = date('m-d-Y', strtotime($val['startdate']));
				}
				$activity 		= $val["activity"];
				$startDate 		= $startDate1;
				$cpd_Stream 	= $this->config->item('cpdstream')[$val["cpdstream"]];
				$cpd_Stream_id 	= $val["cpdstream"];
				$cpdPoints 		= $val["points"];
				$activity1 		= str_replace("'", "\\'", $activity);
				$activity1 		= str_replace('"', "&quot;", $activity1);
				//$activity1 		= addcslashes($activity, "'");
			?>
			<li onClick="selectActivity('<?php echo $activity1; ?>','<?php echo $val["id"]; ?>','<?php echo $startDate; ?>','<?php echo $cpd_Stream; ?>','<?php echo $cpdPoints; ?>','<?php echo $cpd_Stream_id; ?>');"><?php echo $activity; ?></li>
			<?php } ?>
			</ul>
<?php 	} 
	}

	public function DTCpdQueue()
	{
		$post 			= $this->input->post();
		//print_r($post);die;

		$totalcount 	= $this->Cpdtypesetup_Model->getQueueList('count', ['status' => [$post['pagestatus']]]+$post);
		$results 		= $this->Cpdtypesetup_Model->getQueueList('all', ['status' => [$post['pagestatus']]]+$post);
		//print_r($results);die;
		$checkpermission	=	$this->checkUserPermission('17', '2');
		
		$totalrecord 	= [];
		if(count($results) > 0){
			foreach($results as $result){

				if ($checkpermission) {
					$action = '<div class="table-action">
									<a href="'.base_url().'admin/cpd/cpdtypesetup/index_queue/'.$post['pagestatus'].'/'.$result['id'].'" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil-alt"></i></a>
								</div>';
				}else{
					$action = '';
				}

				if ($result['status']==0) {
					$statuz = 'Pending';
				}elseif ($result['status']==3) {
					$statuz = 'Not Submitted';
				}else{
					$statuz = $this->config->item('approvalstatus')[$result['status']];
				}

				

				$totalrecord[] = 	[
					'date' 					=> 	date('m-d-Y',strtotime($result['cpd_start_date'])),
					'namesurname' 			=> 	$result['name_surname'],
					'reg_number' 			=> 	$result['reg_number'],
					'acivity' 				=> 	$result['cpd_activity'],
					'points' 				=> 	$result['points'],
					'status' 				=> 	$statuz,
					'action'				=> 	$action
				];
			}
		}
		
		$json = array(
			"draw"            => intval($post['draw']),   
			"recordsTotal"    => intval($totalcount),  
			"recordsFiltered" => intval($totalcount),
			"data"            => $totalrecord
		);

		echo json_encode($json);
	}

	public function massimport(){

		if (isset($_FILES) && !$this->input->post()) {
			$directory 	 = dirname(__DIR__, 4);
			// $filename =  $_FILES["file"]["name"];
			$filename =  'cpd template.xlsx';
			$upload_path = $directory.'/assets/uploads/temp/';
			if (!file_exists($upload_path.$filename)) {
		        $location = $upload_path.$filename;
		        if (move_uploaded_file($_FILES["file"]["tmp_name"], $location)) {
		        	echo json_encode($_FILES["file"]["name"]);
		        }else{
		        	echo json_encode('errors');
		        }
			}else{
				unlink($upload_path.$filename);
				$location = $upload_path.$filename;
		        if (move_uploaded_file($_FILES["file"]["tmp_name"], $location)) {
		        	echo json_encode($_FILES["file"]["name"]);
		        }else{
		        	echo json_encode('errors');
		        }
			}
		}
		if ($this->input->post()) {
			$post = $this->input->post();
			$directory 	 = dirname(__DIR__, 4);
			// $filename =  $_FILES["filename"]["name"];
			$filename =  'cpd template.xlsx';
			$upload_path = $directory.'/assets/uploads/temp/';
			$file 	= $upload_path.$filename;
			$type 	= \PhpOffice\PhpSpreadsheet\IOFactory::identify($file);
			$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($type);
			$spreadsheet = $reader->load($file);
			$datas 	= $spreadsheet->getActiveSheet()->toArray();
			$datas[0][2] = 'Errors';
			$rawdata = $datas;
			unset($datas[0]);
			if (isset($exceldata)) {unset($exceldata);}
			$i = 0;
			foreach ($datas as $key => $value) {
				if($value[0] !=''){
				
					$this->db->select('u.*, up.user_id, up.registration_no, concat(ud.name, " ", ud.surname) as name');
					$this->db->from('users u');
					$this->db->join('users_plumber up', 'up.user_id=u.id', 'left');
					$this->db->join('users_detail ud', 'ud.user_id=u.id', 'left');
					$this->db->where('up.registration_no', $value[0]);
					$query = $this->db->get();
					$result = $query->row_array();
					// if ($value[1] !='') {
					// 	$cpdpoints = $value[1];
					// }else{
					// 	$cpdpoints = $post['cpdpoints'];
					// }
					if ($value[1] !='' && $value[1] !='0') {
						$cpdpoints = $value[1];
					}elseif($value[1] !='' && $value[1] =='0'){
						$cpdpoints = $post['cpdpoints'];
					}else{
						$cpdpoints = $post['cpdpoints'];
					}

					if ($result) {
						
						$exceldata[$i][0] = $value[0];
						$exceldata[$i][1] = $cpdpoints;
						$exceldata[$i][2] = $result['user_id'];
						$exceldata[$i][3] = $result['name'];
						$exceldata[$i][4] = 'Plumber found';
					}else{
						$exceldata[$i][0] = $value[0];
						$exceldata[$i][1] = $cpdpoints;
						$exceldata[$i][2] = $result['user_id'];
						$exceldata[$i][3] = $result['name'];
						$exceldata[$i][4] = 'Plumber not found';
					}
					$i++;
				}
			}
			$j = 0;
			
			if (isset($exceldata)) {
				foreach ($exceldata as $exceldatakey => $exceldatavalue) {

					$this->db->select('*');
					$this->db->from('cpd_activity_form');
					$this->db->where('reg_number', $exceldatavalue[0]);
					$this->db->where('cpdtype_id', $post['cpdid']);
					$query1 = $this->db->get();
					$result2 = $query1->row_array();
					if ($result2) {
						if ($result2['status'] == '1' || $result2['status'] == '0' || $result2['status'] == '1') {
							if ($result2['status'] == '1') {
								$cellstatus = 'approved';
							}elseif($result2['status'] == '0'){
								$cellstatus = 'pending';
							}
							$cpddata[$j][0]	= $exceldatavalue[0];
							$cpddata[$j][1] = $exceldatavalue[1];
							$cpddata[$j][2] = $exceldatavalue[2];
							$cpddata[$j][3] = $exceldatavalue[3];
							$cpddata[$j][4] = $exceldatavalue[4];
							$cpddata[$j][5] = '0';
							$cpddata[$j][6] = 'Activity already '.$cellstatus.'';
						}elseif($result2['status'] == '2'){
							$cpddata[$j][0]	= $exceldatavalue[0];
							$cpddata[$j][1] = $exceldatavalue[1];
							$cpddata[$j][2] = $exceldatavalue[2];
							$cpddata[$j][3] = $exceldatavalue[3];
							$cpddata[$j][4] = $exceldatavalue[4];
							$cpddata[$j][5] = '1';
							$cpddata[$j][6] = 'Activity should insert';
						}
					}else{
						if ($exceldatavalue[4] == 'Plumber not found') {
							$cpddata[$j][0] = $exceldatavalue[0];
							$cpddata[$j][1] = $exceldatavalue[1];
							$cpddata[$j][2] = $exceldatavalue[2];
							$cpddata[$j][3] = $exceldatavalue[3];
							$cpddata[$j][4] = $exceldatavalue[4];
							$cpddata[$j][5] = '0';
							$cpddata[$j][6] = 'Plumber not found';
						}else{
							$cpddata[$j][0] = $exceldatavalue[0];
							$cpddata[$j][1] = $exceldatavalue[1];
							$cpddata[$j][2] = $exceldatavalue[2];
							$cpddata[$j][3] = $exceldatavalue[3];
							$cpddata[$j][4] = $exceldatavalue[4];
							$cpddata[$j][5] = '1';
							$cpddata[$j][6] = 'Activity should insert';
						}
					}
					
				$j++;
				}
			}
			$phpExcel = new Spreadsheet();
			$phpExcel->setActiveSheetIndex(0)
					->setCellValue('A1','Reg No') // reg
			        ->setCellValue('B1','Points') // point
			        ->setCellValue('C1','User id') //user id 
			        ->setCellValue('D1','Name Surname') // name
			        ->setCellValue('E1','Errors') // error
			        ->setCellValue('F1','Status') // status
			        ->setCellValue('G1','Errors'); // status
			if (isset($cpddata)) {
				$k = 0;
				foreach ($cpddata as $cpddatakey => $cpddatavalue) {
					$counter = $k+1;
					$row = $k+2;
					$phpExcel->setActiveSheetIndex(0)
					         ->setCellValue('A'.$row.'',$cpddatavalue[0]) // reg
					         ->setCellValue('B'.$row.'',$cpddatavalue[1]) // point
					         ->setCellValue('C'.$row.'',$cpddatavalue[2]) //user id 
					         ->setCellValue('D'.$row.'',$cpddatavalue[3]) // name
					         ->setCellValue('E'.$row.'',$cpddatavalue[4]) // error
					         ->setCellValue('F'.$row.'',$cpddatavalue[5]) // status
					         // ->setCellValue('G'.$row.'',$cpddatavalue[4].' '.$cpddatavalue[6]); // status
					         ->setCellValue('G'.$row.'',$cpddatavalue[6]); // status
					$k++;
				}
				$writer = new Xlsx($phpExcel);
				$writer->save($directory.'/assets/uploads/cpdmassimport/cpd_template.xlsx');

				$templatepath = $directory.'/assets/uploads/cpdmassimport/cpd_template.xlsx';
				$type1 	= \PhpOffice\PhpSpreadsheet\IOFactory::identify($templatepath);
				$reader1 = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($type1);
				$spreadsheet1 = $reader1->load($templatepath);
				$datas1 	= $spreadsheet1->getActiveSheet()->toArray();
				$tabledata = '<table style = "border-collapse: collapse;width: 100%;">
							<tr>
							    <th>Reg No</th>
							    <th>Error</th>
							</tr>';
				foreach ($datas1 as $datas1key => $datas1value) {
					if (($datas1value[5] == "0" && $datas1value[6] == "Activity already approved") || ($datas1value[5] == "0" && $datas1value[6] == "Plumber not found")) {
						$tabledata .= '<tr>
								    <td>'.$datas1value[0].'</td>
								    <td>'.$datas1value[6].'</td>
							  	</tr>';
					}
				}
				$tabledata .= '</table>';
				if (file_exists($directory.'/assets/uploads/temp/cpd_template.xlsx')) {
					unlink($directory.'/assets/uploads/temp/cpd_template.xlsx');
				}
				echo $tabledata;
			}
		}
	}

	// public function proceed2(){
	// 	$post = $this->input->post();
	// 		$directory 	 = dirname(__DIR__, 4);
	// 		$filename =  $_FILES["filename"]["name"];
	// 		$upload_path = $directory.'/assets/uploads/temp/';
	// 		$file 	= $upload_path.$filename;
	// 		$type 	= \PhpOffice\PhpSpreadsheet\IOFactory::identify($file);
	// 		$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($type);
	// 		$spreadsheet = $reader->load($file);
	// 		$datas 	= $spreadsheet->getActiveSheet()->toArray();
	// 		$datas[0][2] = 'Errors';
	// 		$rawdata = $datas;
	// 		unset($datas[0]);
	// 		if (isset($exceldata)) {unset($exceldata);}
	// 		$i = 0;
	// 		foreach ($datas as $key => $value) {
	// 			$this->db->select('u.*, up.user_id, up.registration_no, concat(ud.name, " ", ud.surname) as name');
	// 			$this->db->from('users u');
	// 			$this->db->join('users_plumber up', 'up.user_id=u.id', 'left');
	// 			$this->db->join('users_detail ud', 'ud.user_id=u.id', 'left');
	// 			$this->db->where('up.registration_no', $value[0]);
	// 			$query = $this->db->get();
	// 			$result = $query->row_array();
	// 			if ($value[1] !='') {
	// 				$cpdpoints = $value[1];
	// 			}else{
	// 				$cpdpoints = $post['cpdpoints'];
	// 			}
	// 			if ($result) {
					
	// 				$exceldata[$i][0] = $value[0];
	// 				$exceldata[$i][1] = $cpdpoints;
	// 				$exceldata[$i][2] = $result['user_id'];
	// 				$exceldata[$i][3] = $result['name'];
	// 				$exceldata[$i][4] = 'Plumber found';
	// 			}else{
	// 				$exceldata[$i][0] = $value[0];
	// 				$exceldata[$i][1] = $cpdpoints;
	// 				$exceldata[$i][2] = $result['user_id'];
	// 				$exceldata[$i][3] = $result['name'];
	// 				$exceldata[$i][4] = 'Plumber not found';
	// 			}
	// 			$i++;
	// 		}
	// 		$j = 0;
			
	// 		if (isset($exceldata)) {
	// 			foreach ($exceldata as $exceldatakey => $exceldatavalue) {

	// 				$this->db->select('*');
	// 				$this->db->from('cpd_activity_form');
	// 				$this->db->where('reg_number', $exceldatavalue[0]);
	// 				$this->db->where('cpdtype_id', $post['cpdid']);
	// 				$query1 = $this->db->get();
	// 				$result2 = $query1->row_array();
	// 				if ($result2) {
	// 					if ($result2['status'] == '1' || $result2['status'] == '0') {
	// 						$cpddata[$j][0]	= $exceldatavalue[0];
	// 						$cpddata[$j][1] = $exceldatavalue[1];
	// 						$cpddata[$j][2] = $exceldatavalue[2];
	// 						$cpddata[$j][3] = $exceldatavalue[3];
	// 						$cpddata[$j][4] = $exceldatavalue[4];
	// 						$cpddata[$j][5] = '0';
	// 						$cpddata[$j][6] = 'Activity already approved';
	// 					}elseif($result2['status'] == '2'){
	// 						$cpddata[$j][0]	= $exceldatavalue[0];
	// 						$cpddata[$j][1] = $exceldatavalue[1];
	// 						$cpddata[$j][2] = $exceldatavalue[2];
	// 						$cpddata[$j][3] = $exceldatavalue[3];
	// 						$cpddata[$j][4] = $exceldatavalue[4];
	// 						$cpddata[$j][5] = '1';
	// 						$cpddata[$j][6] = 'Activity should insert';
	// 					}
	// 				}else{
	// 					if ($exceldatavalue[4] == 'Plumber not found') {
	// 						$cpddata[$j][0] = $exceldatavalue[0];
	// 						$cpddata[$j][1] = $exceldatavalue[1];
	// 						$cpddata[$j][2] = $exceldatavalue[2];
	// 						$cpddata[$j][3] = $exceldatavalue[3];
	// 						$cpddata[$j][4] = $exceldatavalue[4];
	// 						$cpddata[$j][5] = '0';
	// 						$cpddata[$j][6] = 'Plumber not found';
	// 					}else{
	// 						$cpddata[$j][0] = $exceldatavalue[0];
	// 						$cpddata[$j][1] = $exceldatavalue[1];
	// 						$cpddata[$j][2] = $exceldatavalue[2];
	// 						$cpddata[$j][3] = $exceldatavalue[3];
	// 						$cpddata[$j][4] = $exceldatavalue[4];
	// 						$cpddata[$j][5] = '1';
	// 						$cpddata[$j][6] = 'Activity should insert';
	// 					}
	// 				}
					
	// 			$j++;
	// 			}
	// 		}
	// 		$phpExcel = new Spreadsheet();
	// 		$phpExcel->setActiveSheetIndex(0)
	// 				->setCellValue('A1','Reg No') // reg
	// 		        ->setCellValue('B1','Points') // point
	// 		        ->setCellValue('C1','User id') //user id 
	// 		        ->setCellValue('D1','Name Surname') // name
	// 		        ->setCellValue('E1','Errors') // error
	// 		        ->setCellValue('F1','Status') // status
	// 		        ->setCellValue('G1','Errors'); // status
	// 		if (isset($cpddata)) {
	// 			$k = 0;
	// 			foreach ($cpddata as $cpddatakey => $cpddatavalue) {
	// 				$counter = $k+1;
	// 				$row = $k+2;
	// 				$phpExcel->setActiveSheetIndex(0)
	// 				         ->setCellValue('A'.$row.'',$cpddatavalue[0]) // reg
	// 				         ->setCellValue('B'.$row.'',$cpddatavalue[1]) // point
	// 				         ->setCellValue('C'.$row.'',$cpddatavalue[2]) //user id 
	// 				         ->setCellValue('D'.$row.'',$cpddatavalue[3]) // name
	// 				         ->setCellValue('E'.$row.'',$cpddatavalue[4]) // error
	// 				         ->setCellValue('F'.$row.'',$cpddatavalue[5]) // status
	// 				         // ->setCellValue('G'.$row.'',$cpddatavalue[4].' '.$cpddatavalue[6]); // status
	// 				         ->setCellValue('G'.$row.'',$cpddatavalue[6]); // status
	// 				$k++;
	// 			}
	// 			$writer = new Xlsx($phpExcel);
	// 			$writer->save($directory.'/assets/uploads/cpdmassimport/cpd_template.xlsx');

	// 			$templatepath = $directory.'/assets/uploads/cpdmassimport/cpd_template.xlsx';
	// 			$type1 	= \PhpOffice\PhpSpreadsheet\IOFactory::identify($templatepath);
	// 			$reader1 = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($type1);
	// 			$spreadsheet1 = $reader1->load($templatepath);
	// 			$datas1 	= $spreadsheet1->getActiveSheet()->toArray();
	// 			$tabledata = '<table style = "border-collapse: collapse;width: 100%;">
	// 						<tr>
	// 						    <th>Reg No</th>
	// 						    <th>Error</th>
	// 						</tr>';
	// 			foreach ($datas1 as $datas1key => $datas1value) {
	// 				if ($datas1value[5] == "0") {
	// 					$tabledata .= '<tr>
	// 							    <td>'.$datas1value[0].'</td>
	// 							    <td>'.$datas1value[6].'</td>
	// 						  	</tr>';
	// 				}
	// 			}
	// 			$tabledata .= '</table>';
	// 			if (file_exists($directory.'/assets/uploads/temp/cpd_template.xlsx')) {
	// 				unlink($directory.'/assets/uploads/temp/cpd_template.xlsx');
	// 			}
	// 			echo $tabledata;
	// 		}

	// }
	public function proceed1(){
		$filename 		=  $_FILES["filename"]["name"];
		$post 			= $this->input->post();
		$directory 		= dirname(__DIR__, 4);
		$temppath 		= $directory.'/assets/uploads/temp/cpd template.xlsx';
		$file 			= $temppath;
		$type 			= \PhpOffice\PhpSpreadsheet\IOFactory::identify($file);
		$reader 		= \PhpOffice\PhpSpreadsheet\IOFactory::createReader($type);
		$spreadsheet 	= $reader->load($file);
		$datas 			= $spreadsheet->getActiveSheet()->toArray();
		unset($datas[0]);
		$tabledata = '<table style = "border-collapse: collapse;width: 100%;">
						<tr>
						    <th>Reg No</th>
						    <th>Points(optional)</th>
						</tr>';
			foreach ($datas as $dataskey => $datasvalue) {
				$tabledata .= '<tr>
						    <td>'.$datasvalue[0].'</td>
						    <td>'.$datasvalue[1].'</td>
					  	</tr>';
			}
		$tabledata .= '</table>';
		echo $tabledata;
	}

	public function importdownload(){
		$directory 	 	= dirname(__DIR__, 4);
		$templatepath 	= $directory.'/assets/uploads/cpdmassimport/cpd_template.xlsx';
		if (file_exists($directory.'/assets/uploads/cpdmassimport/cpd_errors.xlsx')) {
			unlink($directory.'/assets/uploads/cpdmassimport/cpd_errors.xlsx');
		}
		$file 			= $templatepath;
		$type 			= \PhpOffice\PhpSpreadsheet\IOFactory::identify($file);
		$reader 		= \PhpOffice\PhpSpreadsheet\IOFactory::createReader($type);
		$spreadsheet 	= $reader->load($file);
		$datas 			= $spreadsheet->getActiveSheet()->toArray();
		unset($datas[0]);
		$phpExcel = new Spreadsheet();
			$phpExcel->setActiveSheetIndex(0)
					->setCellValue('A1','Reg No') // reg
			        ->setCellValue('B1','Points(optional)') // point
			        ->setCellValue('C1','User id') //user id 
			        ->setCellValue('D1','Name Surname') // name
			        ->setCellValue('E1','Errors'); // status
		$k = 0;
		foreach ($datas as $dataskey => $datasvalue) {
			if (($datasvalue[5] == '0' && $datasvalue[6] =='Plumber not found') || ($datasvalue[5] == '0' && $datasvalue[6] =='Activity already approved')) {
				$row = $k+2;
					$phpExcel->setActiveSheetIndex(0)
					         ->setCellValue('A'.$row.'',$datasvalue[0]) // reg
					         ->setCellValue('B'.$row.'',$datasvalue[1]) // point
					         ->setCellValue('C'.$row.'',$datasvalue[2]) //user id 
					         ->setCellValue('D'.$row.'',$datasvalue[3]) // name
					         // ->setCellValue('G'.$row.'',$cpddatavalue[4].' '.$cpddatavalue[6]); // status
					         ->setCellValue('E'.$row.'',$datasvalue[6]); // status
			}$k++;
		}
		$writer = new Xlsx($phpExcel);
		$writer->save($directory.'/assets/uploads/cpdmassimport/cpd_errors.xlsx');
		// if (file_exists($templatepath)) {
		// 	unlink($templatepath);
		// }
		echo "file downloaded";

	}
	public function importproceed(){
		// $tempfile = $_FILES["filename"]["name"];
		$post 	= $this->input->post();
		$userid = $this->getUserID();
		$userdetails = $this->getUserDetails();
		$directory 	 = dirname(__DIR__, 4);
		$templatepath = $directory.'/assets/uploads/cpdmassimport/cpd_template.xlsx';
		$file 	= $templatepath;
		$type 	= \PhpOffice\PhpSpreadsheet\IOFactory::identify($file);
		$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($type);
		$spreadsheet = $reader->load($file);
		$datas 	= $spreadsheet->getActiveSheet()->toArray();
		unset($datas[0]);

		foreach ($datas as $key => $value) {
			if ($value[6] == 'Activity already approved' || $value[6] == 'Activity already pending') {
				$this->db->select('*');
				$this->db->from('cpd_activity_form');
				$this->db->where('reg_number', $value[0]);
				$this->db->where('cpdtype_id', $post['cpdid']);
				$this->db->where('status', '0');
				$query = $this->db->get();
				$result = $query->row_array();
				if ($result && $result!='') {
					$updatedata = [
						'status' => '1',
						'points' => $value[1],
						'admin_comments' => 'Approved by '.$this->config->item('roletype')[$userdetails['roletype']].'',
						'updated_by' => $userid,
					];
					$this->db->update('cpd_activity_form', $updatedata, ['id' => $result['id']]);
				}
			}

			if ($value[5] =='1') {
				$formdata = [
					'user_id_hide' 		=> $value[2],
					'search_reg_no' 	=> $value[0],
					'name_surname' 		=> $value[3],
					'activity_id_hide' 	=> $post['cpdid'],
					'activity' 			=> $post['activity'],
					'hidden_stream_id' 	=> $post['cpdstream'],
					'points' 			=> $value[1],
					'status' 			=> '1',
					// 'created_at'		=> date('Y-m-d H:i:s'),
					'approved_date'		=> date('Y-m-d H:i:s'),
					'startdate'			=> date('Y-m-d H:i:s'),
					'flag'				=> '1',
					'admin_comments' 	=> 'Approved by '.$this->config->item('roletype')[$userdetails['roletype']].'',
					// 'created_by'		=> $userid,

				];
				//  echo "<pre>";print_r($formdata);die;
				$data 	=  $this->Cpdtypesetup_Model->queue_action($formdata);
			}
		}
		$temp 	 			= $directory.'/assets/uploads/temp/cpd template.xlsx';
		// $temp 	 			= $directory.'/assets/uploads/temp/'.$tempfile.'';
		
		if (file_exists($temp)) {
			unlink($temp);
		}
		if (file_exists($templatepath)) {
			unlink($templatepath);
		}
		if (file_exists($directory.'/assets/uploads/cpdmassimport/cpd_errors.xlsx')) {
			unlink($directory.'/assets/uploads/cpdmassimport/cpd_errors.xlsx');
		}
		echo "CPD Added Successfully.";
	}

	public function sampletemplate(){
		$post = $this->input->post();
		$directory 	 = dirname(__DIR__, 4);
		$sampledirectory 	 = $directory.'/assets/uploads/cpdmassimport/sample';
		if (!is_dir($sampledirectory)) {
			mkdir($sampledirectory, 0755, true);
		}
		if (!file_exists($sampledirectory.'/'.$post['filename'].'')) {
			$phpExcel = new Spreadsheet();
			$phpExcel->setActiveSheetIndex(0)
					->setCellValue('A1','Reg No') // reg
			        ->setCellValue('B1','Points(optional)'); // point
			$writer = new Xlsx($phpExcel);
			$writer->save($sampledirectory.'/'.$post['filename'].'');
		}
		echo "csv template";
	}

	public function error_unlink(){
		$directory 	 		= dirname(__DIR__, 4);
		$errortemplate 	 	= $directory.'/assets/uploads/cpdmassimport/cpd_errors.xlsx';
		if (file_exists($errortemplate)) {
			unlink($errortemplate);
		}
		echo "1";
	}

	public function cancel(){

		// $filename =  $_FILES["file"]["name"];
		$directory 	 = dirname(__DIR__, 4);
		$templatepath = $directory.'/assets/uploads/cpdmassimport/cpd_template.xlsx';
		$errortemplate 	 = $directory.'/assets/uploads/cpdmassimport/cpd_errors.xlsx';
		$temp 	 			= $directory.'/assets/uploads/temp/cpd template.xlsx';
		
		if (file_exists($temp)) {
			unlink($temp);
		}
		if (file_exists($templatepath)) {
			unlink($templatepath);
		}
		if (file_exists($errortemplate)) {
			unlink($errortemplate);
		}

		echo "Canceled..";

	}
}
