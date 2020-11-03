<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class index extends CC_Controller 
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('Plumber_Model');
		
	}
	
	public function index($id='')
	{
		$userid 					= $this->getUserID();
		$userdetails 				= $this->getUserDetails();
		$pagedata['notification'] 	= $this->getNotification();		
		$pagedata['result'] 		= $this->Plumber_Model->getList('row', ['id' => $userid, 'type' => '3', 'status' => ['1', '2']], ['usersdetail', 'usersplumber']);
		$pagedata['userdetails'] 	= $userdetails;
		$data['plugins']			= ['datatables', 'datatablesresponsive', 'sweetalert', 'validation'];
		$data['content'] 			= $this->load->view('plumber/lms/index', (isset($pagedata) ? $pagedata : ''), true);
		$this->layout2($data);
		
	}

	public function lmsaction(){
		$post = $this->input->post();
		echo "<pre>";print_r($post);
		$request['lms_status'] = $post['lms_status'];
		$this->db->update('users_plumber', $request, ['user_id' => $post['uid']]);
		echo '1';
	}

}
