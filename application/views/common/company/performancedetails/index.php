<?php
$userid 			= isset($userid) ? $userid : '';
if(isset($result) && $result){
	$id 				= $result['id'];
	$document_type 		= (set_value('document_type')) ? set_value('document_type') : $result['document_type'];
	$date_of_renewal 	= (set_value('date_of_renewal')) ? set_value('date_of_renewal') : date('d-m-Y', strtotime($result['date_of_renewal']));	
	
	$heading			= 'Update';
}else{
	$id 				= '';
	$document_type		= set_value('document_type');
	$date_of_renewal	= set_value('date_of_renewal');	

	$heading			= 'Add';
}

$profileimg 			= base_url().'assets/images/profile.jpg';
$pdfimg 				= base_url().'assets/images/pdf.png';
$attachments 			= isset($result['attachments']) ? $result['attachments'] : '';
$filepath 				= base_url().'assets/uploads/company/documents/'.$userid.'/';
$filepath1				= (isset($result['attachments']) && $result['attachments']!='') ? $filepath.$result['attachments'] : base_url().'assets/uploads/cpdqueue/profile.jpg';
if($attachments!=''){
	$explodefile2 	= explode('.', $attachments);
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
		<h4 class="text-themecolor">Performance Details</h4>
	</div>
	<div class="col-md-7 align-self-center text-right">
		<div class="d-flex justify-content-end align-items-center">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?php echo base_url().'admin/dashboard'; ?>">Home</a></li>
				<li class="breadcrumb-item active">Performance Details</li>
			</ol>
		</div>
	</div>
</div>
<?php echo $notification; ?>
<?php  echo isset($menu) ? $menu : ''; ?>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Total Company Performance Value  = <?php echo $totalpoints; ?></h4>				
				<form class="mt-4 form" action="" method="post">
					<div class="row">
						<div class="form-group col-md-12">
							<label for="cpdstream">Document Type</label>
							<?php echo form_dropdown('document_type', $document_type_list, $document_type, ['id' => 'document_type', 'class' => 'form-control']); ?>
						</div>
					</div>
					<div class="row">						
						<div class="form-group col-md-12">
							<label for="startdate">Date of Renewal *</label>
							<input type="text" class="form-control" id="startdate" name="date_of_renewal" placeholder="Enter Date of Renewal *" value="<?php echo $date_of_renewal; ?>">						
						</div>
					</div>
					
					<div class="row">
						<div class="form-group col-md-6">
							<h4 class="card-title">Attachments *</h4>
								<div class="form-group">
									<div>
										<a href="<?php echo $photoidurl; ?>" target="_blank"><img src="<?php echo $photoidimg; ?>" class="document_image" width="100"></a>
									</div>
									<input type="file" id="file_2" class="document_file">
									<label for="file_2" class="choose_file">Choose File</label>
									<input type="hidden" name="image1" class="document percentageslide" value="<?php echo $attachments; ?>">
									<p>(Image/File Size Smaller than 5mb)</p>
								</div>
						</div>
					</div>					
				<!-- </form> -->
						<?php 
						if ($companystatus == 'Active') {						
						?>				
					<div class="row">
						<div class="col-md-12 text-right">
							<input type="hidden" name="id" value="<?php echo $id; ?>">	
							<input type="hidden" name="userid" value="<?php echo $userid; ?>">	
							<input type="hidden" name="userid" value="<?php echo $userid; ?>">					
							<button type="submit" name="submit" value="submit" class="btn btn-primary"><?php echo $heading; ?></button>
						</div>
					</div>	
					<?php 
						}
					?>															
				</form>			
			
						
				<div id="active" class="table-responsive m-t-40">
					<table class="table table-bordered table-striped datatables fullwidth">
						<thead>
							<tr>
								<th>Date of Upload/Update</th>
								<th>Date of Renewal</th>
								<th>Description</th>
								<th>Point Allocation</th>
								<th>Attachments</th>								
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

		var options = {
			url 	: 	'<?php echo base_url()."admin/company/index/DTCompanyperformancedetails"; ?>',		
			data 	: {compid : '<?php echo $userid; ?>'},
			columns : 	[
							{ "data": "updated_at" },
							{ "data": "date_of_renewal" },
							{ "data": "document_type" },						
							{ "data": "points" },
							{ "data": "attachments" },
							{ "data": "action" }
						],			
			target : [5],
			sort : '2'
		};
		
		ajaxdatatables('.datatables', options);	

		var filepath 	= '<?php echo $filepath; ?>';
		var pdfimg		= '<?php echo $pdfimg; ?>';
		var userid ='<?php echo $userid; ?>';
		fileupload([".document_file", "./assets/uploads/company/documents/"+userid, ['jpg','jpeg','png','tiff','tif','pdf']], ['.document', '.document_image', filepath, pdfimg]);
					
		validation(
			'.form',
			{
				document_type : {
					required	: true,
				},
				date_of_renewal : {
					required	: true,
				},
				image1 : {
					required	: true,
				},		
			},
			{
				document_type 	: {
					required	: "Please choose the Document Type."
				},
				date_of_renewal 	: {
					required	: "Date of Renewal field is required."
				},
				image1 : {
					required	: "Attachments field is required.",
				},		
			},
			{
				ignore : '.productcode',
			}
		);		
		
	});

	$( document ).ready(function() {	
		datepicker('#startdate', ['currentdate']);			
	});
	 

	// Delete
	$(document).on('click', '.delete', function(){
		var userid ='<?php echo $userid; ?>';
		var action 	= 	'<?php echo base_url().'admin/company/index/deleteDoc'; ?>';
		var data	= 	'\
		<input type="hidden" value="'+$(this).attr('data-id')+'" name="id">\
		<input type="hidden" value="'+userid+'" name="userid">\
		<input type="hidden" value="2" name="status">\
		';

		sweetalert(action, data);
	})
</script>
