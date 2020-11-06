<?php
// /////////////echo "<pre>";
// print_r($result);die;

	$id 			= isset($result['id']) ? $result['id'] : '';
	$usertype 		= isset($result['type']) ? $result['type'] : '5';
	$email  		= isset($result['email']) ? $result['email'] : set_value ('email');
	$password  		= isset($result['password_raw']) ? $result['password_raw'] : set_value ('password_raw');
	$allocation_number = isset($result['password_raw']) ? $result['password_raw'] : set_value ('password_raw');
	
	$userdetailid 	= isset($result['userdetailid']) ? $result['userdetailid'] : '';
	$name 			= isset($result['name']) ? $result['name'] : set_value ('name');
	$surname 		= isset($result['surname']) ? $result['surname'] : set_value ('surname');	
	$billingname 	= isset($result['company_name']) ? $result['company_name'] : set_value ('company_name');
	$compreg 		= isset($result['reg_no']) ? $result['reg_no'] : set_value ('reg_no');
	$compvat 		= isset($result['vat_no']) ? $result['vat_no'] : set_value ('vat_no');
	$compvatvendor	= isset($result['vat_vendor']) ? $result['vat_vendor'] : set_value ('vat_vendor');
	$billingemail 	= isset($result['billing_email']) ? $result['billing_email'] : '';
	$billingcontact = isset($result['billing_contact']) ? $result['billing_contact'] : '';
	$mobile  		= isset($result['mobile_phone']) ? $result['mobile_phone'] : set_value ('mobile_phone');
	$workphone  	= isset($result['work_phone']) ? $result['work_phone'] : set_value ('work_phone');
	$image 			= isset($result['file1']) ? $result['file1'] : set_value ('file1');	
	$complogo 		= isset($result['file2']) ? $result['file2'] : set_value ('file2');
	$idno 			= isset($result['identity_no']) ? $result['identity_no'] : set_value ('idno');
	$user_status1 	= isset($result['usstatus']) ? $result['usstatus'] : '';


	$auditoravaid 			= isset($result['available']) ? $result['available'] : '';
	$audit_status1 			= isset($result['status']) ? $result['status'] : '';

	$allocation_allowed 	= isset($result['allocation_allowed']) ? $result['allocation_allowed'] : '';

	$useraddressid 	= isset($result['useraddressid']) ? $result['useraddressid'] : '';
	
	$billaddress 	= isset($result['address']) ? $result['address'] : set_value ('address');
	$province 		= isset($result['province']) ? $result['province'] : set_value ('province');
	$city 			= isset($result['city']) ? $result['city'] : set_value ('city');
	$suburb 		= isset($result['suburb']) ? $result['suburb'] : set_value ('suburb');
	$postal 		= isset($result['postal_code']) ? $result['postal_code'] : set_value ('postal_code');

	$userbankid 	= isset($result['userbankid']) ? $result['userbankid'] : '';
	$bank 			= isset($result['bank_name']) ? $result['bank_name'] : set_value ('bankname');
	$branchcode 	= isset($result['branch_code']) ? $result['branch_code'] : set_value ('branchcode');
	$accountname 	= isset($result['account_name']) ? $result['account_name'] : set_value ('account_name');
	$accno 			= isset($result['account_no']) ? $result['account_no'] : set_value ('account_no');
	$type 			= isset($result['account_type']) ? $result['account_type'] : set_value ('account_type');

	$areas 			= isset($result['areas']) ? array_filter(explode('@-@', $result['areas'])) : [];

	$heading 		= isset($result['id']) ? 'Update' : 'Save';   
	$profileimg 			= base_url().'assets/images/profile.jpg';

	$filepath 		= base_url().'assets/uploads/auditor/';
	$filepath1		= (isset($result['file1']) && $result['file1']!='') ? $filepath.$result['file1'] : base_url().'assets/uploads/auditor/profile.jpg';
	$filepath2		= (isset($result['file2']) && $result['file2']!='')  ? $filepath.$result['file2'] : base_url().'assets/uploads/auditor/profile.jpg';	
	$pdfimg 		= base_url().'assets/uploads/auditor/pdf.png';

	if($image!=''){
		$explodefile2 	= explode('.', $image);
		$extfile2 		= array_pop($explodefile2);
		$photoidimg 	= (in_array($extfile2, ['pdf', 'tiff'])) ? $pdfimg : $filepath1;
		$photoidurl 	= $filepath1;
	}else{
		$photoidimg 	= $profileimg;
		$photoidurl 	= 'javascript:void(0);';
	}	

	if($complogo!=''){
		$explodefile21 	= explode('.', $complogo);
		$extfile21 		= array_pop($explodefile21);
		$photoidimg1 	= (in_array($extfile21, ['pdf', 'tiff'])) ? $pdfimg : $filepath2;
		$photoidurl1 	= $filepath2;
	}else{
		$photoidimg1 	= $profileimg;
		$photoidurl1 	= 'javascript:void(0);';
	}
	if($roletype=='1'){
		$heading = 'Auditors Profile';
	}

	$count 				= $history['count'];
	$total 				= $history['total'];
	$refixincomplete 	= $history['refixincomplete'];
	$refixcomplete 		= $history['refixcomplete'];
	$compliment 		= $history['compliment'];
	$cautionary 		= $history['cautionary'];
	$noaudit 			= $history['noaudit'];

	$refixincompletepercentage 	= ($refixincomplete!=0) ? round(($refixincomplete/$total)*100,2).'%' : '0%'; 
	$refixcompletepercentage 	= ($refixcomplete!=0) ? round(($refixcomplete/$total)*100,2).'%' : '0%'; 
	$complimentpercentage 		= ($compliment!=0) ? round(($compliment/$total)*100,2).'%' : '0%'; 
	$cautionarypercentage 		= ($cautionary!=0) ? round(($cautionary/$total)*100,2).'%' : '0%'; 
	$noauditpercentage 			= ($noaudit!=0) ? round(($noaudit/$total)*100,2).'%' : '0%'; 

