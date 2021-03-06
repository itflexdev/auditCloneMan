<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_listing extends CC_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('CC_Model');
		$this->load->model('Company_Model');
		$this->load->model('Communication_Model');
		$this->load->model('Company_allocatecoc_Model');
	}
	
	public function index($userID='')
	{
        if ($userID!='') {

            $result = $this->Company_Model->getEmpList('employee', ['comp_id' => $userID, 'type' => '3', 'status' => ['0','1', '2']]);

           $pagedata['specialization']  = $this->config->item('specialisations');
            $pagedata['employee'] = $result;
            $pagedata['company']        = $this->getCompanyList();
            $pagedata['plumberstatus']  = $this->config->item('plumberstatus');
           
            ////$pagedata['history']        = $this->Auditor_Model->getReviewHistory2Count(['plumberid' => $result[0]['user_id']]);
            $pagedata['history']        = $this->Auditor_Model->getReviewHistoryCount(['plumberid' => $result[0]['user_id']]);

            $pagedata['history2']       = $this->Auditor_Model->getReviewHistory2Count(['plumberid' => $result[0]['user_id']]);

            $pagedata['logged']         = $this->Coc_Model->getCOCList('count', ['user_id' => $result[0]['user_id'], 'coc_status' => ['2']]);

            $pagedata['allocated']      = $this->Coc_Model->getCOCList('count', ['user_id' => $result[0]['user_id'], 'coc_status' => ['4']]);

            $pagedata['nonlogged']      = $this->Coc_Model->getCOCList('count', ['user_id' => $result[0]['user_id'], 'coc_status' => ['5']]);

            $pagedata['user_details']   = $this->Plumber_Model->getList('row', ['id' => $result[0]['user_id']], ['usersplumber']);

            $pagedata['settings_cpd']   = $this->Systemsettings_Model->getList('all',['user_id' => $result[0]['user_id']]);
        }

		$companyID = $this->getuserID();
		$data['plugins']				= ['datatables','validation','datepicker','inputmask','select2', 'echarts'];
		$pagedata['notification'] 		= $this->getNotification();
		$pagedata['designation2']		= $this->config->item('designation2');
		$pagedata['plumberstatus']		= $this->config->item('plumberstatus');
        $pagedata['roletype']           = '5';
		$pagedata['id'] 				= $companyID;
		$data['content'] 				= $this->load->view('company/employee_listing', (isset($pagedata) ? $pagedata : ''), true);
		$this->layout2($data);

	}

	public function DTemplist()
    {
        $post = $this->input->post();
        $totalcount     = $this->Company_Model->getEmpList('count', ['type' => '4', 'approvalstatus' => ['0', '1'], 'formstatus' => ['1'], 'status' => ['0', '1', '2']] + $post);
        $results        = $this->Company_Model->getEmpList('all', ['type' => '4', 'approvalstatus' => ['0', '1'], 'formstatus' => ['1'], 'status' => ['0', '1', '2']] + $post);
       
        $companystatus  = $this->config->item('companystatus');
        $rollingavg                 = $this->getRollingAverage();
        $date                       = date('Y-m-d', strtotime(date('Y-m-d').'+'.$rollingavg.' months'));
        
        $totalrecord = [];
        if (count($results) > 0) {
            foreach ($results as $result) {
            	// $array_orderqty = [];
                $userdatacoc_count = $this->Coc_Model->COCcount(['user_id' => $result['user_id']]);
                $desigcount     = $this->Company_Model->getdesignationCount(['designation' => $result['designation']]);
                //print_r($desigcount);
                $performance = $this->Plumber_Model->performancestatus('all', ['plumberid' => $result['user_id'], 'archive' => '0', 'date' => $date]);

                $array_orderqty = $this->Company_allocatecoc_Model->getqty('row', ['user_id' => $result['user_id']]);
        		$balace_coc 	= $array_orderqty['sumqty'];

                $per_points = array_sum(array_column($performance, 'point'));
                // print_r($performance);die;
                $points     = $this->Company_Model->cpdPoints($result['user_id']);

                if ($points[0]['cpd']!=''){
                     $points         = round($points[0]['cpd'],2);
                }else{
                    $points         = '0';
                } 
                if( $per_points!=''){
                    $performance    = round($per_points,2);
                }else{
                    
                    $performance    = '0';
                }
                if ($result['designation']=='6' || $result['designation']=='4') {
                   $divclass = 'lm';
                   $divclass2 = 'lm2';
                }else{
                    $divclass = 'other';
                    $divclass2 = 'other2';
                }
                // $overall = round((number_format($points+$performance)/$desigcount[0]['desigcount']),1);//
                $overall = round((($points+$performance)/($desigcount[0]['desigcount'])),2);
                $companystatus1 = isset($companystatus[$result['status']]) ? $companystatus[$result['status']] : '';

                $action = '<div class="table-action">
                            <a href="' . base_url() . 'company/employee_listing/index/' . $result['id'] . '" data-toggle="tooltip" data-placement="top" title="View"><i class="fa fa-eye"></i></a>';

                if ($result['coc_electronic'] == 1 && $userdatacoc_count['count'] > 0) {
                    $action .= '<a href="' . base_url() . 'company/allocatecoc/index/index/' . $result['user_id'] . '" data-toggle="tooltip" data-placement="top" title="Allocate"><i class="fa fa-file-text"></i></a>';
                }
                
                $totalrecord[] = [
                    'reg'           => $result['registration_no'],
                    'designation'   => $this->config->item('designation2')[$result['designation']],
                    'status'        => $this->config->item('plumberstatus')[$result['status']],
                    'namesurname'   => $result['name'].' '.$result['surname'],
                    'cpdstatus'     => $points,
                    // 'perstatus'     => '<input type="hidden" value="'.$performance.'" class="'.$divclass2.'">'.$performance.'',
                    // 'rating'        => '<input type="hidden" value="'.$overall.'" class="'.$divclass.'">'.$overall.'',
                    'nococ'     	=> $balace_coc,
                    'action'        => $action,
                ];
            }
        }
       
        $json = array(
            "draw" => intval($post['draw']),
            "recordsTotal" => intval($totalcount),
            "recordsFiltered" => intval($totalcount),
            "data" => $totalrecord,
        );

        echo json_encode($json);
    }

    public function employees($id, $user_id)
	{
		
			$result = $this->Company_Model->getEmpList('employee', ['comp_id' => $user_id, 'type' => '3', 'status' => ['0','1', '2']]);
			$pagedata['employee'] = $result;
			$pagedata['specialization']	= $this->config->item('specialisations');

			$pagedata['company'] 		= $this->getCompanyList();
			$pagedata['plumberstatus'] 	= $this->config->item('plumberstatus');
			$userdata1					= $this->Plumber_Model->getList('row', ['id' => $result[0]['user_id']], ['usersplumber']);

			$pagedata['user_details'] 	= $userdata1;

			
			
			$pagedata['history']		= $this->Auditor_Model->getReviewHistory2Count(['auditorid' => '', 'plumberid' => $result[0]['user_id']]);
			$pagedata['settings_cpd']	= $this->Systemsettings_Model->getList('all',['user_id' => $result[0]['user_id']]);
			

			$pagedata['loggedcoc']		= $this->Coc_Model->getCOCList('count', ['user_id' => $result[0]['user_id'], 'coc_status' => ['2']]);
		
		//print_r($pagedata['loggedcoc']);die;
		$companyID = $this->getuserID();
		$data['plugins']				= ['datatables','validation','datepicker','inputmask','select2', 'echarts'];
		$pagedata['notification'] 		= $this->getNotification();
		$pagedata['designation2']		= $this->config->item('designation2');
		$pagedata['plumberstatus']		= $this->config->item('plumberstatus');
		$pagedata['id'] 				= $companyID;
		$data['content'] 				= $this->load->view('company/employee_listing', (isset($pagedata) ? $pagedata : ''), true);
		$this->layout2($data);
	}
}