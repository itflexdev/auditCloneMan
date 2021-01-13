<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CC_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Documentsletters_Model');
	}
	
	public function index()
	{
		$id = $this->getUserID();
		$this->companyprofile($id, ['roletype' => $this->config->item('rolecompany'), 'pagetype' => 'companyprofile'], ['redirect' => 'company/profile/index']);
	}

    public function Deletefunc()
    {       
        $post = $this->input->post();
        $result = $this->Documentsletters_Model->deleteid_comp($post['id']);
       	echo "1";
    }

    public function actionDocuments(){
    	$post = $this->input->post();
    	$data = $this->Documentsletters_Model->action2($post);

    	$documentlist			= $this->Documentsletters_Model->getcompanyList('row', ['id' => $data]);

    	if($documentlist){
			$json = ['status' => '1', 'result' => $documentlist];
		}else{
			$json = ['status' => '0'];
		}

    	echo json_encode($json);

    }
	
}
