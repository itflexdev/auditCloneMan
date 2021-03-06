<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CC_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('CC_Model');
		$this->load->model('Plumber_Model');
		$this->load->model('Managearea_Model');
		$this->load->model('Subtype_Model');
		$this->load->model('Noncompliancelisting_Model');
		$this->load->model('Noncompliance_Model');
		$this->load->model('Coc_Ordermodel');
		$this->load->model('Reportlisting_Model');
		$this->load->model('Plumberperformance_Model');
		$this->load->model('Auditor_Reportlisting_Model');
		$this->load->model('Chat_Model');
		$this->load->model('Auditor_Model');
		$this->load->model('Systemsettings_Model');
	}
	
	public function ajaxfileupload()
	{
		$post 			= $this->input->post();
		$path			= strval($post['path']);
		$type			= strval($post['type']);
		
		$result 		= $this->CC_Model->fileUpload('file', $path, $type);
		echo json_encode($result);
	}
	
	public function ajaxsubtype()
	{
		$post = $this->input->post();
		$result = $this->Subtype_Model->getList('all', ['status' => ['1']]+$post);

		if(count($result)){
			$json = ['status' => '1', 'result' => $result];
		}else{
			$json = ['status' => '0', 'result' => []];
		}

		echo json_encode($json);
	}

	public function ajaxreportlisting()
	{
		$post = $this->input->post();
		$result = $this->Reportlisting_Model->getList('all', ['status' => ['1']]+$post);

		if(count($result)){
			$json = ['status' => '1', 'result' => $result];
		}else{
			$json = ['status' => '0', 'result' => []];
		}

		echo json_encode($json);
	}

	public function ajaxcity()
	{
		$post 				= $this->input->post(); 
		$post['orderby'] 	= "c.name asc";
		$result = $this->Managearea_Model->getListCity('all', $post);

		if(count($result)){
			$json = ['status' => '1', 'result' => $result];
		}else{
			$json = ['status' => '0', 'result' => []];
		}

		echo json_encode($json);
	}

	public function ajaxcityaction()
	{
		$post 		= $this->input->post();
		$checkname 	= $this->Managearea_Model->citynamevalidation(['name' => $post['city1']]);
		
		if($checkname=='0'){
			$result 	= $this->Managearea_Model->action($post);

			if($result){
				$resultdata = $this->Managearea_Model->getListCity('row', ['id' => $result]);
				$json 	= ['status' => '1', 'result' => $resultdata];
			}else{
				$json 	= ['status' => '0', 'result' => []];
			}
		}else{
			$json 	= ['status' => '2', 'result' => []];
		}
		
		echo json_encode($json);
	}

	public function ajaxsuburb()
	{
		$post = $this->input->post();  
		$post['orderby'] = "name asc";
		$result = $this->Managearea_Model->getListSuburb('all', $post);
		
		if(count($result)){
			$json = ['status' => '1', 'result' => $result];
		}else{
			$json = ['status' => '0', 'result' => []];
		}
		
		echo json_encode($json);
	}
	
	public function ajaxsuburbaction()
	{
		$post 	= $this->input->post();
		$checkname 	= $this->Managearea_Model->suburbnamevalidation(['name' => $post['suburb']]);
		
		if($checkname=='0'){
			$result = $this->Managearea_Model->action($post);

			if($result){
				$resultdata = $this->Managearea_Model->getListSuburb('row', ['id' => $result]);
				$json 	= ['status' => '1', 'result' => $resultdata];
			}else{
				$json 	= ['status' => '0', 'result' => []];
			}
		}else{
			$json 	= ['status' => '2', 'result' => []];
		}
		
		echo json_encode($json);
	}

	public function ajaxskillaction()
	{
		$post 				= $this->input->post();
		
		if(isset($post['action']) && $post['action']=='delete'){
			$result = $this->Plumber_Model->deleteSkillList($post['skillid']);
		}else{
			if(isset($post['action']) && $post['action']=='edit'){
				$result['skillid'] = $post['skillid'];
			}else{
				$result = $this->Plumber_Model->action($post);
			}
			
			$result = $this->Plumber_Model->getSkillList('row', ['id' => $result['skillid']]);
		}
		
		if($result){
			$json = ['status' => '1', 'result' => $result];
		}else{
			$json = ['status' => '0'];
		}
		
		echo json_encode($json);
	}
	
	public function ajaxnoncompliancelisting()
	{
		$post 	= $this->input->post();
		$result = $this->Noncompliancelisting_Model->getList('row', $post+['status' => ['1']]);
		
		if($result){
			$json = ['status' => '1', 'result' => $result];
		}else{
			$json = ['status' => '0'];
		}
		
		echo json_encode($json);
	}
	
	public function ajaxnoncomplianceaction()
	{
		$post 				= $this->input->post();
		
		if(isset($post['action']) && $post['action']=='delete'){
			$result = $this->Noncompliance_Model->delete($post['id']);
		}else{
			if(isset($post['action']) && $post['action']=='edit'){
				$result = $post['id'];
			}else{
				$result = $this->Noncompliance_Model->action($post);
			}
			
			$result = $this->Noncompliance_Model->getList('row', ['id' => $result]);
		}
		
		if($result){
			$json = ['status' => '1', 'result' => $result];
		}else{
			$json = ['status' => '0'];
		}
		
		echo json_encode($json);
	}
	
	/*public function ajaxreviewaction()
	{
		$post 				= $this->input->post();
		
		if(isset($post['action']) && $post['action']=='delete'){
			$result = $this->Auditor_Model->deleteReview($post['id']);
		}else{
			if(isset($post['action']) && $post['action']=='edit'){
				$result = $post['id'];
			}else{
				$result = $this->Auditor_Model->actionReview($post);
			}
			
			$result = $this->Auditor_Model->getReviewList('row', ['id' => $result]);
		}
		
		if($result){
			$json = ['status' => '1', 'result' => $result];
		}else{
			$json = ['status' => '0'];
		}
		
		echo json_encode($json);
	}*/

	public function ajaxreviewaction()
	{
		$post 				= $this->input->post();
		$created_by 		= $this->getuserID();
		$datetime 			=  date('Y-m-d H:i:s');
		// print_r($post);die;
		if(isset($post['action']) && $post['action']=='delete'){

			if (isset($post['roletype']) && $post['roletype'] =='1') {
				$review_data = $this->Auditor_Model->getReviewList('row', ['id' => $post['id']]);
				if (isset($post['image3']) && $post['image3'] !=''){
					$imagedata = '- <a href='.base_url().'/assets/uploads/auditor/statement/'.$post['image3'].''.' target="_blank">'.'Reason file link'.'</a>';
				}else{
					$imagedata = '';
				}

				$message = 'Review '.$review_data['statementname'].' '.$this->config->item('reviewtype')[$review_data['reviewtype']].' removed - '.$post['reasontext'].' '.$imagedata.'';

				$commentdata = [
					'auditor_id' 	=> $review_data['auditor_id'],
					'coc_id' 		=> $review_data['coc_id'],
					'plumber_id' 	=> $review_data['plumber_id'],
					'admin_id' 		=> $created_by,
					'message' 		=> $message,
					'type' 			=> '1',
					'action' 		=> '1',
					'datetime' 		=> $datetime,
					];
					$this->db->insert('diary',$commentdata);
			}
			
			$result = $this->Auditor_Model->deleteReview($post['id']);

		}else{
			if(isset($post['action']) && $post['action']=='edit'){
				$result = $post['id'];

				/*if ((isset($post['roletype']) && $post['roletype'] =='1') && (isset($post['reviewreason']) && isset($post['image2']))) {
					$AddedreviewData = $this->Auditor_Model->getReviewList('row', ['id' => $result]);
					if (isset($post['image2']) && $post['image2'] !=''){
						$imagedata = '- <a href='.base_url().'/assets/uploads/auditor/statement/'.$post['image2'].''.' target="_blank">'.'Reason file link'.'</a>';
					}else{
						$imagedata = '';
					}

					$message = 'Review '.$AddedreviewData['statementname'].' '.$this->config->item('reviewtype')[$AddedreviewData['reviewtype']].' edited - '.$post['reviewreason'].' '.$imagedata.'';


							$commentdata = [
							'auditor_id' 	=> $AddedreviewData['auditor_id'],
							'coc_id' 		=> $AddedreviewData['coc_id'],
							'plumber_id' 	=> $AddedreviewData['plumber_id'],
							'admin_id' 		=> $created_by,
							'message' 		=> $message,
							'type' 			=> '1',
							'action' 		=> '1',
							'datetime' 		=> $datetime,
							];
							print_r($commentdata);die;
							$this->db->insert('diary',$commentdata);
				}*/
			}else{
				// print_r($post);die;
				$result = $this->Auditor_Model->actionReview($post);

				// if (isset($post['roletype']) && $post['roletype'] =='1' && $post['rqst_type'] !='change_status' && $post['id'] =='') {
				if ((isset($post['hiddenroletype']) && $post['hiddenroletype'] =='1') && $post['id'] =='') {
					$AddedreviewData = $this->Auditor_Model->getReviewList('row', ['id' => $result]);
					if (isset($post['image2']) && $post['image2'] !=''){
						$imagedata = '- <a href='.base_url().'/assets/uploads/auditor/statement/'.$post['image2'].''.' target="_blank">'.'Reason file link'.'</a>';
					}else{
						$imagedata = '';
					}

					$message = 'Review '.$AddedreviewData['statementname'].' '.$this->config->item('reviewtype')[$AddedreviewData['reviewtype']].' added - '.$post['reviewreason'].' '.$imagedata.'';


							$commentdata = [
							'auditor_id' 	=> $post['auditorid'],
							'coc_id' 		=> $post['cocid'],
							'plumber_id' 	=> $post['plumberid'],
							'admin_id' 		=> $created_by,
							'message' 		=> $message,
							'type' 			=> '1',
							'action' 		=> '1',
							'datetime' 		=> $datetime,
							];
							$this->db->insert('diary',$commentdata);
				}elseif(isset($post['roletype']) && $post['roletype'] =='1' && $post['rqst_type'] =='change_status' && $post['id'] !=''){
					if ($post['status'] =='1') {
						$update_status = "Completed";
					}else{
						$update_status = "Incomplete";
					}

					$AddedreviewData = $this->Auditor_Model->getReviewList('row', ['id' => $post['id']]);
					if (isset($post['image2']) && $post['image2'] !=''){
						$imagedata = '- <a href='.base_url().'/assets/uploads/auditor/statement/'.$post['image2'].''.' target="_blank">'.'Reason file link'.'</a>';
					}else{
						$imagedata = '';
					}

					$message = 'Review '.$AddedreviewData['statementname'].' '.$this->config->item('reviewtype')[$AddedreviewData['reviewtype']].' updated to '.$update_status.' - '.$post['reviewreason'].' '.$imagedata.'';


							$commentdata = [
							'auditor_id' 	=> $post['auditorid'],
							'coc_id' 		=> $post['cocid'],
							'plumber_id' 	=> $post['plumberid'],
							'admin_id' 		=> $created_by,
							'message' 		=> $message,
							'type' 			=> '1',
							'action' 		=> '1',
							'datetime' 		=> $datetime,
							];
							$this->db->insert('diary',$commentdata);
				}elseif((isset($post['hiddenroletype']) && isset($post['id'])) && ($post['hiddenroletype'] =='1' && $post['id'] !='')){

					$AddedreviewData = $this->Auditor_Model->getReviewList('row', ['id' => $post['id']]);
					if (isset($post['image2']) && $post['image2'] !=''){
						$imagedata = '- <a href='.base_url().'/assets/uploads/auditor/statement/'.$post['image2'].''.' target="_blank">'.'Reason file link'.'</a>';
					}else{
						$imagedata = '';
					}

					$message = 'Review '.$AddedreviewData['statementname'].' '.$this->config->item('reviewtype')[$AddedreviewData['reviewtype']].' edited - '.$post['reviewreason'].' '.$imagedata.'';


							$commentdata = [
							'auditor_id' 	=> $AddedreviewData['auditor_id'],
							'coc_id' 		=> $AddedreviewData['coc_id'],
							'plumber_id' 	=> $AddedreviewData['plumber_id'],
							'admin_id' 		=> $created_by,
							'message' 		=> $message,
							'type' 			=> '1',
							'action' 		=> '1',
							'datetime' 		=> $datetime,
							];
							$this->db->insert('diary',$commentdata);
				}
			}
			
			$result = $this->Auditor_Model->getReviewList('row', ['id' => $result]);
		}
		
		if($result){
			$json = ['status' => '1', 'result' => $result];
		}else{
			$json = ['status' => '0'];
		}
		
		echo json_encode($json);
	}
	
	public function ajaxreviewrating()
	{
		$post 	= $this->input->post();
		$result = $this->Auditor_Model->getReviewRating('row', $post);
		
		if($result){
			$json = ['status' => '1', 'result' => $result];
		}else{
			$json = ['status' => '0'];
		}
		
		echo json_encode($json);
	}
	
	public function ajaxauditorreportinglist()
	{
		$post = $this->input->post();  
		$data = $this->Auditor_Reportlisting_Model->getList('row', ['id' => $post['id'], 'status' => ['1']]);
		
		if($data){
			$json = ['status' => '1', 'result' => $data];
		}else{
			$json = ['status' => '0', 'result' => []];
		}
		
		echo json_encode($json);
	}
	
	public function ajaxplumberperformancelist()
	{
		$post = $this->input->post();  
		$data = $this->Plumberperformance_Model->getList('row', ['id' => $post['id']]);
		
		if($data){
			$json = ['status' => '1', 'result' => $data];
		}else{
			$json = ['status' => '0', 'result' => []];
		}
		
		echo json_encode($json);
	}
	
	public function ajaxuserautocomplete()
	{ 
		$post = $this->input->post();

		if($post['type']== 3){
			$data 	=   $this->Coc_Ordermodel->autosearchPlumber($post);
		}else if($post['type']== 6){
			$data 	=   $this->Coc_Ordermodel->autosearchReseller($post);
		}else if($post['type']== 5){
			$data 	=   $this->Coc_Ordermodel->autosearchAuditor($post);
		}else if ($post['type'] == 4) {
		    $data   =   $this->Coc_Ordermodel->autosearchCompany($post);
		}

		
		echo json_encode($data);
	}
	
	
	public function ajaxchat()
	{
		$post = $this->input->post();  
		$result = $this->Chat_Model->getList('all', $post);
		
		if(count($result)){
			$json = ['status' => '1', 'result' => $result];
		}else{
			$json = ['status' => '0', 'result' => []];
		}
		
		echo json_encode($json);
	}
	
	public function ajaxchatviewed()
	{
		$post = $this->input->post();  
		$result = $this->Chat_Model->getList('count', $post);
		
		if($result!=0){
			$json = ['status' => '1', 'result' => $result];
		}else{
			$json = ['status' => '0', 'result' => []];
		}
		
		echo json_encode($json);
	}
	
	public function ajaxchataction()
	{
		$post 	= $this->input->post();
		$result = $this->Chat_Model->action($post);

		if($result){
			$json 	= ['status' => '1', 'result' => ['id' => $result]];
		}else{
			$json 	= ['status' => '0', 'result' => []];
		}
	
		echo json_encode($json);
	}
	
	public function ajaxdelete()
	{
		$post 	= $this->input->post();
		$result = $this->Chat_Model->delete($post);

		if($result){
			$json 	= ['status' => '1', 'result' => []];
		}else{
			$json 	= ['status' => '0', 'result' => []];
		}
	
		echo json_encode($json);
	}

	public function ajaxdtaudithistory()
	{
		$post 			= $this->input->post();
		$totalcount 	= $this->Auditor_Model->getReviewList('count', $post);
		$results 		= $this->Auditor_Model->getReviewList('all', $post);
		
		$totalrecord 	= [];
		if(count($results) > 0){
			foreach($results as $result){
				$totalrecord[] = 	[
										'date' 				=> 	date('d-m-Y', strtotime($result['created_at'])),
										'auditor' 			=> 	$result['auditorname'],
										'installationtype' 	=> 	$result['installationtypename'],
										'subtype' 			=> 	$result['subtypename'],
										'statementname' 	=> 	$result['statementname'],
										'finding' 			=> 	$this->config->item('reviewtype')[$result['reviewtype']]
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
	
	
	/*public function ajaxotp(){
		$post		= $this->input->post();
		$userdata 	= $this->getUserDetails();
		$userid 	= $userdata['id'];
		$mobile 	= str_replace([' ', '(', ')', '-'], ['', '', '', ''], trim(isset($post['mobile']) ? $post['mobile'] : $userdata['mobile_phone']));
		$otp		= rand (10000, 99999);
		
		$query = $this->db->get_where('otp', ['user_id' => $userid]);
		if ($query->num_rows() == 1) {
			$this->db->update('otp', ['otp' => $otp, 'mobile' => $mobile], ['user_id' => $userid]);
		}else{
			$this->db->insert('otp', ['otp' => $otp, 'mobile' => $mobile, 'user_id' => $userid]);
		}		
		
		$settingsdetail = $this->Systemsettings_Model->getList('row');
		if($settingsdetail && $settingsdetail['otp']=='0'){
			echo $otp;
			$this->sms(['no' => $mobile, 'otpcode' => $otp, 'msg' => 'One Time Password is '.$otp, 'userid' => $userdata['id'], 'email' => $userdata['email'], 'smsenable' => '0']);
		}else{
			$this->sms(['no' => $mobile, 'otpcode' => $otp, 'msg' => 'One Time Password is '.$otp, 'userid' => $userdata['id'], 'email' => $userdata['email'], 'smsenable' => '1']);
			echo '';
		}
	}*/

	public function ajaxotp(){
		$post		= $this->input->post();

		if (isset($post['pagetype']) && $post['pagetype'] =='reseller_allocation') {
			$userid 	= $post['plumberid'];
			$userdata 	= $this->getUserDetails($userid);
		}else{
			$userdata 	= $this->getUserDetails();
			$userid 	= $userdata['id'];
		}
		
		$mobile 	= str_replace([' ', '(', ')', '-'], ['', '', '', ''], trim(isset($post['mobile']) ? $post['mobile'] : $userdata['mobile_phone']));
		$otp		= rand (10000, 99999);
		
		$query = $this->db->get_where('otp', ['user_id' => $userid]);
		if ($query->num_rows() == 1) {
			$this->db->update('otp', ['otp' => $otp, 'mobile' => $mobile], ['user_id' => $userid]);
		}else{
			$this->db->insert('otp', ['otp' => $otp, 'mobile' => $mobile, 'user_id' => $userid]);
		}		
		
		$settingsdetail = $this->Systemsettings_Model->getList('row');
		if($settingsdetail && $settingsdetail['otp']=='0'){
			echo $otp;
			$this->sms(['no' => $mobile, 'otpcode' => $otp, 'msg' => 'One Time Password is '.$otp, 'userid' => $userdata['id'], 'email' => $userdata['email'], 'smsenable' => '0']);
		}else{
			$this->sms(['no' => $mobile, 'otpcode' => $otp, 'msg' => 'One Time Password is '.$otp, 'userid' => $userdata['id'], 'email' => $userdata['email'], 'smsenable' => '1']);
			echo '';
		}
	}

	/*public function ajaxotpverification(){
		$requestdata 	= $this->input->post();
		$userid 		= $this->getUserID();
		
		$result = $this->db->from('otp')->where(['otp' => $requestdata['otp'], 'user_id' => $userid])->get()->row_array();
		
		if ($result) {
			echo '1';
		}else{
			echo '0';
		}
	}*/
	public function ajaxotpverification(){
		$requestdata 	= $this->input->post();
		if (isset($requestdata['pagetype']) && $requestdata['pagetype'] =='reseller_allocation') {
			$userid 	= $requestdata['plumberid'];
		}else{
			$userid 		= $this->getUserID();
		}
			
		$result = $this->db->from('otp')->where(['otp' => $requestdata['otp'], 'user_id' => $userid])->get()->row_array();
		
		if ($result) {
			echo '1';
		}else{
			echo '0';
		}
	}
	
	public function ajaxdtresellers()
	{		
		$post 		= $this->input->post();	
		$totalcount =  $this->Resellers_allocatecoc_Model->getstockList('count',$post);
		$results 	=  $this->Resellers_allocatecoc_Model->getstockList('all',$post);
		$totalrecord 	= [];
		if(count($results) > 0){
			foreach($results as $result){				
				if($result['allocatedby'] > 0){
					$status = "Allocated";
					$name = $result['name']." ".$result['surname'];
					$timestamp = strtotime($result['allocation_date']);
					$newDate = date('d-F-Y H:i:s', $timestamp);
				}
				else{
					$status = "In stock";
					$name = "";
					$newDate = "";
				}

				

				$stockcount = 0;				
				$totalrecord[] = 	[										
										'cocno' 		=> 	$result['id'],
										'status' 		=> 	$status,										
										'datetime' 		=> 	$newDate,
										'invoiceno' 	=> 	$result['invoiceno'],
										'name' 			=> 	$name,
										'registration_no'=> $result['registration_no'],
										'company'=> $result['company'],
										
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
	
	
	public function ajaxauditorinvoicevalidation()
	{
		$post 			= $this->input->post();		
		$result 		= $this->Auditor_Model->auditorinvoicevalidation($post);
		echo $result;
	}
	
	public function ajaxqualificationvalidation()
	{
		$post 			= $this->input->post();		
		if(in_array($post['designation'], ['4','5','6']) && (!isset($post['roletype']) || (isset($post['roletype']) && $post['roletype']!='3'))){
			$result	= $this->Plumber_Model->qualificationvalidation($post);
			
			if($result) $result = "true";
			else $result = "false";
		}else{
			$result = "true";
		}
		echo $result;
	}
	
	public function ajaxplumberidentitynumber()
	{
		$post 	= $this->input->post();		
		$result	= $this->Plumber_Model->plumberidentitynumber($post);
			
		if($result) $result = "false";
		else $result = "true";
	
		echo $result;
	}

	public function ajaxplumberidentitynumberprofile()
	{
		$post 	= $this->input->post();		
		$result	= $this->Plumber_Model->plumberidentitynumberprofile($post);
			
		if($result) $result = "false";
		else $result = "true";
	
		echo $result;
	}
	
}
