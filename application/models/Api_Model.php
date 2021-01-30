<?php

class Api_Model extends CC_Model
{
	/*public function getCOCList($type, $requestdata=[])
	{ 
		$coclog 			= 	[ 
									'cl.id cl_id','cl.log_date cl_log_date','cl.completion_date cl_completion_date','cl.order_no cl_order_no','cl.name cl_name','cl.address cl_address','cl.street cl_street','cl.number cl_number',
									'cl.province cl_province','cl.city cl_city','cl.suburb cl_suburb','cl.contact_no cl_contact_no','cl.alternate_no cl_alternate_no','cl.email cl_email','cl.installationtype cl_installationtype',
									'cl.specialisations cl_specialisations','cl.installation_detail cl_installation_detail','cl.file1 cl_file1','cl.file2 cl_file2','cl.agreement cl_agreement','cl.ncnotice cl_ncnotice','cl.ncemail cl_ncemail','cl.ncreason cl_ncreason','cl.status cl_status'
								];
							
		$auditorstatement 	= 	[ 
									'aas.id as_id','aas.audit_date as_audit_date','aas.workmanship as_workmanship','aas.plumber_verification as_plumber_verification','aas.coc_verification as_coc_verification','aas.hold as_hold','aas.reason as_reason','aas.auditcomplete as_auditcomplete','aas.refixcompletedate as_refixcompletedate'
								];
							
		$auditorreview 		= 	[ 
									'ar.incomplete_point ar_incomplete_point','ar.complete_point ar_complete_point','ar.cautionary_point ar_cautionary_point','ar.noaudit_point ar_noaudit_point','ar.refix_date ar_refix_date'
								];
		
		$auditorreview1 = [];
		if(isset($requestdata['page']) && (in_array($requestdata['page'], ['adminauditorstatement', 'auditorstatement', 'plumberauditorstatement', 'review']))){
			$auditorreview1 = 	[ 
									'ar1.refix_date ar1_refix_date'
								];
		}
		
		$this->db->select('
			sm.*, 
			u.id as u_id,
			u.type as u_type,
			concat(ud.name, " ", ud.surname) as u_name, 
			u.email as u_email,
			ud.mobile_phone as u_mobile,
			ud.work_phone as u_work,
			ud.file2 as u_file,
			ud.status as u_status,
			'.implode(',', $coclog).',
			p.name as cl_province_name,
			c.name as cl_city_name,
			s.name as cl_suburb_name,
			cd1.company as plumbercompany,
			up.registration_no as plumberregno, 
			pa.createddate as resellercreateddate,
			rd.company as resellercompany,
			rd.user_id as resellersid,
			concat(rd.name, " ", rd.surname) as resellername, 
			concat(ad.name, " ", ad.surname) as auditorname, 
			ad.mobile_phone as auditormobile, 
			a.email as auditoremail, 
			ad.status as auditorstatus, 
			'.implode(',', $auditorstatement).',
			'.implode(',', $auditorreview).',
			'.implode(',', $auditorreview1).'
		');
		$this->db->from('stock_management sm');
		$this->db->join('users_plumber up', 'up.user_id=sm.user_id', 'left');
		$this->db->join('users_detail ud', 'ud.user_id=sm.user_id', 'left');
		$this->db->join('users u', 'u.id=sm.user_id', 'left');
		$this->db->join('coc_log cl', 'cl.coc_id=sm.id', 'left'); // Coc Log
		$this->db->join('province p', 'p.id=cl.province', 'left'); // Coc Log
		$this->db->join('city c', 'c.id=cl.city', 'left'); // Coc Log
		$this->db->join('suburb s', 's.id=cl.suburb', 'left'); // Coc Log
		$this->db->join('users_detail cd1', 'cd1.user_id=cl.company_details', 'left'); // Plumber Details
		$this->db->join('plumberallocate pa', 'pa.stockid=sm.id', 'left'); // Reseller Allocate
		$this->db->join('users_detail rd', 'rd.user_id=pa.resellersid', 'left'); // Reseller Details
		$this->db->join('users_detail ad', 'ad.user_id=sm.auditorid', 'left'); // Auditor
		$this->db->join('users a', 'a.id=sm.auditorid', 'left'); // Auditor
		$this->db->join('auditor_statement aas', 'aas.coc_id=sm.id', 'left'); // Auditor Statement
		$this->db->join('auditor_review ar', 'ar.coc_id=sm.id', 'left'); // Auditor Review
		
		if((isset($requestdata['search']['value']) && $requestdata['search']['value']!='') || (isset($requestdata['order']['0']['column']) && $requestdata['order']['0']['column']!='' && isset($requestdata['order']['0']['dir']) && $requestdata['order']['0']['dir']!='')){
			$this->db->join('custom c1', 'c1.c_id=sm.coc_status and c1.type="1"', 'left');
			$this->db->join('custom c2', 'c2.c_id=sm.audit_status and c2.type="2"', 'left');
			$this->db->join('custom c3', 'c3.c_id=sm.type and c3.type="3"', 'left');
		}
		
		if(isset($requestdata['page']) && (in_array($requestdata['page'], ['adminauditorstatement', 'auditorstatement', 'plumberauditorstatement', 'review']))){
			$this->db->join('auditor_review ar1', 'ar1.coc_id=sm.id and ar1.reviewtype="1"', 'left');
		}
		
		if(isset($requestdata['startrange']) && $requestdata['startrange']!='')				$this->db->where('sm.id >=', $requestdata['startrange']);
		if(isset($requestdata['endrange']) && $requestdata['endrange']!='')					$this->db->where('sm.id <=', $requestdata['endrange']);
		if(isset($requestdata['auditstatus']) && count($requestdata['auditstatus']) > 0){
			$this->db->where_in('sm.audit_status', $requestdata['auditstatus']);
			$this->db->where('sm.coc_status', '2');
			$this->db->where('sm.auditorid !=', '0');
		}
		if(isset($requestdata['coctype']) && count($requestdata['coctype']) > 0)			$this->db->where_in('sm.type', $requestdata['coctype']);
		if(isset($requestdata['startdate']) && $requestdata['startdate']!='')				$this->db->where('sm.purchased_at >=', date('Y-m-d', strtotime($requestdata['startdate'])));
		if(isset($requestdata['enddate']) && $requestdata['enddate']!='')					$this->db->where('sm.purchased_at <=', date('Y-m-d', strtotime($requestdata['enddate'])));
		if(isset($requestdata['province']) && $requestdata['province']!='')					$this->db->where('cl.province', $requestdata['province']);
		if(isset($requestdata['city']) && $requestdata['city']!='')							$this->db->where('cl.city', $requestdata['city']);
		if(isset($requestdata['auditorid']) && $requestdata['auditorid']!='')				$this->db->where('sm.auditorid', $requestdata['auditorid']);
		if(isset($requestdata['noaudit']))													$this->db->where('sm.auditorid !=', '');
		if(isset($requestdata['auditcomplete']))											$this->db->where('aas.auditcomplete', '1');
		
		if(isset($requestdata['user_id']) && $requestdata['user_id']!='')					$this->db->where('sm.user_id', $requestdata['user_id']);
		if(isset($requestdata['id']) && $requestdata['id']!='')								$this->db->where('sm.id', $requestdata['id']);
		
		if(isset($requestdata['coc_status']) && count($requestdata['coc_status']) > 0){
			$this->db->group_start();
				$this->db->where_in('sm.coc_status', $requestdata['coc_status']);
				$this->db->or_where_in('sm.coc_orders_status', $requestdata['coc_status']);
			$this->db->group_end();
		}
		
		if(isset($requestdata['allocated_id'])){
			$this->db->group_start();
				$this->db->where('sm.user_id', $requestdata['allocated_id']);
				$this->db->or_where('sm.allocatedby', $requestdata['allocated_id']);
			$this->db->group_end();
		}

		if(isset($requestdata['monthrange'])){
			$monthArray 	=	explode('-', $requestdata['monthArray']);
			$this->db->where('YEAR(sm.purchased_at) = '.$monthArray[0].' AND '.'MONTH(sm.purchased_at) = '.$monthArray[1]);	
		}
		
		if(isset($requestdata['search']['value']) && $requestdata['search']['value']!=''){
			$searchvalue = $requestdata['search']['value'];
			
			if(isset($requestdata['page'])){
				$page = $requestdata['page'];
				$this->db->group_start();
					if($page=='plumbercocstatement'){					
						$this->db->like('sm.id', $searchvalue, 'both');
						$this->db->or_like('c1.name', $searchvalue, 'both');
						$this->db->or_like('DATE_FORMAT(sm.allocation_date,"%d-%m-%Y")', $searchvalue, 'both');
						$this->db->or_like('DATE_FORMAT(cl.log_date,"%d-%m-%Y")', $searchvalue, 'both');
						$this->db->or_like('c3.name', $searchvalue, 'both');
						$this->db->or_like('cl.name', $searchvalue, 'both');
						$this->db->or_like('cl.address', $searchvalue, 'both');
						$this->db->or_like('cd1.company', $searchvalue, 'both');					
						$this->db->or_like('rd.name', $searchvalue, 'both');					
					}elseif($page=='admincocdetails'){
						$this->db->like('sm.id', $searchvalue, 'both');
						$this->db->or_like('c3.name', $searchvalue, 'both');
						$this->db->or_like('c1.name', $searchvalue, 'both');
						$this->db->or_like('concat(ud.name, " ", ud.surname)', $searchvalue, 'both');
						$this->db->or_like('concat(rd.name, " ", rd.surname)', $searchvalue, 'both');
						$this->db->or_like('concat(ad.name, " ", ad.surname)', $searchvalue, 'both');							
					}elseif($page=='auditorstatement'){
						$this->db->like('sm.id', $searchvalue, 'both');
						$this->db->or_like('c1.name', $searchvalue, 'both');
						$this->db->or_like('concat(ud.name, " ", ud.surname)', $searchvalue, 'both');		
						$this->db->or_like('ud.mobile_phone', $searchvalue, 'both');
						$this->db->or_like('DATE_FORMAT(sm.audit_allocation_date,"%d-%m-%Y")', $searchvalue, 'both');
						$this->db->or_like('DATE_FORMAT(ar1.refix_date,"%d-%m-%Y")', $searchvalue, 'both');
						$this->db->or_like('DATE_FORMAT(aas.refixcompletedate,"%d-%m-%Y")', $searchvalue, 'both');
						$this->db->or_like('s.name', $searchvalue, 'both');		
						$this->db->or_like('cl.name', $searchvalue, 'both');		
						$this->db->or_like('cl.contact_no', $searchvalue, 'both');		
					}elseif($page=='plumberauditorstatement'){
						$this->db->like('sm.id', $searchvalue, 'both');
						$this->db->or_like('c2.name', $searchvalue, 'both');
						$this->db->or_like('cl.name', $searchvalue, 'both');		
						$this->db->or_like('cl.address', $searchvalue, 'both');		
						$this->db->or_like('DATE_FORMAT(ar1.refix_date,"%d-%m-%Y")', $searchvalue, 'both');
						$this->db->or_like('DATE_FORMAT(aas.refixcompletedate,"%d-%m-%Y")', $searchvalue, 'both');
						$this->db->or_like('DATE_FORMAT(sm.audit_allocation_date,"%d-%m-%Y")', $searchvalue, 'both');
						$this->db->or_like('ad.name', $searchvalue, 'both');		
					}elseif($page=='adminauditorstatement'){
						$this->db->like('sm.id', $searchvalue, 'both');
						$this->db->or_like('c1.name', $searchvalue, 'both');
						$this->db->or_like('ad.name', $searchvalue, 'both');		
						$this->db->or_like('ad.mobile_phone', $searchvalue, 'both');		
						$this->db->or_like('DATE_FORMAT(sm.audit_allocation_date,"%d-%m-%Y")', $searchvalue, 'both');
						$this->db->or_like('DATE_FORMAT(ar1.refix_date,"%d-%m-%Y")', $searchvalue, 'both');
						$this->db->or_like('DATE_FORMAT(aas.refixcompletedate,"%d-%m-%Y")', $searchvalue, 'both');
					}elseif($page=='auditorprofile'){
						$this->db->like('sm.id', $searchvalue, 'both');
						$this->db->or_like('DATE_FORMAT(aas.audit_date,"%d-%m-%Y")', $searchvalue, 'both');
						$this->db->or_like('concat(ud.name, " ", ud.surname)', $searchvalue, 'both');
						$this->db->or_like('s.name', $searchvalue, 'both');		
						$this->db->or_like('c.name', $searchvalue, 'both');		
						$this->db->or_like('p.name', $searchvalue, 'both');		
						$this->db->or_like('ar.incomplete_point', $searchvalue, 'both');		
						$this->db->or_like('ar.complete_point', $searchvalue, 'both');		
						$this->db->or_like('ar.cautionary_point', $searchvalue, 'both');		
						$this->db->or_like('ar.noaudit_point', $searchvalue, 'both');			
					}
				$this->db->group_end();
			}
		}
		if(isset($requestdata['order']['0']['column']) && $requestdata['order']['0']['column']!='' && isset($requestdata['order']['0']['dir']) && $requestdata['order']['0']['dir']!=''){
			if(isset($requestdata['page'])){
				$page = $requestdata['page'];				
				if($page=='plumbercocstatement'){
					$column = ['sm.id', 'c1.name', 'sm.allocation_date', 'cl.log_date', 'c3.name', 'cl.name', 'cl.address', 'cd1.company', 'rd.name'];
				}elseif($page=='admincocdetails'){
					$column = ['sm.id', 'c3.name', 'c1.name', 'ud.name', 'rd.name', 'ad.name'];
				}elseif($page=='auditorstatement'){
					$column = ['sm.id', 'sm.audit_status', 'ud.name', 'ud.mobile_phone', 'sm.audit_allocation_date', 'ar1.refix_date', 'aas.refixcompletedate', 's.name', 'cl.name', 'cl.contact_no'];
				}elseif($page=='plumberauditorstatement'){
					$column = ['sm.id', 'c2.name', 'cl.name', 'cl.address', 'ar1.refix_date', 'aas.refixcompletedate', 'sm.audit_allocation_date', 'ad.name'];
				}elseif($page=='adminauditorstatement'){
					$column = ['sm.id', 'c1.name', 'ad.name', 'ad.mobile_phone', 'sm.audit_allocation_date', 'ar1.refix_date', 'aas.refixcompletedate'];
				}elseif($page=='auditorprofile'){
					$column = ['sm.id', 'aas.audit_date', 'ud.name', 's.name', 'c.name', 'p.name', 'ar.incomplete_point', 'ar.complete_point', 'ar.cautionary_point', 'ar.noaudit_point'];
				}
				
				$this->db->order_by($column[$requestdata['order']['0']['column']], $requestdata['order']['0']['dir']);
			}
		}
		if($type!=='count' && isset($requestdata['start']) && isset($requestdata['length'])){
			$this->db->limit($requestdata['length'], $requestdata['start']);
		}
		if (isset($requestdata['api_data']) && $requestdata['api_data'] =='plumber_coc_statement_api') {
			$this->db->order_by('sm.coc_status', 'DESC');
		}
		
		$this->db->group_by('sm.id');
		if (isset($requestdata['api_data']) && $requestdata['api_data'] =='auditstatement_auditor') {
			$this->db->order_by("sm.id", "DESC");
		}
		
		if($type=='count'){
			$result = $this->db->count_all_results();
		}else{
			$query = $this->db->get();
			
			if($type=='all') 		$result = $query->result_array();
			elseif($type=='row') 	$result = $query->row_array();
		}
		
		return $result;		
	}*/

