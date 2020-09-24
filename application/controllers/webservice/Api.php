<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CC_Controller 
{
	public function __construct()
	{
		parent::__construct();

		if (isset($_SERVER['HTTP_ORIGIN'])){
			$http_origin = $_SERVER['HTTP_ORIGIN'];
		} else if (isset($_SERVER['HTTP_REFERER'])){
			$http_origin = $_SERVER['HTTP_REFERER'];
		} else {
			$http_origin = $_SERVER['SERVER_NAME'];
		}
		
		$website = [
			'http://testing.mrventer.co.za',
			'https://fogi.co.za',
			'https://katchmi.co.za',
			'http://podcast.articulateit.co.za',
			'http://diyesh.com',
			'http://localhost',
			'https://audit-it.co.za',
			'https://staging.audit-it.co.za'
		];
		
		if (in_array($http_origin, $website))
		{
				header("Access-Control-Allow-Origin: $http_origin");
		}
		header("Access-Control-Allow-Credentials: true");
		header("Access-Control-Max-Age: 1728000");
		header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
		header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");

		$this->load->model('Coc_Model');
		$this->load->model('Coc_Ordermodel');
		$this->load->model('Auditor_Model');
		$this->load->model('Friends_Model');
		$this->load->model('Chat_Model');

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
		$this->load->model('Mycpd_Model');
		$this->load->model('Subtype_Model');
		$this->load->model('Reportlisting_Model');
		$this->load->model('Api_Model');
	}

	public function login(){
		if ($this->input->post()) {
			if ($this->input->post('submit') == 'login') {
				$this->form_validation->set_rules('email', 'Email', 'trim|required');
				$this->form_validation->set_rules('password', 'Password', 'trim|required');
				$this->form_validation->set_rules('roletype', 'User Type', 'trim|required');

				if ($this->form_validation->run()==FALSE) {
					$errorMsg =  validation_errors();
					$jsonArray = array("status"=>'0', "message"=>$errorMsg, 'result' => []);
				}else{
					$jsonData = [];
					$email 		= trim($this->input->post('email'));
					$password 	= md5($this->input->post('password'));
					$roletype 	= $this->input->post('roletype');
					if ($roletype == '1') { // 1 - plumber 2- auditor
						$type = '3';
					}elseif($roletype == '2'){
						$type = '5';
					}
					// $query = $this->db->get_where('users', ['email' => $email, 'password' => $password]);
					$query = $this->db->where_in('type', $type)->get_where('users', ['email' => $email, 'password' => $password]);
				
					if($query->num_rows() > 0){
						$result 	= $query->row_array();
						if ($result['type'] == '3') {
							$userdata	= $this->Plumber_Model->getList('row', ['id' => $result['id'], 'type' => '3', 'status' => ['0', '1', '2']], ['users', 'usersdetail', 'usersplumber', 'usersskills', 'company', 'physicaladdress', 'postaladdress', 'billingaddress']);

							if (isset($userdata['plumberstatus']) && $userdata['plumberstatus'] !='') {
								if ($this->config->item('plumberstatus')[$userdata['plumberstatus']] == 'Expired') {
									$jsonData = ['plumberstatus' => $this->config->item('plumberstatus')[$userdata['plumberstatus']], 'pageresponse' => 'Plumber has Expired'];
									$message = 'Plumber has Expired';

									$jsonArray = array('status' => '1', "message"=>$message, 'result' => $jsonData);
								}else{
									if ($result['mailstatus'] =='1') {
									$jsonData['userdetails'] = [ 'userid' => $result['id'], 'roletype' => $result['type'], 'role' => $this->config->item('usertype2')[$result['type']], 'formstatus' => $result['formstatus'], 'mobilenumber' => $userdata['mobile_phone']
								 	];
								 	$message = 'Login sucessfully';
								 	$status = '1';
								 	$jsonArray = array('status' => '1', "message"=>$message, 'result' => $jsonData);
									}else{
										$jsonData['userdetails'] = [ 'userid' => $result['id'], 'roletype' => $result['type'], 'role' => $this->config->item('usertype2')[$result['type']], 'formstatus' => $result['formstatus']
									 	];
										$message = 'Please activate your account by verifying the link sent to your E-mail id.';
										$status = '0';
										$jsonArray = array('status' => '0', "message"=>$message, 'result' => $jsonData);
									}
								}
							}else{
								$jsonArray = array('status' => '1', "message"=>'Please submit your application by login into the website URL.', 'result' => []);
							}
							

						}else{
							$userdata	= $this->Auditor_Model->getList('row', ['id' => $result['id'], 'status' => ['0','1']]);
							$jsonData['userdetails'] = [ 'userid' => $result['id'], 'roletype' => $result['type'], 'role' => $this->config->item('usertype2')[$result['type']], 'formstatus' => $result['formstatus'], 'mobilenumber' => $userdata['mobile_phone']
							 	];
							$jsonArray = array('status' => '1', "message"=>'auditor details', 'result' => $jsonData);
						}
						
						
					}else{
						$jsonArray = array('status' => '0', "message"=>'Invalid Credentials.', 'result' => []);
					}
				}
			}elseif($this->input->post('submit') == 'register'){

				$this->form_validation->set_rules('email', 'Email', 'trim|required');
				$this->form_validation->set_rules('password', 'Password', 'trim|required');

				$this->form_validation->set_rules('cnfm_email', 'Email', 'trim|required');
				$this->form_validation->set_rules('cnfm_password', 'Password', 'trim|required');

				if ($this->form_validation->run()==FALSE) {
					$errorMsg =  validation_errors();
					$jsonArray = array("status"=>'0', "message"=>$errorMsg, 'result' => []);
				}else{
					$usertypename 	= $this->config->item('usertype2')[2];
					$requestData['id'] 			= '';
					$requestData['status'] 		= '0';
					$requestData['email'] 		= trim($this->input->post('email'));
					$requestData['password'] 	= trim($this->input->post('password'));
					$requestData['type'] 		= '3';
					$data 						= $this->Users_Model->actionUsers($requestData);

					if($data){
						$id 		= 	$data;
						$subject 	= 	'Email Verification';
						$message 	= 	'<div>Thank you for creating an account/profile on the PIRB\'s Audit-IT System.</div>
										<br>
										<div>Please click on the link below to verify your email address:</div>
										<br>
										<div><a href="'.base_url().'login/verification/'.$id.'/'.$usertypename.'">Click Here</a></div>
										<br>
										<div>Once verified, you will automatically be redirected to the Login Page, and may then log into your new account/profile to complete the required Application to Register forms.</div>
										<br>
										<div>Best Regards</div>
										<br>
										<div>The PIRB Team</div>
										<div>Tel: 0861 747 275</div>
										<div>Email: info@pirb.co.za</div>
										<br>
										<div>Please do not reply to this email, as it will not be responded to.</div>
										';
					
						$this->CC_Model->sentMail($requestData['email'], $subject, $message);
						$message 	= 'Successfully Registered. Kindly check your inbox for account activation details.';
						$status 	= '1';
						$jsonData['registration_details'] = $requestData;
					}else{
						$message 	= 'Try Later.';
						$status 	= '0';
					}
					$jsonArray = array("status"=>$status, "message"=>$message, 'result' => $jsonData);
				}
			}
		}else{
			$jsonArray = array("status"=>'0', "message"=>'invalid request', 'result' => []);
		}
		echo json_encode($jsonArray);
	}

	public function email_validation(){
		if ($this->input->post()) {
			$post['email'] 	= $this->input->post('email');
			$post['id'] 	= '';
	        $result 		= $this->Users_Model->emailvalidation($post);
	        if ($result == 'true') {
	        	$jsonArray = array("status"=>'1', "message"=>'Email Verified.', 'result' => $post);
	        }else{
	        	$jsonArray = array("status"=>'0', "message"=>'Email already exists.', 'result' => $post);
	        }
		}else{
			$jsonArray = array("status"=>'0', "message"=>'invalid request', 'result' => []);
		}
		echo json_encode($jsonArray);
    }

    public function forgotpassword_plumber(){
    	if ($this->input->post()) {
    		$requestData['email'] 	= $this->input->post('email');
			$requestData['type'] 	= ['0' => '3'];
			$data 					= $this->Users_Model->forgotPassword($requestData);
			if($data=='1'){
				$message 	= 'Please check your email inbox and follow the steps, as instructed, to reset your password.';
				$status 	= '1';
			}elseif($data=='3'){
				$message 	= 'Incorrect Email ID.';
				$status 	= '0';
			}else{
				$message 	= 'Try Later.';
				$status 	= '0';
			}
			$jsonArray = array("status"=>$status, "message"=>$message, 'result' => $requestData);
    	}else{
			$jsonArray = array("status"=>'0', "message"=>'invalid request', 'result' => []);
    	}
    	echo json_encode($jsonArray);
    }

    public function forgotpassword_auditor(){
    	if ($this->input->post()) {
    		$requestData['email'] 	= $this->input->post('email');
			$requestData['type'] 	= ['0' => '5'];
			$data 					= $this->Users_Model->forgotPassword($requestData);
			if($data=='1'){
				$message 	= 'Please check your email inbox and follow the steps, as instructed, to reset your password.';
				$status 	= '1';
			}elseif($data=='3'){
				$message 	= 'Incorrect Email ID.';
				$status 	= '0';
			}else{
				$message 	= 'Try Later.';
				$status 	= '0';
			}
			$jsonArray = array("status"=>$status, "message"=>$message, 'result' => $requestData);
    	}else{
			$jsonArray = array("status"=>'0', "message"=>'invalid request', 'result' => []);
    	}
    	echo json_encode($jsonArray);
    }

    public function plumberprofile_api(){
    	if ($this->input->post() && $this->input->post('user_id')) {
    		$id 				= $this->input->post('user_id');
    		$result 			= $this->Plumber_Model->getList('row', ['id' => $id, 'type' => '3', 'status' => ['1', '2']], ['users', 'usersdetail', 'usersplumber', 'usersskills', 'company', 'physicaladdress', 'postaladdress', 'billingaddress']);
    		$specialisations 	= explode(',', $result['specialisations']);

    		// Physical address
			$physicaladdress 		= isset($result['physicaladdress']) ? explode('@-@', $result['physicaladdress']) : [];
			$jsonData['physical']['addressid1'] 	= isset($physicaladdress[0]) ? $physicaladdress[0] : '';
			$jsonData['physical']['address1']		= isset($physicaladdress[2]) ? $physicaladdress[2] : '';
			$jsonData['physical']['suburb1'] 		= isset($physicaladdress[3]) ? $physicaladdress[3] : '';
			$jsonData['physical']['city1'] 			= isset($physicaladdress[4]) ? $physicaladdress[4] : '';
			$jsonData['physical']['province1'] 		= isset($physicaladdress[5]) ? $physicaladdress[5] : '';
			$jsonData['physical']['postalcode1'] 	= isset($physicaladdress[6]) ? $physicaladdress[6] : '';
			$jsonData['physical']['type'] 			= isset($physicaladdress[6]) ? $physicaladdress[7] : '';
			$jsonData['physical']['id'] 			= isset($physicaladdress[6]) ? $physicaladdress[0] : '';

			// Postal address
			$postaladdress 			= isset($result['postaladdress']) ? explode('@-@', $result['postaladdress']) : [];
			$jsonData['postal']['addressid2'] 		= isset($postaladdress[0]) ? $postaladdress[0] : '';
			$jsonData['postal']['address2']			= isset($postaladdress[2]) ? $postaladdress[2] : '';
			$jsonData['postal']['suburb2'] 			= isset($postaladdress[3]) ? $postaladdress[3] : '';
			$jsonData['postal']['city2'] 			= isset($postaladdress[4]) ? $postaladdress[4] : '';
			$jsonData['postal']['province2'] 		= isset($postaladdress[5]) ? $postaladdress[5] : '';
			$jsonData['postal']['postalcode2'] 		= isset($postaladdress[6]) ? $postaladdress[6] : '';
			$jsonData['postal']['type'] 			= isset($postaladdress[6]) ? $postaladdress[7] : '';
			$jsonData['postal']['id'] 				= isset($postaladdress[6]) ? $postaladdress[0] : '';

			// Billing address
			$billingaddress 		= isset($result['billingaddress']) ? explode('@-@', $result['billingaddress']) : [];
			$jsonData['billing']['addressid3'] 		= isset($billingaddress[0]) ? $billingaddress[0] : '';
			$jsonData['billing']['address3']		= isset($billingaddress[2]) ? $billingaddress[2] : '';
			$jsonData['billing']['suburb3'] 		= isset($billingaddress[3]) ? $billingaddress[3] : '';
			$jsonData['billing']['city3'] 			= isset($billingaddress[4]) ? $billingaddress[4] : '';
			$jsonData['billing']['province3'] 		= isset($billingaddress[5]) ? $billingaddress[5] : '';
			$jsonData['billing']['postalcode3'] 	= isset($billingaddress[6]) ? $billingaddress[6] : '';
			$jsonData['billing']['type'] 			= isset($billingaddress[6]) ? $billingaddress[7] : '';
			$jsonData['billing']['id'] 				= isset($billingaddress[6]) ? $billingaddress[0] : '';

			$jsonData['plumber_result'] 			= $result;
			$jsonData['plumber_designation'] 		= $this->config->item('designation2')[$result['designation']];
			$jsonData['plumber_gender'] 			= $this->config->item('gender')[$result['gender']];
			$jsonData['plumber_racial'] 			= $this->config->item('racial')[$result['racial']];
			$jsonData['plumber_homelanguage'] 		= $this->config->item('homelanguage')[$result['homelanguage']];
			$jsonData['plumber_citizen'] 			= $this->config->item('citizen')[$result['citizen']];

			if ($result['disability'] =='0' || $result['disability'] =='') {
				$jsonData['plumber_disability'] 		= 'None';
			}else{
				$jsonData['plumber_disability'] 		= $this->config->item('disability')[$result['disability']];
			}
			if ($result['coc_electronic'] =='0' || $result['coc_electronic'] =='') {
				$coc_electronic = '2';
				$jsonData['plumber_coc_electronic'] 		= $this->config->item('yesno')[$coc_electronic];
			}else{
				$jsonData['plumber_coc_electronic'] 		= $this->config->item('yesno')[$result['coc_electronic']];
			}
			if ($result['nationality'] =='0' || $result['nationality'] =='2' || $result['nationality'] =='') {
				$nationality = '2';
				$jsonData['plumber_nationality'] 		= $this->config->item('yesno')[$nationality];
			}else{
				$jsonData['plumber_nationality'] 		= $this->config->item('yesno')[$result['nationality']];
			}

			if ($result['plumberstatus'] !='') {
				$jsonData['plumber_status'] 	= $this->config->item('plumberstatus')[$result['plumberstatus']];
			}else{
				$jsonData['plumber_status'] 	= '';
			}
			if ($result['title'] !='') {
				$jsonData['plumber_title'] 		= $this->config->item('titlesign')[$result['title']];
			}else{
				$jsonData['plumber_title'] 		= '';
			}
			
			foreach ($specialisations as $key => $specialisationsvalue) {
				if (!empty($specialisationsvalue)) {
					$jsonData['plumber_specialisations'][] 		= $this->config->item('specialisations')[$specialisationsvalue];
				}else{
					$jsonData['plumber_specialisations'][] 		= '';
				}
			}

			if ($result['file1'] !='') {
				$jsonData['plumber_identity_doc'][] = base_url().'assets/uploads/plumber/'.$id.'/'.$result['file1'];
			}else{
				$jsonData['plumber_identity_doc'][] = '';
			}
			if ($result['file2'] !='') {
				$jsonData['plumber_photoid'][] = base_url().'assets/uploads/plumber/'.$id.'/'.$result['file2'];
			}else{
				$jsonData['plumber_photoid'][] = '';
			}

			$jsonArray = array("status"=>'1', "message"=>'Plumber Registration Details', 'result' => $jsonData);
    	}else{
    		$jsonArray = array("status"=>'0', "message"=>'invalid request', 'result' => []);
    	}
    	echo json_encode($jsonArray);
    }

    // Plumber profile action:
    public function plumber_profile_action(){
		if ($this->input->post() && $this->input->post('user_id')) {
			// Physical address
			$this->form_validation->set_rules('address[1][address]','Physical Address ','trim|required');
			$this->form_validation->set_rules('address[1][province]','Physical Province ','trim|required');
			$this->form_validation->set_rules('address[1][city]','Physical City ','trim|required');
			$this->form_validation->set_rules('address[1][suburb]','Physical Suburb ','trim|required');

			// Postal address
			$this->form_validation->set_rules('address[2][address]','Postal Address','trim|required');
			$this->form_validation->set_rules('address[2][province]','Postal Province ','trim|required');
			$this->form_validation->set_rules('address[2][city]','Postal City ','trim|required');
			$this->form_validation->set_rules('address[2][suburb]','Postal Suburb ','trim|required');
			$this->form_validation->set_rules('address[2][postal_code]','Postal Suburb ','trim|required');

			// Billing details
			$this->form_validation->set_rules('company_name','Billing name','trim|required');
			$this->form_validation->set_rules('billing_email','Billing email','trim|required');
			$this->form_validation->set_rules('billing_contact','Billing Contact','trim|required');
			// Billing address
			$this->form_validation->set_rules('address[3][address]','Billing Address','trim|required');
			$this->form_validation->set_rules('address[3][province]','Billing Province','trim|required');
			$this->form_validation->set_rules('address[3][city]','Billing City','trim|required');
			$this->form_validation->set_rules('address[3][suburb]','Billing Suburb','trim|required');
			$this->form_validation->set_rules('address[3][postal_code]','Postal code','trim|required');	

			if ($this->form_validation->run()==FALSE) {
				$errorMsg =  validation_errors();
				$jsonArray = array("status"=>'0', "message"=>$errorMsg, 'result' => []);
			}else{
				$post 				= $this->input->post();
				$plumberID 			= $this->input->post('user_id');

				$userdata			= $this->Plumber_Model->getList('row', ['id' => $plumberID, 'type' => '3', 'status' => ['1', '2']], ['users', 'usersdetail', 'usersplumber', 'usersskills', 'company', 'physicaladdress', 'postaladdress', 'billingaddress']);
				$post['user_id']	 	= 	$plumberID;
				$post['usersdetailid'] 	= 	$userdata['usersdetailid'];
				$post['usersplumberid'] = 	$userdata['usersplumberid'];

				if ((isset($post['address'][1]['id']) && $post['address'][1]['id'] !='') && (isset($post['address'][1]['type']) && $post['address'][1]['type'] !='')) {
					$post['address'][1]['id'] 	= $post['address'][1]['id'];
					$post['address'][1]['type'] = $post['address'][1]['type'];
				}else{
					$post['address'][1]['id'] 	= '';
					$post['address'][1]['type'] = '';
				}

				if ((isset($post['address'][2]['id']) && $post['address'][2]['id'] !='') && (isset($post['address'][2]['type']) && $post['address'][2]['type'] !='')) {
					$post['address'][2]['id'] 	= $post['address'][2]['id'];
					$post['address'][2]['type'] = $post['address'][2]['type'];
				}else{
					$post['address'][2]['id'] 	= '';
					$post['address'][2]['type'] = '';
				}

				if ((isset($post['address'][3]['id']) && $post['address'][3]['id'] !='') && (isset($post['address'][3]['type']) && $post['address'][3]['type'] !='')) {
					$post['address'][3]['id'] 	= $post['address'][3]['id'];
					$post['address'][3]['type'] = $post['address'][3]['type'];
				}else{
					$post['address'][3]['id'] 	= '';
					$post['address'][3]['type'] = '';
				}
				$data 				=  	$this->Plumber_Model->action($post);

				if ($data) {
					$jsonData['userdata'] = $userdata;
					$jsonArray = array("status"=>'1', "message"=>'Profile Updated Successfully', "result"=>$post);
				}else{
					$jsonArray = array("status"=>'0', "message"=>'Something went wrong Please try again!!!', 'result' => []);
				}
			}	

		}else{

			$jsonArray = array("status"=>'0', "message"=>'invalid request', 'result' => []);
		}
		echo json_encode($jsonArray);
	}

    public function plumber_registration_index(){
    	$jsonData = [];

		$jsonData['page_lables'] = [ 'headertabs' => 'Welcome', 'Personal Details', 'Billing Details', 'Employement Details', 'Designation', 'Declaration', 'buttons' => 'Previous', 'Next', 'welcome' => 'Registered Plumber Details
', 'Donec augue enim, volutpat at ligula et, dictum laoreet sapien. Sed maximus feugiat tincidunt. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla eu mollis leo, eu elementum nisl. Curabitur cursus turpis nibh, egestas efficitur diam tristique non. Proin faucibus erat ligula, nec interdum odio rhoncus vel. Nulla facilisi. Nulla vehicula felis lorem, sed molestie lacus maximus quis. Mauris dolor enim, fringilla ut porta sed, ullamcorper id quam. Integer in eleifend justo, quis cursus odio. Pellentesque fermentum sapien elit, aliquam rhoncus neque semper in. Duis id consequat nisl, vitae semper elit. Nulla tristique lorem sem, et pretium magna cursus sit amet. Maecenas malesuada fermentum mauris, at vestibulum arcu vulputate a.', 'personaldetails' => 'Registered Plumber Details', 'Title *', 'Date of Birth *', 'Name *', 'Surname *', 'Gender *', 'Racial Status *', 'South African National *', 'ID Number', 'Home Language *', 'Disability *', 'Citizen Residential Status *', 'Identity Document *', 'Photo ID *', 'Photos must be no more than 6 months old Photos must be high quality Photos must be in colour Photos must have clear preferably white background Photos must be in sharp focus and clear Photo must be only of your head and shoulders You must be looking directly at the camera No sunglasses or hats File name is your NAME and SURNAME.', '(Image/File Size Smaller than 5mb)', 'Registration Card', 'Due to the high number of card returns and cost incurred, the registration fees do not include a registration card. Registration cards are available but must be requested separately. If the registration card option is selected you will be billed accordingly.', 'Registration Card Required *', 'Method of Delivery of Card *', 'Physical Address', 'Postal Address', 'Note: All delivery services will be sent to this address.', 'Note: All postal services will be sent to this address.', 'Physical Address *', 'Postal Address *', 'Province *', 'City *', 'Suburb *', 'Add city', 'Add suburb', 'Postal Code *', 'Contact Details', 'Home Phone:', 'Mobile Phone *', 'Note: All SMS and OTP notifications will be sent to this mobile number above.', 'Work Phone:', 'Secondary Mobile Phone', 'Email Address *', 'Secondary Email Address', 'Note: This email will be used as your user profile name and all emails notifications will be sent to it.', 'billingdetails' => 'Billing Details', 'All invoices generated, will be used this billing information.', 'Billing Name *', 'Company Reg Number', 'Company VAT Number', 'Billing Email *', 'Billing Contact *', 'Billing Address *', 'Province *', 'City *', 'Suburb *', 'Add city', 'Add suburb', 'Postal Code *', 'employementdetails' => 'Employment Details', 'Company Details', 'Your Employment Status', 'Company *', 'If the Company does not appear on this list please ask the company to register with the PIRB. Once they have been approved and registered, return to the list and select the company', 'designation' => 'Designation', '', 'Applications for Master Plumber and/or specialisations can only be done once your registration has been verified and approved. Please select the relevant designation being applied for.'
		];

    	if ($this->input->post() && $this->input->post('user_id')) {
    		$id = $this->input->post('user_id');
    		$result		= 	$this->Plumber_Model->getList('row', ['id' => $userid, 'status' => ['0','1']], ['users', 'usersdetail', 'usersplumber', 'usersskills', 'company', 'physicaladdress', 'postaladdress', 'billingaddress']);
    		// Physical address
			$physicaladdress 		= isset($result['physicaladdress']) ? explode('@-@', $result['physicaladdress']) : [];
			$jsonData['physical']['addressid1'] 	= isset($physicaladdress[0]) ? $physicaladdress[0] : '';
			$jsonData['physical']['address1']		= isset($physicaladdress[2]) ? $physicaladdress[2] : '';
			$jsonData['physical']['suburb1'] 		= isset($physicaladdress[3]) ? $physicaladdress[3] : '';
			$jsonData['physical']['city1'] 			= isset($physicaladdress[4]) ? $physicaladdress[4] : '';
			$jsonData['physical']['province1'] 		= isset($physicaladdress[5]) ? $physicaladdress[5] : '';
			$jsonData['physical']['postalcode1'] 	= isset($physicaladdress[6]) ? $physicaladdress[6] : '';

			// Postal address
			$postaladdress 			= isset($result['postaladdress']) ? explode('@-@', $result['postaladdress']) : [];
			$jsonData['postal']['addressid2'] 		= isset($postaladdress[0]) ? $postaladdress[0] : '';
			$jsonData['postal']['address2']			= isset($postaladdress[2]) ? $postaladdress[2] : '';
			$jsonData['postal']['suburb2'] 			= isset($postaladdress[3]) ? $postaladdress[3] : '';
			$jsonData['postal']['city2'] 			= isset($postaladdress[4]) ? $postaladdress[4] : '';
			$jsonData['postal']['province2'] 		= isset($postaladdress[5]) ? $postaladdress[5] : '';
			$jsonData['postal']['postalcode2'] 		= isset($postaladdress[6]) ? $postaladdress[6] : '';

			// Billing address
			$billingaddress 		= isset($result['billingaddress']) ? explode('@-@', $result['billingaddress']) : [];
			$jsonData['billing']['addressid3'] 		= isset($billingaddress[0]) ? $billingaddress[0] : '';
			$jsonData['billing']['address3']		= isset($billingaddress[2]) ? $billingaddress[2] : '';
			$jsonData['billing']['suburb3'] 		= isset($billingaddress[3]) ? $billingaddress[3] : '';
			$jsonData['billing']['city3'] 			= isset($billingaddress[4]) ? $billingaddress[4] : '';
			$jsonData['billing']['province3'] 		= isset($billingaddress[5]) ? $billingaddress[5] : '';
			$jsonData['billing']['postalcode3'] 	= isset($billingaddress[6]) ? $billingaddress[6] : '';
			$jsonData['plumber_result'] 			= $result;

			if ($result['file1'] !='') {
				$jsonData['plumber_identity_doc'][] = base_url().'assets/uploads/plumber/'.$id.'/'.$result['file1'];
			}else{
				$jsonData['plumber_identity_doc'][] = '';
			}
			if ($result['file2'] !='') {
				$jsonData['plumber_photoid'][] = base_url().'assets/uploads/plumber/'.$id.'/'.$result['file2'];
			}else{
				$jsonData['plumber_photoid'][] = '';
			}

    		$jsonArray = array("status"=>'1', "message"=>'Plumber Registration Details', 'result' => $jsonData);
    	}else{
    		$jsonArray = array("status"=>'0', "message"=>'invalid request', 'result' => []);
    	}
    	echo json_encode($jsonArray);
    }

	// Plumber Registration save:
    public function plumber_registration_save(){
		$jsonData = [];

		$jsonData['page_lables'] = [ 'headertabs' => 'Welcome', 'Personal Details', 'Billing Details', 'Employement Details', 'Designation', 'Declaration', 'buttons' => 'Previous', 'Next', 'welcome' => 'Registered Plumber Details
', 'Donec augue enim, volutpat at ligula et, dictum laoreet sapien. Sed maximus feugiat tincidunt. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla eu mollis leo, eu elementum nisl. Curabitur cursus turpis nibh, egestas efficitur diam tristique non. Proin faucibus erat ligula, nec interdum odio rhoncus vel. Nulla facilisi. Nulla vehicula felis lorem, sed molestie lacus maximus quis. Mauris dolor enim, fringilla ut porta sed, ullamcorper id quam. Integer in eleifend justo, quis cursus odio. Pellentesque fermentum sapien elit, aliquam rhoncus neque semper in. Duis id consequat nisl, vitae semper elit. Nulla tristique lorem sem, et pretium magna cursus sit amet. Maecenas malesuada fermentum mauris, at vestibulum arcu vulputate a.', 'personaldetails' => 'Registered Plumber Details', 'Title *', 'Date of Birth *', 'Name *', 'Surname *', 'Gender *', 'Racial Status *', 'South African National *', 'ID Number', 'Home Language *', 'Disability *', 'Citizen Residential Status *', 'Identity Document *', 'Photo ID *', 'Photos must be no more than 6 months old Photos must be high quality Photos must be in colour Photos must have clear preferably white background Photos must be in sharp focus and clear Photo must be only of your head and shoulders You must be looking directly at the camera No sunglasses or hats File name is your NAME and SURNAME.', '(Image/File Size Smaller than 5mb)', 'Registration Card', 'Due to the high number of card returns and cost incurred, the registration fees do not include a registration card. Registration cards are available but must be requested separately. If the registration card option is selected you will be billed accordingly.', 'Registration Card Required *', 'Method of Delivery of Card *', 'Physical Address', 'Postal Address', 'Note: All delivery services will be sent to this address.', 'Note: All postal services will be sent to this address.', 'Physical Address *', 'Postal Address *', 'Province *', 'City *', 'Suburb *', 'Add city', 'Add suburb', 'Postal Code *', 'Contact Details', 'Home Phone:', 'Mobile Phone *', 'Note: All SMS and OTP notifications will be sent to this mobile number above.', 'Work Phone:', 'Secondary Mobile Phone', 'Email Address *', 'Secondary Email Address', 'Note: This email will be used as your user profile name and all emails notifications will be sent to it.', 'billingdetails' => 'Billing Details', 'All invoices generated, will be used this billing information.', 'Billing Name *', 'Company Reg Number', 'Company VAT Number', 'Billing Email *', 'Billing Contact *', 'Billing Address *', 'Province *', 'City *', 'Suburb *', 'Add city', 'Add suburb', 'Postal Code *', 'employementdetails' => 'Employment Details', 'Company Details', 'Your Employment Status', 'Company *', 'If the Company does not appear on this list please ask the company to register with the PIRB. Once they have been approved and registered, return to the list and select the company', 'designation' => 'Designation', '', 'Applications for Master Plumber and/or specialisations can only be done once your registration has been verified and approved. Please select the relevant designation being applied for.'
		];

		if ($this->input->post()) {
				$post 				= $this->input->post();
				$plumberID 			= $this->input->post('user_id');

				$userdata			= $this->Plumber_Model->getList('row', ['id' => $plumberID, 'status' => ['0','1']], ['users', 'usersdetail', 'usersplumber', 'usersskills', 'company', 'physicaladdress', 'postaladdress', 'billingaddress']);

		//print_r($userdata);die;
				$post['user_id']	 	= 	$plumberID;
				$post['usersdetailid'] 	= 	$userdata['usersdetailid'];
				$post['usersplumberid'] = 	$userdata['usersplumberid'];

				if ((isset($post['address'][1]['id']) && $post['address'][1]['id'] !='') && (isset($post['address'][1]['type']) && $post['address'][1]['type'] !='')) {
					$post['address'][1]['id'] 	= $post['address'][1]['id'];
					$post['address'][1]['type'] = $post['address'][2]['type'];
				}else{
					$post['address'][1]['id'] 	= '';
					$post['address'][1]['type'] = '';
				}

				if ((isset($post['address'][2]['id']) && $post['address'][2]['id'] !='') && (isset($post['address'][2]['type']) && $post['address'][2]['type'] !='')) {
					$post['address'][2]['id'] 	= $post['address'][2]['id'];
					$post['address'][2]['type'] = $post['address'][2]['type'];
				}else{
					$post['address'][2]['id'] 	= '';
					$post['address'][2]['type'] = '';
				}

				if ((isset($post['address'][3]['id']) && $post['address'][3]['id'] !='') && (isset($post['address'][3]['type']) && $post['address'][3]['type'] !='')) {
					
					$post['address'][3]['id'] 	= $post['address'][3]['id'];
					$post['address'][3]['type'] = $post['address'][3]['type'];
				}else{
					$post['address'][3]['id'] 	= '';
					$post['address'][3]['type'] = '';
				}

				// if (isset($post['skill_id'])) {
				// 	$post['skill_id'] = $post['skill_id'];
				// }else{
				// 	$post['skill_id'] = '';
				// }

				if (isset($post['image1']) && $post['image1'] != '') {
					$data = $this->fileupload(['files' => $post['image1'], 'file_name' => $post['image1_name'], 'user_id' => $plumberID, 'page' => 'plumber_reg']);
					$post['image1'] = $data[0];
				}
				if (isset($post['image2']) && $post['image2'] != '') {
					$data = $this->fileupload(['files' => $post['image2'], 'file_name' => $post['image2_name'], 'user_id' => $plumberID, 'page' => 'plumber_reg']);
					$post['image2'] = $data[0];
				}
				$data 				=  	$this->Plumber_Model->action($post);

				if ($data) {
					$jsonData['userdata'] = $userdata;
					$jsonArray = array("status"=>'1', "message"=>'Form Saved Successfully', "result"=>$post);
				}else{
					$jsonArray = array("status"=>'0', "message"=>'Something went wrong Please try again!!!', 'result' => []);
				}

		}else{

			$jsonArray = array("status"=>'0', "message"=>'invalid request', 'result' => []);
		}
		echo json_encode($jsonArray);
	}

	// Plumber Registration:

	public function plumber_registration(){
		$jsonData = [];

		$jsonData['page_lables'] = [ 'headertabs' => 'Welcome', 'Personal Details', 'Billing Details', 'Employement Details', 'Designation', 'Declaration', 'buttons' => 'Previous', 'Next', 'welcome' => 'Registered Plumber Details
', 'Donec augue enim, volutpat at ligula et, dictum laoreet sapien. Sed maximus feugiat tincidunt. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla eu mollis leo, eu elementum nisl. Curabitur cursus turpis nibh, egestas efficitur diam tristique non. Proin faucibus erat ligula, nec interdum odio rhoncus vel. Nulla facilisi. Nulla vehicula felis lorem, sed molestie lacus maximus quis. Mauris dolor enim, fringilla ut porta sed, ullamcorper id quam. Integer in eleifend justo, quis cursus odio. Pellentesque fermentum sapien elit, aliquam rhoncus neque semper in. Duis id consequat nisl, vitae semper elit. Nulla tristique lorem sem, et pretium magna cursus sit amet. Maecenas malesuada fermentum mauris, at vestibulum arcu vulputate a.', 'personaldetails' => 'Registered Plumber Details', 'Title *', 'Date of Birth *', 'Name *', 'Surname *', 'Gender *', 'Racial Status *', 'South African National *', 'ID Number', 'Home Language *', 'Disability *', 'Citizen Residential Status *', 'Identity Document *', 'Photo ID *', 'Photos must be no more than 6 months old Photos must be high quality Photos must be in colour Photos must have clear preferably white background Photos must be in sharp focus and clear Photo must be only of your head and shoulders You must be looking directly at the camera No sunglasses or hats File name is your NAME and SURNAME.', '(Image/File Size Smaller than 5mb)', 'Registration Card', 'Due to the high number of card returns and cost incurred, the registration fees do not include a registration card. Registration cards are available but must be requested separately. If the registration card option is selected you will be billed accordingly.', 'Registration Card Required *', 'Method of Delivery of Card *', 'Physical Address', 'Postal Address', 'Note: All delivery services will be sent to this address.', 'Note: All postal services will be sent to this address.', 'Physical Address *', 'Postal Address *', 'Province *', 'City *', 'Suburb *', 'Add city', 'Add suburb', 'Postal Code *', 'Contact Details', 'Home Phone:', 'Mobile Phone *', 'Note: All SMS and OTP notifications will be sent to this mobile number above.', 'Work Phone:', 'Secondary Mobile Phone', 'Email Address *', 'Secondary Email Address', 'Note: This email will be used as your user profile name and all emails notifications will be sent to it.', 'billingdetails' => 'Billing Details', 'All invoices generated, will be used this billing information.', 'Billing Name *', 'Company Reg Number', 'Company VAT Number', 'Billing Email *', 'Billing Contact *', 'Billing Address *', 'Province *', 'City *', 'Suburb *', 'Add city', 'Add suburb', 'Postal Code *', 'employementdetails' => 'Employment Details', 'Company Details', 'Your Employment Status', 'Company *', 'If the Company does not appear on this list please ask the company to register with the PIRB. Once they have been approved and registered, return to the list and select the company', 'designation' => 'Designation', '', 'Applications for Master Plumber and/or specialisations can only be done once your registration has been verified and approved. Please select the relevant designation being applied for.'
		];

		if ($this->input->post()) {
			$this->form_validation->set_rules('name','First Name','trim|required');
			$this->form_validation->set_rules('surname','Second Name','trim|required');
			$this->form_validation->set_rules('citizen','Citizen Residential','trim|required');
			$this->form_validation->set_rules('address[2][postal_code]','postal code','trim|required');
			$this->form_validation->set_rules('homelanguage','home language','trim|required');
			$this->form_validation->set_rules('racial','Racial Status','trim|required');
			// Physical address
			$this->form_validation->set_rules('address[1][address]','Physical Address ','trim|required');
			$this->form_validation->set_rules('address[1][province]','Physical Province ','trim|required');
			$this->form_validation->set_rules('address[1][city]','Physical City ','trim|required');
			$this->form_validation->set_rules('address[1][suburb]','Physical Suburb ','trim|required');

			$this->form_validation->set_rules('mobile_phone','Mobile phone','trim|required');
			// $this->form_validation->set_rules('company_name','Company name','trim|required');
			// $this->form_validation->set_rules('billing_email','Billing email','trim|required');
			
			// Postal address
			$this->form_validation->set_rules('address[2][address]','Postal Address','trim|required');
			$this->form_validation->set_rules('address[2][province]','Postal Province ','trim|required');
			$this->form_validation->set_rules('address[2][city]','Postal City ','trim|required');
			$this->form_validation->set_rules('address[2][suburb]','Postal Suburb ','trim|required');

			// Billing details
			$this->form_validation->set_rules('company_name','Billing name','trim|required');
			$this->form_validation->set_rules('billing_email','Billing email','trim|required');
			$this->form_validation->set_rules('billing_contact','Billing Contact','trim|required');
			// Billing address
			$this->form_validation->set_rules('address[3][address]','Billing Address','trim|required');
			$this->form_validation->set_rules('address[3][province]','Billing Province','trim|required');
			$this->form_validation->set_rules('address[3][city]','Billing City','trim|required');
			$this->form_validation->set_rules('address[3][suburb]','Billing Suburb','trim|required');

			$this->form_validation->set_rules('address[3][postal_code]','Postal code','trim|required');	
			$this->form_validation->set_rules('image1','Identity Document','trim|required');
			$this->form_validation->set_rules('image2','Photo ID','trim|required');
			$this->form_validation->set_rules('designation','designation','trim|required');

			// Declaration:
			$this->form_validation->set_rules('registerprocedure','The Registered Procedure','trim|required');
			$this->form_validation->set_rules('acknowledgement','Acknowledgement','trim|required');
			$this->form_validation->set_rules('codeofconduct','PIRBs Code of Conduct','trim|required');

			if ($this->form_validation->run()==FALSE) {
				$errorMsg =  validation_errors();
				$jsonArray = array("status"=>'0', "message"=>$errorMsg, 'result' => []);
			}else{

				$post 				= $this->input->post();
				$plumberID 			= $this->input->post('user_id');

				$userdata			= $this->Plumber_Model->getList('row', ['id' => $plumberID, 'status' => ['0','1']], ['users', 'usersdetail', 'usersplumber', 'usersskills', 'company', 'physicaladdress', 'postaladdress', 'billingaddress']);

		//print_r($userdata);die;
				$post['user_id']	 	= 	$plumberID;
				$post['formstatus'] 	= 	'1';
				$post['status'] 		= 	'1';
				$post['usersdetailid'] 	= 	$userdata['usersdetailid'];
				$post['usersplumberid'] = 	$userdata['usersplumberid'];

				if ((isset($post['address'][1]['id']) && $post['address'][1]['id'] !='') && (isset($post['address'][1]['type']) && $post['address'][1]['type'] !='')) {
					$post['address'][1]['id'] 	= $post['address'][1]['id'];
					$post['address'][1]['type'] = $post['address'][2]['type'];
				}else{
					$post['address'][1]['id'] 	= '';
					$post['address'][1]['type'] = '';
				}

				if ((isset($post['address'][2]['id']) && $post['address'][2]['id'] !='') && (isset($post['address'][2]['type']) && $post['address'][2]['type'] !='')) {
					$post['address'][2]['id'] 	= $post['address'][2]['id'];
					$post['address'][2]['type'] = $post['address'][2]['type'];
				}else{
					$post['address'][2]['id'] 	= '';
					$post['address'][2]['type'] = '';
				}

				if ((isset($post['address'][3]['id']) && $post['address'][3]['id'] !='') && (isset($post['address'][3]['type']) && $post['address'][3]['type'] !='')) {
					
					$post['address'][3]['id'] 	= $post['address'][3]['id'];
					$post['address'][3]['type'] = $post['address'][3]['type'];
				}else{
					$post['address'][3]['id'] 	= '';
					$post['address'][3]['type'] = '';
				}

				// if (isset($post['skill_id'])) {
				// 	$post['skill_id'] = $post['skill_id'];
				// }else{
				// 	$post['skill_id'] = '';
				// }

				if (isset($post['image1']) && $post['image1'] != '') {
					$data = $this->fileupload(['files' => $post['image1'], 'file_name' => $post['image1_name'], 'user_id' => $plumberID, 'page' => 'plumber_reg']);
					$post['image1'] = $data[0];
				}
				if (isset($post['image2']) && $post['image2'] != '') {
					$data = $this->fileupload(['files' => $post['image2'], 'file_name' => $post['image2_name'], 'user_id' => $plumberID, 'page' => 'plumber_reg']);
					$post['image2'] = $data[0];
				}
				$data 				=  	$this->Plumber_Model->action($post);

				if ($data) {
					$jsonData['userdata'] = $userdata;
					$jsonArray = array("status"=>'1', "message"=>'Registration Successfully', "result"=>$post);
				}else{
					$jsonArray = array("status"=>'0', "message"=>'Something went wrong Please try again!!!', 'result' => []);
				}
				
			}

		}else{

			$jsonArray = array("status"=>'0', "message"=>'invalid request', 'result' => []);
		}
		echo json_encode($jsonArray);
	}

	public function skill_api(){
		if ($this->input->post() && $this->input->post('user_id')) {

			if ($this->input->post('pagetype') =='insert') {
				$this->form_validation->set_rules('skill_date','Skill date','trim|required');
				$this->form_validation->set_rules('skill_certificate','Skill certificate','trim|required');
				$this->form_validation->set_rules('skill_qualification_type','Skill qualification','trim|required');
				$this->form_validation->set_rules('skill_route','Skill route','trim|required');
				$this->form_validation->set_rules('skill_training','Skill training','trim|required');
				$this->form_validation->set_rules('skill_attachment','Skill attachment','trim|required');

				if ($this->form_validation->run()==FALSE) {
					$errorMsg =  validation_errors();
					$jsonArray = array("status"=>'0', "message"=>$errorMsg, 'result' => []);
				}else{
					$post 			= $this->input->post();
					$plumberID 		= $this->input->post('user_id');
					if (isset($post['skill_attachment']) && $post['skill_attachment'] != '') {
						$data = $this->fileupload(['files' => $post['skill_attachment'], 'file_name' => $post['skill_attachment_name'], 'user_id' => $plumberID, 'page' => 'plumber_skill']);
						$post['skill_attachment'] = $data[0];
					}
					$result 			= $this->Plumber_Model->action($post);
				}
				$jsonArray = array("status"=>'1', "message"=>'Plumber Skill Inserted Successfully', 'result' => $post);

			}elseif($this->input->post('pagetype') =='edit_view' && $this->input->post('skillid')){
				$result = $this->Plumber_Model->getSkillList('row', ['id' => $this->input->post('skillid')]);
				$jsonData['skill_result'][] = $result;
				if ($result['attachment'] !='') {
					$jsonData['skill_attachment'][] = base_url().'assets/uploads/plumber/'.$this->input->post('user_id').'/'.$result['attachment'];
				}
				$jsonArray = array("status"=>'1', "message"=>'Plumber Skill', 'result' => $jsonData);

			}elseif($this->input->post('pagetype') =='edit' && $this->input->post('skillid')){

				$this->form_validation->set_rules('skill_date','Skill date','trim|required');
				$this->form_validation->set_rules('skill_certificate','Skill certificate','trim|required');
				$this->form_validation->set_rules('skill_qualification_type','Skill qualification','trim|required');
				$this->form_validation->set_rules('skill_route','Skill route','trim|required');
				$this->form_validation->set_rules('skill_training','Skill training','trim|required');
				$this->form_validation->set_rules('skill_attachment','Skill attachment','trim|required');

				if ($this->form_validation->run()==FALSE) {
					$errorMsg =  validation_errors();
					$jsonArray = array("status"=>'0', "message"=>$errorMsg, 'result' => []);
				}else{
					$post 			= $this->input->post();
					$plumberID 		= $this->input->post('user_id');
					if (isset($post['skill_attachment']) && $post['skill_attachment'] != '') {
						$data = $this->fileupload(['files' => $post['skill_attachment'], 'file_name' => $post['skill_attachment_name'], 'user_id' => $plumberID, 'page' => 'plumber_skill']);
						$post['skill_attachment'] = $data[0];
					}
					$post['user_id'] 	= $plumberID;
					$post['skill_id'] 	= $this->input->post('skillid');
					$result 			= $this->Plumber_Model->action($post);
				}
				$jsonArray = array("status"=>'1', "message"=>'Plumber Skill Updated Successfully', 'result' => $post);

			}elseif($this->input->post('pagetype') =='delete' && $this->input->post('skillid')){
				$result = $this->Plumber_Model->deleteSkillList($post['skillid']);
				$jsonArray = array("status"=>'1', "message"=>'Skill Deleted Successfully', 'result' => $result);

			}elseif($this->input->post('pagetype') =='list'){
				$results			= $this->Plumber_Model->getList('row', ['id' => $this->input->post('user_id')], ['usersskills']);
				$slillarrays = explode('@-@', $results['skills']);
				if ($slillarrays) {
					foreach ($slillarrays as $key => $value) {
						$skillarray = explode('@@@', $value);
						$jsonData['skills'][] = ['id' => $skillarray[0], 'userid' => $skillarray[1], 'date_qualification' => date('d-m-Y', strtotime($skillarray[2])), 'certificate_no' => $skillarray[3], 'qualification_type' => $skillarray[4], 'skill_route' => $skillarray[5], 'skill_route_name' => $skillarray[8], 'provider' => $skillarray[6], 'atachments' => base_url().'assets/uploads/plumber/'.$skillarray[1].'/'.$skillarray[7],
						];
					}
				}
				$jsonArray = array("status"=>'1', "message"=>'Skill list', 'result' => $jsonData);
			}else{
				$jsonArray = array("status"=>'0', "message"=>'invalid request', 'result' => []);
			}
		}else{
			$jsonArray = array("status"=>'0', "message"=>'invalid request', 'result' => []);
		}
		echo json_encode($jsonArray);
	}

	public function qualificationtype_api(){
		$jsonData['qualificationtype'][] = $this->config->item('qualificationtype');
		$jsonArray = array("status"=>'1', "message"=>'Qulification types', 'result' => $jsonData);
		echo json_encode($jsonArray);
	}
	public function getQualificationRouteList_api(){
		$data = $this->Qualificationroute_Model->getList('all', ['status' => ['1']]);
		
		if(count($data) > 0){
			foreach ($data as $key => $value) {
				$jsonData['qualificationroutes'][] = [ 'id' => $value['id'], 'name' => $value['name']
				];
			}
			$jsonArray = array("status"=>'1', "message"=>'Qualification Route Lists', 'result' => $jsonData);
		}else{
			$jsonArray = array("status"=>'0', "message"=>'No Qualification Route Found', 'result' => []);
		}
		echo json_encode($jsonArray);
	}
	public function homelanguage_api(){
		$jsonData['homelanguage'][] = $this->config->item('homelanguage');
		$jsonArray = array("status"=>'1', "message"=>'Home Language list', 'result' => $jsonData);
		echo json_encode($jsonArray);
	}
	public function racial_api(){
		$jsonData['racial'][] = $this->config->item('racial');
		$jsonArray = array("status"=>'1', "message"=>'Racial Status list', 'result' => $jsonData);
		echo json_encode($jsonArray);
	}
	public function yes_no_api(){
		$jsonData['racial'][] = $this->config->item('yesno');
		$jsonArray = array("status"=>'1', "message"=>'yesno', 'result' => $jsonData);
		echo json_encode($jsonArray);
	}
	public function workmanship_api(){
		$jsonData['workmanship'][] = $this->config->item('workmanship');
		$jsonArray = array("status"=>'1', "message"=>'Workmanship', 'result' => $jsonData);
		echo json_encode($jsonArray);
	}
	public function deliverycard_api(){
		$jsonData['deliverycard'][] = $this->config->item('deliverycard');
		$jsonArray = array("status"=>'1', "message"=>'deliverycard', 'result' => $jsonData);
		echo json_encode($jsonArray);
	}
	public function employmentdetail_api(){
		$jsonData['employmentdetail'][] = $this->config->item('employmentdetail');
		$jsonArray = array("status"=>'1', "message"=>'employmentdetail', 'result' => $jsonData);
		echo json_encode($jsonArray);
	}
	public function company_list_api(){
		$jsonData['company'][] = $this->getCompanyList();
		$jsonArray = array("status"=>'1', "message"=>'company list', 'result' => $jsonData);
		echo json_encode($jsonArray);
	}
	public function degisgnation_api(){
		$designation1 = $this->config->item('designation1');
		$plumberrates = $this->getPlumberRates();
		foreach($designation1 as $k => $design){
			$jsonData['designation_array'][] =  sprintf($design, $plumberrates[$k]);
		}
		$jsonArray = array("status"=>'1', "message"=>'designation list', 'result' => $jsonData);
		echo json_encode($jsonArray);
	}
	public function reg_declaration_api(){
		if ($this->input->post('registerprocedure')) {
			$jsonData['registerprocedure'] 	= $this->config->item('registerprocedure');
		}elseif($this->input->post('acknowledgement')){
			$jsonData['acknowledgement'] 	= $this->config->item('acknowledgement');
		}elseif($this->input->post('codeofconduct')){
			$jsonData['codeofconduct'] 		= $this->config->item('codeofconduct');
		}elseif($this->input->post('declaration')){
			$jsonData['declaration'] 		= $this->config->item('declaration');
		}
		$jsonArray = array("status"=>'1', "message"=>'Declaration Tab', 'result' => $jsonData);
		echo json_encode($jsonArray);
	}

	// Common Dashboard:

	public function dashoard(){

		if ($this->input->post('user_id')  && $this->input->post('type') == 'plumber') {
			$id 										= $this->input->post('user_id');
			$userdata 									= $this->getUserDetails($id);
			$userdetails 								= $this->Plumber_Model->getList('row', ['id' => $id], ['users', 'usersdetail', 'usersplumber', 'usersskills', 'company', 'physicaladdress', 'postaladdress', 'billingaddress']);

			$getcity = $this->Managearea_Model->getListCity('all', ['status' => ['1']]);
			if(count($getcity) > 0) {
				$citydata=  ['' => 'Select City']+array_column($getcity, 'name', 'id');
			}else{
				$citydata = [];
			}
			$getsuburb = $this->Managearea_Model->getListSuburb('all', ['status' => ['1']]);
			if(count($getsuburb) > 0) {
				$suburbdata=  ['' => 'Select City']+array_column($getsuburb, 'name', 'id');
			}
			else {
				$suburbdata = [];
			}

			// Physical address
			$physicaladdress 		= isset($userdetails['physicaladdress']) ? explode('@-@', $userdetails['physicaladdress']) : [];
			$addressid1 			= isset($physicaladdress[0]) ? $physicaladdress[0] : '';
			$address1				= isset($physicaladdress[2]) ? $physicaladdress[2] : '';
			$suburb1 				= isset($physicaladdress[3]) ? $suburbdata[$physicaladdress[3]] : '';
			$city1 					= isset($physicaladdress[4]) ? $citydata[$physicaladdress[4]] : '';
			$province1 				= isset($physicaladdress[5]) ? $this->getProvinceList()[$physicaladdress[5]] : '';
			$postalcode1 			= isset($physicaladdress[6]) ? $physicaladdress[6] : '';

			// Postal address
			$postaladdress 			= isset($result['postaladdress']) ? explode('@-@', $result['postaladdress']) : [];
			$addressid2 			= isset($postaladdress[0]) ? $postaladdress[0] : '';
			$address2				= isset($postaladdress[2]) ? $postaladdress[2] : '';
			$suburb2 				= isset($postaladdress[3]) ? $suburbdata[$postaladdress[3]] : '';
			$city2 					= isset($postaladdress[4]) ? $citydata[$postaladdress[4]] : '';
			$province2 				= isset($postaladdress[5]) ? $this->getProvinceList()[$postaladdress[5]] : '';
			$postalcode2 			= isset($postaladdress[6]) ? $postaladdress[6] : '';
			// Billing address
			$billingaddress 		= isset($userdetails['billingaddress']) ? explode('@-@', $userdetails['billingaddress']) : [];
			$addressid3 			= isset($billingaddress[0]) ? $billingaddress[0] : '';
			$address3				= isset($billingaddress[2]) ? $billingaddress[2] : '';
			$suburb3 				= isset($billingaddress[3]) ? $suburbdata[$billingaddress[3]] : '';
			$city3 					= isset($billingaddress[4]) ? $citydata[$billingaddress[4]] : '';
			$province3 				= isset($billingaddress[5]) ? $this->getProvinceList()[$billingaddress[5]] : '';
			$postalcode3 			= isset($billingaddress[6]) ? $billingaddress[6] : '';
			
			//$jsonData['id'] 								= $id;
			//$jsonData['userdata'] 						= $this->getUserDetails($id);
			$mycpd 								= $this->userperformancestatus(['performancestatus' => '1', 'auditorstatement' => '1', 'userid' => $id]);
			$nonlogcoc 							= $this->Coc_Model->getCOCList('count', ['user_id' => $id, 'coc_status' => ['4','5']]);
			$adminstock 			 			= $this->Coc_Ordermodel->getCocorderList('all', ['admin_status' => '0', 'userid' => $id]);
			$adminstock 						= array_sum(array_column($adminstock, 'quantity'));
			$coccount 							= $this->Coc_Model->COCcount(['user_id' => $id]);
			$coccount 							= $coccount['count'];
			$history 							= $this->Auditor_Model->getReviewHistoryCount(['plumberid' => $id]);
			$auditcoc 							= $history['total'];
			$auditrefixincomplete				= $history['refixincomplete'];
			$auditorratio						= $this->Auditor_Model->getAuditorRatio('row', ['userid' => $id]);
			$auditorratio 						= ($auditorratio) ? $auditorratio['audit'].'%' : '0%';
			// country rangking
			$overallperformancestatus 			= $this->userperformancestatus(['overall' => '1']);
			$myprovinceperformancestatus 		= $this->userperformancestatus(['province' => $physicaladdress[5]], $id);
			$performancestatus 					= $this->userperformancestatus(['userid' => $id]);
			$mycityperformancestatus 			= $this->userperformancestatus(['city' => $physicaladdress[4]], $id);
			$provinceperformancestatus 			= $this->userperformancestatus(['province' => $physicaladdress[5], 'limit' => '3']);

			$countryranking 	= $this->ranking(['id' => $id, 'type' => 'country']);
			$regionalranking 	= $this->ranking(['id' => $id, 'type' => 'province']);
			$countryrank  = 0;
			$regionalrank = 0;

			// country and industry
			foreach ($countryranking as $key1 => $user_country_ranking) {
				if ($user_country_ranking['userid'] == $id) {
					$countryrank = $key1+1;
				}
			}
			// province
			foreach ($regionalranking as $key1 => $user_province_ranking) {
				if ($user_province_ranking['userid'] == $id) {
					$regionalrank = $key1+1;
				}
			}

			// $jsonData['cityperformancestatus'] 			= $this->userperformancestatus(['city' => $userdata['city'], 'limit' => '3'],$id);

			if ($this->config->item('plumberstatus')[$userdetails['plumberstatus']] == 'Pending') {
				$jsonData = ['plumberstatus' => $this->config->item('plumberstatus')[$userdetails['plumberstatus']], 'pageresponse' => 'Your Applciation is Pending'];

			}elseif($this->config->item('plumberstatus')[$userdetails['plumberstatus']] == 'Active'){
				//$jsonData['plumber_contact_details'] 	= [];
				$jsonData['plumber_details']			= [];
				$jsonData['plumber_profile']			= [];
				$jsonData['plumber_designation']		= [];
				$jsonData['page_lables']				= [];

				//$jsonData['plumber_contact_details'] = ['email' => $userdata['email'], 'mobile1' => $userdetails['mobile_phone'], 'home_phone' => $userdetails['home_phone'], 'mobile2' => $userdetails['mobile_phone2'], 'work_phone' => $userdetails['work_phone'], 'email2' => $userdetails['email2'], 'physical_address' => ['province' => $province1, 'city' => $city1, 'suburb' => $suburb1, 'address' => $address1, 'postalcode' => $postalcode1], 'postal_address' => ['province' => $province2, 'city' => $city2, 'suburb' => $suburb2, 'address' => $address2, 'postalcode' => $postalcode2], 'billing_address' => ['province' => $province3, 'city' => $city3, 'suburb' => $suburb3, 'address' => $address3, 'postalcode' => $postalcode3]];

				$jsonData['page_lables'] = [ 'hellomsg' => 'Hello,', 'card' => 'PIRB registration card', 'country_rank' => 'My Country Ranking', 'perfomancscore' => 'My Performance Score', 'reginal_ranking' => 'My Regional Ranking', 'non_log' => 'non-logged', 'purchase' => 'Purchase CoC', 'cocstatement' => 'CoC Statement', 'audit_percent' => 'My Audits', 'rank_industry' => 'Industry Ranking', 'point' => 'Points', 'cpd' => 'My CPD', 'perfomancstatus' => 'Performance Status'
			];

			if (isset($userdata) && (count($userdata) > 0)) {
				$jsonData['plumber_details'] = [
					'plumberid' 		=> $userdata['id'],
					'renewaldate' 		=> date('d-m-Y', strtotime($userdata['expirydate'])),
					'renewaldate_milli' => $userdata['expirydate'],
					'name' 				=> $userdata['name'],
					'regno' 			=> $userdata['registration_no'],
					'status' 			=> $this->config->item('plumberstatus')[$userdetails['plumberstatus']],
					'nonlogcoc' 		=> $nonlogcoc,
					'adminstock' 		=> $adminstock,
					'employementstatus' => $this->config->item('employmentdetail')[$userdetails['employment_details']],
					'companyname' 		=> $userdetails['companyname'],
					'coccount' 			=> $coccount,
					'auditorreview' 	=> $history,
					'auditcoc' 			=> $auditcoc,
					'auditrefixincomplete' 	=> $auditrefixincomplete,
					'auditorratio' 		=> $auditorratio,
					'overallperformancestatus' 		=> $overallperformancestatus,
					'myprovinceperformancestatus' 	=> $regionalrank,
					'performancestatus' 			=> $performancestatus,
					'mycityperformancestatus' 		=> $mycityperformancestatus,
					'industryranking' 				=> $countryrank,
					'countryranking' 				=> $countryrank,
					'provinceperformancestatus' 	=> $provinceperformancestatus[0]['point'],
					'cpdpoints' 					=> $mycpd
					

				];
			}

			if ($userdata['file2'] !='') {
				$jsonData['plumber_profile'] = [
					'file' 		=> base_url().'assets/uploads/plumber/'.$id.'/'.$userdata['file2'] 
				];
			}else{
				$jsonData['plumber_profile'] = [
					'file' 		=> base_url().'assets/uploads/plumber/'.$id.'/'.$userdata['file2'] 
				];
					
			}
			if ($userdata['designation'] !='') {
				$jsonData['plumber_designation'] = [
					'designation' 		=> $this->config->item('designation2')[$userdata['designation']]
				];
			}else{
				$jsonData['plumber_designation'] = [
					'designation' 		=> $jsonData['userdata']['designation'] 
				];
			}

			}elseif($this->config->item('plumberstatus')[$userdetails['plumberstatus']] == 'CPD Suspention'){
				$jsonData = ['plumberstatus' => $this->config->item('plumberstatus')[$userdetails['plumberstatus']], 'pageresponse' => 'plumber has CPD Suspention'];
				
			}elseif($this->config->item('plumberstatus')[$userdetails['plumberstatus']] == 'Expired'){
				$jsonData = ['plumberstatus' => $this->config->item('plumberstatus')[$userdetails['plumberstatus']], 'pageresponse' => 'Plumber has Expired'];
				
			}elseif($this->config->item('plumberstatus')[$userdetails['plumberstatus']] == 'Deceased'){
				$jsonData = ['plumberstatus' => $this->config->item('plumberstatus')[$userdetails['plumberstatus']], 'pageresponse' => 'Plumber has Deceased'];
				
			}elseif($this->config->item('plumberstatus')[$userdetails['plumberstatus']] == 'Resigned'){
				$jsonData = ['plumberstatus' => $this->config->item('plumberstatus')[$userdetails['plumberstatus']], 'pageresponse' => 'Plumber has Resigned'];
			}
			elseif($this->config->item('plumberstatus')[$userdetails['plumberstatus']] == 'Suspended'){
				$jsonData = ['plumberstatus' => $this->config->item('plumberstatus')[$userdetails['plumberstatus']], 'pageresponse' => 'Plumber has Suspended'];
			}
			//print_r($jsonData);die;
			$jsonArray = array("status"=>'1', "message"=>'User details', "result"=>$jsonData);

		
		}elseif($this->input->post('user_id')  && $this->input->post('type') == 'auditor'){
			$id 				= $this->input->post('user_id');
			$userdata 			= $this->getUserDetails($id);
			$history			= $this->Auditor_Model->getReviewHistoryCount(['auditorid' => $id]);	
			$unread_chat		= $this->Chat_Model->getList('count',['viewed' => $id]);

			$jsonData['auditor_data'][] = ['id' => $userdata['id'], 'namesurname' => $userdata['name'], 'total' => $history['total'], 'noaudit' => $history['noaudit'], 'cautionary' => $history['cautionary'], 'refixincomplete' => $history['refixincomplete'], 'refixcomplete' => $history['refixcomplete'], 'compliment' => $history['compliment'], 'openaudits' => $history['openaudits'], 'unread_chat' => $unread_chat];
			$jsonArray = array("status"=>'1', "message"=>'Auditor Dashboard Details', "result"=>$jsonData);
		}else{

			$jsonArray = array("status"=>'0', "message"=>'invalid request', 'result' => []);
		}

		echo json_encode($jsonArray);
	}

	public function card(){

		if ($this->input->post() && $this->input->post('user_id')) {
			$userid 			= $this->input->post('user_id');
			$cardtype 			= $this->input->post('cardtype');
			$card 				= $this->plumbercard_api(['id' => $userid, 'type' => $cardtype]);
			$jsonData['card'] 	= $card;
			echo $jsonData['card'];die;
			$jsonArray = array("status"=>'1', "message"=>'Plumber PIRB registration card', 'result' => $jsonData);
		}else{
			$jsonArray = array("status"=>'0', "message"=>'invalid request', 'result' => []);
		}
		echo json_encode($jsonArray);
	}
	public function plumbercard_api($requestdata = []){

		$data['company'] 			= $this->getCompanyList();
		$data['designation2'] 		= $this->config->item('designation3');
		$data['specialisations'] 	= $this->config->item('specialisations');
		$data['settings'] 			= $this->Systemsettings_Model->getList('row');
		
		$data['result'] = $this->Plumber_Model->getList('row', ['id' => $requestdata['id']], ['users', 'usersdetail', 'usersplumber', 'company']);
		if (isset($requestdata['type']) && $requestdata['type'] == 'front') {
			return $this->load->view('api/card/card_front', $data, true) ;
		}elseif (isset($requestdata['type']) && $requestdata['type'] == 'back'){
			return $this->load->view('api/card/card_back', $data, true) ;
		}
		
	}

	// Purchase CoC:
	public function purchase_coc(){

		if ($this->input->post('user_id')) {
			$jsonData = [];
			$jsonData['plumber_purchase_details'] = [];
			
			$userid 					=	$this->input->post('user_id');
			$userdata					= 	$this->getUserDetails($userid);
			$userdata1					= 	$this->Plumber_Model->getList('row', ['id' => $userid], ['users', 'usersdetail', 'usersplumber']);
			$userdatacoc_count			= 	$this->Coc_Model->COCcount(['user_id' => $userid]);
			//$jsonData['userid']			= 	$userid;
			//$jsonData['userdata']		= 	$userdata;
			//$jsonData['userdata1']		= 	$userdata1;
			// $jsonData['coc_count']		= 	$userdatacoc_count['count'];

			$jsonData['deliverycard']	= 	$this->config->item('purchasecocdelivery');
			$jsonData['coctype']		= 	$this->config->item('coctype');
			$settings 					= 	$this->Systemsettings_Model->getList('row');
			$nonlogcoc	 				=	$this->Coc_Model->getCOCList('count', ['user_id' => $userid, 'coc_status' => ['4','5']]);
			$cocpaperwork 				=	$this->Rates_Model->getList('row', ['id' => $this->config->item('cocpaperwork')]);
			$cocelectronic 				=	$this->Rates_Model->getList('row', ['id' => $this->config->item('cocelectronic')]);
			$postage 					= 	$this->Rates_Model->getList('row', ['id' => $this->config->item('postage')]);
			$couriour 					= 	$this->Rates_Model->getList('row', ['id' => $this->config->item('couriour')]);
			$collectedbypirb 			= 	$this->Rates_Model->getList('row', ['id' => $this->config->item('collectedbypirb')]);
			$orderquantity 				= $this->Coc_Ordermodel->getCocorderList('all', ['admin_status' => '0', 'userid' => $userid]);
			
			$userorderstock 			= array_sum(array_column($orderquantity, 'quantity'));

			$jsonData['collectedbypirb']= 	['id' => $collectedbypirb['id'], 'supllyname' => $collectedbypirb['supplyitem'], 'amount' => $collectedbypirb['amount']];
			$jsonData['couriour']		= 	['id' => $couriour['id'], 'supllyname' => $couriour['supplyitem'], 'amount' => $couriour['amount']];
			$jsonData['postage']		= 	['id' => $postage['id'], 'supllyname' => $postage['supplyitem'], 'amount' => $postage['amount']];
			$jsonData['cocelectronic']	=	['id' => $cocelectronic['id'], 'supllyname' => $cocelectronic['supplyitem'], 'amount' => $cocelectronic['amount']];
			$jsonData['cocpaperwork']	=	['id' => $cocpaperwork['id'], 'supllyname' => $cocpaperwork['supplyitem'], 'amount' => $cocpaperwork['amount']];

			if ($userdata1['coc_electronic'] != '0') {
				$coc_electronic = '1';
			}else{
				$coc_electronic = '2';
			}
			$jsonData['plumber_purchase_details'] = ['plumberid' => $userdata1['id'],  'coc_purchase_limit' => $userdata1['coc_purchase_limit'], 'coc_purchase' => $userdatacoc_count['count'], 'nonlogcoc' => $nonlogcoc, 'adminallocated' => $userorderstock, 'coc_electronic' => $this->config->item('yesno')[$coc_electronic]
				];

			$jsonData['page_lables'] = [ 'mycoc' => 'My COC’s', "permitted" => "Total number COC’s your are permitted", "purchase" => "Number of Permitted COC's that you are able to purchase", "nonlogged" => "Number of non-logged COC’s","allocateadmin" => "Number of COC’s to be allocated by admin", "purchasecoc_heading" => "Purchase COC’s", "selectcoctype" => "Select type of COC’s you wish to purchase", "coctype1" => "Electronic","coctype2" => "Paper Based", "purchasecoc" => "Number of COC’s you wish to purchase", "typecost" => "Cost of COC Type", "vat" => "VAT @".$settings['vat_percentage']."%", "totaldue" => "Total Due", "currency" => $this->config->item('currency'), 'vatamt' => $settings['vat_percentage']
			];


			$jsonArray = array("status"=>'1', "message"=>'Plumber coc details', "result"=>$jsonData);

		}else{

			$jsonArray = array("status"=>'0', "message"=>'invalid request', 'result' => []);
		}

		echo json_encode($jsonArray);
	}

	public function purchase_coc_action(){

		if ($this->input->post('user_id') && $this->input->post('coc_count') && $this->input->post('coc_type') ) {
			$userid 					=	$this->input->post('user_id');
			$coc_count 					=	$this->input->post('coc_count');
			$coc_type 					=	$this->input->post('coc_type'); // 1- electronic , 2- paperbased
			$delivery_type 				=	$this->input->post('delivery_type'); // 1- collected by PIRB , 2- By courier, 3- registered post
			$userdata					= 	$this->getUserDetails($userid);
			$userdata1					= 	$this->Plumber_Model->getList('row', ['id' => $userid], ['users', 'usersdetail', 'usersplumber']);
			$userdatacoc_count			= 	$this->Coc_Model->COCcount(['user_id' => $userid]);
			$settings 					= 	$this->Systemsettings_Model->getList('row');

			if ($coc_count > $userdatacoc_count['count']) {
				if ($userdatacoc_count['count'] == '0') {
					$errormsg = 'Purchase limit has been exceeded. Contact our support for further assistance.';
				}else{
					$errormsg = 'You cannot purchase more than '.$userdatacoc_count['count'].' COCs. Contact our support for further assistance.';
				}
				$jsonData['plumber_purchase_details'] = ['plumberid' => $userdata1['id'], 'errormsg' => $errormsg
				];
				$jsonArray = array("status"=>'1', "message"=>'Purchase COC’s', 'result' => $jsonData);
			}else{

				if ($this->input->post('coc_type') == '1') {
					$cocelectronic 		=	$this->Rates_Model->getList('row', ['id' => $this->config->item('cocelectronic')]);
					$typecost 			= 	$cocelectronic['amount']*$coc_count;
					$deliveryamt 		= 	0;
				}else{
					$cocpaperwork 		=	$this->Rates_Model->getList('row', ['id' => $this->config->item('cocpaperwork')]);
					$typecost 			= 	$cocpaperwork['amount']*$coc_count;

					if ($delivery_type == '1') {
						$deliveryamt 	= 0;
					}elseif($delivery_type == '2'){
						$couriour 		= 	$this->Rates_Model->getList('row', ['id' => $this->config->item('couriour')]);
						$deliveryamt 	= 	$couriour['amount'];
					}elseif($delivery_type == '3'){
						$postage 		=	 $this->Rates_Model->getList('row', ['id' => $this->config->item('postage')]);
						$deliveryamt 	= 	 $postage['amount'];
					}

				}

				$vatcalculation = number_format(((($typecost+$deliveryamt)*$settings['vat_percentage'])/100), 2, '.', '');
				//$totaldue 		= ($typecost+$deliveryamt+$vatcalculation);
				$totaldue 		= $this->currencyconvertor($typecost+$deliveryamt+$vatcalculation);

				$jsonData['plumber_purchase_details'] = ['plumberid' => $userdata1['id'], 'costtypeofcoc' => number_format($typecost, 2, '.', ''), 'deliverycost' => number_format($deliveryamt, 2, '.', ''), 'totalvat' => number_format($vatcalculation, 2, '.', ''), 'totaldue' => number_format($totaldue, 2, '.', '')
					];
				$jsonArray = array("status"=>'1', "message"=>'Purchase COC’s', 'result' => $jsonData);
			}

		}else{
			$jsonArray = array("status"=>'0', "message"=>'invalid request', 'result' => []);
		}
		echo json_encode($jsonArray);
	}
	// public function currencyconvertor($amount){
	// 	$lastchar = substr($amount, -1);
	// 	if ($lastchar < 5) {
	// 		$appendvalue = '0';
	// 	}else{
	// 		$appendvalue = '5';
	// 	}
	// 	$slice = substr($amount, 0, -1);
	// 	$currency = $slice.$appendvalue;
	// 	return $currency;
	// }
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

	// CoC Statement:
	public function coc_statement(){

		if ($this->input->post('user_id') && $this->input->post('type') == 'list') {
			$jsonData = [];

			$userid 				= $this->input->post('user_id');

			$totalcount 			 = $this->Api_Model->getCOCList('count', ['user_id' => $userid, 'coc_status' => ['2','4','5','7']]);
			$results	 			= $this->Api_Model->getCOCList('all', ['user_id' => $userid, 'coc_status' => ['2','4','5','7'], 'api_data' => 'plumber_coc_statement_api']);
			
			foreach ($results as $key => $value) {
				if ( $this->config->item('cocstatus')[$value['coc_status']] == 'Logged') {
					$colorcode = '#7f694f';
					$coc_status = 'Logged';
				}else{
					$colorcode 	= '#ade33d';
					$coc_status = 'Un Logged';
				}
				$jsonData['coc_statement'][] = [ 'coc_number' => $value['id'], 'plumberid' => $value['user_id'], 'coc_status' =>  $coc_status, 'coc_type' => $this->config->item('coctype')[$value['type']], 'cl_name' => $value['cl_name'], 'colorcode' => $colorcode, 'totalcount' => $totalcount
				];
			}
			
			$jsonArray = array("status"=>'1', "message"=>'COC statement details', "result"=>$jsonData);

		}elseif($this->input->post('user_id') && $this->input->post('type') == 'search'  && $this->input->post('keywords')){
			$keywords 		= $this->input->post('keywords');
			$userid 		= $this->input->post('user_id');
			$post 			= $this->input->post();

			$totalcount 	= $this->Api_Model->getCOCList('count', ['coc_status' => ['2','4','5','7'], 'user_id' => $userid, 'search' => ['value' => $keywords], 'page' => 'plumbercocstatement']);
			$results 		= $this->Api_Model->getCOCList('all', ['coc_status' => ['2','4','5','7'], 'user_id' => $userid, 'search' => ['value' => $keywords], 'page' => 'plumbercocstatement']);

			foreach ($results as $key => $value) {

				if($value['coc_status']=='2' && $value['auditorid']!='0'){
					$cocstatus = '7'+$value['audit_status'];					
				}else{
					$cocstatus = $value['coc_status'];
				}
				
				if ( $this->config->item('cocstatus')[$value['coc_status']] == 'Logged') {
					$colorcode = '#ade33d';
				}else{
					$colorcode = '#7f694f';
				}
				$jsonData['coc_statement'][] = [ 'cocid' => $value['id'], 'plumberid' => $value['user_id'], 'coc_status' =>  $this->config->item('cocauditstatus')[$cocstatus], 'coc_type' => $this->config->item('coctype')[$value['type']], 'cl_name' => $value['cl_name'], 'colorcode' => $colorcode, 'totalcount' => $totalcount
				];
			}
			
			$jsonArray = array("status"=>'1', "message"=>'COC statement serach details', "result"=>$jsonData);

		}else{

			$jsonArray = array("status"=>'0', "message"=>'invalid request', 'result' => []);
		}

		echo json_encode($jsonArray);
	}
	// Log CoC View:
	public function logcoc_view(){

		if ($this->input->post() && $this->input->post('user_id') && $this->input->post('id')) {
			$jsonData = [];

			$plumberID						= $this->input->post('user_id');
			$id								= $this->input->post('id'); // id = cocid

			if ($this->input->post('auditorid') != '') {
				$auditorid						= ['auditorid' => $extras['auditorid']];
			}else{
				$auditorid						= [];
			}
			
			$result							= $this->Coc_Model->getCOCList('row', ['id' => $id, 'user_id' => $plumberID]+$auditorid);

			$userdata				 		= $this->Plumber_Model->getList('row', ['id' => $plumberID], ['users', 'usersdetail', 'usersplumber', 'company']);
			$specialisations 				= explode(',', $userdata['specialisations']);

			$jsonData['page_lables'] = [ 'plumbingwork' => 'Plumbing Work Completion Date *', 'insuranceclaim' => "Insurance Claim/Order no: (if relevant)", 'certificate' => "Certificate Number: ".$id."", 'installationimages' => "Installation Images", 'address' => "Physical Address Details of Installation", 'ownersname' => "Owners Name *", 'complex' => "Name of Complex/Flat and Unit Number (if applicable)", 'street' => "Street *", 'number' => "Number *", 'province' => "Province *", 'city' => "City *", 'suburb' => "Suburb *", 'contact' => "Contact Mobile *", 'alternate_no' => "Alternate Contact", 'email' => "Email Address"
			];

			$jsonData['result'] 			= $result;
			$jsonData['userdata'] 			= $userdata;
			$jsonData['cocid'] 				= $id;
			$jsonData['installation'] 		= $this->Installationtype_Model->getList('all', ['designation' => $userdata['designation'], 'specialisations' => [], 'ids' => range(1,8)]);
			$jsonData['specialisations']	= $this->Installationtype_Model->getList('all', ['designation' => $userdata['designation'], 'specialisations' => $specialisations, 'ids' => range(1,8)]);


			$province 						= $this->Managearea_Model->getListProvince('all', ['status' => ['1']]);
			if(count($province) > 0){
				foreach ($province as $key => $value) {
					$jsonData['province_list'][] = [ 'id' => $value['id'], 'name' => $value['name']
					];
				}
			}
			//$jsonData['designation2'] 		= $this->config->item('designation2');
			//$jsonData['ncnotice'] 			= $this->config->item('ncnotice');
			//$jsonData['installationtype']	= $this->getInstallationTypeList();
		
			$noncompliance					= $this->Noncompliance_Model->getList('all', ['coc_id' => $id, 'user_id' => $plumberID]);		
			$jsonData['noncompliance']		= [];
			foreach($noncompliance as $compliance){
				$jsonData['noncompliance'][] = [
					'id' 		=> $compliance['id'],
					'details' 	=> $this->parsestring($compliance['details']),
					'file' 		=> base_url().'assets/uploads/plumber/'.$plumberID.'/log/'.$compliance['file']
				];
			}

			if ($result['cl_file1'] !='') {
				$jsonData['paper_image'] = base_url().'assets/uploads/plumber/'.$plumberID.'/log/'.$result['cl_file1'];
			}
			if ($result['cl_file2'] !='') {
				$imgarray = explode(',', $result['cl_file2']);
				foreach ($imgarray as $imgarraykey => $imgarrayvalue) {
					$jsonData['installation_images'][] = base_url().'assets/uploads/plumber/'.$plumberID.'/log/'.$imgarrayvalue;
				}
				
			}

			$jsonData['agreement'] = [ 'header' => ["I ".$userdata['name'].' '.$userdata['surname'].", Licensed registration number ".$userdata['registration_no'].", certify that, the above compliance certifcate details are true and correct and will be logged in accordance with the prescribed requirements as defned by the PIRB. Select either A or B as appropriate"],'agreement1' => ['description' => 'A: The above plumbing work was carried out by me or under my supervision, and that it complies in all respects to the plumbing regulations, laws, National Compulsory Standards and Local bylaws.', 'agreementid' => '1'], 'agreement2' => ['description' => 'B: I have fully inspected and tested the work started but not completed by another Licensed plumber. I further certify that the inspected and tested work and the necessary completion work was carried out by me or under my supervision- complies in all respects to the plumbing regulations, laws, National Compulsory Standards and Local bylaws.', 'agreementid' => '2'], 
			];

			$jsonArray = array("status"=>'1', "message"=>'Plumber CoC Detail', "result"=>$jsonData);
			
		}else{

			$jsonArray = array("status"=>'0', "message"=>'invalid request', "result"=>[]);
		}

		echo json_encode($jsonArray);
	}

	// Log coc save:
	public function logcoc_save(){

			if ($this->input->post() && $this->input->post('submit') == 'save') {
			

			$this->form_validation->set_rules('completion_date','Completeion date','trim|required');
			$this->form_validation->set_rules('name','Owners name','trim|required');
			$this->form_validation->set_rules('street','Street','trim|required');
			$this->form_validation->set_rules('number','Number','trim|required');
			$this->form_validation->set_rules('contact_no','Contact mobile','trim|required');
			$this->form_validation->set_rules('province','Province','trim|required');
			$this->form_validation->set_rules('city','city','trim|required');
			$this->form_validation->set_rules('suburb','suburb','trim|required');
			$this->form_validation->set_rules('agreement','Agreement','trim|required');

			if ($this->form_validation->run()==FALSE) {
				$findtext 		= ['<div class="form_error">', "</div>"];
				$replacetext 	= ['', ''];
				$errorMsg 		= str_replace($findtext, $replacetext, validation_errors());
				$jsonArray = array("status"=>'0', "message"=>$errorMsg, 'result' => []);
			}else{

				$post 				= $this->input->post();
				$plumberID 			= $this->input->post('user_id');
				$cocId 				= $this->input->post('coc_id');
				$datetime			= date('Y-m-d H:i:s');

				$userdata				 		= $this->Plumber_Model->getList('row', ['id' => $plumberID], ['users', 'usersdetail', 'usersplumber', 'company']);
				$specialisations 				= explode(',', $userdata['specialisations']);
				$post['company_details'] 		= 	$userdata['company_details'];

				// Save

				if ($this->input->post('cl_id') != '') { // cl_id = log coc autoincrement id
					$id 			= 	$this->input->post('cl_id');
				}else{
					$id 			= 	'';
				}
				if (isset($post['file1']) && $post['file1'] !='') {
					$data = $this->fileupload(['files' => $post['file1'], 'file_name' => $post['file1_name'], 'user_id' => $plumberID, 'page' => 'plumber_logcoc']);
				$post['file1'] = $data[0];
				}
				if (isset($post['file2']) && $post['file2'] != '') {
					$data = $this->fileupload(['files' => $post['file2'], 'file_name' => $post['file2_name'], 'user_id' => $plumberID, 'page' => 'plumber_logcoc']);
				$post['file2'] = $data[0];
				}
				

				if(isset($post['coc_id'])) 				$request['coc_id'] 					= $cocId;
				if(isset($post['completion_date'])) 	$request['completion_date'] 		= date('Y-m-d', strtotime($post['completion_date']));
				if(isset($post['order_no'])) 			$request['order_no'] 				= $post['order_no'];
				if(isset($post['name'])) 				$request['name'] 					= $post['name'];
				if(isset($post['address'])) 			$request['address'] 				= $post['address'];
				if(isset($post['street'])) 				$request['street'] 					= $post['street'];
				if(isset($post['number'])) 				$request['number'] 					= $post['number'];
				if(isset($post['province'])) 			$request['province'] 				= $post['province'];
				if(isset($post['city'])) 				$request['city'] 					= $post['city'];
				if(isset($post['suburb'])) 				$request['suburb'] 					= $post['suburb'];
				if(isset($post['contact_no'])) 			$request['contact_no'] 				= $post['contact_no'];
				if(isset($post['alternate_no'])) 		$request['alternate_no'] 			= $post['alternate_no'];
				if(isset($post['email'])) 				$request['email'] 					= $post['email'];
				if(isset($post['installationtype'])) 	$request['installationtype'] 		= implode(',', $post['installationtype']);
				if(isset($post['specialisations'])) 	$request['specialisations'] 		= implode(',', $post['specialisations']);
				if(isset($post['installation_detail'])) $request['installation_detail'] 	= $post['installation_detail'];
				if(isset($post['file1'])) 				$request['file1'] 					= $post['file1'];
				if(isset($post['agreement'])) 			$request['agreement'] 				= $post['agreement'];
				if(isset($post['file2'])) 				$request['file2'] 					= $post['file2'];	
				if(isset($post['company_details'])) 	$request['company_details'] 		= $post['company_details'];
				if(isset($post['ncnotice'])) 			$request['ncnotice'] 				= $post['ncnotice'];
				if(isset($post['ncemail'])) 			$request['ncemail'] 				= $post['ncemail'];
				if(isset($post['ncreason'])) 			$request['ncreason'] 				= $post['ncreason'];
				
				
				// $request['file2'] 					= (isset($post['file2'])) ? $post['file2'] : '';
				
				if($id==''){
					$request['created_at'] = $datetime;
					$request['created_by'] = $plumberID;
					$this->db->insert('coc_log', $request);
					// $insertid = $this->db->insert_id();
					$jsonData['insertid'] 			= $this->db->insert_id();
				}else{
					$request['updated_at'] = $datetime;
					$request['updated_by'] = $plumberID;
					$this->db->update('coc_log', $request, ['id' => $id]);
					$jsonData['insertid'] 			= $id;
				}
				
				$cocstatus = '5';
				if(isset($cocstatus)) $this->db->update('stock_management', ['coc_status' => $cocstatus], ['id' => $cocId]);
				
				$result							= $this->Coc_Model->getCOCList('row', ['id' => $cocId, 'user_id' => $plumberID]);

				$userdata				 		= $this->Plumber_Model->getList('row', ['id' => $plumberID], ['users', 'usersdetail', 'usersplumber', 'company']);
				$specialisations 				= explode(',', $userdata['specialisations']);


				$jsonData['userdata'] 			= $userdata;
				$jsonData['cocid'] 				= $cocId;
				$jsonData['log_coc_id'] 		= $id;
				$jsonData['result'] 			= $result;
				$jsonData['notification'] 		= $this->getNotification();
				$jsonData['province'] 			= $this->getProvinceList();
				$jsonData['designation2'] 		= $this->config->item('designation2');
				$jsonData['ncnotice'] 			= $this->config->item('ncnotice');
				$jsonData['installationtype']	= $this->getInstallationTypeList();
				$jsonData['installation'] 		= $this->Installationtype_Model->getList('all', ['designation' => $userdata['designation'], 'specialisations' => [], 'ids' => range(1,8)]);
				$jsonData['specialisations']	= $this->Installationtype_Model->getList('all', ['designation' => $userdata['designation'], 'specialisations' => $specialisations, 'ids' => range(1,8)]);
			
				$noncompliance					= $this->Noncompliance_Model->getList('all', ['coc_id' => $id, 'user_id' => $plumberID]);		
				$jsonData['noncompliance']		= [];
				foreach($noncompliance as $compliance){
					$jsonData['noncompliance'][] = [
						'id' 		=> $compliance['id'],
						'details' 	=> $this->parsestring($compliance['details']),
						'file' 		=> $compliance['file']
					];
				}

				$jsonArray = array("status"=>'1', "message"=>'Thanks for Saving the COC.', "result"=>$jsonData);
			}
		}else{

			$jsonArray = array("status"=>'0', "message"=>'invalid request', "result"=>[]);
		}

		echo json_encode($jsonArray);
	}

	// Log coc log:
	public function logcoc_log(){

		if ($this->input->post() && $this->input->post('submit') == 'log') {
			
			$this->form_validation->set_rules('completion_date','Completeion date','trim|required');
			$this->form_validation->set_rules('name','Owners name','trim|required');
			$this->form_validation->set_rules('street','Street','trim|required');
			$this->form_validation->set_rules('number','Number','trim|required');
			$this->form_validation->set_rules('contact_no','Contact mobile','trim|required');
			$this->form_validation->set_rules('agreement','Agreement','trim|required');
			$this->form_validation->set_rules('installationtype[]','Instalaltion type','trim|required');
			$this->form_validation->set_rules('specialisations[]','Specialisations','trim|required');
			$this->form_validation->set_rules('installation_detail','Instalaltion details','trim|required');
			$this->form_validation->set_rules('ncnotice','non compliance notice','required');
			

			if ($this->form_validation->run()==FALSE) {
				$findtext 		= ['<div class="form_error">', "</div>"];
				$replacetext 	= ['', ''];
				$errorMsg 		= str_replace($findtext, $replacetext, validation_errors());
				$jsonArray = array("status"=>'0', "message"=>$errorMsg, 'result' => []);
			}else{

				$post 				= $this->input->post();
				$plumberID 			= $this->input->post('user_id');
				$cocId 				= $this->input->post('coc_id');
				$datetime			= date('Y-m-d H:i:s');

				$userdata				 		= $this->Plumber_Model->getList('row', ['id' => $plumberID], ['users', 'usersdetail', 'usersplumber', 'company']);
				$specialisations 				= explode(',', $userdata['specialisations']);
				$post['company_details'] 		= 	$userdata['company_details'];

				// // Save

				if ($this->input->post('cl_id') != '') { // cl_id = log coc autoincrement id
					$id 			= 	$this->input->post('cl_id');
				}else{
					$id 			= 	'';
				}

				if (isset($post['file1']) && $post['file1'] != '') {
					$data = $this->fileupload(['files' => $post['file1'], 'file_name' => $post['file1_name'], 'user_id' => $plumberID, 'page' => 'plumber_logcoc']);
				$post['file1'] = $data[0];
				}
				if (isset($post['file2']) && $post['file2'] != '') {
					$data = $this->fileupload(['files' => $post['file2'], 'file_name' => $post['file2_name'], 'user_id' => $plumberID, 'page' => 'plumber_logcoc']);
				$post['file2'] = $data[0];
				}
				

				if(isset($post['coc_id'])) 				$request['coc_id'] 					= $cocId;
				if(isset($post['completion_date'])) 	$request['completion_date'] 		= date('Y-m-d', strtotime($post['completion_date']));
				if(isset($post['order_no'])) 			$request['order_no'] 				= $post['order_no'];
				if(isset($post['name'])) 				$request['name'] 					= $post['name'];
				if(isset($post['address'])) 			$request['address'] 				= $post['address'];
				if(isset($post['street'])) 				$request['street'] 					= $post['street'];
				if(isset($post['number'])) 				$request['number'] 					= $post['number'];
				if(isset($post['province'])) 			$request['province'] 				= $post['province'];
				if(isset($post['city'])) 				$request['city'] 					= $post['city'];
				if(isset($post['suburb'])) 				$request['suburb'] 					= $post['suburb'];
				if(isset($post['contact_no'])) 			$request['contact_no'] 				= $post['contact_no'];
				if(isset($post['alternate_no'])) 		$request['alternate_no'] 			= $post['alternate_no'];
				if(isset($post['email'])) 				$request['email'] 					= $post['email'];
				if(isset($post['installationtype'])) 	$request['installationtype'] 		= implode(',', $post['installationtype']);
				if(isset($post['specialisations'])) 	$request['specialisations'] 		= implode(',', $post['specialisations']);
				if(isset($post['installation_detail'])) $request['installation_detail'] 	= $post['installation_detail'];
				if(isset($post['file1'])) 				$request['file1'] 					= $post['file1'];
				if(isset($post['agreement'])) 			$request['agreement'] 				= $post['agreement'];
				if(isset($post['file2'])) 				$request['file2'] 					= $post['file2'];	
				if(isset($post['company_details'])) 	$request['company_details'] 		= $post['company_details'];
				if(isset($post['ncnotice'])) 			$request['ncnotice'] 				= $post['ncnotice'];
				if(isset($post['ncemail'])) 			$request['ncemail'] 				= $post['ncemail'];
				if(isset($post['ncreason'])) 			$request['ncreason'] 				= $post['ncreason'];
				$request['log_date'] = date('Y-m-d H:i:s');
				
				
				// $request['file2'] 					= (isset($post['file2'])) ? $post['file2'] : '';

				if($id==''){
					$request['created_at'] = $datetime;
					$request['created_by'] = $plumberID;
					$actiondata = $this->db->insert('coc_log', $request);
					$jsonData['insertid'] 			= $this->db->insert_id();
				}else{
					$request['updated_at'] = $datetime;
					$request['updated_by'] = $plumberID;
					$actiondata = $this->db->update('coc_log', $request, ['id' => $id]);
					$jsonData['insertid'] 			= $id;
				}
				
				$cocstatus = '2';
				$this->db->set('count', 'count + 1',FALSE); 
				$this->db->where('user_id', $plumberID); 
				$increase_count = $this->db->update('coc_count'); 

				if(isset($cocstatus)) $this->db->update('stock_management', ['coc_status' => $cocstatus], ['id' => $cocId]);
				
				$result							= $this->Coc_Model->getCOCList('row', ['id' => $cocId, 'user_id' => $plumberID]);

				$userdata				 		= $this->Plumber_Model->getList('row', ['id' => $plumberID], ['users', 'usersdetail', 'usersplumber', 'company']);
				$specialisations 				= explode(',', $userdata['specialisations']);

				$notificationdata 	= $this->Communication_Model->getList('row', ['id' => '18', 'emailstatus' => '1']);
				$settingsdetail 	= 	$this->Systemsettings_Model->getList('row');
				
				if($notificationdata){
					$body 		= str_replace(['{Plumbers Name and Surname}', '{number}'], [$userdata['name'].' '.$userdata['surname'], $cocId], $notificationdata['email_body']);
					$subject 	= str_replace(['{cocno}'], [$cocId], $notificationdata['subject']);
					$this->CC_Model->sentMail($userdata['email'], $subject, $body);
				}

				if($settingsdetail && $settingsdetail['otp']=='1'){
					$smsdata 	= $this->Communication_Model->getList('row', ['id' => '18', 'smsstatus' => '1']);
					if($smsdata){
						$sms = str_replace(['{number of COC}'], [$cocId], $smsdata['sms_body']);
						$this->sms(['no' => $userdata['mobile_phone'], 'msg' => $sms]);
					}
				}

				if(isset($post['ncemail']) && $post['ncemail']=='1'){
					$notificationdata 	= $this->Communication_Model->getList('row', ['id' => '23', 'emailstatus' => '1']);
					$replacetext = ['', '', '', '', '', '', ''];							
					if(isset($post['name'])) 		$post[0] = $post['name'];
					if(isset($post['address'])) 	$post[1] = $post['address'];
					if(isset($post['street'])) 		$post[2] = $post['street'];
					if(isset($post['number'])) 		$post[3] = $post['number'];
					if(isset($post['province'])){
						$provincename 	= 	$this->Managearea_Model->getListProvince('row', ['id' => $post['province']]);
						$nc_data[4] 	=  $provincename['name'];
					} 	
					if(isset($post['city'])){
						$cityname 	= 	$this->Managearea_Model->getListCity('row', ['id' => $post['city']]);
						$nc_data[5] =  $cityname['name'];
					} 		
					if(isset($post['suburb'])){
						$suburbname = 	$this->Managearea_Model->getListSuburb('row', ['id' => $post['suburb']]);
						$nc_data[6] =  $suburbname['name'];
					} 	
					
					if(isset($post['email']) && $post['email']!='' && $notificationdata){
						
						$subject 	= str_replace(['{Customer Name}', '{Complex Name}', '{Street}', '{Number}', '{Suburb}', '{City}', '{Province}'], $nc_data, $notificationdata['subject']);
						$body 		= str_replace(['{Customer Name}', '{Plumber Name}', '{plumbers company name}', '{company contact number}'], [$nc_data[0], $userdata['name'].' '.$userdata['surname'], $userdata['companyname'], $userdata['companymobile']], $notificationdata['email_body']);
						
						$pdf 		= FCPATH.'assets/uploads/temp/'.$cocId.'.pdf';
						$this->pdfnoncompliancereport($cocId, $userid, $pdf);
						$this->CC_Model->sentMail($post['email'], $subject, $body, $pdf, $userdata['email']);
						if(file_exists($pdf)) unlink($pdf);  
					}				
					
					if(isset($post['contact_no']) && $post['contact_no']!='' && $this->config->item('otpstatus')!='1'){
						$smsdata 	= $this->Communication_Model->getList('row', ['id' => '23', 'smsstatus' => '1']);
			
						if($smsdata){
							$sms = str_replace(['{Customer Name}', '{Complex Name}', '{Street}', '{Number}', '{Suburb}', '{City}', '{Province}'], $nc_data, $smsdata['sms_body']);
							$this->sms(['no' => $post['contact_no'], 'msg' => $sms]);
						}
					}
				}


				$jsonData['userdata'] 			= $userdata;
				$jsonData['cocid'] 				= $cocId;
				$jsonData['result'] 			= $result;
				$jsonData['notification'] 		= $this->getNotification();
				$jsonData['province'] 			= $this->getProvinceList();
				$jsonData['designation2'] 		= $this->config->item('designation2');
				$jsonData['ncnotice'] 			= $this->config->item('ncnotice');
				$jsonData['installationtype']	= $this->getInstallationTypeList();
				$jsonData['installation'] 		= $this->Installationtype_Model->getList('all', ['designation' => $userdata['designation'], 'specialisations' => [], 'ids' => range(1,8)]);
				$jsonData['specialisations']	= $this->Installationtype_Model->getList('all', ['designation' => $userdata['designation'], 'specialisations' => $specialisations, 'ids' => range(1,8)]);
			
				$noncompliance					= $this->Noncompliance_Model->getList('all', ['coc_id' => $cocId, 'user_id' => $plumberID]);		
				$jsonData['noncompliance']		= [];
				foreach($noncompliance as $compliance){
					$jsonData['noncompliance'][] = [
						'id' 		=> $compliance['id'],
						'details' 	=> $this->parsestring($compliance['details']),
						'file' 		=> $compliance['file']
					];
				}
				
				$jsonArray = array("status"=>'1', "message"=>'Thanks for Logging the COC.', "result"=>$jsonData);
			}
		}else{

			$jsonArray = array("status"=>'0', "message"=>'invalid request', "result"=>[]);
		}

		echo json_encode($jsonArray);
	}

	// Audit Statement:
	public function audit_statement(){
		if ($this->input->post() && $this->input->post('type') == 'search') {
			$jsonData = [];
			$jsonData['results'] = [];

			$keywords 		= $this->input->post('keywords');
			$userid 		= $this->input->post('user_id');
			$post 			= $this->input->post();
			$totalcount 	= $this->Coc_Model->getCOCList('count', ['coc_status' => ['2'], 'user_id' => $userid, 'search' => ['value' => $keywords], 'page' => 'plumberauditorstatement', 'noaudit' => '']);
			$results 		= $this->Coc_Model->getCOCList('all', ['coc_status' => ['2'], 'user_id' => $userid, 'search' => ['value' => $keywords], 'page' => 'plumberauditorstatement', 'noaudit' => '']);

			foreach ($results as $key => $value) {
				if ($value['u_status'] =='1') {
					$colorcode = '#ade33d';
				}elseif($value['u_status'] =='2'){
					$colorcode = '#ffd700';
				}elseif($value=='3'){
					$colorcode = '#eb0000';
				}elseif($value['u_status'] =='4'){
					$colorcode = '#ade33d';
				}elseif($value['u_status'] =='5'){
					$colorcode = '#87d0ef';
				}
				$jsonData['results'][] = [
					'coc_number' => $value['id'], 'auditstatus' => $this->config->item('auditstatus')[$value['u_status']], 'colorcode' => $colorcode, 'consumername' => $value['cl_name'], 'auditorname' => $value['auditorname'], 'address' => $value['cl_address'], 'audit_allocation_date' => $value['audit_allocation_date'], 
				];
			}
			$jsonData['totalcount'] = $totalcount;
			if (count($results) > 0) {
				$jsonArray = array("status"=>'1', "message"=>'Search Audit Statement', "result"=> $jsonData);
			}else{
				$jsonArray = array("status"=>'0', "message"=>'No record found', "result"=>[]);
			}
			
		}elseif ($this->input->post() && $this->input->post('type') == 'dropdown') {
			$jsonData = [];
			$jsonData['results'] = [];

			$length 		= $this->input->post('dropdown_value');
			$userid 		= $this->input->post('user_id');
			$post 			= $this->input->post();
			if ($this->input->post('keywords')!='') {
				$keywords 		= $this->input->post('keywords');
			}else{
				$keywords 		= '';
			}
			$totalcount 	= $this->Coc_Model->getCOCList('count', ['coc_status' => ['2'], 'user_id' => $userid, 'search' => ['value' => $keywords], 'page' => 'plumberauditorstatement', 'start' => '0', 'length' => $length, 'noaudit' => '']);
			$results 		= $this->Coc_Model->getCOCList('all', ['coc_status' => ['2'], 'user_id' => $userid, 'search' => ['value' => $keywords], 'page' => 'plumberauditorstatement', 'start' => '0', 'length' => $length, 'noaudit' => '']);

			foreach ($results as $key => $value) {
				if ($value['u_status'] =='1') {
					$colorcode = '#ade33d';
				}elseif($value['u_status'] =='2'){
					$colorcode = '#ffd700';
				}elseif($value=='3'){
					$colorcode = '#eb0000';
				}elseif($value['u_status'] =='4'){
					$colorcode = '#ade33d';
				}elseif($value['u_status'] =='5'){
					$colorcode = '#87d0ef';
				}
				$jsonData['results'][] = [
					'coc_number' => $value['id'], 'auditstatus' => $this->config->item('auditstatus')[$value['u_status']], 'colorcode' => $colorcode, 'consumername' => $value['cl_name'], 'auditorname' => $value['auditorname'], 'address' => $value['cl_address'], 'audit_allocation_date' => $value['audit_allocation_date'], 
				];
			}
			$jsonData['totalcount'] = $totalcount;
			if (count($results) > 0) {
				$jsonArray = array("status"=>'1', "message"=>'Search Audit Statement', "result"=> $jsonData);
			}else{
				$jsonArray = array("status"=>'0', "message"=>'No record found', "result"=>[]);
			}
			
		}elseif ($this->input->post() && $this->input->post('type') == 'list') {
			$jsonData = [];
			$jsonData['results'] = [];

			$userid 		= $this->input->post('user_id');
			$post 			= $this->input->post();
			$totalcount 	= $this->Coc_Model->getCOCList('count', ['coc_status' => ['2'], 'user_id' => $userid, 'noaudit' => '']+$post);
			$results 		= $this->Coc_Model->getCOCList('all', ['coc_status' => ['2'], 'user_id' => $userid, 'noaudit' => '']+$post);
			
			foreach ($results as $key => $value) {
				if ($value['u_status'] =='1') {
					$colorcode = '#ade33d';
				}elseif($value['u_status'] =='2'){
					$colorcode = '#ffd700';
				}elseif($value=='3'){
					$colorcode = '#eb0000';
				}elseif($value['u_status'] =='4'){
					$colorcode = '#ade33d';
				}elseif($value['u_status'] =='5'){
					$colorcode = '#87d0ef';
				}
				$jsonData['results'][] = [
					'coc_number' => $value['id'], 'auditstatus' => $this->config->item('auditstatus')[$value['u_status']], 'colorcode' => $colorcode, 'consumername' => $value['cl_name'], 'auditorname' => $value['auditorname'], 'address' => $value['cl_address'], 'audit_allocation_date' => $value['audit_allocation_date'], 
				];
			}
			$jsonData['totalcount'] = $totalcount;
			//$jsonData['results'] 	= $results;

			if (count($results) > 0) {
				$jsonArray = array("status"=>'1', "message"=>'Audit Statement', "result"=>$jsonData);
			}else{
				$jsonArray = array("status"=>'0', "message"=>'No record found', "result"=>[]);
			}

		}else{
			$jsonArray = array("status"=>'0', "message"=>'invalid request', "result"=>[]);
		}

		echo json_encode($jsonArray);
	}

	// coc Details;
	public function coc_details(){

		if ($this->input->post() && $this->input->post('coc_id') && $this->input->post('type') == 'coc_details') {
			$extraparam = [];
			$jsonData 	= [];
			
			$userid						= $this->input->post('user_id');
			$id							= $this->input->post('coc_id'); // id = coc id
			// if ($this->input->post('auditorid') !='') {
			// 	$extraparam['auditorid']	= $this->input->post('auditorid');
			// }else{
			// 	$extraparam['auditorid']	= '';
			// }
			$extraparam['user_id'] 		= $userid;
			$extraparam['page'] 		= 'review';
		
			$result						= $this->Coc_Model->getCOCList('row', ['id' => $id, 'coc_status' => ['2']]+$extraparam);
			$userdata				 	= $this->Plumber_Model->getList('row', ['id' => $userid], ['users', 'usersdetail', 'usersplumber', 'company']);
			$reviewlist					= $this->Auditor_Model->getReviewList('all', ['coc_id' => $id]);
			
			// $specialisations 				= explode(',', $userdata['specialisations']);

			// $jsonData['userdata'] 			= $userdata;
			// $jsonData['cocid'] 				= $id;
			// $jsonData['auditorid'] 			= $auditorid;
			// $jsonData['notification'] 		= $this->getNotification();
			// $jsonData['province'] 			= $this->getProvinceList();
			// $jsonData['designation2'] 		= $this->config->item('designation2');
			// $jsonData['ncnotice'] 			= $this->config->item('ncnotice');
			// $jsonData['installationtype']	= $this->getInstallationTypeList();
			// $jsonData['installation'] 		= $this->Installationtype_Model->getList('all', ['designation' => $userdata['designation'], 'specialisations' => [], 'ids' => range(1,8)]);
			// $jsonData['specialisations']	= $this->Installationtype_Model->getList('all', ['designation' => $userdata['designation'], 'specialisations' => $specialisations, 'ids' => range(1,8)]);

			// $noncompliance					= $this->Noncompliance_Model->getList('all', ['coc_id' => $id, 'user_id' => $userid]);		
			// $jsonData['noncompliance']		= [];
			// foreach($noncompliance as $compliance){
			// 	$jsonData['noncompliance'][] = [
			// 		'id' 		=> $compliance['id'],
			// 		'details' 	=> $this->parsestring($compliance['details']),
			// 		'file' 		=> $compliance['file']
			// 	];
			// }
			if ($result) {
				$jsonData['page_lables'] = [
				'page_heading' => 'CoC Details', 'certificate' => 'Certificate','plumbingwork' => 'Plumbing work completeion date', 'ownersname' => 'Owners name', 'street' => 'Street', 'suburb' => 'Suburb', 'city' => 'City', 'province' => 'Province', 'complex' => 'Name of the complex / flat (if applicable)', 'contactnumber1' => 'Contact number', 'contactnumber2' => 'Alternate Contact number', 'auditstaus' => 'Audit status', 'auditorname' => 'Auditors name and surname', 'phone' => 'Phone (mobile)', 'date' => 'Date of audit', 'overall' => 'Overall workmanship', 'plumberpresent' => 'Licensed plumber present', 'coccorrect' => 'Was CoC completed correctly'
				];
				if ($result['as_workmanship'] =='') {
					$as_workmanship = '1';
				}else{
					$as_workmanship = $result['as_workmanship'];
				}
				if ($result['as_plumber_verification'] !='') {
					$as_plumber_verification = $result['as_plumber_verification'];
				}else{
					$as_plumber_verification = '1';
				}
				if ($result['as_coc_verification'] !='') {
					$as_coc_verification = $result['as_coc_verification'];
				}else{
					$as_coc_verification = '1';
				}
				$jsonData['result']	= [ 'cocnumber' => $result['id'], 'plumberid' => $result['user_id'], 'completiondate' => date("d-m-Y", strtotime($result['cl_completion_date'])), 'onersname' =>  $result['cl_name'], 'cl_address' =>  $result['cl_address'], 'cl_street' =>  $result['cl_street'], 'cl_province_name' =>  $result['cl_province_name'], 'cl_city_name' =>  $result['cl_city_name'], 'cl_suburb_name' =>  $result['cl_suburb_name'], 'complex' =>  $result['cl_address'], 'cl_contact_no' =>  $result['cl_contact_no'], 'cl_alternate_no' =>  $result['cl_alternate_no'], 'auditstatus' => $this->config->item('auditstatus')[$result['audit_status']], 'auditorid' => $result['auditorid'], 'auditorname' => $result['auditorname'], 'auditormobile' => $result['auditormobile'], 'auditormobile' => $result['auditormobile'], 'as_audit_date' => date("d-m-Y", strtotime($result['as_audit_date'])), 'as_workmanship' => $this->config->item('workmanship')[$as_workmanship], 'as_plumber_verification' => $this->config->item('yesno')[$as_plumber_verification], 'as_coc_verification' => $this->config->item('yesno')[$as_coc_verification], 'auditorid' => $result['auditorid']
				];

				foreach ($reviewlist as $key => $value) {

					if ($this->config->item('reviewtype')[$value['reviewtype']] == 'Cautionary') {
					$colorcode = '#ffd700';
					}elseif($this->config->item('reviewtype')[$value['reviewtype']] == 'Compliment'){
						$colorcode = '#ade33d';
					}elseif($this->config->item('reviewtype')[$value['reviewtype']] == 'Failure'){
						$colorcode = '#f33333';
					}elseif($this->config->item('reviewtype')[$value['reviewtype']] == 'No Audit Findings'){
						$colorcode = '#50c6f2';
					}

					$jsonData['review_details'][] = [ 'reviewid' => $value['id'], 'reviewtype' => $this->config->item('reviewtype')[$value['reviewtype']], 'statementname' => $value['statementname'], 'colorcode' => $colorcode
					];
				}
				$message 	= 'CoC Details';
				$status 	= '1';
			}else{
				$message 	= 'No Record Found';
				$status 	= '0';
			}

			// print_r($jsonData);die;
			$jsonArray = array("status"=> $status, "message"=> $message, "result"=> $jsonData);
		}else{
			$jsonArray = array("status"=>'0', "message"=>'Invalid Api', "result"=>[]);
		}

		echo json_encode($jsonArray);
	}

	// Audit Review, view coc;
	public function auditreview_coc(){

		if ($this->input->post() && $this->input->post('user_id') && $this->input->post('type') == 'view_coc') {

			$userid					 = $this->input->post('user_id');
			$cocID 					 = $this->input->post('coc_id');
			$electroniccocreport 	 = '';
			$noncompliancereport 	 = '';
			$userdata				 = $this->Plumber_Model->getList('row', ['id' => $userid], ['users', 'usersdetail', 'usersplumber', 'company']);
			$specialisations 		 = explode(',', $userdata['specialisations']);

			//$jsonData['installationtype']	= $this->getInstallationTypeList();
			$installations 		 	 = $this->Installationtype_Model->getList('all', ['designation' => $userdata['designation'], 'specialisations' => [], 'ids' => range(1,8)]);
			$specialisations_details = $this->Installationtype_Model->getList('all', ['designation' => $userdata['designation'], 'specialisations' => $specialisations, 'ids' => range(1,8)]);

			if ($this->input->post('auditorid') != '') {
				$auditorid			 = ['auditorid' => $this->input->post('auditorid')];
			}else{
				$auditorid			 = [];
			}
			
			$result 				 = $this->Coc_Model->getCOCList('row', ['id' => $cocID, 'user_id' => $userid]+$auditorid);
			$logdate 				 = isset($result['cl_log_date']) && date('Y-m-d', strtotime($result['cl_log_date']))!='1970-01-01' ? date('d-m-Y', strtotime($result['cl_log_date'])) : '';

			$noncompliance			 = $this->Noncompliance_Model->getList('all', ['coc_id' => $cocID, 'user_id' => $userid]);

			$jsonData['noncompliance']		= [];
			foreach($noncompliance as $compliance){
				$jsonData['noncompliance'][] = [
					'id' 		=> $compliance['id'],
					'details' 	=> $this->parsestring($compliance['details']),
					'file' 		=> base_url().'assets/uploads/plumber/'.$userid.'/log/'.$compliance['file'] 
				];
			}

			$jsonData['installation_details']		= [];
			foreach($installations as $installation){
				$jsonData['installation_details'][] = [
					'id' 		=> $installation['id'],
					'name' 		=> $installation['name'],
					'code' 		=>$installation['code']
				];
			}

			$jsonData['specialisation_details']		= [];
			foreach($specialisations_details as $specialisations_detail){
				$jsonData['specialisation_details'][] = [
					'id' 		=> $specialisations_detail['id'],
					'name' 		=> $specialisations_detail['name'],
					'code' 		=>$specialisations_detail['code']
				];
			}

			$jsonData['coc_data']		= [];
			$jsonData['coc_data'] = [ 'coc_id' => $result['id'], 'coc_id' => $result['id'], 'plumberid' => $result['user_id'], 'completiondate' => date('d-m-Y', strtotime($result['cl_completion_date'])), 'insuranceclaim' => $result['cl_order_no'], 'cl_name' => $result['cl_name'], 'cl_name' => $result['cl_name'], 'complex' => $result['cl_address'], 'cl_street' => $result['cl_street'], 'cl_number' => $result['cl_number'], 'cl_province_name' => $result['cl_province_name'], 'cl_city_name' => $result['cl_city_name'], 'cl_suburb_name' => $result['cl_suburb_name'], 'cl_contact_no' => $result['cl_contact_no'], 'cl_alternate_no' => $result['cl_alternate_no'], 'cl_email' => $result['cl_email'], 'cl_installationtype' => $result['cl_installationtype'], 'cl_specialisations' => $result['cl_specialisations'], 'cl_installation_detail' => $result['cl_installation_detail'], 'cl_ncnotice ' => $result['cl_ncnotice'], 'cl_agreement' => $result['cl_agreement']
			];

			$jsonData['installation_images']		= [];
			if ($result['cl_file2'] !='') {
				if(strpos($result['cl_file2'], ',') !== false){
					$imgarray = explode(",",$result['cl_file2']);
					foreach ($imgarray as $key => $images) {
						$jsonData['installation_images'][] = [
							'file' 		=> base_url().'assets/uploads/plumber/'.$userid.'/log/'.$images 
						];
					}
				}else{
					$jsonData['installation_images'][] = [
							'file' 		=> base_url().'assets/uploads/plumber/'.$userid.'/log/'.$result['cl_file2'] 
						];
				}
			}else{
				$jsonData['installation_images'][] = [
							'file' 		=> '' 
						];
			}

			if ($result['type'] =='2' && $logdate!='') {
				//$electroniccocreport = base_url().'plumber/auditstatement/index/electroniccocreport/'.$cocID.'/'.$cocID;
				$electroniccocreport = base_url().'webservice/api/pdfelectroniccocreport_api/'.$cocID.'/'.$cocID;
			}
			if (count($jsonData['noncompliance']) > 0 && $logdate!='') {
				//$noncompliancereport = base_url().'plumber/auditstatement/index/noncompliancereport/'.$cocID.'/'.$userid;
				$noncompliancereport = base_url().'webservice/api/pdfnoncompliancereport_api/'.$cocID.'/'.$cocID;
			}

			$jsonData['pdf'] = ['electroniccocreport' => $electroniccocreport, 'noncompliancereport' => $noncompliancereport];

			$jsonData['page_lables'] = [ 'plumbingwork' => 'Plumbing Work Completion Date *', 'insuranceclaim' => "Insurance Claim/Order no: (if relevant)", "certificatenumber" => $cocID, 'physicaladdress' => "Physical Address Details of Installation", 'ownername' => "Owners Name *", 'complex' => "Name of Complex/Flat and Unit Number (if applicable)", 'street' => "Street *", 'number' => "Number *", 'province' => "Province *", 'city' => "City *", 'suburb' => "Suburb *", 'contactmobile' => "Contact Mobile *", 'Alternate Contact' => "Alternate Contact", 'email' => "Email Address", 'installationimages' => "Installation Images"
			];

			$jsonData['agreement'] = [ 'header' => ["I ".$result['u_name'].", Licensed registration number ".$result['plumberregno'].", certify that, the above compliance certifcate details are true and correct and will be logged in accordance with the prescribed requirements as defned by the PIRB. Select either A or B as appropriate"],'agreement1' => ['description' => 'A: The above plumbing work was carried out by me or under my supervision, and that it complies in all respects to the plumbing regulations, laws, National Compulsory Standards and Local bylaws.', 'agreementid' => '1'], 'agreement2' => ['description' => 'B: I have fully inspected and tested the work started but not completed by another Licensed plumber. I further certify that the inspected and tested work and the necessary completion work was carried out by me or under my supervision- complies in all respects to the plumbing regulations, laws, National Compulsory Standards and Local bylaws.', 'agreementid' => '2'], 
			];

			if (count($result) > 0) {
				$jsonArray = array("status"=>'1', "message"=>'View CoC', "result"=>$jsonData);
			}else{
				$jsonArray = array("status"=>'0', "message"=>'invalid request', "result"=>[]);
			}
		}else{
			$jsonArray = array("status"=>'0', "message"=>'invalid request', "result"=>[]);
		}
		echo json_encode($jsonArray);
	}

	public function get_reviewlist(){
		if ($this->input->post() && $this->input->post('review_id')) {
			$id 			= $this->input->post('review_id');
			$reviewlists	= $this->Auditor_Model->getReviewList('row', ['id' => $id]);

			if (isset($review_images)) unset($review_images);
			if ($this->config->item('reviewtype')[$reviewlists['reviewtype']] == 'Cautionary') {
				$colorcode = '#ffd700';
			}elseif($this->config->item('reviewtype')[$reviewlists['reviewtype']] == 'Compliment'){
				$colorcode = '#ade33d';
			}elseif($this->config->item('reviewtype')[$reviewlists['reviewtype']] == 'Failure'){
				$colorcode = '#f33333';
			}elseif($this->config->item('reviewtype')[$reviewlists['reviewtype']] == 'No Audit Findings'){
				$colorcode = '#50c6f2';
			}
			if ($reviewlists['file'] !='') {
				$images =  explode(",",$reviewlists['file']);
				if (count($images) > 0) {
					foreach ($images as $images_key => $image) {
						$review_images[] = base_url().'assets/uploads/auditor/statement/'.$image.'';
					}
				}else{
					$review_images[] = base_url().'assets/uploads/auditor/statement/'.$reviewlists['file'].'';
				}
			}else{
				$review_images[] = '';
			}
			if ($reviewlists['auditor_id'] !='' && $reviewlists['favourites'] !='') {
				$favourites = $this->getfavourites(['auditorid' => $reviewlists['auditor_id'], 'favid' => $reviewlists['favourites']]);
				$favouritesname = $favourites['favour_name'];
			}
			
			$jsonData['review_details'][] = [ 'reviewid' => $reviewlists['id'], 'reviewtype' => $this->config->item('reviewtype')[$reviewlists['reviewtype']], 'installationtypename' => $reviewlists['installationtypename'], 'subtypename' => $reviewlists['subtypename'], 'statementname' => $reviewlists['statementname'], 'favouritesname' => (isset($favouritesname) ? $favouritesname : ''), 'colorcode' => $colorcode, 'cocid' => $reviewlists['coc_id'], 'reference' => $reviewlists['reference'], 'comments' => $reviewlists['comments'], 'performancepoint' => $reviewlists['point'], 'knowledgelink' => $reviewlists['link'], 'review_images' => $review_images, 'status' => $reviewlists['status']
			];
			$jsonArray = array("status"=>'1', "message"=>'Review Deatils', "result"=>$jsonData);
		}else{
			$jsonArray = array("status"=>'0', "message"=>'invalid request', "result"=>[]);
		}
		echo json_encode($jsonArray);

	}

	// Chat History:
	public function chathistory(){

		if ($this->input->post()) {

			$jsonData 			= [];
			$cocid 		= $this->input->post('cocid');  
			$fromto 	= $this->input->post('userid');   // fromto = userid
			$data 		= $this->chat_sync(['cocid' => $cocid, 'fromto' => $fromto]);

			$jsonArray = ['status' => '1', "message"=>'Chat History', 'result' => $data];
		
		}else{
			$jsonArray = array("status"=>'0', "message"=>'invalid request', "result"=>[]);
		}
		echo json_encode($jsonArray);
	}
	public function chat_action(){
		if ($this->input->post() && $this->input->post('user_id') && $this->input->post('action') == "insert") {

			$requestdata = $this->input->post();
			if ($this->input->post('file') !='') {
				$data = $this->fileupload(['files' => $this->input->post('file'), 'file_name' => $this->input->post('file_name'), 'user_id' => $this->input->post('coc_id'), 'page' => 'chat']);
					// $image = $data[0];
				$post['attachment'] = $data[0];
			}

			$post['cocid'] 		= $this->input->post('coc_id');
			$post['fromid'] 	= $this->input->post('user_id');
			if (isset($requestdata['auditorid'])) {
				$post['toid'] 	= $this->input->post('auditorid');
			}elseif(isset($requestdata['plumberid'])){
				$post['toid'] 	= $this->input->post('plumberid');
			}
			
			if ($this->input->post('message') !='') {
				$post['message'] 	= $this->input->post('message');
			}
			$post['state1'] 	= '1'; // [state1] => 1 for viewd
			if (isset($post['attachment'])) {
				$post['type'] 		= '2'; //[type] => 2 for file upload
			}else{
				$post['type'] 		= '1'; //[type] => 1
			}

			$result = $this->Chat_Model->action($post);
			if ($result) {
				$data = $this->chat_sync(['cocid' => $post['cocid'], 'fromto' => $post['fromid']]);
				$jsonArray = array("status"=>'1', "message"=>'chat inserted sucessfully', "result"=>$data);
			}
			
		}elseif($this->input->post() && $this->input->post('id') && $this->input->post('action') == "delete" && $this->input->post('coc_id') && $this->input->post('user_id')) { // id =chat id
			$delete = $this->db->delete('chat', ['id' => $this->input->post('id')]);
			if ($delete) {
				$data = $this->chat_sync(['cocid' => $this->input->post('coc_id'), 'fromto' => $this->input->post('user_id')]);
				$jsonArray = array("status"=>'1', "message"=>'chat deleted sucessfully', "result"=>$data);
			}
		}elseif($this->input->post() && $this->input->post('id') && $this->input->post('action') == "quote" && $this->input->post('coc_id') && $this->input->post('user_id')) { // id =chat id
			$chat = $this->chat_sync(['id' => $this->input->post('id')]);
			if ($chat) {
				$id 				= $this->input->post('id');
				$request['quote'] 	= $this->input->post('quote_message');
				$this->db->update('chat', $request, ['id' => $id]);
				$insertid = $id;
				$data = $this->chat_sync(['cocid' => $this->input->post('coc_id'), 'fromto' => $this->input->post('user_id')]);
				$jsonArray = array("status"=>'1', "message"=>'quote added sucessfully', "result"=>$data);

			}else{
				$jsonArray = array("status"=>'0', "message"=>'No Chat found', "result"=>[]);
			}
		}else{
			$jsonArray = array("status"=>'0', "message"=>'invalid request', "result"=>[]);
		}
		echo json_encode($jsonArray);
	}
	public function chat_sync($data = []){
		$result 			= $this->Chat_Model->getList('all', $data);
		if(count($result)){
			foreach ($result as $key => $value) {
				if ($value['attachment'] !='') {
					$attachment = base_url().'assets/uploads/chat/'.$data['cocid'].'/'.$value['attachment'].'';
				}else{
					$attachment = '';
				}
				$jsonData['chatdata'][] = [ 'id' => $value['id'], 'coc_id' => $value['coc_id'], 'from_id' => $value['from_id'], 'to_id' => $value['to_id'], 'quote' => $value['quote'], 'message' => $value['message'], 'attachment' => $attachment, 'name' => $value['name'], 'chatdate' => date('d-m-Y', strtotime($value['created_at']))
				];
			}
		}
		return $jsonData;
	}

	public function mycpd_current_year(){

		if ($this->input->post() && $this->input->post('user_id')) {
			$jsonData 					= [];
			$jsonData['page_lables'] 	= [];
			$jsonData['results'] 		= [];

			$user_id 		= $this->input->post('user_id');
			$pagestatus 	= '1';
			$post['pagestatus'] = $pagestatus;

			$totalcount 	= $this->Mycpd_Model->getQueueList('count', ['status' => [$pagestatus], 'user_id' => [$user_id]]+$post);
			$results 		= $this->Mycpd_Model->getQueueList('all', ['status' => [$pagestatus], 'user_id' => [$user_id]]+$post);
			$mycpd 			= $this->userperformancestatus(['userid' => $user_id, 'performancestatus' => '1', 'auditorstatement' => '1']);
			$userdata		= $this->Plumber_Model->getList('row', ['id' => $user_id], ['users', 'usersdetail', 'usersplumber', 'company']);

			$jsonData['page_lables'] = [ 'mycpd' => 'My CPD points', 'logcpd' => 'Log your CPD points', 'activity' => 'PIRB CPD Activity', 'date' => 'The Date', 'comments' => 'Comments', 'documents' => 'Supporting Documents', 'files' => 'Choose Files', 'declaration' => 'I declare that the information contained in this CPD Activity form is complete, accurate and true. I further declare that I understand that I must keep verifiable evidence of all my CPD Activities for at least two years, as the PIRB may conduct a random audit of my activities, which would require me to submit the evidence to the PIRB.', 'or' => 'OR', 'previouscpd' => 'Your Previous CPD Points'
			];
			$jsonData['total_cpd_point'] 	= $mycpd;
			$jsonData['renewal_cpd_point'] 	= '';
			$jsonData['plumber_details'][] 	= ['registration_no' => $userdata['registration_no'], 'name_surname' => $userdata['name'].' '.$userdata['surname']];
			foreach ($results as $key => $value) {

				if ($value['status'] == '0') {
					$status = 'Pending';
					$statusicons = '';
				}elseif($value['status'] == '1'){
					$status = 'Approve';
					$statusicons = '';
				}elseif($value['status'] == '2'){
					$status = 'Reject';
					$statusicons = '';
				}elseif($value['status'] == '3'){
					$status = 'Not Submitted';
					$statusicons = '';
				}
				$jsonData['results'][] = [ 'dateofactivity' => date('d/m/Y', strtotime($value['cpd_start_date'])), 'activity' => $value['cpd_activity'], 'status' => $value['status'], 'status_words' => $status, 'stausicons' => $statusicons, 'cpdpoints' => $value['points'], 'userid' => $value['user_id'], 'cpdid' => $value['id'], 
				];
			}

			if (count($results) > 0) {
				$jsonArray 	= array("status"=>'1', "message"=>'My CPD', "result"=>$jsonData);
			}else{
				$jsonArray 	= array("status"=>'0', "message"=>'No Record Found', "result"=>[]);
			}

			
		}else{
			$jsonArray 		= array("status"=>'0', "message"=>'invalid request', "result"=>[]);
		}
		echo json_encode($jsonArray);
	}
	public function cpd_search_activity(){
		if ($this->input->post() && $this->input->post('user_id')) {
			$jsonData 	= [];
			$userid 	= $this->input->post('user_id');
			$keyword 	= $this->input->post('keyword');

			if ($keyword != '') {
				$data 		=   $this->Mycpd_Model->autosearchActivity(['search_keyword' => $keyword]);
				foreach ($data as $key => $value) {
					$jsonData['cpd_data'][] = [ 'actid' =>$value['id'], 'activityname' => $value['activity'], 'streamid' => $value['cpdstream'], 'points' => $value['points'], 'startdate' => date('m-d-Y', strtotime($value['startdate'])), 'plumberid' => $userid
					];
				}
				if (count($data) > 0) {
					$jsonArray 	= array("status"=>'1', "message"=>'My CPD', "result"=>$jsonData);
				}else{
					$jsonArray 	= array("status"=>'0', "message"=>'No Record Found', "result"=>[]);
				}
			}else{

				$currentDate = date('Y-m-d H:i:s');

				$this->db->select('cp1.id, cp1.activity, cp1.startdate, cp1.points, cp1.cpdstream');
				$this->db->from('cpdtypes cp1');

				$this->db->where('cp1.status="1"');
				$this->db->where('cp1.startdate<="'.$currentDate.'"');
				$this->db->where('cp1.enddate>"'.$currentDate.'"');
				
				$this->db->group_by("cp1.id");		
				$query = $this->db->get();
				$data = $query->result_array(); 

				foreach ($data as $key => $value) {
					$jsonData['cpd_data'][] = [ 'actid' =>$value['id'], 'activityname' => $value['activity'], 'streamid' => $value['cpdstream'], 'points' => $value['points'], 'startdate' => date('m-d-Y', strtotime($value['startdate'])), 'plumberid' => $userid
					];
				}

				if (count($data) > 0) {
					$jsonArray 	= array("status"=>'1', "message"=>'My CPD', "result"=>$jsonData);
				}else{
					$jsonArray 	= array("status"=>'0', "message"=>'No Record Found', "result"=>[]);
				}

			}

		}else{
			$jsonArray 		= array("status"=>'0', "message"=>'invalid request', "result"=>[]);
		}
		echo json_encode($jsonArray);
	}

	public function mycpd_previous_year(){

		if ($this->input->post() && $this->input->post('user_id')) {
			$jsonData 					= [];
			$jsonData['page_lables'] 	= [];
			$jsonData['results'] 		= [];

			$user_id 		= $this->input->post('user_id');
			$pagestatus 	= '0';
			$post['pagestatus'] = $pagestatus;

			$totalcount 	= $this->Mycpd_Model->getQueueList('count', ['status' => [$pagestatus], 'user_id' => [$user_id]]+$post);
			$results 		= $this->Mycpd_Model->getQueueList('all', ['status' => [$pagestatus], 'user_id' => [$user_id]]+$post);
			
			$jsonData['page_lables'] = [ 'mycpd' => 'My CPD points', 'logcpd' => 'Log your CPD points', 'activity' => 'PIRB CPD Activity', 'date' => 'The Date', 'comments' => 'comments', 'documents' => 'Supporting Documents', 'files' => 'Choose Files', 'declaration' => 'I declare that the information contained in this CPD Activity form is complete, accurate and true. I further decalre that I understadn that I must keep verifiable evidence of all the CPD activities for at least 2 years and the PRIB may conduct a random audit of my activity(s) which would require me to submit the evidence to the PIRB', 'or' => 'OR', 'previouscpd' => 'Your Previous CPD Points'
			];

			foreach ($results as $key => $value) {

				if ($value['status'] == '0') {
					$status = 'Pending';
					$statusicons = '';
				}elseif($value['status'] == '1'){
					$status = 'Approve';
					$statusicons = '';
				}elseif($value['status'] == '2'){
					$status = 'Reject';
					$statusicons = '';
				}
				$jsonData['results'][] = [ 'dateofactivity' => date('d/m/Y', strtotime($value['cpd_start_date'])), 'activity' => $value['cpd_activity'], 'status' => $status, 'stausicons' => $statusicons, 'cpdpoints' => $value['points'], 'userid' => $value['user_id'], 'cpdid' => $value['id']
				];
			}

			if (count($results) > 0) {
				$jsonArray 	= array("status"=>'1', "message"=>'My CPD', "result"=>$jsonData);
			}else{
				$jsonArray 	= array("status"=>'0', "message"=>'No Record Found', "result"=>[]);
			}

			
		}else{
			$jsonArray 		= array("status"=>'0', "message"=>'invalid request', "result"=>[]);
		}
		echo json_encode($jsonArray);
	}
	public function mycpd_view(){
		if ($this->input->post() && $this->input->post('cpdid') && $this->input->post('pagestatus')) {
			$cpdID 			= $this->input->post('cpdID');
			$pagestatus 	= $this->input->post('pagestatus');
			$base_url 		= base_url();
			$file1 			= '';
			$file2 			= '';

			$result 		= $this->Mycpd_Model->getQueueList('row', ['id' => $cpdID, 'pagestatus' => $pagestatus]);
			if (count($result) > 0) {
					if ($result['file1'] !='') {
						$file1 			= $base_url.'assets/uploads/cpdqueue/'.$result['file1'];
					}if ($result['file2'] !='') {
						$file2 			= $base_url.'assets/uploads/cpdqueue/'.$result['file2'];
					}
					$jsonData['result'] = [ 'id' => $result['id'], 'user_id' => $result['user_id'], 'cpd_activity' => $result['cpd_activity'], 'cpd_start_date' => date('d/m/Y', strtotime($result['cpd_start_date'])), 'comments' => $result['comments'], 'file1' => $file1,'file2' => $file2,'admin_comments' => $result['admin_comments'],'status' => $this->config->item('approvalstatus')[$result['status']]
					]; 
			}
			$jsonData['page_lables'] = [ 'heading' => 'View your CPD Activity', 'subheading1' => 'PIRB CPD Activity', 'subheading2' => 'The Date', 'subheading3' => 'comments', 'subheading4' => 'Supporting Documents', 'heading1' => 'PIRB Office Purpose', 'subheading5' => 'CPD Activity Approval Status:', 'status1' => 'Approved', 'status2' => 'Rejected', 'subheading6' => 'Admin Comments:'
			];
			$jsonArray 	= array("status"=>'1', "message"=>'My CPD', "result"=>$jsonData);

		}else{
			$jsonArray 		= array("status"=>'0', "message"=>'invalid request', "result"=>[]);
		}
		echo json_encode($jsonArray);
	}

	public function mycpd_edit_view(){

		if ($this->input->post() && $this->input->post('cpdID') && $this->input->post('pagestatus') !='') {
			$jsonData 					= [];
			$jsonData['page_lables'] 	= [];
			$jsonData['result'] 		= [];
			$base_url 					= base_url();

			$cpdID 			= $this->input->post('cpdID');
			if ($this->input->post('pagestatus') =='2' || $this->input->post('pagestatus') =='0') {
				$pagestatus 	= '0';
			}else{
				$pagestatus 	= $this->input->post('pagestatus');
			}

			$result 		= $this->Mycpd_Model->getQueueList('row', ['id' => $cpdID, 'pagestatus' => $pagestatus]);

			$jsonData['page_lables'] = [ 'mycpd' => 'My CPD points', 'logcpd' => 'Log your CPD points', 'activity' => 'PIRB CPD Activity', 'date' => 'The Date', 'comments' => 'Comments', 'documents' => 'Supporting Documents', 'files' => 'Choose Files', 'declaration' => 'I declare that the information contained in this CPD Activity form is complete, accurate and true. I further decalre that I understadn that I must keep verifiable evidence of all the CPD activities for at least 2 years and the PRIB may conduct a random audit of my activity(s) which would require me to submit the evidence to the PIRB', 'or' => 'OR', 'previouscpd' => 'Your Previous CPD Points', 'renewalcpd' => 'CPD points needed for renewal'
			];

			if ($result) {
				if ($result['status'] == '0') {
						$status = 'Pending';
						$statusicons = '';
					}elseif($result['status'] == '1'){
						$status = 'Approve';
						$statusicons = '';
					}elseif($result['status'] == '2'){
						$status = 'Reject';
						$statusicons = '';
					}

				$jsonData['result'] = [ 'dateofactivity' => date('d/m/Y', strtotime($result['cpd_start_date'])), 'activity' => $result['cpd_activity'], 'status' => $status, 'stausicons' => $statusicons, 'cpdpoints' => $result['points'], 'comments' => $result['comments'], 'admindocument' => ''.$base_url.'assets/uploads/cpdqueue/'.$result['file1'].'', 'plumberdocument' => ''.$base_url.'assets/uploads/cpdqueue/'.$result['file2'].'','cpdstreamid' => $result['cpd_stream'], 'userid' => $result['user_id'], 'cpdid' => $result['id'], 'renewalcpd' => '', 'admin_comments' => $result['admin_comments']
					];

					$jsonArray 	= array("status"=>'1', "message"=>'My CPD', "result"=>$jsonData);
			}else{
				$jsonArray 	= array("status"=>'0', "message"=>'No Record Found', "result"=>[]);
			}
		}else{
			$jsonArray 		= array("status"=>'0', "message"=>'invalid request', "result"=>[]);
		}
		echo json_encode($jsonArray);
	}

	public function mycpd_action(){

		if ($this->input->post() && $this->input->post('user_id')) {
			
			$pagestatus 	= '1';

			$this->form_validation->set_rules('activity','CPD Activity','trim|required');
			$this->form_validation->set_rules('startdate','Start date','trim|required');

			if ($this->form_validation->run()==FALSE) {
				$errorMsg = implode(",", validation_errors());
				$jsonArray = array("status"=>'0', "message"=>$errorMsg, 'result' => []);
			}else{
				$requestData1 		= [];

				$post 				= $this->input->post();
				if($this->input->post('id') !=''){$id = $this->input->post('id');}else{$id = '';}
				$plumberID 			= $this->input->post('user_id');
				$datetime			= date('Y-m-d H:i:s');

				if (isset($post['image1']) && $post['image1'] != '') {
					$data = $this->fileupload(['files' => $post['image1'], 'file_name' => $post['file_name'], 'user_id' => $plumberID, 'page' => 'plumbercpd']);
					$image = $data[0];
				}

				if(isset($post['hidden_regnumber'])) 	$requestData1['reg_number']    		= $post['hidden_regnumber'];
				if(isset($post['user_id']))  			$requestData1['user_id'] 	    	= $post['user_id'];
				if(isset($post['name_surname']))  		$requestData1['name_surname']  		= $post['name_surname'];
				if(isset($post['activity'])) 			$requestData1['cpd_activity']  		= $post['activity'];
				if(isset($post['startdate'])) 	 		$requestData1['cpd_start_date'] 	= date("Y-m-d H:i:s", strtotime(str_replace('/','-',$post['startdate'])));
				if(isset($post['comments'])) 	 		$requestData1['comments'] 			= $post['comments'];
				if(isset($image)) 		 				$requestData1['file1'] 				= $image;
				if(isset($post['points'])) 		 		$requestData1['points'] 			= $post['points'];
				if(isset($post['hidden_stream_id'])) 	$requestData1['cpd_stream'] 		= $post['hidden_stream_id'];
				// 1- submit 2- save
				if ($this->input->post('submit') == '1') {
					$requestData1['status'] 												= '0';
				}else{
					$requestData1['status'] 												= '3';
				}

				if ($id=='') {
					$requestData1['created_at'] = 	$datetime;
					$requestData1['created_by']	= 	$plumberID;
					$result = $this->db->insert('cpd_activity_form', $requestData1);
					$message = 'My CPD Inserted Successfully';
				}else{
					$requestData1['updated_at'] = 	$datetime;
					$requestData1['updated_by']	= 	$plumberID;
					$result = $this->db->update('cpd_activity_form', $requestData1, ['id' => $cpd_id]);
					$message = 'My CPD Updated Successfully';
				}

				$jsonArray = array("status"=>'1', "message"=>$message, "result"=>$requestData1);
			}
			
		}else{
			$jsonArray 		= array("status"=>'0', "message"=>'invalid request', "result"=>[]);
		}
		echo json_encode($jsonArray);
	}

	// public function mycpd_edit_action(){

	// 	if ($this->input->post() && $this->input->post('pagestatus') && $this->input->post('user_id') && $this->input->post('cpd_id')) {
			
	// 		$pagestatus 	= $this->input->post('pagestatus');

	// 		$this->form_validation->set_rules('activity','CPD Activity','trim|required');
	// 		$this->form_validation->set_rules('startdate','Start date','trim|required');

	// 		if ($this->form_validation->run()==FALSE) {
	// 			$errorMsg = implode(",", validation_errors());
	// 			$jsonArray = array("status"=>'0', "message"=>$errorMsg, 'result' => []);
	// 		}else{
	// 			$requestData1 		= [];

	// 			$post 				= $this->input->post();
	// 			$plumberID 			= $this->input->post('user_id');
	// 			$cpd_id 			= $this->input->post('cpd_id');
	// 			$datetime			= date('Y-m-d H:i:s');

	// 			if ($post['image1'] != '') {
	// 				$data = $this->fileupload(['files' => $post['image1'], 'user_id' => $plumberID, 'page' => 'plumbercpd']);
	// 				$image = base64_decode($data);
	// 				print_r($data);die;
	// 			}

	// 			if(isset($post['hidden_regnumber'])) 	$requestData1['reg_number']    		= $post['hidden_regnumber'];
	// 			if(isset($post['user_id']))  			$requestData1['user_id'] 	    	= $post['user_id'];
	// 			if(isset($post['name_surname']))  		$requestData1['name_surname']  		= $post['name_surname'];
	// 			if(isset($post['activity'])) 			$requestData1['cpd_activity']  		= $post['activity'];
	// 			if(isset($post['startdate'])) 	 		$requestData1['cpd_start_date'] 	= date("Y-m-d H:i:s", strtotime($post['startdate']));
	// 			if(isset($post['comments'])) 	 		$requestData1['comments'] 			= $post['comments'];
	// 			if(isset($image)) 		 				$requestData1['file1'] 				= $image;
	// 			if(isset($post['points'])) 		 		$requestData1['points'] 			= $post['points'];
	// 			if(isset($post['hidden_stream_id'])) 	$requestData1['cpd_stream'] 		= $post['hidden_stream_id'];
				
	// 			if ($this->input->post('submit') == 'submit') {
	// 				$requestData1['status'] 												= '0';
	// 			}else{
	// 				$requestData1['status'] 												= '3';
	// 			}
				
				

	// 			$jsonArray = array("status"=>'1', "message"=>'My CPD Updated Successfully', "result"=>$result);
	// 		}
			
	// 	}else{
	// 		$jsonArray 		= array("status"=>'0', "message"=>'invalid request', "result"=>[]);
	// 	}
	// 	echo json_encode($jsonArray);
	// }

	public function noncompliance_coc(){

		if ($this->input->post() && $this->input->post('user_id') && $this->input->post('pagetype') =='view') {
			$jsonData = [];

			if ($this->input->post('request_type') !='' && $this->input->post('request_type') =='ajaxgetinstallationtype') {

				$data		= $this->getInstallationTypeList_api();
				$message 	= 'Installation Types';
				
			}elseif ($this->input->post('request_type') !='' && $this->input->post('request_type') =='ajaxgetsubtype' && $this->input->post('installationtypeid') !='') {

				$installationtypeid		= $this->input->post('installationtypeid');
				$data					= $this->getSubTypeList_api(['installationtypeid' => $installationtypeid]);
				$message 				= 'Sub Types';

			}elseif ($this->input->post('request_type') !='' && $this->input->post('request_type') =='ajaxreportreportlisting' && $this->input->post('installationtypeid') !='' && $this->input->post('subtypeid') !='') {

				$installationtypeid		= $this->input->post('installationtypeid');
				$subtypeid 				= $this->input->post('subtypeid');
				$data					= $this->getreportlisting_api(['installationtypeid' => $installationtypeid, 'subtypeid' => $subtypeid]);
				$message 				= 'Non Compliance Report';

			}elseif ($this->input->post('request_type') !='' && $this->input->post('request_type') =='imageupload') {

				$post = $this->input->post();
				if ($post['nc_file'] != '') {
					$data = $this->fileupload(['files' => $post['nc_file'], 'file_name' => $post['file_name'], 'user_id' => $post['user_id'], 'page' => 'noncompliance_coc_image']);
					$message 		= 'Non Compliance Images';
				}
			}

			$jsonData['page_lables'] = [ 'heading' => 'Pre-existing Non Compliance Conditions', 'installation' => 'Installation Type', 'subtype' => 'Sub Type', 'statement' => 'Statement', 'nc_details' => 'Non compliance details', 'remedi' => 'Possible remedial actions', 'sans' => 'SANS / Regulation / Bylaw Reference', 'img' => 'images', 'addimg' => 'Add images'
			];
			$jsonArray 		= array("status"=>'1', "message"=>$message, "result"=>$data);
		}elseif ($this->input->post() && $this->input->post('user_id') && $this->input->post('id') && $this->input->post('pagetype') =='edit') {

			$id 	= $this->input->post('id'); // id = non compliance id
			$userid = $this->input->post('user_id');
			$result = $this->Noncompliance_Model->getList('row', ['id' => $id]);
			if ($result) {
				$message 		= 'Non Compliance Data';

				if ($result['file'] !='') {
					if(strpos($result['file'], ',') !== false){
						$imgarray = explode(",",$result['file']);
						foreach ($imgarray as $key => $images) {
							$jsonData['nc_images'][] = [
								'file' 	=> base_url().'assets/uploads/plumber/'.$userid.'/log/'.$images 
							];
						}
					}else{
						$jsonData['nc_images'][] = [
								'file' 	=> base_url().'assets/uploads/plumber/'.$userid.'/log/'.$result['file'] 
							];
					}
				}else{
					$jsonData['nc_images'][] = [
								'file' 	=> '' 
							];
				}
				$installationtype 	= $this->getInstallationTypeList_api(['id' => $result['installationtype'], 'type' => 'getinstallation']);
				$subtype 			= $this->getSubTypeList_api(['id' => $result['subtype'], 'type' => 'getsubtypes']);
				$statement 			= $this->getreportlisting_api(['id' => $result['statement'], 'type' => 'getstatement']);

				$jsonData['noncompliance_details'][] = [ 'id' => $result['id'], 'user_id' => $result['user_id'], 'coc_id' => $result['coc_id'], 'installationtypeid' => $result['installationtype'], 'subtypeid' => $result['subtype'], 'statementid' => $result['statement'], 'details' => $result['details'], 'action' => $result['action'], 'reference' => $result['reference'], 'installationtype' => $installationtype[0]['name'], 'subtype' => $subtype[0]['name'], 'statement' => $statement[0]['statement']
				];
			}
			$jsonData['page_lables'] = [ 'heading' => 'Pre-existing Non Compliance Conditions', 'installation' => 'Installation Type', 'subtype' => 'Sub Type', 'statement' => 'Statement', 'nc_details' => 'Non compliance details', 'remedi' => 'Possible remedial actions', 'sans' => 'SANS / Regulation / Bylaw Reference', 'img' => 'images', 'addimg' => 'Add images'
			];
			$jsonArray 		= array("status"=>'1', "message"=>$message, "result"=>$jsonData);

		}elseif ($this->input->post() && $this->input->post('user_id') && $this->input->post('id') && $this->input->post('pagetype') =='delete') {

			$id 	= $this->input->post('id');
			$userid = $this->input->post('user_id');
			$result = $this->Noncompliance_Model->delete($id);
			if ($result) {
				$message 		= 'Non Compliance Deleted Sucessfully';
			}
			$jsonArray 		= array("status"=>'1', "message"=>$message, "result"=>['user_id' => $userid]);
		}
		else{
			$jsonArray 		= array("status"=>'0', "message"=>'invalid request', "result"=>[]);
		}
		echo json_encode($jsonArray);
	}

	public function noncompliance_coc_action(){
		if ($this->input->post() && $this->input->post('user_id') && $this->input->post('coc_id')) {

			$this->form_validation->set_rules('installationtype','installationtype','trim|required');
			$this->form_validation->set_rules('subtype','subtype','trim|required');
			$this->form_validation->set_rules('statement','statement','trim|required');
			$this->form_validation->set_rules('details','details','trim|required');
			$this->form_validation->set_rules('action','action','trim|required');
			$this->form_validation->set_rules('reference','reference','trim|required');

			if ($this->form_validation->run()==FALSE) {
				$errorMsg = validation_errors();
				$jsonArray = array("status"=>'0', "message"=>$errorMsg, 'result' => []);
			}else{
				$post 			= 	$this->input->post();
				$userid			= 	$post['user_id'];
				if (isset($post['id'])) {
					$id 			= 	$post['id']; //noncompliance id
				}else{
					$id 			= 	''; //noncompliance id
				}
				
				$datetime		= 	date('Y-m-d H:i:s');
				
				$request		=	[
					'updated_at' 		=> $datetime,
					'updated_by' 		=> $userid
				];

				$request['user_id'] 													= $userid;
				$request['coc_id'] 														= $post['coc_id'];
				if(isset($post['installationtype'])) 	$request['installationtype'] 	= $post['installationtype'];
				if(isset($post['subtype'])) 			$request['subtype'] 			= $post['subtype'];
				if(isset($post['statement'])) 			$request['statement'] 			= $post['statement'];
				if(isset($post['details'])) 			$request['details'] 			= $post['details'];
				if(isset($post['action'])) 				$request['action'] 				= $post['action'];
				if(isset($post['reference'])) 			$request['reference'] 			= $post['reference'];
				
				
				$request['file'] 	= (isset($post['file'])) ? $post['file'] : '';

				$request['status'] 	= (isset($post['status'])) ? $post['status'] : '0';
				
				if($id==''){
					$request['created_at'] = $datetime;
					$request['created_by'] = $userid;
					$this->db->insert('noncompliance', $request);
					$insertid = $this->db->insert_id();
					$request['id'] = $insertid;
					$message = 'Non-Compliance inserted successfully';
					$jsonArray 		= array("status"=>'0', "message"=>$message, "result"=>$request);
				}else{
					$this->db->update('noncompliance', $request, ['id' => $id]);
					$insertid = $id;
					$request['id'] = $insertid;
					$message = 'Non Compliance Updated Sucessfully';
					$jsonArray 		= array("status"=>'0', "message"=>$message, "result"=>$request);
				}

			}
		}else{
				$jsonArray 		= array("status"=>'0', "message"=>'invalid request', "result"=>[]);
			}
		echo json_encode($jsonArray);
	}

	// Auditor API
	public function myreport_listing(){

		if ($this->input->post() && $this->input->post('user_id') && $this->input->post('pagetype') =='view') {
			$jsonData = [];

			if ($this->input->post('request_type') !='' && $this->input->post('request_type') =='ajaxgetinstallationtype') {

				$data		= $this->getInstallationTypeList_api();
				$message 	= 'Installation Types';
				
			}elseif ($this->input->post('request_type') !='' && $this->input->post('request_type') =='ajaxgetsubtype' && $this->input->post('installationtypeid') !='') {

				$installationtypeid		= $this->input->post('installationtypeid');
				$data					= $this->getSubTypeList_api(['installationtypeid' => $installationtypeid]);
				$message 				= 'Sub Types';

			}elseif ($this->input->post('request_type') !='' && $this->input->post('request_type') =='ajaxreportreportlisting' && $this->input->post('installationtypeid') !='' && $this->input->post('subtypeid') !='') {

				$installationtypeid		= $this->input->post('installationtypeid');
				$subtypeid 				= $this->input->post('subtypeid');
				$data					= $this->getreportlisting_api(['installationtypeid' => $installationtypeid, 'subtypeid' => $subtypeid]);
				$message 				= 'Statement';
			}elseif ($this->input->post('request_type') !='' && $this->input->post('request_type') =='edit_view' && $this->input->post('id') !='') {
				$result = $this->Auditor_Reportlisting_Model->getList('row', ['id' => $this->input->post('id')]);
				$get_installationtype 	= $this->getInstallationTypeList_api(['id' => $result['installationtype_id']]);
				$get_subtype 			= $this->getSubTypeList_api(['id' => $result['subtype_id']]);
				$get_statement 			= $this->getreportlisting_api(['id' => $result['statement_id']]);

				if ($result['status'] =='1') {
							$colorcode = "#A2D831";
						}else{
							$colorcode = "#EB3120";
						}
				$data['report_list'][] = [
					'id'					 => $result['id'],
					'installationtype_id' 	=> $result['installationtype_id'],
					'isntallation_type' 	=> $get_installationtype[0]['name'],
					'subtype_id' 			=> $result['subtype_id'],
					'subtype' 				=> $get_subtype[0]['name'],
					'comments'				=> $result['comments'],
					'favourname'			=> $result['favour_name'],
					'status' 				=> $result['status'],
					'statement_id' 			=> $result['statement_id'],
					'statementname' 		=> $get_statement[0]['statement'],
					'colorcode' 			=> $colorcode
				];
				$message = 'Report List Edit View';
			}

			$jsonData['page_lables'] = [];
			$jsonArray 		= array("status"=>'1', "message"=>$message, "result"=>$data);
		}elseif($this->input->post() && $this->input->post('user_id') && $this->input->post('pagetype') =='get_reportlists'){
			if ($this->input->post('request_type') !='' && $this->input->post('request_type') =='list') {
				$results = $this->Auditor_Reportlisting_Model->getList('all', ['user_id' => $this->input->post('user_id'), 'status' => ['0','1']]);

				if (count($results) > 0) {
					foreach ($results as $key => $value) {
						if ($value['status'] =='1') {
							$colorcode = "#A2D831";
						}else{
							$colorcode = "#EB3120";
						}
					$get_installationtype 	= $this->getInstallationTypeList_api(['id' => $value['installationtype_id']]);
					$get_subtype 			= $this->getSubTypeList_api(['id' => $value['subtype_id']]);
					$get_statement 			= $this->getreportlisting_api(['id' => $value['statement_id']]);
					$jsonData['report_list'][] = ['id' => $value['id'], 'installationtype_id' => $value['installationtype_id'], 'isntallation_type' => $get_installationtype[0]['name'], 'subtype_id' => $value['subtype_id'], 'subtype' => $get_subtype[0]['name'], 'comments' => $value['comments'], 'statusicon' => $this->config->item('statusicon')[$value['status']], 'favourname' => $value['favour_name'], 'statement_id' => $value['statement_id'], 'statementname' => $get_statement, 'colorcode' => $colorcode, 'status' => $value['status']];
					}
				}
				$message = 'My Report Listing';
				
			}elseif ($this->input->post('request_type') !='' && $this->input->post('request_type') =='search') {
				$jsonData = [];
				$jsonData['results'] = [];

				$keywords 		= $this->input->post('keywords');
				$userid 		= $this->input->post('user_id');
				$post 			= $this->input->post();
				$totalcount 	= $this->Auditor_Reportlisting_Model->getList('count', ['coc_status' => ['2'], 'user_id' => $userid, 'search' => ['value' => $keywords], 'status' => ['0','1']]);
				$results 		= $this->Auditor_Reportlisting_Model->getList('all', ['coc_status' => ['2'], 'user_id' => $userid, 'search' => ['value' => $keywords], 'status' => ['0','1']]);
				if ($results) {
					foreach ($results as $key => $value) {
						$get_installationtype 	= $this->getInstallationTypeList_api(['id' => $value['installationtype_id']]);
						$get_subtype 			= $this->getSubTypeList_api(['id' => $value['subtype_id']]);
						$get_statement 			= $this->getreportlisting_api(['id' => $value['statement_id']]);
						if ($value['status'] =='1') {
							$colorcode = "#A2D831";
						}else{
							$colorcode = "#EB3120";
						}
						$jsonData['report_list'][] = ['id' => $value['id'], 'installationtype_id' => $value['installationtype_id'], 'isntallation_type' => $get_installationtype[0]['name'], 'subtype_id' => $value['subtype_id'], 'subtype' => $get_subtype[0]['name'], 'comments' => $value['comments'], 'statusicon' => $this->config->item('statusicon')[$value['status']], 'favourname' => $value['favour_name'], 'statement_id' => $value['statement_id'], 'statementname' => $get_statement, 'colorcode' => $colorcode, 'status' => $value['status']];
					}
				}
				$message = 'My Report Listing Search Result';
			}

			$jsonArray 		= array("status"=>'1', "message"=>$message, "result"=>$jsonData);
		}elseif ($this->input->post() && $this->input->post('user_id') && $this->input->post('pagetype') =='action') {

			$this->form_validation->set_rules('installation','installationtype','trim|required');
			$this->form_validation->set_rules('subtype','subtype','trim|required');
			$this->form_validation->set_rules('statement','statement','trim|required');
			$this->form_validation->set_rules('comment','comments','trim|required');
			$this->form_validation->set_rules('favour_name','favour name','trim|required');

			if ($this->form_validation->run()==FALSE) {
				$errorMsg = validation_errors();
				$jsonArray = array("status"=>'0', "message"=>$errorMsg, 'result' => []);
			}else{
				$post 			= $this->input->post();
				$userid			= 	$this->input->post('user_id');
				if($this->input->post('id') !=''){$id = $this->input->post('id');}else{$id ='';}
				$datetime		= 	date('Y-m-d H:i:s');
				$request		=	[

					'updated_at' 		=> $datetime,
					'updated_by' 		=> $userid
				];
			
				$request['user_id'] = $userid;	
				if(isset($post['installation'])) 	$request['installationtype_id'] 	= $post['installation'];
				if(isset($post['subtype'])) 		$request['subtype_id'] 				= $post['subtype'];
				if(isset($post['statement'])) 		$request['statement_id'] 			= $post['statement'];
				if(isset($post['comment'])) 		$request['comments'] 				= $post['comment'];
				if(isset($post['favour_name'])) 	$request['favour_name'] 			= $post['favour_name'];
				$request['status'] 	= (isset($post['status'])) ? $post['status'] : '0';
				if($id==''){
					$request['created_at'] = $datetime;
					$request['created_by'] = $userid;
					$this->db->insert('auditor_report_listing', $request);
					$insert_id = $this->db->insert_id();
					$message = "My Report Listing Added Sucessfully";
				}else{
					$this->db->update('auditor_report_listing', $request, ['id' => $id]);
					$insert_id = $id;
					$message = "My Report Listing Updated Sucessfully";
				}
				$jsonArray 		= array("status"=>'1', "message"=>$message, "result"=>$insert_id);

			}


		}elseif ($this->input->post() && $this->input->post('user_id') && $this->input->post('id') && $this->input->post('pagetype') =='delete') {

			$id 		= $this->input->post('id');
			$userid 	= $this->input->post('user_id');
			$datetime	= 	date('Y-m-d H:i:s');
			$delete 	= 	$this->db->update('auditor_report_listing', ['status' => '2', 'updated_at' => $datetime, 'updated_by' => $userid], ['id' => $id]);
			if ($delete) {
				$message 		= 'My Report Listing Deleted Sucessfully';
			}
			$jsonArray 		= array("status"=>'1', "message"=>$message, "result"=>['user_id' => $userid]);
		}
		else{
			$jsonArray 		= array("status"=>'0', "message"=>'invalid request', "result"=>[]);
		}
		echo json_encode($jsonArray);
	}

	public function auditstatement_auditor(){
		if ($this->input->post() && $this->input->post('user_id') && $this->input->post('type') == 'list') {
			$userid = $this->input->post('user_id');

			$totalcount 	= $this->Api_Model->getCOCList('count', ['coc_status' => ['2'], 'auditorid' => $userid, 'api_data' => 'auditstatement_auditor']);
			$results 		= $this->Api_Model->getCOCList('all', ['coc_status' => ['2'], 'auditorid' => $userid, 'api_data' => 'auditstatement_auditor']);
			if ($results) {
				foreach ($results as $key => $value) {
					$jsonData['auditstatement'][] = ['id' => $value['id'], 'plumbedid' => $value['user_id'], 'plumbedname' => $value['u_name'], 'plumbedmobile' => $value['u_mobile'], 'auditorid' => $value['auditorid'], 'audit_status' => $this->config->item('auditstatus')[$value['audit_status']], 'audit_allocation_date' => date('d-m-Y', strtotime($value['audit_allocation_date'])), 'as_refixcompletedate' => date('d-m-Y', strtotime($value['as_refixcompletedate'])), 'as_refixcompletedate' => date('d-m-Y', strtotime($value['as_refixcompletedate'])), 'cl_suburb_name' => $value['cl_suburb_name'], 'cl_name' => $value['cl_name'], 'cl_contact_no' => $value['cl_contact_no'], 'as_refix_duecompletedate' => ''];
				}
			}
			$jsonArray 		= array("status"=>'1', "message"=>'Audit Statement', "result"=>$jsonData);
		}elseif ($this->input->post() && $this->input->post('user_id') && $this->input->post('type') == 'search' && $this->input->post('keywords')) {
			$userid 		= $this->input->post('user_id');
			$keywords 		= $this->input->post('keywords');
			$post['page'] 	= 'auditorstatement';
			$post['search'] = ['value' => $keywords, 'regex' => false];

			$totalcount 	= $this->Api_Model->getCOCList('count', ['coc_status' => ['2'], 'auditorid' => $userid]+$post);
			$results 		= $this->Api_Model->getCOCList('all', ['coc_status' => ['2'], 'auditorid' => $userid]+$post);
			if ($results) {
				foreach ($results as $key => $value) {
					$jsonData['auditstatement'][] = ['id' => $value['id'], 'plumbedid' => $value['user_id'], 'plumbedname' => $value['u_name'], 'plumbedmobile' => $value['u_mobile'], 'auditorid' => $value['auditorid'], 'audit_status' => $this->config->item('auditstatus')[$value['audit_status']], 'audit_allocation_date' => date('d-m-Y', strtotime($value['audit_allocation_date'])), 'as_refixcompletedate' => date('d-m-Y', strtotime($value['as_refixcompletedate'])), 'as_refixcompletedate' => date('d-m-Y', strtotime($value['as_refixcompletedate'])), 'cl_suburb_name' => $value['cl_suburb_name'], 'cl_name' => $value['cl_name'], 'cl_contact_no' => $value['cl_contact_no'], 'as_refix_duecompletedate' => ''];
				}
			}
			$jsonArray 		= array("status"=>'1', "message"=>'Audit Statement', "result"=>$jsonData);
		}else{
			$jsonArray 		= array("status"=>'0', "message"=>'invalid request', "result"=>[]);
		}
		echo json_encode($jsonArray);
	}

	public function auditor_accounts(){
		if ($this->input->post() && $this->input->post('user_id')) {
			$userid = $this->input->post('user_id');
			$jsonData = [];
			if ($this->input->post('type') == 'list') {
				$totalcount 				= $this->Api_Model->getInvoiceList('count',['user_id' => $userid, 'api_data' => 'auditor_accounts']);
				$results 					= $this->Api_Model->getInvoiceList('all', ['user_id' => $userid, 'api_data' => 'auditor_accounts']);
				
				$jsonData['totalcount']    	= 	$totalcount;
				$jsonData['userid']    		= 	$userid;
				if(count($results) > 0){
					$message = 'Auditor My Accounts';
					foreach($results as $result){
						//$internal_inv = "";
						$originalDate = isset($result['invoice_date']) && $result['invoice_date']!='1970-01-01' && $result['invoice_date']!='0000-00-00' ? date('d-m-Y', strtotime($result['invoice_date'])) : '';
						//$internal_inv = $result['invoice_no'];
						// $newDate = date("d-m-Y", strtotime($originalDate));
						if($result['status'] == '0'){
							$status = "Unpaid";
							$action = '';
						}elseif($result['status'] == '1'){
							$status = "Paid";
							$action = base_url().'assets/inv_pdf/'.$result['inv_id'].'.pdf';
						}else{
							$status = "Not Submitted";
							if($result['status'] == '2'){
								$action = '';
							}
						}
						$jsonData['accounts_results'][] = 	[      
							'inv_id' 		=> 	$result['inv_id'],
							'invoicenumber' => 	$result['invoice_no'],
							'created_at'    =>  $originalDate,
							'description'   =>  $result['description'],
							'total_cost'    => 	$this->config->item('currency').' '.$result['total_cost'],
							'action'	    => 	$action,
							'status'    	=> 	$status

						];
					}
				}else{
					$message = 'No Record Found';
				}
			
			}elseif($this->input->post('type') == 'search' && $this->input->post('keywords') !=''){
				$userid 			= $this->input->post('user_id');
				$keywords 			= $this->input->post('keywords');
				$post['search'] 	= ['value' => $keywords, 'regex' => false];
				$totalcount 				= $this->Api_Model->getInvoiceList('count',['user_id' => $userid]+$post);
				$results 					= $this->Api_Model->getInvoiceList('all', ['user_id' => $userid]+$post);
				$jsonData['totalcount']    	= 	$totalcount;
				$jsonData['userid']    		= 	$userid;
				if(count($results) > 0){
					$message = 'Auditor Accounts Search Results';
					foreach($results as $result){
						$internal_inv = "";
						$originalDate = isset($result['invoice_date']) && $result['invoice_date']!='1970-01-01' && $result['invoice_date']!='0000-00-00' ? date('d-m-Y', strtotime($result['invoice_date'])) : '';
						$internal_inv = $result['invoice_no'];
						// $newDate = date("d-m-Y", strtotime($originalDate));
						if($result['status'] == '0'){
							$status = "Unpaid";
							$action = '';
						}elseif($result['status'] == '1'){
							$status = "Paid";
							$action = base_url().'assets/inv_pdf/'.$result['inv_id'].'.pdf';
						}else{
							$status = "Not Submitted";
							if($result['status'] == '2'){
								$action = '';
							}
						}
						$jsonData['accounts_results'][] = 	[      
							'inv_id' 		=> 	$internal_inv,
							'created_at'    =>  $originalDate,
							'description'   =>  $result['description'],
							'total_cost'    => 	$this->config->item('currency').' '.$result['total_cost'],
							'action'	    => 	$action,
							'status'    	=> 	$status

						];
					}
				}else{
					$message = 'No Record Found';
				}
			}
			$jsonArray 		= array("status"=>'1', "message"=>$message, "result"=>$jsonData);
		}else{
			$jsonArray 		= array("status"=>'0', "message"=>'invalid request', "result"=>[]);
		}
		echo json_encode($jsonArray);
	}
	public function auditor_accounts_action(){
		if ($this->input->post() && $this->input->post('user_id') && $this->input->post('inv_id') && $this->input->post('request_type') =='edit_view') {
			$userid 		= $this->input->post('user_id');
			$inv_id 		= $this->input->post('inv_id');
			// $result 		= $this->Auditor_Model->getInvoiceList('row', $userid);
			// $auditordetail 	= $this->Auditor_Model->getAuditorList('row',$userid);
			$result 		= $this->Auditor_Model->getInvoiceList('row', ['user_id' => $userid]);
			$auditordetail 	= $this->Auditor_Model->getAuditorList('row',['id' => $userid]);
			$settings		= $this->Systemsettings_Model->getList('row');
			$dbVat 			= $settings['vat_percentage'];
			$auditordetail 	= $this->Auditor_Model->getAuditorList('row',['id' => $userid]);
			$billingaddress = explode("@-@",$auditordetail['billingaddress']);
			$vat 		= $this->Coc_Model->getPermissions('row');
			$address2 		= $billingaddress[2];
			$address3 		= $auditordetail['suburb'];
			$address4 		= $auditordetail['city'];
			$address5 		= $auditordetail['province'];
			$work_phone 	= $auditordetail['work_phone'];
			$email 			= $auditordetail['email'];

			$bank_name 		= $auditordetail['bank_name'];
			$branch_code 	= $auditordetail['branch_code'];
			$account_name 	= $auditordetail['account_name'];
			$account_no 	= $auditordetail['account_no'];
			$account_type 	= $auditordetail['account_type'];
			$currency 		= $this->config->item('currency');

			$editid = isset($result['inv_id']) ? $result['inv_id'] : '';
			$vat_vendor = isset($result['vat_vendor']) ? $result['vat_vendor'] : '';
			$description = isset($result['description']) ? $result['description'] : '';	
			$total_cost = isset($result['total_cost']) ? $result['total_cost'] : '';
			$vatvalue = '0.00';
			$total = '0.00';
			if($editid > 0)	{
				if($vat_vendor > 0){
					$vatper = $vat['vat_percentage'];		
					$vat_amount1 = $total_cost * $vatper / 100;
					$vatvalue = $this->currencyconvertor($vat_amount1);

					$total = $this->currencyconvertor($total_cost + $vatvalue);
				}
				else{
					$total = $this->currencyconvertor($total_cost);
				}
			}
			$jsonData['auditor_details'][] = [
				'userid' 		=> $auditordetail['id'],
				'inv_id' 		=> $inv_id,
				'vat_vendor' 	=> $result['vat_vendor'],
				'description' 	=> $result['description'],
				'total_cost' 	=> $result['total_cost'],
				'address2' 		=> $address2,
				'suburb' 		=> $address3,
				'city' 			=> $address4,
				'province' 		=> $address5,
				'work_phone' 	=> $work_phone,
				'email' 		=> $email,
				'companyname'	=> $auditordetail['company_name'],
				'namesurname'	=> $auditordetail['name'].' '.$auditordetail['surname'],
			];
			$jsonData['vat_details'][] = [
				'dbvat' 		=> $dbVat,
			];
			$jsonData['table_content'][] = [
				'description' 	=> $result['description'],
				'sub_total' 	=> $currency.$total_cost,
				'vat' 			=> $currency.$vatvalue,
				'total' 		=> $currency.$total,
			];

			$jsonData['banking_details'][] = [
				'bank_name' 	=> $bank_name,
				'branch_code' 	=> $branch_code,
				'account_name' 	=> $account_name,
				'account_no' 	=> $account_no,
				'account_type' 	=> $account_type
			];
			$jsonArray 		= array("status"=>'1', "message"=>'Auditor Invoice Details', "result"=>$jsonData);
		}elseif ($this->input->post() && $this->input->post('user_id') && $this->input->post('inv_id') && $this->input->post('request_type') =='action') {
			$this->form_validation->set_rules('inv_id','Invoice id','trim|required');
			$this->form_validation->set_rules('invoicedate','Invoice Date','trim|required');
			$this->form_validation->set_rules('invoice_no','Invoice Number','trim|required');
			$this->form_validation->set_rules('total_cost','Total cost','trim|required');
			$this->form_validation->set_rules('total','Total','trim|required');
			$this->form_validation->set_rules('vat','VAT','trim|required');

			if ($this->form_validation->run()==FALSE) {
				$errorMsg = validation_errors();
				$jsonArray = array("status"=>'0', "message"=>$errorMsg, 'result' => []);
			}else{
				$id					= $this->input->post('inv_id');
				$post 				= $this->input->post();
				$request1['status'] = '0';

				if(isset($post['invoicedate']) && $post['invoicedate']!='1970-01-01') $request1['invoice_date'] = date('Y-m-d', strtotime(str_replace('/','-',$post['invoicedate'])));
				if(isset($post['invoice_no'])) $request1['invoice_no'] = $post['invoice_no'];
				if(isset($post['total_cost'])) $request1['total_cost'] = $post['total_cost'];
				if(isset($post['vat'])) $request1['vat'] = $post['vat'];
				if(isset($post['internal_inv'])) $request1['internal_inv'] = $post['internal_inv'];
				if(isset($request1)){	
					$userdata = $this->db->update('invoice', $request1, ['inv_id' => $id]);	
				}

				if(isset($post['total_cost'])) $request2['cost_value'] = $post['total_cost'];
				if(isset($post['vat'])) $request2['vat'] = $post['vat'];		
				if(isset($post['total'])) $request2['total_due'] = $post['total'];
				if(isset($request2)){	
					$userdata = $this->db->update('coc_orders', $request2, ['inv_id' => $id]);	
				}
			}
			$jsonArray 		= array("status"=>'1', "message"=>'Auditor Invoice Added Sucessfully', "result"=>$post);
		}else{
			$jsonArray 		= array("status"=>'0', "message"=>'invalid request', "result"=>[]);
		}
		echo json_encode($jsonArray);
	}
	// 3 Tabs
	public function audit_review(){
		if ($this->input->post() && $this->input->post('coc_id') && $this->input->post('user_id')) {
			$cocid 						= $this->input->post('coc_id');
			$auditorid 					= $this->input->post('user_id');
			$extraparam['auditorid'] 	= $auditorid;
			$workmanshippt				= $this->getWorkmanshipPoint();
			$plumberverificationpt		= $this->getPlumberVerificationPoint();
			$cocverificationpt			= $this->getCocVerificationPoint();
			$settings 					= $this->Systemsettings_Model->getList('row');
			$datetime 					= date('Y-m-d H:i:s');
			$date 						= date('Y-m-d');

			$result	= $this->Coc_Model->getCOCList('row', ['id' => $cocid, 'coc_status' => ['2']]+$extraparam);

			if ($result['as_workmanship'] !='') {
				$as_workmanship 	= $this->config->item('workmanship')[$result['as_workmanship']];
				$workmanship_pts 	= $this->getWorkmanshipPoint()[$result['as_workmanship']];
			}else{
				$as_workmanship 	= '';
				$workmanship_pts 	= '';
			}

			if ($result['as_plumber_verification'] !='') {
				$as_plumber_verification = $this->config->item('yesno')[$result['as_plumber_verification']];
				$plumberverification_pts = $this->getPlumberVerificationPoint()[$result['as_plumber_verification']];
			}else{
				$as_plumber_verification = '';
				$plumberverification_pts = '';
			}
			if ($result['as_coc_verification'] !='') {
				$as_coc_verification = $this->config->item('yesno')[$result['as_coc_verification']];
				$cocverification_pts = $this->getCocVerificationPoint();
			}else{
				$as_coc_verification = '';
				$cocverification_pts = '';
			}
			if ($result['u_file'] !='') {
				$plumberprofile = base_url().'assets/uploads/plumber/'.$result['user_id'].'/'.$result['u_file'].'';
			}else{
				$plumberprofile = '';
			}

			$jsonData['audit_review'][] = [
				'as_id' => $result['as_id'],
				'cocid' => $result['id'],
				'plumber' => $result['user_id'],
				'plumbername' => $result['u_name'],
				'plumberregno' => $result['plumberregno'],
				'u_work' => $result['u_work'],
				'u_mobile' => $result['u_mobile'],
				'cl_completion_date' => $result['cl_completion_date'],
				'cl_name' => $result['cl_name'],
				'cl_address' => $result['cl_address'],
				'cl_street' => $result['cl_street'],
				'cl_number' => $result['cl_number'],
				'cl_number' => $result['cl_number'],
				'cl_province_name' => $result['cl_province_name'],
				'cl_city_name' => $result['cl_city_name'],
				'cl_suburb_name' => $result['cl_suburb_name'],
				'cl_contact_no' => $result['cl_contact_no'],
				'cl_alternate_no' => $result['cl_alternate_no'],
				'dateodaudit' => $result['as_audit_date'],
				'audit_allocation_date' => $result['audit_allocation_date'],
				'as_workmanship' => $as_workmanship,
				'as_plumber_verification' => $as_plumber_verification,
				'as_coc_verification' => $as_coc_verification,
				'as_auditcomplete' => $result['as_auditcomplete'],
				'auditorid' => $result['auditorid'],
				'refix_period' => $settings['refix_period'],
				'currentdatetime' => $datetime,
				'currentdate' => $date,
				'plumberprofile' => $plumberprofile,
			];
			$jsonData['points'][] = [
				'workmanship' => $workmanshippt,
 				'plumberverification' => $plumberverificationpt,
				'cocverfication' => $cocverificationpt
			];
			$jsonArray 		= array("status"=>'1', "message"=>'Audit Review', "result"=>$jsonData);

		}else{
			$jsonArray 		= array("status"=>'0', "message"=>'invalid request', "result"=>[]);
		}
		echo json_encode($jsonArray);
	}

	public function audit_review_save(){ //(refix = 1, cautionary = 2, complement =3 , noaudit findings =4)

		if ($this->input->post() && $this->input->post('coc_id') && $this->input->post('user_id') && $this->input->post('plumber_id')) {
			$this->form_validation->set_rules('auditdate','Audit Date','trim|required');
			$this->form_validation->set_rules('reviewpoint','Review','trim|required');

			if ($this->form_validation->run()==FALSE) {
				$findtext 		= ['<div class="form_error">', "</div>"];
				$replacetext 	= ['', ''];
				$errorMsg 		= str_replace($findtext, $replacetext, validation_errors());
				$jsonArray = array("status"=>'0', "message"=>$errorMsg, 'result' => []);
			}else{
				$cocid 		= $this->input->post('coc_id');
				$auditorid 	= $this->input->post('user_id');
				$plumberid 	= $this->input->post('plumber_id');
				$settings 	= $this->Systemsettings_Model->getList('row');
				$extraparam['auditorid'] = $auditorid;
				// $extras['plumberid'] = $plumberid;	
				$result		= $this->Coc_Model->getCOCList('row', ['id' => $cocid, 'coc_status' => ['2']]+$extraparam);	

				if($this->input->post('id') !=''){$id = $this->input->post('id');}else{$id = '';} // audit revreview id (as_id)
				$datetime 	=  date('Y-m-d H:i:s');
				$post 		=  $this->input->post();

				$request['coc_id'] 						= $cocid;
				$request['auditor_id'] 					= $auditorid;
				$request['plumber_id'] 					= $plumberid;
				if(isset($post['auditdate']))		 			$request['audit_date'] 					= date('Y-m-d', strtotime($post['auditdate']));
				if(isset($post['workmanship'])) 				$request['workmanship'] 				= $post['workmanship'];
				if(isset($post['plumberverification'])) 		$request['plumber_verification'] 		= $post['plumberverification'];
				if(isset($post['cocverification'])) 			$request['coc_verification'] 			= $post['cocverification'];
				if(isset($post['workmanshippoint'])) 			$request['workmanship_point'] 			= $post['workmanshippoint'];
				if(isset($post['plumberverificationpoint']))	$request['plumberverification_point'] 	= $post['plumberverificationpoint'];
				if(isset($post['cocverificationpoint'])) 		$request['cocverification_point'] 		= $post['cocverificationpoint'];
				if(isset($post['reviewpoint'])) 				$request['review_point'] 				= $post['reviewpoint'];
				if(isset($post['point'])) 						$request['point'] 						= $post['point'];
				if(isset($post['reason'])) 						$request['reason'] 						= $post['reason'];
				$request['reportdate'] 					= date('Y-m-d H:i:s');
				// if(isset($post['auditcomplete']) && isset($post['submit']) && $post['submit']=='submitreport')	$request['auditcomplete'] 		= $post['auditcomplete'];
				// if(isset($post['auditcomplete']) && isset($post['submit']) && $post['submit']=='submitreport') 	$request['status'] 				= '1';
				// if(isset($post['auditcomplete']) && isset($post['submit']) && $post['submit']=='submitreport') 	$request['auditcompletedate'] 	= date('Y-m-d');

				$request['refixcompletedate'] 	= (isset($post['refixcompletedate']) && $post['refixcompletedate']!='') ? date('Y-m-d', strtotime($post['refixcompletedate'])) : NULL;	
				$request['hold'] 				= (isset($post['hold'])) ? $post['hold'] : '0';
				if($id==''){
					$request['created_at'] = $datetime;
					$request['created_by'] = $auditorid;
					$data = $this->db->insert('auditor_statement', $request);
					$insertid = $this->db->insert_id();
				}else{
					$data = $this->db->update('auditor_statement', $request, ['id' => $id]);
					$insertid = $id;
				}
				if ($data) {
					if($post['submit']=='save' && isset($post['hold'])){
					$this->db->update('stock_management', ['audit_status' => '5', 'notification' => '1'], ['id' => $cocid]);
					}elseif($post['submit']=='save' && !isset($post['hold']) && $post['auditstatus']=='0'){
						$this->db->update('stock_management', ['audit_status' => '3', 'notification' => '1'], ['id' => $cocid]);
					}elseif($post['submit']=='save' && !isset($post['hold']) && $post['auditstatus']=='1'){
						$this->db->update('stock_management', ['audit_status' => '2', 'notification' => '1'], ['id' => $cocid]);
					}

						if($post['auditstatus']=='0'){
							$auditreviewrow = $this->Auditor_Model->getReviewList('row', ['coc_id' => $cocid, 'reviewtype' => '1', 'status' => '0']);
						if($auditreviewrow){
							$notificationdata 	= $this->Communication_Model->getList('row', ['id' => '22', 'emailstatus' => '1']);
							
							if($notificationdata){
								$pdf 		= FCPATH.'assets/uploads/temp/'.$cocid.'.pdf';
								$this->pdfauditreport($cocid, $pdf);
								
								$duedate 		= ($auditreviewrow) ? date('d-m-Y', strtotime($auditreviewrow['created_at'].' +'.$settings['refix_period'].'days')) : '';
								
								$body 		= str_replace(['{Plumbers Name and Surname}', '{COC number}', '{refix number} ', '{due date}'], [$result['u_name'], $cocid, $settings['refix_period'], $duedate], $notificationdata['email_body']);
								$subject 	= str_replace(['{cocno}'], [$cocid], $notificationdata['subject']);
								$this->CC_Model->sentMail($result['u_email'], $subject, $body, $pdf);
								if(file_exists($pdf)) unlink($pdf);  
							}
							
							// if($settingsdetail && $settingsdetail['otp']=='1'){
							// 	$smsdata 	= $this->Communication_Model->getList('row', ['id' => '22', 'smsstatus' => '1']);
					
							// 	if($smsdata){
							// 		$sms = str_replace(['{number of COC}'], [$cocid], $smsdata['sms_body']);
							// 		$this->sms(['no' => $result['u_mobile'], 'msg' => $sms]);
							// 	}
							// }
						}
					}
				}
			}
			$post['insertid'] = $insertid;
			$jsonArray 		= array("status"=>'1', "message"=>'Review Added Sucessfully', "result"=>$request);
		}else{
			$jsonArray 		= array("status"=>'0', "message"=>'invalid request', "result"=>[]);
		}
		echo json_encode($jsonArray);
	}

	public function audit_review_submit(){ //(refix = 1, cautionary = 2, complement =3 , noaudit findings =4)
		if ($this->input->post() && $this->input->post('coc_id') && $this->input->post('user_id') && $this->input->post('plumber_id')) {
			$this->form_validation->set_rules('auditdate','Audit Date','trim|required');
			$this->form_validation->set_rules('auditstatus','Audit Status','trim|required');

			if ($this->form_validation->run()==FALSE) {
				$findtext 		= ['<div class="form_error">', "</div>"];
				$replacetext 	= ['', ''];
				$errorMsg 		= str_replace($findtext, $replacetext, validation_errors());
				$jsonArray = array("status"=>'0', "message"=>$errorMsg, 'result' => []);
			}else{
				$cocid 		= $this->input->post('coc_id');
				$auditorid 	= $this->input->post('user_id');
				$plumberid 	= $this->input->post('plumber_id');
				$settings 	= $this->Systemsettings_Model->getList('row');
				$settingsdetail =  $this->Systemsettings_Model->getList('row');
				$extraparam['auditorid'] = $auditorid;
				// $extras['plumberid'] = $plumberid;	
				$result		= $this->Coc_Model->getCOCList('row', ['id' => $cocid, 'coc_status' => ['2']]+$extraparam);	

				if($this->input->post('id') !=''){$id = $this->input->post('id');}else{$id = '';} // audit revreview id (as_id)
				$datetime 	=  date('Y-m-d H:i:s');
				$post 		=  $this->input->post();

				$request['coc_id'] 						= $cocid;
				$request['auditor_id'] 					= $auditorid;
				$request['plumber_id'] 					= $post['plumber_id'];
				if(isset($post['auditdate']))		 			$request['audit_date'] 					= date('Y-m-d', strtotime($post['auditdate']));
				if(isset($post['workmanship'])) 				$request['workmanship'] 				= $post['workmanship'];
				if(isset($post['plumberverification'])) 		$request['plumber_verification'] 		= $post['plumberverification'];
				if(isset($post['cocverification'])) 			$request['coc_verification'] 			= $post['cocverification'];
				if(isset($post['workmanshippoint'])) 			$request['workmanship_point'] 			= $post['workmanshippoint'];
				if(isset($post['plumberverificationpoint']))	$request['plumberverification_point'] 	= $post['plumberverificationpoint'];
				if(isset($post['cocverificationpoint'])) 		$request['cocverification_point'] 		= $post['cocverificationpoint'];
				if(isset($post['reviewpoint'])) 				$request['review_point'] 				= $post['reviewpoint'];
				if(isset($post['point'])) 						$request['point'] 						= $post['point'];
				if(isset($post['reason'])) 						$request['reason'] 						= $post['reason'];
				$request['reportdate'] 					= date('Y-m-d H:i:s');
				if(isset($post['auditcomplete']) && isset($post['submit']) && $post['submit']=='submitreport')	$request['auditcomplete'] 		= $post['auditcomplete'];
				if(isset($post['auditcomplete']) && isset($post['submit']) && $post['submit']=='submitreport') 	$request['status'] 				= '1';
				if(isset($post['auditcomplete']) && isset($post['submit']) && $post['submit']=='submitreport') 	$request['auditcompletedate'] 	= date('Y-m-d');

				$request['refixcompletedate'] 	= (isset($post['refixcompletedate']) && $post['refixcompletedate']!='') ? date('Y-m-d', strtotime($post['refixcompletedate'])) : NULL;	
				$request['hold'] 				= (isset($post['hold'])) ? $post['hold'] : '0';
				if($id==''){
					$request['created_at'] = $datetime;
					$request['created_by'] = $auditorid;
					$data = $this->db->insert('auditor_statement', $request);
					$insertid = $this->db->insert_id();
				}else{
					$data = $this->db->update('auditor_statement', $request, ['id' => $id]);
					$insertid = $id;
				}

				if(isset($post['auditcomplete']) && $post['auditcomplete']=='1' && $post['submit']=='submitreport'){
					
					//Invoice and Order
					$inspectionrate = $this->currencyconvertor($this->getRates($this->config->item('inspection')));
					$invoicedata = [
						'description' 	=> 'Audit undertaken for '.$result['u_name'].' on COC '.$result['id'].'. Date of Review Submission '.date('d-m-Y', strtotime($datetime)),
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
					
					if($post['auditstatus']=='1'){						
						// Email
						$notificationdata 	= $this->Communication_Model->getList('row', ['id' => '21', 'emailstatus' => '1']);
						if($notificationdata){
							$body 		= str_replace(['{Plumbers Name and Surname}', '{COC number}'], [$result['u_name'], $result['id']], $notificationdata['email_body']);
							$subject 	= str_replace(['{cocno}'], [$id], $notificationdata['subject']);
							$this->CC_Model->sentMail($result['u_email'], $subject, $body);
						}
						
						// SMS
						// if($settingsdetail && $settingsdetail['otp']=='1'){
						// 	$smsdata 	= $this->Communication_Model->getList('row', ['id' => '21', 'smsstatus' => '1']);
				
						// 	if($smsdata){
						// 		$sms = str_replace(['{number of COC}'], [$id], $smsdata['sms_body']);
						// 		$this->sms(['no' => $result['u_mobile'], 'msg' => $sms]);
						// 	}
						// }
						
						// Stock
						$this->db->update('stock_management', ['audit_status' => '1', 'notification' => '1'], ['id' => $result['id']]);
						
						$this->CC_Model->diaryactivity(['plumberid' => $result['user_id'], 'auditorid' => $result['auditorid'], 'cocid' => $result['id'], 'action' => '9', 'type' => '4']);
					}elseif($post['auditstatus']=='0'){
						$this->db->update('stock_management', ['audit_status' => '4', 'notification' => '1'], ['id' => $result['id']]);
						
						$this->CC_Model->diaryactivity(['plumberid' => $result['user_id'], 'auditorid' => $result['auditorid'], 'cocid' => $result['id'], 'action' => '10', 'type' => '4']);
					}
					
					$this->Auditor_Model->actionRatio($post['plumber_id']);
					/// check audit statements

					$auditcomplete_count = $this->db->select('count(auditcomplete) as countaudit')->get_where('auditor_statement', ['plumber_id' => $post['plumber_id'], 'auditcomplete' => '1'])->row_array();
					$audit_list = $this->db->select('id, allocation')->get_where('compulsory_audit_listing', ['user_id' => $post['plumber_id']])->row_array();
					
					if ($audit_list['allocation']<=$auditcomplete_count['countaudit']) {
						//$this->db->delete('compulsory_audit_listing', array('id' => $audit_list['id']));
						//$this->db->delete('compulsory_audit_listing')->where('id', $audit_list['id']);
						$this->db->where('id', $audit_list['id']);
   						$this->db->delete('compulsory_audit_listing'); 
					}

				}

			}
			$post['insertid'] = $insertid;
			$jsonArray 		= array("status"=>'1', "message"=>'Audit Review Sucessfully', "result"=>$post);
		}else{
			$jsonArray 		= array("status"=>'0', "message"=>'invalid request', "result"=>[]);
		}
		echo json_encode($jsonArray);
	}
	public function getauditor_reportlisting(){
		if ($this->input->post() && $this->input->post('user_id')) { //user_id = auditor id
			if ($this->input->post('type') !='' && $this->input->post('type') =='favourites') {
				$auditorreportlist	= $this->getAuditorReportingList($this->input->post('user_id'));
				$status 	= '1';
				$message 	= 'favourites';
				$jsonData[] = $auditorreportlist;
			}elseif($this->input->post('id') !='' && $this->input->post('type') =='reportlist'){
				$data = $this->Auditor_Reportlisting_Model->getList('all', ['id' => $this->input->post('id'), 'status' => ['1']]);
				if($data){
					$jsonData[] = ['status' => '1', 'result' => $data];
					$status 	= '1';
					$message 		= 'reportlist';
				}else{
					$jsonData[] = ['status' => '0', 'result' => []];
					$status 	= '0';
					$message 	= 'No recod found';
				}
			}
			
			$jsonArray 		= array("status"=>$status, "message"=>$message, "result"=>$jsonData);
		}else{
			$jsonArray 		= array("status"=>'0', "message"=>'invalid request', "result"=>[]);
		}
		echo json_encode($jsonArray);
	}

	public function reviewlist_pointsdetails(){ //(points for refix, cautionary, complement, noaudit findings)
		if ($this->input->post() && $this->input->post('installationtypeid') && $this->input->post('subtypeid')) {
			$installationtypeid = $this->input->post('installationtypeid');
			$subtypeid 			= $this->input->post('subtypeid');

			$post = $this->input->post();
			$result = $this->Reportlisting_Model->getList('all', ['status' => ['1']]+$post);
			if(count($result)){
				$jsonData = $result;
				$message = 'audit review details';
			}else{
				$jsonData = [];
				$message = 'no record found';
			}
			$jsonArray 		= array("status"=>'1', "message"=>$message, "result"=>$jsonData);
		}else{
			$jsonArray 		= array("status"=>'0', "message"=>'invalid request', "result"=>[]);
		}
		echo json_encode($jsonArray);
	}

	public function getall_reviewlist(){
		if ($this->input->post() && $this->input->post('coc_id')) {
			$id 			= $this->input->post('coc_id');
			$reviewlists	= $this->Auditor_Model->getReviewList('all', ['coc_id' => $id]);

			foreach ($reviewlists as $key => $reviewlist) {
				if (isset($review_images)) unset($review_images);
				if ($this->config->item('reviewtype')[$reviewlist['reviewtype']] == 'Cautionary') {
					$colorcode = '#ffd700';
				}elseif($this->config->item('reviewtype')[$reviewlist['reviewtype']] == 'Compliment'){
					$colorcode = '#ade33d';
				}elseif($this->config->item('reviewtype')[$reviewlist['reviewtype']] == 'Failure'){
					$colorcode = '#f33333';
				}elseif($this->config->item('reviewtype')[$reviewlist['reviewtype']] == 'No Audit Findings'){
					$colorcode = '#50c6f2';
				}
				if ($reviewlist['file'] !='') {
					$images =  explode(",",$reviewlist['file']);
					if (count($images) > 0) {
						foreach ($images as $images_key => $image) {
							$review_images[] = base_url().'assets/uploads/auditor/statement/'.$image.'';
						}
					}else{
						$review_images[] = base_url().'assets/uploads/auditor/statement/'.$reviewlist['file'].'';
					}
				}else{
					$review_images[] = '';
				}
				$jsonData['review_details'][] = [ 'reviewid' => $reviewlist['id'], 'reviewtype' => $this->config->item('reviewtype')[$reviewlist['reviewtype']], 'statementname' => $reviewlist['statementname'], 'installationtypename' =>$reviewlist['installationtypename'], 'subtypename' =>$reviewlist['subtypename'], 'colorcode' => $colorcode, 'cocid' => $reviewlist['coc_id'], 'reference' => $reviewlist['reference'], 'comments' => $reviewlist['comments'], 'performancepoint' => $reviewlist['point'], 'knowledgelink' => $reviewlist['link'], 'review_images' => $review_images, 'status' => $reviewlist['status']
				];
			}
			$jsonArray = array("status"=>'1', "message"=>'Review Deatils', "result"=>$jsonData);
		}else{
			$jsonArray = array("status"=>'0', "message"=>'invalid request', "result"=>[]);
		}
		echo json_encode($jsonArray);
	}

	public function refix_action(){
		if ($this->input->post() && $this->input->post('id')) { // id = review id
			$this->form_validation->set_rules('id','Review Id','trim|required');
			$this->form_validation->set_rules('point','Point','trim|required');
			$this->form_validation->set_rules('refixperiod','Refix Period','trim|required');
			$this->form_validation->set_rules('status','Status','trim|required');
			if ($this->form_validation->run()==FALSE) {
				$findtext 		= ['<div class="form_error">', "</div>"];
				$replacetext 	= ['', ''];
				$errorMsg 		= str_replace($findtext, $replacetext, validation_errors());
				$jsonArray = array("status"=>'0', "message"=>$errorMsg, 'result' => []);
			}else{
				$post 	= $this->input->post();
				$id 	= $this->input->post('id');
				if(isset($post['point'])) 			$request['point'] 				= $post['point'];
				if(isset($post['status'])) 			$request['status'] 				= $post['status'];
				$this->db->update('auditor_review', $request, ['id' => $id]);
				if(isset($post['refixperiod']) && isset($post['status'])){
					$list = $this->Auditor_Model->getReviewList('row', ['id' => $id]);
					if($list['reviewtype']=='1'){
						$reviewstatus			= $post['status'];
						$listrefixdate 			= $list['refix_date'];
						
						if($listrefixdate=='' && $reviewstatus=='0'){
							$refixdate 			= date('Y-m-d', strtotime(date('d-m-Y').' +'.$post['refixperiod'].'days'));
							$this->db->update('auditor_review', ['refix_date' => $refixdate], ['id' => $id]);
						}
					}
				}
				$jsonArray = array("status"=>'1', "message"=>'Refix update', "result"=>$post);
			}
		}else{
			$jsonArray = array("status"=>'0', "message"=>'invalid request', "result"=>[]);
		}
		echo json_encode($jsonArray);
	}

	public function reviewlist_action(){ //(points for refix, cautionary, complement, noaudit findings)
		if ($this->input->post() && $this->input->post('coc_id') && $this->input->post('user_id') && $this->input->post('plumberid')) {
			if ($this->input->post('type') !='' && $this->input->post('type') == 'action') {
				$this->form_validation->set_rules('reviewtype','Review Type','trim|required');
				$this->form_validation->set_rules('favourites','Favourites','trim|required');
				$this->form_validation->set_rules('installationtype','Installation Type','trim|required');
				$this->form_validation->set_rules('subtype','Subtype','trim|required');
				$this->form_validation->set_rules('statement','Statement','trim|required');
				// $this->form_validation->set_rules('reference','reference','trim|required');
				// $this->form_validation->set_rules('link','Link','trim|required');
				//
				$this->form_validation->set_rules('comments','Comments','trim|required');

				if ($this->form_validation->run()==FALSE) {
					$findtext 		= ['<div class="form_error">', "</div>"];
					$replacetext 	= ['', ''];
					$errorMsg 		= str_replace($findtext, $replacetext, validation_errors());
					$jsonArray = array("status"=>'0', "message"=>$errorMsg, 'result' => []);
				}else{
					$auditorid 	= $this->input->post('user_id');
					$plumberid 	= $this->input->post('plumberid');
					$cocid 		= $this->input->post('coc_id');
					$post 		= $this->input->post();
					if($this->input->post('id') !=''){$id = $this->input->post('id');}else{$id = '';}
					$datetime	= 	date('Y-m-d H:i:s');
					$request		=	[
						'updated_at' 		=> $datetime,
						'updated_by' 		=> $auditorid
					];
					
					if (isset($post['file']) && $post['file'] != '') {
						$data = $this->fileupload(['files' => $post['file'], 'file_name' => $post['file_name'], 'user_id' => '', 'page' => 'auditorreview']);
						$post['file'] = $data[0];
					}

					$request['coc_id'] 				= $cocid;
					$request['auditor_id'] 			= $auditorid;
					$request['plumber_id'] 			= $plumberid;
					if(isset($post['reviewtype']))		 			$request['reviewtype'] 			= $post['reviewtype'];
					if(isset($post['favourites'])) 					$request['favourites'] 			= $post['favourites'];
					if(isset($post['installationtype'])) 			$request['installationtype'] 	= $post['installationtype'];
					if(isset($post['subtype'])) 					$request['subtype'] 			= $post['subtype'];
					if(isset($post['statement'])) 					$request['statement'] 			= $post['statement'];
					if(isset($post['reference'])) 					$request['reference'] 			= $post['reference'];
					if(isset($post['link'])) 						$request['link'] 				= $post['link'];
					if(isset($post['comments'])) 					$request['comments'] 			= $post['comments'];
					if(isset($post['file'])) 						$request['file'] 				= $post['file'];
					if(isset($post['incompletepoint'])) 			$request['incomplete_point'] 	= $post['incompletepoint'];
					if(isset($post['completepoint'])) 				$request['complete_point'] 		= $post['completepoint'];
					if(isset($post['cautionarypoint'])) 			$request['cautionary_point'] 	= $post['cautionarypoint'];
					if(isset($post['complimentpoint'])) 			$request['compliment_point'] 	= $post['complimentpoint'];
					if(isset($post['noauditpoint'])) 				$request['noaudit_point'] 		= $post['noauditpoint'];
					if(isset($post['point'])) 						$request['point'] 				= $post['point'];
					if(isset($post['status'])) 						$request['status'] 				= $post['status'];
					// if refix means status 0, for others status 1
					if($id==''){
					$request['created_at'] = $datetime;
					$request['created_by'] = $auditorid;
					$this->db->insert('auditor_review', $request);
					$message = 'Review Added Successfully';
					$insertid = $this->db->insert_id();
					}else{
						$this->db->update('auditor_review', $request, ['id' => $id]);
						$message = 'Review updated Successfully';
						$insertid = $id;
					}
					if(isset($data['refixperiod']) && isset($data['status'])){
						$list = $this->Auditor_Model->getReviewList('row', ['id' => $insertid]);
						
						if($list['reviewtype']=='1'){
							$reviewstatus			= $data['status'];
							$listrefixdate 			= $list['refix_date'];
							
							if($listrefixdate=='' && $reviewstatus=='0'){
								$refixdate 			= date('Y-m-d', strtotime(date('d-m-Y').' +'.$data['refixperiod'].'days'));
								$this->db->update('auditor_review', ['refix_date' => $refixdate], ['id' => $insertid]);
							}
						}
					}
					$status  = '1';
					$message = 'Review Added Sucessfully';
					$post['insertid'] = $insertid;
					$jsonData = $post;
				}
				
			}elseif($this->input->post('id') !='' && $this->input->post('type') !='' && $this->input->post('type') == 'delete'){
				$reviewid = $this->input->post('id');
				$result = $this->Auditor_Model->deleteReview($reviewid);
				$status  = '1';
				$message = 'Review Deleted Sucessfully';
				$jsonData = $result;
			}
			$jsonArray = array("status"=>'1', "message"=>$message, "result"=>$jsonData);
		}else{
			$jsonArray = array("status"=>'0', "message"=>'invalid request', "result"=>[]);
		}
		echo json_encode($jsonArray);
	}

	public function audithistory(){
		if ($this->input->post() && $this->input->post('coc_id')) {
			if($this->input->post('user_id') !=''){$auditorid = ['auditorid' =>$this->input->post('user_id')];}else{$auditorid = [];}
			$cocid 			= $this->input->post('coc_id');
			$cocdetail 		= $this->Coc_Model->getCOCList('row', ['id' => $cocid, 'coc_status' => ['2']]+$auditorid);	

			$plumberid 		= $cocdetail['user_id'];
			$reviewresults 		= $this->Auditor_Model->getReviewHistoryCount(['plumberid' => $plumberid]);

			$count 				= $reviewresults['count'];
			$total 				= $reviewresults['total'];
			$refixincomplete 	= $reviewresults['refixincomplete'];
			$refixcomplete 		= $reviewresults['refixcomplete'];
			$compliment 		= $reviewresults['compliment'];
			$cautionary 		= $reviewresults['cautionary'];
			$noaudit 			= $reviewresults['noaudit'];

			$refixincompletepercentage 	= ($refixincomplete!=0) ? round(($refixincomplete/$total)*100,2).'%' : '0%'; 
			$refixcompletepercentage 	= ($refixcomplete!=0) ? round(($refixcomplete/$total)*100,2).'%' : '0%'; 
			$complimentpercentage 		= ($compliment!=0) ? round(($compliment/$total)*100,2).'%' : '0%'; 
			$cautionarypercentage 		= ($cautionary!=0) ? round(($cautionary/$total)*100,2).'%' : '0%'; 
			$noauditpercentage 			= ($noaudit!=0) ? round(($noaudit/$total)*100,2).'%' : '0%'; 

			$jsonData['coc_details'][] = [
				'cocid' 		=> $cocdetail['id'],
				'plumberid' 	=> $cocdetail['user_id'],
				'plumbername' 	=> $cocdetail['u_name'],
				'plumberemail' 	=> $cocdetail['u_email'],
				'auditorid' 	=> $cocdetail['auditorid'],
				'auditorname' 	=> $cocdetail['auditorname'],
				'auditoremail' 	=> $cocdetail['auditoremail'],
				'auditormobile' => $cocdetail['auditormobile'],
			];
			$jsonData['history_details'][] = [
				'count' 					=> $count,
				'total' 					=> $total,
				'refixincomplete' 			=> $refixincomplete,
				'refixcomplete' 			=> $refixcomplete,
				'compliment' 				=> $compliment,
				'cautionary' 				=> $cautionary,
				'noaudit' 					=> $noaudit,
				'refixincompletepercentage' => $refixincompletepercentage,
				'refixcompletepercentage' 	=> $refixcompletepercentage,
				'complimentpercentage' 		=> $complimentpercentage,
				'cautionarypercentage' 		=> $cautionarypercentage,
				'noauditpercentage' 		=> $noauditpercentage,
			];
			$message = 'Plumber Audit History';
			if ($this->input->post() && $this->input->post('plumber_id') && $this->input->post('type') =='list') {
				$post['plumberid'] 	= $this->input->post('plumber_id');
				$totalcount 		= $this->Auditor_Model->getReviewList('count', $post);
				$reviewresults 		= $this->Auditor_Model->getReviewList('all', $post);
				if(count($reviewresults) > 0){
					foreach($reviewresults as $result){
						$jsonData['table_content'][] = 	[
												'date' 				=> 	date('d-m-Y', strtotime($result['created_at'])),
												'auditor' 			=> 	$result['auditorname'],
												'installationtype' 	=> 	$result['installationtypename'],
												'subtype' 			=> 	$result['subtypename'],
												'statementname' 	=> 	$result['statementname'],
												'finding' 			=> 	$this->config->item('reviewtype')[$result['reviewtype']]
											];
					}
				}
				$message = 'Plumber History Results';
			}elseif ($this->input->post() && $this->input->post('plumber_id') && $this->input->post('type') =='search' && $this->input->post('keywords')) {
				$post['plumberid'] 	= $this->input->post('plumber_id');
				$post['search'] 	= ['value' => $this->input->post('keywords'), 'regex' => 'false'];
				$post['page'] 		= 'adminaudithistroy';
				$totalcount 		= $this->Auditor_Model->getReviewList('count', $post);
				$reviewresults 		= $this->Auditor_Model->getReviewList('all', $post);
				if(count($reviewresults) > 0){
					foreach($reviewresults as $result){
						$jsonData['table_content'][] = 	[
												'date' 				=> 	date('d-m-Y', strtotime($result['created_at'])),
												'auditor' 			=> 	$result['auditorname'],
												'installationtype' 	=> 	$result['installationtypename'],
												'subtype' 			=> 	$result['subtypename'],
												'statementname' 	=> 	$result['statementname'],
												'finding' 			=> 	$this->config->item('reviewtype')[$result['reviewtype']]
											];
					}
				}
				$message = 'Plumber History Search Results';
			}
			$jsonArray 		= array("status"=>'1', "message"=>$message, "result"=>$jsonData);
		}else{
			$jsonArray 		= array("status"=>'0', "message"=>'invalid request', "result"=>[]);
		}
		echo json_encode($jsonArray);
	}

	public function auditor_diarycomments(){
		if ($this->input->post() && $this->input->post('coc_id') && $this->input->post('user_id')) {
			$auditorid['auditorid']	= $this->input->post('user_id');
			$result					= $this->Coc_Model->getCOCList('row', ['id' => $this->input->post('coc_id'), 'coc_status' => ['2']]+$auditorid);
			$comments				= $this->Auditor_Comment_Model->getList('all', ['coc_id' => $this->input->post('coc_id')]);	
			$diary					= $this->diaryactivity(['cocid' => $this->input->post('coc_id')]+$auditorid);
			if (isset($diary) || $diary !='') {
				$findtext 			= ['<p>Diary of Activities</p>', '<div class="row">', '<div class="col-12 diarybar">', '<div>', '</div>'];
				$replacetext 		= ['', ''];
				$diaryodactvites 	= str_replace($findtext, $replacetext, $diary);
			}

			if (count($comments) > 0) {
				foreach ($comments as $commentskey => $commentsvalue) {
					$jsonData['comments'][] = [
						'date' 			=> date('d-m-Y', strtotime($commentsvalue['created_at'])),
						'auditorname' 	=> $commentsvalue['username'],
						'comments' 	=> $commentsvalue['comments'],
					];
				}
			}else{
				$jsonData['comments'][] = [];
			}

			$jsonData['review_details'][] = [
				'auditorid' 		=> $result['auditorid'],
				'plumberid' 		=> $result['user_id'],
				'cocid' 			=> $result['id'],
				'as_id' 			=> $result['as_id'],
				'as_audit_date' 	=> $result['as_audit_date'],
				'as_auditcomplete' 	=> $result['as_auditcomplete'],
			];
			$jsonData['diaryactvites'][] = [
				'activites' 		=> (isset($diaryodactvites) ? $diaryodactvites : '')
			];
			$jsonArray 		= array("status"=>'0', "message"=>'Diary And Comments', "result"=>$jsonData);
		}else{
			$jsonArray 		= array("status"=>'0', "message"=>'invalid request', "result"=>[]);
		}
		echo json_encode($jsonArray);
	}

	public function auditor_diarycomments_action(){
		if ($this->input->post() && $this->input->post('coc_id') && $this->input->post('user_id') && $this->input->post('comments')) {
			$data 		= $this->input->post();
			$userid 	= $this->input->post('user_id');
			$datetime 	= date('Y-m-d H:i:s');
			$request		=	[
				'comments' 			=> $data['comments'],
				'user_id' 			=> $data['user_id'],
				'coc_id' 			=> $data['coc_id'],
				'created_at' 		=> $datetime,
				'created_by' 		=> $userid,
				'updated_at' 		=> $datetime,
				'updated_by' 		=> $userid
			];
			$this->db->insert('auditor_comment', $request);
			$jsonArray 		= array("status"=>'0', "message"=>'Comments inserted', "result"=>$request);
		}else{
			$jsonArray 		= array("status"=>'0', "message"=>'invalid request', "result"=>[]);
		}
		echo json_encode($jsonArray);
		
	}

	public function getInstallationTypeList_api($data = []){

		if (!isset($data['id']) && !isset($data['type'])) {
			$results = $this->Installationtype_Model->getList('all', ['status' => ['1']]);
			if(count($results) > 0){
				foreach ($results as $key => $value) {
					$arraydata[] = ['id' => $value['id'], 'name' => $value['name']];
				}
			}else{
				$arraydata[] = [];
			}
		}else{
			$results = $this->Installationtype_Model->getList('all', ['status' => ['1'], 'id' => $data['id']]);
			if(count($results) > 0){
				foreach ($results as $key => $value) {
					$arraydata[] = ['id' => $value['id'], 'name' => $value['name']];
				}
			}else{
				$arraydata[] = [];
			}
		}
		
		return $arraydata;
	}
	public function getSubTypeList_api($data = []){

		if (!isset($data['id']) && !isset($data['type'])) {
			$results = $this->Subtype_Model->getList('all', ['status' => ['1'], 'installationtypeid' => $data['installationtypeid']]);
			if(count($results) > 0){
				foreach ($results as $key => $value) {
					$arraydata[] = ['id' => $value['id'], 'installationtypeid' => $value['installationtype_id'], 'name' => $value['name']];
				}
			}else{
				$arraydata[] = [];
			}
		}else{
			$data = $this->Subtype_Model->getList('all', ['status' => ['1'], 'id' => $data['id']]);
			if(count($data) > 0){
				foreach ($data as $key => $value) {
					$arraydata[] = ['id' => $value['id'], 'installationtypeid' => $value['installationtype_id'], 'name' => $value['name']];
				}
			}else{
				$arraydata[] = [];
			}
		}
		
		return $arraydata;
	}
	public function getreportlisting_api($data =[]){

		if (!isset($data['id']) && !isset($data['type'])) {
			$results = $this->Reportlisting_Model->getList('all', ['status' => ['1'], 'installationtypeid' => $data['installationtypeid'], 'subtypeid' => $data['subtypeid']]);
			if(count($results) > 0){
				foreach ($results as $key => $value) {
					$arraydata[] = ['id' => $value['id'], 'installationtypeid' => $value['installation_id'], 'subtype_id' => $value['subtype_id'], 'statement' => $value['statement'], 'regulation' => $value['regulation'], 'regulation' => $value['regulation'], 'compliment' => $value['compliment'], 'cautionary' => $value['cautionary'], 'refix_complete' => $value['refix_complete'], 'refix_incomplete' => $value['refix_incomplete'], 'ncn_details' => 'Details', 'pub_remedial_ac' => 'Actions', 'comments' => $value['comments'], 'knowledge_link' => $value['knowledge_link'], 'reference' => 'Reference'];
				}
			}else{
				$arraydata[] = [];
			}
		}else{
			$results = $this->Reportlisting_Model->getList('all', ['status' => ['1'], 'id' => $data['id']]);
			if(count($results) > 0){
				foreach ($results as $key => $value) {
					$arraydata[] = ['id' => $value['id'], 'installationtypeid' => $value['installation_id'], 'subtype_id' => $value['subtype_id'], 'statement' => $value['statement'], 'regulation' => $value['regulation'], 'regulation' => $value['regulation'], 'compliment' => $value['compliment'], 'cautionary' => $value['cautionary'], 'refix_complete' => $value['refix_complete'], 'refix_incomplete' => $value['refix_incomplete'], 'ncn_details' => 'Details', 'pub_remedial_ac' => 'Actions', 'reference' => 'Reference'];
				}
			}else{
				$arraydata[] = [];
			}
		}
		
		return $arraydata;
	}

	// public function electroniccocreport_api($id, $userid)
	// {	
	// 	$this->pdfelectroniccocreport_apdi($id, $userid);
	// }
	// public function noncompliancereport_adi($id, $userid)
	// {	
	// 	$this->pdfnoncompliancereport_api($id, $userid);
	// }

	public function pdfelectroniccocreport_api($id, $userid){

		$userdata				 		= $this->Plumber_Model->getList('row', ['id' => $userid], ['users', 'usersdetail', 'usersplumber']);
		$pagedata['userdata']	 		= $userdata;
		$pagedata['specialisations']	= explode(',', $pagedata['userdata']['specialisations']);
		$pagedata['result']		    	= $this->Coc_Model->getCOCList('row', ['id' => $id]);
		$pagedata['designation2'] 		= $this->config->item('designation2');
		$specialisations 				= explode(',', $userdata['specialisations']);
		$pagedata['installationtype']	= $this->getInstallationTypeList();
		$pagedata['installation'] 		= $this->Installationtype_Model->getList('all', ['ids' => ['1','2','3','5','6','7']]);
		$pagedata['specialisations']	= $this->Installationtype_Model->getList('all', ['ids' => ['4','8']]);

		$html = $this->load->view('pdf/electroniccocreport', (isset($pagedata) ? $pagedata : ''), true);
		$this->pdf->loadHtml($html);
		$this->pdf->setPaper('A4', 'portrait');
		$this->pdf->render();
		$output = $this->pdf->output();
		$this->pdf->stream('Electronic COC Report '.$id);
	}
	
	public function pdfnoncompliancereport_api($id, $userid, $save=''){		

		$pagedata['result']			= $this->Coc_Model->getCOCList('row', ['id' => $id, 'coc_status' => ['2']]);
		$pagedata['noncompliance'] 	= $this->Noncompliance_Model->getList('all', ['coc_id' => $id, 'user_id' => $userid]);	

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

	public function ranking($data = []){

		if ($data['type'] == 'province') {
			$userdetail		= $this->getUserDetails($data['id']);
			$userdetails 	= $this->Plumber_Model->getList('row', ['id' => $data['id']], ['users', 'physicaladdress', 'postaladdress', 'billingaddress']);
			$physicaladdress 		= isset($userdetails['physicaladdress']) ? explode('@-@', $userdetails['physicaladdress']) : [];
			$province1 				= isset($physicaladdress[5]) ? $this->getProvinceList()[$physicaladdress[5]] : '';

			$province 		= $this->Managearea_Model->getListProvince('row', ['id' => $physicaladdress[5]]);
			$rollingavg 	= $this->getRollingAverage();
			$date			= date('Y-m-d', strtotime(date('Y-m-d').'+'.$rollingavg.' months'));
			$ranking 		= $this->Plumber_Model->performancestatus('all', ['date' => $date, 'archive' => '0', 'province' => $physicaladdress[5]]);
		}else{
			$rollingavg 	 = $this->getRollingAverage();
			$date			 = date('Y-m-d', strtotime(date('Y-m-d').'+'.$rollingavg.' months'));
			$ranking  = $this->Plumber_Model->performancestatus('all', ['date' => $date, 'archive' => '0', 'overall' => '1']);
		}
		return $ranking;
	}

	public function country_ranking(){

		if ($this->input->post('user_id')) {
			$jsonData = [];

			$id 				= $this->input->post('user_id');
			$userdata 			= $this->getUserDetails($id);
			$countryrank  = 0;
			

			$countryranking 	= $this->ranking(['id' => $id, 'type' => 'country']);

			// country and industry
			foreach ($countryranking as $key1 => $user_country_ranking) {
				if ($user_country_ranking['userid'] == $id) {
					$countryrank = $key1+1;
				}
			}
			$jsonData['plumber_data'] = [ 'id' => $id, 'rank' => $countryrank
			];

			$jsonArray = array("status"=>'1', "message"=>'Country Ranking', "result"=> $jsonData);
		}else{
			$jsonArray = array("status"=>'0', "message"=>'Invalid API', "result"=> []);
		}
		echo json_encode($jsonArray);
	}

	public function province_ranking(){

		if ($this->input->post('user_id')) {
			$jsonData = [];

			$id 										= $this->input->post('user_id');
			$userdata 									= $this->getUserDetails($id);
			$regionalrank = 0;

			$regionalranking 	= $this->ranking(['id' => $id, 'type' => 'province']);
			
			// province
			foreach ($regionalranking as $key1 => $user_province_ranking) {
				if ($user_province_ranking['userid'] == $id) {
					$regionalrank = $key1+1;
				}
			}
			$jsonData['plumber_data'] = [ 'id' => $id, 'rank' => $regionalrank
			];

			$jsonArray = array("status"=>'1', "message"=>'Province Ranking', "result"=> $jsonData);
		}else{
			$jsonArray = array("status"=>'0', "message"=>'Invalid API', "result"=> []);
		}
		echo json_encode($jsonArray);
	}

	public function fileupload($data = []){

		$userid 	 = $data['user_id'];
		$base64files = $data['files'];
		$base_url 	 = base_url();
		$page 		 = $data['page'];
		$file_name 	 = $data['file_name'];
		$directory 	 = dirname(__DIR__, 3);

		// $file_size	=  $base64files['image']['size'];
    	$files		=  explode(',', $base64files);
    	$countfiles = count($files);

		if ($page == 'plumbercpd') {
			$path = FCPATH.'assets/uploads/cpdqueue/';
		}elseif($page == 'plumberlogcoc'){
			$path = FCPATH.'assets/uploads/cpdqueue/';
		}elseif($page == 'auditorreview'){
			$path = FCPATH.'assets/uploads/auditor/statement/';

			if(!is_dir($path)){
				mkdir($directory.'/assets/uploads/auditor/statement', 0755, true);
			}
		}
		elseif($page == 'noncompliance_coc_image' || $page == 'plumber_logcoc'){
			$path = FCPATH.'assets/uploads/plumber/'.$userid.'/log/';
			
			if(!is_dir($path)){
				mkdir($directory.'/assets/uploads/plumber/'.$userid.'/log', 0755, true);
			}
		}elseif($page == 'chat'){
			$path = FCPATH.'assets/uploads/chat/'.$userid.'/';

			if(!is_dir($path)){
				mkdir($directory.'/assets/uploads/chat/'.$userid.'/', 0755, true);
			}
		}elseif($page == 'plumber_skill' || $page == 'plumber_reg'){
			$path = FCPATH.'assets/uploads/plumber/'.$userid.'/';

			if(!is_dir($path)){
				mkdir($directory.'/assets/uploads/plumber/'.$userid.'/', 0755, true);
			}
		}

		
		if ($countfiles > 1) {
			$file_names = explode(',', $file_name);
			for($i=0;$i<$countfiles;$i++){
				
				$base64		= $files[$i];
				$file_name 	= $file_names[$i];
	            $extension 	= explode('.', $file_name)[1];
	            $image 		= base64_decode($base64);
	            $image_name = md5(uniqid(rand(), true).$i);
	            $filename 	= $image_name . '.' . $extension;
				$filearray[] 	= $filename;
	            file_put_contents($path . $filename, $image);
			}
		}
		else{

			$base64		= $base64files;
			$extension 	= explode('.', $file_name)[1];
	        $image 		= base64_decode($base64);
	        $image_name = md5(uniqid(rand(), true));
	        $filename 	= $image_name . '.' . $extension;
	        $filearray 	= $filename;

			file_put_contents($path . $filename, $image);
		}
		if (is_array($filearray) && (count($filearray) > 1)) {
			$file[] = implode(",",$filearray);
		}else{
			$file[] = $filearray;
		}
		return $file;
	}

	public function ajaxprovince(){
		$jsonData = [];
		$data = $this->Managearea_Model->getListProvince('all', ['status' => ['1']]);
		
		if(count($data) > 0){
			foreach ($data as $key => $value) {
				$jsonData['provincedata'][] = [ 'id' => $value['id'], 'name' => $value['name']
				];
			}
			$jsonArray = ['status' => '1', 'result' => $jsonData];
		}else{
			$jsonArray = ['status' => '0', 'result' => []];
		}
		echo json_encode($jsonArray);
	}

	public function ajaxcity(){

		if ($this->input->post()) {
			$jsonData 			= [];
			$post['provinceid']	= $this->input->post('provinceid'); 
			$post['orderby'] 	= "c.name asc";
			$result 			= $this->Managearea_Model->getListCity('all', $post);

			if(count($result)){
				foreach ($result as $key => $value) {
					$jsonData['citydata'][] = [ 'id' => $value['id'], 'province_id' => $value['province_id'], 'name' => $value['name'], 
					];
				}
				$jsonArray = ['status' => '1', 'result' => $jsonData];
			}else{
				$jsonArray = ['status' => '0', 'result' => []];
			}
		}else{
			$jsonArray = array("status"=>'0', "message"=>'Invalid API', "result"=> []);
		}
		echo json_encode($jsonArray);
	}

	public function ajaxsuburb(){

		if ($this->input->post()) {
			$jsonData 			= [];
			$post['provinceid'] = $this->input->post('provinceid');  
			$post['cityid'] 	= $this->input->post('cityid');  
			$post['orderby'] 	= "name asc";
			$result 			= $this->Managearea_Model->getListSuburb('all', $post);
			
			if(count($result)){
				foreach ($result as $key => $value) {
					$jsonData['suburbdata'][] = [ 'id' => $value['id'], 'province_id' => $value['province_id'], 'city_id' => $value['city_id'], 'name' => $value['name'], 
					];
				}
				$jsonArray = ['status' => '1', 'result' => $jsonData];
			}else{
				$jsonArray = ['status' => '0', 'result' => []];
			}
		}else{
			$jsonArray = array("status"=>'0', "message"=>'Invalid API', "result"=> []);
		}
		echo json_encode($jsonArray);
	}

	public function getfavourites($data = []){
		$this->db->select('*');
		$this->db->from('auditor_report_listing');
		if(isset($data['favid'])) $this->db->where('id', $data['favid']);
		if(isset($data['auditorid'])) $this->db->where('user_id', $data['auditorid']);

		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}

	public function featchprovince($data = []){
		$getprovince = $this->Managearea_Model->getListProvince('row', ['id' => $data['id']]);
		if(count($getprovince) > 0) {
			$provincedata[]=  [ 'id' => $value['id'], 'name' => $value['name']
				];
		}else{
			$provincedata = [];
		}
		return $provincedata;
	}

	public function featchcity($data = []){
		$getcity = $this->Managearea_Model->getListCity('row', ['id' => $data['id']]);
		if(count($getcity) > 0) {
			$citydata[]=  [ 'id' => $value['id'], 'name' => $value['name']
				];
		}else{
			$citydata[] = [];
		}
		return $citydata;
	}

	public function featchsuburb($data = []){
		$getsuburb = $this->Managearea_Model->getListSuburb('row', ['status' => $data['id']]);
		if(count($getsuburb) > 0) {
			$suburbdata[]=  [ 'id' => $value['id'], 'name' => $value['name']];
		}
		else {
			$suburbdata[] = [];
		}
		return $suburbdata;
	}


// Selvamani
	public function get_cocplumber(){
		if ($this->input->post('COCno')) {
			$jsonData = [];
			$id = $this->input->post('COCno');
			$userdata = $this->Coc_Model->getCOCList('row', ['id' => $id]);
			if(!empty($userdata)){
				if($userdata['coc_status'] == '2'){

					if (isset($plumberspecialisation)) 	unset($plumberspecialisation);
					if (isset($installation)) 			unset($installation);

					$plumberdetail = $this->Plumber_Model->getList('row', ['id' => $userdata['user_id'], 'type' => '3', 'status' => ['1']], ['users', 'usersdetail', 'usersplumber', 'company', 'physicaladdress']);

					if (isset($userdata['cl_installationtype']) && $userdata['cl_installationtype'] !='') {
						$installationarray = explode(',', $userdata['cl_installationtype']);
						$installation 		= $this->Installationtype_Model->getList('all', ['designation' => $plumberdetail['designation'], 'specialisations' => [], 'ids' => $installationarray]);
					}

					if (isset($plumberdetail['specialisations']) && $plumberdetail['specialisations'] !='') {
						$plumberspecialisationarray = explode(',', $plumberdetail['specialisations']);
						foreach ($plumberspecialisationarray as $plumberspecialisationarraykey => $plumberspecialisationarrayvalue) {
							$plumberspecialisation[] = $this->config->item('specialisations')[$plumberspecialisationarrayvalue];
						}
					}

					// $jsonData['pName']  = $userdata['u_name'];
					// $jsonData['pRegNo'] = $userdata['plumberregno'];

					$jsonData['cocdetail'] = [
						'cocid' 		=> $userdata['id'],

						'coc_status' 	=> $this->config->item('auditstatus')[$userdata['coc_status']].' '.isset($this->config->item('auditstatus')[$userdata['audit_status']]) ? $this->config->item('auditstatus')[$userdata['audit_status']] : '',

						'allocation_date' => date('d-m-Y', strtotime($userdata['allocation_date'])),

						'cl_completion_date' => isset($userdata['cl_completion_date']) ? date('d-m-Y', strtotime($userdata['cl_completion_date'])) : '',

						'cl_installationtype' => isset($installation) ? $installation : '',
					];

					$jsonData['plumberdetail'] = [
						'name' => $plumberdetail['name'],
						'surname' => $plumberdetail['surname'],
						'designation' => $this->config->item('designation2')[$plumberdetail['designation']],
						'designation' => isset($plumberspecialisation) ? $plumberspecialisation : '',
					];

					$jsonArray = array("status"=>'1', "message"=>'Plumber Detail', "result"=> $jsonData);
				}elseif($userdata['coc_status'] == '4' || $userdata['coc_status'] == '5'){
					$jsonArray = array("status"=>'1', "message"=>'Error: COC has not been logged', "result"=> (object) null);
				}elseif($userdata['coc_status'] == '3'){
					$jsonArray = array("status"=>'1', "message"=>'There is no plumber assigned to this COC.', "result"=> (object) null);
				}else{
					if($userdata['user_id'] == '0'){
							$jsonArray = array("status"=>'1', "message"=>'There is no plumber assigned to this COC.', "result"=> (object) null);
					}else{
							$jsonArray = array("status"=>'1', "message"=>'Error: plumber cannot be found', "result"=> (object) null);
					}
				}
			}else{
				$jsonArray = array("status"=>'1', "message"=>'There is no COC with the number '.$id, "result"=> (object) null);
			}
		}else{
			$jsonArray = array("status"=>'0', "message"=>'Invalid API', "result"=> (object) null);
		}
		echo json_encode($jsonArray);
	}

	public function cardhtml(){
		$this->load->view('api/card');
	}


											/* PIRB co.za */
	/*public function provincelists(){
		echo json_encode($this->getProvinceList());
	}

	public function citylists(){
		if ($this->input->post() && $this->input->post('provinceid')) {
				$this->form_validation->set_rules('provinceid', 'Province', 'trim|required');
				
				if ($this->form_validation->run()==FALSE) {
					$errorMsg =  validation_errors();
					$jsonArray = array("status"=>'0', "message"=>$errorMsg, 'result' => []);
				}else{
					$post = $this->input->post();
					$data = $this->Managearea_Model->getListCity('all', ['status' => ['1'], 'province_id' => $post['provinceid']]);
					if(count($data) > 0) $city =  ['' => 'Select City']+array_column($data, 'name', 'id');

					$jsonArray = array("status"=>'0', "message"=>'Invalid API', "result"=> $city);
				}

		}else{
			$jsonArray = array("status"=>'0', "message"=>'Invalid API', "result"=> null);
		}
		echo json_encode($jsonArray);
	}

	public function suburublists(){
		if ($this->input->post() && $this->input->post('provinceid') && $this->input->post('cityid')) {
				$this->form_validation->set_rules('provinceid', 'Province', 'trim|required');
				$this->form_validation->set_rules('cityid', 'City', 'trim|required');
				
				if ($this->form_validation->run()==FALSE) {
					$errorMsg =  validation_errors();
					$jsonArray = array("status"=>'0', "message"=>$errorMsg, 'result' => []);
				}else{
					$post = $this->input->post();
					$data = $this->Managearea_Model->getListSuburb('all', ['status' => ['1'], 'province_id' => $post['provinceid'], 'city_id' => $post['cityid']]);
					if(count($data) > 0) $suburub =  ['' => 'Select Suburb']+array_column($data, 'name', 'id');

					$jsonArray = array("status"=>'0', "message"=>'Invalid API', "result"=> $suburub);
				}

		}else{
			$jsonArray = array("status"=>'0', "message"=>'Invalid API', "result"=> null);
		}
		echo json_encode($jsonArray);
		
		
		
		// echo json_encode($this->getProvinceList());
	}*/

	public function searchplumber_suburb(){
		if ($this->input->post() && $this->input->post('suburb')) {
				//$this->form_validation->set_rules('province', 'Province', 'trim|required');
				$this->form_validation->set_rules('suburb', 'Suburb', 'trim|required');
				
				if ($this->form_validation->run()==FALSE) {
					$errorMsg =  validation_errors();
					$jsonArray = array("status"=>'0', "message"=>$errorMsg, 'result' => []);
				}else{
					$post = $this->input->post();
					$suburubId = $post['suburb'];
					$this->db->select('*');
					$this->db->from('suburb');
					$this->db->where('name', $post['suburb']);
					$validatesuburb = $this->db->get()->result_array();
					if ($validatesuburb) {
						$namearray	 	= $post['suburb'];
						if(isset($idarray)) unset($idarray);
						// if(isset($namearray)) unset($namearray);
						foreach ($validatesuburb as $key => $value) {
							$idarray[] 		= $value['id'];
							
						}
						$data = $this->getplumbersData('suburbplumber', ['suburubid' => $idarray, 'suburubname' => $namearray]);
						$jsonArray = array("status"=>'1', "message"=>'Plumber Data', "result"=> $data);
					}
				}
		}else{
			$jsonArray = array("status"=>'0', "message"=>'Invalid API', "result"=> null);
		}
		echo json_encode($jsonArray);
	}
	public function verifiedplumber(){

		if ($this->input->post() && $this->input->post('plumberregno')) {
				$this->form_validation->set_rules('plumberregno', 'Plumber Registration Number', 'trim|required');
				if ($this->form_validation->run()==FALSE) {
					$errorMsg =  validation_errors();
					$jsonArray = array("status"=>'0', "message"=>$errorMsg, 'result' => []);
				}else{
					$post = $this->input->post();
					$data = $this->getplumbersData('regplumber', ['regno' => $post['plumberregno']]);

					$jsonArray = array("status"=>'1', "message"=>'Plumber Data', "result"=> $data);
				}
		}else{
			$jsonArray = array("status"=>'0', "message"=>'Invalid API', "result"=> null);
		}
		echo json_encode($jsonArray);
	}



	public function getplumbersData($type, $data = []){

		if ($type == 'regplumber') {

			$totalcount 	= $this->Api_Model->getplumbersLists('count', ['type' => '3', 'approvalstatus' => ['1'], 'status' => ['1'], 'search_plumberstatus' => '1', 'search_reg_no' => $data['regno'], 'customsearch' => 'listsearch1', 'page' => 'adminplumberlist'], ['users', 'usersdetail', 'usersplumber', 'usersskills', 'company', 'physicaladdress']);

			$results 		= $this->Api_Model->getplumbersLists('all', ['type' => '3', 'approvalstatus' => ['1'], 'status' => ['1'], 'search_plumberstatus' => '1', 'search_reg_no' => $data['regno'], 'customsearch' => 'listsearch1', 'page' => 'adminplumberlist'], ['users', 'usersdetail', 'usersplumber', 'usersskills', 'company', 'physicaladdress', 'postaladdress', 'billingaddress']);

				$getcity = $this->Managearea_Model->getListCity('all', ['status' => ['0', '1']]);
				if(count($getcity) > 0) {
					$citydata=  ['' => 'Select City']+array_column($getcity, 'name', 'id');
				}else{
					$citydata = [];
				}
				$getsuburb = $this->Managearea_Model->getListSuburb('all', ['status' => ['0', '1']]);
				if(count($getsuburb) > 0) {
					$suburbdata=  ['' => 'Select suburb']+array_column($getsuburb, 'name', 'id');
				}
				else {
					$suburbdata = [];
				}

			foreach ($results as $key => $value) {
				if (isset($value['specialisations']) && $value['specialisations'] !='') {
					if(isset($plumberspecialisations)) unset($plumberspecialisations);
					$specialisationsarray = explode(',', $value['specialisations']);
					foreach ($specialisationsarray as $specialisationsarraykey => $specialisationsarrayvalue) {
						$plumberspecialisations[] = $this->config->item('specialisations')[$specialisationsarrayvalue];
					}
				}
				if(isset($physicaladdress[3]) && (1 === preg_match('/^[0-9]+$/', $physicaladdress[3]))){
				    $suburb1 	= isset($physicaladdress[3]) ? $suburbdata[$physicaladdress[3]] : '';
				}else{
					$suburb1 	= isset($physicaladdress[3]) ? $physicaladdress[3] : '';
				}
				if(isset($physicaladdress[4]) && (1 === preg_match('/^[0-9]+$/', $physicaladdress[4]))){
				    $city1 	= isset($physicaladdress[4]) ? $citydata[$physicaladdress[4]] : '';
				}else{
					$city1 	= isset($physicaladdress[4]) ? $physicaladdress[4] : '';
				}
				if(isset($physicaladdress[5]) && (1 === preg_match('/^[0-9]+$/', $physicaladdress[5]))){
				    $province1 	= isset($physicaladdress[5]) ? $this->getProvinceList()[$physicaladdress[5]] : '';
				}else{
					$province1 	= isset($physicaladdress[5]) ? $physicaladdress[5] : '';
				}

				$physicaladdress 		= isset($value['physicaladdress']) ? explode('@-@', $value['physicaladdress']) : [];
				$addressid1 			= isset($physicaladdress[0]) ? $physicaladdress[0] : '';
				/*$suburb1 				= isset($physicaladdress[3]) ? $physicaladdress[3] : '';
				$city1 					= isset($physicaladdress[4]) ? $physicaladdress[4] : '';
				$province1 				= isset($physicaladdress[5]) ? $this->getProvinceList()[$physicaladdress[5]] : '';*/

				$plumberData[] = [
					'id' 				=> $value['id'],
					'plumbername' 		=> $value['name'],
					'plumbersurname' 	=> $value['surname'],
					'mobile' 			=> $value['mobile_phone'],
					'province' 			=> $province1,
					'suburb' 			=> $suburb1,
					'city' 				=> $city1,
					'designation' 		=> $this->config->item('designation2')[$value['designation']],
					'specialisations' 	=> isset($plumberspecialisations) ? implode(',', $plumberspecialisations) : '',
					'totalcount'		=> $totalcount
				];
			}
		}elseif($type == 'suburbplumber'){

			$totalcount 	= $this->Api_Model->getplumbersLists_suburb('count', ['type' => '3', 'approvalstatus' => ['1'], 'status' => ['1'], 'suburubid' => $data['suburubid'], 'suburuname' => $data['suburubname']], ['users', 'usersdetail', 'usersplumber', 'physicaladdress']);

			$results 		= $this->Api_Model->getplumbersLists_suburb('all', ['type' => '3', 'approvalstatus' => ['1'], 'status' => ['1'], 'suburubid' => $data['suburubid'], 'suburuname' => $data['suburubname']], ['users', 'usersdetail', 'usersplumber', 'physicaladdress']);

			$getcity = $this->Managearea_Model->getListCity('all', ['status' => ['0', '1']]);
				if(count($getcity) > 0) {
					$citydata=  ['' => 'Select City']+array_column($getcity, 'name', 'id');
				}else{
					$citydata = [];
				}
				$getsuburb = $this->Managearea_Model->getListSuburb('all', ['status' => ['0', '1']]);
				if(count($getsuburb) > 0) {
					$suburbdata=  ['' => 'Select suburb']+array_column($getsuburb, 'name', 'id');
				}
				else {
					$suburbdata = [];
				}

				foreach ($results as $key => $value) {
				if (isset($value['specialisations']) && $value['specialisations'] !='') {
					if(isset($plumberspecialisations)) unset($plumberspecialisations);
					$specialisationsarray = explode(',', $value['specialisations']);
					foreach ($specialisationsarray as $specialisationsarraykey => $specialisationsarrayvalue) {
						$plumberspecialisations[] = $this->config->item('specialisations')[$specialisationsarrayvalue];
					}
				}

				$physicaladdress 		= isset($value['physicaladdress']) ? explode('@-@', $value['physicaladdress']) : [];
				if(isset($physicaladdress[3]) && (1 === preg_match('/^[0-9]+$/', $physicaladdress[3]))){
				    $suburb1 	= isset($physicaladdress[3]) ? $suburbdata[$physicaladdress[3]] : '';
				}else{
					$suburb1 	= isset($physicaladdress[3]) ? $physicaladdress[3] : '';
				}
				if(isset($physicaladdress[4]) && (1 === preg_match('/^[0-9]+$/', $physicaladdress[4]))){
				    $city1 	= isset($physicaladdress[4]) ? $citydata[$physicaladdress[4]] : '';
				}else{
					$city1 	= isset($physicaladdress[4]) ? $physicaladdress[4] : '';
				}
				if(isset($physicaladdress[5]) && (1 === preg_match('/^[0-9]+$/', $physicaladdress[5]))){
				    $province1 	= isset($physicaladdress[5]) ? $this->getProvinceList()[$physicaladdress[5]] : '';
				}else{
					$province1 	= isset($physicaladdress[5]) ? $physicaladdress[5] : '';
				}

				$physicaladdress 		= isset($value['physicaladdress']) ? explode('@-@', $value['physicaladdress']) : [];
				$addressid1 			= isset($physicaladdress[0]) ? $physicaladdress[0] : '';
				/*$suburb1 				= isset($physicaladdress[3]) ? $physicaladdress[3] : '';
				$city1 					= isset($physicaladdress[4]) ? $physicaladdress[4] : '';
				$province1 				= isset($physicaladdress[5]) ? $this->getProvinceList()[$physicaladdress[5]] : '';*/

				$plumberData[] = [
					'id' 				=> $value['id'],
					'plumbername' 		=> $value['name'],
					'plumbersurname' 	=> $value['surname'],
					'mobile' 			=> $value['mobile_phone'],
					'province' 			=> $province1,
					'suburb' 			=> $suburb1,
					'city' 				=> $city1,
					'designation' 		=> $this->config->item('designation2')[$value['designation']],
					'specialisations' 	=> isset($plumberspecialisations) ? implode(',', $plumberspecialisations) : '',
					'totalcount'		=> $totalcount
				];
			}
		}
		return $plumberData;
	}

}