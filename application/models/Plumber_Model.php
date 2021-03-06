<?php

class Plumber_Model extends CC_Model
{
	public function getList($type, $requestdata=[], $querydata=[])
	{ 
		$select = [];
		
		if(in_array('users', $querydata)){
			$users 			= 	[ 
									'u.id','u.email','u.formstatus','u.expirydate','u.type','u.status' 
								];
								
			$select[] 		= 	implode(',', $users);
		}
		
		if(in_array('usersdetail', $querydata)){
			$usersdetail 	= 	[ 
									'ud.id as usersdetailid','ud.title','ud.name','ud.surname','ud.dob','ud.gender','ud.company_name','ud.reg_no','ud.vat_no','ud.billing_email','ud.billing_contact','ud.contact_person','ud.home_phone','ud.mobile_phone','ud.mobile_phone2','ud.work_phone','ud.email2','ud.file1','ud.file2','ud.coc_purchase_limit','ud.specialisations','ud.status as plumberstatus'
								];
								
			$select[] 		= 	implode(',', $usersdetail);
		}
		
		if(in_array('usersplumber', $querydata)){
			$usersplumber 	= 	[ 
									'up.id as usersplumberid','up.racial','up.nationality','up.othernationality','up.idcard','up.otheridcard','up.homelanguage','up.disability','up.citizen','up.registration_card','up.delivery_card','up.employment_details','up.company_details',
									'up.registration_no','up.registration_date','up.designation','up.qualification_year','up.coc_electronic','up.message',
									'up.application_received','up.application_status','up.approval_status','up.reject_reason','up.reject_reason_other', 'up.lms_registration', 'up.lms_status'
								];
								
			$select[] 		= 	implode(',', $usersplumber);
		}
		
		if(in_array('usersskills', $querydata)){
			$select[]		= 	'group_concat(IF(COALESCE(ups.id, "")="", "", concat_ws("@@@", COALESCE(ups.id, ""), COALESCE(ups.user_id, ""), COALESCE(ups.date, ""), COALESCE(ups.certificate, ""), COALESCE(ups.qualification, ""), COALESCE(ups.skills, ""), COALESCE(ups.training, ""), COALESCE(ups.attachment, ""), COALESCE(qr.name, ""))) separator "@-@") as skills';
		}
		
		if(in_array('company', $querydata)){
			$userscompany	= 	[ 
									'c.company as companyname',
									'c.mobile_phone as companymobile', 'c.work_phone as cwork_phone'
								];
			
			$select[] 		= 	implode(',', $userscompany);
		}
		
		if(in_array('physicaladdress', $querydata)){
			$select[] 		= 	'concat_ws("@-@", ua1.id, ua1.user_id, ua1.address, ua1.suburb, ua1.city, ua1.province, ua1.postal_code, ua1.type)  as physicaladdress';
		}
		
		if(in_array('postaladdress', $querydata)){
			$select[]		= 	'concat_ws("@-@", ua2.id, ua2.user_id, ua2.address, ua2.suburb, ua2.city, ua2.province, ua2.postal_code, ua2.type)  as postaladdress';
		}
		
		if(in_array('billingaddress', $querydata)){
			$select[]		= 	'concat_ws("@-@", ua3.id, ua3.user_id, ua3.address, ua3.suburb, ua3.city, ua3.province, ua3.postal_code, ua3.type)  as billingaddress';
		}
		
		if(in_array('alllist', $querydata)){
			$select 		= 	[];
			$alllist		= 	[
									'u.id','u.email','ud.name','ud.surname','ud.status as plumberstatus','up.designation','up.registration_no'
								];
			$select[] 		= 	implode(',', $alllist);
		}
		
		$this->db->select(implode(',', $select));
		$this->db->from('users u');
		if(in_array('usersdetail', $querydata)) 		$this->db->join('users_detail ud', 'ud.user_id=u.id', 'left');
		if(in_array('physicaladdress', $querydata)) 	$this->db->join('users_address ua1', 'ua1.user_id=u.id and ua1.type="1"', 'left');
		if(in_array('postaladdress', $querydata)) 		$this->db->join('users_address ua2', 'ua2.user_id=u.id and ua2.type="2"', 'left');
		if(in_array('billingaddress', $querydata)) 		$this->db->join('users_address ua3', 'ua3.user_id=u.id and ua3.type="3"', 'left');
		if(in_array('usersplumber', $querydata)) 		$this->db->join('users_plumber up', 'up.user_id=u.id', 'left');
		if(in_array('usersskills', $querydata)) 		$this->db->join('users_plumber_skill ups', 'ups.user_id=u.id', 'left');
		if(in_array('usersskills', $querydata)) 		$this->db->join('qualificationroute qr', 'qr.id=ups.skills', 'left'); 
		if(in_array('company', $querydata)) 			$this->db->join('users_detail c', 'c.user_id=up.company_details', 'left');
		
		if((isset($requestdata['search']['value']) && $requestdata['search']['value']!='') || (isset($requestdata['order']['0']['column']) && $requestdata['order']['0']['column']!='' && isset($requestdata['order']['0']['dir']) && $requestdata['order']['0']['dir']!='')){
			if(isset($requestdata['page']) && $requestdata['page']=='adminplumberlist'){
				$this->db->join('custom c1', 'c1.c_id=up.designation and c1.type="5"', 'left');
				$this->db->join('custom c2', 'c2.c_id=ud.status and c2.type="6"', 'left');
			}
		}
		
		if(isset($requestdata['id'])) 					$this->db->where('u.id', $requestdata['id']);
		if(isset($requestdata['type'])) 				$this->db->where('u.type', $requestdata['type']);
		if(isset($requestdata['formstatus']))			$this->db->where_in('u.formstatus', $requestdata['formstatus']);
		if(isset($requestdata['status']))				$this->db->where_in('u.status', $requestdata['status']);
		if(isset($requestdata['approvalstatus']))		$this->db->where_in('up.approval_status', $requestdata['approvalstatus']);
		if(isset($requestdata['plumberstatus']))		$this->db->where_in('ud.status', $requestdata['plumberstatus']);
		if(isset($requestdata['gender']))				$this->db->where_in('ud.gender', $requestdata['gender']);
		if(isset($requestdata['designation']))			$this->db->where_in('up.designation', $requestdata['designation']);
		if(isset($requestdata['racial']))				$this->db->where_in('up.racial', $requestdata['racial']);
		if(isset($requestdata['searchregno']))			$this->db->like('up.registration_no', $requestdata['searchregno']);
		
		if($type!=='count' && isset($requestdata['start']) && isset($requestdata['length'])){
			$this->db->limit($requestdata['length'], $requestdata['start']);
		}
		if(isset($requestdata['order']['0']['column']) && isset($requestdata['order']['0']['dir'])){
			if(isset($requestdata['page']) && $requestdata['page']=='adminplumberlist'){
				$column = ['up.registration_no', 'ud.name', 'ud.surname', 'c1.name', 'u.email', 'c2.name'];
				$this->db->order_by($column[$requestdata['order']['0']['column']], $requestdata['order']['0']['dir']);
			}elseif(isset($requestdata['page']) && $requestdata['page']=='adminplumberrejectedlist'){
				$column = ['up.registration_date', 'ud.name'];
				$this->db->order_by($column[$requestdata['order']['0']['column']], $requestdata['order']['0']['dir']);
			}
		}
		if(isset($requestdata['search']['value']) && $requestdata['search']['value']!=''){
			$searchvalue = $requestdata['search']['value'];
						
			if(isset($requestdata['page'])){
				$page = $requestdata['page'];
				
				$this->db->group_start();
					if($page=='adminplumberlist'){					
						$this->db->like('up.registration_no', $searchvalue);
						$this->db->or_like('ud.name', $searchvalue);
						$this->db->or_like('ud.surname', $searchvalue);
						$this->db->or_like('c1.name', $searchvalue);
						$this->db->or_like('u.email', $searchvalue);
						$this->db->or_like('c2.name', $searchvalue);
					}elseif($page=='adminplumberrejectedlist'){					
						$this->db->like('DATE_FORMAT(up.registration_date,"%d-%m-%Y")', $searchvalue, 'both');
						$this->db->or_like('ud.name', $searchvalue);
						$this->db->or_like('ud.surname', $searchvalue);
					}
				$this->db->group_end();
			}			
		}
		
		if(isset($requestdata['customsearch'])){			
			if($requestdata['customsearch']=='listsearch1'){
				if(isset($requestdata['search_reg_no']) && $requestdata['search_reg_no']!='') $this->db->like('up.registration_no', $requestdata['search_reg_no']);
				if(isset($requestdata['search_plumberstatus']) && $requestdata['search_plumberstatus']!='') $this->db->like('ud.status', $requestdata['search_plumberstatus']);
				if(isset($requestdata['search_idcard']) && $requestdata['search_idcard']!='') $this->db->like('up.idcard', $requestdata['search_idcard']);
				if(isset($requestdata['search_mobile_phone']) && $requestdata['search_mobile_phone']!='') $this->db->like('ud.mobile_phone', $requestdata['search_mobile_phone']);
				if(isset($requestdata['search_dob']) && $requestdata['search_dob']!='') $this->db->like('ud.dob', date('Y-m-d', strtotime($requestdata['search_dob'])));
				if(isset($requestdata['search_company_details']) && $requestdata['search_company_details']!='') $this->db->like('up.company_details', $requestdata['search_company_details']);
			}elseif($requestdata['customsearch']=='listsearch2'){
				if(isset($requestdata['name'])|| $requestdata['surname']!='') {
					$this->db->like('ud.name', $requestdata['name']);
					$this->db->or_like('ud.surname', $requestdata['surname']);
				}
			}
		}
		
		$this->db->group_by('u.id');
		
		if($type=='count'){
			$result = $this->db->count_all_results();
		}else{
			$query = $this->db->get();
			
			if($type=='all') 		$result = $query->result_array();
			elseif($type=='row') 	$result = $query->row_array();
		}
		
		return $result;
	}
	