	public function getCOCList($type, $requestdata=[], $querydata=[])
	{ 
		$select 	= [];
		$select[] 	= 'sm.*';
		
		if(in_array('users', $querydata)){
			$users 				= 	['u.id as u_id, u.type as u_type, u.email as u_email'];								
			$select[] 			= 	implode(',', $users);
		}
		
		if(in_array('usersdetail', $querydata)){
			$usersdetail 		= 	['concat(ud.name, " ", ud.surname) as u_name,  ud.mobile_phone as u_mobile, ud.work_phone as u_work, ud.file2 as u_file, ud.status as u_status'];								
			$select[] 			= 	implode(',', $usersdetail);
		}
		
		if(in_array('usersplumber', $querydata)){
			$usersplumber 		= 	['up.registration_no as plumberregno'];								
			$select[] 			= 	implode(',', $usersplumber);
		}
		
		if(in_array('coclog', $querydata)){
			$coclog 			= 	[ 
										'cl.id cl_id','cl.log_date cl_log_date','cl.completion_date cl_completion_date','cl.order_no cl_order_no','cl.name cl_name','cl.address cl_address','cl.street cl_street','cl.number cl_number',
										'cl.province cl_province','cl.city cl_city','cl.suburb cl_suburb','cl.contact_no cl_contact_no','cl.alternate_no cl_alternate_no','cl.email cl_email','cl.installationtype cl_installationtype',
										'cl.specialisations cl_specialisations','cl.installation_detail cl_installation_detail','cl.file1 cl_file1','cl.file2 cl_file2','cl.agreement cl_agreement','cl.ncnotice cl_ncnotice','cl.ncemail cl_ncemail','cl.ncreason cl_ncreason','cl.status cl_status'
									];								
			$select[] 		= 		implode(',', $coclog);
		}
		
		if(in_array('coclogprovince', $querydata)){
			$coclogprovince 	= 	['p.name as cl_province_name'];								
			$select[] 			= 	implode(',', $coclogprovince);
		}
		
		if(in_array('coclogcity', $querydata)){
			$coclogcity 		= 	['c.name as cl_city_name'];								
			$select[] 			= 	implode(',', $coclogcity);
		}
		
		if(in_array('coclogsuburb', $querydata)){
			$coclogsuburb 		= 	['s.name as cl_suburb_name'];								
			$select[] 			= 	implode(',', $coclogsuburb);
		}
		
		if(in_array('coclogcompany', $querydata)){
			$coclogcompany 		= 	['cd1.company as plumbercompany'];								
			$select[] 			= 	implode(',', $coclogcompany);
		}
		
		if(in_array('reseller', $querydata)){
			$reseller 			= 	['pa.createddate as resellercreateddate'];								
			$select[] 			= 	implode(',', $reseller);
		}
		
		if(in_array('resellerdetails', $querydata)){
			$resellerdetails 	= 	['rd.company as resellercompany, concat(rd.name, " ", rd.surname) as resellername, rd.user_id as resellersid'];								
			$select[] 			= 	implode(',', $resellerdetails);
		}
		
		if(in_array('auditor', $querydata)){
			$auditor 			= 	['a.email as auditoremail'];								
			$select[] 			= 	implode(',', $auditor);
		}
		
		if(in_array('auditordetails', $querydata)){
			$auditordetails 	= 	['concat(ad.name, " ", ad.surname) as auditorname, ad.mobile_phone as auditormobile, ad.status as auditorstatus'];								
			$select[] 			= 	implode(',', $auditordetails);
		}
		
		if(in_array('auditorstatement', $querydata)){
			$auditorstatement 	= 	[ 
										'aas.id as_id','aas.audit_date as_audit_date','aas.workmanship as_workmanship','aas.plumber_verification as_plumber_verification','aas.coc_verification as_coc_verification','aas.hold as_hold','aas.reason as_reason','aas.auditcomplete as_auditcomplete','aas.refixcompletedate as_refixcompletedate'
									];						
			$select[] 			= 	implode(',', $auditorstatement);
		}
		
		if(in_array('auditorreview', $querydata)){
			$auditorreview 		= 	[ 
										'ar.incomplete_point ar_incomplete_point','ar.complete_point ar_complete_point','ar.cautionary_point ar_cautionary_point','ar.noaudit_point ar_noaudit_point','ar.refix_date ar_refix_date'
									];						
			$select[] 			= 	implode(',', $auditorreview);
		}
		
		if(isset($requestdata['page']) && (in_array($requestdata['page'], ['adminauditorstatement', 'auditorstatement', 'plumberauditorstatement', 'review']))){
			$auditorreview1 	= 	[ 
										'ar1.refix_date ar1_refix_date'
									];
			$select[] 			= 	implode(',', $auditorreview1);
		}
		
		$this->db->select(implode(',', $select));
		$this->db->from('stock_management sm');
		if(in_array('users', $querydata))				$this->db->join('users u', 'u.id=sm.user_id', 'left'); // Users
		if(in_array('usersdetail', $querydata))			$this->db->join('users_detail ud', 'ud.user_id=sm.user_id', 'left'); // Users Detail
		if(in_array('usersplumber', $querydata))		$this->db->join('users_plumber up', 'up.user_id=sm.user_id', 'left'); // Users Plumber
		if(in_array('coclog', $querydata)) 				$this->db->join('coc_log cl', 'cl.coc_id=sm.id', 'left'); // Coc Log
		if(in_array('coclogprovince', $querydata)) 		$this->db->join('province p', 'p.id=cl.province', 'left'); // Coc Log Province
		if(in_array('coclogcity', $querydata)) 			$this->db->join('city c', 'c.id=cl.city', 'left'); // Coc Log City
		if(in_array('coclogsuburb', $querydata)) 		$this->db->join('suburb s', 's.id=cl.suburb', 'left'); // Coc Log Suburb
		if(in_array('coclogcompany', $querydata))		$this->db->join('users_detail cd1', 'cd1.user_id=cl.company_details', 'left'); // Coc Log Company Details
		if(in_array('reseller', $querydata))			$this->db->join('plumberallocate pa', 'pa.stockid=sm.id', 'left'); // Reseller
		if(in_array('resellerdetails', $querydata))		$this->db->join('users_detail rd', 'rd.user_id=pa.resellersid', 'left'); // Reseller Details
		if(in_array('auditor', $querydata))				$this->db->join('users a', 'a.id=sm.auditorid', 'left'); // Auditor
		if(in_array('auditordetails', $querydata))		$this->db->join('users_detail ad', 'ad.user_id=sm.auditorid', 'left'); // Auditor Details
		if(in_array('auditorstatement', $querydata))	$this->db->join('auditor_statement aas', 'aas.coc_id=sm.id', 'left'); // Auditor Statement
		if(in_array('auditorreview', $querydata))		$this->db->join('auditor_review ar', 'ar.coc_id=sm.id', 'left'); // Auditor Review
		if((isset($requestdata['search']['value']) && $requestdata['search']['value']!='') || (isset($requestdata['order']['0']['column']) && $requestdata['order']['0']['column']!='' && isset($requestdata['order']['0']['dir']) && $requestdata['order']['0']['dir']!='')){
			$this->db->join('custom c1', 'c1.c_id=sm.coc_status and c1.type="1"', 'left');
			$this->db->join('custom c2', 'c2.c_id=sm.audit_status and c2.type="2"', 'left');
			$this->db->join('custom c3', 'c3.c_id=sm.type and c3.type="3"', 'left');
		}
		if(isset($requestdata['page']) && (in_array($requestdata['page'], ['adminauditorstatement', 'auditorstatement', 'plumberauditorstatement', 'review']))){
			$this->db->join('auditor_review ar1', 'ar1.coc_id=sm.id and ar1.reviewtype="1"', 'left');
		}
		
		if(isset($requestdata['startrange']) && $requestdata['startrange']!='')				$this->db->where('sm.id >=', $requestdata['startrange']);
		if(isset($requestdata['endrange']) && $requestdata['endrange']!='')					$this->db->where('sm.id <=', $requestdata['endrange']);
		if(isset($requestdata['coctype']) && count($requestdata['coctype']) > 0)			$this->db->where_in('sm.type', $requestdata['coctype']);
		if(isset($requestdata['cocstatus']) && count($requestdata['cocstatus']) > 0)		$this->db->where_in('sm.coc_status', $requestdata['cocstatus']);
		if(isset($requestdata['nococstatus']) && count($requestdata['nococstatus']) > 0)	$this->db->where_not_in('sm.coc_status', $requestdata['nococstatus']);
		if(isset($requestdata['startdate']) && $requestdata['startdate']!='')				$this->db->where('sm.purchased_at >=', date('Y-m-d', strtotime($requestdata['startdate'])));
		if(isset($requestdata['enddate']) && $requestdata['enddate']!='')					$this->db->where('sm.purchased_at <=', date('Y-m-d', strtotime($requestdata['enddate'])));
		if(isset($requestdata['province']) && $requestdata['province']!='')					$this->db->where('cl.province', $requestdata['province']);
		if(isset($requestdata['city']) && $requestdata['city']!='')							$this->db->where('cl.city', $requestdata['city']);
		if(isset($requestdata['auditorid']) && $requestdata['auditorid']!='')				$this->db->where('sm.auditorid', $requestdata['auditorid']);
		if(isset($requestdata['noaudit']))													$this->db->where('sm.auditorid !=', '');
		if(isset($requestdata['auditcomplete']))											$this->db->where('aas.auditcomplete', '1');		
		if(isset($requestdata['user_id']) && $requestdata['user_id']!='')					$this->db->where('sm.user_id', $requestdata['user_id']);
		if(isset($requestdata['id']) && $requestdata['id']!='')								$this->db->where('sm.id', $requestdata['id']);
		if(isset($requestdata['auditstatus']) && count($requestdata['auditstatus']) > 0){
			$this->db->where_in('sm.audit_status', $requestdata['auditstatus']);
			$this->db->where('sm.coc_status', '2');
			$this->db->where('sm.auditorid !=', '0');
		}		
		if(isset($requestdata['coc_status']) && count($requestdata['coc_status']) > 0){
			$this->db->group_start();
				$this->db->where_in('sm.coc_status', $requestdata['coc_status']);
				$this->db->or_where_in('sm.coc_orders_status', $requestdata['coc_status']);
			$this->db->group_end();
		}		
		if(isset($requestdata['allocated_id'])){
			$this->db->group_start();
				$this->db->where('sm.user_id', $requestdata['allocated_id']);
				$this->db->or_where('sm.allocatedby', $requestdata['allocated_id']);
			$this->db->group_end();
		}
		if(isset($requestdata['monthrange'])){
			$monthArray 	=	explode('-', $requestdata['monthArray']);
			$this->db->where('YEAR(sm.purchased_at) = '.$monthArray[0].' AND '.'MONTH(sm.purchased_at) = '.$monthArray[1]);	
		}
		
		if(isset($requestdata['search']['value']) && $requestdata['search']['value']!=''){
			$searchvalue = $requestdata['search']['value'];
			
			if(isset($requestdata['page'])){
				$page = $requestdata['page'];
				$this->db->group_start();
					if($page=='plumbercocstatement'){					
						$this->db->like('sm.id', $searchvalue, 'both');
						$this->db->or_like('c1.name', $searchvalue, 'both');
						$this->db->or_like('DATE_FORMAT(sm.allocation_date,"%d-%m-%Y")', $searchvalue, 'both');
						$this->db->or_like('DATE_FORMAT(cl.log_date,"%d-%m-%Y")', $searchvalue, 'both');
						$this->db->or_like('c3.name', $searchvalue, 'both');
						$this->db->or_like('cl.name', $searchvalue, 'both');
						$this->db->or_like('cl.address', $searchvalue, 'both');
						$this->db->or_like('cd1.company', $searchvalue, 'both');					
						// $this->db->or_like('rd.name', $searchvalue, 'both');					
					}elseif($page=='admincocdetails'){
						$this->db->like('sm.id', $searchvalue, 'both');
						$this->db->or_like('c3.name', $searchvalue, 'both');
						$this->db->or_like('c1.name', $searchvalue, 'both');
						$this->db->or_like('concat(ud.name, " ", ud.surname)', $searchvalue, 'both');
						$this->db->or_like('concat(rd.name, " ", rd.surname)', $searchvalue, 'both');
						$this->db->or_like('concat(ad.name, " ", ad.surname)', $searchvalue, 'both');							
					}elseif($page=='auditorstatement'){
						$this->db->like('sm.id', $searchvalue, 'both');
						$this->db->or_like('c1.name', $searchvalue, 'both');
						$this->db->or_like('concat(ud.name, " ", ud.surname)', $searchvalue, 'both');		
						$this->db->or_like('ud.mobile_phone', $searchvalue, 'both');
						$this->db->or_like('DATE_FORMAT(sm.audit_allocation_date,"%d-%m-%Y")', $searchvalue, 'both');
						$this->db->or_like('DATE_FORMAT(ar1.refix_date,"%d-%m-%Y")', $searchvalue, 'both');
						$this->db->or_like('DATE_FORMAT(aas.refixcompletedate,"%d-%m-%Y")', $searchvalue, 'both');
						$this->db->or_like('s.name', $searchvalue, 'both');		
						$this->db->or_like('cl.name', $searchvalue, 'both');		
						$this->db->or_like('cl.contact_no', $searchvalue, 'both');		
					}elseif($page=='plumberauditorstatement'){
						$this->db->like('sm.id', $searchvalue, 'both');
						$this->db->or_like('c2.name', $searchvalue, 'both');
						$this->db->or_like('cl.name', $searchvalue, 'both');		
						$this->db->or_like('cl.address', $searchvalue, 'both');		
						$this->db->or_like('DATE_FORMAT(ar1.refix_date,"%d-%m-%Y")', $searchvalue, 'both');
						$this->db->or_like('DATE_FORMAT(aas.refixcompletedate,"%d-%m-%Y")', $searchvalue, 'both');
						$this->db->or_like('DATE_FORMAT(sm.audit_allocation_date,"%d-%m-%Y")', $searchvalue, 'both');
						$this->db->or_like('ad.name', $searchvalue, 'both');		
					}elseif($page=='adminauditorstatement'){
						$this->db->like('sm.id', $searchvalue, 'both');
						$this->db->or_like('c1.name', $searchvalue, 'both');
						$this->db->or_like('ad.name', $searchvalue, 'both');		
						$this->db->or_like('ad.mobile_phone', $searchvalue, 'both');		
						$this->db->or_like('DATE_FORMAT(sm.audit_allocation_date,"%d-%m-%Y")', $searchvalue, 'both');
						$this->db->or_like('DATE_FORMAT(ar1.refix_date,"%d-%m-%Y")', $searchvalue, 'both');
						$this->db->or_like('DATE_FORMAT(aas.refixcompletedate,"%d-%m-%Y")', $searchvalue, 'both');
					}elseif($page=='auditorprofile'){
						$this->db->like('sm.id', $searchvalue, 'both');
						$this->db->or_like('DATE_FORMAT(aas.audit_date,"%d-%m-%Y")', $searchvalue, 'both');
						$this->db->or_like('concat(ud.name, " ", ud.surname)', $searchvalue, 'both');
						$this->db->or_like('s.name', $searchvalue, 'both');		
						$this->db->or_like('c.name', $searchvalue, 'both');		
						$this->db->or_like('p.name', $searchvalue, 'both');		
						$this->db->or_like('ar.incomplete_point', $searchvalue, 'both');		
						$this->db->or_like('ar.complete_point', $searchvalue, 'both');		
						$this->db->or_like('ar.cautionary_point', $searchvalue, 'both');		
						$this->db->or_like('ar.noaudit_point', $searchvalue, 'both');			
					}
				$this->db->group_end();
			}
		}
		
		if(isset($requestdata['order']['0']['column']) && $requestdata['order']['0']['column']!='' && isset($requestdata['order']['0']['dir']) && $requestdata['order']['0']['dir']!=''){
			if(isset($requestdata['page'])){
				$page = $requestdata['page'];				
				if($page=='plumbercocstatement'){
					$column = ['sm.id', 'c1.name', 'sm.allocation_date', 'cl.log_date', 'c3.name', 'cl.name', 'cl.address', 'cd1.company', 'rd.name'];
				}elseif($page=='admincocdetails'){
					$column = ['sm.id', 'c3.name', 'c1.name', 'ud.name', 'rd.name', 'ad.name'];
				}elseif($page=='auditorstatement'){
					$column = ['sm.id', 'sm.audit_status', 'ud.name', 'ud.mobile_phone', 'sm.audit_allocation_date', 'ar1.refix_date', 'aas.refixcompletedate', 's.name', 'cl.name', 'cl.contact_no'];
				}elseif($page=='plumberauditorstatement'){
					$column = ['sm.id', 'c2.name', 'cl.name', 'cl.address', 'ar1.refix_date', 'aas.refixcompletedate', 'sm.audit_allocation_date', 'ad.name'];
				}elseif($page=='adminauditorstatement'){
					$column = ['sm.id', 'c1.name', 'ad.name', 'ad.mobile_phone', 'sm.audit_allocation_date', 'ar1.refix_date', 'aas.refixcompletedate'];
				}elseif($page=='auditorprofile'){
					$column = ['sm.id', 'aas.audit_date', 'ud.name', 's.name', 'c.name', 'p.name', 'ar.incomplete_point', 'ar.complete_point', 'ar.cautionary_point', 'ar.noaudit_point'];
				}
				
				$this->db->order_by($column[$requestdata['order']['0']['column']], $requestdata['order']['0']['dir']);
			}
		}
		if($type!=='count' && isset($requestdata['start']) && isset($requestdata['length'])){
			$this->db->limit($requestdata['length'], $requestdata['start']);
		}
		if (isset($requestdata['api_data']) && $requestdata['api_data'] =='plumber_coc_statement_api') {
			$this->db->order_by('sm.coc_status', 'DESC');
		}
		
		$this->db->group_by('sm.id');
		if (isset($requestdata['api_data']) && $requestdata['api_data'] =='auditstatement_auditor') {
			$this->db->order_by("sm.id", "DESC");
		}
		
		if($type=='count'){
			$result = $this->db->count_all_results();
		}else{
			$query = $this->db->get();
			
			if($type=='all') 		$result = $query->result_array();
			elseif($type=='row') 	$result = $query->row_array();
		}
		
		return $result;		
	}

