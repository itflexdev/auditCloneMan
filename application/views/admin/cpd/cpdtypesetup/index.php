<?php
if(isset($result) && $result){
	$id 				= $result['id'];
	$activityname 		= (set_value('activity')) ? set_value('activity') : $result['activity'];
	$startdate 			= (set_value('startdate')) ? set_value('startdate') : $result['startdate'];
	$points 			= (set_value('points')) ? set_value('points') : $result['points'];
	$cpdstream 			= (set_value('cpdstream')) ? set_value('cpdstream') : $result['cpdstream'];
	$enddate 			= (set_value('enddate')) ? set_value('enddate') : $result['enddate'];
	$productcode 		= (set_value('productcode')) ? set_value('productcode') : $result['productcode'];
	$qrcode 			= (set_value('qrcode')) ? set_value('qrcode') : $result['qrcode'];
	$status 			= (set_value('status')) ? set_value('status') : $result['status'];
	$link 				= (set_value('link')) ? set_value('link') : $result['link'];
	$description 		= (set_value('description')) ? set_value('description') : $result['description'];
	$proof 				= (set_value('proof')) ? set_value('proof') : $result['proof'];
	$hide 				= (set_value('hidden')) ? set_value('hidden') : $result['hidden'];
	
	$heading			= 'Update';
}else{
	$id 				= '';
	$activityname		= set_value('activity');
	$startdate			= set_value('startdate');
	$points				= set_value('points');
	$enddate			= set_value('enddate');
	$productcode		= set_value('productcode');
	$qrcode				= set_value('qrcode');
	$cpdstream			= set_value('cpdstream');
	$status				= set_value('status');
	$hide 				= set_value('hidden');
	$link 				= set_value('link');
	$description 		= set_value('description');
	$proof 				= set_value('proof');

	$heading			= 'Add';
}
$profileimg 			= base_url().'assets/images/profile.jpg';
$pdfimg 				= base_url().'assets/images/pdf.png';
$image 					= isset($result['image']) ? $result['image'] : '';
$filepath 				= base_url().'assets/uploads/cpdtypes/images/';
$filepath1				= (isset($result['image']) && $result['image']!='') ? $filepath.$result['image'] : base_url().'assets/uploads/cpdqueue/profile.jpg';
if($image!=''){
	$explodefile2 	= explode('.', $image);
	$extfile2 		= array_pop($explodefile2);
	$photoidimg 	= (in_array($extfile2, ['pdf', 'tiff'])) ? $pdfimg : $filepath1;
	$photoidurl 	= $filepath1;
}else{
	$photoidimg 	= $profileimg;
	$photoidurl 	= 'javascript:void(0);';
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
							<input type="text" class="form-control" id="activity" name="activityname" placeholder="Enter Activity *" value="<?php echo $activityname; ?>">						
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
							<?php echo form_dropdown('cpdstream1', $cpdstreamID, $cpdstream, ['id' => 'cpdstream', 'class' => 'form-control']); ?>					
						</div>
					</div>

					<div class="row">
						<div class="form-group col-md-12">
							<label for="imagelink">Image Link</label>
							<input type="text" name="imagelink" class="form-control" id="imagelink" placeholder="Image Link" value="<?php echo $link ?>">
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-12">
							<label for="description">Description</label>
							<textarea name="description" class="form-control" id="description" placeholder="Description"><?php echo $description ?></textarea>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-12">
							<label for="proofrequired">Proof Required</label>
							<textarea name="proofrequired" class="form-control" id="proofrequired" placeholder="Proof Required"><?php echo $proof ?></textarea>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-6">
							<h4 class="card-title">Activity Image *</h4>
								<div class="form-group">
									<div>
										<a href="<?php echo $photoidurl; ?>" target="_blank"><img src="<?php echo $photoidimg; ?>" class="document_image" width="100"></a>
									</div>
									<input type="file" id="file_2" class="document_file">
									<label for="file_2" class="choose_file">Choose File</label>
									<input type="hidden" name="image1" class="document percentageslide" value="<?php echo $image; ?>">
									<p>(Image/File Size Smaller than 5mb)</p>
								</div>
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
								<div class="custom-control custom-checkbox mr-sm-2 mb-3 pt-2">
									<input type="checkbox" class="custom-control-input" name="hidden_option" id="hidden_option" <?php if($hide=='1') echo 'checked'; ?> value="1">
									<label class="custom-control-label" for="hidden_option">Hidden</label>
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
								<div class="custom-control custom-checkbox mr-sm-2 mb-3 pt-2">
									<input type="checkbox" class="custom-control-input" name="hidden_option" id="hidden_option" <?php if($hide=='1') echo 'checked'; ?> value="1">
									<label class="custom-control-label" for="hidden_option">Hidden</label>
								</div>
							</div>
						<?php } ?>
						<div class="col-md-6 text-right" style="display: flex;justify-content: flex-end; align-items: end;">
							<?php if($id!='' && $pagestatus =='1'){ ?>
				<!-- <form class="importform"> -->
					<div class="tempp" style="margin-right: 20px;">
						<div class="massimport">
							<input type="file" id="file" class="cpdimport">
							<label for="file" class="choose_file massimport">Mass Import</label>
							<div class="cpdtemplate-link">
								<a id="excel-generate" href="javascript:void(0);">CSV Template</a>
							</div>
						</div>
						
					</div>
					<div class="massimport_btn_div">
						<button type="button" name="massimport" value="massimport" class="btn btn-primary massimport-btn" data-toggle="modal" data-target="#massimportmodal1">Import Activites</button>
						<div class="cpdtemplate-link">
							<a id="excel-generate" href="javascript:void(0);">CSV Template</a>
						</div>
					</div>
					<button type="button" id="triggerproceed1" class="btn btn-primary displaynone" data-toggle="modal" data-target="#massimportmodal2">Trigger Proceed(1/2)</button>
					<!-- popu (1/2) -->
					<div class="modal fade" id="massimportmodal1" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					  <div class="modal-dialog" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <h5 class="modal-title" id="exampleModalLabel1">CPD Mass Import(1/2)</h5>
					      </div>
					      <div class="modalloader"></div>
					      <div class="modalcontant">
					      	<div class="modal-body">
					        	<div class="appendtable"></div>
					      	</div>
						      <div class="modal-footer">
						        <button type="button" class="btn btn-secondary closebtn" data-dismiss="modal">Close</button>
						        <button type="button" id="proceed1" class="btn btn-primary" data-toggle="modal" data-dismiss="modal">Proceed(1/2)</button>
						        <button type="button" name="triggerbtn" class="triggerbtn" data-dismiss="modal" class="btn btn-primary">hidden</button>
						      </div>
					      </div>

					    </div>
					  </div>
					</div>
					<!-- popu (2/2) -->
					<div class="modal fade" id="massimportmodal2" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
					  <div class="modal-dialog" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <h5 class="modal-title" id="exampleModalLabel2">CPD Mass Import(2/2)</h5>
					      </div>
					      <div class="modalloader"></div>
					      <div class="modalcontant">
					      	<div class="modal-body">
					        	<div class="appendtable1"></div>
					      	</div>
						      <div class="modal-footer">
						        <button type="button" class="btn btn-secondary closebtn" data-dismiss="modal">Close</button>
						        <button type="button" class="btn btn-primary downloadxl">Download</button>
						        <button type="button" class="btn btn-primary proceed" >Proceed(2/2)</button>
						        <button type="button" name="triggerbtn" class="triggerbtn" data-dismiss="modal" class="btn btn-primary">hidden</button>
						      </div>
					      </div>

					    </div>
					  </div>
					</div>
				<!-- </form> -->
								
				<?php }?>
							<input type="hidden" id='codstream' name="cpdstream" value="<?php echo $cpdstream; ?>">
							<input type="hidden" id='activity' name="activity" value="<?php echo $activityname; ?>">
							<input type="hidden" id='cpdid' name="id" value="<?php echo $id; ?>">
							<input type="hidden" name="productcode" class="productcode" value="<?php echo $productcode; ?>">
							<button type="submit" name="submit" value="submit" class="btn btn-primary"><?php echo $heading; ?> CPD Type</button>
						</div>
					</div>
				</form>
			<?php } ?>
			
			

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
	function fileunlinkfunc(){
		$.ajax({
	      	// data: form_data,
	        type: 'POST',
	        url: '<?php echo base_url().'admin/cpd/cpdtypesetup/error_unlink'; ?>',
	        async: false,
            success:function(data)
	        {
	         	console.log(data);
	        }
	    });
}
	
	$(function(){
		var filepath 	= '<?php echo $filepath; ?>';
		var pdfimg		= '<?php echo $pdfimg; ?>';
		fileupload([".document_file", "./assets/uploads/cpdtypes/images", ['jpg','jpeg','png','tiff','tif']], ['.document', '.document_image', filepath, pdfimg]);
		// <?php //echo base_url().'admin/cpd/cpdtypesetup/massimport'; ?>

		// fileupload([".file1", "./assets/uploads/temp", ['xls', 'xlsx','csv']]);
		$('.triggerbtn').hide();
		$('.massimport-btn').hide();
		$('.massimport_btn_div').hide();
		$('.closebtn').click(function(){
			var form_data = new FormData();
	        form_data.append("file", document.getElementById('file').files[0]);
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
		         $('.massimport_btn_div').hide();
		         $('.massimport').show();
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
				activityname : {
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
				},
				imagelink : {
					required	: function() {
								return $('#status').is(":checked");
							},
					required	: function() {
								return $('#hidden_option').prop('checked') == false;
							}
				},
				description : {
					required	: function() {
								return $('#status').is(":checked");
							},
					required	: function() {
								return $('#hidden_option').prop('checked') == false;
							}
				},
				proofrequired : {
					required	: function() {
								return $('#status').is(":checked");
							},
					required	: function() {
								return $('#hidden_option').prop('checked') == false;
							}
				},
				image1 : {
					required	: true,
				},
			},
			{
				activityname 	: {
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
				},
				imagelink : {
					required	: "Image Link field is required."
				},
				description : {
					required	: "Description field is required."
				},
				proofrequired : {
					required	: "Proof Frequired field is required."
				},
				image1 : {
					required	: "Identity Document field is required.",
				},
			},
			{
				ignore : '.productcode',
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
	 		// if($('#file').val().split('\\').pop() !== 'cpd template.xlsx'){
	 		// 	$("#file").val('');
	 		// 	alert('Valid Template file is allowed');
	 		// 	return false;
	 		// }
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
		          $('.massimport').hide();
		          $('.massimport_btn_div').show();
		          $('.massimport-btn').show();
		        }
		      });
		});
		$('.massimport-btn').on('click', function(){
			var cpdpoints = '<?php echo $points; ?>';
			var form_data = new FormData();
			var oFReader = new FileReader();
			form_data.append("cpdid", $('#cpdid').val());
			form_data.append("cpdstream", $('#cpdstream').val());
			form_data.append("cpdpoints", cpdpoints);
			form_data.append("activity", $('#activity').val());
			form_data.append("filename", document.getElementById("file").files[0]);
	  		$('.modalcontant').hide();
		      $.ajax({

		      	data: form_data,
		        type: 'POST',
		        url: '<?php echo base_url().'admin/cpd/cpdtypesetup/proceed1'; ?>',
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
		$('#proceed1').on('click', function(){

			var cpdpoints = '<?php echo $points; ?>';
			var form_data = new FormData();
			var oFReader = new FileReader();
			form_data.append("cpdid", $('#cpdid').val());
			form_data.append("cpdstream", $('#cpdstream').val());
			form_data.append("cpdpoints", cpdpoints);
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
	            	$('.modalloader').show();
		          $('.modalloader').html('<img src="<?php echo base_url().'assets/images/ajax-loader.gif'; ?>"/>');
	            	setTimeout(function(){ $( "#triggerproceed1" ).trigger( "click" ); 
	            }, 800);
	              
		        },
	            success:function(data)
		        {
		         // $( "#triggerproceed1" ).trigger( "click" );
		         $('.modalcontant').show();
		         $('.modalloader').hide()
		         $('.appendtable1').append().html(data);
		         // $( "#triggerproceed1" ).trigger( "click" );
		        }
		      });
		});
		$('.downloadxl').on('click', function(){
			$('.massimport-btn').hide();
			$('.massimport_btn_div').hide();
			var url = '<?php echo base_url().'assets/uploads/cpdmassimport/cpd_errors.xlsx' ?>';
			//window.location.href = url;
		      $.ajax({
		        type: 'POST',
		        // data: form_data,
		        url: '<?php echo base_url().'admin/cpd/cpdtypesetup/importdownload'; ?>',
		        contentType: false,  
	            cache: false,  
	            processData:false,
	            success:function(data)
		        {
		        	$('.massimport').show();
		         	$("#file").val('');
		         	$('.massimport-btn').hide();
		         	$('.massimport_btn_div').hide();
		         	// $( ".triggerbtn-downloadxl" ).trigger( "click" );
		         	window.location.href = url;
		         	console.log(data)
		         	//fileunlinkfunc();
		        }
		      });
		});
		$('.proceed').on('click', function(){
			$('.massimport-btn').hide();
			$('.massimport_btn_div').hide();
			$('.downloadxl, .proceed, .closebtn').prop('disabled', true);
			var form_data = new FormData();
			form_data.append("cpdid", $('#cpdid').val());
			form_data.append("cpdstream", $('#cpdstream').val());
			form_data.append("activity", $('#activity').val());
			form_data.append("filename", document.getElementById("file").files[0]);
		      $.ajax({
		      	data: form_data,
		        type: 'POST',
		        url: '<?php echo base_url().'admin/cpd/cpdtypesetup/importproceed'; ?>',
		        contentType: false,  
	            cache: false,  
	            processData:false,
	            beforeSend:function(){
		          $('.modalloader').html('<img src="<?php echo base_url().'assets/images/ajax-loader.gif'; ?>"/>');
		        },
	            success:function(data)
		        {
		        $('.massimport-btn').hide();
		        $('.massimport_btn_div').hide();
		         console.log(data);
		         sweetalertautoclose(data);
		         $('.downloadxl, .proceed, .closebtn').prop('disabled', false);
		         $('.massimport').show();
		         $("#file").val('');
		         $( ".triggerbtn" ).trigger( "click" );
		        }
		      });
		});

		$('#excel-generate').on('click', function(){
			var filename = 'cpd template.xlsx';
			var form_data = new FormData();
			form_data.append("filename", filename);
			var url = '<?php echo base_url().'assets/uploads/cpdmassimport/sample/cpd template.xlsx' ?>';
			$.ajax({
		      	data: form_data,
		        type: 'POST',
		        url: '<?php echo base_url().'admin/cpd/cpdtypesetup/sampletemplate'; ?>',
		        contentType: false,  
	            cache: false,  
	            processData:false,
	            success:function(data)
		        {
		        	window.location.href = url;
		         	console.log(data);
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
