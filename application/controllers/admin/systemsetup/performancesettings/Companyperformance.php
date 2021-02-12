<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Companyperformance extends CC_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Companyperformance_Model');

        //$this->checkUserPermission('14', '1');
    }

    public function index()
    {
        $result = $this->Companyperformance_Model->getList('all');
        if ($result) {
            $pagedata['result'] = $result;
        } else {
            $this->session->set_flashdata('error', 'No Record Found.');
            redirect('admin/systemsetup/performancesettings/companyperformance');
        }

        if ($this->input->post()) {
            //$this->checkUserPermission('14', '2', '1');

            $requestData = $this->input->post();

            // print_r($requestData);
            // exit();

            $data = $this->Companyperformance_Model->action($requestData);

            if ($data) {
                $this->session->set_flashdata('success', 'Company Performance Types ' . (($id == '') ? 'updated' : 'updated') . ' successfully.');
            } else {
                $this->session->set_flashdata('error', 'Try Later.');
            }

            redirect('admin/systemsetup/performancesettings/companyperformance');
        }

        //$pagedata['checkpermission'] = $this->checkUserPermission('14', '2');
        $pagedata['notification']    = $this->getNotification();
        $data['plugins']             = ['datatables', 'datatablesresponsive', 'sweetalert', 'validation', 'datepicker'];
        $data['content']             = $this->load->view('admin/systemsetup/performancesettings/companyperformance', (isset($pagedata) ? $pagedata : ''), true);
        $this->layout2($data);
    }

}
