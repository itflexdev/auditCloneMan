<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Index extends CC_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Companyperformancedetails_Model');
        $this->load->model('CC_Model');
        $this->load->model('Plumber_Model');

        //$this->checkUserPermission('16', '1');
    }

    public function index($id = '')
    {
        $user_id = $this->getUserID();
        if ($id != '') {
            //$this->checkUserPermission('16', '2', '1');

            $result = $this->Companyperformancedetails_Model->getList('row', ['user_id' => $user_id, 'id' => $id, 'status' => ['1']]);
            if ($result) {
                $pagedata['result'] = $result;
            } else {
                $this->session->set_flashdata('error', 'No Record Found.');
                redirect('company/performancedetails/index');
            }
        }

        if ($this->input->post()) {
            //$this->checkUserPermission('16', '2', '1');

            $requestData = $this->input->post();

            if ($requestData['submit'] == 'submit') {
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

            redirect('company/performancedetails/index');
        }

        $totalpoints = $this->Companyperformancedetails_Model->getList('all', ['user_id' => $user_id, 'status' => ['1'], 'date' => date("Y-m-d")]);

        $points = 0;
        foreach ($totalpoints as $totalpoint) {
            $points += $totalpoint['points'];
        }

        $pagedata['totalpoints']  = $points;
        $pagedata['notification'] = $this->getNotification();
        //$pagedata['checkpermission'] = $this->checkUserPermission('16', '2');
        $today = date("Y-m-d");

        $company_performance = $this->config->item('company_performance');

        $document_type_list = array();
        foreach ($company_performance as $key => $value) {
            $document_types = $this->Companyperformancedetails_Model->GetDate_of_Renewal(['user_id' => $user_id, 'status' => ['1'], 'date_of_renewal' => $today, 'document_type' => $key]);
            if (!$document_types) {
                $document_type_list[$key] = $value;
            }
        }
        $pagedata['document_type_list'] = $document_type_list;
        $pagedata['userid']             = $this->getUserID();
        $data['plugins']                = ['datatables', 'datatablesresponsive', 'sweetalert', 'validation', 'datepicker'];
        $data['content']                = $this->load->view('company/performancedetails/index', (isset($pagedata) ? $pagedata : ''), true);
        $this->layout2($data);
    }

    public function DTCompanyperformancedetails()
    {
        $userid     = $this->getUserID();
        $post       = $this->input->post();
        $totalcount = $this->Companyperformancedetails_Model->getList('count', ['user_id' => $userid, 'status' => ['1']] + $post);
        $results    = $this->Companyperformancedetails_Model->getList('all', ['user_id' => $userid, 'status' => ['1']] + $post);

        //$checkpermission = $this->checkUserPermission('16', '2');

        $totalrecord = [];
        if (count($results) > 0) {
            foreach ($results as $result) {
                $profileimg  = base_url() . 'assets/images/profile.jpg';
                $pdfimg      = base_url() . 'assets/images/pdf.png';
                $attachments = isset($result['attachments']) ? $result['attachments'] : '';
                $filepath    = base_url() . 'assets/uploads/company/documents/' . $userid . '/';
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

                $points = $this->Companyperformancedetails_Model->getList('row', ['user_id' => $userid, 'document_type' => $result['document_type']]);
                $points = $points['points'];

                // if ($checkpermission) {
                $action = '<div class="table-action">
                                <a href="' . base_url() . 'company/performancedetails/index/index/' . $result['id'] . '" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil-alt"></i></a>
                                <a href="javascript:void(0);" data-id="' . $result['id'] . '" class="delete" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                </div>';
                // } else {
                // $action = '';
                // }

                $totalrecord[] = [
                    'updated_at'      => date('d-m-Y H:i:s', strtotime($result['updated_at'])),
                    'date_of_renewal' => date('d-m-Y', strtotime($result['date_of_renewal'])),
                    'document_type'   => $this->config->item('company_performance')[$result['document_type']],
                    'points'          => $points,
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

}
