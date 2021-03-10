<div class="row page-titles">
	<div class="col-md-5 align-self-center">
		<h4 class="text-themecolor">COC Statement</h4>
	</div>
	<div class="col-md-7 align-self-center text-right">
		<div class="d-flex justify-content-end align-items-center">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
				<li class="breadcrumb-item active">COC Statement</li>
			</ol>
		</div>
	</div>
</div>
<?php echo $notification; ?>
<div class="row">	
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<h5><b>Number of COC not Assigned</b></h5>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<input type="text" value="<?php echo $userorderstock; ?>" readonly>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<h5><b>Number of CoC's Able to purchase</b></h5>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">							
							<input type="text" value="<?php echo $coc_purchase; ?>" readonly>
						</div>
					</div>
				</div>

				<input type="hidden" name="usersid" id="usersid" value="<?php echo $usersid; ?>">
				<div class="table-responsive m-t-40">
					<table class="table table-bordered table-striped datatables fullwidth">
						<thead>
							<tr>
								<th>COC Number</th>
								<th>Status</th>
								<th>Date and Time of</br>Allocation</th>								
								<th>Licensed Plumber</br>Name Surname</th>
								<th>Customer</th>
								<th>Address</th>
								<th>Action</th>
							</tr>
						</thead>
					</table>
				</div>
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
		var user_id		= $('#usersid').val();
		var options = {
			url 	: 	'<?php echo base_url()."company/cocstatement/index/ajaxdtcompany"; ?>',
			data    :   { roletype:6,user_id : user_id},  			
			destroy :   destroy,  			
			columns : 	[							
							{ "data": "cocno" },
							{ "data": "status" },
							{ "data": "datetime" },							
							{ "data": "name" },							
							{ "data": "customer" },
							{ "data": "address" },
							{ "data": "action" },
						],
			target	:	[6],
			sort	:	'0'			
		};
		
		ajaxdatatables('.datatables', options);
	}
	
	
</script>