	public function action($data)
	{
		$this->db->trans_begin();
		
		$datetime				= 	date('Y-m-d H:i:s');
		$idarray				= 	[];
		
		if(isset($data['title'])) 				$request1['title'] 				= $data['title'];
		if(isset($data['name'])) 				$request1['name'] 				= $data['name'];
		if(isset($data['surname'])) 			$request1['surname'] 			= $data['surname'];
		if(isset($data['dob'])) 				$request1['dob'] 				= date('Y-m-d', strtotime($data['dob']));
		if(isset($data['gender'])) 				$request1['gender'] 			= $data['gender'];		
		if(isset($data['company_name'])) 		$request1['company_name'] 		= $data['company_name'];
		if(isset($data['reg_no'])) 				$request1['reg_no'] 			= $data['reg_no']; 
		if(isset($data['vat_no'])) 				$request1['vat_no'] 			= $data['vat_no'];
		if(isset($data['billing_email'])) 		$request1['billing_email'] 		= $data['billing_email'];
		if(isset($data['billing_contact'])) 	$request1['billing_contact'] 	= $data['billing_contact'];
		if(isset($data['home_phone'])) 			$request1['home_phone'] 		= $data['home_phone'];
		if(isset($data['mobile_phone'])) 		$request1['mobile_phone'] 		= $data['mobile_phone'];
		if(isset($data['work_phone'])) 			$request1['work_phone'] 		= $data['work_phone'];
		if(isset($data['image1'])) 				$request1['file1'] 				= $data['image1'];
		if(isset($data['image2'])) 				$request1['file2'] 				= $data['image2'];
		if(isset($data['mobile_phone2'])) 		$request1['mobile_phone2'] 		= $data['mobile_phone2'];
		if(isset($data['email2'])) 				$request1['email2'] 			= $data['email2'];
		if(isset($data['coc_purchase_limit'])) 	$request1['coc_purchase_limit']	= $data['coc_purchase_limit'];
		if(isset($data['specialisations'])) 	$request1['specialisations'] 	= implode(',', $data['specialisations']);	
		if(isset($data['commonaction']) && !isset($data['specialisations'])) 		$request1['specialisations'] 	= '';	
		if(isset($data['plumberstatus'])) 		$request1['status'] 			= $data['plumberstatus'];
		
		if(isset($data['approval_status']) && $data['approval_status']=='1' && !isset($data['plumberstatus'])){
			$request1['status'] 	= '1';
		}
		
		if(isset($request1)){
			$usersdetailid	= 	$data['usersdetailid'];
			if(isset($data['user_id'])) $request1['user_id'] = $data['user_id'];
			
			if($usersdetailid==''){
				$usersdetail = $this->db->insert('users_detail', $request1);
				$usersdetailinsertid = $this->db->insert_id();
			}else{
				$usersdetail = $this->db->update('users_detail', $request1, ['id' => $usersdetailid]);
				$usersdetailinsertid = $usersdetailid;
			}
			
			$idarray['usersdetailid'] = $usersdetailinsertid;
		}
		
		if(isset($data['racial'])) 				$request2['racial'] 				= $data['racial'];
		if(isset($data['nationality'])) 		$request2['nationality'] 			= $data['nationality'];
		if(isset($data['othernationality'])) 	$request2['othernationality'] 		= $data['othernationality'];
		if(isset($data['idcard'])) 				$request2['idcard'] 				= $data['idcard'];
		if(isset($data['otheridcard'])) 		$request2['otheridcard'] 			= $data['otheridcard'];
		if(isset($data['homelanguage'])) 		$request2['homelanguage'] 			= $data['homelanguage'];
		if(isset($data['disability'])) 			$request2['disability'] 			= $data['disability'];
		if(isset($data['citizen'])) 			$request2['citizen'] 				= $data['citizen'];
		if(isset($data['registration_card'])) 	$request2['registration_card'] 		= $data['registration_card'];
		if(isset($data['delivery_card'])) 		$request2['delivery_card'] 			= $data['delivery_card'];
		if(isset($data['employment_details'])) 	$request2['employment_details'] 	= $data['employment_details'];
		if(isset($data['company_details'])) 	$request2['company_details'] 		= $data['company_details'];
		if(isset($data['registration_date'])) 	$request2['registration_date'] 		= date('Y-m-d', strtotime($data['registration_date']));
		if(isset($data['designation'])) 		$request2['designation'] 			= $data['designation'];
		if(isset($data['designation2'])) 		$request2['designation'] 			= $data['designation2'];
		if(isset($data['qualification_year'])) 	$request2['qualification_year'] 	= $data['qualification_year'];
		if(isset($data['message'])) 			$request2['message'] 				= $data['message'];
		if(isset($data['application_received']))$request2['application_received'] 	= date('Y-m-d', strtotime($data['application_received']));
		if(isset($data['application_status'])) 	$request2['application_status'] 	= implode(',', $data['application_status']);
		if(isset($data['approval_status'])) 	$request2['approval_status'] 		= $data['approval_status'];
		if(isset($data['reject_reason'])) 		$request2['reject_reason'] 			= implode(',', $data['reject_reason']);
		if(isset($data['reject_reason_other'])) $request2['reject_reason_other']	= $data['reject_reason_other'];
		if(isset($data['customregno'])) 		$request2['registration_no']		= $data['customregno'];
		if(isset($data['lmsregistration'])) 	$request2['lms_registration']		= $data['lmsregistration'];
						
		if(isset($data['registration_no']) && !isset($data['approval_status']) && isset($data['user_id']) && isset($data['designation2'])){
			$request2['registration_no'] 		= $this->plumberregistrationno($data['user_id'], $data['designation2'], ((isset($data['qualification_year'])) ? $data['qualification_year'] : ''));
		}
		
		if(isset($data['approval_status']) && $data['approval_status']=='1' && !isset($data['registration_date'])){
			$request2['registration_date'] 	= date('Y-m-d');
		}

		if (isset($request2['registration_no'])) {
			$idarray['registration_no'] = $request2['registration_no'];
		}elseif(isset($data['customregno'])){
			$idarray['registration_no'] = $data['customregno'];
		}
		
		if(isset($request2)){
			if((isset($data['usertype']) && $data['usertype']!='1') || !isset($data['usertype'])) $request2['coc_electronic'] 	= (isset($data['coc_electronic'])) ? $data['coc_electronic'] : '0';
			$usersplumberid					= $data['usersplumberid'];
			if(isset($data['user_id'])) $request2['user_id'] 	= $data['user_id'];
			
			if($usersplumberid==''){
				$usersplumber = $this->db->insert('users_plumber', $request2);
				$usersplumberinsertid = $this->db->insert_id();
			}else{
				$usersplumber = $this->db->update('users_plumber', $request2, ['id' => $usersplumberid]);
				$usersplumberinsertid = $usersplumberid;
			}
			
			$idarray['usersplumberid'] = $usersplumberinsertid;
		}
		
		if(isset($data['address']) && count($data['address'])){
			$usersaddressinsertids = [];
			foreach($data['address'] as $key => $request3){
				if(isset($data['user_id'])) $request3['user_id'] = $data['user_id'];
				if($request3['id']==''){
					$usersaddress = $this->db->insert('users_address', $request3);
					$usersaddressinsertids[$request3['type']] = $this->db->insert_id();
				}else{
					$usersaddress = $this->db->update('users_address', $request3, ['id' => $request3['id']]);
					$usersaddressinsertids[$request3['type']] = $request3['id'];
				}
			}
			
			$idarray['usersaddressinsertid'] = $usersaddressinsertids;
		}
		
		if(isset($data['skill_date'])) 					$request4['date'] 				= date('Y-m-d', strtotime($data['skill_date']));
		if(isset($data['skill_certificate'])) 			$request4['certificate'] 		= $data['skill_certificate'];
		if(isset($data['skill_qualification_type'])) 	$request4['qualification'] 		= $data['skill_qualification_type'];
		if(isset($data['skill_route'])) 				$request4['skills'] 			= $data['skill_route'];
		if(isset($data['skill_training'])) 				$request4['training'] 			= $data['skill_training'];
		if(isset($data['skill_attachment'])) 			$request4['attachment'] 		= $data['skill_attachment'];
		
		if(isset($request4)){
			$skillid = (isset($data['skill_id'])) ? $data['skill_id'] : '';
			if(isset($data['user_id'])) $request4['user_id'] = $data['user_id'];
			
			if($skillid==''){
				$skill = $this->db->insert('users_plumber_skill', $request4);
				$skillid = $this->db->insert_id();
			}else{
				$skill = $this->db->update('users_plumber_skill', $request4, ['id' => $skillid]);
			}
			
			$idarray['skillid'] = $skillid;
		}
		
		if(isset($data['migrateid'])) 		$request5['migrateid'] 		= $data['migrateid'];
		if(isset($data['formstatus'])) 		$request5['formstatus'] 	= $data['formstatus'];
		if(isset($data['email'])) 			$request5['email'] 			= $data['email'];
		if(isset($data['status'])) 			$request5['status'] 		= $data['status'];
		if(isset($data['approval_status']) && $data['approval_status']=='1' && isset($request2['registration_date'])) $request5['expirydate'] = date('Y-m-d H:i:s', strtotime($request2['registration_date']. ' +365 days'));
		if(isset($data['expirydate'])) $request5['expirydate'] = date('Y-m-d H:i:s', strtotime($data['expirydate']));
		if(isset($data['plumberstatus']) && ($data['plumberstatus']=='3' || $data['plumberstatus']=='4' || $data['plumberstatus']=='5')) 	$request5['status'] = '2';
		if(isset($data['plumberstatus']) && ($data['plumberstatus']=='1' || $data['plumberstatus']=='2')) 	$request5['status'] = '1';
		if(isset($data['plumberstatus']) && $data['plumberstatus']=='1' && isset($data['plumberpreviousstatus']) && $data['plumberpreviousstatus']=='3' && isset($data['plumberpreviousexpirydate'])){
			$plumberpreviousexpirydate  = explode('-', $data['plumberpreviousexpirydate']);
			$plumberday 	= (isset($plumberpreviousexpirydate[0]) && $plumberpreviousexpirydate[0]=='00') ? date('d') : date('d', strtotime($data['plumberpreviousexpirydate']));
			$plumbermonth 	= (isset($plumberpreviousexpirydate[1]) && $plumberpreviousexpirydate[1]=='00') ? date('m') : date('m', strtotime($data['plumberpreviousexpirydate']));
			$plumberyear 	= date('Y', strtotime(date('Y-m-d H:i:s'). ' +365 days'));
			$request5['expirydate'] = $plumberyear.'-'.$plumbermonth.'-'.$plumberday;
		}
		
		if(isset($request5)){
			if(isset($data['user_id'])){
				$userid = $data['user_id'];	
				$users = $this->db->update('users', $request5, ['id' => $userid]);
			}
		}
		
		if((isset($usersdetail) || isset($usersplumber) || isset($usersaddress) || isset($skill) || isset($users)) && $this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return false;
		}
		else
		{
			$this->db->trans_commit();
			return $idarray;
		}
	}
	
