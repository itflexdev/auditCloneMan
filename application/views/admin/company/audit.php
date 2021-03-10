<div class="row page-titles">
	<div class="col-md-5 align-self-center">
		<h4 class="text-themecolor">Audit Details</h4>
	</div>
	<div class="col-md-7 align-self-center text-right">
		<div class="d-flex justify-content-end align-items-center">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?php echo base_url().'admin/dashboard'; ?>">Home</a></li>
				<li class="breadcrumb-item"><a href="<?php echo base_url().'admin/company/index'; ?>">Company</a></li>
				<li class="breadcrumb-item active">Audit Details</li>
			</ol>
		</div>
	</div>
</div>

<?php 
echo $notification; 
if($roletype=='1'){ echo isset($menu) ? $menu : ''; } 
$pagestatus = isset($pagestatus) ? $pagestatus : '';
?>

<div class="row">
	<div class="col-12">
		<div class="card">
			<h4 class="card-title">Audit Details for <?php echo $user_details['company']?></h4>
						
				<input type="hidden" name="usersid" id="usersid" value="<?php echo $companyid; ?>">
				<div class="table-responsive m-t-40">
					<table class="table table-bordered table-striped datatables fullwidth">
						<thead>
							<tr>
								<th>COC Number</th>
								<th>Status</th>
								<th>Consumer</th>
								<th>Address</th>								
								<th>Refix Date</th>	
								<th>Auditor</th>								
								<th>Action</th>
							</tr>
						</thead>
					</table>
				</div>		
		</div>
	</div>
</div>


<script>
	$(function(){
		datatable();
	});
	
	$('.search').on('click',function(){		
		datatable(1);
	});
	
	function datatable(destroy=0){
		var companyid		= $('#usersid').val();
		var options = {
			url 	: 	'<?php echo base_url()."admin/company/index/DTaudit"; ?>',
			data 	: 	{ page : 'plumberauditorstatement', companyid : companyid },	
			columns : 	[							
							{ "data": "cocno" },
							{ "data": "status" },
							{ "data": "consumer" },							
							{ "data": "address" },
							{ "data": "refixdate" },
							{ "data": "auditor" },
							{ "data": "action" },
						],
			target : [6],
			sort : '0',
			order 	: 	[[0, 'desc']],
		};
		
		ajaxdatatables('.datatables', options);
	}
	
	
</script>
