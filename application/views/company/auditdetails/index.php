<div class="row page-titles">
	<div class="col-md-5 align-self-center">
		<h4 class="text-themecolor">Audit Details</h4>
	</div>
	<div class="col-md-7 align-self-center text-right">
		<div class="d-flex justify-content-end align-items-center">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
				<li class="breadcrumb-item active">Audit Details</li>
			</ol>
		</div>
	</div>
</div>
<?php echo $notification; ?>
<div class="row">	
	<div class="col-12">
		<div class="card">
			<div class="card-body">				
				<input type="hidden" name="usersid" id="usersid" value="<?php echo $usersid; ?>">
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
			url 	: 	'<?php echo base_url()."company/auditdetails/index/DTAuditStatement"; ?>',
			data 	: 	{ page : 'plumberauditorstatement' },		
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
