<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'application/libraries/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
		
class CC_Controller extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('CC_Model');
		$this->load->model('Users_Model');
		$this->load->model('Company_Model');
		$this->load->model('Installationtype_Model');
		$this->load->model('Managearea_Model');
		$this->load->model('Qualificationroute_Model');
		$this->load->model('Rates_Model');
		$this->load->model('Comment_Model');
		$this->load->model('Systemsettings_Model');
		$this->load->model('Auditor_Model');
		$this->load->model('Coc_Model');
		$this->load->model('Coc_Ordermodel');
		$this->load->model('Communication_Model');
		$this->load->model('Plumber_Model');
		$this->load->model('Paper_Model');
		$this->load->model('Noncompliance_Model');
		$this->load->model('Auditor_Reportlisting_Model');
		$this->load->model('Global_performance_Model');
		$this->load->model('Auditor_Comment_Model');
		$this->load->model('Diary_Model');
		$this->load->model('Resellers_Model');
		$this->load->model('Resellers_allocatecoc_Model');
		$this->load->model('Plumberperformance_Model');
		$this->load->model('Chat_Model');
		$this->load->model('Documentsletters_Model');

		$this->load->library('pdf');
		$this->load->library('phpqrcode/qrlib');
		
		$currenturl = current_url();
		$paymentarray = [
			base_url().'plumber/purchasecoc/index/paymentnotify',
			base_url().'company/purchasecoc/index/paymentnotify'
		];
		
		$segment1 = $this->uri->segment(1);
		
		if(!in_array($currenturl, $paymentarray) && $segment1!='' && $segment1!='login' && $segment1!='forgotpassword' && $segment1!='authentication' && $segment1!='ajax' && $segment1!='common' && $segment1!='webservice' && $segment1!='errors') $this->middleware();
	}
	
	public function layout1($data=[])
	{
		if(!isset($data['exception'])) $this->middleware('1');
		
		$this->load->view('template/layout1', $data);
	}
	
	public function layout2($data=[])
	{
		if(!isset($data['exception'])) $this->middleware();	
		
		$data['userdata'] 					= $this->getUserDetails();
		$data['permission'] 				= ($data['userdata']['type']=='2') ? $this->getUserPermission() : [];
		$data['performancestatus'] 			= ($data['userdata']['type']=='3') ? $this->userperformancestatus() : '';
		$data['overallperformancestatus'] 	= ($data['userdata']['type']=='3') ? $this->userperformancestatus(['overall' => '1']) : '';
		$data['provinceperformancestatus'] 	= ($data['userdata']['type']=='3') ? $this->userperformancestatus(['province' => $data['userdata']['province']]) : '';
		
		if($data['userdata']['type']=='5'){
			$history = $this->Auditor_Model->getReviewHistoryCount(['auditorid' => $data['userdata']['id']]);
			$data['openaudits'] 	= $history['openaudits'];
			$data['auditorstatus'] 	= $data['userdata']['status'];
		}else{
			$data['openaudits'] 	= ''; 
			$data['auditorstatus'] 	= ''; 
		}
		
		if($data['userdata']['type']=='6'){
			$data['cocstock'] = $this->Coc_Model->getCOCList('count',  ['allocated_id' => $data['userdata']['id']]);
		}else{
			$data['cocstock'] 	= ''; 
		}
		
		$data['sidebar'] 		= $this->load->view('template/sidebar', $data, true);
		$this->load->view('template/layout2', $data);
	}
	
	public function middleware($type='')
	{
		$userDetails = $this->getUserDetails();
		
		if($type=='1'){
			if($userDetails){				
				if($userDetails['type']=='1' || $userDetails['type']=='2'){
					redirect('admin/plumber/index'); 
				}elseif($userDetails['type']=='3'){
					if($userDetails['formstatus']=='1' && $userDetails['approvalstatus']=='1') redirect('plumber/profile/index'); 
					elseif($userDetails['formstatus']=='1' && $userDetails['approvalstatus']=='0') redirect('plumber/profile/index'); 
					else redirect('plumber/registration/index'); 
				}elseif($userDetails['type']=='4'){
					if($userDetails['formstatus']=='1') redirect('company/profile/index'); 
					else redirect('company/registration/index'); 
				}elseif($userDetails['type']=='5'){
					redirect('auditor/profile/index'); 
				}elseif($userDetails['type']=='6'){
					redirect('resellers/profile/index'); 
				}
			}
		}else{
			$segment1 = $this->uri->segment(1);
			if(!$userDetails){
				if($segment1=='admin'){
					redirect(''); 
				}elseif($segment1=='plumber'){
					redirect('login/plumber'); 
				}elseif($segment1=='company'){
					redirect('login/company'); 
				}elseif($segment1=='auditor'){
					redirect('login/auditor'); 
				}elseif($segment1=='resellers'){
					redirect('login/resellers'); 
				}
			}else{			
				if(($userDetails['type']=='1'  || $userDetails['type']=='2') && $segment1!='admin'){
					redirect('admin/plumber/index'); 
				}elseif($userDetails['type']=='3' && $segment1!='plumber'){
					if($userDetails['formstatus']=='1') redirect('plumber/profile/index'); 
					else redirect('plumber/registration/index'); 
				}elseif($userDetails['type']=='4' && $segment1!='company'){
					if($userDetails['formstatus']=='1') redirect('company/profile/index'); 
					else redirect('company/registration/index'); 
				}elseif($userDetails['type']=='5' && $segment1!='auditor'){
					redirect('auditor/profile/index'); 
				}elseif($userDetails['type']=='6' && $segment1!='resellers'){
					redirect('resellers/profile/index'); 
				}
			}
		}
	}
	
	public function getPageStatus($pagestatus='')
	{
		if($pagestatus=='' || $pagestatus=='1'){
			return '1';
		}else{
			return '0';
		}
	}

	public function getAuditorPageStatus($pagestatus='')
	{
		if($pagestatus=='' || $pagestatus=='1'){
			return '1';
		}else{
			return '2';
		}
	}
	
	public function getUserID($id='')
	{
		$userDetails = $this->getUserDetails($id);
		
		if($userDetails){
			return $userDetails['id'];
		}else{
			return '';
		}
	}
	
	public function getUserDetails($id='')
	{
		if($id!=''){
			$userid = $id;
		}elseif($this->session->has_userdata('userid')){
			$userid = $this->session->userdata('userid');
		}
		
		if(isset($userid)){
			$result = $this->Users_Model->getUserDetails('row', ['id' => $userid, 'status' => ['0','1','2']]);
			
			if($result){
				return $result;
			}else{
				return '';
			}
		}else{
			return '';
		}
	}
	
	public function getUserPermission()
	{
		return $this->Users_Model->getUserPermission($this->getUserID());
	}
	
	public function checkUserPermission($pagetype, $permissiontype, $redirect='')
	{
		$userDetails = $this->getUserDetails();
		if($userDetails['type']=='2'){
			$permission = $this->Users_Model->getUserPermission($this->getUserID());
						
			$readpermission 	= explode(',', $permission['readpermission']);
			$writepermission 	= explode(',', $permission['writepermission']);
			
			if($permissiontype=='1'){
				if(!in_array($pagetype, $readpermission) && !in_array($pagetype, $writepermission)){ 
					$this->session->set_flashdata('error', 'Invalid Url');
					redirect('admin/dashboard/index'); 
				}
			}
			
			if($permissiontype=='2'){
				if(!in_array($pagetype, $writepermission)){ 
					if($redirect=='1'){
						$this->session->set_flashdata('error', 'Invalid Url');
						redirect('admin/dashboard/index'); 
					}
					
					return false;
				}else{
					return true;
				}
			}
		}else{
			return true;
		}
	}
	
	public function getNotification()
	{
		return $this->load->view('template/notification', '', true);
	}
	
	function parsestring($text) {
		$text = str_replace("\r\n", "\n", $text);
		$text = str_replace("\r", "\n", $text);

		$text = str_replace("\n", "\\n", $text);
		return $text;
	}

	public function getInstallationTypeList()
	{
		$data = $this->Installationtype_Model->getList('all', ['status' => ['1']]);
		
		if(count($data) > 0) return ['' => 'Select Installation Type']+array_column($data, 'name', 'id');
		else return [];
	}

	public function getProvinceList()
	{
		$data = $this->Managearea_Model->getListProvince('all', ['status' => ['1']]);
		
		if(count($data) > 0) return ['' => 'Select Province']+array_column($data, 'name', 'id');
		else return [];
	}
	
	public function getQualificationRouteList()
	{
		$data = $this->Qualificationroute_Model->getList('all', ['status' => ['1']]);
		
		if(count($data) > 0) return ['' => 'Select Qualification Route']+array_column($data, 'name', 'id');
		else return [];
	}
	
	public function getCompanyList()
	{
		$data = $this->Company_Model->getList('all', ['type' => '4', 'status' => ['1'], 'companystatus' => ['1']], ['users', 'usersdetail']);
		
		if(count($data) > 0) return ['' => 'Select Company']+array_column($data, 'company', 'id');
		else return [];
	}
	
	public function getPlumberPerformanceList()
	{
		$data = $this->Plumberperformance_Model->getList('all', ['status' => ['1']]);
		
		if(count($data) > 0) return array_column($data, 'type', 'id');
		else return [];
	}
	
	public function getRates($id)
	{
		$data = $this->Rates_Model->getList('row', ['id' => $id, 'status' => ['1']]);
		
		if($data) return $data['amount'];
		else return '';
	}
	
	public function getAuditorPoints($id)
	{
		$data = $this->Global_performance_Model->getPointList('row', ['id' => $id]);
		
		if($data) return $data['point'];
		else return '';
	}
	
	public function getWorkmanshipPoint()
	{
		return 	[
			'1' => $this->getAuditorPoints($this->config->item('verypoor')),
			'2' => $this->getAuditorPoints($this->config->item('poor')),
			'3' => $this->getAuditorPoints($this->config->item('good')),
			'4' => $this->getAuditorPoints($this->config->item('excellent'))
		];
	}
	
	public function getPlumberVerificationPoint()
	{
		return 	[
			'1' => $this->getAuditorPoints($this->config->item('plumberverificationyes')),
			'2' => $this->getAuditorPoints($this->config->item('plumberverificationno'))
		];
	}
	
	public function getCocVerificationPoint()
	{
		return 	[
			'1' => $this->getAuditorPoints($this->config->item('cocverificationyes')),
			'2' => $this->getAuditorPoints($this->config->item('cocverificationno'))
		];
	}
	
	public function getRollingAverage()
	{
		return $this->getAuditorPoints($this->config->item('rollingaverage'));
	}
	
	public function getPlumberRates()
	{
		return 	[
			'1' => $this->getRates($this->config->item('learner')),
			'2' => $this->getRates($this->config->item('assistant')),
			'3' => $this->getRates($this->config->item('operator')),
			'4' => $this->getRates($this->config->item('licensed'))
		];
	}
	
	public function getCityList()
	{
		$data = $this->Managearea_Model->getListCity('all', ['status' => ['1']]);

		if(count($data) > 0) return ['' => 'Select City']+array_column($data, 'name', 'id');
		else return [];
	}
	
	public function getAuditorReportingList($userid)
	{
		$requestData = $this->input->post();
		$data = $this->Auditor_Reportlisting_Model->getList('all', ['status' => ['1'], 'user_id' => $userid]);

		if(count($data) > 0) return ['' => 'Select My Report Listings/Favourites']+array_column($data, 'favour_name', 'id');
		else return [];
	}
	
	public function plumbercard($userid)
	{
		$data['company'] 			= $this->getCompanyList();
		$data['designation2'] 		= $this->config->item('designation3');
		$data['specialisations'] 	= $this->config->item('specialisations');
		$data['settings'] 			= $this->Systemsettings_Model->getList('row');
		
		$data['result'] = $this->Plumber_Model->getList('row', ['id' => $userid], ['users', 'usersdetail', 'usersplumber', 'company']);
		return $this->load->view('common/card', $data, true) ;
	}
	
	public function plumberprofile($id, $pagedata=[], $extras=[])
	{
		$result = $this->Plumber_Model->getList('row', ['id' => $id, 'type' => '3', 'status' => ['1', '2']], ['users', 'usersdetail', 'usersplumber', 'usersskills', 'company', 'physicaladdress', 'postaladdress', 'billingaddress']);
		if(!$result){
			redirect($extras['redirect']); 
		}
		
		if($this->input->post()){
			$requestData 					= 	$this->input->post();
			$requestData['user_id'] 		= 	$id;
			$requestData['commonaction'] 	= 	'1';
			$userdata 						= 	$this->getUserDetails($id);

			if(isset($requestData['coc_purchase_limit'])){
				$currentcoclimit	= $result['coc_purchase_limit'];
				$coclimit 			= $requestData['coc_purchase_limit'];						
				$userpaperstock 	= $this->Paper_Model->getList('count', ['nococstatus' => '2', 'userid' => $id]); 				
				$orderquantity 		= $this->Coc_Ordermodel->getCocorderList('all', ['admin_status' => '0', 'userid' => $id]);
				$userorderstock 	= array_sum(array_column($orderquantity, 'quantity'));
				$plumberstock		= ($userpaperstock + $userorderstock);
				
				if($coclimit < $plumberstock){
					$this->session->set_flashdata('error', 'Plumber already has '.$userpaperstock.' coc without logged and '.$userorderstock.' coc waiting for approval.');
					
					redirect($extras['redirect']); 
				}else{
					$stockcount = $coclimit - $plumberstock;
				}
				
				$this->Coc_Model->actionCocCount(['count' => $stockcount, 'user_id' => $id]);	
			}
			
			$plumberdata 	=  $this->Plumber_Model->action($requestData);
			if (isset($pagedata['pagetype']) && $pagedata['pagetype'] =='applications' && isset($plumberdata['registration_no']) && $requestData['lmsregistration'] =='1') {
				
				$curlData['firstname'] 	= $requestData['name'];
				$curlData['surname'] 	= $requestData['surname'];
				// $curlData['username'] 	= $plumberdata['registration_no'];
				$curlData['password'] 	= $userdata['password_raw'];
				$curlData['email'] 		= $userdata['email'];
				$curlData['nickname'] 	= $plumberdata['registration_no'];
				$curlData['userid'] 	= $id;
				$this->lmscurl($curlData);
			}elseif(isset($pagedata['pagetype']) && $pagedata['pagetype'] =='applications' && isset($plumberdata['registration_no']) && $requestData['lmsregistration'] =='2'){
				$curlData['userid'] 	= $id;
				$curlData['lms_status'] = '0';
				$this->lmscurlaction($curlData);
			}
				
			if(isset($requestData['submit']) && $requestData['submit']=='approvalsubmit'){
				$commentdata 	=  $this->Comment_Model->action($requestData);				
			}
			
			if($plumberdata || (isset($commentdata) && $commentdata)){
				$data		= '1';
				$message 	= 'Plumber '.(($id=='') ? 'created' : 'updated').' successfully.';
			}
			
			if(isset($data)){
				$settingsdetail = $this->Systemsettings_Model->getList('row');
				
				if(isset($requestData['submit']) && $requestData['submit']=='approvalsubmit'){
					if(isset($requestData['approval_status'])){
						$diaryparam = ($extras['roletype']=='1') ? ['adminid' => $this->getUserID(), 'type' => '1'] : [];
						if($requestData['approval_status']=='1'){
							//$this->plumberregistrationdocument($result);
							$this->CC_Model->diaryactivity(['plumberid' => $id, 'action' => '2']+$diaryparam);
							
							$notificationdata 	= $this->Communication_Model->getList('row', ['id' => '5', 'emailstatus' => '1']);
				
							if($notificationdata){
								$body 	= str_replace(['{Plumbers Name and Surname}', '{email}'], [$result['name'].' '.$result['surname'], $result['email']], $notificationdata['email_body']);
								$this->CC_Model->sentMail($result['email'], $notificationdata['subject'], $body);
							}
							
							if($settingsdetail && $settingsdetail['otp']=='1'){
								$smsdata 	= $this->Communication_Model->getList('row', ['id' => '5', 'smsstatus' => '1']);
					
								if($smsdata){
									$sms = str_replace(['{primary email}'], [$result['email']], $smsdata['sms_body']);
									$this->sms(['no' => $result['mobile_phone'], 'msg' => $sms]);
								}
							}
						}elseif($requestData['approval_status']=='2'){
							$this->CC_Model->diaryactivity(['plumberid' => $id, 'action' => '3']+$diaryparam);
							
							$notificationdata 	= $this->Communication_Model->getList('row', ['id' => '6', 'emailstatus' => '1']);
				
							if($notificationdata){
								$body 	= str_replace(['{Plumbers Name and Surname}'], [$result['name'].' '.$result['surname']], $notificationdata['email_body']);
								$this->CC_Model->sentMail($result['email'], $notificationdata['subject'], $body);
							}
							
							if($settingsdetail && $settingsdetail['otp']=='1'){
								$smsdata 	= $this->Communication_Model->getList('row', ['id' => '6', 'smsstatus' => '1']);
					
								if($smsdata){
									$sms = $smsdata['sms_body'];
									$this->sms(['no' => $result['mobile_phone'], 'msg' => $sms]);
								}
							}
						}
					}
				}
				
				if(isset($requestData['designation2']) && $requestData['designation2']!=$result['designation']){
					$notificationdata 	= $this->Communication_Model->getList('row', ['id' => '7', 'emailstatus' => '1']);
				
					if($notificationdata){
						$body 	= str_replace(['{Plumbers Name and Surname}'], [$result['name'].' '.$result['surname']], $notificationdata['email_body']);
						$this->CC_Model->sentMail($result['email'], $notificationdata['subject'], $body);
					}
					
					if($settingsdetail && $settingsdetail['otp']=='1'){
						$smsdata 	= $this->Communication_Model->getList('row', ['id' => '7', 'smsstatus' => '1']);
			
						if($smsdata){
							$sms = $smsdata['sms_body'];
							$this->sms(['no' => $result['mobile_phone'], 'msg' => $sms]);
						}
					}	
				}

				if(isset($requestData['plumberstatus']) && $requestData['plumberstatus']!=$result['plumberstatus']){
					$notificationdata 	= $this->Communication_Model->getList('row', ['id' => '24', 'emailstatus' => '1']);
				
					if($notificationdata){
						$body 	= str_replace(['{Plumbers Name and Surname}'], [$result['name'].' '.$result['surname']], $notificationdata['email_body']);
						$this->CC_Model->sentMail($result['email'], $notificationdata['subject'], $body);
					}
					
					if($settingsdetail && $settingsdetail['otp']=='1'){
						$smsdata 	= $this->Communication_Model->getList('row', ['id' => '24', 'smsstatus' => '1']);
			
						if($smsdata){
							$sms = $smsdata['sms_body'];
							$this->sms(['no' => $result['mobile_phone'], 'msg' => $sms]);
						}
					}	
				}
				
				if(isset($requestData['submit']) && $requestData['submit']!='approvalsubmit'){
					$diaryparam = ($extras['roletype']=='1') ? ['adminid' => $this->getUserID(), 'type' => '1'] : ['type' => '2'];
					$this->CC_Model->diaryactivity(['plumberid' => $id, 'action' => '4']+$diaryparam);
				}
				
				$this->session->set_flashdata('success', $message);
			}else{
				$this->session->set_flashdata('error', 'Try Later.');
			}
			
			redirect($extras['redirect']); 
		}
		
		$userid			= 	$result['id'];
		
		$pagedata['notification'] 		= $this->getNotification();
		$pagedata['province'] 			= $this->getProvinceList();
		$pagedata['qualificationroute'] = $this->getQualificationRouteList();
		$pagedata['plumberrates'] 		= $this->getPlumberRates();
		$pagedata['company'] 			= $this->getCompanyList();
		$pagedata['card'] 				= $this->plumbercard($userid);
		
		$pagedata['titlesign'] 			= $this->config->item('titlesign');
		$pagedata['gender'] 			= $this->config->item('gender');
		$pagedata['racial'] 			= $this->config->item('racial');
		$pagedata['yesno'] 				= $this->config->item('yesno');
		$pagedata['othernationality'] 	= $this->config->item('othernationality');
		$pagedata['homelanguage'] 		= $this->config->item('homelanguage');
		$pagedata['disability'] 		= $this->config->item('disability');
		$pagedata['citizen'] 			= $this->config->item('citizen');
		$pagedata['deliverycard'] 		= $this->config->item('deliverycard');
		$pagedata['employmentdetail'] 	= $this->config->item('employmentdetail');
		$pagedata['qualificationtype'] 	= $this->config->item('qualificationtype');
		$pagedata['userid'] 			= $userid;
		$pagedata['result'] 			= $result;
		
		$pagedata['designation2'] 		= $this->config->item('designation2');
		$pagedata['applicationstatus'] 	= $this->config->item('applicationstatus');
		$pagedata['approvalstatus'] 	= $this->config->item('approvalstatus');
		$pagedata['rejectreason'] 		= $this->config->item('rejectreason');
		$pagedata['plumberstatus'] 		= $this->config->item('plumberstatus');
		$pagedata['specialisations'] 	= $this->config->item('specialisations');
		$pagedata['comments'] 			= $this->Comment_Model->getList('all', ['user_id' => $id]);
		$pagedata['defaultsettings'] 	= $this->Systemsettings_Model->getList('row');
		$pagedata['menu']				= $this->load->view('common/plumber/menu', ['id'=>$id],true);
		
		$data['plugins']				= ['validation','datepicker','inputmask','select2'];
		$data['content'] 				= $this->load->view('common/plumber/profile', (isset($pagedata) ? $pagedata : ''), true);
		$this->layout2($data);
	}
	
	public function companyprofile($id, $pagedata=[], $extras=[])
	{
		$result = $this->Company_Model->getList('row', ['id' => $id, 'type' => '4', 'status' => ['0','1', '2']], ['users', 'usersdetail', 'userscompany', 'physicaladdress', 'postaladdress', 'billingaddress']);
		if(!$result){
			redirect($extras['redirect']); 
		}
		
		if($this->input->post()){
			$requestData 			= 	$this->input->post();
			$requestData['user_id'] = 	$id;

			if(isset($requestData['coc_purchase_limit'])){
				$currentcoclimit	= $result['coc_purchase_limit'];
				$coclimit 			= $requestData['coc_purchase_limit'];						
				$userpaperstock 	= $this->Paper_Model->getList('count', ['nococstatus' => '2', 'userid' => $id]); 				
				$orderquantity 		= $this->Coc_Ordermodel->getCocorderList('all', ['admin_status' => '0', 'userid' => $id]);
				$userorderstock 	= array_sum(array_column($orderquantity, 'quantity'));
				$companystock		= ($userpaperstock + $userorderstock);
				
				if($coclimit < $companystock){
					$this->session->set_flashdata('error', 'Company already has '.$userpaperstock.' coc without logged and '.$userorderstock.' coc waiting for approval.');
					
					redirect($extras['redirect']); 
				}else{
					$stockcount = $coclimit - $companystock;
				}
				
				$this->Coc_Model->actionCocCount(['count' => $stockcount, 'user_id' => $id]);	
			}
			
			$companydata 	=  $this->Company_Model->action($requestData);
				
			if(isset($requestData['submit']) && $requestData['submit']=='approvalsubmit'){
				$commentdata 	=  $this->Comment_Model->action($requestData);				
			}
			
			if($companydata || (isset($commentdata) && $commentdata)){
				$data		= '1';
				$message 	= 'Company '.(($id=='') ? 'created' : 'updated').' successfully.';
			}
			
			if(isset($data)){
				$this->session->set_flashdata('success', $message);
			}else{
				$this->session->set_flashdata('error', 'Try Later.');
			}
			
			redirect($extras['redirect']); 
		}
		
		$userid			= 	$result['id'];
		
		$pagedata['notification'] 			= $this->getNotification();
		$pagedata['province'] 				= $this->getProvinceList();
		$pagedata['approvalstatus'] 		= $this->config->item('approvalstatus');
		$pagedata['companyrejectreason'] 	= $this->config->item('companyrejectreason');
		$pagedata['worktype'] 				= $this->config->item('worktype');
		$pagedata['worktype1'] 				= $this->config->item('worktype1');
		$pagedata['documenttype'] 			= $this->config->item('document_type');
		$pagedata['specialization']			= $this->config->item('specialization');
		$pagedata['companystatus']			= $this->config->item('companystatus');

		$pagedata['declaration'] 		= $this->config->item('companydeclaration');
		$pagedata['registerprocedure'] 	= $this->config->item('companyregisterprocedure1');
		$pagedata['acknowledgement'] 	= $this->config->item('companyacknowledgement1');
		$pagedata['codeofconduct'] 		= $this->config->item('companycodeofconduct1');
		
		$pagedata['comments'] 				= $this->Comment_Model->getList('all', ['user_id' => $id]);
		$pagedata['result'] 				= $result;
		$pagedata['menu']					= $this->load->view('common/company/menu', ['id'=>$id],true);
		$pagedata['documentlist']			= $this->Documentsletters_Model->getcompanyList('all', ['user_id' => $id]);
		//
		$data['plugins']					= ['validation','datepicker','inputmask','select2', 'datatables', 'sweetalert'];
		$data['content'] 					= $this->load->view('common/company/company', (isset($pagedata) ? $pagedata : ''), true);
		$this->layout2($data);
	}
	// Company Employee Listing
	public function employee($id=[], $pagedata=[], $extras=[])
	{

		 if (isset($pagedata['pagetype']) && $pagedata['pagetype']=='adminempdetails') {

		 	// comp_id = plumber ID

			$result = $this->Company_Model->getEmpList('employee', ['comp_id' => $id['id'], 'type' => '3', 'status' => ['0','1', '2']]);
			//print_r($result[0]['user_id']);die;
			$pagedata['employee'] = $result;
			$pagedata['specialization']	= $this->config->item('specialisations');

			$pagedata['company'] 		= $this->getCompanyList();
			$pagedata['plumberstatus'] 	= $this->config->item('plumberstatus');
			$userdata1					= $this->Plumber_Model->getList('row', ['id' => $result[0]['user_id']], ['users', 'usersdetail', 'usersplumber']);

			$pagedata['user_details1'] 	= $userdata1;

			
			
			////$pagedata['history']		= $this->Auditor_Model->getReviewHistory2Count(['plumberid' => $result[0]['user_id']]);
			$pagedata['history']		= $this->Auditor_Model->getReviewHistoryCount(['plumberid' => $result[0]['user_id']]);

			$pagedata['history2']		= $this->Auditor_Model->getReviewHistory2Count(['plumberid' => $result[0]['user_id']]);

			$pagedata['logged']			= $this->Coc_Model->getCOCList('count', ['user_id' => $result[0]['user_id'], 'coc_status' => ['2']]);

			$pagedata['allocated']		= $this->Coc_Model->getCOCList('count', ['user_id' => $result[0]['user_id'], 'coc_status' => ['4']]);

			$pagedata['nonlogged']		= $this->Coc_Model->getCOCList('count', ['user_id' => $result[0]['user_id'], 'coc_status' => ['5']]);

			$pagedata['user_details'] 	= $this->Plumber_Model->getList('row', ['id' => $result[0]['user_id']], ['users', 'usersdetail', 'usersplumber']);

			$pagedata['settings_cpd']	= $this->Systemsettings_Model->getList('all',['user_id' => $result[0]['user_id']]);
			

			//$pagedata['loggedcoc']		= $this->Coc_Model->getCOCList('count', ['user_id' => $result[0]['user_id'], 'coc_status' => ['2']]);
		 }
		
		if(isset($result) && !$result){
			redirect($extras['redirect']); 
		}
		
		if (is_array($id)) {
			$companyID =  $id['compid'];
		}else{
			$companyID =  $id;
		}
		
		$pagedata['menu']				= $this->load->view('common/company/menu', ['id'=>$companyID],true);
		$data['plugins']				= ['datatables','validation','datepicker','inputmask','select2', 'echarts'];
		$pagedata['notification'] 		= $this->getNotification();
		$pagedata['designation2']		= $this->config->item('designation2');
		$pagedata['plumberstatus']		= $this->config->item('plumberstatus');
		$pagedata['id'] 				= $companyID;
		$pagedata['menu']				= $this->load->view('common/company/menu', ['id'=>$companyID],true);
		$data['content'] 				= $this->load->view('common/company/employee_listing', (isset($pagedata) ? $pagedata : ''), true);
		$this->layout2($data);
	}
	
	public function companydiary($id='')
	{
		if($id!=''){
			$result = $this->Company_Model->getList('row', ['id' => $id, 'type' => '4', 'status' => ['1', '2']], ['users', 'usersdetail', 'userscompany', 'physicaladdress', 'postaladdress']);
			$pagedata['result'] 		= $result;
			$DBcomments = $this->Comment_Model->getList('all', ['user_id' => $id, 'type' => '4', 'status' => ['1', '2']]);
			if($DBcomments){
				$pagedata['comments']		= $DBcomments;
			}
		}

		if($this->input->post()){
			$requestData 	= 	$this->input->post();
			$data = $this->Company_Model->companydiary($requestData);
			if($data) $message = 'Comment added successfully.';

			if(isset($data)) $this->session->set_flashdata('success', $message);
			else $this->session->set_flashdata('error', 'Try Later.');

			redirect('admin/company/index/diary/'.$requestData['user_id'].''); 

		}


		$pagedata['diarylist'] = $this->diaryactivity(['companyid'=>$id]);		

		$pagedata['user_id']		= $result['id'];
		$pagedata['user_role']		= $this->config->item('roletype');
		$pagedata['notification'] 	= $this->getNotification();
		$pagedata['roletype']		= $this->config->item('roleadmin');
		$pagedata['menu']			= $this->load->view('common/company/menu', ['id'=>$id],true);
		$data['plugins']			= ['datatables', 'datatablesresponsive', 'sweetalert', 'datepicker'];
		$data['content'] 			= $this->load->view('common/company/diary', (isset($pagedata) ? $pagedata : ''), true);
		
		$this->layout2($data);		
	}
	
	public function resellersprofile($id, $pagedata=[], $extras=[])
	{
		if($id!=''){
			$result = $this->Resellers_Model->getList('row', ['id' => $id, 'status' => ['0','1']], ['users', 'usersdetail', 'coccount', 'physicaladdress', 'postaladdress', 'billingaddress']);
			if($result){
				$pagedata['result'] = $result;

			}else{
				$this->session->set_flashdata('error', 'No Record Found.');
				if($extras['redirect']) redirect($extras['redirect']); 
				else redirect('admin/resellers/index'); 
			}
		}
		
		if($this->input->post()){
			$requestData 				= 	$this->input->post();
			$requestData['roletype'] 	= 	$pagedata['roletype'];

			$data 	=  $this->Resellers_Model->action($requestData);
		
			if($data && is_array($data)) $this->session->set_flashdata('success', 'Resellers '.(($id=='') ? 'created' : 'updated').' successfully.');
			elseif(!is_array($data) && $data=='outofstock') $this->session->set_flashdata('error', 'Reseller purchase limit cannot be less than reseller in stock.');
			else $this->session->set_flashdata('error', 'Try Later.');
			
			if($extras['redirect']) redirect($extras['redirect']); 
			else redirect('admin/resellers/index');
		}
		
		$post1['user_id'] = $id;
		$post1['search']['value'] = 'in stock';
		$pagedata['stock_count'] = $this->Resellers_allocatecoc_Model->getstockList('count',$post1);

		$pagedata['adminvalue']   = $extras['adminvalue'];
		$pagedata['notification'] 	= $this->getNotification();
		$pagedata['province'] 		= $this->getProvinceList();
		
		$data['plugins']			= ['datatables', 'datatablesresponsive', 'sweetalert', 'validation','inputmask','select2'];
		$data['content'] 			= $this->load->view('common/resellers', (isset($pagedata) ? $pagedata : ''), true);
		$this->layout2($data);
	}
	
	public function auditorprofile($id,$extras=[])
	{
		if($id!=''){
			$result = $this->Auditor_Model->getList('row', ['id' => $id, 'status' => ['0','1']]);
			if($result){
				$pagedata['result'] = $result;
			}else{
				$this->session->set_flashdata('error', 'No Record Found.');
				if($extras['redirect']) redirect($extras['redirect']); 
				else redirect('admin/audits/index'); 
			}
		}

		if($this->input->post()){
			if(isset($flag)) unset($flag);
			$requestData 	= $this->input->post();	
			$data 			= $this->Auditor_Model->action($requestData);
			
			if ($requestData['logincredentials'] =='1') {
				$this->CC_Model->diaryactivity([ 'auditorid' => $requestData['id'], 'action' => '16', 'type' => '4']);
				$flag = '1';
			}

			if ($requestData['statusradio'] =='1') {
				if ($requestData['auditstatus'] =='1') {
					$auditaction = '17';
				}elseif($requestData['auditstatus'] =='2'){
					$auditaction = '18';
				}
				$this->CC_Model->diaryactivity([ 'auditorid' => $requestData['id'], 'action' => $auditaction, 'type' => '4']);
				$flag = '1';
			}
			if (!isset($flag)) {
				$this->CC_Model->diaryactivity([ 'auditorid' => $requestData['id'], 'action' => '19', 'type' => '4']);
			}
			

			if($data) $this->session->set_flashdata('success', 'Auditor '.(($id=='') ? 'created' : 'updated').' successfully.');
			else $this->session->set_flashdata('error', 'Try Later.');
			
			if($extras['redirect']) redirect($extras['redirect']); 
			else redirect('admin/audits/index');
		}

		
		$pagedata['notification'] = $this->getNotification();
		$pagedata['provincelist'] = $this->getProvinceList();
		$pagedata['audit_status'] = $this->config->item('audits_status1');
		$pagedata['menu']		  = $this->load->view('common/auditor/menu', ['id'=>$id],true);
		$pagedata['roletype']	  = $this->config->item('roleadmin');
		
		$pagedata['history']	  = $this->Auditor_Model->getReviewHistoryCount(['auditorid' => $id]);	
		
		$data['plugins'] = ['datatables', 'datatablesresponsive', 'sweetalert', 'validation','inputmask','echarts','select2'];
		$data['content'] = $this->load->view('common/auditor', (isset($pagedata) ? $pagedata : ''), true);
		$this->layout2($data);
	}
	
	public function coclogaction($id, $pagedata=[], $extras=[])
	{
		$userid							= $extras['userid'];
		$auditorid						= isset($extras['auditorid']) ? ['auditorid' => $extras['auditorid']] : [];
		$result							= $this->Coc_Model->getCOCList('row', ['id' => $id, 'user_id' => $userid]+$auditorid, ['coclog']);
		if(!$result){
			$this->session->set_flashdata('error', 'No Record Found.');
			redirect($extras['redirect']); 
		}
		
		$userdata				 		= $this->Plumber_Model->getList('row', ['id' => $userid], ['users', 'usersdetail', 'usersplumber', 'company']);
		$specialisations 				= explode(',', $userdata['specialisations']);
		
		if($this->input->post()){
			$settingsdetail 					= 	$this->Systemsettings_Model->getList('row');
			$requestData 						= 	$this->input->post();
			$requestData['company_details'] 	= 	$userdata['company_details'];
			
			$data 	=  $this->Coc_Model->actionCocLog($requestData);
			
			$message = '';
			if(isset($requestData['submit'])){
				if($requestData['submit']=='save'){
					$message = 'Thanks for Saving the COC.';
				}elseif($requestData['submit']=='log'){
					$message = 'Thanks for Logging the COC.';
					$this->CC_Model->diaryactivity(['plumberid' => $this->getUserID(), 'cocid' => $requestData['coc_id'], 'action' => '7', 'type' => '2']);
										
					$notificationdata 	= $this->Communication_Model->getList('row', ['id' => '18', 'emailstatus' => '1']);
					
					if($notificationdata){
						$body 		= str_replace(['{Plumbers Name and Surname}', '{number}'], [$userdata['name'].' '.$userdata['surname'], $id], $notificationdata['email_body']);
						$subject 	= str_replace(['{cocno}'], [$id], $notificationdata['subject']);
						$this->CC_Model->sentMail($userdata['email'], $subject, $body);
					}				
					
					if($settingsdetail && $settingsdetail['otp']=='1'){
						$smsdata 	= $this->Communication_Model->getList('row', ['id' => '18', 'smsstatus' => '1']);
			
						if($smsdata){
							$sms = str_replace(['{number of COC}'], [$id], $smsdata['sms_body']);
							$this->sms(['no' => $userdata['mobile_phone'], 'msg' => $sms]);
						}
					}
					
					if(isset($requestData['ncemail']) && $requestData['ncemail']=='1'){
						$notificationdata 	= $this->Communication_Model->getList('row', ['id' => '23', 'emailstatus' => '1']);
						$replacetext = ['', '', '', '', '', '', ''];							
						if(isset($requestData['name'])) 		$replacetext[0] = $requestData['name'];
						if(isset($requestData['address'])) 		$replacetext[1] = $requestData['address'];
						if(isset($requestData['street'])) 		$replacetext[2] = $requestData['street'];
						if(isset($requestData['number'])) 		$replacetext[3] = $requestData['number'];
						if(isset($requestData['province'])){
							$provincename 	= 	$this->Managearea_Model->getListProvince('row', ['id' => $requestData['province']]);
							$replacetext[4] 	=  $provincename['name'];
						} 	
						if(isset($requestData['city'])){
							$cityname 	= 	$this->Managearea_Model->getListCity('row', ['id' => $requestData['city']]);
							$replacetext[5] =  $cityname['name'];
						} 		
						if(isset($requestData['suburb'])){
							$suburbname = 	$this->Managearea_Model->getListSuburb('row', ['id' => $requestData['suburb']]);
							$replacetext[6] =  $suburbname['name'];
						} 	
						
						if(isset($requestData['email']) && $requestData['email']!='' && $notificationdata){
							
							$subject 	= str_replace(['{Customer Name}', '{Complex Name}', '{Street}', '{Number}', '{Suburb}', '{City}', '{Province}'], $replacetext, $notificationdata['subject']);
							$body 		= str_replace(['{Customer Name}', '{Plumber Name}', '{plumbers company name}', '{company contact number}'], [$replacetext[0], $userdata['name'].' '.$userdata['surname'], $userdata['companyname'], $userdata['cwork_phone']], $notificationdata['email_body']);
							
							$pdf 		= FCPATH.'assets/uploads/temp/'.$requestData['coc_id'].'.pdf';
							$this->pdfnoncompliancereport($requestData['coc_id'], $userid, $pdf);
							$this->CC_Model->sentMail($requestData['email'], $subject, $body, $pdf, $userdata['email']);
							if(file_exists($pdf)) unlink($pdf);  
						}				
						
						if(isset($requestData['contact_no']) && $requestData['contact_no']!='' && $settingsdetail && $settingsdetail['otp']=='1'){
							$smsdata 	= $this->Communication_Model->getList('row', ['id' => '23', 'smsstatus' => '1']);
				
							if($smsdata){
								$sms = str_replace(['{Customer Name}', '{Complex Name}', '{Street}', '{Number}', '{Suburb}', '{City}', '{Province}'], $replacetext, $smsdata['sms_body']);
								$this->sms(['no' => $requestData['contact_no'], 'msg' => $sms]);
							}
						}
					}
				}
			}
			
			if($data) $this->session->set_flashdata('success', $message);
			else $this->session->set_flashdata('error', 'Try Later.');
		
			redirect($extras['redirect']); 
		}
		
		$pagedata['userdata'] 			= $userdata;
		$pagedata['cocid'] 				= $id;
		$pagedata['notification'] 		= $this->getNotification();
		$pagedata['province'] 			= $this->getProvinceList();
		$pagedata['designation2'] 		= $this->config->item('designation2');
		$pagedata['ncnotice'] 			= $this->config->item('ncnotice');
		$pagedata['installationtype']	= $this->getInstallationTypeList();
		$pagedata['installation'] 		= $this->Installationtype_Model->getList('all', ['designation' => $userdata['designation'], 'specialisations' => [], 'ids' => range(1,8)]);
		$pagedata['specialisations']	= $this->Installationtype_Model->getList('all', ['designation' => $userdata['designation'], 'specialisations' => $specialisations, 'ids' => range(1,8)]);
		$pagedata['result']				= $result;
		
		$noncompliance					= $this->Noncompliance_Model->getList('all', ['coc_id' => $id, 'user_id' => $userid]);		
		$pagedata['noncompliance']		= [];
		foreach($noncompliance as $compliance){
			$pagedata['noncompliance'][] = [
				'id' 		=> $compliance['id'],
				'details' 	=> $this->parsestring($compliance['details']),
				'file' 		=> $compliance['file']
			];
		}
		
		$data['plugins']				= ['datatables', 'datatablesresponsive', 'sweetalert', 'validation', 'datepicker', 'inputmask', 'select2'];
		$data['content'] 				= $this->load->view('common/logcocstatement', (isset($pagedata) ? $pagedata : ''), true);
		
		$this->layout2($data);
	}
	
	/*public function getauditreview($id, $pagedata=[], $extras=[])
	{		
		if(isset($extras['notification'])){
			$this->db->update('stock_management', ['notification' => '0'], ['id' => $id]);
		}
		
		$extraparam = [];
		if(isset($extras['auditorid'])) $extraparam['auditorid'] 	= $extras['auditorid'];
		if(isset($extras['plumberid'])) $extraparam['user_id'] 		= $extras['plumberid'];	
		$extraparam['page'] = 'review';	
		
		$result	= $this->Coc_Model->getCOCList('row', ['id' => $id, 'coc_status' => ['2']]+$extraparam, ['coclog', 'users', 'usersdetail', 'usersplumber', 'auditordetails', 'auditorstatement']);	
		if(!$result){
			$this->session->set_flashdata('error', 'No Record Found.');
			redirect($extras['redirect']); 
		}
		
		$pagedata['settings'] 		= $this->Systemsettings_Model->getList('row');
		$pagedata['result']			= $result;
		
		if($this->input->post()){
			$datetime 		=  date('Y-m-d H:i:s');
			$requestData 	=  $this->input->post();
			$data 			=  $this->Auditor_Model->actionStatement($requestData);
			$settingsdetail =  $this->Systemsettings_Model->getList('row');
						
			if($data){
				if($requestData['submit']=='save' && isset($requestData['hold'])){
					$this->db->update('stock_management', ['audit_status' => '5', 'notification' => '1'], ['id' => $pagedata['result']['id']]);
					$this->CC_Model->diaryactivity(['plumberid' => $pagedata['result']['user_id'], 'auditorid' => $pagedata['result']['auditorid'], 'cocid' => $pagedata['result']['id'], 'action' => '13', 'type' => '4']);

				}elseif($requestData['submit']=='save' && !isset($requestData['hold']) && $requestData['auditstatus']=='0'){
					$this->db->update('stock_management', ['audit_status' => '3', 'notification' => '1'], ['id' => $pagedata['result']['id']]);
					$this->CC_Model->diaryactivity(['plumberid' => $pagedata['result']['user_id'], 'auditorid' => $pagedata['result']['auditorid'], 'cocid' => $pagedata['result']['id'], 'action' => '14', 'type' => '4']);

				}elseif($requestData['submit']=='save' && !isset($requestData['hold']) && $requestData['auditstatus']=='1'){
					$this->db->update('stock_management', ['audit_status' => '2', 'notification' => '1'], ['id' => $pagedata['result']['id']]);
					$this->CC_Model->diaryactivity(['plumberid' => $pagedata['result']['user_id'], 'auditorid' => $pagedata['result']['auditorid'], 'cocid' => $pagedata['result']['id'], 'action' => '14', 'type' => '4']);
				}
				
				if($requestData['auditstatus']=='0'){
					$auditreviewrow = $this->Auditor_Model->getReviewList('row', ['coc_id' => $pagedata['result']['id'], 'reviewtype' => '1', 'status' => '0']);
					$this->CC_Model->diaryactivity(['plumberid' => $pagedata['result']['user_id'], 'auditorid' => $pagedata['result']['auditorid'], 'cocid' => $pagedata['result']['id'], 'action' => '11', 'type' => '4']);

					if($auditreviewrow){
						$notificationdata 	= $this->Communication_Model->getList('row', ['id' => '22', 'emailstatus' => '1']);
						
						if($notificationdata){
							$pdf 		= FCPATH.'assets/uploads/temp/'.$id.'.pdf';
							$this->pdfauditreport($id, $pdf);
							
							$duedate 		= ($auditreviewrow) ? date('d-m-Y', strtotime($auditreviewrow['created_at'].' +'.$pagedata['settings']['refix_period'].'days')) : '';
							
							$body 		= str_replace(['{Plumbers Name and Surname}', '{COC number}', '{refix number} ', '{due date}'], [$pagedata['result']['u_name'], $pagedata['result']['id'], $pagedata['settings']['refix_period'], $duedate], $notificationdata['email_body']);
							$subject 	= str_replace(['{cocno}'], [$id], $notificationdata['subject']);
							$this->CC_Model->sentMail($pagedata['result']['u_email'], $subject, $body, $pdf);
							if(file_exists($pdf)) unlink($pdf);  
						}
						
						if($settingsdetail && $settingsdetail['otp']=='1'){
							$smsdata 	= $this->Communication_Model->getList('row', ['id' => '22', 'smsstatus' => '1']);
				
							if($smsdata){
								$sms = str_replace(['{number of COC}'], [$id], $smsdata['sms_body']);
								$this->sms(['no' => $pagedata['result']['u_mobile'], 'msg' => $sms]);
							}
						}
					}
				}
				
				if(isset($requestData['auditcomplete']) && $requestData['auditcomplete']=='1' && $requestData['submit']=='submitreport'){
					$chatlists = $this->Chat_Model->getList('all', ['coc_id' => $id, 'state' => '1']);
					if(count($chatlists)){
						foreach($chatlists as $chatlist){
							$this->Chat_Model->action(['id' => $chatlist['id'], 'state1' => '1', 'state2' => '1', 'viewed' => '1']);
						}
					}
					
					//Invoice and Order
					$inspectionrate = $this->currencyconvertor($this->getRates($this->config->item('inspection')));
					$invoicedata = [
						'description' 	=> 'Audit undertaken for '.$pagedata['result']['u_name'].' on COC '.$pagedata['result']['id'].'. Date of Review Submission '.date('d-m-Y', strtotime($datetime)),
						'user_id'		=> (isset($extras['auditorid'])) ? $extras['auditorid'] : '',
						'total_cost'	=> $inspectionrate,
						'status'		=> '2',
						'created_at'	=> $datetime
					];
					$this->db->insert('invoice', $invoicedata);
					$insertid = $this->db->insert_id();
					unset($invoicedata['total_cost']);
					$invoicedata = $invoicedata+['cost_value' => $inspectionrate, 'total_due' => $inspectionrate, 'inv_id' => $insertid];
					$this->db->insert('coc_orders', $invoicedata);
					
					if($requestData['auditstatus']=='1'){						
						// Email
						$notificationdata 	= $this->Communication_Model->getList('row', ['id' => '21', 'emailstatus' => '1']);
						if($notificationdata){
							$body 		= str_replace(['{Plumbers Name and Surname}', '{COC number}'], [$pagedata['result']['u_name'], $pagedata['result']['id']], $notificationdata['email_body']);
							$subject 	= str_replace(['{cocno}'], [$id], $notificationdata['subject']);
							$this->CC_Model->sentMail($pagedata['result']['u_email'], $subject, $body);
						}
						
						// SMS
						if($settingsdetail && $settingsdetail['otp']=='1'){
							$smsdata 	= $this->Communication_Model->getList('row', ['id' => '21', 'smsstatus' => '1']);
				
							if($smsdata){
								$sms = str_replace(['{number of COC}'], [$id], $smsdata['sms_body']);
								$this->sms(['no' => $pagedata['result']['u_mobile'], 'msg' => $sms]);
							}
						}
						
						// Stock
						$this->db->update('stock_management', ['audit_status' => '1', 'notification' => '1'], ['id' => $pagedata['result']['id']]);
						
						// $this->CC_Model->diaryactivity(['plumberid' => $pagedata['result']['user_id'], 'auditorid' => $pagedata['result']['auditorid'], 'cocid' => $pagedata['result']['id'], 'action' => '9', 'type' => '4']);
						$this->CC_Model->diaryactivity(['plumberid' => $pagedata['result']['user_id'], 'auditorid' => $pagedata['result']['auditorid'], 'cocid' => $pagedata['result']['id'], 'action' => '12', 'type' => '4']);
					}elseif($requestData['auditstatus']=='0'){
						$this->db->update('stock_management', ['audit_status' => '4', 'notification' => '1'], ['id' => $pagedata['result']['id']]);
						
						// $this->CC_Model->diaryactivity(['plumberid' => $pagedata['result']['user_id'], 'auditorid' => $pagedata['result']['auditorid'], 'cocid' => $pagedata['result']['id'], 'action' => '10', 'type' => '4']);
						$this->CC_Model->diaryactivity(['plumberid' => $pagedata['result']['user_id'], 'auditorid' => $pagedata['result']['auditorid'], 'cocid' => $pagedata['result']['id'], 'action' => '11', 'type' => '4']);
					}
					
					$this->Auditor_Model->actionRatio($requestData['plumberid']);
					/// check audit statements

					$auditcomplete_count = $this->db->select('count(auditcomplete) as countaudit')->get_where('auditor_statement', ['plumber_id' => $requestData['plumberid'], 'auditcomplete' => '1'])->row_array();
					$audit_list = $this->db->select('id, allocation')->get_where('compulsory_audit_listing', ['user_id' => $requestData['plumberid']])->row_array();
					
					if ($audit_list['allocation']<=$auditcomplete_count['countaudit']) {
						//$this->db->delete('compulsory_audit_listing', array('id' => $audit_list['id']));
						//$this->db->delete('compulsory_audit_listing')->where('id', $audit_list['id']);
						$this->db->where('id', $audit_list['id']);
   						$this->db->delete('compulsory_audit_listing'); 
					}

				} 
				
				$this->session->set_flashdata('success', 'Successfully updated.');
			}else{
				$this->session->set_flashdata('error', 'Try Later.');
			}
			
			redirect($extras['redirect']); 
		}
		
		$pagedata['userid'] 					= $this->getUserID();
		$pagedata['notification'] 				= $this->getNotification();
		$pagedata['province'] 					= $this->getProvinceList();
		$pagedata['installationtype']			= $this->getInstallationTypeList();
		$pagedata['auditorreportlist']			= $this->getAuditorReportingList((isset($extras['auditorid']) ? $extras['auditorid'] : ''));
		$pagedata['workmanshippt']				= $this->getWorkmanshipPoint();
		$pagedata['plumberverificationpt']		= $this->getPlumberVerificationPoint();
		$pagedata['cocverificationpt']			= $this->getCocVerificationPoint();
		$pagedata['noaudit']					= $this->getAuditorPoints($this->config->item('noaudit'));
		$pagedata['refixcompletept'] 			= $this->getAuditorPoints($this->config->item('refixcompletept'));	
		$pagedata['cautionarypt'] 				= $this->getAuditorPoints($this->config->item('cautionarypt'));	
		$pagedata['complimentpt'] 				= $this->getAuditorPoints($this->config->item('complimentpt'));	
		$pagedata['cpdpt'] 						= $this->getAuditorPoints($this->config->item('cpdpt'));	
		$pagedata['workmanship'] 				= $this->config->item('workmanship');
		$pagedata['yesno'] 						= $this->config->item('yesno');		
		$pagedata['reviewtype'] 				= $this->config->item('reviewtype');	
		$pagedata['reviewlist']					= $this->Auditor_Model->getReviewList('all', ['coc_id' => $id]);
		$pagedata['menu']						= $this->load->view('common/auditstatement/menu', (isset($pagedata) ? $pagedata : ''), true);
		
		$data['plugins']			= ['datepicker', 'sweetalert', 'validation', 'select2'];
		$data['content'] 			= $this->load->view('common/auditstatement/review', (isset($pagedata) ? $pagedata : ''), true);
		$this->layout2($data);
	}*/

	public function getauditreview($id, $pagedata=[], $extras=[])
	{		
		if(isset($extras['notification'])){
			$this->db->update('stock_management', ['notification' => '0'], ['id' => $id]);
		}
		
		$extraparam = [];
		if(isset($extras['auditorid'])) $extraparam['auditorid'] 	= $extras['auditorid'];
		if(isset($extras['plumberid'])) $extraparam['user_id'] 		= $extras['plumberid'];	
		$extraparam['page'] = 'review';	
		
		$result	= $this->Coc_Model->getCOCList('row', ['id' => $id, 'coc_status' => ['2']]+$extraparam, ['coclog', 'users', 'usersdetail', 'usersplumber', 'auditordetails', 'auditorstatement']);	
		if(!$result){
			$this->session->set_flashdata('error', 'No Record Found.');
			redirect($extras['redirect']); 
		}
		
		$pagedata['settings'] 		= $this->Systemsettings_Model->getList('row');
		$pagedata['result']			= $result;
		
		if($this->input->post()){
			$datetime 		=  date('Y-m-d H:i:s');
			$requestData 	=  $this->input->post();
			// echo "<pre>";print_r($requestData);die;
			$data 			=  $this->Auditor_Model->actionStatement($requestData);
			$settingsdetail =  $this->Systemsettings_Model->getList('row');
						
			if($data){

				if ($requestData['submit'] == 'adminsubmitreport') {

					if ($requestData['auditstatus']=='1') {
						$this->db->update('stock_management', ['audit_status' => '1', 'notification' => '1'], ['id' => $requestData['cocid']]);
					}elseif($requestData['auditstatus']=='0'){
						$this->db->update('stock_management', ['audit_status' => '4', 'notification' => '1'], ['id' => $requestData['cocid']]);
					}
					
					// $this->db->update('stock_management', ['audit_status' => $requestData['auditorstatus']], ['id' => $requestData['cocid']]);

					$created_by = $this->getuserID();

					if (isset($requestData['image2']) && $requestData['image2'] !=''){
						$imagedata = '<a href='.base_url().'/assets/uploads/auditor/statement/'.$requestData['image2'].''.' target="_blank">'.'Reason file link'.'</a>';
					}else{
						$imagedata = '';
					}

					$message3 = 'Made changes to the audit.';
					$commentdata = [
						'auditor_id' 	=> $requestData['auditorid'],
						'coc_id' 		=> $requestData['cocid'],
						'plumber_id' 	=> $requestData['plumberid'],
						'admin_id' 		=> $created_by,
						'message' 		=> $message3,
						'type' 			=> '1',
						'action' 		=> '12',
						'datetime' 		=> $datetime,
					];
					$this->db->insert('diary',$commentdata);
				}
				
				if($requestData['submit']=='submitreport' && isset($requestData['hold'])){
					$this->db->update('stock_management', ['audit_status' => '5', 'notification' => '1'], ['id' => $pagedata['result']['id']]);
					$this->CC_Model->diaryactivity(['plumberid' => $pagedata['result']['user_id'], 'auditorid' => $pagedata['result']['auditorid'], 'cocid' => $pagedata['result']['id'], 'action' => '13', 'type' => '4']);

				}elseif($requestData['submit']=='submitreport' && !isset($requestData['hold']) && $requestData['auditstatus']=='0'){
					$this->db->update('stock_management', ['audit_status' => '3', 'notification' => '1'], ['id' => $pagedata['result']['id']]);
					$this->CC_Model->diaryactivity(['plumberid' => $pagedata['result']['user_id'], 'auditorid' => $pagedata['result']['auditorid'], 'cocid' => $pagedata['result']['id'], 'action' => '14', 'type' => '4']);

				}elseif($requestData['submit']=='submitreport' && !isset($requestData['hold']) && $requestData['auditstatus']=='1'){
					$this->db->update('stock_management', ['audit_status' => '2', 'notification' => '1'], ['id' => $pagedata['result']['id']]);
					$this->CC_Model->diaryactivity(['plumberid' => $pagedata['result']['user_id'], 'auditorid' => $pagedata['result']['auditorid'], 'cocid' => $pagedata['result']['id'], 'action' => '14', 'type' => '4']);
				}
				
				if($requestData['auditstatus']=='0'){
					$auditreviewrow = $this->Auditor_Model->getReviewList('row', ['coc_id' => $pagedata['result']['id'], 'reviewtype' => '1', 'status' => '0']);
					$this->CC_Model->diaryactivity(['plumberid' => $pagedata['result']['user_id'], 'auditorid' => $pagedata['result']['auditorid'], 'cocid' => $pagedata['result']['id'], 'action' => '11', 'type' => '4']);

					if($auditreviewrow){
						$notificationdata 	= $this->Communication_Model->getList('row', ['id' => '22', 'emailstatus' => '1']);
						
						if($notificationdata){
							$pdf 		= FCPATH.'assets/uploads/temp/'.$id.'.pdf';
							$this->pdfauditreport($id, $pdf);
							
							$duedate 		= ($auditreviewrow) ? date('d-m-Y', strtotime($auditreviewrow['created_at'].' +'.$pagedata['settings']['refix_period'].'days')) : '';
							
							$body 		= str_replace(['{Plumbers Name and Surname}', '{COC number}', '{refix number} ', '{due date}'], [$pagedata['result']['u_name'], $pagedata['result']['id'], $pagedata['settings']['refix_period'], $duedate], $notificationdata['email_body']);
							$subject 	= str_replace(['{cocno}'], [$id], $notificationdata['subject']);
							$this->CC_Model->sentMail($pagedata['result']['u_email'], $subject, $body, $pdf);
							if(file_exists($pdf)) unlink($pdf);  
						}
						
						if($settingsdetail && $settingsdetail['otp']=='1'){
							$smsdata 	= $this->Communication_Model->getList('row', ['id' => '22', 'smsstatus' => '1']);
				
							if($smsdata){
								$sms = str_replace(['{number of COC}'], [$id], $smsdata['sms_body']);
								$this->sms(['no' => $pagedata['result']['u_mobile'], 'msg' => $sms]);
							}
						}
					}
				}
				
				if(isset($requestData['auditcomplete']) && $requestData['auditcomplete']=='1' && $requestData['submit']=='finalizereport'){

					// Update points if refuserefix
					$refuseRefixPts = $this->Global_performance_Model->getPointList('row', ['id' => '18']);
					if ((isset($requestData['refuserefix']) && $requestData['refuserefix']=='1') && isset($requestData['refuse_point']) && $requestData['refuse_point']!='') {
						$reviewIdArray = explode(',', $requestData['refuse_point']);
						foreach ($reviewIdArray as $reviewIdArraykey => $reviewIdArrayvalue) {
							$reviewData = $this->Auditor_Model->getReviewList('row', ['id' => $reviewIdArrayvalue]);
							$updatePts['point'] = $reviewData['point']*$refuseRefixPts['point'];
							$this->db->update('auditor_review', $updatePts, ['id' => $reviewData['id']]);
						}
					}

					if ((isset($requestData['refuserefix']) && $requestData['refuserefix']=='1') && isset($requestData['refuse_point']) && $requestData['refuse_point']!='') {
						$refuseDBpoint = $refuseRefixPts['point'];
					}else{
						$refuseDBpoint = '0';
					}

					if ((isset($requestData['refuserefix']) && $requestData['refuserefix']=='1') && isset($requestData['refuse_point']) && $requestData['refuse_point']!='') {
						$updateperformacepts = $this->updatePerformance(['cocid' => $requestData['cocid'], 'overallwrk' => $this->config->item('workmanship')[$requestData['workmanship']], 'plumberprst' => $this->config->item('yesno')[$requestData['plumberverification']], 'cocverification' => $this->config->item('yesno')[$requestData['cocverification']], 'refusepts' => $refuseDBpoint]);
					}

					

					$chatlists = $this->Chat_Model->getList('all', ['coc_id' => $id, 'state' => '1']);
					if(count($chatlists)){
						foreach($chatlists as $chatlist){
							$this->Chat_Model->action(['id' => $chatlist['id'], 'state1' => '1', 'state2' => '1', 'viewed' => '1']);
						}
					}
					
					//Invoice and Order
					$inspectionrate = $this->currencyconvertor($this->getRates($this->config->item('inspection')));
					$invoicedata = [
						'description' 	=> 'Audit undertaken for '.$pagedata['result']['u_name'].' on COC '.$pagedata['result']['id'].'. Date of Review Submission '.date('d-m-Y', strtotime($datetime)),
						'user_id'		=> (isset($extras['auditorid'])) ? $extras['auditorid'] : '',
						'total_cost'	=> $inspectionrate,
						'status'		=> '2',
						'created_at'	=> $datetime
					];
					$this->db->insert('invoice', $invoicedata);
					$insertid = $this->db->insert_id();
					unset($invoicedata['total_cost']);
					$invoicedata = $invoicedata+['cost_value' => $inspectionrate, 'total_due' => $inspectionrate, 'inv_id' => $insertid];
					$this->db->insert('coc_orders', $invoicedata);
					
					if($requestData['auditstatus']=='1'){						
						// Email
						$notificationdata 	= $this->Communication_Model->getList('row', ['id' => '21', 'emailstatus' => '1']);
						if($notificationdata){
							$body 		= str_replace(['{Plumbers Name and Surname}', '{COC number}'], [$pagedata['result']['u_name'], $pagedata['result']['id']], $notificationdata['email_body']);
							$subject 	= str_replace(['{cocno}'], [$id], $notificationdata['subject']);
							$this->CC_Model->sentMail($pagedata['result']['u_email'], $subject, $body);
						}
						
						// SMS
						if($settingsdetail && $settingsdetail['otp']=='1'){
							$smsdata 	= $this->Communication_Model->getList('row', ['id' => '21', 'smsstatus' => '1']);
				
							if($smsdata){
								$sms = str_replace(['{number of COC}'], [$id], $smsdata['sms_body']);
								$this->sms(['no' => $pagedata['result']['u_mobile'], 'msg' => $sms]);
							}
						}
						
						// Stock
						$this->db->update('stock_management', ['audit_status' => '1', 'notification' => '1'], ['id' => $pagedata['result']['id']]);
						
						// $this->CC_Model->diaryactivity(['plumberid' => $pagedata['result']['user_id'], 'auditorid' => $pagedata['result']['auditorid'], 'cocid' => $pagedata['result']['id'], 'action' => '9', 'type' => '4']);
						$this->CC_Model->diaryactivity(['plumberid' => $pagedata['result']['user_id'], 'auditorid' => $pagedata['result']['auditorid'], 'cocid' => $pagedata['result']['id'], 'action' => '12', 'type' => '4']);
					}elseif($requestData['auditstatus']=='0'){
						$this->db->update('stock_management', ['audit_status' => '4', 'notification' => '1'], ['id' => $pagedata['result']['id']]);
						
						// $this->CC_Model->diaryactivity(['plumberid' => $pagedata['result']['user_id'], 'auditorid' => $pagedata['result']['auditorid'], 'cocid' => $pagedata['result']['id'], 'action' => '10', 'type' => '4']);
						// $this->CC_Model->diaryactivity(['plumberid' => $pagedata['result']['user_id'], 'auditorid' => $pagedata['result']['auditorid'], 'cocid' => $pagedata['result']['id'], 'action' => '11', 'type' => '4']);
					}
					
					$this->Auditor_Model->actionRatio($requestData['plumberid']);
					/// check audit statements

					$auditcomplete_count = $this->db->select('count(auditcomplete) as countaudit')->get_where('auditor_statement', ['plumber_id' => $requestData['plumberid'], 'auditcomplete' => '1'])->row_array();
					$audit_list = $this->db->select('id, allocation')->get_where('compulsory_audit_listing', ['user_id' => $requestData['plumberid']])->row_array();
					
					if ($audit_list['allocation']<=$auditcomplete_count['countaudit']) {
						//$this->db->delete('compulsory_audit_listing', array('id' => $audit_list['id']));
						//$this->db->delete('compulsory_audit_listing')->where('id', $audit_list['id']);
						$this->db->where('id', $audit_list['id']);
   						$this->db->delete('compulsory_audit_listing'); 
					}

				}
				if((!isset($requestData['auditcomplete'])) && $requestData['submit']=='submitreport'){



					$chatlists = $this->Chat_Model->getList('all', ['coc_id' => $id, 'state' => '1']);
					if(count($chatlists)){
						foreach($chatlists as $chatlist){
							$this->Chat_Model->action(['id' => $chatlist['id'], 'state1' => '1', 'state2' => '1', 'viewed' => '1']);
						}
					}

					// only failiure ID
					if (isset($requestData['refuse_point']) && $requestData['refuse_point']!='') {
						$reviewIdArray = explode(',', $requestData['refuse_point']);
						if (count($reviewIdArray) > 0) {
							$this->db->update('stock_management', ['audit_status' => '3', 'notification' => '1'], ['id' => $pagedata['result']['id']]);

							$auditreviewrow = $this->Auditor_Model->getReviewList('row', ['coc_id' => $pagedata['result']['id'], 'reviewtype' => '1', 'status' => '0']);
							// $this->CC_Model->diaryactivity(['plumberid' => $pagedata['result']['user_id'], 'auditorid' => $pagedata['result']['auditorid'], 'cocid' => $pagedata['result']['id'], 'action' => '11', 'type' => '4']);

						}else{
							$this->db->update('stock_management', ['audit_status' => '6', 'notification' => '1'], ['id' => $pagedata['result']['id']]);
						}
					}else{
						$this->db->update('stock_management', ['audit_status' => '6', 'notification' => '1'], ['id' => $pagedata['result']['id']]);
					}
					
					$this->Auditor_Model->actionRatio($requestData['plumberid']);
					/// check audit statements

					$auditcomplete_count = $this->db->select('count(auditcomplete) as countaudit')->get_where('auditor_statement', ['plumber_id' => $requestData['plumberid'], 'auditcomplete' => '1'])->row_array();
					$audit_list = $this->db->select('id, allocation')->get_where('compulsory_audit_listing', ['user_id' => $requestData['plumberid']])->row_array();
					
					if ($audit_list['allocation']<=$auditcomplete_count['countaudit']) {
						$this->db->where('id', $audit_list['id']);
   						$this->db->delete('compulsory_audit_listing'); 
					}

				}
				
				$this->session->set_flashdata('success', 'Successfully updated.');
			}else{
				$this->session->set_flashdata('error', 'Try Later.');
			}
			
			redirect($extras['redirect']); 
		}
		
		
		$pagedata['userid'] 					= (isset($extras['auditorid'])) ? $extras['auditorid'] : $result['auditorid'];
		//$this->getUserID();
		if ($pagedata['roletype'] =='1') $pagedata['adminid'] 					= $this->getUserID();
		
		$pagedata['notification'] 				= $this->getNotification();
		$pagedata['province'] 					= $this->getProvinceList();
		$pagedata['installationtype']			= $this->getInstallationTypeList();
		$pagedata['auditorreportlist']			= $this->getAuditorReportingList((isset($extras['auditorid']) ? $extras['auditorid'] : ''));
		$pagedata['workmanshippt']				= $this->getWorkmanshipPoint();
		$pagedata['plumberverificationpt']		= $this->getPlumberVerificationPoint();
		$pagedata['cocverificationpt']			= $this->getCocVerificationPoint();
		$pagedata['noaudit']					= $this->getAuditorPoints($this->config->item('noaudit'));
		$pagedata['refixcompletept'] 			= $this->getAuditorPoints($this->config->item('refixcompletept'));	
		$pagedata['cautionarypt'] 				= $this->getAuditorPoints($this->config->item('cautionarypt'));	
		$pagedata['complimentpt'] 				= $this->getAuditorPoints($this->config->item('complimentpt'));	
		$pagedata['cpdpt'] 						= $this->getAuditorPoints($this->config->item('cpdpt'));	
		$pagedata['workmanship'] 				= $this->config->item('workmanship');
		$pagedata['yesno'] 						= $this->config->item('yesno');		
		$pagedata['reviewtype'] 				= $this->config->item('reviewtype');	
		$pagedata['reviewlist']					= $this->Auditor_Model->getReviewList('all', ['coc_id' => $id]);
		$pagedata['menu']						= $this->load->view('common/auditstatement/menu', (isset($pagedata) ? $pagedata : ''), true);
		
		$data['plugins']			= ['datepicker', 'sweetalert', 'validation', 'select2'];
		$data['content'] 			= $this->load->view('common/auditstatement/review', (isset($pagedata) ? $pagedata : ''), true);
		$this->layout2($data);
	}

	public function updatePerformance($data){
		$reviewdata 	= $this->Auditor_Model->getReviewList('all', ['coc_id' => $data['cocid']]);

		$reviewpoints 	= array_sum(array_column($reviewdata, 'reportlisting_point'));

		

		$workmanship 		= $this->overallpts($data['overallwrk'], '2');
		$plumberpresent 	= $this->overallpts($data['plumberprst'], '4');
		$cocverification 	= $this->overallpts($data['cocverification'], '6');
		$refusepoint 		= $data['refusepts'];

		$total = (($refusepoint*$reviewpoints)+$workmanship['point']+$plumberpresent['point']+$cocverification['point']);

		$updatedata = [
			'point' => $total,
		];

		$this->db->update('auditor_statement', $updatedata, ['coc_id' => $data['cocid']]);
		return $total;
	}

	public function overallpts($data, $type){
		$this->db->select('*');
		$this->db->from('gps_point');
		
		$this->db->where('description', $data);
		$this->db->where('type', $type);
		$query = $this->db->get();

		$result = $query->row_array();
		
		return $result;
	}
	
	public function getaudithistory($id, $pagedata=[], $extras=[])
	{
		$auditorid					= isset($extras['auditorid']) ? ['auditorid' => $extras['auditorid']] : [];
		$result						= $this->Coc_Model->getCOCList('row', ['id' => $id, 'coc_status' => ['2']]+$auditorid, ['auditorstatement']);	
		if(!$result){
			$this->session->set_flashdata('error', 'No Record Found.');
			redirect($extras['redirect']); 
		}
		
		$pagedata['notification'] 	= $this->getNotification();
		$pagedata['result']			= $result;
		$pagedata['history']		= $this->Auditor_Model->getReviewHistoryCount(['plumberid' => $pagedata['result']['user_id']]);	
		$pagedata['menu']			= $this->load->view('common/auditstatement/menu', (isset($pagedata) ? $pagedata : ''), true);
		
		$data['plugins']			= ['datatables', 'datatablesresponsive', 'datepicker', 'echarts'];
		$data['content'] 			= $this->load->view('common/auditstatement/history', (isset($pagedata) ? $pagedata : ''), true);
		$this->layout2($data);
	}
	
	public function getauditdiary($id, $pagedata=[], $extras=[])
	{
		$auditorid					= isset($extras['auditorid']) ? ['auditorid' => $extras['auditorid']] : [];
		$result	= $this->Coc_Model->getCOCList('row', ['id' => $id, 'coc_status' => ['2']]+$auditorid, ['auditorstatement']);	
		if(!$result){
			$this->session->set_flashdata('error', 'No Record Found.');
			redirect($extras['redirect']); 
		}
		
		$pagedata['notification'] 	= $this->getNotification();
		$pagedata['result']			= $result;
		$pagedata['comments']		= $this->Auditor_Comment_Model->getList('all', ['coc_id' => $id]);	
		$pagedata['diary']			= $this->diaryactivity(['cocid' => $id]+$auditorid);	
		$pagedata['menu']			= $this->load->view('common/auditstatement/menu', (isset($pagedata) ? $pagedata : ''), true);
		
		$data['plugins']			= ['datatables', 'datatablesresponsive', 'datepicker', 'sweetalert', 'validation', 'select2'];
		$data['content'] 			= $this->load->view('common/auditstatement/diary', (isset($pagedata) ? $pagedata : ''), true);
		$this->layout2($data);
	}
	
	public function getchat($id, $data=[], $extras=[])
	{
		$auditorid	= isset($extras['auditorid']) ? ['auditorid' => $extras['auditorid']] : [];
		$result		= $this->Coc_Model->getCOCList('row', ['id' => $id, 'coc_status' => ['2']]+$auditorid, ['users', 'auditorstatement']);	
		if(!$result){
			$this->session->set_flashdata('error', 'No Record Found.');
			redirect($extras['redirect']); 
		}
		$data['result']	= $result;
		
		$this->load->view('common/auditstatement/chat', $data);
	}
	
	public function pdfauditreport($id, $save='')
	{
		$pagedata['result']			= $this->Coc_Model->getCOCList('row', ['id' => $id, 'coc_status' => ['2']], ['coclog', 'coclogprovince', 'coclogcity', 'coclogsuburb', 'auditor', 'auditordetails']);
		$pagedata['reviewlist']		= $this->Auditor_Model->getReviewList('all', ['coc_id' => $id]);
		$html = $this->load->view('pdf/auditreport', (isset($pagedata) ? $pagedata : ''), true);
		$this->pdf->loadHtml($html);
		$this->pdf->setPaper('A4', 'portrait');
		$this->pdf->render();
		$output = $this->pdf->output();
		
		if($save==''){
			$this->pdf->stream('Audit Report '.$id);
		}else{
			file_put_contents($save, $output);
			return $save;
		}
	}

	public function pdfelectroniccocreport($id, $userid)
	{		
		$userdata				 		= $this->Plumber_Model->getList('row', ['id' => $userid], ['users', 'usersdetail', 'usersplumber', 'coclog', 'coclogprovince', 'coclogcity', 'coclogsuburb', 'coclogcompany']);

		$pagedata['userdata']	 		= $userdata;
		$pagedata['specialisations']	= explode(',', $pagedata['userdata']['specialisations']);
		$pagedata['result']		    	= $this->Coc_Model->getCOCList('row', ['id' => $id], ['coclog', 'coclogprovince', 'coclogcity', 'coclogsuburb', 'coclogcompany', 'users', 'usersdetail']);
		$pagedata['noncompliance'] 	= $this->Noncompliance_Model->getList('all', ['coc_id' => $id, 'user_id' => $userid]);
		$pagedata['designation2'] 		= $this->config->item('designation2');
		$specialisations 				= explode(',', $userdata['specialisations']);
		$pagedata['installationtype']	= $this->getInstallationTypeList();
		$pagedata['installation'] 		= $this->Installationtype_Model->getList('all', ['ids' => ['1','2','3','5','6','7']]);
		$pagedata['specialisations']	= $this->Installationtype_Model->getList('all', ['ids' => ['4','8']]);


		$noncompliance					= $this->Noncompliance_Model->getList('all', ['coc_id' => $id, 'user_id' => $userid]);
		if (count($noncompliance) > 0) {
			$pagedata['noncompliance'] 	= $this->Noncompliance_Model->getList('all', ['coc_id' => $id, 'user_id' => $userid]);
			$html = $this->load->view('pdf/electroniccoc_ncreport', (isset($pagedata) ? $pagedata : ''), true);
		}else{
			$html = $this->load->view('pdf/electroniccocreport', (isset($pagedata) ? $pagedata : ''), true);
		}
		
		$this->pdf->loadHtml($html);
		$this->pdf->setPaper('A4', 'portrait');
		$this->pdf->render();
		$output = $this->pdf->output();
		$this->pdf->stream('Electronic COC Report '.$id);
	}
	
	public function pdfnoncompliancereport($id, $userid, $save='')
	{		
		$pagedata['result']			= $this->Coc_Model->getCOCList('row', ['id' => $id, 'coc_status' => ['2']], ['coclog', 'coclogprovince', 'coclogcity', 'coclogsuburb', 'coclogcompany', 'users', 'usersdetail']);
		$pagedata['noncompliance'] 	= $this->Noncompliance_Model->getList('all', ['coc_id' => $id, 'user_id' => $userid]);
		$userdata				 	= $this->Plumber_Model->getList('row', ['id' => $userid], ['users', 'usersdetail', 'usersplumber', 'company']);
		$pagedata['cwork_phone'] 	= $userdata['cwork_phone'];	

		$html = $this->load->view('pdf/noncompliancereport', (isset($pagedata) ? $pagedata : ''), true);
		$this->pdf->loadHtml($html);
		$this->pdf->setPaper('A4', 'portrait');
		$this->pdf->render();
		$output = $this->pdf->output();
		
		if($save==''){
			$this->pdf->stream('Non Compliance Report '.$id);
		}else{
			file_put_contents($save, $output);
			return $save;
		}
	}	

	public function cocreport($id, $title, $extras=[])
	{		
		$pagedata['settings']	= $this->Systemsettings_Model->getList('row');
		$pagedata['currency']   = $this->config->item('currency');
		$pagedata['rowData'] 	= $this->Coc_Model->getListPDF('row', ['id' => $id, 'status' => ['0','1']]);
		$pagedata['rowData1'] 	= $this->Coc_Model->getPermissions('row'); 
		$pagedata['rowData2'] 	= $this->Coc_Model->getPermissions1('row');
		$pagedata['title'] 		= $title;
		$pagedata['extras'] 	= $extras;
		
		$html 			= $this->load->view('pdf/coc', (isset($pagedata) ? $pagedata : ''), true);
		$pdfFilePath 	= $id.'.pdf';
		$filePath 		= FCPATH.'assets/inv_pdf/';
		
		if(file_exists($filePath.$pdfFilePath)) unlink($filePath.$pdfFilePath);  
		
		$dompdf = new Dompdf();
		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();
		$output = $dompdf->output();
		file_put_contents($filePath.$pdfFilePath, $output);
		
		return $filePath.$pdfFilePath;
	}	
	
	function generaterenewalpdf($inv_id, $type='', $pdftype=''){
		$invoice 		= 	$this->db->get_where('invoice', ['inv_id' => $inv_id])->row_array();
		$orders 		= 	$this->db->get_where('coc_orders', ['inv_id' => $inv_id])->row_array();
		$userdata1		= 	$this->Plumber_Model->getList('row', ['id' => $invoice['user_id']], ['users', 'usersdetail', 'usersplumber']);
		$designation	= 	$userdata1['designation'];	
		$otherfee = [];
		if($userdata1['registration_card']=='1'){
			$otherfee['cardfee'] = $this->getRates($this->config->item('cardfee'));
		}
		$specialisations = array_filter(explode(',', $userdata1['specialisations']));
		if(count($specialisations) > 0){
			$otherfee['specialisationsfee'] = $this->getRates($this->config->item('specializationfee'));
			$otherfee['specialisationsqty'] = count($specialisations);
		}
		if($pdftype!='') $otherfee['type'] = $pdftype;
		
		if($type=='1') $this->Renewal_Model->updatedata($invoice['user_id'],$designation,'3',$invoice['inv_id'],$orders['id'],$otherfee);
		
		$designation 	=	$this->config->item('designation2')[$designation];
		unlink('./assets/inv_pdf/'.$inv_id.'.pdf'); 
		$rowData 		= 	$this->Coc_Model->getListPDF('row', ['id' => $inv_id, 'status' => ['0','1']]);
		
		$this->cocreport($inv_id, 'PDF Invoice Plumber COC', ['description' => 'PIRB year renewal fee for '.$designation.' for '.$rowData['username'].' '.$rowData['surname'].', registration number '.$rowData['registration_no']]+$otherfee);
	}
	
	public function mycptindex($pagestatus='',$id='',$userid='')
	{
		$userdetails 	= $this->getUserDetails();
		if($id!=''){
			$dbexpirydate = $userdetails['expirydate'];
			$result = $this->Mycpd_Model->getQueueList('row', ['id' => $id, 'pagestatus' => $pagestatus, 'dbexpirydate' => $userdetails['expirydate']]);
			if($result){
				$pagedata['result'] = $result;
			}else{
				$this->session->set_flashdata('error', 'No Record Found.');
				redirect('plumber/mycpd/index'); 
			}
		}
		
		if($this->input->post()){
			$requestData 	= 	$this->input->post();

			if($requestData['submit']=='submit'){

				$data 	=  $this->Mycpd_Model->actionInsert($requestData);
				if($data) $message = 'CPD activity '.(($id=='') ? 'submitted.' : 'updated.');
			}elseif($requestData['submit']=='save'){
				//print_r($requestData);die;

				$data 	=  $this->Mycpd_Model->actionSave($requestData);
				if($data) $message = 'CPD activity '.(($id=='') ? 'save' : 'updated').' successfully.';
			}
			else{
				$data 			= 	$this->Mycpd_Model->changestatus($requestData);
				$message		= 	'CPD activity deleted successfully.';
			}

			if(isset($data)) $this->session->set_flashdata('success', $message);
			else $this->session->set_flashdata('error', 'Try Later.');
			
			redirect('plumber/mycpd/index'); 
		}
		if ($pagestatus =='' || $pagestatus =='1') $pagestatuz = '1';
		else $pagestatuz = '0';

		$developmental 				= $this->Auditor_Model->admingetcpdpoints('all', ['pagestatus' => $pagestatuz, 'plumberid' => $userid, 'status' => ['1'], 'cpd_stream' => 'developmental', 'dbexpirydate' => $userdetails['expirydate']]);
		$individual 				= $this->Auditor_Model->admingetcpdpoints('all', ['pagestatus' => $pagestatuz, 'plumberid' => $userid, 'status' => ['1'], 'cpd_stream' => 'individual', 'dbexpirydate' => $userdetails['expirydate']]);
		$workbased 				= $this->Auditor_Model->admingetcpdpoints('all', ['pagestatus' => $pagestatuz, 'plumberid' => $userid, 'status' => ['1'], 'cpd_stream' => 'workbased', 'dbexpirydate' => $userdetails['expirydate']]);

		if (count($developmental) > 0) $developmental = array_sum(array_column($developmental, 'points')); 
		else $developmental = 0;
		if (count($individual) > 0) $individual = array_sum(array_column($individual, 'points')); 
		else $individual = 0;
		if (count($workbased) > 0) $workbased = array_sum(array_column($workbased, 'points')); 
		else $workbased = 0;
		$totalcpd = $developmental+$individual+$workbased;

		// $pagedata['mycpd'] 			= $this->userperformancestatus(['performancestatus' => '1', 'auditorstatement' => '1']);
		$pagedata['mycpd'] 			= $totalcpd;
		$userdata1					= $this->Plumber_Model->getList('row', ['id' => $userid], ['users', 'usersdetail', 'usersplumber']);
		$pagedata['notification'] 	= $this->getNotification();
		$pagedata['cpdstreamID'] 	= $this->config->item('cpdstream');
		$pagedata['pagestatus'] 	= $this->getPageStatus($pagestatus);
		$pagedata['id'] 			= $userid;
		$pagedata['user_details'] 	= $userdata1;
		$data['plugins']			= ['datatables', 'datatablesresponsive', 'sweetalert', 'validation', 'datepicker', 'knob'];
		$data['content'] 			= $this->load->view('plumber/mycpd/index', (isset($pagedata) ? $pagedata : ''), true);
		$this->layout2($data);
	}
	
	public function userperformancestatus($data = []){	
		$rollingavg 	= $this->getRollingAverage();
		$userid			= (isset($data['userid'])) ? $data['userid'] : $this->getUserID();
		$date			= date('Y-m-d', strtotime(date('Y-m-d').'+'.$rollingavg.' months'));
		
		$extradata 				= $data;
		$extradata['date'] 		= $date;
		$extradata['archive'] 	= '0';
		
		if(count($data)==0){
			$extradata['plumberid'] = $userid;
		}elseif(count($data) > 0 && in_array('performancestatus', array_keys($data))){
			unset($extradata['date']);
			unset($extradata['archive']);
			$extradata['plumberid'] = $userid;
			unset($data);
		}
		
		$results = $this->Plumber_Model->performancestatus('all', $extradata);
		
		if(isset($data) && count($data) > 0){
			if(isset($data['limit'])){
				return $results;
			}else{
				$useridsearch = array_search($userid, array_column($results, 'userid'));
				return ($useridsearch !== false) ? $useridsearch+1 : 0;
			}
		}else{
			return count($results) ? array_sum(array_column($results, 'point')) : '0';
		}
	}
	
	public function performancestatusrollingaverage(){	
		$data = $this->Global_performance_Model->getPointList('row', ['id' => $this->config->item('rollingaverage')]);
		if($data && isset($data['point']) && $data['point']!=''){
			$this->db->trans_begin();	
			$date = date('Y-m-d', strtotime(date('Y-m-d').'-'.$data['point'].' months'));
			$this->db->update('auditor_statement', ['archive' => '1'], ['auditcompletedate <=' => $date,'archive' => '0']);
			$this->db->update('cpd_activity_form', ['archive' => '1'], ['approved_date <=' => $date,'archive' => '0']);	
			$this->db->update('performance_status', ['archive' => '1'], ['enddate <=' => $date,'archive' => '0']);	
			
			if($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				return false;
			}
			else
			{
				$this->db->trans_commit();
				return true;
			}
		}
	}
	
	public function performancestatusmail()
	{
		$warnings		= $this->Global_performance_Model->getWarningList('all', ['status' => ['1']]);
		$rollingavg 	= $this->getRollingAverage();
		$date			= date('Y-m-d', strtotime(date('Y-m-d').'+'.$rollingavg.' months'));
		$settingsdetail = $this->Systemsettings_Model->getList('row');
		
		$datas = $this->Plumber_Model->performancestatus('all', ['plumbergroup' => '1', 'archive' => '0', 'date' => $date, 'performancestatus' => '1']);
		foreach($datas as $data){
			$explodepoint 	= explode(',', $data['point']);
			$plumberid		= $data['userid'];
			$warninglevel 	= '';
			$warningtext 	= '';
			
			foreach($explodepoint as $plumberpoint){				
				for($i=0; $i<count($warnings); $i++){	
			
					if($plumberpoint < 0){					
						$warningpoint = $warnings[$i]['point'];
						$warningend = isset($warnings[$i+1]['point']) ? $warnings[$i+1]['point'] : '0';
						
						if(
							($warningend!='0' && ((abs($warningpoint) <= abs($plumberpoint)) && (abs($warningend) > abs($plumberpoint)))) ||
							($warningend=='0' && ((abs($warningpoint) <= abs($plumberpoint))))
						){
							$warninglevel = $i+1;
							$warningtext  = $warnings[$i]['warning'];
						}					
					}
				}				
			}
			
			if($warninglevel!=''){
				$userDetails = $this->getUserDetails($plumberid);
				$userwarning = $userDetails['performancestatus'];
				if($userwarning!=$warninglevel){
					$this->db->update('users', ['performancestatus' => $warninglevel], ['id' => $plumberid]);
					$notificationid 	= ['9', '10', '11', '12'];
					$notificationdata 	= $this->Communication_Model->getList('row', ['id' => $notificationid[$warninglevel-1], 'emailstatus' => '1']);

					if($notificationdata){
						$plumber 	= $this->Plumber_Model->getList('row', ['id' => $plumberid], ['users', 'usersdetail']);
						$body 		= str_replace(['{Plumbers Name and Surname}', '{Performance warning status}'], [$plumber['name'].' '.$plumber['surname'], $warningtext], $notificationdata['email_body']);
						$this->CC_Model->sentMail($plumber['email'], $notificationdata['subject'], $body);
					}
					
					if($settingsdetail && $settingsdetail['otp']=='1'){
						$smsdata 	= $this->Communication_Model->getList('row', ['id' => $notificationid[$warninglevel-1], 'smsstatus' => '1']);
			
						if($smsdata){
							$sms = str_replace(['{performance warning status}.'], [$warningtext], $smsdata['sms_body']);
							$this->sms(['no' => $plumber['mobile_phone'], 'msg' => $sms]);
						}
					}
					
					if($warninglevel=='4'){
						$this->db->update('users', ['status' => '2'], ['id' => $plumberid]);
						$this->db->update('users_detail', ['status' => '2'], ['user_id' => $plumberid]);
					}
				}
			}else{
				$this->db->update('users', ['performancestatus' => '0'], ['id' => $plumberid]);
			}							
		}
	}
	
	public function diaryactivity($requestdata=[])
	{
		$data['results'] 	= $this->Diary_Model->getList('all', $requestdata);
		return $this->load->view('common/diary', $data, true);
	}
	
	public function sms($data)
	{
		$no = str_replace([' ', '(', ')', '-'], ['', '', '', ''], trim($data['no']));
		if($no[0]=='0') $no = substr($no, 1);
			
		$param = [
			'Type' 		=> 'sendparam',
			'username' 	=> 'PIRB Registration',
			'password' 	=> 'Plumber',
			'numto' 	=> '+'.$no,
			'data1' 	=> $data['msg']
		];

		if (isset($data['userid']) && isset($data['email']) && isset($data['otpcode'])) {
			$body 	= '
			Hi,<br>

				Please use the following OTP code for the Audit-IT website.<br>
				OTP code: '.$data['otpcode'].'<br>

				Best Regards<br>

				The PIRB Team<br>
				Tel: 0861 747 275<br>
				Email: info@pirb.co.za<br>

				Please do not reply to this email, as it will not be responded to.
				';
			$this->CC_Model->sentMail($data['email'], 'OTP Verification', $body);
			// $this->CC_Model->sentMail('suresh@itflexsolutions.com', 'Test OTP Verification', $body);
		}
		
		$url = 'http://www.mymobileapi.com/api5/http5.aspx';
		if (isset($data['smsenable']) && $data['smsenable'] =='1') {
			$this->curlRequest($url, 'GET', $param);
		}
	}
	
	public function curlRequest($url, $method, $param=[])
	{
		$curlaction['url'] = $url;
		
		$curl = curl_init(); 

        if (!$curl) {
            die("Couldn't initialize a cURL handle"); 
        }
		
		
		if($method=='GET' && count($param) > 0){
			$curlaction['request'] = json_encode($param);
			$url = $url.'?'.http_build_query($param);
		}
		
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json')); 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
        
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method); 
		
		if($method=='POST' && count($param) > 0){			
			$param = json_encode($param);
			$curlaction['request'] = $param;
			curl_setopt($curl, CURLOPT_POSTFIELDS, $param);
		}
		
        $result = curl_exec($curl); 

        if (curl_errno($curl)){
			//return false;
            //echo 'cURL error: ' . curl_error($curl); 
			$curlaction['error'] = curl_error($curl); 
        }else{ 
           // print_r(curl_getinfo($curl)); 
		   $curlaction['info'] = json_encode(curl_getinfo($curl)); 
        }
		
        curl_close($curl);
		
		$curlaction['response'] = $result; 
		$this->curlAction($curlaction);
		return $result;
	}
	
	public function curlAction($data)
	{
		$this->db->trans_begin();
		
		$datetime		= 	date('Y-m-d H:i:s');
		
		if(isset($data['url']))		 		$request['url'] 			= $data['url'];
		if(isset($data['request']))		 	$request['request'] 		= $data['request'];
		if(isset($data['response']))		$request['response'] 		= $data['response'];
		if(isset($data['error']))			$request['error'] 			= $data['error'];
		if(isset($data['info']))			$request['info'] 			= $data['info'];
		
		$request['datetime'] 	= $datetime;
		$this->db->insert('curl_log', $request);
				
		if($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return false;
		}
		else
		{
			$this->db->trans_commit();
			return true;
		}
	}
	public function lmscurl($data){
		$url = 'https://iopsatraining.co.za/wp-json/lms/v2/users/register/';
		$method = 'GET';
		$curlaction['url'] = $url;
		$curl = curl_init(); 

		$param = [
					'firstname' 		=> $data['firstname'],
					'surname' 			=> $data['surname'],
					'password' 			=> $data['password'],
					// 'username' 			=> $data['username'],
					'email' 			=> $data['email'],
					'nickname' 			=> $data['nickname'],
				];
		$curlaction['request'] = json_encode($param);
		$url = $url.'?'.http_build_query($param);

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json')); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
		$result = curl_exec($curl); 
		if (curl_errno($curl)){
		//return false;
		//echo 'cURL error: ' . curl_error($curl); 
		$curlaction['error'] = curl_error($curl); 
		}else{ 
		// print_r(curl_getinfo($curl)); 
		$curlaction['info'] = json_encode(curl_getinfo($curl)); 
		}

		curl_close($curl);
		$curlaction['response'] = $result;
		$curlaction['userid'] 	= $data['userid'];
		$curlaction['lms_status'] = '1';
		$this->lmscurlaction($curlaction);
		return $result;
	}

	public function lmscurlaction($data){
		$request['lms_status'] = $data['lms_status'];
		$this->db->update('users_plumber', $request, ['user_id' => $data['userid']]);
		return true;

	}

	public function cronLog($extras=[]){
		$requestdata0['filename'] 		= $extras['filename'];
		$requestdata0['start_time'] 	= $extras['start_time'];
		$requestdata0['end_time'] 		= $extras['end_time'];
		$result = $this->db->insert('cron_log',$requestdata0);
	}
	
	
	public function downloadfile($file){
		if (file_exists($file)){
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="'.basename($file).'"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			readfile($file);
			exit;
		}
	}
		
	function base64conversion($path){
		$type = pathinfo($path, PATHINFO_EXTENSION);
		$data = file_get_contents($path);
		return 'data:image/' . $type . ';base64,' . base64_encode($data);
	}
	
	function currencyconvertor($currency){
		$amount 	= number_format(floor($currency*100)/100, 2,".","");
		$lastchr	= $amount[strlen($amount)-1];
		
		if($lastchr < 5){
			$amount[strlen($amount)-1] = '0';
		}else{
			$amount[strlen($amount)-1] = '5';
		}
		
		return $amount;
	}
	
	public function plumberregistrationdocument($result){
		$filename				= 'registrationletter'.date("dmY").'.docx';
		$userid					= $result['id'];
		
		$postaladdress 		= isset($result['postaladdress']) ? explode('@-@', $result['postaladdress']) : [];
		$address				= isset($postaladdress[2]) ? $postaladdress[2] : '';
		$suburb 				= isset($postaladdress[3]) ? $this->Managearea_Model->getList('row', ['id' => $postaladdress[3]]) : '';
		$city					= isset($postaladdress[4]) ? $this->Managearea_Model->getListCity('row', ['id' => $postaladdress[4]]) : '';
		$province 				= isset($postaladdress[5]) ? $this->Managearea_Model->getListProvince('row', ['id' => $postaladdress[5]]) : '';
		$postalcode 			= isset($postaladdress[6]) ? $postaladdress[6] : '';
		$designationid 			= isset($result['designation']) ? $result['designation'] : '';
		$specialisationsid 		= isset($result['specialisations']) ? array_filter(explode(',', $result['specialisations'])) : [];
		$expirydatestart 		= isset($result['expirydate']) && $result['expirydate']!='1970-01-01' ? date('d-m-Y', strtotime("-1 year", strtotime($result['expirydate']))) : '';
		$expirydateend 			= isset($result['expirydate']) && $result['expirydate']!='1970-01-01' ? date('d-m-Y', strtotime($result['expirydate'])) : '';
		$card 					= $this->plumbercard($userid);
		
		$specialisations		= [];
		foreach($specialisationsid as $specialisationsdata){
			if(isset($this->config->item('specialisations')[$specialisationsdata])){
				$specialisations[] = $this->config->item('specialisations')[$specialisationsdata];
			}
		}
		$designation 			= (isset($this->config->item('designation2')[$designationid]) ? $this->config->item('designation2')[$designationid] : '');
		$specialisations 		= ((count($specialisations) > 0) ? implode(',', $specialisations) : 'None');
		
		$cardurl 	= base_url().'common/import/plumbercarddesign/'.$userid;
		$imageurl 	= $_SERVER['DOCUMENT_ROOT'].'/assets/uploads/temp/card'.$userid.'.png';
		$output 	= shell_exec('wkhtmltoimage '.$cardurl.' '.$imageurl);
		
		$templateDocx = new \PhpOffice\PhpWord\TemplateProcessor('./assets/docx/registrationletter.docx');
		$templateDocx->setValue(
			[
				'Current date and time', 
				'Plumber name', 
				'Plumber surname',
				'RegNo',
				'Delivery address street',
				'Delivery address suburb',
				'Delivery address city',
				'Delivery address province',
				'Delivery address area code',
				'Designation',
				'Specialization',
				'Expiration date start',
				'Expiration date end'
			],
			[
				date("d-m-Y H:i:s"), 
				$result['name'], 
				$result['surname'],
				$result['registration_no'],
				$address,
				$suburb['name'],
				$city['name'],
				$province['name'],
				$postalcode,
				$designation,
				$specialisations,
				$expirydatestart,
				$expirydateend
			]
		);
		$templateDocx->setImageValue('Card', array('path' => './assets/uploads/temp/card'.$userid.'.png', 'width' => 500, 'height' => 250, 'ratio' => false));
		/*
		$templateDocx->saveAs('./assets/test.docx');
		*/
		$templateDocx->saveAs('./assets/uploads/plumber/'.$userid.'/'.$filename);
		
		$data = [
			'description' 	=> 'Registration Confirmation Letter',
			'file1'			=> $filename,
			'plumberid'		=> $userid,
			'documentsid'	=> ''
		];
		
		$this->Documentsletters_Model->action($data);
	}
}
