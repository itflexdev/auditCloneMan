
<div class="row page-titles">
	<div class="col-md-5 align-self-center">
		<h4 class="text-themecolor">My Accounts</h4>
	</div>
	<div class="col-md-7 align-self-center text-right">
		<div class="d-flex justify-content-end align-items-center">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?php echo base_url().'admin/dashboard'; ?>">Home</a></li>
				<li class="breadcrumb-item"><a href="<?php echo base_url().'admin/plumber/index'; ?>">Home</a></li>
				<li class="breadcrumb-item active">My Accounts</li>
			</ol>
		</div>
	</div>
</div>

<?php 
echo $notification; 
if($roletype=='1'){ echo isset($menu) ? $menu : ''; } 
?>
				
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Account Details for <?php echo $user_details['name']." ".$user_details['surname']?></h4>
				<?php if ($invoicedetails =='' && $user_details['plumberstatus'] =='1') { ?>
					<a href="javascript:void(0);" class="active_link_btn trigerr-btn" style="margin-left: 112px !important;">Trigger Renewal</a>
				<?php } ?>
				<div class="modalloader displaynone"></div>
				<div id="active" class="table-responsive m-t-40">
					<table class="table table-bordered table-striped datatables fullwidth">
						<thead>
							<tr>
								<th>Description</th>
								<th>Invoice Number</th>
								<th>Invoice Date</th>
								<th>Invoice Value</th>
								<th>Invoice Status</th>
								<th>Order Status</th>
								<th>Action</th>	
							</tr>
						</thead>
					</table>
				</div>
				<h5 class="">Note: "Trigger Renewal" button will not available when there is active Invoice found.</h4>
			</div>
		</div>
	</div>
</div>

<script>
	$(function(){

$('.modalloader').html('<img src="<?php echo base_url().'assets/images/ajax-loader.gif'; ?>" height="50"/>');
		
		var options = {
			url 	: 	'<?php echo base_url()."admin/plumber/index/DTAccounts"; ?>',
			data    : 	{ user_id : '<?php echo $id; ?>'},
			columns : 	[
							{ "data": "description" },
							{ "data": "invoiceno" },
							{ "data": "invoicedate" },
							{ "data": "invoicevalue" },
							{ "data": "invoicestatus" },
							{ "data": "orderstatus" },
							{ "data": "action" }
						]			
		};
		
		ajaxdatatables('.datatables', options);

		$('.trigerr-btn').click(function(){
			$('.modalloader').removeClass('displaynone');
			$(this).prop('disabled', true);
			var form_data = new FormData();
	        form_data.append("id", '<?php echo $user_details['id']; ?>');

	        $.ajax({
		      	data: form_data,
		        type: 'POST',
		        url: '<?php echo base_url().'admin/plumber/index/triggerrenewal'; ?>',
		        contentType: false,  
	            cache: false,  
	            processData:false,
	            success:function(data)
		        {
		        	$('.modalloader').addClass('displaynone');
		        	sweetalertautoclose('Renewal triggered sucessfully.');
		        	location.reload();
		        }
		      });

		});
	
	});
	
</script>
