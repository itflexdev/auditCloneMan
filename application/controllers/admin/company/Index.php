<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Index extends CC_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Company_Model');
        $this->load->model('Documentsletters_Model');
        $this->load->model('Companyperformancedetails_Model');
        $this->load->model('Companyperformance_Model');
    }

    public function index()
    {
        $this->checkUserPermission('20', '1');

        $pagedata['notification'] = $this->getNotification();
        $pagedata['checkpermission'] = $this->checkUserPermission('20', '2');
        $data['plugins'] = ['datatables', 'datatablesresponsive', 'sweetalert', 'validation'];
        $data['content'] = $this->load->view('admin/company/index', (isset($pagedata) ? $pagedata : ''), true);
        $this->layout2($data);
    }

    public function DTcompanylist()
    {
        $post = $this->input->post();
        $totalcount 	= $this->Company_Model->getList('count', ['type' => '4', 'approvalstatus' => ['0', '1'], 'formstatus' => ['1'], 'status' => ['0', '1', '2']] + $post, ['users', 'usersdetail', 'userscompany']);
        $results 		= $this->Company_Model->getList('all', ['type' => '4', 'approvalstatus' => ['0', '1'], 'formstatus' => ['1'], 'status' => ['0', '1', '2']] + $post, ['users', 'usersdetail', 'userscompany', 'lttqcount', 'lmcount']);
        $companystatus	= $this->config->item('companystatus');

        $checkpermission = $this->checkUserPermission('20', '2');
        
        $totalrecord = [];
        if (count($results) > 0) {
            foreach ($results as $result) {

                if ($checkpermission) {
                    $action = '<div class="table-action">
                                    <a href="' . base_url() . 'admin/company/index/action/'.$result['id'].'" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil-alt"></i></a>
                                </div>';
                }else{
                    $action = '';
                }

				$companystatus1 = isset($companystatus[$result['companystatus']]) ? $companystatus[$result['companystatus']] : '';
                $totalrecord[] = [
									'id' 			=> $result['id'],
									'company' 		=> $result['company'],
									'status' 		=> $companystatus1,
									'lmcount' 		=> $result['lmcount'],
									'lttqcount' 	=> $result['lttqcount'],
									'action' 		=>  $action,
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
                $desigcount     = $this->Company_Model->getdesignationCount(['designation' => $result['designation']]);
                //print_r($desigcount);
                $performance = $this->Plumber_Model->performancestatus('all', ['plumberid' => $result['user_id'], 'archive' => '0', 'date' => $date]);     

                $per_points = array_sum(array_column($performance, 'point'));
                // print_r($performance);die;
                $points     = $this->Company_Model->cpdPoints($result['user_id']);

                if ($points[0]['cpd']!=''){
                     $points         = $points[0]['cpd'];
                }else{
                    $points         = '0';
                } 
                if( $per_points!=''){
                    $performance    = $per_points;
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
                // $overall = round((number_format($points+$performance)/$desigcount[0]['desigcount']),1);
                $overall = round((($points+$performance)/($desigcount[0]['desigcount'])),2);
                $companystatus1 = isset($companystatus[$result['status']]) ? $companystatus[$result['status']] : '';
                $totalrecord[] = [
                                    'reg'           => $result['registration_no'],
                                    'designation'   => $this->config->item('designation2')[$result['designation']],
                                    'status'        => $this->config->item('plumberstatus')[$result['status']],
                                    'namesurname'   => $result['name'].' '.$result['surname'],
                                    'cpdstatus'     => round(($points),2),
                                    // 'perstatus'     => '<input type="hidden" value="'.$performance.'" class="'.$divclass2.'">'.$performance.'',
                                    // 'rating'        => '<input type="hidden" value="'.$overall.'" class="'.$divclass.'">'.$overall.'',
                                    'action'        => '
                                                            <div class="table-action">
                                                                <a href="' . base_url() . 'admin/company/index/empaction/'.$post['comp_id'].'/'.$result['id'].'" data-toggle="tooltip" data-placement="top" title="View"><i class="fa fa-eye"></i></a>
                                                            </div>
                                                        ',
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

    public function empaction($compid,$id)
    {
       $this->employee(['compid' => $compid, 'id' => $id], ['roletype' => $this->config->item('roleadmin'), 'pagetype' => 'adminempdetails'], ['redirect' => 'admin/company/company/employee_listing']);
    }
	
	public function action($id)
    {
        $this->companyprofile($id, ['roletype' => $this->config->item('roleadmin'), 'pagetype' => 'adminprofile'], ['redirect' => 'admin/company/index']);
    }

    public function rejected()
    {
         $this->checkUserPermission('21', '1');

        $pagedata['notification']   = $this->getNotification();
        $pagedata['checkpermission'] = $this->checkUserPermission('21', '2');
        
        $data['plugins']            = ['datatables', 'datatablesresponsive'];
        $data['content']            = $this->load->view('admin/company/rejected', (isset($pagedata) ? $pagedata : ''), true);
        
        $this->layout2($data);      
    }

    public function DTRejectedCompany()
    {
        $post = $this->input->post();
        // print_r($post);die;
        $totalcount     = $this->Company_Model->getList('count', ['type' => '4', 'approvalstatus' => ['2'], 'status' => ['0', '1', '2']] + $post, ['users', 'usersdetail', 'userscompany']);
        $results        = $this->Company_Model->getList('all', ['type' => '4', 'approvalstatus' => ['2'], 'status' => ['0', '1', '2']] + $post, ['users', 'usersdetail', 'userscompany']);
        // print_r($this->db->last_query());die;
        $companystatus  = $this->config->item('companystatus');

        $checkpermission = $this->checkUserPermission('21', '2');

        $totalrecord = [];
        if (count($results) > 0) {
            foreach ($results as $result) {

                if ($checkpermission) {
                    $action = 	'<div class="table-action">
									<a href="' . base_url() . 'admin/company/index/action/'.$result['id'].'" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil-alt"></i></a>
								</div>';
                }else{
                    $action = '';
                }


                $companystatus = $result['companystatus']!='' && isset($companystatus[$result['companystatus']]) ? $companystatus[$result['companystatus']] : '';
                $totalrecord[] = [
                                    'date'          => date('d-m-Y', strtotime($result['created_at'])),
                                    'company'       => $result['company'],
                                    'reason'        => $this->config->item('companyrejectreason')[$result['reject_reason']],
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
    
    public function rejectedaction($id)
    {
       // $this->plumberprofile($id, ['roletype' => $this->config->item('roleadmin'), 'pagetype' => 'rejectedapplications'], ['redirect' => 'admin/plumber/index/rejected']);
         $this->companyprofile($id, ['roletype' => $this->config->item('roleadmin'), 'pagetype' => 'rejectedapplications'], ['redirect' => 'admin/company/index/rejected']);
    }

    // Empployee Lsiting
    public function emplist($id){
         $this->employee($id, ['roletype' => $this->config->item('roleadmin'),'redirect' => 'admin/company/index/index']);
    }
	
	 public function diary($id){
        //print_r($id);die;
        $this->companydiary($id, ['roletype' => $this->config->item('roleadmin'),'redirect' => 'admin/company/index/index']);
    }

        // Accounts
    public function accounts($compId)
    {
        $userdata1                = $this->Company_Model->getList('row', ['id' => $compId], ['users', 'usersdetail']);
        $pagedata['user_details'] = $userdata1;
        $pagedata['roletype']     = $this->config->item('roleadmin');
        $pagedata['notification'] = $this->getNotification();
        $pagedata['companyid']    = $compId;
        $pagedata['menu']         = $this->load->view('common/company/menu', ['id' => $compId], true);
        $data['plugins']          = ['datatables', 'datatablesresponsive'];
        $data['content']          = $this->load->view('admin/company/accounts', (isset($pagedata) ? $pagedata : ''), true);
        $this->layout2($data);
    }

    public function DTAccounts()
    {
        $post = $this->input->post();
        
        $totalcount = $this->Company_Model->getInvoiceList('count', $post);
        $results    = $this->Company_Model->getInvoiceList('all', $post);
            
        // echo $this->db->last_query();
        // exit();

        $totalrecord = [];
        if (count($results) > 0) {
            foreach ($results as $result) {
                $invoicestatus = isset($this->config->item('payment_status2')[$result['status']]) ? $this->config->item('payment_status2')[$result['status']] : '';

                $originalDate = $result['created_at'];
                $newDate      = date("d-m-Y H:i:s", strtotime($originalDate));

                // $date=date("d-m-Y",);
                if ($result['total_due'] != '') {
                    $amt = $this->config->item('currency') . ' ' . $result['total_due'];
                } else {
                    $amt = $this->config->item('currency').' '.$result['total_due'];
                }

                $totalrecord[] = [
                    'description'   => $result['description'],
                    'inv_id'        => $result['inv_id'],
                    'created_at'    => $newDate,
                    'total_cost'    => $amt,
                    'invoicestatus' => $invoicestatus,
                    'action'        => '<div class="col-md-6">
                                            <a  href="' . base_url() . 'assets/inv_pdf/' . $result['inv_id'] . '.pdf" target="_blank" ><img src="' . base_url() . 'assets/images/pdf.png" height="50" width="50"></a>
                                        </div>',
                ];
            }
        }

        $json = array(
            "draw"            => intval($post['draw']),
            "recordsTotal"    => intval($totalcount),
            "recordsFiltered" => intval($totalcount),
            "data"            => $totalrecord,
        );

        echo json_encode($json);
    }

    // Document Letters
    public function documents($compId,$documentsid='')
    {
        if($documentsid!=''){
            $result = $this->Documentsletters_Model->getcompanyList('row', ['id' => $documentsid]);
            if($result){
                $pagedata['result'] = $result;              

            }else{
                $this->session->set_flashdata('error', 'No Record Found.');
                if($extras['redirect']) redirect($extras['redirect']); 
                else redirect('admin/company/index'); 
            }
        }
        
        if($this->input->post()){
            $requestData    =   $this->input->post();           
            $result     =  $this->Documentsletters_Model->action2($requestData);             
            if($result){
             $this->session->set_flashdata('success', 'Documents Letters '.(($result==1) ? 'created' : 'updated').' successfully.');

             redirect('admin/company/index/documents/'.$compId);
            }
            else{
             $this->session->set_flashdata('error', 'Try Later.');
            }

        }


        $userdata1  = $this->Company_Model->getList('row', ['id' => $compId], ['users', 'usersdetail']);
        $pagedata['user_details']   = $userdata1;
        $pagedata['roletype']       = $this->config->item('roleadmin');
        $pagedata['notification']   = $this->getNotification();
        $pagedata['companyid']      = $compId;
        $pagedata['menu']           = $this->load->view('common/company/menu', ['id'=>$compId],true);
        $data['plugins'] = ['datatables', 'datatablesresponsive', 'sweetalert', 'validation','inputmask'];
        $data['content'] = $this->load->view('admin/company/documents', (isset($pagedata) ? $pagedata : ''), true);
        $this->layout2($data);
    }


    public function DTDocuments()
    {
        $post       = $this->input->post();         
        $totalcount =  $this->Documentsletters_Model->getcompanyList('count',$post);
        $results    =  $this->Documentsletters_Model->getcompanyList('all',$post);
        $totalrecord    = [];
        if(count($results) > 0){
            foreach($results as $result){
                //echo date('Y-m-d', strtotime($result['updated_at']))!='0001-30-0001 00:00:00';die;
                $created_at = strtotime($result['created_at']);
                $upload_date = date("d-m-Y H:i:s", $created_at);
                if ($result['updated_at']!='') {
                    $updated_at = strtotime($result['updated_at']);
                    $update_date = date("d-m-Y H:i:s", $updated_at);

                    $full_date = $upload_date.' / '.$update_date;
                 }else{
                     $full_date = $upload_date;
                 }

                $filename = isset($result['file']) ? $result['file'] : '';
                
                $filepath   = base_url().'assets/uploads/company/';
                $pdfimg     = base_url().'assets/images/pdf.png';
                $file       = '';
                $download   = '';
                
                if($filename!=''){
                    $explodefile    = explode('.', $filename);
                    $extfile        = array_pop($explodefile);
                    $imgpath        = (in_array($extfile, ['pdf', 'tiff'])) ? $pdfimg : $filepath.$filename;
                    $file           = '<div class="col-md-6"><a href="' .$imgpath.'" target="_blank"><img src="'.$imgpath.'" width="100"></div></a>';

                    $download       = '<a href="' .base_url().'assets/uploads/company/'.$result['file'].'" download><i class="fa fa-download" style="color:blue;"></i></a>';
                }
                
                $action = '<div class="table-action"><a href="' . base_url() . 'admin/company/index/documents/'.$result['user_id'].'/' . $result['id'] . '" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil-alt"></i></a><a href="'.base_url().'admin/company/index/Deletefunc/'.$result['user_id'].'/' . $result['id'] .'" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash" style="color:red;"></i></a>'.$download.'</div>';

                $totalrecord[] =    [   
                                        'description'=>     $result['description'], 
                                        'datetime'   =>     $full_date,
                                        'file'       =>     $file,
                                        'action'     =>     $action,
                                        
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


    public function Deletefunc($compId,$documentsid='')
    {       
        
        $result = $this->Documentsletters_Model->deleteid_comp($documentsid);
        if($result == '1'){
            // $url = FCPATH."assets/uploads/plumber/".$documentsid.".pdf";
            // unlink($url);
            $this->session->set_flashdata('success', 'Record was Deleted');
        }
        else{
            $this->session->set_flashdata('error', 'Error to delete the Record.');      
        }

        $this->index();
        redirect('admin/company/index/documents/'.$compId);
    }


    public function perfomacerating($compid = '', $id ='')
    {
        $user_id        = $compid;
        $results        = $this->Company_Model->getList('row', ['id' => $user_id], ['users', 'usersdetail', 'userscompany']);
        $companystatus1 = $this->config->item('companystatus');

        $pagedata['companystatus'] = $companystatus1[$results['companystatus']];

        if ($id != '') {
            $result = $this->Companyperformancedetails_Model->getList('row', ['user_id' => $user_id, 'id' => $id, 'status' => ['1']]);
            if ($result) {
                $pagedata['result'] = $result;
            } else {
                $this->session->set_flashdata('error', 'No Record Found.');
                redirect('admin/company/index/perfomacerating/'.$user_id.'');
            }
        }

        if ($this->input->post()) {

            $requestData = $this->input->post();
            
            if (isset($requestData['submit']) && $requestData['submit'] == 'submit') {

                if ($requestData['id'] =='') {
                    $points = $this->getPoints(['id' => $requestData['document_type']]);
                    $requestData['points'] = $points['points'];
                }
                

                $data = $this->Companyperformancedetails_Model->action($requestData);
                if ($data) {
                    $message = 'Performance Details ' . (($id == '') ? 'created' : 'updated') . ' successfully.';
                }

            } else {
                $data    = $this->Companyperformancedetails_Model->changestatus($requestData);
                $message = 'Performance Details deleted successfully.';
            }

            if (isset($data)) {
                $this->session->set_flashdata('success', $message);
            } else {
                $this->session->set_flashdata('error', 'Try Later.');
            }
            redirect('admin/company/index/perfomacerating/'.$user_id.'');
        }

        $totalpoints = $this->Companyperformancedetails_Model->getList('all', ['user_id' => $user_id, 'status' => ['1'], 'date' => date("Y-m-d")]);

        $points = 0;
        foreach ($totalpoints as $totalpoint) {
            $points += $totalpoint['points'];
        }

        $pagedata['totalpoints']  = $points;
        $pagedata['notification'] = $this->getNotification();
        $today = date("Y-m-d");

        $company_performance = $this->config->item('company_performance');

        $document_type_list = array();
        if ($id == '') {
            foreach ($company_performance as $key => $value) {
                $document_types = $this->Companyperformancedetails_Model->GetDate_of_Renewal(['user_id' => $user_id, 'status' => ['1'], 'date_of_renewal' => $today, 'document_type' => $key]);

                if (!$document_types) {
                    $document_type_list[$key] = $value;
                }
            }
        } else {
            $document_type_list = $company_performance;
        }

        $pagedata['document_type_list'] = $document_type_list;
        $pagedata['userid']             = $user_id;

        $data['plugins']            = ['datatables', 'datatablesresponsive', 'sweetalert', 'validation', 'datepicker'];
        $pagedata['menu']           = $this->load->view('common/company/menu', ['id'=>$user_id],true);
        $data['content']            = $this->load->view('common/company/performancedetails/index', (isset($pagedata) ? $pagedata : ''), true);
        $this->layout2($data);
    }

    public function getPoints($data = []){
        $data = $this->Companyperformance_Model->getList('row', ['id'=> $data['id']]);
        return $data;
    }

    public function DTCompanyperformancedetails()
    {
        $post           = $this->input->post();
        $user_id        = $post['compid'];
        $statusresults  = $this->Company_Model->getList('row', ['id' => $user_id], ['users', 'usersdetail', 'userscompany']);
        $companystatus1 = $this->config->item('companystatus');

        $companystatus = $companystatus1[$statusresults['companystatus']];

        
        $totalcount = $this->Companyperformancedetails_Model->getList('count', ['user_id' => $user_id, 'status' => ['1']] + $post);
        $results    = $this->Companyperformancedetails_Model->getList('all', ['user_id' => $user_id, 'status' => ['1']] + $post);

        $totalrecord = [];
        if (count($results) > 0) {
            foreach ($results as $result) {
                $profileimg  = base_url() . 'assets/images/profile.jpg';
                $pdfimg      = base_url() . 'assets/images/pdf.png';
                $attachments = isset($result['attachments']) ? $result['attachments'] : '';
                $filepath    = base_url() . 'assets/uploads/company/documents/' . $user_id . '/';
                $filepath1   = (isset($result['attachments']) && $result['attachments'] != '') ? $filepath . $result['attachments'] : base_url() . 'assets/uploads/cpdqueue/profile.jpg';
                if ($attachments != '') {
                    $explodefile2 = explode('.', $attachments);
                    $extfile2     = array_pop($explodefile2);
                    $photoidimg   = (in_array($extfile2, ['pdf', 'tiff'])) ? $pdfimg : $filepath1;
                    $photoidurl   = $filepath1;
                } else {
                    $photoidimg = $profileimg;
                    $photoidurl = 'javascript:void(0);';
                }

                $files = '<a href="' . $photoidurl . '" target="_blank"><img src="' . $photoidimg . '" width="80"></a>';

                $points = $this->Companyperformancedetails_Model->getList('row', ['user_id' => $user_id, 'document_type' => $result['document_type']]);
                $points = $points['points'];

                /*$action = '<div class="table-action">
                                <a href="' . base_url() . 'admin/company/index/perfomacerating/'.$user_id.'/'.$result['id'].'" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil-alt"></i></a>';
                if ($companystatus == 'Active') {
                    $action .= '   <a href="javascript:void(0);" data-id="' . $result['id'] . '" class="delete" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                </div>';
                }*/
                if ($result['date_of_renewal'] >= date("Y-m-d")) {
                    $action = '<div class="table-action">
                                <a href="' . base_url() . 'admin/company/index/perfomacerating/'.$user_id.'/'.$result['id'].'" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil-alt"></i></a>';
                    if ($companystatus == 'Active') {
                        $action .= '   <a href="javascript:void(0);" data-id="' . $result['id'] . '" class="delete" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                </div>';
                    }
                } else {
                    if ($companystatus == 'Active') {
                        $action = '   <a href="javascript:void(0);" data-id="' . $result['id'] . '" class="delete" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                </div>';
                    }
                }

                $totalrecord[] = [
                    'updated_at'      => date('d-m-Y H:i:s', strtotime($result['updated_at'])),
                    'date_of_renewal' => date('d-m-Y', strtotime($result['date_of_renewal'])),
                    'document_type'   => $this->config->item('company_performance')[$result['document_type']],
                    'points'          => $result['points'],
                    'attachments'     => $files,
                    'action'          => $action,
                ];

            }
        }

        $json = array(
            "draw"            => intval($post['draw']),
            "recordsTotal"    => intval($totalcount),
            "recordsFiltered" => intval($totalcount),
            "data"            => $totalrecord,
        );

        echo json_encode($json);
    }

    public function deleteDoc(){

        $post       = $this->input->post();
        $data       = $this->Companyperformancedetails_Model->changestatus($post);
        $message    = 'Performance Details deleted successfully.';
        if (isset($data)) {
            $this->session->set_flashdata('success', $message);
        } else {
            $this->session->set_flashdata('error', 'Try Later.');
        }
        redirect('admin/company/index/perfomacerating/'.$post['userid'].'');
    }

    // COC Statement
    public function cocstatement($compId)
    {
        $pagedata['usersid'] = $this->getUserID();

        $coc_purchase               = $this->Coc_Model->COCcount(['user_id' => $compId]);
        $pagedata['userorderstock'] = $this->Coc_Model->getCOCList('count', ['allocated_id' => $compId]);
        $pagedata['coc_purchase']   = isset($coc_purchase['count']) ? $coc_purchase['count'] : '0';

        $userdata1                = $this->Company_Model->getList('row', ['id' => $compId], ['users', 'usersdetail']);
        $pagedata['user_details'] = $userdata1;
        $pagedata['roletype']     = $this->config->item('roleadmin');
        $pagedata['notification'] = $this->getNotification();
        $pagedata['companyid']    = $compId;
        $pagedata['menu']         = $this->load->view('common/company/menu', ['id' => $compId], true);
        $data['plugins']          = ['datatables', 'datatablesresponsive'];
        $data['content']          = $this->load->view('admin/company/cocstatement', (isset($pagedata) ? $pagedata : ''), true);
        $this->layout2($data);
    }

    public function ajaxdtcompany()
    {
        $post       = $this->input->post();
        $totalcount = $this->Company_Model->getstockList('count', $post);
        $results    = $this->Company_Model->getstockList('all', $post);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // exit();
        $totalrecord = [];
        if (count($results) > 0) {
            foreach ($results as $result) {
                if ($result['allocatedby'] > 0) {
                    $name      = $result['name'] . " " . $result['surname'];
                    $timestamp = strtotime($result['allocation_date']);
                    $newDate   = date('d-F-Y H:i:s', $timestamp);
                } else {
                    $name    = "";
                    $newDate = "";
                }

                if ($result['coc_status'] == '8') {
                    $cocstatus = "In Stock";
                } else if ($result['coc_status'] == '2') {
                    $cocstatus = "Logged";
                } else if ($result['coc_status'] == '4') {
                    $cocstatus = "Non Logged";
                } else if ($result['coc_status'] == '9') {
                    $cocstatus = "Allocated (Company)";
                }

                if ($result['cl_address'] != '') {
                    $address = $result['cl_address'];
                } else {
                    $address = $result['cl_street'] . '<br>' . $result['cl_suburb_name'] . '<br>' . $result['cl_city_name'] . '<br>' . $result['cl_province_name'];
                }

                if ($result['coc_status'] == '2') {
                    $action = '<a href="' . base_url() . 'admin/company/index/view/' . $result['id'] . '/' . $result['user_id'] . '" data-toggle="tooltip" data-placement="top" title="View"><i class="fa fa-eye"></i></a>';
                } else {
                    $action = '';
                }

                $stockcount    = 0;
                $totalrecord[] = [
                    'cocno'    => $result['id'],
                    'status'   => $cocstatus,
                    'datetime' => $newDate,
                    'name'     => $name,
                    'customer' => $result['cl_name'],
                    'address'  => $address,
                    'action'   => $action,
                ];
            }
        }

        $json = array(
            "recordsTotal"    => intval($totalcount),
            "recordsFiltered" => intval($totalcount),
            "data"            => $totalrecord,
        );

        echo json_encode($json);
    }

    public function view($id, $user_id)
    {
        $this->coclogaction(
            $id,
            ['pagetype' => 'view', 'roletype' => $this->config->item('roleplumber'), 'electroniccocreport' => 'admin/company/index/electroniccocreport/' . $id . '/' . $user_id, 'noncompliancereport' => 'admin/company/index/noncompliancereport/' . $id . '/' . $user_id],
            ['redirect' => 'admin/company/index/cocstatement', 'userid' => $user_id]
        );
    }

    public function electroniccocreport($id, $user_id)
    {
        $this->pdfelectroniccocreport($id, $user_id);
    }

    public function noncompliancereport($id, $user_id)
    {
        $this->pdfnoncompliancereport($id, $user_id);
    }

    // Audit Statement
    public function audit($compId)
    {
        $pagedata['usersid']      = $this->getUserID();
        $userdata1                = $this->Company_Model->getList('row', ['id' => $compId], ['users', 'usersdetail']);
        $pagedata['user_details'] = $userdata1;
        $pagedata['roletype']     = $this->config->item('roleadmin');
        $pagedata['notification'] = $this->getNotification();
        $pagedata['companyid']    = $compId;
        $pagedata['menu']         = $this->load->view('common/company/menu', ['id' => $compId], true);
        $data['plugins']          = ['datatables', 'datatablesresponsive'];
        $data['content']          = $this->load->view('admin/company/audit', (isset($pagedata) ? $pagedata : ''), true);
        $this->layout2($data);
    }

    public function DTaudit()
    {
        $userid = $this->input->post('companyid');

        $post       = $this->input->post();
        $totalcount = $this->Coc_Model->getCOCList('count', ['coc_status' => ['2'], 'allocated_id' => $userid, 'noaudit' => ''] + $post, ['coclog', 'auditordetails', 'auditorstatement']);
        $results    = $this->Coc_Model->getCOCList('all', ['coc_status' => ['2'], 'allocated_id' => $userid, 'noaudit' => ''] + $post, ['coclog', 'auditordetails', 'auditorstatement']);

        // echo $this->db->last_query(); exit();

        $time = strtotime("-1 year", time());
        $date = date("Y-m-d", $time);

        $totalrecord = [];
        if (count($results) > 0) {
            foreach ($results as $result) {
                $auditstatus = isset($this->config->item('auditstatus')[$result['audit_status']]) ? $this->config->item('auditstatus')[$result['audit_status']] : '';
                $action      = '<a href="' . base_url() . 'admin/company/index/viewaudit/' . $result['id'] . '/' . $result['user_id'] . '" data-toggle="tooltip" data-placement="top" title="View"><i class="fa fa-eye"></i></a>';

                $refixdate = ($result['ar1_refix_date'] != '') ? '<p class="' . (($date > date('Y-m-d', strtotime($result['ar1_refix_date']))) && $result['as_refixcompletedate'] == '' ? "tagline" : "") . '">' . date('d-m-Y', strtotime($result['ar1_refix_date'])) . '</p>' : '';

                $totalrecord[] = [
                    'cocno'     => $result['id'],
                    'status'    => $auditstatus,
                    'consumer'  => $result['cl_name'],
                    'address'   => $result['cl_address'],
                    'refixdate' => $refixdate,
                    'auditor'   => $result['auditorname'],
                    'action'    => '<div class="table-action">' . $action . '</div>',
                ];
            }
        }

        $json = array(
            "draw"            => intval($post['draw']),
            "recordsTotal"    => intval($totalcount),
            "recordsFiltered" => intval($totalcount),
            "data"            => $totalrecord,
        );

        echo json_encode($json);
    }

    public function viewaudit($id, $user_id)
    {
        $this->getauditreview($id, ['pagetype' => 'view', 'viewcoc' => 'admin/company/index/viewcoc', 'downloadattachment' => 'admin/company/index/downloadattachment', 'seperatechat' => 'admin/company/index/seperatechat/' . $id . '/view', 'auditreport' => 'admin/company/index/auditreport/' . $id, 'roletype' => $this->config->item('roleplumber')], ['redirect' => 'admin/company/index', 'plumberid' => $user_id, 'notification' => '1']);
    }

    public function viewcoc($id, $plumberid)
    {
        $this->coclogaction(
            $id,
            ['pagetype' => 'view', 'roletype' => $this->config->item('roleplumber'), 'electroniccocreport' => 'admin/company/index/auditelectroniccocreport/' . $id . '/' . $plumberid, 'noncompliancereport' => 'admin/company/index/auditnoncompliancereport/' . $id . '/' . $plumberid],
            ['redirect' => 'admin/company/index', 'userid' => $plumberid]
        );
    }

    public function seperatechat($id, $pagetype)
    {
        $this->getchat($id, ['roletype' => $this->config->item('roleplumber'), 'pagetype' => $pagetype], ['redirect' => 'admin/company/index']);
    }

    public function auditreport($id)
    {
        $this->pdfauditreport($id);
    }

    public function auditelectroniccocreport($id, $userid)
    {
        $this->pdfelectroniccocreport($id, $userid);
    }

    public function auditnoncompliancereport($id, $userid)
    {
        $this->pdfnoncompliancereport($id, $userid);
    }

    public function downloadattachment($cocid, $file)
    {
        $file = './assets/uploads/chat/' . $cocid . '/' . $file;
        $this->downloadfile($file);
    }

}
