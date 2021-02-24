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
	}
	
	public function index($documentsid ='')
	{
        $id = $this->getUserID();
		if($documentsid!=''){
            $result = $this->Documentsletters_Model->getcompanyList('row', ['id' => $documentsid]);
            if($result){
                $pagedata['result'] = $result;              

            }else{
                $this->session->set_flashdata('error', 'No Record Found.');
                if($extras['redirect']) redirect($extras['redirect']); 
                else redirect('company/profile/index'); 
            }
        }
        
        if($this->input->post()){
            $requestData    =   $this->input->post();           
            $result     =  $this->Documentsletters_Model->action2($requestData);             
            if($result){
             $this->session->set_flashdata('success', 'Documents Letters '.(($result==1) ? 'created' : 'updated').' successfully.');

             redirect('company/documents/index');
            }
            else{
             $this->session->set_flashdata('error', 'Try Later.');
            }

        }


        $userdata1                  = $this->Company_Model->getList('row', ['id' => $id], ['users', 'usersdetail']);
        $pagedata['user_details']   = $userdata1;
        $pagedata['roletype']       = $this->config->item('roleadmin');
        $pagedata['notification']   = $this->getNotification();
        $pagedata['companyid']      = $id;
        $data['plugins']            = ['datatables', 'datatablesresponsive', 'sweetalert', 'validation','inputmask'];
        $data['content']            = $this->load->view('company/documents', (isset($pagedata) ? $pagedata : ''), true);
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
                $upload_date = date('d-F-Y H:i:s', $created_at);
                if ($result['updated_at']!='') {
                    $updated_at = strtotime($result['updated_at']);
                    $update_date = date('d-F-Y H:i:s', $updated_at);

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
                
                $action = '<div class="table-action"><a href="' . base_url() . 'company/documents/index/index/'.$result['id'].'" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil-alt"></i></a><a href="'.base_url().'company/documents/index/Deletefunc/'.$result['user_id'].'/' . $result['id'] .'" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash" style="color:red;"></i></a>'.$download.'</div>';

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
            $this->session->set_flashdata('success', 'Documents deleted successfully.');
        }
        else{
            $this->session->set_flashdata('error', 'Error to delete the Documents.');      
        }

        redirect('company/documents/index');
    }
}