?>

<div class="row page-titles">
	<div class="col-md-5 align-self-center">
		<h4 class="text-themecolor"><?php echo $heading ?></h4>
	</div>
	<div class="col-md-7 align-self-center text-right">
		<div class="d-flex justify-content-end align-items-center">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">Home</li>
				<li class="breadcrumb-item active"><?php echo $heading ?></li>
			</ol>
		</div>
	</div>
</div>
<?php echo $notification; ?>
<?php if($roletype=='1'){ echo isset($menu) ? $menu : ''; } ?>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
					<h4 class="card-title">Audit Report History</h4>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Number Audits Done to Date</label>
								<input type="text" class="form-control" value="<?php echo $count; ?>" disabled>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Total Number of Audit Findings</label>
								<input type="text" class="form-control" value="<?php echo $total; ?>" disabled>
							</div>
						</div>
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-12"><div id="auditbar" style="width:100%; height:400px;"></div></div>
								<div class="col-md-12"><div id="auditpie" style="width:100%; height:400px;"></div></div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Cautionary Audit Findings</label>
								<div class=" col-md-12">
									<div class="row">
										<input type="text" class="form-control col-md-7" value="<?php echo $cautionary; ?>" disabled>
										<input type="text" class="form-control col-md-4 offset-md-1" value="<?php echo $cautionarypercentage; ?>" disabled>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Refix (In-Complete) Audit Findings</label>
								<div class=" col-md-12">
									<div class="row">
										<input type="text" class="form-control col-md-7" value="<?php echo $refixincomplete; ?>" disabled>
										<input type="text" class="form-control col-md-4 offset-md-1" value="<?php echo $refixincompletepercentage; ?>" disabled>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Refix (Complete) Audit Findings</label>
								<div class=" col-md-12">
									<div class="row">
										<input type="text" class="form-control col-md-7" value="<?php echo $refixcomplete; ?>" disabled>
										<input type="text" class="form-control col-md-4 offset-md-1" value="<?php echo $refixcompletepercentage; ?>" disabled>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>No Audit Findings Audit Findings</label>
								<div class=" col-md-12">
									<div class="row">
										<input type="text" class="form-control col-md-7" value="<?php echo $noaudit; ?>" disabled>
										<input type="text" class="form-control col-md-4 offset-md-1" value="<?php echo $noauditpercentage; ?>" disabled>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Compliment Audit Findings</label>
								<div class=" col-md-12">
									<div class="row">
										<input type="text" class="form-control col-md-7" value="<?php echo $compliment; ?>" disabled>
										<input type="text" class="form-control col-md-4 offset-md-1" value="<?php echo $complimentpercentage; ?>" disabled>
									</div>
								</div>
							</div>
						</div>
					</div>
					<h4 class="card-title">Audit History</h4>
					<div class="table-responsive m-t-40">
						<table class="table table-bordered table-striped datatabless fullwidth">
							<thead>
								<tr>
									<th>COC Number</th>
									<th>Audit Date</th>
									<th>Plumber</th>
									<th>Suburb</th>
									<th>City</th>
									<th>Province</th>
									<th>Cautionary Count</th>
									<th>Refix (Complete) Count</th>
									<th>Refix (In-Complete) Count</th>
									<th>No Audit Findings Count</th>
								</tr>							
							</thead>
						</table>
					</div>
			</div>
		</div>
	</div>
