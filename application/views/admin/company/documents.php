<?php
//echo "<pre>";print_r($result);die;
$description 	= isset($result['description']) ? $result['description'] : '';
$documentsid 	= isset($result['id']) ? $result['id'] : '';

$pdfimg 		= base_url().'assets/images/pdf.png';
$profileimg 	= base_url().'assets/images/profile.jpg';
$image 			= isset($result['file']) ? $result['file'] : set_value ('file');	
$filepath 		= base_url().'assets/uploads/company/';
$filepath1		= (isset($result['file']) && $result['file']!='') ? $filepath.$result['file'] : base_url().'assets/uploads/company/profile.jpg';
if($image!=''){
	$explodefile2 	= explode('.', $image);
	$extfile2 		= array_pop($explodefile2);

	$photoidimg 	= (in_array($extfile2, ['pdf', 'tiff'])) ? $pdfimg : $filepath1;
}else{
	$photoidimg 	= $profileimg;
}

?>

<div class="row page-titles">
	<div class="col-md-5 align-self-center">
		<h4 class="text-themecolor">Documents/Letters</h4>
	</div>
	<div class="col-md-7 align-self-center text-right">
		<div class="d-flex justify-content-end align-items-center">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?php echo base_url().'admin/dashboard'; ?>">Home</a></li>
				<li class="breadcrumb-item"><a href="<?php echo base_url().'admin/company/index'; ?>">Company</a></li>
				<li class="breadcrumb-item active">Documents/Letters</li>
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
			<h4 class="card-title">Documents/Letters for <?php echo $user_details['company']?></h4>

			<form class="mt-4 form documents" method="post" enctype="multipart/form-data">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Description *</label>
							<input type="text" class="form-control"  name="description" id="description"  value="<?php echo $description; ?>">
							</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<h4 class="card-title">Attachments</h4>
						<div class="form-group">
							<div>
								<img src="<?php echo $photoidimg; ?>" class="company_photo" width="100">
							</div>
							<input type="file" class="company_image">
							<input type="hidden" name="file1" class="company_picture" value="<?php echo $image; ?>">
							<p>(Image/File Size Smaller than 5mb)</p>
						</div>
					</div>
				</div>

				<div class="col-md-12 text-left">
					<input type="hidden" name="companyid" id="companyid" value="<?php echo $companyid; ?>">
					<input type="hidden" name="documentsid" id="documentsid" value="<?php echo $documentsid; ?>">
					<input type="hidden" name="user_id" id="user_id" value="<?php echo $user_details['id']; ?>">
					<input type="submit" name="submit" id="submit" value="submit" class="btn btn-primary">
				</div>				
			</form>	

			<div class="table-responsive m-t-40">
					<table class="table table-bordered table-striped datatables fullwidth">
						<thead>
							<tr>
								<th>Date of </br>Upload/Update</th>
								<th>Description</th>
								<th>Attachment</th>
								<th></th>
							</tr>
						</thead>
					</table>
				</div>		
		</div>
	</div>
</div>



<script type="text/javascript">


$(function(){
	var filepath 	= '<?php echo $filepath; ?>';
	var pdfimg		= '<?php echo $pdfimg; ?>';

	datatable();

	fileupload([".company_image", "./assets/uploads/company/", ['jpg','gif','jpeg','png','pdf','tiff','tif']], ['.company_picture', '.company_photo', filepath, pdfimg]);
	
	validation(
		'.documents',
		{
			
			description : {
				required	: true,
			},
			image : {
				required	: true,
			}
		},
		{
			
			description 	: {
				required	: "Description field is required.",
			}
		},
		{
			ignore : '.test',
		}
	);


	$('#submit').click(function(e){
		
		if($('form.documents').valid()==false){
			accord = $('.error_class_1').parents('.collapse').addClass('show');			
		}
		
	})




});

$('.search').on('click',function(){		
		datatable(1);
});

function datatable(destroy=0){
	var documentsid		= $('#documentsid').val();
	var companyid		= $('#companyid').val();
	var options = {
		url 	: 	'<?php echo base_url()."admin/company/index/DTDocuments"; ?>',
		data    :   { customsearch : 'listsearch1',documentsid : documentsid,companyid : companyid},  			
		destroy :   destroy,  			
		columns : 	[							
						{ "data": "datetime" },
						{ "data": "description" },
						{ "data": "file" },
						{ "data": "action" },
					]
	};
	
	ajaxdatatables('.datatables', options);
}

</script>

<style type="text/css">
.progress-circle span {
    display: none;
}
</style>