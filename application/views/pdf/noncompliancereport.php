<!DOCTYPE html>
<html>
<head>
	
</head>
<body style=" padding-top: 10px; padding-bottom: 15px; font-family: 'Helvetica' !important;">
<style type="text/css">
table.page_overall_auditreport {
    margin: 10px auto;
}
table.head-all {
    width: 100%;
  /*  margin-bottom: 20px;
    padding-bottom: 20px;  */  
}
table.coc_details_overall {
    width: 100%;
    border-bottom: 1.5px solid;   
    margin-bottom: 20px;
    padding-bottom: 20px;    
}
table.coc_details_overall h3 {
    border-bottom: 1.5px solid #000;
    display: inline-block;
    margin: 0;
    margin-bottom: 10px;
}	
table.coc_details_overall td, table.auditor_details_overall td {
    padding: 5px;
    width: 33.3%;
}
table.page_overall_auditreport input[type="text"] {
    padding: 5px;
}
table.auditor_details_overall h3 {
    border-bottom: 1.5px solid #000;
    display: inline-block;
    margin: 0;
    margin-bottom: 10px;
}

table.auditor_details_overall {
    width: 100%;
    border-bottom: 1.5px solid #000;
    margin-bottom: 20px; 
    padding-bottom: 20px;       
}
table.notice-license-text {
    width: 100%;
    border: 1px solid #000;
    border-collapse: collapse;
    margin-bottom: 25px; 
}
table.notice-license-text th {
    background: #f00;
}
table.notice-license-text th h3 {
    color: #fff;
    font-weight: 600;
    margin: 0;
    padding: 5px 0;
}
table.table.table-bordered.reviewtable {
    width: 100%;
    border-collapse: collapse;
}
h3.audit-table-heading {
    border-bottom: 1.5px solid #000;
    display: inline-block;
    margin: 20px 0 20px;
}
table.table.table-bordered.reviewtable td {
    border: 1px solid #000;
    padding: 10px;
    text-align: center;
}
table.table.table-bordered.reviewtable th {
    border: 1px solid #000;
	padding: 15px;    
	text-align: center;
}
table.coc_details_overall label, table.auditor_details_overall label {
    font-weight: bold;
}
table.notice-license-text tbody tr td {
    padding: 5px;
}

table, th, td {
    border: 1px solid #a7a9ab;
    font-family: Helvetica !important;
}

table{
border-collapse: collapse;
    width: 100%;
}

</style>

<?php
$cocid 					= isset($result['id']) ? $result['id'] : '';
	
$completiondate 		= isset($result['cl_completion_date']) && $result['cl_completion_date']!='1970-01-01' ? date('d-m-Y', strtotime($result['cl_completion_date'])) : '';
$name 					= isset($result['cl_name']) ? $result['cl_name'] : '';
$address 				= isset($result['cl_address']) ? $result['cl_address'] : '';
$street 				= isset($result['cl_street']) ? $result['cl_street'] : '';
$number 				= isset($result['cl_number']) ? $result['cl_number'] : '';
$province 				= isset($result['cl_province_name']) ? $result['cl_province_name'] : '';
$city 					= isset($result['cl_city_name']) ? $result['cl_city_name'] : '';
$suburb 				= isset($result['cl_suburb_name']) ? $result['cl_suburb_name'] : '';

$plumberid 				= isset($result['u_id']) ? $result['u_id'] : '';
$plumbername 			= isset($result['u_name']) ? $result['u_name'] : '';
$plumbercompany 		= isset($result['plumbercompany']) ? $result['plumbercompany'] : '';
$plumberwork 			= isset($result['u_work']) ? $result['u_work'] : '';

function base64conversion($path){
	$type = pathinfo($path, PATHINFO_EXTENSION);
	$data = file_get_contents($path);
	return 'data:image/' . $type . ';base64,' . base64_encode($data);
}

$logoimg = base64conversion(base_url().'assets/images/pitrb-logo.png');
?>