</div>
<script>
		var count 			= '<?php echo $count; ?>';
		var total 			= '<?php echo $total; ?>';
		var refixincomplete = '<?php echo $refixincomplete; ?>';
		var refixcomplete 	= '<?php echo $refixcomplete; ?>';
		var compliment 		= '<?php echo $compliment; ?>';
		var cautionary 		= '<?php echo $cautionary; ?>';
		var noaudit 		= '<?php echo $noaudit; ?>';

		$(function(){
			
			var options = {
				url 	: 	'<?php echo base_url()."admin/audits/index/DTAuditHistory"; ?>',
				columns : 	[							
								{ "data": "cocno" },
								{ "data": "auditdate" },
								{ "data": "plumber" },
								{ "data": "suburb" },
								{ "data": "city" },
								{ "data": "province" },
								{ "data": "cautionary" },
								{ "data": "refixcomplete" },
								{ "data": "refixincomplete" },
								{ "data": "noaudit" }
							],
							data : {auditorid : '<?php echo $id; ?>', auditcomplete : 1, page : 'auditorprofile'}
			};
			
			ajaxdatatables('.datatabless', options);
			
			barchart(
				'auditbar',
				{
					xaxis : [
						'Total No of Audit Findings',
						'Compliments',
						'Cautionary',
						'Refix (Complete)',
						'Refix(In Complete)',
						'No Audit'
					],
					series : [{
						name : 'Audit',
						yaxis : [
							total,
							compliment,
							cautionary,
							refixcomplete,
							refixincomplete,
							noaudit
						],
						colors : ['#4472C4','#843C0C','#FF0000','#ED7D31','#333F50','#4472C4']
					}]
				}
			)
			
			piechart(
				'auditpie',
				{
					name : 'Audit',
					xaxis : [
						'Compliments',
						'Cautionary',
						'Refix (Complete)',
						'Refix(In Complete)',
						'No Audit'
					],
					yaxis : [
						{value : compliment, name : 'Compliments'},
						{value : cautionary, name : 'Cautionary'},
						{value : refixcomplete, name : 'Refix (Complete)'},
						{value : refixincomplete, name : 'Refix(In Complete)'},
						{value : noaudit, name : 'No Audit'}
					],
					colors : ['#843C0C','#FF0000','#ED7D31','#333F50','#4472C4']				
				}
			)
			
		});
</script>