	public function AuditorgetList($type, $requestdata=[])
	{ 		
		$user 			= ['u.id as id', 'u.email','u.type','u.status as usstatus', 'u.password_raw'];
		$usersdetail 	= ['ud.id as userdetailid','ud.name','ud.surname','ud.company_name','ud.reg_no','ud.vat_no','ud.vat_vendor','ud.billing_email','ud.billing_contact','ud.mobile_phone','ud.work_phone','ud.file1','ud.file2','ud.identity_no'];		
		$useraddress 	= ['ua.id as useraddressid', 'ua.address', 'ua.province', 'ua.city', 'ua.suburb', 'ua.postal_code'];

		$userbank 		= ['ub.id as userbankid', 'ub.bank_name', 'ub.branch_code', 'ub.account_name', 'ub.account_no', 'account_type'];
		$auditor 		= ['ub1.id as available', 'ub1.user_id', 'ub1.allocation_allowed', 'ub1.status'];

		$this->db->select('
			'.implode(',', $user).',
			'.implode(',', $usersdetail).',
			'.implode(',', $useraddress).',
			'.implode(',', $auditor).',
			'.implode(',', $userbank).',
			group_concat(concat_ws("@@@", uaa.id, uaa.province, uaa.city, uaa.suburb, p.name, c.name, s.name, uaa.city) separator "@-@") as areas'
		);
		$this->db->from('users as u');
		$this->db->join('users_detail as ud','ud.user_id=u.id', 'left');
		$this->db->join('users_address as ua', 'ua.user_id=u.id and ua.type="3"', 'left');		
		$this->db->join('users_bank as ub', 'ub.user_id=u.id', 'left');
		$this->db->join('auditor_availability as ub1', 'ub1.user_id=u.id', 'left');
		$this->db->join('users_auditor_area as uaa', 'uaa.user_id=u.id', 'left');
		$this->db->join('province as p', 'p.id=uaa.province', 'left');
		$this->db->join('city as c', 'c.id=uaa.city', 'left');
		$this->db->join('suburb as s', 's.id=uaa.suburb', 'left');
		
		if(isset($requestdata['id'])) 			$this->db->where('u.id', $requestdata['id']);
		//if(isset($requestdata['status'])) 		$this->db->where_in('u.status', $requestdata['status']);

		if($type=='count'){
			$result = $this->db->count_all_results();
		}
		else
		{
			$query = $this->db->get();
			
			if($type=='all') 		$result = $query->result_array();
			elseif($type=='row') 	$result = $query->row_array();
		}
		
		return $result;	
	}