	public function getSkillList($type, $requestdata=[])
	{
		$this->db->select('ups.*,qr.name as skillname');
		$this->db->from('users_plumber_skill ups');
		$this->db->join('qualificationroute qr', 'qr.id=ups.skills', 'left');
		
		if(isset($requestdata['id'])) 		$this->db->where('ups.id', $requestdata['id']);
		
		if($type=='count'){
			$result = $this->db->count_all_results();
		}else{
			$query = $this->db->get();
			
			if($type=='all') 		$result = $query->result_array();
			elseif($type=='row') 	$result = $query->row_array();
		}
		
		return $result;
	}
	
	public function deleteSkillList($id)
	{
		return $this->db->where('id', $id)->delete('users_plumber_skill');
	}
	
	public function plumberregistrationno($id, $value, $year, $counts='')
	{
		$row = $this->getList('row', ['id' => $id, 'type' => '3'], ['users', 'usersdetail', 'usersplumber']);
		
		if(isset($row['registration_no']) && $row['registration_no']!=''){
			$exploderegno = explode('/', $row['registration_no']);
			if(isset($exploderegno[0])) $count = $exploderegno[0];
		}else{
			$count 		= $this->db->select("MAX(SUBSTRING_INDEX(registration_no, '/', 1)) as maxcount")->get('users_plumber')->row_array();
			$count 		= ($counts=='') ? $count['maxcount']+1 : $counts;
			$checkcount = str_pad($count, 5, '0', STR_PAD_LEFT);
			$checkcount = ($checkcount[0]=='0') ? substr($checkcount, 1) : $checkcount;
			
			$checkregno = $this->getList('count', ['type' => '3', 'approvalstatus' => ['1'], 'searchregno' => $checkcount], ['users', 'usersdetail', 'usersplumber']);			
			if($checkregno > 0){				
				return $this->plumberregistrationno($id, $value, $year, ($count+1));
			}
		}
		
		if($value=='1'){
			$prefix = '/L';
		}elseif($value=='2'){
			$prefix = '/TA';
		}elseif($value=='3'){
			$prefix = '/TO';
		}else{
			$prefix = '/'.substr($year, 2, 2);
		}
		
		$regno = str_pad($count, 5, '0', STR_PAD_LEFT).$prefix;
		
		return ($regno[0]=='0') ? substr($regno, 1) : $regno;
	}
	
