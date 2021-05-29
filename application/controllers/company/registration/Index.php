<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CC_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('CC_Model');
		$this->load->model('Company_Model');
		$this->load->model('Communication_Model');
		$this->load->model('Systemsettings_Model');
	}
	
	public function index()
	{
		$userid		= 	$this->getUserID();
		$result		= 	$this->Company_Model->getList('row', ['id' => $userid, 'type' => '4', 'status' => ['0','1']], ['users', 'usersdetail', 'userscompany', 'physicaladdress', 'postaladdress', 'billingaddress']);
		$settings 	= $this->Systemsettings_Model->getList('row');
		//die;
		
		if(!$result){
			redirect('admin/company/index');
		}
		
		if($result['formstatus']=='1'){
			redirect('company/profile/index'); 
		}
		
		if($this->input->post()){
			$requestData 				= 	$this->input->post();
			// if (isset($requestData['save1'])) {
			// 	$requestData['formstatus'] 	= 	'0';
			// 	$mark ="Application saved.";
			// }else{
			// 	$requestData['formstatus'] 	= 	'1';
			// 	$mark ="Thanks for submitting the application.";
			// }

			// $requestData['user_id']	 	= 	$userid;
			
			// $requestData['status'] 		= 	'1';
			// $data 						=  	$this->Company_Model->action($requestData);

			if (isset($requestData['completeapplication']) && $requestData['completeapplication'] =='submit') {
				$request1['formstatus'] 	= 	'1';
				$users = $this->db->update('users', $request1, ['id' => $userid]);
				$this->CC_Model->diaryactivity(['companyid' => $userid, 'action' => '1', 'type' => '3']);
				redirect('company/profile/index');
			}

			
			// if(isset($data)){
			// 	// $this->CC_Model->diaryactivity(['companyid' => $userid, 'action' => '1', 'type' => '3']);
			// 	$this->session->set_flashdata('success', $mark);
			// }else{
			// 	$this->session->set_flashdata('error', 'Try Later.');
			// }
			// if ($mark =="Application saved.") {
			// 	redirect('company/registration/index');
			// }else{
			// 	$this->CC_Model->diaryactivity(['companyid' => $userid, 'action' => '1', 'type' => '3']);
			// 	redirect('company/profile/index'); 
			// }			
		}
		
	
		$pagedata['notification'] 		= $this->getNotification();
		$pagedata['province'] 			= $this->getProvinceList();
		$pagedata['worktype'] 			= $this->config->item('worktype');
		$pagedata['worktype1'] 			= $this->config->item('worktype1');
		$pagedata['specialization']		= $this->config->item('specialization');
		$pagedata['pagetype'] 			= 'registration';
		$pagedata['roletype'] 			= $this->config->item('rolecompany');
		$pagedata['result'] 			= $result;
		$pagedata['coclimit']			= $settings['reseller_certificate'];
		$pagedata['declaration'] 		= $this->config->item('companydeclaration');
		$pagedata['registerprocedure'] 	= $this->config->item('companyregisterprocedure');
		$pagedata['acknowledgement'] 	= $this->config->item('companyacknowledgement');
		$pagedata['codeofconduct'] 		= $this->config->item('companycodeofconduct');
		
		// $pagedata['commoncompany'] 		= $this->load->view('common/company/company', (isset($pagedata) ? $pagedata : ''), true);
		$data['plugins']				= ['sweetalert', 'validation', 'datepicker', 'inputmask', 'select2'];
		$data['content'] 				= $this->load->view('company/registration/index', (isset($pagedata) ? $pagedata : ''), true);
		$this->layout2($data);
	}

	public function ajaxregistration()
	{
		$post 				= $this->input->post();
		// echo "<pre>";print_r($post);die;
		$post['user_id'] 	= $this->getUserID();
		$result 			= $this->Company_Model->action($post);
		
		if($result){
			$json = ['status' => '1', 'result' => $result];
		}else{
			$json = ['status' => '0'];
		}
		
		echo json_encode($json);
	}
}
