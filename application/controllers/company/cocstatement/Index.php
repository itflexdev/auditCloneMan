<?php
//Resellers Controllers
defined('BASEPATH') or exit('No direct script access allowed');

class Index extends CC_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Company_Model');
        $this->load->model('Coc_Model');
    }

    public function index()
    {
        $user_id                  = $this->getUserID();
        $pagedata['notification'] = $this->getNotification();

        $pagedata['coc_not_assigned'] = $this->getNotification();
        $coc_purchase                 = $this->Coc_Model->COCcount(['user_id' => $user_id]);
        $pagedata['userorderstock']   = $this->Coc_Model->getCOCList('count', ['allocated_id' => $user_id]);
        $pagedata['coc_purchase']     = isset($coc_purchase['count']) ? $coc_purchase['count'] : '0';

        $pagedata['result']  = $this->Company_Model->getList('row', ['id' => $user_id], ['users', 'usersdetail', 'userscompany']);
        $pagedata['usersid'] = $user_id;

        $data['plugins'] = ['datatables', 'datatablesresponsive', 'sweetalert', 'datepicker', 'inputmask'];
        $data['content'] = $this->load->view('company/cocstatement/index', (isset($pagedata) ? $pagedata : ''), true);

        $this->layout2($data);
    }

    public function ajaxdtcompany()
    {
        $post       = $this->input->post();
        $totalcount = $this->Company_Model->getstockList('count', $post);
        $results    = $this->Company_Model->getstockList('all', $post);

        // echo "<pre>";
        // print_r($results);
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
                }

                if ($result['cl_address'] != '') {
                    $address = $result['cl_address'];
                } else {
                    $address = $result['cl_street'] . '<br>' . $result['cl_suburb_name'] . '<br>' . $result['cl_city_name'] . '<br>' . $result['cl_province_name'];
                }

                if ($result['coc_status'] == '2') {
                    $action = '<a href="' . base_url() . 'company/cocstatement/index/view/' . $result['id'] . '/' . $result['user_id'] . '" data-toggle="tooltip" data-placement="top" title="View"><i class="fa fa-eye"></i></a>';
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
            ['pagetype' => 'view', 'roletype' => $this->config->item('roleplumber'), 'electroniccocreport' => 'company/cocstatement/index/electroniccocreport/' . $id . '/' . $user_id, 'noncompliancereport' => 'company/cocstatement/index/noncompliancereport/' . $id . '/' . $user_id],
            ['redirect' => 'company/cocstatement/index', 'userid' => $user_id]
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
}
