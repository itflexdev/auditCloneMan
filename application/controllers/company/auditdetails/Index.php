<?php
//Resellers Controllers
defined('BASEPATH') or exit('No direct script access allowed');

class Index extends CC_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Plumber_Model');
        $this->load->model('Company_allocatecoc_Model');
    }

    public function index()
    {
        $pagedata['usersid']      = $this->getUserID();
        $pagedata['notification'] = $this->getNotification();
        $data['plugins']          = ['datatables', 'datatablesresponsive', 'sweetalert', 'datepicker', 'inputmask'];
        $data['content']          = $this->load->view('company/auditdetails/index', (isset($pagedata) ? $pagedata : ''), true);

        $this->layout2($data);
    }

    public function DTAuditStatement()
    {
        $userid     = $this->getUserID();
        $post       = $this->input->post();
        $totalcount = $this->Coc_Model->getCOCList('count', ['coc_status' => ['2'], 'allocated_id' => $userid, 'noaudit' => ''] + $post, ['coclog', 'auditordetails', 'auditorstatement']);
        $results    = $this->Coc_Model->getCOCList('all', ['coc_status' => ['2'], 'allocated_id' => $userid, 'noaudit' => ''] + $post, ['coclog', 'auditordetails', 'auditorstatement']);

        // echo $this->db->last_query(); exit();
        
        $totalrecord = [];
        if (count($results) > 0) {
            foreach ($results as $result) {
                $auditstatus = isset($this->config->item('auditstatus')[$result['audit_status']]) ? $this->config->item('auditstatus')[$result['audit_status']] : '';
                $action      = '<a href="' . base_url() . 'company/auditdetails/index/view/' . $result['id'] . '/' . $result['user_id'] . '" data-toggle="tooltip" data-placement="top" title="View"><i class="fa fa-eye"></i></a>';

                $refixdate = ($result['ar1_refix_date'] != '') ? '<p class="' . ((date('Y-m-d') > date('Y-m-d', strtotime($result['ar1_refix_date']))) && $result['as_refixcompletedate'] == '' ? "tagline" : "") . '">' . date('d-m-Y', strtotime($result['ar1_refix_date'])) . '</p>' : '';

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

    public function view($id, $user_id)
    {
        $this->getauditreview($id, ['pagetype' => 'view', 'viewcoc' => 'company/auditdetails/index/viewcoc', 'downloadattachment' => 'company/auditdetails/index/downloadattachment', 'seperatechat' => 'company/auditdetails/index/seperatechat/' . $id . '/view', 'auditreport' => 'company/auditdetails/index/auditreport/' . $id, 'roletype' => $this->config->item('roleplumber')], ['redirect' => 'company/auditdetails/index', 'plumberid' => $user_id, 'notification' => '1']);
    }

    public function viewcoc($id, $plumberid)
    {
        $this->coclogaction(
            $id,
            ['pagetype' => 'view', 'roletype' => $this->config->item('roleplumber'), 'electroniccocreport' => 'company/auditdetails/index/electroniccocreport/' . $id . '/' . $plumberid, 'noncompliancereport' => 'company/auditdetails/index/noncompliancereport/' . $id . '/' . $plumberid],
            ['redirect' => 'company/auditdetails/index', 'userid' => $plumberid]
        );
    }

    public function seperatechat($id, $pagetype)
    {
        $this->getchat($id, ['roletype' => $this->config->item('roleplumber'), 'pagetype' => $pagetype], ['redirect' => 'company/auditdetails/index']);
    }

    public function auditreport($id)
    {
        $this->pdfauditreport($id);
    }

    public function electroniccocreport($id, $userid)
    {
        $this->pdfelectroniccocreport($id, $userid);
    }

    public function noncompliancereport($id, $userid)
    {
        $this->pdfnoncompliancereport($id, $userid);
    }

    public function downloadattachment($cocid, $file)
    {
        $file = './assets/uploads/chat/' . $cocid . '/' . $file;
        $this->downloadfile($file);
    }
}
