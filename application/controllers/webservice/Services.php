<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Services extends CC_Controller 
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

		$this->load->model('Plumber_Model');
		$this->load->model('Managearea_Model');
		$this->load->model('Friends_Model');
		$this->load->model('Systemsettings_Model');
		$this->load->model('Coc_Model');
	}
	
	public function national_ranking()
	{
		$extras['extras'] = ['heading' => 'Industry Ranking', 'subheading' => 'National'];
		
		if($this->input->post()){
			$this->form_validation->set_rules('id', 'ID', 'trim|required');
			
			if ($this->form_validation->run()==FALSE) {
				$json = array("status" => "0", "message" => $this->errormessage(validation_errors()), 'result' => []);
			}else{
				$rollingavg 	= $this->getRollingAverage();
				$date			= date('Y-m-d', strtotime(date('Y-m-d').'+'.$rollingavg.' months'));
				$ranking 		= $this->Plumber_Model->performancestatus('all', ['date' => $date, 'archive' => '0', 'overall' => '1']);
				
				if(count($ranking) > 0){
					$result = [];
					
					foreach($ranking as $data){
						$userid = $data['userid'];
						$image 	= $data['image'];
						
						if(file_exists('./assets/uploads/plumber/'.$userid.'/'.$image)){
							$image = base_url().'assets/uploads/plumber/'.$userid.'/'.$image;
						}else{
							$image = '';
						}
						
						$result[] = [
							'id' 		=> $userid,
							'name' 		=> $data['name'],
							'point' 	=> $data['point'],
							'image' 	=> $image
						];
					}
					
					$json = array("status" => "1", "message" => count($result)." Record Found", "result" => $result);
				}else{
					$json = array("status" => "0", "message" => "No Record Found", "result" => []);
				}
			}
		}else{
			$json = array("status" => "0", "message" => "Invalid Request", "result" => []);
		}
		
		echo json_encode($json+$extras);
	}
	
	public function province_ranking()
	{
		$extras['extras'] = ['heading' => 'Industry Ranking', 'subheading' => 'Provincial'];
		
		if($this->input->post()){
			$this->form_validation->set_rules('id', 'ID', 'trim|required');
			
			if ($this->form_validation->run()==FALSE) {
				$json = array("status" => "0", "message" => $this->errormessage(validation_errors()), 'result' => []);
			}else{
				$post			= $this->input->post();
				
				$userdetail		= $this->getUserDetails($post['id']);
				
				$province 		= $this->Managearea_Model->getListProvince('row', ['id' => $userdetail['province']]);
				$extras['extras']['subheading'] = 'Provincial - '.$province['name'];
				
				$rollingavg 	= $this->getRollingAverage();
				$date			= date('Y-m-d', strtotime(date('Y-m-d').'+'.$rollingavg.' months'));
				$ranking 		= $this->Plumber_Model->performancestatus('all', ['date' => $date, 'archive' => '0', 'province' => $userdetail['province']]);
				
				if(count($ranking) > 0){
					$result = [];
					
					foreach($ranking as $data){
						$userid = $data['userid'];
						$image 	= $data['image'];
						
						if(file_exists('./assets/uploads/plumber/'.$userid.'/'.$image)){
							$image = base_url().'assets/uploads/plumber/'.$userid.'/'.$image;
						}else{
							$image = '';
						}
						
						$result[] = [
							'id' 		=> $userid,
							'name' 		=> $data['name'],
							'point' 	=> $data['point'],
							'image' 	=> $image
						];
					}
					
					$json = array("status" => "1", "message" => count($result)." Record Found", "result" => $result);
				}else{
					$json = array("status" => "0", "message" => "No Record Found", "result" => []);
				}
			}
		}else{
			$json = array("status" => "0", "message" => "Invalid Request", "result" => []);
		}
		
		echo json_encode($json+$extras);
	}
	
	public function my_friends()
	{
		$extras['extras'] = ['heading' => 'Industry Ranking', 'subheading' => 'My Friends'];
		
		if($this->input->post()){
			$this->form_validation->set_rules('id', 'ID', 'trim|required');
			
			if ($this->form_validation->run()==FALSE) {
				$json = array("status" => "0", "message" => $this->errormessage(validation_errors()), 'result' => []);
			}else{
				$post			= $this->input->post();
				$id				= $post['id'];
				
				$rollingavg 	= $this->getRollingAverage();
				$date			= date('Y-m-d', strtotime(date('Y-m-d').'+'.$rollingavg.' months'));
				
				
				$friends 		= $this->Friends_Model->getList('all', ['userid' => $id, 'fromto' => $id, 'status' => ['1']]);
				$result			= [];
				if(count($friends) > 0){
					foreach($friends as $friend){
						$userid					= $friend['userid'];
						$filepath				= base_url().'assets/uploads/plumber/'.$userid.'/';
						$file2 					= isset($friend['file2']) ? $friend['file2'] : '';
						if($file2!=''){
							$explodefile2 	= explode('.', $file2);
							$extfile2 		= array_pop($explodefile2);
							$photoidimg 	= (in_array($extfile2, ['pdf', 'tiff'])) ? $pdfimg : $filepath.$file2;
						}else{
							$photoidimg 	= $profileimg;
						}
						
						$friendperformance 	= $this->Plumber_Model->performancestatus('all', ['date' => $date, 'archive' => '0', 'overall' => '1', 'plumberid' => $friend['userid']]);
						$point 				= count($friendperformance) ? array_sum(array_column($friendperformance, 'point')) : '0';
						$useridsearch		= array_search($userid, array_column($friendperformance, 'userid'));
						$rank				= ($useridsearch !== false) ? $useridsearch+1 : 0;
				
						$result[] =  [
							'id' 		=> $friend['id'],
							'userid' 	=> $userid,
							'name' 		=> $friend['name'],
							'point' 	=> $point,
							'rank' 		=> $rank,
							'image' 	=> $photoidimg
						];
					}
					
					array_multisort(array_column($result, 'rank'), SORT_ASC, $result);
					$json = array("status" => "1", "message" => count($result)." Record Found", "result" => $result);
				}else{
					$json = array("status" => "0", "message" => "No Record Found", "result" => []);
				}
			}
		}else{
			$json = array("status" => "0", "message" => "Invalid Request", "result" => []);
		}
		
		echo json_encode($json+$extras);
	}
	
	public function my_friends_delete()
	{
		if($this->input->post()){
			$this->form_validation->set_rules('id', 'ID', 'trim|required');
			
			if ($this->form_validation->run()==FALSE) {
				$json = array("status" => "0", "message" => $this->errormessage(validation_errors()), 'result' => []);
			}else{
				$post			= $this->input->post();				
				$this->Friends_Model->remove($post);
				
				$json = array("status" => "1", "message" => "Successfully Deleted", "result" => []);
			}
		}else{
			$json = array("status" => "0", "message" => "Invalid Request", "result" => []);
		}
		
		echo json_encode($json);
	}
	
	public function otp(){
		if($this->input->post()){
			$this->form_validation->set_rules('id', 'ID', 'trim|required');
			$this->form_validation->set_rules('mobile', 'Mobile Number', 'trim|required');
			
			if ($this->form_validation->run()==FALSE) {
				$json = array("status" => "0", "message" => $this->errormessage(validation_errors()), 'result' => []);
			}else{
				$post		= $this->input->post();
				$userid 	= $post['id'];
				$mobile 	= str_replace([' ', '(', ')', '-'], ['', '', '', ''], trim($post['mobile']));
				$otp		= rand (10000, 99999);
				
				$query = $this->db->get_where('otp', ['user_id' => $userid]);
				if ($query->num_rows() == 1) {
					$this->db->update('otp', ['otp' => $otp, 'mobile' => $mobile], ['user_id' => $userid]);
				}else{
					$this->db->insert('otp', ['otp' => $otp, 'mobile' => $mobile, 'user_id' => $userid]);
				}		
				
				$settingsdetail = $this->Systemsettings_Model->getList('row');
				if($settingsdetail && $settingsdetail['otp']=='1'){
					$this->sms(['no' => $mobile, 'msg' => 'One Time Password is '.$otp]);
					$otpstatus = '1';
				}else{
					$otpstatus = '0';
				}
				
				$json = array("status" => "1", "message" => "OTP sent successfully.", "result" => ['otp' => $otp, 'otpstatus' => $otpstatus]);
			}
		}else{
			$json = array("status" => "0", "message" => "Invalid Request", "result" => []);
		}
		
		echo json_encode($json);
	}
	
	public function otp_verification(){
		if($this->input->post()){
			$this->form_validation->set_rules('id', 'ID', 'trim|required');
			$this->form_validation->set_rules('otp', 'OTP', 'trim|required');
			
			if ($this->form_validation->run()==FALSE) {
				$json = array("status" => "0", "message" => $this->errormessage(validation_errors()), 'result' => []);
			}else{
				$post	= $this->input->post();
				$result = $this->db->from('otp')->where(['otp' => $post['otp'], 'user_id' => $post['id']])->get()->row_array();
				
				if ($result) {
					$json = array("status" => "1", "message" => "Successfully Verified.", "result" => []);
				}else{
					$json = array("status" => "0", "message" => "Invalid OTP.", "result" => []);
				}
			}
		}else{
			$json = array("status" => "0", "message" => "Invalid Request", "result" => []);
		}
		
		echo json_encode($json);
	}
	
	function errormessage($error){
		return str_replace("\n", "", strip_tags($error));
	}
	
	
	public function purchasecoc(){
		if($this->input->post()){
			$this->form_validation->set_rules('id', 'ID', 'trim|required');
			$this->form_validation->set_rules('amount', 'Amount', 'trim|required');
			$this->form_validation->set_rules('coc_type', 'COC Type', 'trim|required');
			$this->form_validation->set_rules('delivery_type', 'Delivery Type', 'trim');
			$this->form_validation->set_rules('cost_value', 'Cost Value', 'trim|required');
			$this->form_validation->set_rules('quantity', 'Quantity', 'trim|required');
			$this->form_validation->set_rules('vat', 'Vat', 'trim|required');
			$this->form_validation->set_rules('total_due', 'Total Due', 'trim|required');
			$this->form_validation->set_rules('delivery_cost', 'Delivery Cost', 'trim|required');
			$this->form_validation->set_rules('permittedcoc', 'Permitted Coc', 'trim|required');
			$this->form_validation->set_rules('userid', 'Userid', 'trim|required');
			
			if ($this->form_validation->run()==FALSE) {
				$json = array("status" => "0", "message" => $this->errormessage(validation_errors()), 'result' => []);
			}else{
				$post				= 	$this->input->post();
				
				$data['post']		= 	$post;			
				$data['plumber']	= 	$this->Plumber_Model->getList('row', ['id' => $post['id']], ['users', 'usersdetail']);
				
				$this->load->view('api/purchasecoc/index', $data);
			}
		}else{
			$json = array("status" => "0", "message" => "Invalid Request", "result" => []);
			echo json_encode($json);
		}
	}
	
	public function purchasecocreturn(){
		echo 'Success';
	}
	
	public function purchasecoccancel(){
		echo 'Cancelled';
	}
	
	public function purchasecocnotify(){
		header( 'HTTP/1.0 200 OK' );
		flush();
		
		$result = $_POST;
		$this->Coc_Model->purchasecoc($result);
	}
	
	public function googleapikey(){
		echo $this->config->item('googleapikey');
	}
	
}