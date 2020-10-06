<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CC_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
	
	public function index()
	{
		$pagedata['totalplumber'] 		= $this->Plumber_Model->getList('count', ['type' => '3', 'approvalstatus' => ['0','1'], 'status' => ['1', '2']], ['users', 'usersplumber']);
		
		$pagedata['statusactive'] 		= $this->Plumber_Model->getList('count', ['type' => '3', 'approvalstatus' => ['0','1'], 'plumberstatus' => ['1'], 'status' => ['1']], ['users', 'usersdetail', 'usersplumber']);
		$pagedata['statussuspended'] 	= $this->Plumber_Model->getList('count', ['type' => '3', 'approvalstatus' => ['0','1'], 'plumberstatus' => ['6'], 'status' => ['1']], ['users', 'usersdetail', 'usersplumber']);
		$pagedata['statusexpired'] 		= $this->Plumber_Model->getList('count', ['type' => '3', 'approvalstatus' => ['0','1'], 'plumberstatus' => ['3', '5'], 'status' => ['2']], ['users', 'usersdetail', 'usersplumber']);
		$pagedata['statuspending'] 		= $this->Plumber_Model->getList('count', ['type' => '3', 'approvalstatus' => ['0','1'], 'plumberstatus' => ['0'], 'status' => ['1']], ['users', 'usersdetail', 'usersplumber']);
		$pagedata['statuscpdsuspend'] 	= $this->Plumber_Model->getList('count', ['type' => '3', 'approvalstatus' => ['0','1'], 'plumberstatus' => ['2'], 'status' => ['1']], ['users', 'usersdetail', 'usersplumber']);
		
		$pagedata['designationl'] 		= $this->Plumber_Model->getList('count', ['type' => '3', 'approvalstatus' => ['0','1'], 'status' => ['1', '2'], 'designation' => ['1']], ['users', 'usersplumber']);
		$pagedata['designationtap'] 	= $this->Plumber_Model->getList('count', ['type' => '3', 'approvalstatus' => ['0','1'], 'status' => ['1', '2'], 'designation' => ['2']], ['users', 'usersplumber']);
		$pagedata['designationtop'] 	= $this->Plumber_Model->getList('count', ['type' => '3', 'approvalstatus' => ['0','1'], 'status' => ['1', '2'], 'designation' => ['3']], ['users', 'usersplumber']);
		$pagedata['designationqp'] 		= $this->Plumber_Model->getList('count', ['type' => '3', 'approvalstatus' => ['0','1'], 'status' => ['1', '2'], 'designation' => ['4']], ['users', 'usersplumber']);
		$pagedata['designationlp'] 		= $this->Plumber_Model->getList('count', ['type' => '3', 'approvalstatus' => ['0','1'], 'status' => ['1', '2'], 'designation' => ['5']], ['users', 'usersplumber']);
		$pagedata['designationmp'] 		= $this->Plumber_Model->getList('count', ['type' => '3', 'approvalstatus' => ['0','1'], 'status' => ['1', '2'], 'designation' => ['6']], ['users', 'usersplumber']);
		
		$pagedata['racial1'] 			= $this->Plumber_Model->getList('count', ['type' => '3', 'approvalstatus' => ['0','1'], 'status' => ['1', '2'], 'racial' => ['2']], ['users', 'usersplumber']);
		$pagedata['racial2'] 			= $this->Plumber_Model->getList('count', ['type' => '3', 'approvalstatus' => ['0','1'], 'status' => ['1', '2'], 'racial' => ['3']], ['users', 'usersplumber']);
		$pagedata['racial3'] 			= $this->Plumber_Model->getList('count', ['type' => '3', 'approvalstatus' => ['0','1'], 'status' => ['1', '2'], 'racial' => ['1']], ['users', 'usersplumber']);
		$pagedata['racial4'] 			= $this->Plumber_Model->getList('count', ['type' => '3', 'approvalstatus' => ['0','1'], 'status' => ['1', '2'], 'racial' => ['4']], ['users', 'usersplumber']);
		
		$pagedata['genderm'] 			= $this->Plumber_Model->getList('count', ['type' => '3', 'approvalstatus' => ['0','1'], 'status' => ['1', '2'], 'gender' => ['1']], ['users', 'usersdetail', 'usersplumber']);
		$pagedata['genderf'] 			= $this->Plumber_Model->getList('count', ['type' => '3', 'approvalstatus' => ['0','1'], 'status' => ['1', '2'], 'gender' => ['2']], ['users', 'usersdetail', 'usersplumber']);
		
		$pagedata['totalcoc']			= $this->Coc_Model->getCOCList('count', ['nococstatus' => ['1']]);
		$pagedata['totalelectroniccoc']	= $this->Coc_Model->getCOCList('count', ['nococstatus' => ['1'], 'coctype' => ['1']]);
		$pagedata['totalpapercoc']		= $this->Coc_Model->getCOCList('count', ['nococstatus' => ['1'], 'coctype' => ['2']]);
		$pagedata['totallogged']		= $this->Coc_Model->getCOCList('count', ['cocstatus' => ['2']]);
		$pagedata['totalreseller']		= $this->Coc_Model->getCOCList('count', ['cocstatus' => ['3']]);
		$pagedata['totalaudit']			= $this->Coc_Model->getCOCList('count', ['noaudit' => '1']);
		
		$pagedata['history']			= $this->Auditor_Model->getReviewHistoryCount();
		
		$sixmonthgraph = [];
		for($i = 0; $i <= 5; $i++){
			$sixmonthgraph[] = [
				'month' => date('F', strtotime('-'.$i.' months')), 
				'electronic' => $this->Coc_Model->getCOCList('count', ['nococstatus' => ['1'], 'coctype' => ['1'], 'monthArray' => date('Y-m', strtotime('-'.$i.' months')), 'monthrange' => '1']),
				'paper' => $this->Coc_Model->getCOCList('count', ['nococstatus' => ['1'], 'coctype' => ['2'], 'monthArray' => date('Y-m', strtotime('-'.$i.' months')), 'monthrange' => '1'])
			];
		}	
		$pagedata['sixmonthgraph']	= array_reverse($sixmonthgraph);
		
		$data['plugins'] = ['knob', 'echarts'];
		$data['content'] = $this->load->view('admin/dashboard/index', $pagedata, true);
		$this->layout2($data);
	}
}
