<div class="row page-titles">
	<div class="col-md-5 align-self-center">
		<h4 class="text-themecolor">Account Details</h4>
	</div>
	<div class="col-md-7 align-self-center text-right">
		<div class="d-flex justify-content-end align-items-center">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?php echo base_url().'admin/dashboard'; ?>">Home</a></li>
				<li class="breadcrumb-item"><a href="<?php echo base_url().'admin/company/index'; ?>">Company</a></li>
				<li class="breadcrumb-item active">Account Details</li>
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
			<h4 class="card-title">Account Details for <?php echo $user_details['company']?></h4>
			
			<div id="active" class="table-responsive m-t-40">
				<table class="table table-bordered table-striped datatables fullwidth">
					<thead>
						<tr>
							<th>Description</th>
							<th>Invoice Number</th>
							<th>Invoice Date</th>
							<th>Invoice Value</th>
							<th>Invoice Status</th>								
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
		
		var options = {
			url 	: 	'<?php echo base_url()."admin/company/index/DTAccounts"; ?>',			
			data	: 	{page : 'companyaccount', user_id : '<?php echo $companyid; ?>'},		
			columns : 	[
							{ "data": "description" },
							{ "data": "inv_id" },
							{ "data": "created_at" },
							{ "data": "total_cost" },
							{ "data": "invoicestatus" },							
							{ "data": "action" }
						],
			target : [5],
			sort : '0',
			order 	: [[0, 'desc']]
		};
		
		ajaxdatatables('.datatables', options);
	
	});
	
</script>
