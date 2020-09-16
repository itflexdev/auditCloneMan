<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CC_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Auditor_allocatecoc_Model');
		$this->load->model('Communication_Model');
		$this->load->model('Systemsettings_Model');
		$this->load->model('CC_Model');
	}
	
	public function index()
	{
		$this->checkUserPermission('27', '1');
		
		if($this->input->post()){
			$requestData 	= 	$this->input->post();
			
			foreach($requestData['allocate'] as $allocate){
				$auditorid 	= $allocate['auditorid'];
				$plumberid 	= $allocate['plumberid'];
				$cocid 		= $allocate['cocid'];
				
				$result 	=  	$this->Auditor_allocatecoc_Model->action(['auditor_id' => $auditorid, 'coc_id' => $cocid]);	
				
				if($result){
					$this->CC_Model->diaryactivity(['adminid' => $this->getUserID(), 'plumberid' => $plumberid, 'auditorid' => $auditorid, 'cocid' => $cocid, 'action' => '8', 'type' => '1']);
					
					$plumberdata		= $this->getUserDetails($plumberid);				
					$auditordata		= $this->getUserDetails($auditorid);				
					$notificationdata 	= $this->Communication_Model->getList('row', ['id' => '20', 'emailstatus' => '1']);
					$settingsdetail 	= $this->Systemsettings_Model->getList('row');
					
					if($notificationdata){
						$array1 = ['{Plumbers Name and Surname}','{COC number}', '{Auditors Names and Surname}'];
						$array2 = [$plumberdata['name'], $cocid, $auditordata['name']];
						
						$body 		= str_replace($array1, $array2, $notificationdata['email_body']);
						$subject 	= str_replace(['{cocno}'], [$cocid], $notificationdata['subject']);
						$this->CC_Model->sentMail($plumberdata['email'], $subject, $body);
					}
					
					if($settingsdetail && $settingsdetail['otp']=='1'){
						$smsdata 	= $this->Communication_Model->getList('row', ['id' => '20', 'smsstatus' => '1']);
			
						if($smsdata){
							$sms = str_replace(['{number of COC}', '{auditors name and surname}'], [$cocid, $auditordata['name']], $smsdata['sms_body']);
							$this->sms(['no' => $plumberdata['mobile_phone'], 'msg' => $sms]);
						}
					}
				}
			}
			
			redirect('admin/audits/cocallocate/index');
		}
		
		$pagedata['notification'] 		= $this->getNotification();
		$pagedata['company'] 			= $this->getCompanyList();
		$pagedata['province'] 			= $this->getProvinceList();
		$pagedata['plumberstatus'] 		= $this->config->item('plumberstatus');
		$pagedata['checkpermission'] 	= $this->checkUserPermission('27', '2');
		$pagedata['totalcoccount'] 		= $this->Auditor_allocatecoc_Model->getCOCList('count', []);
		
		$data['plugins']				= ['datatables', 'datatablesresponsive', 'sweetalert', 'datepicker', 'select2'];
		$data['content'] 				= $this->load->view('admin/audits/cocallocate/index', (isset($pagedata) ? $pagedata : ''), true);
		
		$this->layout2($data);		
	}
	
	
	public function DTAllocateAudit($pagination=0)
	{
		$post 				= $this->input->post();
		$results 			= $this->Auditor_allocatecoc_Model->getList('all', $post);
		$checkpermission	= $this->checkUserPermission('27', '2');

		$totalrecord 	= [];
		$noofcoc		= 0;
		$noofcocbr		= 0;
		
		if(count($results) > 0){
			$coccountattr = '';
			
			foreach($results as $index => $result){
				
				if($post['no_coc_allocation']!=''){
					$postcocallocation 	= $post['no_coc_allocation'];
					$noofcoc 			+= $result['coccount'];
					
					if($noofcocbr==1){
						break;
					}
					
					if($noofcoc >= $postcocallocation){
						$noofcocbr = 1;
					}		
					
					if($noofcoc > $postcocallocation){
						$coccountattr = $postcocallocation;
					}else{
						$coccountattr = $result['coccount'];
					}					
				}
				
				$audit 			= $result['audit'];
				$rollingavg 	= $this->getRollingAverage();
				$date			= date('Y-m-d', strtotime(date('Y-m-d').'+'.$rollingavg.' months'));
				$user_id 		= $result['user_id']; 
				$performance 	= $this->Plumber_Model->performancestatus('all', ['plumberid' => $user_id, 'archive' => '0', 'date' => $date]);
				$overallpoint 	= array_sum(array_column($performance, 'point'));
				
				$checkcocranking 	= [];
				if($post['compulsory_audit']=='1') 									$checkcocranking[] = ($result['auditassign'] <= $result['auditcomplete']) ? '0' : '1';
				if($post['audit_ratio_start']!='' && $post['audit_ratio_end']=='') 	$checkcocranking[] = ($audit < $post['audit_ratio_start']) ? '0' : '1';
				if($post['audit_ratio_start']=='' && $post['audit_ratio_end']!='') 	$checkcocranking[] = ($audit > $post['audit_ratio_end']) ? '0' : '1';
				if($post['audit_ratio_start']!='' && $post['audit_ratio_end']!='') 	$checkcocranking[] = ($audit >=  $post['audit_ratio_start'] && $audit <= $post['audit_ratio_end']) ? '1' : '0';
				if($post['rating_start']!='' && $post['rating_end']=='') 			$checkcocranking[] = ($overallpoint < $post['rating_start']) ? '0' : '1';
				if($post['rating_start']=='' && $post['rating_end']!='') 			$checkcocranking[] = ($overallpoint > $post['rating_end']) ? '0' : '1';
				if($post['rating_start']!='' && $post['rating_end']!='') 			$checkcocranking[] = ($overallpoint >= $post['rating_start'] && $overallpoint <= $post['rating_end']) ? '1' : '0';
				
				if(count($checkcocranking) && array_sum($checkcocranking)=='0'){
					continue;
				}
				
				if($checkpermission){
					$action = 	"<a href='javascript:void(0);' class='cocaccordion' data-coc-count='".$coccountattr."' data-user-id='".$user_id."'><i class='fa fa-caret-up caretup'></i><i class='fa fa-caret-down caretdown displaynone'></i></a>";
				}else{
					$action = '';
				}
				
				$totalrecord[] = 	[
										'plumbername' 			=> 	$result['plumbername'],
										'regno' 				=> 	$result['regno'],
										'company' 				=> 	$result['company'],
										'city' 					=> 	$result['cityname'],
										'province' 				=> 	$result['provincename'],
										'audit' 				=> 	($result['audit']!='') ? $result['audit'].'%' : '',
										'cautionary' 			=> 	($result['cautionary']!='') ? $result['cautionary'].'%' : '',
										'refix_incomplete' 		=> 	($result['refix_incomplete']!='') ? $result['refix_incomplete'].'%' : '',
										'refix_complete' 		=> 	($result['refix_complete']!='') ? $result['refix_complete'].'%' : '',
										'rating' 				=> 	$overallpoint,
										'coc_link' 				=> 	$action
									];
			}
		}
		
		$this->load->library('pagination');
		$config['base_url'] 			= base_url().'admin/audits/cocallocate/index/DTAllocateAudit/';
		$config['total_rows'] 			= count($totalrecord);
		$config['per_page'] 			= 10;
		$config['num_tag_open'] 		= '<span class="paginate_button">';
		$config['num_tag_close'] 		= '</span>';
		$config['cur_tag_open'] 		= '<span class="paginate_button current">';
		$config['cur_tag_close'] 		= '</span>';
		$config['first_link'] 			= FALSE;
		$config['last_link'] 			= FALSE;
		$config['next_link'] 			= '>>';
		$config['next_tag_open'] 		= '<span class="paginate_button next">';
		$config['next_tag_close'] 		= '</span>';
		$config['prev_link'] 			= '<<';
		$config['prev_tag_open'] 		= '<span class="paginate_button previous">';
		$config['prev_tag_close'] 		= '</span>';
        $config['page_query_string'] 	= FALSE;
        $config['use_page_numbers'] 	= TRUE;
		
		$this->pagination->initialize($config);
		
		$perpage = $config['per_page'];
		if($pagination!= 0){
          $pagination = ($pagination-1) * $perpage;
        }
		
		$entries1 = $pagination+1;
		$entries2 = (($pagination+$perpage) >= count($totalrecord)) ? count($totalrecord) : $pagination+$perpage;
		$json = array(
			"data"   		=> array_slice($totalrecord, $pagination, $perpage),
			"pagination"   	=> $this->pagination->create_links(),
			"info"   		=> 'Showing '.$entries1.' to '.$entries2.' of '.count($totalrecord).' entries'
		);

		echo json_encode($json);
	}

	public function coc()
	{ 
		$post 			= $this->input->post();
		$result 		= $this->Auditor_allocatecoc_Model->getCOCList('all', $post);
		echo json_encode(array("result" => $result));
	}
} 