	public function performancestatus($type, $requestdata=[]){	
		
		if(!isset($requestdata['auditorstatement'])){
			$this->db->select('astat.id as id, astat.auditcompletedate as date, "Audit" as type, astat.coc_id as comments, astat.point as point, "" as attachment, astat.plumber_id as userid, "1" as flag, concat(ud.name, " ", ud.surname) as name, ud.file2 as image');
			$this->db->from('auditor_statement astat');		
			$this->db->join('users u', 'u.id=astat.plumber_id', 'inner');
			$this->db->join('users_detail ud', 'ud.user_id=u.id', 'inner');
			if(isset($requestdata['province']))	$this->db->join('users_address ua', 'ua.user_id=astat.plumber_id and ua.type="1" and ua.province="'.$requestdata['province'].'"', 'inner');
			if(isset($requestdata['city']))	$this->db->join('users_address ua', 'ua.user_id=astat.plumber_id and ua.type="1" and ua.city="'.$requestdata['city'].'"', 'inner');
			
			if(isset($requestdata['plumberid'])) $this->db->where('astat.plumber_id', $requestdata['plumberid']);
			if(isset($requestdata['archive'])) $this->db->where('astat.archive', $requestdata['archive']);
			if(isset($requestdata['date'])) $this->db->where('astat.auditcompletedate <', $requestdata['date']);
			$this->db->where(['astat.auditcomplete' => '1']);
			$compileresult[] = $this->db->get_compiled_select();
		}
		
		if(!isset($requestdata['cpd'])){
			$this->db->select('caf.id as id, caf.approved_date as date, "CPD" as type, caf.comments as comments, caf.points as point, caf.file1 as attachment, caf.user_id as userid, "2" as flag, concat(ud.name, " ", ud.surname) as name, ud.file2 as image');
			$this->db->from('cpd_activity_form caf');	
			$this->db->join('users u', 'u.id=caf.user_id', 'inner');
			$this->db->join('users_detail ud', 'ud.user_id=u.id', 'inner');
			if(isset($requestdata['province']))	$this->db->join('users_address ua', 'ua.user_id=caf.user_id and ua.type="1" and ua.province="'.$requestdata['province'].'"', 'inner');
			if(isset($requestdata['city']))	$this->db->join('users_address ua', 'ua.user_id=caf.user_id and ua.type="1" and ua.city="'.$requestdata['city'].'"', 'inner');
			
			if(isset($requestdata['plumberid'])) $this->db->where('caf.user_id', $requestdata['plumberid']);
			if(isset($requestdata['archive'])) $this->db->where('caf.archive', $requestdata['archive']);
			if(isset($requestdata['date'])) $this->db->where('caf.approved_date <', $requestdata['date']);
			$this->db->where(['caf.status' => '1']);
			$compileresult[] = $this->db->get_compiled_select();
		}
		
		if(!isset($requestdata['performancestatus'])){
			$this->db->select('ps.id as id, ps.date as date, "Admin" as type, ps.comments as comments, ps.point as point, ps.attachment as attachment, ps.plumber_id as userid, "3" as flag, concat(ud.name, " ", ud.surname) as name, ud.file2 as image');
			$this->db->from('performance_status ps');	
			$this->db->join('users u', 'u.id=ps.plumber_id', 'inner');
			$this->db->join('users_detail ud', 'ud.user_id=u.id', 'inner');
			if(isset($requestdata['province']))	$this->db->join('users_address ua', 'ua.user_id=ps.plumber_id and ua.type="1" and ua.province="'.$requestdata['province'].'"', 'inner');
			if(isset($requestdata['city']))	$this->db->join('users_address ua', 'ua.user_id=ps.plumber_id and ua.type="1" and ua.city="'.$requestdata['city'].'"', 'inner');
			
			if(isset($requestdata['plumberid'])) $this->db->where('ps.plumber_id', $requestdata['plumberid']);
			if(isset($requestdata['archive'])) $this->db->where('ps.archive', $requestdata['archive']);
			if(isset($requestdata['date'])) $this->db->where('ps.date <', $requestdata['date']);
			$this->db->where(['ps.status' => '1']);
			$compileresult[] = $this->db->get_compiled_select();
		}
		
		$compileresult = implode(' UNION ', $compileresult);
		if(isset($requestdata['plumbergroup'])) $query = "select group_concat(point order by date separator ',') as point, userid from (".$compileresult.") as data where 1=1 group by userid order by date asc";
		elseif(isset($requestdata['overall']) || isset($requestdata['province'])) $query = "select sum(point) as point, name, image, userid from (".$compileresult.") as data where 1=1 group by userid order by sum(point) desc";
		else $query = "select * from (".$compileresult.") as data where 1=1 ";
		
		if(isset($requestdata['limit'])) $query .= ' limit '.$requestdata['limit'].' ';
			
		if(isset($requestdata['search']['value']) && $requestdata['search']['value']!=''){
			$searchvalue = $requestdata['search']['value'];
			
			if(isset($requestdata['page'])){
				$page = $requestdata['page'];
				
				if($page=='plumberperformancestatus'){			
					$query .= ' and (DATE_FORMAT(date,"%d-%m-%Y") like "%'.$searchvalue.'%" or type like "%'.$searchvalue.'%" or comments like "%'.$searchvalue.'%" or point like "%'.$searchvalue.'%")';
				}				
			}
		}
		if(isset($requestdata['order']['0']['column']) && $requestdata['order']['0']['column']!='' && isset($requestdata['order']['0']['dir']) && $requestdata['order']['0']['dir']!=''){
			if(isset($requestdata['page'])){
				$page = $requestdata['page'];				
				if($page=='plumberperformancestatus'){
					$column = ['date', 'type', 'comments', 'point'];
					$query .= ' order by '.$column[$requestdata['order']['0']['column']].' '.$requestdata['order']['0']['dir'];
				}
			}
		}
		if($type!=='count' && isset($requestdata['start']) && isset($requestdata['length'])){
			$query .= ' limit '.$requestdata['start'].', '.$requestdata['length'];
		}
		
		$query = $this->db->query($query);
		
		if($type=='count'){
			$result = count($query->result_array());
		}else{
			
			if($type=='all') 		$result = $query->result_array();
			elseif($type=='row') 	$result = $query->row_array();
		}
		
		return $result;
	}
	
