<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once 'application/libraries/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

class Index extends CC_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Plumber_Model');
		$this->load->model('Mycpd_Model');
		$this->load->model('Coc_Model');
		$this->load->model('Installationtype_Model');
		$this->load->model('Noncompliance_Model');
		$this->load->model('Accounts_Model');
		$this->load->model('Documentsletters_Model');
		$this->load->model('Diary_Model');
		$this->load->model('Performancestatus_Model');
		$this->load->model('Systemsettings_Model');
		$this->load->model('Renewal_Model');
		$this->load->model('Communication_Model');

		
	}
	
	public function index()
	{
		$this->checkUserPermission('18', '1');

		$pagedata['notification'] 	= $this->getNotification();
		$pagedata['company'] 		= $this->getCompanyList();
		$pagedata['plumberstatus'] 	= $this->config->item('plumberstatus');
		$pagedata['checkpermission'] = $this->checkUserPermission('18', '2');
		
		$data['plugins']			= ['datatables', 'datatablesresponsive', 'sweetalert', 'datepicker'];
		$data['content'] 			= $this->load->view('admin/plumber/index', (isset($pagedata) ? $pagedata : ''), true);
		
		$this->layout2($data);		
	}
	
	
	public function DTPlumber()
	{
		$post 			= $this->input->post();

		$totalcount 	= $this->Plumber_Model->getList('count', ['type' => '3', 'approvalstatus' => ['0','1'], 'status' => ['1', '2']]+$post, ['users', 'usersdetail', 'usersplumber', 'alllist']);
		$results 		= $this->Plumber_Model->getList('all', ['type' => '3', 'approvalstatus' => ['0','1'], 'status' => ['1', '2']]+$post, ['users', 'usersdetail', 'usersplumber', 'alllist']);

		$checkpermission = $this->checkUserPermission('18', '2');

		$totalrecord 	= [];
		if(count($results) > 0){
			foreach($results as $result){

				if ($checkpermission) {
					$action = '<div class="table-action">
									<a href="'.base_url().'admin/plumber/index/action/'.$result['id'].'" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil-alt"></i></a>
								</div>';
				}else{
					$action = '';
				}

				$designation 	= isset($this->config->item('designation2')[$result["designation"]]) ? $this->config->item('designation2')[$result["designation"]] : '';
				$status 		= isset($this->config->item('plumberstatus')[$result["plumberstatus"]]) ? $this->config->item('plumberstatus')[$result["plumberstatus"]] : '';

				$totalrecord[] = 	[
										'reg_no' 		=> 	$result['registration_no'],
										'name' 			=> 	$result['name'],
										'surname' 		=> 	$result['surname'],
										'designation' 	=> 	$designation,
										'email' 		=> 	$result['email'],
										'status' 		=> 	$status,
										'action'		=> 	$action
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
	
	public function action($id)
	{
		$this->plumberprofile($id, ['roletype' => $this->config->item('roleadmin'), 'pagetype' => 'applications'], ['redirect' => 'admin/plumber/index']);
	}
	
	public function rejected()
	{
		$this->checkUserPermission('19', '1');

		$pagedata['notification'] 	= $this->getNotification();
		$pagedata['checkpermission'] = $this->checkUserPermission('19', '2');
		
		$data['plugins']			= ['datatables', 'datatablesresponsive'];
		$data['content'] 			= $this->load->view('admin/plumber/rejected', (isset($pagedata) ? $pagedata : ''), true);
		
		$this->layout2($data);		
	}
	
	
	public function DTRejectedPlumber()
	{
		$post 			= $this->input->post();

		$totalcount 	= $this->Plumber_Model->getList('count', ['type' => '3', 'approvalstatus' => ['2'], 'status' => ['1']]+$post, ['users', 'usersdetail', 'usersplumber']);
		$results 		= $this->Plumber_Model->getList('all', ['type' => '3', 'approvalstatus' => ['2'], 'status' => ['1']]+$post, ['users', 'usersdetail', 'usersplumber']);

		$checkpermission = $this->checkUserPermission('19', '2');

		$totalrecord 	= [];
		if(count($results) > 0){
			foreach($results as $result){

				if ($checkpermission) {
					$action = '<div class="table-action">
									<a href="'.base_url().'admin/plumber/index/action/'.$result['id'].'" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil-alt"></i></a>
								</div>';
				}else{
					$action = '';
				}


				$rejectreason 	= $this->config->item('rejectreason');
				
				$reasonforrejection = [];
				$exploderejectreasons 	= explode(',', $result['reject_reason']);
				foreach($exploderejectreasons as $exploderejectreason){
					$rejectreasondata = isset($rejectreason[$exploderejectreason]) ? $rejectreason[$exploderejectreason] : '';
					if($exploderejectreason==4){
						$reasonforrejection[] = $rejectreasondata.' - '.$result['reject_reason_other'];
					}else{
						$reasonforrejection[] = $rejectreasondata;
					}
				}
				
				$totalrecord[] = 	[
										'applicationreceived' 	=> 	date('d-m-Y', strtotime($result['application_received'])),
										'name' 					=> 	$result['name'].' '.$result['surname'],
										'reason' 				=> 	implode(', ', $reasonforrejection),
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
	
	public function rejectedaction($id)
	{
		$this->plumberprofile($id, ['roletype' => $this->config->item('roleadmin'), 'pagetype' => 'rejectedapplications'], ['redirect' => 'admin/plumber/index/rejected']);
	}

	public function cpd($id,$pagestatus='')
	{
		$pagedata['company'] 		= $this->getCompanyList();
		$pagedata['plumberstatus'] 	= $this->config->item('plumberstatus');
		$pagedata['pagestatus'] 	= $this->getPageStatus($pagestatus);		
		$userdata1					= $this->Plumber_Model->getList('row', ['id' => $id], ['usersdetail', 'usersplumber']);
		$pagedata['roletype']		= $this->config->item('roleadmin');
		$pagedata['id'] 			= $id;
		$pagedata['user_details'] 	= $userdata1;
		$pagedata['menu']			= $this->load->view('common/plumber/menu', ['id'=>$id],true);
		$pagedata['notification'] 	= $this->getNotification();
		$userdetails 				= $this->getUserDetails($id);
		$dbexpirydate 				= $userdetails['expirydate'];
		
		$pagedata['history']		= $this->Auditor_Model->getReviewHistory2Count(['plumberid' => $id]);
		$developmental 				= $this->Auditor_Model->admingetcpdpoints('all', ['pagestatus' => $pagedata['pagestatus'], 'plumberid' => $id, 'status' => ['1'], 'cpd_stream' => 'developmental', 'dbexpirydate' => $userdetails['expirydate']]);
		$individual 				= $this->Auditor_Model->admingetcpdpoints('all', ['pagestatus' => $pagedata['pagestatus'], 'plumberid' => $id, 'status' => ['1'], 'cpd_stream' => 'individual', 'dbexpirydate' => $userdetails['expirydate']]);
		$workbased 				= $this->Auditor_Model->admingetcpdpoints('all', ['pagestatus' => $pagedata['pagestatus'], 'plumberid' => $id, 'status' => ['1'], 'cpd_stream' => 'workbased', 'dbexpirydate' => $userdetails['expirydate']]);

		if (count($developmental) > 0) {
			$pagedata['developmental'] = array_sum(array_column($developmental, 'points')); 
		}
		if (count($individual) > 0) {
			$pagedata['individual'] = array_sum(array_column($individual, 'points')); 
		}
		if (count($workbased) > 0) {
			$pagedata['workbased'] = array_sum(array_column($workbased, 'points')); 
		}
		// $pagedata['developmental'] 	= $developmental['points'];
		// $pagedata['individual']		= $individual['points'];
		// $pagedata['workbased']		= $workbased['points'];
		$pagedata['settings_cpd']	= $this->Systemsettings_Model->getList('all');
		
		$data['plugins']			= ['datatables', 'datatablesresponsive', 'sweetalert', 'validation', 'echarts'];

		$data['content'] 			= $this->load->view('admin/plumber/cpd', (isset($pagedata) ? $pagedata : ''), true);
		
		$this->layout2($data);		
	}
	

	// public function DTCpdQueue()
	// {
	// 	$post 			= $this->input->post();

	// 	$totalcount 	= $this->Mycpd_Model->getQueueList('count', ['status' => $post['pagestatus'], 'user_id' => $post['user_id']]+$post);
	// 	$results 		= $this->Mycpd_Model->getQueueList('all', ['status' => $post['pagestatus'], 'user_id' => $post['user_id']]+$post);
		
	// 	$totalrecord 	= [];
	// 	if(count($results) > 0){
	// 		foreach($results as $result){
	// 			if ($result['status']==0) {
	// 				$statuz 	= 'Pending';
	// 				$awardPts 	= '';
	// 				$action 	= '';//'<div class="table-action"><a href="'.base_url().'plumber/mycpd/index/index/'.$post['pagestatus'].'/'.$result['id'].'" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil-alt"></i></a></div>';
	// 			}elseif($result['status']==3){
	// 				$statuz 	= 'Not Submited';
	// 				$awardPts 	= '';
	// 				$action 	= '';//'<div class="table-action"><a href="'.base_url().'plumber/mycpd/index/index/'.$post['pagestatus'].'/'.$result['id'].'" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil-alt"></i></a></div>';
	// 			}
			
	// 			else{
	// 				$statuz 	= $this->config->item('approvalstatus')[$result['status']];
	// 				$awardPts 	= $result['points'];
	// 				$action 	= '<div class="table-action"><a href="'.base_url().'admin/plumber/index/viewcpd/'.$post['pagestatus'].'/'.$result['id'].'/'.$post['user_id'].'" data-toggle="tooltip" data-placement="top" title="View"><i class="fa fa-eye"></i></a></div>';
	// 			}

	// 			// Attachments
	// 			if ($result['file1']!='') {
	// 				$attach = '<div class="table-action">
	// 				<a href="'.base_url().'assets/uploads/cpdqueue/'.$result['file1'].'" target="_blank" data-toggle="tooltip" data-placement="top" title="View Attachments"><i class="fa fa-download"></i></a>
	// 				</div>';
	// 			}else{
	// 				$attach = '';
	// 			}


	// 			$totalrecord[] = 	[
	// 				'date' 					=> 	date("m-d-Y", strtotime($result['cpd_start_date'])),
	// 				'acivity' 				=> 	$result['cpd_activity'],
	// 				'streams' 				=> 	$this->config->item('cpdstream')[$result['cpd_stream']],
	// 				'comments' 				=> 	$result['comments'],
	// 				'points' 				=> 	$awardPts,
	// 				'attachment' 			=> 	$attach,
	// 				'status' 				=> 	$statuz,
	// 				'action'				=> 	$action
	// 			];
	// 		}
	// 	}
		
	// 	$json = array(
	// 		"draw"            => intval($post['draw']),   
	// 		"recordsTotal"    => intval($totalcount),  
	// 		"recordsFiltered" => intval($totalcount),
	// 		"data"            => $totalrecord
	// 	);

	// 	echo json_encode($json);
	// }

	public function DTCpdQueue()
	{
		$post 			= $this->input->post();
		$userdetails 				= $this->getUserDetails($post['user_id']);
		$dbexpirydate 				= $userdetails['expirydate'];

		$totalcount 	= $this->Mycpd_Model->getQueueList('count', ['status' => [$post['pagestatus']], 'user_id' => [$post['user_id']], 'dbexpirydate' => $userdetails['expirydate']]+$post);
		$results 		= $this->Mycpd_Model->getQueueList('all', ['status' => [$post['pagestatus']], 'user_id' => [$post['user_id']], 'dbexpirydate' => $userdetails['expirydate']]+$post);
		//print_r($results);die;

		if ($post['pagestatus'] =='0') $pagestatus = '2';
			else $pagestatus = '1';
		
		$totalrecord 	= [];
		if(count($results) > 0){
			foreach($results as $result){
				if ($result['status']!=3) {

					if ($result['status']==0) {
					$statuz 	= 'Pending';
					$awardPts 	= '';
					$action 	= '
					<div class="table-action">
					<a href="'.base_url().'admin/plumber/mycpd/index/index/'.$post['user_id'].'/'.$result['id'].'/'.$pagestatus.'" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil-alt"></i></a>
					</div>
					';
				}			
				else{
					$statuz 	= $this->config->item('approvalstatus')[$result['status']];
					if ($statuz!='Rejected') {
						$awardPts 	= $result['points'];
						$addclass 	= '<i class="fa fa-pencil-alt"></i>';
						$classtitle = 'Edit';
					}else{
						$awardPts 	= 0;
						$addclass 	= '<i class="fa fa-eye"></i>';
						$classtitle = 'View';
					}
					
					$action 	= '
					<div class="table-action">
					<a href="'.base_url().'admin/plumber/mycpd/index/index/'.$post['user_id'].'/'.$result['id'].'/'.$pagestatus.'" data-toggle="tooltip" data-placement="top" title="'.$classtitle.'">'.$addclass.'</a>
					</div>
					';
				}

				// Attachments
				if ($result['file1']!='') {
					$attach = '<div class="table-action">
					<a href="'.base_url().'assets/uploads/cpdqueue/'.$result['file1'].'" target="_blank" data-toggle="tooltip" data-placement="top" title="View Attachments"><i class="fa fa-download"></i></a>
					</div>';
				}else{
					$attach = '';
				}


				$totalrecord[] = 	[
					'date' 					=> 	date("d-m-Y", strtotime($result['cpd_start_date'])),
					'acivity' 				=> 	$result['cpd_activity'],
					'streams' 				=> 	isset($this->config->item('cpdstream')[$result['cpd_stream']]) ? $this->config->item('cpdstream')[$result['cpd_stream']] : '',
					'comments' 				=> 	$result['comments'],
					'points' 				=> 	$awardPts,
					'attachment' 			=> 	$attach,
					'status' 				=> 	$statuz,
					'action'				=> 	$action
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

	public function viewcpd($pagestatus='',$id='',$userid='')
	{
		$this->mycptindex($pagestatus,$id,$userid);
	}

	public function performance($id, $pagestatus='')
	{	
		if($this->input->post()){
			$requestData	=	$this->input->post();
			$data 			= 	$this->Performancestatus_Model->action($requestData);
			
			if(isset($data)) $this->session->set_flashdata('success', 'Successfully Saved.');
			else $this->session->set_flashdata('error', 'Try Later.');
				
			redirect('admin/plumber/index/performance/'.$id); 
		}
		
		$userid 					= $id;
		$rollingavg 				= $this->getRollingAverage();
		$date						= date('Y-m-d', strtotime(date('Y-m-d').'+'.$rollingavg.' months'));
		$pagestatus					= ($pagestatus=='2' ? '1' : '0');
		$extraparam					= $pagestatus=='0' ? ['date' => $date] : [];
		
		$pagedata['plumberid'] 		= $id;
		$pagedata['userdata']		= $this->Plumber_Model->getList('row', ['id' => $id], ['usersdetail']);
		$pagedata['menu']			= $this->load->view('common/plumber/menu', ['id'=>$id],true);
		$pagedata['notification'] 	= $this->getNotification();
		$pagedata['performancelist']= $this->getPlumberPerformanceList();
		$pagedata['pagestatus'] 	= $pagestatus;
		$pagedata['warning']		= $this->Global_performance_Model->getWarningList('all', ['status' => ['1']]);
		$pagedata['results']		= $this->Plumber_Model->performancestatus('all', ['plumberid' => $userid, 'archive' => $pagestatus]+$extraparam);
		
		$data['plugins']			= ['datatables', 'datatablesresponsive', 'sweetalert', 'validation', 'datepicker', 'select2', 'echarts'];
		$data['content'] 			= $this->load->view('admin/plumber/performance', (isset($pagedata) ? $pagedata : ''), true);
		
		$this->layout2($data);
	}
	
	public function DTPerformancestatus()
	{
		$post 			= $this->input->post();
		
		$userid 		= $post['id'];
		$rollingavg 	= $this->getRollingAverage();
		$date			= date('Y-m-d', strtotime(date('Y-m-d').'+'.$rollingavg.' months'));
		
		if($post['archive']=='0'){
			$post['date'] = $date;
		}
		$totalcount 	= $this->Plumber_Model->performancestatus('count', ['plumberid' => $userid]+$post);
		$results 		= $this->Plumber_Model->performancestatus('all', ['plumberid' => $userid]+$post);
		
		$totalrecord 	= [];
		if(count($results) > 0){
			$pdfimg 	= base_url().'assets/images/pdf.png';
			
			foreach($results as $result){	
				$filepath	= ($result['flag']=='2') ? base_url().'assets/uploads/cpdqueue/' : base_url().'assets/uploads/plumber/'.$userid.'/performance/';
				$attachment = $result['attachment'];
				if($attachment!=''){						
					$explodeattachment 	= explode('.', $attachment);
					$extfile 			= array_pop($explodeattachment);
					$file 				= (in_array($extfile, ['pdf', 'tiff'])) ? $pdfimg : $filepath.$attachment;
					$attachment 		= '<a href="'.$filepath.$attachment.'" target="_blank"><img src="'.$file.'" width="100"></a>';
				}else{
					$attachment 		= '';
				}
							
				$totalrecord[] = 	[
										'date' 				=> 	date('d-m-Y', strtotime($result['date'])),
										'type' 				=> 	$result['type'],
										'comments' 			=> 	$result['comments'],
										'point' 			=> 	$result['point'],
										'attachment' 		=> 	$attachment,
										'action'			=> 	'
																	<div class="table-action">	
																		<a href="javascript:void(0);" class="archive" data-id="'.$result['id'].'" data-flag="'.$result['flag'].'"><i class="fa fa-archive"></i></a>
																	</div>
																'
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
	
	public function performanceaction()
	{
		$requestData	=	$this->input->post();
		$data 			= 	$this->Plumber_Model->performancestatusaction($requestData);
		
		if(isset($data)) $this->session->set_flashdata('success', 'Successfully archived.');
		else $this->session->set_flashdata('error', 'Try Later.');
			
		redirect('admin/plumber/index/performance/'.$requestData['plumberid']); 
	}
	
	public function coc($id,$pagestatus='')
	{
		$userdata1					= $this->Plumber_Model->getList('row', ['id' => $id], ['usersdetail']);
		$pagedata['roletype']		= $this->config->item('roleadmin');
		$pagedata['id'] 			= $id;
		$pagedata['user_details'] 	= $userdata1;
		$pagedata['menu']			= $this->load->view('common/plumber/menu', ['id'=>$id],true);
		$pagedata['notification'] 	= $this->getNotification();		
		$pagedata['history']		= $this->Auditor_Model->getReviewHistoryCount(['plumberid' => $id]);				
		$pagedata['logged']			= $this->Coc_Model->getCOCList('count', ['user_id' => $id, 'coc_status' => ['2']]);
		$pagedata['allocated']		= $this->Coc_Model->getCOCList('count', ['user_id' => $id, 'coc_status' => ['4']]);
		$pagedata['nonlogged']		= $this->Coc_Model->getCOCList('count', ['user_id' => $id, 'coc_status' => ['5']]);

		$data['plugins']			= ['datatables', 'datatablesresponsive', 'sweetalert', 'validation', 'echarts'];
		$data['content'] 			= $this->load->view('admin/plumber/coc', (isset($pagedata) ? $pagedata : ''), true);
		$this->layout2($data);
	}

	public function DTCocStatement()
	{
		
		$post 			= $this->input->post();

		$user_id = $post['user_id'];

		$totalcount 	= $this->Coc_Model->getCOCList('count', ['coc_status' => ['2','4','5'], 'user_id' => $user_id]+$post, ['coclog', 'coclogcompany', 'reseller', 'resellerdetails']);
		$results 		= $this->Coc_Model->getCOCList('all', ['coc_status' => ['2','4','5'], 'user_id' => $user_id]+$post, ['coclog', 'coclogcompany', 'reseller', 'resellerdetails']);

		$totalrecord 	= [];
		if(count($results) > 0){
			$action = '';
			foreach($results as $result){
				if($result['coc_status']=='5' || $result['coc_status']=='4'){
					$action = ''; //'<a href="'.base_url().'admin/plumber/index/actioncoc/'.$result['id'].'/'.$user_id.'" data-toggle="tooltip" data-placement="top" title="Edit" disbled><i class="fa fa-pencil-alt"></i></a>';
				}elseif($result['coc_status']=='2'){
					$action = '<a href="'.base_url().'admin/plumber/index/viewcoc/'.$result['id'].'/'.$user_id.'" data-toggle="tooltip" data-placement="top" title="View"><i class="fa fa-eye"></i></a>';
				}elseif($result['coc_status']=='7'){
					$action = '';
				}
				
				$cocstatus = isset($this->config->item('cocstatus')[$result['coc_status']]) ? $this->config->item('cocstatus')[$result['coc_status']] : '';
				$coctype = isset($this->config->item('coctype')[$result['type']]) ? $this->config->item('coctype')[$result['type']] : '';
				
				$totalrecord[] = 	[
										'cocno' 			=> 	$result['id'],
										'cocstatus' 		=> 	$cocstatus,
										'purchased' 		=> 	(date('d-m-Y', strtotime($result['allocation_date']))!='01-01-1970') ? date('d-m-Y', strtotime($result['allocation_date'])) : '-',
										'logdate' 			=> 	(date('d-m-Y', strtotime($result['cl_log_date']))!='01-01-1970') ? date('d-m-Y', strtotime($result['cl_log_date'])) : '-',
										'coctype' 			=> 	$coctype,
										'customer' 			=> 	$result['cl_name'],
										'address' 			=> 	$result['cl_address'],
										'company' 			=> 	$result['plumbercompany'],
										'reseller' 			=> 	$result['resellername'],
										'action'			=> 	'
																	<div class="table-action">
																		'.$action.'
																	</div>
																'
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

	public function viewcoc($id,$plumberid='')
	{
		$this->coclogaction(
			$id, 
			['pagetype' => 'view', 'roletype' => $this->config->item('roleadmin'), 'electroniccocreport' => 'admin/plumber/index/electroniccocreport/'.$id.'/'.$plumberid, 'noncompliancereport' => 'admin/plumber/index/noncompliancereport/'.$id.'/'.$plumberid], 
			['redirect' => 'admin/plumber/index', 'userid' => $plumberid]
		);
	}

	public function actioncoc($id,$plumberid='')
	{
		$this->coclogaction(
			$id, 
			['pagetype' => 'action', 'roletype' => $this->config->item('roleadmin'), 'electroniccocreport' => 'admin/plumber/index/electroniccocreport/'.$id.'/'.$plumberid, 'noncompliancereport' => 'admin/plumber/index/noncompliancereport/'.$id.'/'.$plumberid], 
			['redirect' => 'admin/plumber/index', 'userid' => $plumberid]
		);
	}

	public function electroniccocreport($id,$plumberid='')
	{	
		$userid = $plumberid;
		$this->pdfelectroniccocreport($id, $userid);
	}
	
	public function noncompliancereport($id,$plumberid='')
	{	
		$userid = $plumberid;
		$this->pdfnoncompliancereport($id, $userid);
	}

	public function audit($id)
	{		
		$userdata1					= $this->Plumber_Model->getList('row', ['id' => $id], ['usersdetail']);
		$pagedata['roletype']		= $this->config->item('roleadmin');
		$pagedata['id'] 			= $id;
		$pagedata['user_details'] 	= $userdata1;
		$pagedata['menu']			= $this->load->view('common/plumber/menu', ['id'=>$id],true);
		$pagedata['notification'] 	= $this->getNotification();
		
		$pagedata['history']		= $this->Auditor_Model->getReviewHistoryCount(['plumberid' => $id]);	

		$pagedata['loggedcoc']		= $this->Coc_Model->getCOCList('count', ['user_id' => $id, 'coc_status' => ['2']]);
		
		$data['plugins']			= ['datatables', 'datatablesresponsive', 'sweetalert', 'validation', 'echarts'];
		$data['content'] 			= $this->load->view('admin/plumber/audit', (isset($pagedata) ? $pagedata : ''), true);
		$this->layout2($data);

	}

	public function DTAuditStatement()
	{
		
		$post 			= $this->input->post();
		$userid 		= $post['user_id'];
		$totalcount 	= $this->Coc_Model->getCOCList('count', ['coc_status' => ['2'], 'user_id' => $userid, 'noaudit' => '']+$post, ['coclog', 'auditordetails', 'auditorstatement']);
		$results 		= $this->Coc_Model->getCOCList('all', ['coc_status' => ['2'], 'user_id' => $userid, 'noaudit' => '']+$post, ['coclog', 'auditordetails', 'auditorstatement']);	
		
		$totalrecord 	= [];
		if(count($results) > 0){
			foreach($results as $result){
				$auditstatus 	= isset($this->config->item('auditstatus')[$result['audit_status']]) ? $this->config->item('auditstatus')[$result['audit_status']] : '';
				$action 		= '<a href="'.base_url().'admin/plumber/index/viewaudit/'.$result['id'].'/'.$userid.'" data-toggle="tooltip" data-placement="top" title="View"><i class="fa fa-eye"></i></a>';
				
				$refixdate 			= ($result['ar1_refix_date']!='') ? '<p class="'.((date('Y-m-d') > date('Y-m-d', strtotime($result['ar1_refix_date']))) && $result['as_refixcompletedate']=='' ? "tagline" : "").'">'.date('d-m-Y', strtotime($result['ar1_refix_date'])).'</p>' : '';
				$refixcompletedate 	= ($result['as_refixcompletedate']!='') ? '<p class="successtagline">'.date('d-m-Y', strtotime($result['as_refixcompletedate'])).'</p>' : '';
				
				$totalrecord[] 	= 	[
										'cocno' 			=> 	$result['id'],
										'status' 			=> 	$auditstatus,
										'consumer' 			=> 	$result['cl_name'],
										'address' 			=> 	$result['cl_address'],
										'refixdate' 		=> 	$refixdate,
										'refixcompletedate' => 	$refixcompletedate,
										'auditordate' 		=> 	isset($result['audit_allocation_date']) && $result['audit_allocation_date']!='1970-01-01' ? date('d-m-Y', strtotime($result['audit_allocation_date'])) : '',
										'auditor' 			=> 	$result['auditorname'],
										'action'			=> 	'
																	<div class="table-action">
																		'.$action.'
																	</div>
																'
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

	public function viewaudit($id, $plumberid='')
	{
		$this->getauditreview($id, ['pagetype' => 'view', 'viewcoc' => 'admin/plumber/index/viewcocaudit/'.$id.'/'.$plumberid, 'auditreport' => 'admin/plumber/index/auditreport/'.$id.'/'.$plumberid, 'roletype' => $this->config->item('roleadmin')], ['redirect' => 'admin/audits/auditstatement/index']);
	}
	
	public function viewcocaudit($id, $plumberid='')
	{
		$this->coclogaction(
			$id, 
			['pagetype' => 'view', 'roletype' => $this->config->item('roleadmin'), 'electroniccocreport' => 'admin/audits/auditstatement/index/electroniccocreport/'.$id.'/'.$plumberid, 'noncompliancereport' => 'admin/audits/auditstatement/index/noncompliancereport/'.$id.'/'.$plumberid], 
			['redirect' => 'admin/audits/auditstatement/index', 'userid' => $plumberid]
		);
	}

	public function auditreport($id, $plumberid='')
	{
		$this->pdfauditreport($id);
	}

	public function accounts($id)
	{
		$userdata1					= $this->Plumber_Model->getList('row', ['id' => $id], ['users', 'usersdetail', 'usersplumber']);
		$invoicedetails				= $this->Accounts_Model->getInvdeatils('row', ['user_id' => $id, 'inv_type' => '2', 'status' => '0']);
		// echo "<pre>";print_r($invoicedetails);die;
		$pagedata['invoicedetails']	= $invoicedetails;
		$pagedata['roletype']		= $this->config->item('roleadmin');
		$pagedata['id'] 			= $id;
		$pagedata['user_details'] 	= $userdata1;
		$pagedata['menu']			= $this->load->view('common/plumber/menu', ['id'=>$id],true);
		$pagedata['notification'] 	= $this->getNotification();
		$data['plugins']			= ['datatables', 'datatablesresponsive', 'sweetalert', 'validation'];
		$data['content'] 			= $this->load->view('admin/plumber/accounts', (isset($pagedata) ? $pagedata : ''), true);
		$this->layout2($data);
	}

	public function triggerrenewal(){
		$post 		= $this->input->post();
		$result		= $this->Accounts_Model->getDetails($post['id']);
		$settings 	= $this->Systemsettings_Model->getList('row');
		$userid 	= $post['id'];
		$adminid 	= $this->getUserID();
		$datetime 	= date('Y-m-d H:i:s');
		$fileName 	= base_url().'admin/plumber/index/triggerrenewal';
		
		foreach($result as $data)
		{
			$designation 		= $data['designation'];
			$renewal_date1 		= $data['expirydate'];
			$rdate 				= strtotime($renewal_date1);
			$new_date 			= strtotime('+ 1 year', $rdate);
			$renewal_date 		= date('d/m/Y', $new_date);
			
			
			$userdata1	= 	$this->Plumber_Model->getList('row', ['id' => $userid], ['users', 'usersdetail', 'usersplumber']);
			$otherfee 	= 	$this->invoiceotherfee($userdata1);
			
			$result = $this->Renewal_Model->updatedata($userid,$designation,'2','','',$otherfee);			
			$invoice_id = $result['invoice_id'];
			$cocorder_id = $result['cocorder_id'];
		
			$log	.= $userid.'-'.$invoice_id.'-'.date('d-m-Y', strtotime($renewal_date1)).PHP_EOL;
			
			if ($invoice_id) {
				$inid 				= $cocorder_id;
				$inv_id 			= $invoice_id;

				// $orders = $this->db->select('*')->from('coc_orders')->where(['inv_id' => $invoice_id])->get()->row_array();
				$orders = $this->db->select('cocod.coc_type, cocod.created_at, cocod.quantity, cocod.inv_id')->from('coc_orders as cocod')->where(['cocod.inv_id' => $invoice_id])->get()->row_array();

				$rowData = $this->Coc_Model->getListPDF('row', ['id' => $inv_id, 'status' => ['0','1']]);
				$designation =	$this->config->item('designation2')[$rowData['designation']];					
				$cocreport = $this->cocreport($inv_id, 'PDF Invoice Plumber COC', ['description' => 'PIRB year renewal fee for '.$designation.' for '.$rowData['username'].' '.$rowData['surname'].', registration number '.$rowData['registration_no']]+$otherfee);
				
				$cocTypes = $orders['coc_type'];
				$mail_date = date("d-m-Y", strtotime($orders['created_at']));
				
				
				$notificationdata 	= $this->Communication_Model->getList('row', ['id' => '1', 'emailstatus' => '1']);
				
				if($notificationdata){
					$array1 = ['{Plumbers Name and Surname}','{date of purchase}', '{Number of COC}','{COC Type}','{renewal_date}'];
					$array2 = [$userdata1['name']." ".$userdata1['surname'], $mail_date, $orders['quantity'], $this->config->item('coctype2')[$cocTypes],$renewal_date];
					$body 	= str_replace($array1, $array2, $notificationdata['email_body']);
					$this->CC_Model->sentMail($userdata1['email'], $notificationdata['subject'], $body, $cocreport);
				}
				
				if($settings && $settings['otp']=='1'){
					$smsdata 	= $this->Communication_Model->getList('row', ['id' => '1', 'smsstatus' => '1']);
		
					if($smsdata){
						$sms = $smsdata['sms_body'];
						$this->sms(['no' => $userdata1['mobile_phone'], 'msg' => $sms]);
					}
				}

			}
			$log = [
				'plumber_id' 	=> $userid,
				'admin_id' 		=> $adminid,
				'type' 			=> '1',
				'url' 			=> $fileName,
				'created_at' 	=> $datetime
			];
			$this->db->insert('trigger_renewal_log', $log);

		}
		echo "1";

	}


	public function invoiceotherfee($userdata1){
		$otherfee 		= [];
		$settings 		= $this->Systemsettings_Model->getList('row');
		$designation 	= explode(',', $settings['renewal_card']);

		// if($userdata1['registration_card']=='1' && $settings['renewal_card'] =='1'){
		if($userdata1['registration_card']=='1' && in_array($userdata1['designation'], $designation)){
		
			$otherfee['cardfee'] = $this->getRates($this->config->item('cardfee'));
			/*if($userdata1['delivery_card']=='1'){
				$otherfee['deliveryfee'] 	= $this->getRates($this->config->item('postage'));
				$otherfee['deliverycard'] 	= '1';
			}elseif($userdata1['delivery_card']=='2'){
				$otherfee['deliveryfee'] 	= $this->getRates($this->config->item('couriour'));
				$otherfee['deliverycard'] 	= '2';
			}*/
		}
		$specialisations = array_filter(explode(',', $userdata1['specialisations']));
		if((count($specialisations) > 0) && $settings['renewal_specialization'] =='1'){
			$otherfee['specialisationsfee'] = $this->getRates($this->config->item('specializationfee'));
			$otherfee['specialisationsqty'] = count($specialisations);
		}
		
		return $otherfee;
	}

	public function DTAccounts()
	{
		$post 			= $this->input->post();
		$userid 		= $post['user_id'];
		$userdata1		= $this->Plumber_Model->getList('row', ['id' => $userid], ['users', 'usersdetail']);		
		$totalcount 	= $this->Accounts_Model->getList('count', ['user_id' => $userid]+$post);
		$results 		= $this->Accounts_Model->getList('all', ['user_id' => $userid]+$post);
		
		$totalrecord 	= [];
		if(count($results) > 0){
			foreach($results as $result){
				$invoicestatus = 	isset($this->config->item('payment_status2')[$result['status']]) ? $this->config->item('payment_status2')[$result['status']] : '';
				//print_r($this->db->last_query());
				
				if($result['status']=='0'){
					$this->session->set_userdata('pay_purchaseorder', $result['inv_id']);

					$action = 	'
									<input type="hidden" id="feeamt" value="'.$result['total_cost'].'">
									<input type="hidden" id="name" value="'.$userdata1['name'].'">
									<input type="hidden" id="surname" value="'.$userdata1['surname'].'">
									<input type="hidden" id="usremail" value="'.$userdata1['email'].'">
									<a <a href="javascript:void(0);"> <i class="fa fa-credit-card payfastpayment">
									<script>
									$(".payfastpayment").click(function(){
									$("#name_first").val($("#name").val());
									$("#name_last").val($("#surname").val());
									$("#totaldue1").val($("#feeamt").val());
									$("#email_address").val($("#usremail").val());
									$( "#paymentsubmit" ).trigger( "click" );								
									
								});
								</script></i></a>
								';
				}else{
					$action = 	'';	
				}
				
				$totalrecord[] = 	[      
										'description' 	=> 	$result['description'],
										'invoiceno' 	=> 	$result['inv_id'],
										'invoicedate' 	=> 	date('d-m-Y', strtotime($result['created_at'])),
										'invoicevalue' 	=> 	$result['total_due'],
										'invoicestatus' => 	$invoicestatus,
										'orderstatus' 	=> 	$result['orderstatusname'],		
							     		'action'	    => 	'
																<div class="col-md-6">
																	<a  href="' .base_url().'assets/inv_pdf/'.$result['inv_id'].'.pdf" target="_blank" ><img src="'.base_url().'assets/images/pdf.png" height="50" width="50"></a>
																	'.(isset($action) ? $action : '').'
																</div>'
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

	public function documents($plumberid,$documentsid='')
	{
		if($documentsid!=''){
			$result = $this->Documentsletters_Model->getList('row', ['id' => $documentsid]);
			if($result){
				$pagedata['result'] = $result;				

			}else{
				$this->session->set_flashdata('error', 'No Record Found.');
				if($extras['redirect']) redirect($extras['redirect']); 
				else redirect('admin/plumber/index'); 
			}
		}
		
		if($this->input->post()){
			$requestData 	= 	$this->input->post();		
			
			if(isset($requestData['submit']) && $requestData['submit']=='Generate Card Letter'){
				$result = $this->Plumber_Model->getList('row', ['id' => $plumberid, 'type' => '3', 'status' => ['1', '2']], ['users', 'usersdetail', 'usersplumber', 'usersskills', 'company', 'physicaladdress', 'postaladdress', 'billingaddress']);
				$this->plumberregistrationdocument($result);
				$this->session->set_flashdata('success', 'Documents Letters created successfully.');
				redirect('admin/plumber/index/documents/'.$plumberid);
			}else{
				$result 	=  $this->Documentsletters_Model->action($requestData);				
				if($result){
					$this->session->set_flashdata('success', 'Documents Letters '.(($result=='') ? 'created' : 'updated').' successfully.');
					redirect('admin/plumber/index/documents/'.$plumberid);
				}else{
					$this->session->set_flashdata('error', 'Try Later.');
				}
			}
		}
		
		$userdata1	= $this->Plumber_Model->getList('row', ['id' => $plumberid], ['usersdetail', 'usersplumber']);
		$pagedata['user_details'] 	= $userdata1;
		$pagedata['roletype']		= $this->config->item('roleadmin');
		$pagedata['notification'] 	= $this->getNotification();
		$pagedata['plumberid'] 		= $plumberid;
		$pagedata['menu']			= $this->load->view('common/plumber/menu', ['id'=>$plumberid],true);
		$data['plugins'] = ['datatables', 'datatablesresponsive', 'sweetalert', 'validation','inputmask'];
		$data['content'] = $this->load->view('admin/plumber/documents', (isset($pagedata) ? $pagedata : ''), true);
		$this->layout2($data);
	}

	public function DTDocuments()
	{
		
		$post 		= $this->input->post();			
		$totalcount =  $this->Documentsletters_Model->getList('count',$post);
		$results 	=  $this->Documentsletters_Model->getList('all',$post);
		$totalrecord 	= [];
		if(count($results) > 0){
			foreach($results as $result){
				
				$timestamp = strtotime($result['created_at']);
				$newDate = date('d-F-Y H:i:s', $timestamp);	
				$filename = isset($result['file']) ? $result['file'] : '';
				
				$filepath	= base_url().'assets/uploads/plumber/'.$post['plumberid'].'/';
				$pdfimg 	= base_url().'assets/images/pdf.png';
				$docimg 	= base_url().'assets/images/docx.png';
				$file 		= '';
				
				if($filename!=''){
					$explodefile 	= explode('.', $filename);
					$extfile 		= array_pop($explodefile);
					$imgpath 		= (in_array($extfile, ['pdf', 'tiff'])) ? $pdfimg : ((in_array($extfile, ['docx'])) ? $docimg : $filepath.$filename);
					$file = '<div class="col-md-6"><a href="' .$filepath.$filename.'" target="_blank"><img src="'.$imgpath.'" width="100"></div></a>';
				}
				
				$action = '<div class="table-action"><a href="' . base_url() . 'admin/plumber/index/documents/'.$result['user_id'].'/' . $result['id'] . '" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil-alt"></i></a><a href="'.base_url().'admin/plumber/index/Deletefunc/'.$result['user_id'].'/' . $result['id'] .'" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash" style="color:red;"></i></a><a href="' .base_url().'assets/uploads/plumber/'.$result['file'].'" download><i class="fa fa-download" style="color:blue;"></i></a></div>';

				$totalrecord[] = 	[	
										'description'=> 	$result['description'],	
										'datetime' 	 => 	$newDate,
										'file' 	 	 => 	$file,
										'action' 	 => 	$action,
										
									];
			}
		}
		
		$json = array(			  
			"recordsTotal"    => intval($totalcount),  
			"recordsFiltered" => intval($totalcount),
			"data"            => $totalrecord
		);

		echo json_encode($json);
	}

	public function Deletefunc($plumberid,$documentsid='')
	{		
		
		$result = $this->Documentsletters_Model->deleteid($documentsid);
		if($result == '1'){
			// $url = FCPATH."assets/uploads/plumber/".$documentsid.".pdf";
			// unlink($url);
			$this->session->set_flashdata('success', 'Record was Deleted');
		}
		else{
			$this->session->set_flashdata('error', 'Error to delete the Record.');		
		}

		$this->index();
		redirect('admin/plumber/index/documents/'.$plumberid);
	}

	public function diary($id='')
	{
		////////////////////// $plumberid,$documentsid=''
		if($id!=''){
			$result = $this->Plumber_Model->getList('row', ['id' => $id, 'type' => '3', 'status' => ['1', '2']], ['usersdetail']);
			
			$pagedata['result'] 		= $result;

			$DBcomments = $this->Comment_Model->getList('all', ['user_id' => $id, 'type' => '3', 'status' => ['1', '2']]);
			if($DBcomments){
				$pagedata['comments']		= $DBcomments;
			}else{
				// $this->session->set_flashdata('error', 'No comments Found.');
				//redirect('admin/plumber/index'); 
			}
		}

		if($this->input->post()){
			$requestData 	= 	$this->input->post();
			$data = $this->Plumber_Model->plumberdiary($requestData);
			if($data) $message = 'Comment added successfully.';

			if(isset($data)) $this->session->set_flashdata('success', $message);
			else $this->session->set_flashdata('error', 'Try Later.');

			redirect('admin/plumber/index/diary/'.$requestData['user_id'].''); 

		}


		$pagedata['diarylist'] = $this->diaryactivity(['plumberid'=>$id]);		

		$pagedata['user_id']		= $id;
		$pagedata['user_role']		= $this->config->item('roletype');
		$pagedata['notification'] 	= $this->getNotification();
		$pagedata['roletype']		= $this->config->item('roleadmin');
		$pagedata['menu']				= $this->load->view('common/plumber/menu', ['id'=>$id],true);
		$data['plugins']			= ['datatables', 'datatablesresponsive', 'sweetalert', 'datepicker'];
		$data['content'] 			= $this->load->view('admin/plumber/diary', (isset($pagedata) ? $pagedata : ''), true);
		
		$this->layout2($data);		
	}

	public function exportcard(){
		$post 						= $this->input->post();
		$id 						= $post['id'];
		$data['company'] 			= $this->getCompanyList();
		$data['designation2'] 		= $this->config->item('designation3');
		$data['specialisations'] 	= $this->config->item('specialisations');
		$data['settings'] 			= $this->Systemsettings_Model->getList('row');
		$save 						= FCPATH.'assets/uploads/temp/Card Export '.$id.'.pdf';
			
		
		$data['result'] = $this->Plumber_Model->getList('row', ['id' => $id], ['users', 'usersdetail', 'usersplumber', 'company']);
		
		$notificationdata = 'Card export for plumber '.$data['result']['registration_no'];
		$body = '';
		$html = $this->load->view('common/card_export', (isset($data) ? $data : ''), true);
		
		$this->pdf->loadHtml($html);
		$this->pdf->setPaper('A3', 'portrait');
		$this->pdf->render();
		$output = $this->pdf->output();
		file_put_contents($save, $output);
		$this->CC_Model->sentMail($data['settings']['export_email'], $notificationdata, $body, $save);
		if(file_exists($save)) unlink($save);
		echo "1";
	}
}

