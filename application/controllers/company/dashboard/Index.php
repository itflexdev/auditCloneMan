<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CC_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Company_Model');
        $this->load->model('Company_allocatecoc_Model');
        $this->load->model('Companyperformancedetails_Model');
        $this->load->model('Companyperformance_Model');
    }
    
    public function index()
    {
        $userid                     = $this->getUserID();
        $userdata                   = $this->getUserDetails();
        $result                     = $this->Company_Model->getList('row', ['id' => $userid], ['userscompany']);

        $userorderstock             = $this->Coc_Model->getCOCList('count', ['allocated_id' => $userid]);
        $coccount                   = $this->Coc_Model->COCcount(['user_id' => $userid]);
        $cocdata                    = $this->Company_Model->getstockList('all', ['roletype' => '6', 'user_id' => $userid]);

        foreach ($cocdata as $cocdatakey => $cocdatavalue) {
            if ($cocdatavalue['coc_status'] == '4') {
                    $nonloged[] = $cocdatavalue['id'];
                }
        }

         $totalpoints = $this->Companyperformancedetails_Model->getList('all', ['user_id' => $userid, 'status' => ['1'], 'date' => date("Y-m-d")]);

        $points = 0;
        foreach ($totalpoints as $totalpoint) {
            $points += $totalpoint['points'];
        }

        $myperformance                              = $this->myperformanceCalc(['compID' => $userid, 'province' => $userdata['province']]);
        $score                                      = round(($myperformance['companyperformancepoints']+($myperformance['lmperformanceTotal']*$myperformance['adminsettiingslm']))+($myperformance['otherperformanceTotal']*$myperformance['adminsettiingsnlm']));
        

        $pagedata['performancestatus']              = $score;
        $pagedata['nonlogcoc']                      = isset($nonloged) ? count($nonloged) : '0';
        $pagedata['coccount']                       = isset($coccount['count']) ? $coccount['count'] : '0';
        $pagedata['adminstock']                     = isset($userorderstock) ? $userorderstock : '0';
        $pagedata['id']                             = $userid;
        // $pagedata['overallperformancestatus']       = '1';
        // $pagedata['provinceperformancestatus1']     = '1';

        $pagedata['countrytotal']                   = $myperformance['countrytotal'];
        $pagedata['reginoaltotal']                  = $myperformance['reginoaltotal'];
        $pagedata['pirbmsg']                        = $result['message'];

        $pagedata['overallperformancestatuslimit']  = $this->userperformancestatus(['overall' => '1', 'limit' => '3']);
        $pagedata['provinceperformancestatus']      = $this->userperformancestatus(['province' => $userdata['province']]);
        $pagedata['provinceperformancestatuslimit'] = $this->userperformancestatus(['province' => $userdata['province'], 'limit' => '3']);

        $data['plugins']            = ['datatables','validation','datepicker','inputmask','select2', 'echarts'];
        $data['content']            = $this->load->view('company/dashboard/index', (isset($pagedata) ? $pagedata : ''), true);
        $this->layout2($data);
    }


    public function DTemplist()
    {
        $post           = $this->input->post();
        $userdata       = $this->getUserDetails();
        $totalcount     = $this->Company_Model->getEmpList('count', ['type' => '4', 'approvalstatus' => ['0', '1'], 'formstatus' => ['1'], 'status' => ['0', '1', '2']] + $post);
        $results        = $this->Company_Model->getEmpList('all', ['type' => '4', 'approvalstatus' => ['0', '1'], 'formstatus' => ['1'], 'status' => ['0', '1', '2']] + $post);
       
        $companystatus  = $this->config->item('companystatus');
        $rollingavg                 = $this->getRollingAverage();
        $date                       = date('Y-m-d', strtotime(date('Y-m-d').'+'.$rollingavg.' months'));
        
        $totalrecord = [];
        if (count($results) > 0) {
            foreach ($results as $result) {

                $userdatacoc_count = $this->Coc_Model->COCcount(['user_id' => $result['user_id']]);
                $desigcount     = $this->Company_Model->getdesignationCount(['designation' => $result['designation']]);

                $reginalranking       = $this->userperformancestatus12(['userid' => $result['user_id']]);
                $nationalrangking      = $this->userperformancestatus12(['province' => $userdata['province'], 'userid' => $result['user_id']]);


                $performance = $this->Plumber_Model->performancestatus('all', ['plumberid' => $result['user_id'], 'archive' => '0', 'date' => $date]);

                $array_orderqty = $this->Company_allocatecoc_Model->getqty('row', ['user_id' => $result['user_id']]);
                $balace_coc     = $array_orderqty['sumqty'];

                $per_points = array_sum(array_column($performance, 'point'));

                $points     = $this->Company_Model->cpdPoints($result['user_id']);

                if ($points[0]['cpd']!=''){
                     $points         = round($points[0]['cpd'],2);
                }else{
                    $points         = '0';
                } 
                if( $per_points!=''){
                    $performance    = round($per_points,2);
                }else{
                    
                    $performance    = '0';
                }
                if ($result['designation']=='6' || $result['designation']=='4') {
                   $divclass = 'lm';
                }else{
                    $divclass = 'other';
                }
                // $overall = round((number_format($points+$performance)/$desigcount[0]['desigcount']),1);
                $overall = round((($points+$performance)/($desigcount[0]['desigcount'])),2);
                $companystatus1 = isset($companystatus[$result['status']]) ? $companystatus[$result['status']] : '';
                
                
                $totalrecord[] = [
                    'status'        => $this->config->item('plumberstatus')[$result['status']],
                    'namesurname'   => $result['name'].' '.$result['surname'],
                    // 'rating'        => '<input type="hidden" value="'.$nationalrangking.'" class="'.$divclass.'">'.$nationalrangking.'',
                    // 'rating1'        => '<input type="hidden" value="'.$reginalranking.'" class="'.$divclass.'">'.$reginalranking.'',
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


    public function userperformancestatus12($data = []){  
        $rollingavg     = $this->getRollingAverage();
        $userid         = (isset($data['userid'])) ? $data['userid'] : $this->getUserID();
        $date           = date('Y-m-d', strtotime(date('Y-m-d').'+'.$rollingavg.' months'));
        
        $extradata              = $data;
        $extradata['date']      = $date;
        $extradata['archive']   = '0';
        
        if(count($data)==0){
            $extradata['plumberid'] = $userid;
        }elseif(count($data) > 0 && in_array('performancestatus', array_keys($data))){
            unset($extradata['date']);
            unset($extradata['archive']);
            $extradata['plumberid'] = $userid;
            unset($data);
        }
        
        $results = $this->Plumber_Model->performancestatus('all', $extradata);
         // return $results;
        if(isset($data) && count($data) > 0){
            if(isset($data['limit'])){
                return $results;
            }else{
                $useridsearch = array_search($userid, array_column($results, 'userid'));
                return ($useridsearch !== false) ? $useridsearch+1 : 0;
            }
        }else{
            return count($results) ? array_sum(array_column($results, 'point')) : '0';
        }
    }

    public function myperformanceCalc($data = []){
        

        $results        = $this->Company_Model->getEmpList('all', ['type' => '4', 'approvalstatus' => ['0', '1'], 'formstatus' => ['1'], 'status' => ['0', '1', '2'], 'comp_id' => $data['compID']]);
        $rollingavg             = $this->getRollingAverage();
        $date                   = date('Y-m-d', strtotime(date('Y-m-d').'+'.$rollingavg.' months'));
        $employeeTotalcount     = count($results);

        if (count($results) > 0) {
            foreach ($results as $result) {
                $desigcount                       = $this->Company_Model->getdesignationCount(['designation' => $result['designation']]);
                $overallperformancestatus[]       = $this->userperformancestatus12(['userid' => $result['user_id']]);
                $provinceperformancestatus[]      = $this->userperformancestatus12(['province' => $data['province'], 'userid' => $result['user_id']]);

                $performance = $this->Plumber_Model->performancestatus('all', ['plumberid' => $result['user_id'], 'archive' => '0', 'date' => $date]);
                $per_points[] = array_sum(array_column($performance, 'point'));
                $points     = $this->Company_Model->cpdPoints($result['user_id']);

                $per_points1 = array_sum(array_column($performance, 'point'));
                if ($points[0]['cpd']!=''){
                     $points         = round($points[0]['cpd'],2);
                }else{
                    $points         = '0';
                } 
                if( $per_points1!=''){
                    $performance    = round($per_points1,2);
                }else{
                    
                    $performance    = '0';
                }
                
                // $overall = round((number_format($points+$performance)/$desigcount[0]['desigcount']),1);
                $overall = round((($points+$performance)/($desigcount[0]['desigcount'])),2);

                if ($result['designation']=='6' || $result['designation']=='4') {
                    
                   $lm[]                    = $overall;
                   $lmperformance[]         = $performance;
                }else{
                    $other[]                = $overall;
                    $otherperformance[]     = $performance;
                }

            }
        }

         $totalpoints = $this->Companyperformancedetails_Model->getList('all', ['user_id' => $data['compID'], 'status' => ['1'], 'date' => date("Y-m-d")]);
        $companyperformancepoints = 0;
        foreach ($totalpoints as $totalpoint) {
            $companyperformancepoints += $totalpoint['points'];
        }

        $document_types = $this->Companyperformance_Model->getList('all');

        if (isset($overallperformancestatus)) {
            $countrycumulative = array_sum($overallperformancestatus);
            $countrycount = count($overallperformancestatus);

            $countrytotal = round($countrycumulative/$countrycount);
        }

        if (isset($provinceperformancestatus)) {
            $reginoalcumulative = array_sum($provinceperformancestatus);
            $reginoalcount = count($provinceperformancestatus);

            $reginoaltotal = round($reginoalcumulative/$reginoalcount);
        }

        if (isset($lm)) {
            $lmCount = count($lm);
            $lmTotal = number_format((float)(array_sum($lm)/$lmCount), 2, '.', '');
        }

        if (isset($other)) {
            $otherCount = count($other);
            $otherTotal = number_format((float)(array_sum($other)/$otherCount), 2, '.', '');
        }

        if (isset($lmperformance)) {
            $lmperformanceCount = count($lmperformance);
            $lmperformanceTotal = number_format((float)(array_sum($lmperformance)/$lmperformanceCount), 2, '.', '');
        }

        if (isset($otherperformance)) {
            $otherperformanceCount = count($otherperformance);
            $otherperformanceTotal = number_format((float)(array_sum($otherperformance)/$otherperformanceCount), 2, '.', '');
        }

        $datarray['countrytotal']                   = isset($countrytotal) ? $countrytotal : '0';
        $datarray['reginoaltotal']                  = isset($reginoaltotal) ? $reginoaltotal : '0';
        $datarray['myperformance']                  = isset($performance) ? $performance : '0';
        $datarray['licensedplumber']                = isset($lmTotal) ? $lmTotal : '0';
        $datarray['otherplumber']                   = isset($otherTotal) ? $otherTotal : '0';
        $datarray['companyperformancepoints']       = isset($companyperformancepoints) ? $companyperformancepoints : '0';
        $datarray['adminsettiingsnlm']              = $document_types[7]['points'];
        $datarray['adminsettiingslm']               = $document_types[8]['points'];
        $datarray['lmperformanceTotal']             = isset($lmperformanceTotal) ? $lmperformanceTotal : '0';
        $datarray['otherperformanceTotal']          = isset($otherperformanceTotal) ? $otherperformanceTotal : '0';

        return $datarray;
        
    }
}