	public function performancestatusaction($data){	
		$this->db->trans_begin();
		
		if($data['flag']=='1'){
			$this->db->update('auditor_statement', ['archive' => '1'], ['id' => $data['id']]);
		}elseif($data['flag']=='2'){
			$this->db->update('cpd_activity_form', ['archive' => '1'], ['id' => $data['id']]);	
		}elseif($data['flag']=='3'){
			$this->db->update('performance_status', ['archive' => '1'], ['id' => $data['id']]);	
		}
		
		if($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return false;
		}
		else
		{
			$this->db->trans_commit();
			return true;
		}
	}
	
	// Admin diary section

	public function plumberdiary($data){

		$created_by = $this->getuserID();
		$datetime 	= date('Y-m-d H:i:s');


		if(isset($data['comments'])) 		$request1['comments'] 		= $data['comments'];
		if(isset($data['user_id'])) 		$request1['user_id'] 		= $data['user_id'];
		if(isset($created_by)) 				$request1['created_by'] 	= $created_by;
		if(isset($datetime)) 				$request1['created_at'] 	= $datetime;
		
		$result = $this->db->insert('users_comment',$request1);
		if ($result) {
			return true;
		}else{
			return false;
		}

	}
	
	public function qualificationvalidation($data){
		$result = $this->db->get_where('users_plumber_skill', ['user_id' => $data['id'], 'qualification' => $data['designation']])->row_array();
		if ($result) {
			return true;
		}else{
			return false;
		}
	}
	