	public function AuditorAreagetList($type, $requestdata=[])
	{
		$this->db->select('uaa.*');
		$this->db->from('users_auditor_area as uaa');

		if(isset($requestdata['id'])) 			$this->db->where('uaa.id', $requestdata['id']);
		if(isset($requestdata['province'])) 	$this->db->where('uaa.province', $requestdata['province']);
		if(isset($requestdata['city'])) 		$this->db->where('uaa.city', $requestdata['city']);
		if(isset($requestdata['suburb'])) 		$this->db->where('uaa.suburb', $requestdata['suburb']);
		if(isset($requestdata['userid'])) 		$this->db->where('uaa.user_id', $requestdata['userid']);

		if($type=='count'){
			$result = $this->db->count_all_results();
		}
		else
		{
			$query = $this->db->get();
			
			if($type=='all') 		$result = $query->result_array();
			elseif($type=='row') 	$result = $query->row_array();
		}
		
		return $result;	
	}

	public function auditorAction($data)
	{
		$this->db->trans_begin();

		$datetime		= 	date('Y-m-d H:i:s');
		$id				= 	$data['user_id'];

		if(isset($data['email'])) 				$request1['email'] 				= $data['email'];
		if(isset($data['password'])) 			$request1['password_raw'] 		= $data['password'];
		if(isset($data['password'])) 			$request1['password'] 			= md5($data['password']);
		if(isset($data['status'])) 				$request1['status'] 			= $data['status'];		

		
		if(isset($request1)){
			if($id==''){
				$request1['type']	 		= '5';
				$request1['created_at']		= 	date('Y-m-d H:i:s');

				$userdata = $this->db->insert('users', $request1);
				$id = $this->db->insert_id();
			}else{
				$request1['updated_at']		= 	date('Y-m-d H:i:s');
				$userdata = $this->db->update('users', $request1, ['id' => $id]);
			}
		}

		if(isset($id)) 							$request0['user_id'] 			= $id;
		if(isset($data['allowed'])) 			$request0['allocation_allowed']	= $data['allowed'];
		if(isset($data['auditstatus'])) 			$request0['status'] 			= $data['auditstatus'];

		if (isset($request0)) {
			$auditoravaid			= $data['auditoravaid'];
			if($auditoravaid==''){
				$request0['created_at'] 		= $datetime;
				$auditoravaid1 = $this->db->insert('auditor_availability', $request0);
			}else{
				$request0['updated_at'] 		= $datetime;
				$auditoravaid1 = $this->db->update('auditor_availability', $request0, ['id' => $auditoravaid]);
			}
		}

		
		if(isset($data['name'])) 				$request2['name'] 				= $data['name'];
		if(isset($data['surname'])) 			$request2['surname'] 			= $data['surname'];
		if(isset($data['company_name'])) 		$request2['company_name'] 		= $data['company_name'];
		if(isset($data['reg_no'])) 				$request2['reg_no'] 			= $data['reg_no']; 
		if(isset($data['vat_no'])) 				$request2['vat_no'] 			= $data['vat_no'];

		$request2['vat_vendor'] 				= isset($data['vat_vendor']) ? $data['vat_vendor'] : '0';
		
		if(isset($data['billing_email'])) 		$request2['billing_email'] 		= $data['billing_email'];
		if(isset($data['billing_contact'])) 	$request2['billing_contact'] 	= $data['billing_contact'];		
		if(isset($data['work_phone'])) 			$request2['work_phone'] 		= $data['work_phone'];
		if(isset($data['mobile_phone'])) 		$request2['mobile_phone'] 		= $data['mobile_phone'];	
		if(isset($data['file1'])) 				$request2['file1'] 				= $data['file1'];
		if(isset($data['file2'])) 				$request2['file2'] 				= $data['file2'];
		if(isset($data['idno'])) 				$request2['identity_no'] 		= $data['idno'];
		
		if(isset($request2)){
			$request2['user_id'] 	= $id;
			$userdetailid			= $data['userdetailid'];

			if($userdetailid==''){
				$userdetaildata = $this->db->insert('users_detail', $request2);
			}else{
				$userdetaildata = $this->db->update('users_detail', $request2, ['id' => $userdetailid]);
			}
		}


		if(isset($data['address'])) 			$request3['address'] 		= $data['address'];
		if(isset($data['province'])) 			$request3['province'] 		= $data['province'];
		if(isset($data['city'])) 				$request3['city'] 			= $data['city'];		
		if(isset($data['suburb'])) 				$request3['suburb'] 		= $data['suburb'];
		if(isset($data['postal_code'])) 		$request3['postal_code'] 	= $data['postal_code']; 
		
		if(isset($request3)){
			$request3['user_id'] 	= $id;
			$request3['type'] 		= '3'; 
			$useraddressid			= $data['useraddressid'];

			if($useraddressid==''){
				$useraddressdata = $this->db->insert('users_address', $request3);
			}else{
				$useraddressdata = $this->db->update('users_address', $request3, ['id' => $useraddressid]);
			}
		}

		
		if(isset($data['bank_name'])) 				$request4['bank_name'] 		= $data['bank_name'];
		if(isset($data['branch_code'])) 			$request4['branch_code'] 	= $data['branch_code'];
		if(isset($data['account_name'])) 			$request4['account_name'] 	= $data['account_name'];	
		if(isset($data['account_no'])) 				$request4['account_no'] 	= $data['account_no'];
		if(isset($data['account_type'])) 			$request4['account_type'] 	= $data['account_type']; 
		

		if(isset($request4)){
			$request4['user_id'] 	= $id;
			$userbankid				= $data['userbankid'];

			if($userbankid==''){
				$userbankdata = $this->db->insert('users_bank', $request4);
			}else{
				$userbankdata = $this->db->update('users_bank', $request4, ['id' => $userbankid]);
			}
		}

		/*if(isset($data['area']) && count($data['area'])){
			$auditorids = array_column($data['area'], 'id');
			//$this->db->where('user_id', $id)->where_not_in('id', $auditorids)->delete('users_auditor_area');

			foreach($data['area'] as $key => $request5){
				$request5['user_id'] = $id;

				if($request5['id']==''){
					$usersarea = $this->db->insert('users_auditor_area', $request5);
				}else{
					$usersarea = $this->db->update('users_auditor_area', $request5, ['id' => $request5['id']]);
				}
			}
		}*/
		// else{
		// 	$this->db->where('user_id', $id)->delete('users_auditor_area');
		// }
		
		if((!isset($userdata) && !isset($userdetaildata) && !isset($useraddressdata) && !isset($userbankdata) && !isset($usersarea)) && $this->db->trans_status() === FALSE)
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

	public function AreaAction($data){

		$id = $data['user_id'];

		if(isset($data['area']) && count($data['area'])){
			$auditorids = array_column($data['area'], 'id');
			foreach($data['area'] as $key => $request5){
				$request5['user_id'] = $id;

				// if($request5['id']==''){
					$usersarea = $this->db->insert('users_auditor_area', $request5);
				// }else{
				// 	$usersarea = $this->db->update('users_auditor_area', $request5, ['id' => $request5['id']]);
				// }
			}
		}
	}

	public function deleteAuditorArea($data){

		$user_id 	= $data['user_id'];
		$id 		= $data['area_id'];

		$data = $this->db->where('id', $id)->delete('users_auditor_area');
		return $id;

	}

	public function ChatgetList($type, $requestdata=[])
	{
		$this->db->select('c.*, concat(ud1.name, " ", ud1.surname) name');
		$this->db->from('chat c');
		$this->db->join('users_detail ud1', 'ud1.user_id = c.from_id', 'left');
	
		if(isset($requestdata['id']))		$this->db->where('c.id', $requestdata['id']);
		if(isset($requestdata['cocid']))	$this->db->where('c.coc_id', $requestdata['cocid']);
			
		if(isset($requestdata['fromto'])){
			$this->db->group_start();
				$this->db->group_start();
					$this->db->where('c.from_id', $requestdata['fromto']);
					$this->db->where('c.state1', '1');
				$this->db->group_end();
				$this->db->or_group_start();
					$this->db->where('c.to_id', $requestdata['fromto']);
					$this->db->where('c.state2', '1');
					$this->db->or_where('c.state2', '0');
				$this->db->group_end();
			$this->db->group_end();
		}
		
		if(isset($requestdata['checkfrom'])){
			$this->db->group_start();
				$this->db->where('c.from_id', $requestdata['checkfrom']);
				$this->db->where('c.state1', '0');
			$this->db->group_end();
		}
		
		if(isset($requestdata['checkto'])){
			$this->db->group_start();
				$this->db->where('c.to_id', $requestdata['checkto']);
				$this->db->where('c.state2', '0');
			$this->db->group_end();
		}
		
		if(isset($requestdata['viewed'])){
			$this->db->group_start();
				$this->db->where('c.to_id', $requestdata['viewed']);
				$this->db->where('c.viewed', '0');
			$this->db->group_end();
		}
		
		if(isset($requestdata['state'])){
			$this->db->group_start();
				$this->db->where('c.state1', '0');
				$this->db->or_where('c.state2', '0');
			$this->db->group_end();
		}
		
		
		if($type=='count'){
			$result = $this->db->count_all_results();
		}else{
			$this->db->order_by('c.created_at', 'asc');
			
			$query = $this->db->get();
			
			if($type=='all') 		$result = $query->result_array();
			elseif($type=='row') 	$result = $query->row_array();
		}
		
		return $result;
	}

	public function getInvoiceList($type, $requestdata=[]){
		
		$this->db->select('inv.*, ud.name, ud.surname, ud.vat_vendor');
		$this->db->from('invoice inv');	
		$this->db->join('users_detail ud', 'ud.user_id=inv.user_id', 'left');
		$this->db->join('users u', 'u.id=inv.user_id', 'inner');
		$this->db->where('u.type', '5');

		if(isset($requestdata['status'])) $this->db->where('inv.status', $requestdata['status']);
		if(isset($requestdata['statuslist'])) $this->db->where_in('inv.status', $requestdata['statuslist']);
		if(isset($requestdata['id'])) $this->db->where('inv.inv_id', $requestdata['id']);
		if(isset($requestdata['user_id'])) $this->db->where('inv.user_id', $requestdata['user_id']);

		if($type!=='count' && isset($requestdata['start']) && isset($requestdata['length'])){
			$this->db->limit($requestdata['length'], $requestdata['start']);
		}
		if(isset($requestdata['order']['0']['column']) && isset($requestdata['order']['0']['dir'])){
			$column = ['inv.invoice_no', 'inv.created_at', 'ud.name', 'inv.description', 'inv.total_cost', 'inv.status', 'inv.internal_inv'];
			//$column = ['inv.inv_id', 'inv.description', 'inv.invoice_no', 'inv.invoice_date', 'inv.total_cost', 'inv.total_cost', 'inv.internal_inv', 'ud.name'];
			$this->db->order_by($column[$requestdata['order']['0']['column']], $requestdata['order']['0']['dir']);
		}
		if(isset($requestdata['search']['value']) && $requestdata['search']['value']!=''){
			$searchvalue = trim($requestdata['search']['value']);
			if(strtolower($searchvalue) == 'paid'){
				$this->db->where('inv.status', '1');
			}
			elseif(strtolower($searchvalue) == 'unpaid'){
				$this->db->where('inv.status', '0');
			}
			elseif(strtolower($searchvalue) == 'not submitted'){
				$this->db->where('inv.status', '2');
			}
			
			else{
				$this->db->group_start();
				$this->db->like('inv.inv_id', $searchvalue);
				$this->db->or_like('inv.description', $searchvalue);
				$this->db->or_like('inv.invoice_no', $searchvalue);					
				$this->db->or_like('inv.invoice_date', $searchvalue);
				// $this->db->or_like('inv.created_at', $searchvalue);
				$this->db->or_like('inv.total_cost', $searchvalue);
				$this->db->or_like('inv.internal_inv', $searchvalue);
				$this->db->or_like('ud.name', $searchvalue);
				$this->db->group_end();
			}

		}

		// $this->db->group_by('u.id');
		if (isset($requestdata['api_data']) && $requestdata['api_data'] =='auditor_accounts') {
			$this->db->order_by("inv.status", "DESC");
		}

		if($type=='count'){
			$result = $this->db->count_all_results();
		}else{
			$query = $this->db->get();
			
			if($type=='all') 		$result = $query->result_array();
			elseif($type=='row') 	$result = $query->row_array();
		}
		
		// print_r($this->db->last_query());die;
		
		return $result;

	}
	
	public function COCcount($requestdata=[]){
		$query = $this->db->select('*')->from('coc_count')->where('user_id', $requestdata['user_id'])->get()->row_array();
		//print_r($query);die;
		return $query;

	}
	public function getListPDF($type, $requestdata=[]){
        $query=$this->db->select('t1.*,t1.status,t1.created_at,
        	t2.inv_id, t2.total_due, t2.quantity, t2.cost_value,t2.vat, t2.delivery_cost, t2.total_due, t3.reg_no, t3.id, t3.name username, t3.surname surname, t3.company_name company_name, t3.vat_no vat_no, t3.email2, t3.home_phone, t3.file2, t4.address, t4.suburb, t4.city,t4.province,t4.postal_code, t5.id, t5.name as province,t6.id, t6.province_id, t6.name as city,t7.id, t7.province_id, t7.city_id, t7.name as suburb,t8.registration_no, t8.designation,ub.bank_name, ub.branch_code, ub.account_name, ub.account_no, ub.account_type, t9.type as usertype, t3.billing_email as billingemail, t3.billing_contact as billingcontact');
		$this->db->select('
			group_concat(concat_ws("@@@", t4.id, t4.suburb, t4.city,t4.province, t5.name, t6.name, t7.name) separator "@-@") as areas'
		);

        $this->db->from('invoice t1');

        $this->db->join('coc_orders t2','t2.inv_id = t1.inv_id', 'left');
		
		$this->db->join('users t9', 't9.id=t1.user_id', 'left');
		
        $this->db->join('users_detail t3', 't3.user_id = t1.user_id', 'left');

        $this->db->join('users_address t4', 't4.user_id = t1.user_id AND t4.type="3"', 'left');
		
		$this->db->join('users_plumber t8', 't8.user_id = t1.user_id', 'left');

        $this->db->join('province t5', 't5.id=t4.province', 'left');

        $this->db->join('city t6', 't6.id=t4.city', 'left');

        $this->db->join('suburb t7', 't7.id=t4.suburb', 'left');

        $this->db->join('users_bank ub', 'ub.user_id=t1.user_id', 'left');
   
		if(isset($requestdata['id'])) $this->db->where('t1.inv_id', $requestdata['id']);

		if($type=='count'){
			$result = $this->db->count_all_results();
		}else{
			$query = $this->db->get();
			
			if($type=='all') 		$result = $query->result_array();
			elseif($type=='row') 	$result = $query->row_array();
		}

		return $result;
	}

	public function getPermissions($type1)
	{ 
		$this->db->select('*');
		$this->db->from('settings_details');		
		
		if($type1=='count'){
			$result = $this->db->count_all_results();
		}else{
			$query = $this->db->get();
			
			if($type1=='all') 		$result = $query->result_array();
			elseif($type1=='row') 	$result = $query->row_array();
		}
		
		return $result;
	}

	public function getPermissions1($type2)
	{ 
		$this->db->select('st1.*, p1.id, p1.name');
		$this->db->select('group_concat(concat_ws("@@@", st1.province, p1.name) separator "@-@") as provincesettings');
		$this->db->from('settings_address st1', 'st1.type="2"');
		$this->db->join('province p1', 'p1.id = st1.province', 'left');
		
		if($type2=='count'){
			$result = $this->db->count_all_results();
		}else{
			$query = $this->db->get();
			
			if($type2=='all') 		$result = $query->result_array();
			elseif($type2=='row') 	$result = $query->row_array();
		}
		
		return $result;
	}

	public function action($requestdata, $flag){
		//$datetime		= 	date('Y-m-d H:i:s');

		if ($flag == 1) {
			$result 		= $this->db->insert('invoice',$requestdata);
			$inv_id 		= $this->db->insert_id();
			return $inv_id;
		}elseif($flag == 2){
			$result 		= $this->db->insert('coc_orders',$requestdata);
		}
		else{
			$result 		= $this->db->update('coc_count', $requestdata, ['user_id' => $requestdata['user_id']]);
			
			if ($result) {
				return '1';
			}else{
				return '0';
			}

		}
		

	}
	
	public function checkcocpermitted($userid)
	{
		$query = $this->db
		->select('coc_purchase_limit, coc_electronic')
		->from('users_plumber')
		->where('user_id', $userid)
		->get()
		->row_array();

		if($query){
			return $query;
		}else{
			return '0';
		}
	}
	
	public function actionCocLog($data)
	{
		$this->db->trans_begin();
		
		$userid			= 	$this->getUserID();
		$id 			= 	$data['id'];
		$datetime		= 	date('Y-m-d H:i:s');
		
		if(isset($data['coc_id']) && $id==''){
			$checkcoc = $this->db->get_where('coc_log', ['coc_id' => $data['coc_id']])->row_array();
			if($checkcoc) return true;
		}
		
		$request		=	[
			'updated_at' 		=> $datetime,
			'updated_by' 		=> $userid
		];

		if(isset($data['coc_id'])) 				$request['coc_id'] 					= $data['coc_id'];
		if(isset($data['completion_date'])) 	$request['completion_date'] 		= date('Y-m-d', strtotime($data['completion_date']));
		if(isset($data['order_no'])) 			$request['order_no'] 				= $data['order_no'];
		if(isset($data['name'])) 				$request['name'] 					= $data['name'];
		if(isset($data['address'])) 			$request['address'] 				= $data['address'];
		if(isset($data['street'])) 				$request['street'] 					= $data['street'];
		if(isset($data['number'])) 				$request['number'] 					= $data['number'];
		if(isset($data['province'])) 			$request['province'] 				= $data['province'];
		if(isset($data['city'])) 				$request['city'] 					= $data['city'];
		if(isset($data['suburb'])) 				$request['suburb'] 					= $data['suburb'];
		if(isset($data['contact_no'])) 			$request['contact_no'] 				= $data['contact_no'];
		if(isset($data['alternate_no'])) 		$request['alternate_no'] 			= $data['alternate_no'];
		if(isset($data['email'])) 				$request['email'] 					= $data['email'];
		if(isset($data['installationtype'])) 	$request['installationtype'] 		= implode(',', $data['installationtype']);
		if(isset($data['specialisations'])) 	$request['specialisations'] 		= implode(',', $data['specialisations']);
		if(isset($data['installation_detail'])) $request['installation_detail'] 	= $data['installation_detail'];
		if(isset($data['file1'])) 				$request['file1'] 					= $data['file1'];
		if(isset($data['agreement'])) 			$request['agreement'] 				= $data['agreement'];
		if(isset($data['file1'])) 				$request['file1'] 					= $data['file1'];
		if(isset($data['company_details'])) 	$request['company_details'] 		= $data['company_details'];
		if(isset($data['ncnotice'])) 			$request['ncnotice'] 				= $data['ncnotice'];
		if(isset($data['ncemail'])) 			$request['ncemail'] 				= $data['ncemail'];
		if(isset($data['ncreason'])) 			$request['ncreason'] 				= $data['ncreason'];
		if(isset($data['submit']) && $data['submit']=='log') $request['log_date'] 	= date('Y-m-d H:i:s');
		
		$request['file2'] 					= (isset($data['file2'])) ? implode(',', $data['file2']) : '';
		
		if($id==''){
			$request['created_at'] = $datetime;
			$request['created_by'] = $userid;
			$this->db->insert('coc_log', $request);
		}else{
			$this->db->update('coc_log', $request, ['id' => $id]);
		}
		
		if(isset($data['submit'])){
			if($data['submit']=='save'){
				$cocstatus = '5';
			}elseif($data['submit']=='log'){
				$cocstatus = '2';
				$this->db->set('count', 'count + 1',FALSE); 
				$this->db->where('user_id', $userid); 
				$increase_count = $this->db->update('coc_count'); 
				
				// $checkreseller = $this->getCOCList('row', ['id'=>$data['coc_id']]);
				// if($checkreseller['resellersid'] != ''){
				// 	$this->db->set('count', 'count + 1',FALSE);
				// 	$this->db->where('user_id', $checkreseller['resellersid']);
				// 	$this->db->update('coc_count');
				// }
			}
			
			if(isset($cocstatus)) $this->db->update('stock_management', ['coc_status' => $cocstatus], ['id' => $data['coc_id']]);
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
	
	// Coc Details
	
	public function getCOcDetails($type, $requestdata=[])
	{ 
		$this->db->select('*');
		$this->db->from('coc_details');
	
		if(isset($requestdata['id']))		$this->db->where('id', $requestdata['id']);
		if(isset($requestdata['coc_id']))	$this->db->where('coc_id', $requestdata['coc_id']);
						
		$this->db->order_by('id', 'desc');
		
		if($type=='count'){
			$result = $this->db->count_all_results();
		}else{
			$query = $this->db->get();
			
			if($type=='all') 		$result = $query->result_array();
			elseif($type=='row') 	$result = $query->row_array();
		}
		
		return $result;
	}
	
	public function actionCocDetails($data)
	{
		$this->db->trans_begin();
		
		$userid			= 	$this->getUserID();
		$datetime		= 	date('Y-m-d H:i:s');
		$cocid			=	$data['coc_id'];
		
		if(isset($data['revoked'])){
			$this->db->update('stock_management', ['coc_status' => '4', 'coc_orders_status' => null], ['id' => $cocid]);
			$return = '1';
		}else{
			$recall			=	$data['recall'];
			
			$request		=	[
				'coc_id' 			=> $cocid,
				'recall' 			=> $recall,
				'reason' 			=> isset($data['reason']) && $recall=='2' ? $data['reason'] : '',
				'document' 			=> isset($data['document']) && $recall=='2' ? $data['document'] : '',
				'user_id' 			=> isset($data['userid']) && $recall=='3' ? $data['userid'] : '',
				'created_at' 		=> $datetime,
				'created_by' 		=> $userid,
				'updated_at' 		=> $datetime,
				'updated_by' 		=> $userid
			];

			$this->db->insert('coc_details', $request);
			
			$stock 			= $this->getCOCList('row', ['id' => $cocid]);
			$stockuserid 	= $stock['user_id'];
			
			if($recall=='1'){			
				$this->db->set('count', 'count + 1', FALSE); 
				$this->db->where('user_id', $stockuserid); 
				$this->db->update('coc_count'); 
				
				$this->db->update('stock_management', ['user_id' => '0', 'coc_status' => '1', 'coc_orders_status' => '6'], ['id' => $cocid]);
				$return = '1';
			}elseif($recall=='2'){
				$this->db->update('stock_management', ['coc_status' => '7', 'coc_orders_status' => '7'], ['id' => $cocid]);
				$return = '2';
			}elseif($recall=='3'){
				$cocstatus = (isset($data['user_type']) && $data['user_type']=='3') ? '4' : '3';
				if(isset($data['userid']) && $stockuserid!=$data['userid']){
					$stockcheck = $this->getCOCCount('row', ['user_id' => $data['userid']]);
					if($stockcheck['count'] > 0){
						$this->db->set('count', 'count + 1', FALSE); 
						$this->db->where('user_id', $stockuserid); 
						$this->db->update('coc_count'); 
						
						$this->db->set('count', 'count - 1', FALSE); 
						$this->db->where('user_id', $data['userid']); 
						$this->db->update('coc_count'); 
						
						$this->db->update('stock_management', ['user_id' => $data['userid'], 'coc_status' => $cocstatus, 'coc_orders_status' => '8'], ['id' => $cocid]);
						$return = '3';
					}else{
						$return = '4';
					}
				}else{
					$return = '5';
				}
			}
		}
		
		if($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return false;
		}
		else
		{
			$this->db->trans_commit();
			if(isset($return)) return $return;
			else return true;
		}
	}
	
	// Coc Count
	
	public function getCOCCount($type, $requestdata=[])
	{ 
		$this->db->select('*');
		$this->db->from('coc_count');
	
		if(isset($requestdata['id']))		$this->db->where('id', $requestdata['id']);
		if(isset($requestdata['user_id']))	$this->db->where('user_id', $requestdata['user_id']);
						
		if($type=='count'){
			$result = $this->db->count_all_results();
		}else{
			$query = $this->db->get();
			
			if($type=='all') 		$result = $query->result_array();
			elseif($type=='row') 	$result = $query->row_array();
		}
		
		return $result;
	}
	
	public function actionCocCount($data)
	{
		$this->db->trans_begin();
		
		$userid			= 	$this->getUserID();
		$datetime		= 	date('Y-m-d H:i:s');
		
		$request		=	[
			'count' 			=> $data['count'],
			'user_id' 			=> $data['user_id'],
			'updated_at' 		=> $datetime,
			'updated_by' 		=> $userid
		];

		$count = $this->getCOCCount('count', ['user_id' => $data['user_id']]);
		
		if($count=='0'){
			$request['created_at'] = $datetime;
			$request['created_by'] = $userid;
			$this->db->insert('coc_count', $request);
		}else{
			$this->db->update('coc_count', $request, ['user_id' => $data['user_id']]);
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
	
	// Cancel Coc
	
	
	public function cancelcoc($data)
	{
		$cocid = $data['coc_id'];
		
		$this->db->delete('auditor_statement', ['coc_id' => $cocid]);
		$this->db->delete('auditor_review', ['coc_id' => $cocid]);
		$this->db->update('stock_management', ['auditorid' => '0'], ['id' => $cocid]);
		
		return true;
	}

	public function getplumbersLists($type, $requestdata=[], $querydata=[])
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
									'up.application_received','up.application_status','up.approval_status','up.reject_reason','up.reject_reason_other'
								];
								
			$select[] 		= 	implode(',', $usersplumber);
		}
		
		if(in_array('usersskills', $querydata)){
			$select[]		= 	'group_concat(IF(COALESCE(ups.id, "")="", "", concat_ws("@@@", COALESCE(ups.id, ""), COALESCE(ups.user_id, ""), COALESCE(ups.date, ""), COALESCE(ups.certificate, ""), COALESCE(ups.qualification, ""), COALESCE(ups.skills, ""), COALESCE(ups.training, ""), COALESCE(ups.attachment, ""), COALESCE(qr.name, ""))) separator "@-@") as skills';
		}
		
		if(in_array('company', $querydata)){
			$userscompany	= 	[ 
									'c.company as companyname',
									'c.mobile_phone as companymobile'
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
						/*$this->db->or_like('ud.name', $searchvalue);
						$this->db->or_like('ud.surname', $searchvalue);
						$this->db->or_like('c1.name', $searchvalue);
						$this->db->or_like('u.email', $searchvalue);
						$this->db->or_like('c2.name', $searchvalue);*/
					}
					/*elseif($page=='adminplumberrejectedlist'){					
						$this->db->like('DATE_FORMAT(up.registration_date,"%d-%m-%Y")', $searchvalue, 'both');
						$this->db->or_like('ud.name', $searchvalue);
						$this->db->or_like('ud.surname', $searchvalue);
					}*/
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

	public function getplumbersLists_suburb($type, $requestdata = [], $querydata=[]){

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
									'up.application_received','up.application_status','up.approval_status','up.reject_reason','up.reject_reason_other'
								];
								
			$select[] 		= 	implode(',', $usersplumber);
		}
		
		if(in_array('usersskills', $querydata)){
			$select[]		= 	'group_concat(IF(COALESCE(ups.id, "")="", "", concat_ws("@@@", COALESCE(ups.id, ""), COALESCE(ups.user_id, ""), COALESCE(ups.date, ""), COALESCE(ups.certificate, ""), COALESCE(ups.qualification, ""), COALESCE(ups.skills, ""), COALESCE(ups.training, ""), COALESCE(ups.attachment, ""), COALESCE(qr.name, ""))) separator "@-@") as skills';
		}
		
		if(in_array('company', $querydata)){
			$userscompany	= 	[ 
									'c.company as companyname',
									'c.mobile_phone as companymobile'
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
		if(in_array('usersdetail', $querydata)) 		$this->db->join('users_detail ud', 'ud.user_id=u.id', 'inner');
		if(in_array('physicaladdress', $querydata)) 	$this->db->join('users_address ua1', 'ua1.user_id=u.id and ua1.type="1"', 'left');
		// if(in_array('postaladdress', $querydata)) 		$this->db->join('users_address ua2', 'ua2.user_id=u.id and ua2.type="2"', 'left');
		// if(in_array('billingaddress', $querydata)) 		$this->db->join('users_address ua3', 'ua3.user_id=u.id and ua3.type="3"', 'left');
		if(in_array('usersplumber', $querydata)) 		$this->db->join('users_plumber up', 'up.user_id=u.id', 'inner');
		if(in_array('usersskills', $querydata)) 		$this->db->join('users_plumber_skill ups', 'ups.user_id=u.id', 'inner');
		if(in_array('usersskills', $querydata)) 		$this->db->join('qualificationroute qr', 'qr.id=ups.skills', 'inner'); 
		if(in_array('company', $querydata)) 			$this->db->join('users_detail c', 'c.user_id=up.company_details', 'inner');


		if(isset($requestdata['id'])) 					$this->db->where('u.id', $requestdata['id']);
		if(isset($requestdata['type'])) 				$this->db->where('u.type', $requestdata['type']);
		if(isset($requestdata['formstatus']))			$this->db->where_in('u.formstatus', $requestdata['formstatus']);
		if(isset($requestdata['status']))				$this->db->where_in('u.status', $requestdata['status']);
		if(isset($requestdata['approvalstatus']))		$this->db->where_in('up.approval_status', $requestdata['approvalstatus']);
		if(isset($requestdata['plumberstatus']))		$this->db->where_in('ud.status', $requestdata['plumberstatus']);
		
		$this->db->group_start();
		if(isset($requestdata['suburubid']) && $requestdata['suburubid']!='')		$this->db->where_in('ua1.suburb', $requestdata['suburubid']);
		if(isset($requestdata['suburuname']) && $requestdata['suburuname']!='')		$this->db->or_where('ua1.suburb', $requestdata['suburuname']);
		$this->db->group_end();

		// if(isset($requestdata['suburubid']) && $requestdata['suburubid']!=''){
		// 	foreach ($requestdata['suburubid'] as $suburubidkey => $suburubidvalue) {
		// 		$this->db->like('ua1.suburb', $suburubidvalue);
		// 	}

		// }
		// if(isset($requestdata['suburuname']) && $requestdata['suburuname']!=''){
		// 	foreach ($requestdata['suburuname'] as $suburunamekey => $suburunamevalue) {
		// 		$this->db->like('ua1.suburb', $suburunamevalue);
		// 	}

		// }	
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

	public function autosearchActivity($type, $postData = []){
		$currentDate = date('Y-m-d H:i:s');

		$this->db->select('cp1.*');
		$this->db->from('cpdtypes cp1');

		// $this->db->like('cp1.activity',$postData['search_keyword']);

		$this->db->where('cp1.status="1"');
		$this->db->where('cp1.activity !=" "');
		$this->db->where('cp1.link !=" "');
		$this->db->where('cp1.description !=" "');
		$this->db->where('cp1.proof !=" "');
		$this->db->where('cp1.image !=" "');
		// $this->db->where('cp1.startdate<="'.$currentDate.'"');
		$this->db->where('cp1.enddate>"'.$currentDate.'"');
		if(isset($postData['cpdstream'])) $this->db->where('cp1.cpdstream', $postData['cpdstream']);
		if(isset($postData['pagetype']) && $postData['pagetype'] =='plumbercpd'){
			$this->db->where('cp1.hidden', '0');
		}
		
		$this->db->group_by("cp1.id");		
		$query = $this->db->get();
		$result1 = $query->result_array(); 

		return $result1;
	}

	public function checkusers($data){

		$email 		= trim($data['email']);
		$password 	= md5($data['password']);
		$type 		= $data['type'];

		$this->db->select('u.id, u.email, u.password');
		$this->db->from('users u');
		$this->db->where('u.email', $email);
		$this->db->where('u.type', $type);
		$emailcount = $this->db->count_all_results();
		if ($emailcount =='1') {
			$this->db->select('u.id, u.email, u.password, u.formstatus');
			$this->db->from('users u');
			$this->db->where('u.email', $email);
			$this->db->where('u.formstatus', '1');
			$this->db->where('u.type', $type);
			$formstatus = $this->db->count_all_results();
			if ($formstatus =='1') {
				$this->db->select('u.id, u.email, u.password');
				$this->db->from('users u');
				$this->db->where('u.email', $email);
				$this->db->where('u.password', $password);
				$this->db->where('u.type', $type);
				$passwordcount = $this->db->count_all_results();
				if ($passwordcount =='1') {
					$status 	= '1';
					$message 	= 'Profile verified on Audit-IT database.';
				}else{
					$status 	= '3';
					$message 	= 'Entered password not match with Audit-IT database.';
				}
			}else{
				$status 		= '4';
				$message 		= 'Profile not verified on Audit-IT database.';
			}
			
		}else{
			$status 			= '2';
			$message 			= 'There is no matching emaill address on Audit-IT database.';
		}

		// $query = $this->db->where_in('type', $type)->get_where('users', ['email' => $email, 'password' => $password]);

		// if($query->num_rows() > 0){
		// 	$result = $query->row_array();
		// }else{
		// 	$result = '';
		// }
		$result['status'] 	= $status;
		$result['message'] 	= $message;
		return $result;
	}
}
