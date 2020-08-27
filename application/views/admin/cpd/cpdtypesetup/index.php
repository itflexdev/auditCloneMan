<?php
if(isset($result) && $result){
	$id 				= $result['id'];
	$activity 			= (set_value('activity')) ? set_value('activity') : $result['activity'];
	$startdate 			= (set_value('startdate')) ? set_value('startdate') : $result['startdate'];
	$points 			= (set_value('points')) ? set_value('points') : $result['points'];
	$cpdstream 			= (set_value('cpdstream')) ? set_value('cpdstream') : $result['cpdstream'];
	$enddate 			= (set_value('enddate')) ? set_value('enddate') : $result['enddate'];
	$productcode 		= (set_value('productcode')) ? set_value('productcode') : $result['productcode'];
	$qrcode 			= (set_value('qrcode')) ? set_value('qrcode') : $result['qrcode'];
	$status 			= (set_value('status')) ? set_value('status') : $result['status'];
	
	$heading			= 'Update';
}else{
	$id 				= '';
	$activity			= set_value('activity');
	$startdate			= set_value('startdate');
	$points				= set_value('points');
	$enddate			= set_value('enddate');
	$productcode		= set_value('productcode');
	$qrcode				= set_value('qrcode');
	$cpdstream			= set_value('cpdstream');
	$status				= set_value('status');

	$heading			= 'Add';
}
?>

<div class="row page-titles">
	<div class="col-md-5 align-self-center">
		<h4 class="text-themecolor">CPD Types</h4>
	</div>
	<div class="col-md-7 align-self-center text-right">
		<div class="d-flex justify-content-end align-items-center">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?php echo base_url().'admin/dashboard'; ?>">Home</a></li>
				<li class="breadcrumb-item active">CPD Types</li>
			</ol>
		</div>
	</div>