	public function plumberidentitynumber($data){
		if(isset($data['idcard'])) 		$post = ['idcard' => $data['idcard']];
		if(isset($data['otheridcard'])) $post = ['otheridcard' => $data['otheridcard']];
		
		$result = $this->db->get_where('users_plumber', ['user_id !=' => $data['id']]+$post)->row_array();
		if ($result) {
			return true;
		}else{
			return false;
		}
	}

	public function plumberidentitynumberprofile($data){

		$this->db->select('ud.*, up.idcard, up.otheridcard, up.approval_status, u.formstatus');
		$this->db->from('users_plumber as up');
		$this->db->join('users_detail as ud', 'ud.user_id=up.user_id', 'left');
		$this->db->join('users as u', 'u.id=up.user_id', 'left');
		


		if(isset($data['idcard'])) 		$this->db->where('up.idcard', $data['idcard']);
		if(isset($data['otheridcard'])) $this->db->where('up.otheridcard', $data['otheridcard']);

		$this->db->where('u.formstatus', '1');
		// $this->db->group_start();
			$this->db->where('up.approval_status', '1');
			// $this->db->or_where('up.approval_status', '2');
		// $this->db->group_end();
		$this->db->where('up.user_id !=', $data['id']);

		$query 	= $this->db->get();
		$result = $query->row_array();
		// print_r($this->db->last_query());die;

		/*if(isset($data['idcard'])) 		$post = ['idcard' => $data['idcard']];
		if(isset($data['otheridcard'])) $post = ['otheridcard' => $data['otheridcard']];
		
		$result = $this->db->get_where('users_plumber', ['user_id !=' => $data['id']]+$post)->row_array();*/
		if ($result) {
			return true;
		}else{
			return false;
		}
	}
}