<table class="page_overall_auditreport" style="border:none !important;">
	<tbody style="border:none !important;">

		<tr>
			<td>
				<table class="head-all" style="margin-bottom: 0 !important;">
					<tbody>
						<tr>
							<td><h2 style="padding-left: 6px">NOTICE OF NON-COMPLIANCE</h2></td>
							<td style="text-align: right; width:250px;"><img width="200px" src="<?php echo $logoimg; ?>"></td>                   
						</tr>
					</tbody>
				</table>
			</td>
		</tr>

		<tr>
			<td style="border-bottom: 1.5px solid #000">
				<table class="notice-license-text" style="margin-bottom: 0; padding-bottom: 0;">
					<thead>
						<tr>
						<th style="text-align: center;"><h3 >NOTICE TO HOME OWNERS</h3></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="padding: 5px 15px;text-align: justify; font-size: 15px;">In terms of the, Water Services Act of 8 June 2001; Occupational Health & Safety Act (Pressure Equipment Regulations, 2009); the National Standard SANS 10254 (The Installation, Maintenance, Replacement and Reparation of Fixed Electric Storage Water Heating Systems); the National Standard SANS 1352 (The Installation, Maintenance, Replacement and Reparation of Domestic Air Source Water Heating Heat Pump Systems); the National Standard SANS 10106 (The Installation, Maintenance, Replacement and Reparation of Domestic Solar Water Heating Systems); as well as Section 58 of the Consumer Protection Act, any aspect of your plumbing installation that does not comply with the latest requirements of the above-mentioned national standards and legislation must be notified in writing to the user/owner by the relevant Licensed Plumber. THIS NOTICE SERVES NOTIFY TO YOU OF SUCH NON-COMPLIANCES.  Unless otherwise stated, this inspection is a visual inspection of component(s) and part(s) of your plumbing system, as listed.  These non-compliances are reasonably visible and capable of being inspected without creating damage(s).  Although the applicable plumbing at your premises may have been compliant at the time that it was installed, changes to the above-mentioned requirements are made from time to time for improvement and to minimise any potential risks. In doing so, it ensures that your safety, as the consumer, is the highest priority.</td>             
						</tr>
					</tbody>
				</table>
			</td>
		</tr>

		<tr> 
			<td style="border:none;">
				<table class="coc_details_overall"  style="margin-bottom: 0; border:none;">
					<tbody>
					<tr> 
						<td style="padding: 0; padding-left: 6px; border:none;"> <h3 style="margin: 20px 0 10px">COC DETAILS</h3> </td>
					</tr>

					<tr>
						<td style="padding: 0; padding-left: 6px"><label>Certificate No</label></td>
						<td style="padding-left: 6px"><label>Plumbing Work Completion Date</label></td>
						<td style="padding-left: 6px"><label>Owners Name</label></td>
					</tr>

					<tr>
						<td style="padding: 0; padding-left: 6px"><span class = "nc-td-cls"><?php echo $cocid; ?></span></td>
						<td style="padding-left: 6px"><span class = "nc-td-cls"><?php echo $completiondate; ?></span></td>
						<td style="padding-left: 6px"><span class = "nc-td-cls"><?php echo $name; ?></span></td>
					</tr>           

					<tr>
						<td style="padding: 0; padding-left:6px"><label>Name of Complex/Flat (if applicable)</label></td>
						<td style="padding-left: 6px"><label>Street</label></td>
						<td style="padding-left: 6px"><label>Number</label></td>
					</tr>

					<tr>
						<td style="padding: 0; padding-left:6px"><span class = "nc-td-cls"><?php echo $address; ?></span></td>
						<td style="padding-left: 6px"><span class = "nc-td-cls"><?php echo $street; ?></span></td>
						<td style="padding-left: 6px"><span class = "nc-td-cls"><?php echo $number; ?></span></td>
					</tr>

					<tr>
						<td style="padding: 0; padding-left:6px"><label>Province</label></td>
						<td style="padding-left: 6px"><label>City</label></td>
						<td style="padding-left: 6px"><label>Suburb</label></td>
					</tr>

					<tr>
						<td style="padding: 0; padding-left:5px"><span class = "nc-td-cls"><?php echo $province; ?></span></td>
						<td style="padding-left: 6px"><span class = "nc-td-cls"><?php echo $city; ?></span></td>
						<td style="padding-left: 6px"><span class = "nc-td-cls"><?php echo $suburb; ?></span></td>
					</tr>
						
					</tbody>
				</table>
			</td>
		</tr>
		

		<tr>
			<td style="border:none;">
				<table class="auditor_details_overall" style="border:none; margin-bottom: 77px;">
					<tbody>
						<tr>
							<td colspan="3" style="padding-left: 0; padding-left: 6px"><h3>PLUMBERS DETAILS</h3></td>
						</tr>
						<tr>
							<td style="padding: 0; padding-left:5px"><label>Plumbers Name and Surname</label></td>
							<td style="padding-left: 5px"><label>Company of Plumber</label></td>
							<td style="padding-left: 5px"><label>Company Contact (work number)</label></td>
						</tr>
						<tr>
							<td style="padding: 0; padding-left:5px"><span class = "nc-td-cls"><?php echo $plumbername; ?></span></td>
							<td style="padding-left: 5px"><span class = "nc-td-cls"><?php echo $plumbercompany; ?></span></td>
							<td style="padding-left: 5px"><span class = "nc-td-cls"><?php echo $cwork_phone; ?></span></td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>

	</tbody>
</table>


<h3 style="margin: 10 0 20px; padding: 0 15px 0 15px;" class="audit-table-heading">NON COMPLIANCES</h3>
<table class="table table-bordered reviewtable" >    
	<thead>
		<tr>
			<th>Non Compliance Details</th>
			<th>Possible remedial actions</th>
			<th>SANS/Regulation/Bylaw Reference</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach($noncompliance as $list){ ?>
		<tr>
			<td><?php echo $list['details']; ?></td>
			<td><?php echo $list['action']; ?></td>
			<td><?php echo $list['reference']; ?></td>
		</tr>
		<?php if($list['file']!=''){ ?>
			<tr>
				<td colspan="3">
					<?php 
						$filelist = array_filter(explode(',', $list['file'])); 
						$i=1;
						foreach($filelist as $file){
							if(!file_exists('./assets/uploads/plumber/'.$plumberid.'/log/'.$file)) continue;
							$plumberimg = base64conversion(base_url().'assets/uploads/plumber/'.$plumberid.'/log/'.$file);
					?>
							<p style="display:inline-block;margin-right:10px;margin-bottom:10px;"><img src="<?php echo $plumberimg; ?>" width="215" height="200"></p>
					<?php
							if($i%3==0) echo '<br>';
							$i++;
						}
					?>
				</td>
			</tr>
		<?php } ?>      
	<?php } ?>      
	</tbody>                            
</table>

<h5 style="padding: 0 15px 0 15px;">DISCLAIMER: This document was developed by the PIRB to assist plumbers in providing a Non-Compliance notice.  The responsibility for notifying the user & owner, lies with the licenced plumber. This document simply is a guide and is not exhaustive.  </h5>

</body>
</html>
