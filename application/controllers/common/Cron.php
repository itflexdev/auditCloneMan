<?php
defined('BASEPATH') OR exit('No direct script access allowed');
  
class Cron extends CC_Controller {
  
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Cron_Model');
		$this->load->model('Cpdtypesetup_Model');
		$this->load->model('Plumber_Model');
		$this->load->model('Renewal_Model');
		$this->load->model('Coc_Model');
		$this->load->model('Auditor_Model');
		$this->load->model('Systemsettings_Model');
		
		$this->load->model('Communication_Model');
		$this->load->model('CC_Model');
	}

    public function rate()
	{
		$fileName 	= base_url().'common/cron/rate';
		$starttime 	= date('Y-m-d H:i:s');

	    $data	=	$this->Cron_Model->display_records();

		foreach ($data as $key => $value) {

			$id = $value->id; 
			if($value->futuredate != '' and !empty($value->futuredate))
			{
				$current_date 	= 	strtotime(date('Y-m-d'));
				$futuredate		=	strtotime($value->futuredate);

				if($current_date == $futuredate) $this->Cron_Model->updaterecords($id,$value->futuredate,$value->futureammount);
			}				
		}
	  
		$endtime = date('Y-m-d H:i:s');
		
		if($starttime && $endtime){
			$this->cronLog(['filename' => $fileName, 'start_time' => $starttime, 'end_time' => $endtime]);
		}      	
    }
		
	public function cpdtype(){
		
		$fileName 	= base_url().'common/cron/cpdtype';
		$starttime 	= date('Y-m-d H:i:s');
		
		$this->Cpdtypesetup_Model->getCronDate();
		
		$endtime = date('Y-m-d H:i:s');

		if ($starttime && $endtime) {
			 $this->cronLog(['filename' => $fileName, 'start_time' => $starttime, 'end_time' => $endtime]);
		}
	}
	
	public function monthlycpdtype(){
		
		$plumberemails = '';
		$fileName 	= base_url().'common/cron/monthlycpdtype';
		$starttime 	= date('Y-m-d H:i:s');

		$currentDate 		= date('m-d-Y');
		$currentMonth 		= date('m');
		$lastMonth 			= date('m', strtotime($currentMonth.' -1'));
		
		$settingsplumberDetails = [];
		$cpdTable 				= '';
		$total 					= '';
		$totalDB 				= '';

		$this->db->select('t1.id as t1id, t1.name_surname, t2.id as plumberid, t3.designation, t2.renewal_date, t2.expirydate, t4.mobile_phone, t2.email');
		// $this->db->select('group_concat(concat_ws("@@@", t1.user_id, t1.name_surname,t1.cpd_stream,t1.points) separator "@-@") as cpddata');
		$this->db->from('users t2');
		$this->db->join('users_plumber t3', 't3.user_id=t2.id','left');
		$this->db->join('users_detail t4', 't4.user_id=t2.id','left');
		$this->db->join('cpd_activity_form t1', 't1.user_id=t2.id','left');
		$this->db->where('t2.type', '3');
		$this->db->where('t2.status', '1');
		// $this->db->where('MONTH(t1.cpd_start_date) = MONTH(CURDATE() - INTERVAL 1 MONTH)');
		// $this->db->where('MONTH(t1.cpd_start_date) = MONTH(CURDATE())');
		$this->db->group_start();
		$this->db->where('t1.status="1"');
		$this->db->group_end();
		$this->db->group_by('t1.user_id');
		
		$userQuery = $this->db->get()->result_array();
		// echo "<pre>";print_r($userQuery);die;
		$settingsCPD = $this->db->select('*')->from('settings_cpd')->get()->result_array();
		$template 	= $this->db->select('*')->from('email_notification')->where('id','14')->where('email_active','1')->get()->row_array();
		$i = 0;	
		foreach ($userQuery as $userQuerykey => $userQueryvalue) {
			/*if ($i ==784) {
				echo "<pre>";print_r($userQuery[$i]);die;
			}*/
			if (isset($userQueryvalue['designation']) && $userQueryvalue['designation'] !='') {
				if(isset($settingsplumberDetails)) unset($settingsplumberDetails);
				$designationDB = $this->config->item('designation2')[$userQueryvalue['designation']];
				// echo $designationDB.'<br>';

				if ($designationDB == 'Learner Plumber') {
					$designation = 'learner';
				}elseif($designationDB == 'Technical Assistant Practitioner'){
					$designation = 'assistant';
				}elseif($designationDB == 'Technical Operator Practitioner'){
					$designation = 'operating';
				}elseif($designationDB == 'Licensed Plumber'){
					$designation = 'licensed';
				}elseif($designationDB == 'Registered Plumber'){ //Qualified Plumber
					$designation = 'qualified';
				}elseif($designationDB == 'Master Plumber'){
					$designation = 'master';				
				}
				foreach ($settingsCPD as $key1 => $value1) {
					$settingsplumberDetails[] = $value1[$designation];
				}
				$totalDB = $settingsplumberDetails[0]+$settingsplumberDetails[1]+$settingsplumberDetails[2];


				$developmentalpts 				= $this->Auditor_Model->admingetcpdpoints('all', ['pagestatus' => '1', 'plumberid' => $userQueryvalue['plumberid'], 'status' => ['1'], 'cpd_stream' => 'developmental', 'dbexpirydate' => $userQueryvalue['expirydate']]);
				
				$individualpts 				= $this->Auditor_Model->admingetcpdpoints('all', ['pagestatus' => '1', 'plumberid' => $userQueryvalue['plumberid'], 'status' => ['1'], 'cpd_stream' => 'individual', 'dbexpirydate' => $userQueryvalue['expirydate']]);

				$workbasedpts 				= $this->Auditor_Model->admingetcpdpoints('all', ['pagestatus' => '1', 'plumberid' => $userQueryvalue['plumberid'], 'status' => ['1'], 'cpd_stream' => 'workbased', 'dbexpirydate' => $userQueryvalue['expirydate']]);

				if (count($developmentalpts) > 0) $developmental 	= array_sum(array_column($developmentalpts, 'points'));
				else $developmental 	= 0;

				if (count($individualpts) > 0) $individual 	= array_sum(array_column($individualpts, 'points'));
				else $individual 	= 0;

				if (count($workbasedpts) > 0) $workbased 	= array_sum(array_column($workbasedpts, 'points'));
				else $workbased 	= 0;

				/*$developmental 	= isset($developmentalpts['points']) ? array_sum(array_column($developmentalpts, 'points')) : 0; 
				$individual 	= isset($individualpts['points']) ? array_sum(array_column($individualpts, 'points')) : 0; 
				$workbased 		= isset($individualpts['points']) ? array_sum(array_column($workbasedpts, 'points')) : 0; */
				$total 			= $developmental+$individual+$workbased;
				$cpdTable = '<table style="width:40%; border-collapse:collapse;" class="tablcpd">
							<tr>
							<th style="border: 1px solid #000;padding:5px 10px;text-align:center;">CPD Stream</th>
							<th style="border: 1px solid #000;padding:5px 10px;text-align:center;">Your Points (YTD)</th>
							<th style="border: 1px solid #000;padding:5px 10px;text-align:center;">Preferred Points Required</th>
							
							</tr>
							<tr>
							<td style="border: 1px solid #000;padding:5px 10px;text-align:center;">Developmental</td>
							<td style="border: 1px solid #000;padding:5px 10px;text-align:center;">'.$developmental.'</td>
							<td style="border: 1px solid #000;padding:5px 10px;text-align:center;">'.$settingsplumberDetails[0].'</td>
							</tr>
							<tr>
							<td style="border: 1px solid #000;padding:5px 10px;text-align:center;">Work-based</td>
							<td style="border: 1px solid #000;padding:5px 10px;text-align:center;">'.$workbased.'</td>
							<td style="border: 1px solid #000;padding:5px 10px;text-align:center;">'.$settingsplumberDetails[1].'</td>
							</tr>
							<tr>
							<td style="border: 1px solid #000;padding:5px 10px;text-align:center;">Individual</td>
							<td style="border: 1px solid #000;padding:5px 10px;text-align:center;">'.$individual.'</td>
							<td style="border: 1px solid #000;padding:5px 10px;text-align:center;">'.$settingsplumberDetails[2].'</td>
							</tr>
							<tr>
							<td style="border: 1px solid #000;padding:5px 10px;text-align:center;">Total</td>
							<td style="border: 1px solid #000;padding:5px 10px;text-align:center;">'.$total.'</td>
							<td style="border: 1px solid #000;padding:5px 10px;text-align:center;">'.$totalDB.'</td>
							</tr>
							</table>';
							// echo $userQueryvalue['email'].'<br>';
							// echo $cpdTable;
							
							if ((isset($template['email_active']) && $template['email_active'] == '1') && $totalDB !='0') {
								if(isset($array1)) unset($array1);
								if(isset($array2)) unset($array2);
								$array1 = ['{Plumbers Name and Surname}','{TODAYS DATE}', 'Points Table', '{plumbers registration renewal date}'];
								$array2 = [$userQueryvalue['name_surname'], $currentDate, $cpdTable, date('m-d-Y', strtotime($userQueryvalue['expirydate']))];
								$body = str_replace($array1, $array2, $template['email_body']);
								$this->CC_Model->sentMail($userQueryvalue['email'],$template['subject'],$body);
								
							}
							$smsdata 	= $this->Communication_Model->getList('row', ['id' => '14', 'smsstatus' => '1']);
							if(($smsdata && isset($userQueryvalue['mobile_phone'])) && $totalDB !='0'){
								if(isset($smsbody1)) unset($smsbody1);
								if(isset($smsbody2)) unset($smsbody2);
								$smsbody1 = ['{total Points}','{total points required}', '{next registration date}'];
								$smsbody2 = [$total, $totalDB, date('m-d-Y', strtotime($userQueryvalue['expirydate']))];
								$sms = str_replace($smsbody1, $smsbody2, $smsdata['sms_body']);
								$this->sms(['no' => $userQueryvalue['mobile_phone'], 'msg' => $sms]);
							}

							$plumberemails .= $userQueryvalue['email'].',';
			}$i++;
		}

		$fp = fopen(FCPATH.'assets/uploads/temp/plumberemails.txt',"wb");
		fwrite($fp,$plumberemails);
		fclose($fp);
		
		$txt = FCPATH.'assets/uploads/temp/plumberemails.txt';
		$this->CC_Model->sentMail('suresh@itflexsolutions.com',"plumber's mothly cpd email",'', $txt);
		$this->CC_Model->sentMail('manikandanrengasamy@itflexsolutions.com',"plumber's mothly cpd email",'', $txt);
		if(file_exists($txt)) unlink($txt);
		
		$endtime = date('Y-m-d H:i:s');
		
		if ($starttime && $endtime) {
			$cron_start = $this->cronLog(['filename' => $fileName, 'start_time' => $starttime, 'end_time' => $endtime]);
		}
	}

    public function performancestatusarchive()
	{
		$fileName 	= base_url().'common/cron/performancestatusarchive';
		$starttime 	= date('Y-m-d H:i:s');

		$this->performancestatusrollingaverage();

		$endtime = date('Y-m-d H:i:s');
		
		if ($starttime && $endtime) {
			$this->cronLog(['filename' => $fileName, 'start_time' => $starttime, 'end_time' => $endtime]);
		}
	}
	
    public function performancestatuswarning()
	{
		$fileName 	= base_url().'common/cron/performancestatuswarning';
		$starttime 	= date('Y-m-d H:i:s');

		$this->performancestatusmail();

		$endtime = date('Y-m-d H:i:s');
		
		if ($starttime && $endtime) {
			$this->cronLog(['filename' => $fileName, 'start_time' => $starttime, 'end_time' => $endtime]);
		}
	}
	
	public function renewalreminder1()
	{	
		$log 		= '';
		$fileName 	= base_url().'common/cron/renewalreminder1';
		$starttime 	= date('Y-m-d H:i:s');

		$result = $this->Renewal_Model->getUserids();		
		$settings = $this->Systemsettings_Model->getList('row');
		
		foreach($result as $data)
		{
			//$inv_type = '1';
			$userid = $data['id'];
			// $checkinv_result = $this->Renewal_Model->checkinv($userid);					

			// if(count($checkinv_result) > 0){
			// 	continue;
			// }else{
				$designation 		= $data['designation'];
				$renewal_date1 		= $data['expirydate'];
				$rdate 				= strtotime($renewal_date1);
				$new_date 			= strtotime('+ 1 year', $rdate);
				$renewal_date 		= date('d/m/Y', $new_date);
				
				
				$userdata1	= 	$this->Plumber_Model->getList('row', ['id' => $userid], ['users', 'usersdetail', 'usersplumber']);
				$otherfee 	= 	$this->invoiceotherfee($userdata1);
				
				$result = $this->Renewal_Model->updatedata($userid,$designation,'2','','',$otherfee);			
				$invoice_id = $result['invoice_id'];
				$cocorder_id = $result['cocorder_id'];
			
				$log	.= $userid.'-'.$invoice_id.'-'.date('d-m-Y', strtotime($renewal_date1)).PHP_EOL;
				
				if ($invoice_id) {
					$inid 				= $cocorder_id;
					$inv_id 			= $invoice_id;

					$orders = $this->db->select('*')->from('coc_orders')->where(['inv_id' => $invoice_id])->get()->row_array();

					$rowData = $this->Coc_Model->getListPDF('row', ['id' => $inv_id, 'status' => ['0','1']]);
					$designation =	$this->config->item('designation2')[$rowData['designation']];					
					$cocreport = $this->cocreport($inv_id, 'PDF Invoice Plumber COC', ['description' => 'PIRB year renewal fee for '.$designation.' for '.$rowData['username'].' '.$rowData['surname'].', registration number '.$rowData['registration_no']]+$otherfee);
					
					$cocTypes = $orders['coc_type'];
					$mail_date = date("d-m-Y", strtotime($orders['created_at']));
					
					
					$notificationdata 	= $this->Communication_Model->getList('row', ['id' => '1', 'emailstatus' => '1']);
					
					if($notificationdata){
						$array1 = ['{Plumbers Name and Surname}','{date of purchase}', '{Number of COC}','{COC Type}','{renewal_date}'];
						$array2 = [$userdata1['name']." ".$userdata1['surname'], $mail_date, $orders['quantity'], $this->config->item('coctype2')[$cocTypes],$renewal_date];
						$body 	= str_replace($array1, $array2, $notificationdata['email_body']);
						$this->CC_Model->sentMail($userdata1['email'], $notificationdata['subject'], $body, $cocreport);

						$DBlog = [
							'plumber_id' 	=> $userid,
							'type' 			=> '2',
							'url' 			=> $fileName,
							'created_at' 	=> $starttime
						];
						$this->db->insert('trigger_renewal_log', $DBlog);
					}
					
					if($settings && $settings['otp']=='1'){
						$smsdata 	= $this->Communication_Model->getList('row', ['id' => '1', 'smsstatus' => '1']);
			
						if($smsdata){
							$sms = $smsdata['sms_body'];
							$this->sms(['no' => $userdata1['mobile_phone'], 'msg' => $sms]);
						}
					}

				}			 

			// }
			
		}
		
		$endtime = date('Y-m-d H:i:s');
		if ($starttime && $endtime) {
			$this->cronLog(['filename' => $fileName, 'start_time' => $starttime, 'end_time' => $endtime]);
		}

		$file = fopen("assets/payment/renewalreminder.txt","a");
		fwrite($file, 'Renewal Reminder 1'.PHP_EOL);
		fwrite($file, 'Date -'.date('d-m-Y H:i:s').PHP_EOL);
		fwrite($file, $log.PHP_EOL);
		fwrite($file, PHP_EOL);
		fwrite($file, PHP_EOL);
		fclose($file);		
	}

	public function renewalreminder2()
	{	
		$log		= '';		
		$fileName 	= base_url().'common/cron/renewalreminder2';
		$starttime 	= date('Y-m-d H:i:s');

		$result = $this->Renewal_Model->getUserids_alert2();	
		$settings = $this->Systemsettings_Model->getList('row');
		
		foreach($result as $data)
		{
			
			$userid = $data['id'];
			$designation = $data['designation'];
			$invoice_id = $data['inv_id'];
			$cocid = $data['cocid'];
			$designation = $data['designation'];
			
			$userdata1	= 	$this->Plumber_Model->getList('row', ['id' => $userid], ['users', 'usersdetail', 'usersplumber']);
			$otherfee 	= 	$this->invoiceotherfee($userdata1);
			
			$insert_result = $this->Renewal_Model->updatedata($userid,$designation,'3',$invoice_id,$cocid,$otherfee);
			$invoice_id = $insert_result['invoice_id'];
			$cocorder_id = $insert_result['cocorder_id'];
			
			$log	.= $userid.'-'.$invoice_id.PHP_EOL;
			
			if ($invoice_id) {
				$inv_id 			= $invoice_id;


				$orders = $this->db->select('*')->from('coc_orders')->where(['inv_id' => $invoice_id])->get()->row_array();

				$rowData = $this->Coc_Model->getListPDF('row', ['id' => $inv_id, 'status' => ['0','1']]);
				$designation =	$this->config->item('designation2')[$rowData['designation']];					
				$cocreport = $this->cocreport($inv_id, 'PDF Invoice Plumber COC', ['description' => 'PIRB year renewal fee for '.$designation.' for '.$rowData['username'].' '.$rowData['surname'].', registration number '.$rowData['registration_no'], 'type' => '2']+$otherfee);
				
				$cocTypes = $orders['coc_type'];
				$mail_date = date("d-m-Y", strtotime($orders['created_at']));
							
				$notificationdata 	= $this->Communication_Model->getList('row', ['id' => '2', 'emailstatus' => '1']);
				
				if($notificationdata){
					$array1 = ['{Plumbers Name and Surname}','{date of purchase}', '{Number of COC}','{COC Type}'];
					$array2 = [$userdata1['name']." ".$userdata1['surname'], $mail_date, $orders['quantity'], $this->config->item('coctype2')[$cocTypes]];
					$body 	= str_replace($array1, $array2, $notificationdata['email_body']);
					$this->CC_Model->sentMail($userdata1['email'], $notificationdata['subject'], $body, $cocreport);

					$DBlog = [
							'plumber_id' 	=> $userid,
							'type' 			=> '2',
							'url' 			=> $fileName,
							'created_at' 	=> $starttime
						];
						$this->db->insert('trigger_renewal_log', $DBlog);
				}
				
				if($settings && $settings['otp']=='1'){
					$smsdata 	= $this->Communication_Model->getList('row', ['id' => '2', 'smsstatus' => '1']);
		
					if($smsdata){
						$sms = $smsdata['sms_body'];
						$this->sms(['no' => $userdata1['mobile_phone'], 'msg' => $sms]);
					}
				}
			}
			
		}
		
		$endtime = date('Y-m-d H:i:s');
		if ($starttime && $endtime) {
			$this->cronLog(['filename' => $fileName, 'start_time' => $starttime, 'end_time' => $endtime]);
		}
		
		$file = fopen("assets/payment/renewalreminder.txt","a");
		fwrite($file, 'Renewal Reminder 2'.PHP_EOL);
		fwrite($file, 'Date -'.date('d-m-Y H:i:s').PHP_EOL);
		fwrite($file, $log.PHP_EOL);
		fwrite($file, PHP_EOL);
		fwrite($file, PHP_EOL);
		fclose($file);		
	}

	public function renewalreminder3()
	{	
		$log		= '';
		$fileName 	= base_url().'common/cron/renewalreminder3';
		$starttime 	= date('Y-m-d H:i:s');

		$result = $this->Renewal_Model->getUserids_alert3();	
		$settings = $this->Systemsettings_Model->getList('row');
		foreach($result as $data)
		{						
			$userid = $data['id'];
			$designation = $data['designation'];
			$invoice_id = $data['inv_id'];
			$cocid = $data['cocid'];
			$designation = $data['designation'];
			
			$userdata1	= 	$this->Plumber_Model->getList('row', ['id' => $userid], ['users', 'usersdetail', 'usersplumber']);
			$otherfee 	= 	$this->invoiceotherfee($userdata1);

			$insert_result = $this->Renewal_Model->updatedata($userid,$designation,'4',$invoice_id,$cocid,$otherfee);
			
			$invoice_id = $insert_result['invoice_id'];
			$cocorder_id = $insert_result['cocorder_id'];
			$cocorder_id2 = $insert_result['cocorder_id2'];
			
			$log	.= $userid.'-'.$invoice_id.PHP_EOL;
			
			if ($invoice_id) {
				$inv_id 			= $invoice_id;

				$orders = $this->db->select('*')->from('coc_orders')->where(['inv_id' => $invoice_id])->get()->row_array();

				$lateamount_result = $this->db->select('*')->from('coc_orders')->where(['id' => $cocorder_id2])->get()->row_array();
				$lateamount = $lateamount_result['cost_value'];
				$total_lateamount = $lateamount_result['total_due'];
				$vat_lateamount = $lateamount_result['vat'];

				$rowData = $this->Coc_Model->getListPDF('row', ['id' => $inv_id, 'status' => ['0','1']]);
				$designation =	$this->config->item('designation2')[$rowData['designation']];					
				$cocreport = $this->cocreport($inv_id, 'PDF Invoice Plumber COC', ['description' => 'PIRB year renewal fee for '.$designation.' for '.$rowData['username'].' '.$rowData['surname'].', registration number '.$rowData['registration_no'], 'type' => '2', 'latesubtotalamount' => $lateamount, 'latevatamount' => $vat_lateamount, 'latetotalamount' => $total_lateamount]+$otherfee);
			
				$cocTypes = $orders['coc_type'];
				$mail_date = date("d-m-Y", strtotime($orders['created_at']));
				
				$notificationdata 	= $this->Communication_Model->getList('row', ['id' => '3', 'emailstatus' => '1']);
				
				if($notificationdata){
					$array1 = ['{Plumbers Name and Surname}','{date of purchase}', '{Number of COC}','{COC Type}'];
					$array2 = [$userdata1['name']." ".$userdata1['surname'], $mail_date, $orders['quantity'], $this->config->item('coctype2')[$cocTypes]];
					$body 	= str_replace($array1, $array2, $notificationdata['email_body']);
					$this->CC_Model->sentMail($userdata1['email'], $notificationdata['subject'], $body, $cocreport);

					$DBlog = [
							'plumber_id' 	=> $userid,
							'type' 			=> '2',
							'url' 			=> $fileName,
							'created_at' 	=> $starttime
						];
						$this->db->insert('trigger_renewal_log', $DBlog);
				}
				
				if($settings && $settings['otp']=='1'){
					$smsdata 	= $this->Communication_Model->getList('row', ['id' => '3', 'smsstatus' => '1']);
		
					if($smsdata){
						$sms = $smsdata['sms_body'];
						$this->sms(['no' => $userdata1['mobile_phone'], 'msg' => $sms]);
					}
				}

			}
			
		}
		
		$endtime = date('Y-m-d H:i:s');
		if ($starttime && $endtime) {
			$this->cronLog(['filename' => $fileName, 'start_time' => $starttime, 'end_time' => $endtime]);
		}
		
		$file = fopen("assets/payment/renewalreminder.txt","a");
		fwrite($file, 'Renewal Reminder 3'.PHP_EOL);
		fwrite($file, 'Date -'.date('d-m-Y H:i:s').PHP_EOL);
		fwrite($file, $log.PHP_EOL);
		fwrite($file, PHP_EOL);
		fwrite($file, PHP_EOL);
		fclose($file);		
	}

	public function renewalreminder4()
	{	
		$log		= '';
		$fileName 	= base_url().'common/cron/renewalreminder4';
		$starttime 	= date('Y-m-d H:i:s');

		$result = $this->Renewal_Model->getUserids_alert4();	
		$settings = $this->Systemsettings_Model->getList('row');	
		
		foreach($result as $data)
		{						
			$userid = $data['id'];  
			$request['status'] = '3';
			$this->db->update('users_detail', $request, ['user_id' => $userid]);
			
			$request1['status'] = '2';
			$this->db->update('users', $request1, ['id' => $userid]);
			
			$log	.= $userid.PHP_EOL;

			$DBlog = [
							'plumber_id' 	=> $userid,
							'type' 			=> '2',
							'url' 			=> $fileName,
							'created_at' 	=> $starttime
						];
						$this->db->insert('trigger_renewal_log', $DBlog);
		}
		
		$endtime = date('Y-m-d H:i:s');
		if ($starttime && $endtime) {
			$this->cronLog(['filename' => $fileName, 'start_time' => $starttime, 'end_time' => $endtime]);
		}
		
		$file = fopen("assets/payment/renewalreminder.txt","a");
		fwrite($file, 'Renewal Reminder 4'.PHP_EOL);
		fwrite($file, 'Date -'.date('d-m-Y H:i:s').PHP_EOL);
		fwrite($file, $log.PHP_EOL);
		fwrite($file, PHP_EOL);
		fwrite($file, PHP_EOL);
		fclose($file);		
	}
	
	public function monthlyperformance()
	{	
		$plumbers	= 	$this->Plumber_Model->getList('all', ['plumberstatus' => ['1']], ['users', 'usersdetail']);
		$date		= 	date('d-m-Y');
		$settings 	=	$this->Systemsettings_Model->getList('row');
		
		foreach($plumbers as $plumber){
			$id 			= $plumber['id'];
			
			$result 		= $this->Plumber_Model->performancestatus('all', ['plumberid' => $id, 'archive' => '0']);
			$performance 	= array_sum(array_column($result, 'point'));
			
			$notificationdata 	= $this->Communication_Model->getList('row', ['id' => '13', 'emailstatus' => '1']);
				
			if($notificationdata){
				$array1 = ['{Plumbers Name and Surname}', '{todays dates}', '{total value of performance}'];
				$array2 = [$plumber['name'].' '.$plumber['surname'], $date, $performance];
				
				$body 	= str_replace($array1, $array2, $notificationdata['email_body']);
				$this->CC_Model->sentMail($plumber['email'], $notificationdata['subject'], $body);
			}
			
			if($settings && $settings['otp']=='1'){
				$smsdata 	= $this->Communication_Model->getList('row', ['id' => '13', 'smsstatus' => '1']);
	
				if($smsdata){
					$sms = str_replace(['{performance warning status}'], [$performance], $smsdata['sms_body']);
					$this->sms(['no' => $plumber['mobile_phone'], 'msg' => $sms]);
				}
			}
		}
	}

	public function monthlycoc()
	{	
		$plumbers	= $this->Plumber_Model->getList('all', ['plumberstatus' => ['1']], ['users', 'usersdetail']);
		$date		= date('d-m-Y');
		$settings 	= $this->Systemsettings_Model->getList('row');
		
		foreach($plumbers as $plumber){
			$id 			= $plumber['id'];
			$history		= $this->Auditor_Model->getReviewHistoryCount(['plumberid' => $id]);
			$logged			= $this->Coc_Model->getCOCList('count', ['user_id' => $id, 'coc_status' => ['2']]);
			$allocated		= $this->Coc_Model->getCOCList('count', ['user_id' => $id, 'coc_status' => ['4']]);
			$nonlogged		= $this->Coc_Model->getCOCList('count', ['user_id' => $id, 'coc_status' => ['5']]);
			
			$notificationdata 	= $this->Communication_Model->getList('row', ['id' => '19', 'emailstatus' => '1']);
				
			if($notificationdata){
				$array1 = ['{Plumbers Name and Surname}', '{todays date}', '{number1}', '{number2}', '{number3}', '{number4}'];
				$array2 = [$plumber['name'].' '.$plumber['surname'], $date, $nonlogged, $allocated, $logged, $history['count']];
				
				$body 	= str_replace($array1, $array2, $notificationdata['email_body']);
				$this->CC_Model->sentMail($plumber['email'], $notificationdata['subject'], $body);
			}
			
			if($settings && $settings['otp']=='1'){
				$smsdata 	= $this->Communication_Model->getList('row', ['id' => '19', 'smsstatus' => '1']);
	
				if($smsdata){
					$sms = str_replace(['{number}'], [$nonlogged], $smsdata['sms_body']);
					$this->sms(['no' => $plumber['mobile_phone'], 'msg' => $sms]);
				}
			}
		}
	}
	
	// public function invoiceotherfee($userdata1){
	// 	$otherfee = [];
	// 	if($userdata1['registration_card']=='1'){
	// 		$otherfee['cardfee'] = $this->getRates($this->config->item('cardfee'));
			/*if($userdata1['delivery_card']=='1'){
				$otherfee['deliveryfee'] 	= $this->getRates($this->config->item('postage'));
				$otherfee['deliverycard'] 	= '1';
			}elseif($userdata1['delivery_card']=='2'){
				$otherfee['deliveryfee'] 	= $this->getRates($this->config->item('couriour'));
				$otherfee['deliverycard'] 	= '2';
			}*/
	// 	}
	// 	$specialisations = array_filter(explode(',', $userdata1['specialisations']));
	// 	if(count($specialisations) > 0){
	// 		$otherfee['specialisationsfee'] = $this->getRates($this->config->item('specializationfee'));
	// 		$otherfee['specialisationsqty'] = count($specialisations);
	// 	}
		
	// 	return $otherfee;
	// }

	public function invoiceotherfee($userdata1){
		$otherfee 		= [];
		$settings 		= $this->Systemsettings_Model->getList('row');
		$designation 	= explode(',', $settings['renewal_card']);

		// if($userdata1['registration_card']=='1' && $settings['renewal_card'] =='1'){
		if($userdata1['registration_card']=='1' && in_array($userdata1['designation'], $designation)){
			$otherfee['cardfee'] = $this->getRates($this->config->item('cardfee'));
			/*if($userdata1['delivery_card']=='1'){
				$otherfee['deliveryfee'] 	= $this->getRates($this->config->item('postage'));
				$otherfee['deliverycard'] 	= '1';
			}elseif($userdata1['delivery_card']=='2'){
				$otherfee['deliveryfee'] 	= $this->getRates($this->config->item('couriour'));
				$otherfee['deliverycard'] 	= '2';
			}*/
		}
		$specialisations = array_filter(explode(',', $userdata1['specialisations']));
		if((count($specialisations) > 0) && $settings['renewal_specialization'] =='1'){
			$otherfee['specialisationsfee'] = $this->getRates($this->config->item('specializationfee'));
			$otherfee['specialisationsqty'] = count($specialisations);
		}
		
		return $otherfee;
	}

}

	
  
