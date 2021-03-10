<?php
//Company Controllers
defined('BASEPATH') or exit('No direct script access allowed');

class Index extends CC_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Plumber_Model');
        $this->load->model('Coc_Model');
        $this->load->model('Company_Model');
        $this->load->model('Stock_Model');
        $this->load->model('Company_allocatecoc_Model');
    }

    public function index($userID = '')
    {
        if ($userID != '') {
            $requestData['id']  = $userID;
            $pagedata['result'] = $this->Plumber_Model->getList('row', $requestData, ['users', 'usersdetail', 'usersplumber', 'company']);
        } else {
            redirect('company/employee_listing');
        }

        $resultid['user_id']  = $pagedata['result']['id'];
        $pagedata['cocstock'] = $this->Coc_Model->getCOCList('count', ['allocated_id' => $this->getUserID()]);

        $pagedata['array_orderqty'] = $this->Company_allocatecoc_Model->getqty('row', $resultid);

        $pagedata['card'] = $this->plumbercard($resultid['user_id']);

        if ($this->input->post()) {
            $requestData = $this->input->post();
            // echo "<pre>";
            // print_r($requestData);
            // exit();

            if (isset($requestData['plumberid']) > 0) {
                $plumberid = $requestData['plumberid'];
                $data      = $this->Company_allocatecoc_Model->action($requestData);

                if ($data) {
                    // $message = 'Plumber Allocated Coc' . (($plumberid == '') ? 'created' : 'updated') . ' successfully.';
                    $message = 'COC allocated to plumber successfully.';
                    $this->session->set_flashdata('success', $message);
                } else {
                    $this->session->set_flashdata('error', 'Try Later.');
                }

                redirect('company/employee_listing');
            }
        }

        $pagedata['notification']           = $this->getNotification();
        $pagedata['company']                = $this->getCompanyList();
        $pagedata['designation2']           = $this->config->item('designation2');
        $pagedata['specialisations']        = $this->config->item('specialisations');
        $pagedata['userid']                 = $this->getUserID();
        $pagedata['companydetails']         = $this->getUserDetails();

        // echo "<pre>";
        // print_r($pagedata);
        // exit();

        $data['plugins'] = ['datatables', 'datatablesresponsive', 'sweetalert', 'datepicker', 'inputmask', 'validation'];
        $data['content'] = $this->load->view('company/allocatecoc/index', (isset($pagedata) ? $pagedata : ''), true);

        $this->layout2($data);
    }

}
