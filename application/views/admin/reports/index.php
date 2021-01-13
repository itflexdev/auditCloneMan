<?php
$id 				= isset($result['id']) ? $result['id'] : '';
$report_name 		= isset($result['report_name']) ? $result['report_name'] : '';
$short_description 	= isset($result['short_description']) ? $result['short_description'] : '';
$query 				= isset($result['result_query']) ? $result['result_query'] : '';
?>
<div class="row page-titles">
	<div class="col-md-5 align-self-center">
		<h4 class="text-themecolor">Reports</h4>
	</div>
	<div class="col-md-7 align-self-center text-right">
		<div class="d-flex justify-content-end align-items-center">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?php echo base_url().'admin/dashboard'; ?>">Home</a></li>
				<li class="breadcrumb-item active">Reports</li>
			</ol>
		</div>
	</div>
</div>
<?php echo $notification; ?>
<div class="row">	
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<?php if($checkpermission){ ?>			
				
			<?php } ?>
				<!-- <div class="row mb_30">
					<div class="col-md-6">
						<a href="<?php // echo base_url().'admin/audits/index/index/1'; ?>" class="active_link_btn">Active</a>  <a href="<?php // echo base_url().'admin/audits/index/index/2'; ?>" class="archive_link_btn">Archive</a>
					</div>					
				</div> -->
				<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-body">
						<form class="form" method="post">
							<div class="form-group">
								<label for="name">Report Name</label>
								<input type="text" class="form-control" id="report_name" name="report_name" placeholder="Enter Report name" value="<?php echo $report_name; ?>">
							</div>
							<div class="form-group">
								<label for="name">Short Description</label>
								<textarea class="form-control" id="short_description" name="short_description" placeholder="Enter short description"><?php echo $short_description; ?></textarea>
							</div>
							<div class="form-group row">
								<div class="col-md-12">
									<label>Resulting SQL code.</label>
									<textarea style="height: 200px !important;" class="form-control message" rows="50" cols="50" name="message"><?php echo $query; ?></textarea>
								</div>
							</div>
							<div class="col-md-12 text-right">
								<input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
								<input type="hidden" id="executeid" name="executeid" value="">
								<button type="button" name="triggerbtn" data-toggle="modal" data-target="#reportdata" class="btn btn-primary triggerbtn displaynone">hidden</button>

								<?php if($checkpermission){ ?>
									<button type="button" id="create" class="btn btn-primary">Create</button>
									<button type="button" id="save" name="save" class="btn btn-primary">Save</button>
								<?php } ?>
								
								<button type="submit" id="hidensubmit" name="hidensubmit" class="btn btn-primary displaynone">hidensubmit</button>

								<input type="hidden" id="hiddenreportname" name="hiddenreportname" value="">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

				<div class="table-responsive m-t-40">
					<table class="table table-bordered table-striped datatables fullwidth">
						<thead>
							<tr>
								<th>Report Name</th>
								<th>Short Description</th>
								<th>Action</th>
							</tr>
						</thead>
					</table>
				</div>
				<div class="modal fade" id="reportdata" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					  <div class="modal-dialog" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <h5 class="modal-title" id="exampleModalLabel1">Report Results</h5>
					      </div>
					      <div class="modalloader"></div>
					      <div class="modalcontant">
					      	<div class="modal-body">
					        	<div class="appendtable displaynone">
					        		
					        	</div>
					        	<div class="modalloader1 displaynone"></div>
					      	</div>
						      <div class="modal-footer">
						        <button type="button" class="btn btn-secondary closebtn" data-dismiss="modal" onclick="fileunlinkfunc()">Close</button>
						         <button type="button" id="download" class="btn btn-primary displaynone" data-toggle="modal">Download</button>
						      </div>
					      </div>

					    </div>
					  </div>
					</div>

			</div>
		</div>
	</div>
</div>
		
