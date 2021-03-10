<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Index extends CC_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Coc_Model');
        $this->load->model('Rates_Model');
        $this->load->model('Systemsettings_Model');
        $this->load->model('CC_Model');
        $this->load->model('Company_Model');
    }

    public function index()
    {
        $userid            = $this->getUserID();
        $userdata          = $this->getUserDetails();
        $userdata1         = $this->Company_Model->getList('row', ['id' => $userid], ['users', 'usersdetail', 'userscompany']);
        $userdatacoc_count = $this->Coc_Model->COCcount(['user_id' => $userid]);

        $pagedata['notification']    = $this->getNotification();
        $pagedata['province']        = $this->getProvinceList();
        $pagedata['userid']          = $userid;
        $pagedata['userdata']        = $userdata;
        $pagedata['userdata1']       = $userdata1;
        $pagedata['username']        = $userdata1;
        $pagedata['coc_count']       = $userdatacoc_count;
        $pagedata['deliverycard']    = $this->config->item('purchasecocdelivery');
        $pagedata['coctype']         = $this->config->item('coctype');
        $pagedata['settings']        = $this->Systemsettings_Model->getList('row');
        $pagedata['logcoc']          = $this->Coc_Model->getCOCList('count', ['user_id' => $userid, 'coc_status' => ['4', '5']]);
        $pagedata['cocpaperwork']    = $this->Rates_Model->getList('row', ['id' => $this->config->item('cocpaperwork')]);
        $pagedata['cocelectronic']   = $this->Rates_Model->getList('row', ['id' => $this->config->item('cocelectronic')]);
        $pagedata['postage']         = $this->Rates_Model->getList('row', ['id' => $this->config->item('postage')]);
        $pagedata['couriour']        = $this->Rates_Model->getList('row', ['id' => $this->config->item('couriour')]);
        $pagedata['collectedbypirb'] = $this->Rates_Model->getList('row', ['id' => $this->config->item('collectedbypirb')]);
        $orderquantity               = $this->Coc_Ordermodel->getCocorderList('all', ['admin_status' => '0', 'userid' => $userid]);
        $pagedata['userorderstock']  = $this->Coc_Model->getCOCList('count', ['allocated_id' => $userid]);

        $data['plugins'] = ['validation', 'datepicker'];
        //$pagedata['result']         = $this->Coc_Model->getList('row', ['id' => $userid, 'status' => ['0','1']]);
        $pagedata['customview'] = $this->load->view('common/custom', '', true);
        $data['content']        = $this->load->view('company/purchasecoc/index', (isset($pagedata) ? $pagedata : ''), true);

        $this->layout2($data);
    }

    public function insertOrders()
    {
        if ($this->input->post()) {
            $this->session->set_userdata('pay_purchaseorder', $this->input->post());
            echo '1';
        }
    }

    public function genreateOrderID()
    {
        $result = $this->db->order_by('id', "desc")->get('coc_orders')->row_array();
        if ($result) {
            $sequence_number = $result['order_id'];
            $product_code    = $sequence_number + 1;
            $code            = str_pad($product_code, 6, '0', STR_PAD_LEFT);
            $full_code       = $code;
            return $full_code;
        } else {
            $oderID = '000001';
            return $oderID;
        }
    }

    public function genreateInvID()
    {
        $result = $this->db->order_by('id', "desc")->get('coc_orders')->row_array();
        if ($result) {
            $sequence_number = $result['inv_id'];
            $product_code    = $sequence_number[1] + 1;
            $code            = str_pad($product_code, 6, '0', STR_PAD_LEFT);
            $full_code       = $code;
            return $full_code;
        } else {
            $invID = '000001';
            return $invID;
        }
    }
    
    public function paymentsuccess(){
        $this->session->set_flashdata('success','COC Purchase Sucessfully.');
        redirect('company/purchasecoc/index');
    }
    
    public function paymentnotify(){
        header( 'HTTP/1.0 200 OK' );
        flush();
        
        $result = $_POST;
        $this->Coc_Model->purchasecocCompany($result);
    }

    public function paymentcancel(){
        echo "Your Payment Is Cancelled.";
        redirect('company/purchasecoc/index');
    }
}