</div>
<?php echo $notification; ?>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<?php if ($checkpermission) { ?>
				<form class="mt-4 form" action="" method="post">
					<div class="row">
						<div class="form-group col-md-6">
							<label for="activity">Activity *</label>
							<input type="text" class="form-control" id="activity" name="activity" placeholder="Enter Activity *" value="<?php echo $activity; ?>">						
						</div>
						<div class="form-group col-md-6">
							<label for="startdate">CPD Start Date</label>
							<input type="text" class="form-control" id="startdate" name="startdate" placeholder="Enter CPD Start Date *" value="<?php echo $startdate; ?>">						
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-6">
							<label for="points">CPD Points</label>
							<input type="number" class="form-control" min="0.1" step=".01" id="points" name="points" placeholder="Enter CPD Points *" value="<?php echo $points; ?>">						
						</div>					
						<div class="form-group col-md-6">
							<label for="enddate">CPD End Date</label>
							<input type="text" class="form-control" id="enddate" name="enddate" placeholder="Enter End Date *" value="<?php echo $enddate; ?>">						
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-6">
							<label for="productcode">Product Code</label>
							<input type="text" class="form-control" id="productcode" placeholder="Product Code will generate automatically" readonly value="<?php echo $productcode; ?>">						
						</div>
						<div class="form-group col-md-6">
							<label for="cpdstream">CPD Stream</label>
							<?php echo form_dropdown('cpdstream', $cpdstreamID, $cpdstream, ['id' => 'cpdstream', 'class' => 'form-control']); ?>					
						</div>
					</div>
					<?php
					if(isset($qrcode) && $qrcode){ ?>
						<div class="row">
							<div class="col-md-6">
								<img src="<?php echo base_url().'assets/qrcode/'.$qrcode.''; ?>" height="200" width="200">
							</div>
							<div class="col-md-6">
								<div class="custom-control custom-checkbox mr-sm-2 mb-3 pt-2">
									<input type="checkbox" class="custom-control-input" name="status" id="status" <?php if($status=='1') echo 'checked'; ?> value="1">
									<label class="custom-control-label" for="status">Active</label>
								</div>
							</div>
						</div>
						<?php
					}
					?>
					<div class="row">
						<?php if(isset($qrcode) && $qrcode){ ?>
							<div class="col-md-6">
								<a href="<?php echo base_url().'admin/cpd/cpdtypesetup/getPDF/'.$id.''; ?>" class="btn btn-primary">Download PDF</a>
							</div>
						<?php }else{ ?>
							<div class="col-md-6">
								<div class="custom-control custom-checkbox mr-sm-2 mb-3 pt-2">
									<input type="checkbox" class="custom-control-input" name="status" id="status" <?php if($status=='1') echo 'checked'; ?> value="1">
									<label class="custom-control-label" for="status">Active</label>
								</div>
							</div>
						<?php } ?>
						

						<div class="col-md-6 text-right">
							<input type="hidden" id='codstream' name="cpdstream" value="<?php echo $cpdstream; ?>">
							<input type="hidden" id='activity' name="activity" value="<?php echo $activity; ?>">
							<input type="hidden" id='cpdid' name="id" value="<?php echo $id; ?>">
							<input type="hidden" name="productcode" value="<?php echo $productcode; ?>">
							<button type="submit" name="submit" value="submit" class="btn btn-primary"><?php echo $heading; ?> CPD Type</button>
						</div>
					</div>
				</form>
			<?php } ?>
			<?php if($id!=''){ ?>
				<!-- <form class="importform"> -->
					<div class="col-md-6 massimport">
						<input type="file" id="file" class="cpdimport">
						<label for="file" class="choose_file massimport">CPD Template</label>
						<button type="button" name="massimport" value="massimport" class="btn btn-primary massimport-btn" data-toggle="modal" data-target="#massimportmodal"> Mass Import</button>
						
					</div>
					<div class="modal fade" id="massimportmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					  <div class="modal-dialog" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <h5 class="modal-title" id="exampleModalLabel">CPD Mass Import</h5>
					        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          <span aria-hidden="true">&times;</span>
					        </button> -->
					      </div>
					      <div class="modalloader"></div>
					      <div class="modalcontant">
					      	<div class="modal-body">
					        	<div class="appendtable"></div>
					      	</div>
						      <div class="modal-footer">
						        <button type="button" class="btn btn-secondary closebtn" data-dismiss="modal">Close</button>
						        <button type="button" class="btn btn-primary downloadxl" data-dismiss="modal">Download</button>
						        <button type="button" class="btn btn-primary proceed" >Proceed</button>
						        <button type="button" name="triggerbtn" class="triggerbtn" data-dismiss="modal" class="btn btn-primary">hidden</button>
						      </div>
					      </div>

					    </div>
					  </div>
					</div>
				<!-- </form> -->
								
				<?php }?>
			

				<div class="row">
					<div class="col-md-6">
						<a href="<?php echo base_url().'admin/cpd/cpdtypesetup/index/1'; ?>" class="active_link_btn">Active</a>  <a href="<?php echo base_url().'admin/cpd/cpdtypesetup/index/2'; ?>" class="archive_link_btn">Archive</a>
					</div>					
				</div>
				<div id="active" class="table-responsive m-t-40">
					<table class="table table-bordered table-striped datatables fullwidth">
						<thead>
							<tr>
								<th>Product Code</th>
								<th>Activity</th>
								<th>CPD Start Date</th>
								<th>CPD End Date</th>
								<th>CPD Stream</th>
								<th>CPD Points</th>
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
		// <?php //echo base_url().'admin/cpd/cpdtypesetup/massimport'; ?>

		// fileupload([".file1", "./assets/uploads/temp", ['xls', 'xlsx','csv']]);
		$('.triggerbtn').hide();
		$('.massimport-btn').hide();
		$('.closebtn').click(function(){
			var form_data = new FormData();
	        form_data.append("filename", document.getElementById('file').files[0]);
		      $.ajax({
		      	data: form_data,
		        type: 'POST',
		        url: '<?php echo base_url().'admin/cpd/cpdtypesetup/cancel'; ?>',
		        contentType: false,  
	            cache: false,  
	            processData:false,
	            success:function(data)
		        {
		         $('.massimport-btn').hide();
				 $("#file").val('');
				 console.log(data);
		        }
		      });
		});

		var options = {
			url 	: 	'<?php echo base_url()."admin/cpd/cpdtypesetup/DTCpdType"; ?>',
			columns : 	[
			{ "data": "productcode" },
			{ "data": "activity" },
			{ "data": "startdate" },
			{ "data": "enddate" },
			{ "data": "cpdstream" },
			{ "data": "points" },
			{ "data": "action" }
			],
			data : {pagestatus : '<?php echo $pagestatus; ?>'},
			target : [6],
			sort : '0'
		};
		
		ajaxdatatables('.datatables', options);
		
		validation(
			'.form',
			{
				activity : {
					required	: true,
				},
				startdate : {
					required	: true,
				},
				points : {
					required	: true,
				},
				enddate : {
					required	: true,
				},
				productcode : {
					required	: true,
				},
				stream : {
					required	: true,
				}
			},
			{
				activity 	: {
					required	: "Activity field is required."
				},
				startdate 	: {
					required	: "Start Date field is required."
				},
				points 	: {
					required	: "Points field is required."
				},
				enddate 	: {
					required	: "End Date field is required."
				},
				productcode 	: {
					required	: "Product Code field is required."
				},
				stream 	: {
					required	: "CPD Stream field is required."
				}
			}
			);

		
		
	});
	$( document ).ready(function() {
		
		datepicker('#startdate', ['currentdate']);
		datepicker('#enddate', ['currentdate']);
	});
	 $(document).ready(function() {
	 	
	 	$('.cpdimport').on('change', function(){
	 		if($('#file').val().split('.').pop() !=='xlsx'){
	 			$("#file").val('');
	 			alert('Only Excel file is allowed');
	 			return false;
	 		}
	 		// if ($(this.files[0])) {}
			var form_data = new FormData();
	        var oFReader = new FileReader();
	        // oFReader.readAsDataURL(document.getElementById("file").files[0])
	        form_data.append("file", document.getElementById('file').files[0]);
		      $.ajax({

		      	data: form_data,
		        type: 'POST',
		        url: '<?php echo base_url().'admin/cpd/cpdtypesetup/massimport'; ?>',
		        contentType: false,  
	            cache: false,  
	            processData:false,
	            success:function(data)
		        {
		          $('.massimport-btn').show();
		        }
		      });
		});
		$('.massimport-btn').on('click', function(){
			var form_data = new FormData();
			var oFReader = new FileReader();
			form_data.append("cpdid", $('#cpdid').val());
			form_data.append("cpdstream", $('#cpdstream').val());
			form_data.append("activity", $('#activity').val());
			form_data.append("filename", document.getElementById("file").files[0]);
	  		$('.modalcontant').hide();
		      $.ajax({

		      	data: form_data,
		        type: 'POST',
		        url: '<?php echo base_url().'admin/cpd/cpdtypesetup/massimport'; ?>',
		        contentType: false,  
	            cache: false,  
	            processData:false,
	            beforeSend:function(){
		          $('.modalloader').html('<img src="<?php echo base_url().'assets/images/ajax-loader.gif'; ?>"/>');
		        },
	            success:function(data)
		        {
		         $('.modalcontant').show();
		         $('.modalloader').hide()
		         $('.appendtable').append().html(data);
		         // $( ".triggerbtn" ).trigger( "click" );
		        }
		      });
		});
		$('.downloadxl').on('click', function(){
			$('.massimport-btn').hide();
			var url = '<?php echo base_url().'assets/uploads/cpdmassimport/cpd_template.xlsx' ?>';
			window.location.href = url;
		      $.ajax({
		        type: 'POST',
		        url: '<?php echo base_url().'admin/cpd/cpdtypesetup/importdownload'; ?>',
		        contentType: false,  
	            cache: false,  
	            processData:false,
	            success:function(data)
		        {
		         $("#file").val('');
		         $('.massimport-btn').hide();
		         console.log(data)
		        }
		      });
		});
		$('.proceed').on('click', function(){
			$('.massimport-btn').hide();
			var form_data = new FormData();
			form_data.append("cpdid", $('#cpdid').val());
			form_data.append("cpdstream", $('#cpdstream').val());
			form_data.append("activity", $('#activity').val());
		      $.ajax({
		      	data: form_data,
		        type: 'POST',
		        url: '<?php echo base_url().'admin/cpd/cpdtypesetup/importproceed'; ?>',
		        contentType: false,  
	            cache: false,  
	            processData:false,
	            success:function(data)
		        {
		        $('.massimport-btn').hide();
		         console.log(data);
		         sweetalertautoclose(data);
		         $("#file").val('');
		         $( ".triggerbtn" ).trigger( "click" );
		        }
		      });
		});
		
	 });
	
	// Delete
	
	$(document).on('click', '.delete', function(){
		var action 	= 	'<?php echo base_url().'admin/cpd/cpdtypesetup'; ?>';
		var data	= 	'\
		<input type="hidden" value="'+$(this).attr('data-id')+'" name="id">\
		<input type="hidden" value="2" name="status">\
		';

		sweetalert(action, data);
	})
</script>