<script>
	$(function(){
		datepicker('.dob');
		datatable();

		$('#save').click(function() {
		    $(".form").valid();
		    var injections = ["1=1", ";", "delete", "Delete", "truncate", "Truncate", "show", "Show", '""=""', "DROP", "drop", "@", "Repair","repair", "exec", "Exec", "update", "Update"];
		    var i;
		    var textValue= $('.message').val();
			for (i = 0; i < injections.length; i++) {
			  if (textValue.includes(injections[i]) ==true)
				{
				  alert('Given script is invalid Try again!');
				  return false;
				}
			}
			$(".form").submit();
		});
		$('#create').on('click' ,function() {
			$('#report_name').rules('remove', 'required');  // removes only specified rule(s)
			$('#short_description').rules('remove', 'required');  // removes only specified rule(s)
		   if($('.form').valid()==false){
				return false;
			}
			$('#hiddenreportname').val($('#report_name').val());
		    var injections = ["1=1", ";", "delete", "Delete", "truncate", "Truncate", "show", "Show", '""=""', "DROP", "drop", "@", "Repair","repair", "exec", "Exec", "update", "Update"];
		    var i;
		    var textValue= $('.message').val();
			for (i = 0; i < injections.length; i++) {
			  if (textValue.indexOf(injections[i]) ==true)
				{
				  alert('Given script is invalid Try again!');
				  return false;
				}
			}
			$( ".triggerbtn" ).trigger( "click" );
			var form_data = new FormData();
			form_data.append("id", $('#id').val());
			form_data.append("message", $('.message').val());
			$.ajax({
	      	data: form_data,
	        type: 'POST',
	        url: '<?php echo base_url().'admin/reports/index/queryExecution'; ?>',
	        contentType: false,  
            cache: false,  
            processData:false,
            beforeSend:function(){

	          $('.modalloader').html('<img src="<?php echo base_url().'assets/images/ajax-loader.gif'; ?>"/>');
	        },
            success:function(data)
	        {
	        	if(data ==='1'){
	        		$('.modalloader').hide()
		        	$('.appendtable, #download').removeClass('displaynone');
		        	$('.appendtable').append().html('Your request query is generated please click download for excel format');
	        	}else{
	        		$('.modalloader').hide()
		        	$('.appendtable').removeClass('displaynone');
		        	$('#download').addClass('displaynone');
	        		$('.appendtable').append().html('Given script is invalid Try again!');
	        	}
	         	
	        }
	      });
		});

		validation(
		'.form',
			{
				message : {
					required	: true,
				},
				report_name : {
					required	: true,
				},
				short_description : {
					required	: true,
				}
			},
			{
				message 	: {
					required	: "Query Feild is required.",
				},
				report_name : {
					required	: "Report name is required.",
				},
				short_description : {
					required	: "Short description is required.",
				}
			}
		);

			

	});

		
	
	$('.search').on('click',function(){		
		datatable(1);
	});
	
	function datatable(destroy=0){

		var options = {
			url 	: 	'<?php echo base_url()."admin/reports/index/DTReports"; ?>',
			columns : 	[							
							{ "data": "name" },
							{ "data": "description" },
							{ "data": "action" }
						],
						// data : {pagestatus : '<?php// echo $pagestatus; ?>'},
						target : [2],
						sort : '0'
		};
		
		ajaxdatatables('.datatables', options);
	}
	
	// Delete
	
	$(document).on('click', '.delete', function(){
		var action 	= 	'<?php echo base_url().'admin/reports/index'; ?>';
		var data	= 	'\
							<input type="hidden" value="'+$(this).attr('data-id')+'" name="id">\
							<input type="hidden" value="0" name="status">\
						';
						
		sweetalert(action, data);
	})
	function fileunlinkfunc(){
		var form_data = new FormData();
		form_data.append("reportname", $('#hiddenreportname').val());

		$('#executeid').val('');
		$('.modalloader1, .appendtable').addClass('displaynone');
		$('.modalloader').html('');
		$.ajax({
	      	data: form_data,
	        type: 'POST',
	        url: '<?php echo base_url().'admin/reports/index/file_unlink'; ?>',
	        contentType: false,  
            cache: false,  
            processData:false,
	        async: false,
            success:function(data)
	        {
	        	$('#hiddenreportname').val('');
	         	console.log(data);
	        }
	    });
}
	$(document).on('click', '#download', function(){
		var filename = '';
		var reportname = $('#hiddenreportname').val();
		if (reportname !='') {
			filename = reportname;
		}else{
			filename = 'Report';
		}
		var form_data = new FormData();
		form_data.append("id", $('#id').val());
		form_data.append("executeid", $('#executeid').val());
		form_data.append("message", $('.message').val());
		form_data.append("reportname", $('#hiddenreportname').val());
		var url = '<?php  echo base_url().'assets/uploads/temp/'; ?>'+filename+''+'.xlsx'+'';
		$.ajax({
			data: form_data,
	        type: 'POST',
	        url: '<?php  echo base_url().'admin/reports/index/download_report'; ?>',
	        contentType: false,  
            cache: false,  
            processData:false,
            beforeSend:function(){
              $('.modalloader1').removeClass('displaynone');
	          $('.modalloader1').html('<img src="<?php echo base_url().'assets/images/ajax-loader.gif'; ?>" width="100" height="100"/>');
	        },
            success:function(data)
	        {
	        	if(data =='1'){
					window.location.href = url;
	         		$('.modalloader1').addClass('displaynone');
	        	}else if(data =='0'){
	        		$('.modalloader1').addClass('displaynone');
	        		alert('Youre query has some errors please review the query');
	        	}
			 console.log(data);
	        }

		});
	});

	$(document).on('click', '#executequery', function(){
		var id 			= $(this).attr('data-id');
		var reportname 	= $(this).attr('data-reportname');
		$('#executeid').val(id);
		$( ".triggerbtn" ).trigger( "click" );
		$('#hiddenreportname').val(reportname);
		var form_data = new FormData();
		form_data.append("executeid", id);
		$.ajax({
	      	data: form_data,
	        type: 'POST',
	        url: '<?php echo base_url().'admin/reports/index/queryExecution'; ?>',
	        contentType: false,  
            cache: false,  
            processData:false,
            beforeSend:function(){
	          $('.modalloader').html('<img src="<?php echo base_url().'assets/images/ajax-loader.gif'; ?>"/>');
	        },
            success:function(data)
	        {
	        	
	        	if(data ==='1'){
	        		$('.modalloader').hide();
		        	$('.appendtable, #download').removeClass('displaynone');
		        	$('.appendtable').append().html('Your request query is generated please click download for excel format');
	        	}else{
	        		$('#executeid').val('');
	        		$('.modalloader').hide();
		        	$('.appendtable').removeClass('displaynone');
		        	$('#download').addClass('displaynone');
	        		$('.appendtable').append().html('Given script is invalid Try again!');
	        	}
	         	
	        }
	      });
	});
</script>
