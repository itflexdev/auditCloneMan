<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CC_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Auditor_Model');
	}
	
	public function index()
	{
		$userid = $this->getUserID();
		$result = $this->Auditor_Model->getList('row', ['id' => $userid, 'status' => ['0','1']]);

		if($result){
			$pagedata['result'] = $result;
		}else{
			$this->session->set_flashdata('error', 'No Record Found.');
			redirect('auditor/profile/index'); 
		}
		
		if($this->input->post()){
			$requestData 	= 	$this->input->post();		
			$id				=	$requestData['id'];		
			$data 			=  	$this->Auditor_Model->profileAction($requestData);	

			if ($requestData['logincredentials'] =='1') {
				$this->CC_Model->diaryactivity([ 'auditorid' => $requestData['id'], 'action' => '16', 'type' => '4']);
			}

			if ($requestData['statusradio'] =='1') {
				if ($requestData['auditstatus'] =='1') {
					$auditaction = '17';
				}elseif($requestData['auditstatus'] =='2'){
					$auditaction = '18';
				}
				$this->CC_Model->diaryactivity([ 'auditorid' => $requestData['id'], 'action' => $auditaction, 'type' => '4']);
			}
			
			$this->CC_Model->diaryactivity([ 'auditorid' => $requestData['id'], 'action' => '19', 'type' => '4']);		

			if(isset($data)) $this->session->set_flashdata('success', 'Records '.(($id=='') ? 'created' : 'updated').' successfully.');
			else $this->session->set_flashdata('error', 'Try Later.');
			
			redirect('auditor/profile/index'); 
		}
		
		$pagedata['notification'] 	= $this->getNotification();
		$pagedata['provincelist'] 	= $this->getProvinceList();	
		$pagedata['userid']			= $userid;
		$pagedata['audit_status'] = $this->config->item('audits_status1');
		$data['plugins']			= ['datatables', 'datatablesresponsive', 'sweetalert', 'validation','datepicker','inputmask','select2'];
		$data['content'] 			= $this->load->view('auditor/profile/index', (isset($pagedata) ? $pagedata : ''), true);
		$this->layout2($data);
	}

	

	
}
