<?php

// echo "<pre>";print_r($result);die;
	$pdfimg 				= base_url().'assets/images/pdf.png';
	$profileimg 			= base_url().'assets/images/profile.jpg';
	$opprofileimg 			= base_url().'assets/images/profile.jpg';
	$opfile2 				= isset($result['admin_image']) ? $result['admin_image'] : '';
	$pdfimg 				= base_url().'assets/images/pdf.png';
	$filepath1				= base_url().'assets/uploads/auditor/statement/';
	$adminreason 			= isset($result['admin_comments']) ? $result['admin_comments'] : '';
	$opphotoidurl			= $profileimg;

	/*if($opfile2!=''){
		$explodefile1 	= explode('.', $opfile2);
		$extfile1 		= array_pop($explodefile1);
		$opprofileimg 	= (in_array($extfile1, ['pdf', 'tiff'])) ? $pdfimg : $filepath1.$opfile2;
		$opphotoidurl 	= $filepath1.$opfile2;
	}else{
		$opprofileimg 	= $profileimg;
		$opphotoidurl	= 'javascript:void(0);';
	}*/

	$reviewpath 			= base_url().'assets/uploads/auditor/statement/';
	$datetime 				= date('d-m-Y H:i:s');
	
	$cocid 					= isset($result['id']) ? $result['id'] : '';
	
	$plumberid 				= isset($result['u_id']) ? $result['u_id'] : '';
	$plumberregno 			= isset($result['plumberregno']) ? $result['plumberregno'] : '';
	$plumbername 			= isset($result['u_name']) ? $result['u_name'] : '';
	$plumberwork 			= isset($result['u_work']) ? $result['u_work'] : '';
	$plumbermobile 			= isset($result['u_mobile']) ? $result['u_mobile'] : '';
	$plumberfile 			= isset($result['u_file']) ? $result['u_file'] : '';
	
	$filepath				= base_url().'assets/uploads/plumber/'.$plumberid.'/';
	
	if($plumberfile!=''){
		$explodefile2 		= explode('.', $plumberfile);
		$extfile2 			= array_pop($explodefile2);
		$plumberimage 		= (in_array($extfile2, ['pdf', 'tiff'])) ? $pdfimg : $filepath.$plumberfile;
	}else{
		$plumberimage 		= $profileimg;
	}
	
	$auditorid 				= isset($result['auditorid']) ? $result['auditorid'] : '';
	$auditorname 			= isset($result['auditorname']) ? $result['auditorname'] : '';
	$auditormobile 			= isset($result['auditormobile']) ? $result['auditormobile'] : '';
	$auditordate 			= isset($result['audit_allocation_date']) && $result['audit_allocation_date']!='1970-01-01' ? date('d-m-Y', strtotime($result['audit_allocation_date'])) : '';
	$auditorstatus 			= isset($this->config->item('auditstatus')[$result['audit_status']]) ? $this->config->item('auditstatus')[$result['audit_status']] : '';
	$audit_statusid 		= isset($result['audit_status']) ? $result['audit_status'] : '';
	$auditstatus_array 		= $this->config->item('auditstatus');
	
	$completiondate 		= isset($result['cl_completion_date']) && $result['cl_completion_date']!='1970-01-01' ? date('d-m-Y', strtotime($result['cl_completion_date'])) : '';
	$name 					= isset($result['cl_name']) ? $result['cl_name'] : '';
	$address 				= isset($result['cl_address']) ? $result['cl_address'] : '';
	$street 				= isset($result['cl_street']) ? $result['cl_street'] : '';
	$number 				= isset($result['cl_number']) ? $result['cl_number'] : '';
	$provinceid 			= isset($result['cl_province']) ? $result['cl_province'] : '';
	$cityid 				= isset($result['cl_city']) ? $result['cl_city'] : '';
	$suburbid 				= isset($result['cl_suburb']) ? $result['cl_suburb'] : '';
	$contactno 				= isset($result['cl_contact_no']) ? $result['cl_contact_no'] : '';
	$alternateno 			= isset($result['cl_alternate_no']) ? $result['cl_alternate_no'] : '';
	
	$statementid 			= isset($result['as_id']) ? $result['as_id'] : '';
	$auditdate 				= isset($result['as_audit_date']) && ($result['as_audit_date']!='1970-01-01' && $result['as_audit_date']!='0000-00-00') ? date('d-m-Y', strtotime($result['as_audit_date'])) : '';
	$workmanshipid 			= isset($result['as_workmanship']) ? $result['as_workmanship'] : '';
	$plumberverification 	= isset($result['as_plumber_verification']) ? $result['as_plumber_verification'] : '';
	$cocverification 		= isset($result['as_coc_verification']) ? $result['as_coc_verification'] : '';
	$hold 					= isset($result['as_hold']) ? $result['as_hold'] : '';
	$reason 				= isset($result['as_reason']) ? $result['as_reason'] : '';
	$auditcomplete 			= isset($result['as_auditcomplete']) ? $result['as_auditcomplete'] : '';
	$refixrefuse 			= isset($result['as_refix_refuse']) ? $result['as_refix_refuse'] : '';
	$refixcompletedate 		= isset($result['as_refixcompletedate']) && $result['as_refixcompletedate']!='' ? date('d-m-Y', strtotime($result['as_refixcompletedate'])) : date('d-m-Y');
	$as_buttonstatus 			= isset($result['as_buttonstatus']) ? $result['as_buttonstatus'] : '';
	
	$reviewtableclass		= ['1' => 'review_failure', '2' => 'review_cautionary', '3' => 'review_compliment', '4' => 'review_noaudit'];
	
	if($pagetype=='action' && ($as_buttonstatus =='0' || $as_buttonstatus =='')){
		$pagetype 		= '1';
		$disabled1 		= '';
		$disabled1array = [];
	}else if($pagetype=='action' && $as_buttonstatus !='0'){
		$pagetype 		= '1';
		$disabled1 		= 'disabled';
		$disabled1array	= ['disabled' => 'disabled'];
	}else if($pagetype=='view'){
		$pagetype 		= '2';
		$disabled1 		= 'disabled';
		$disabled1array	= ['disabled' => 'disabled'];
	}
	
	if($roletype=='1'){
		$heading = 'Manage Allocted Audits';
	}else if($roletype=='3' || $roletype=='5'){
		$heading = 'Audit Report';
	}
		
	if($workmanshipid=='' && $roletype=='1') $workmanship = [];
	if($plumberverification=='' && $roletype=='1') $yesno = [];
	
	$chatfilepath	= base_url().'assets/uploads/chat/'.$cocid.'/';
	$downloadurl	= base_url().(isset($downloadattachment) ? $downloadattachment : '');


	$strrefixdate 	= date('d-m-Y', strtotime($result['ar1_refix_date']));
	$curdatestr 	= date('d-m-Y', strtotime($datetime))

	

?>

<div class="row page-titles">
	<div class="col-md-5 align-self-center">
		<h4 class="text-themecolor"><?php echo $heading; ?></h4>
	</div>
	<div class="col-md-7 align-self-center text-right">
		<div class="d-flex justify-content-end align-items-center">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">Home</li>
				<li class="breadcrumb-item active"><?php echo $heading; ?></li>
			</ol>
		</div>
	</div>
</div>
<?php echo $notification; ?>
<?php if($roletype=='1' || $roletype=='5'){ echo isset($menu) ? $menu : ''; } ?>
<div class="row">
	<div class="col-12">
		<div class="card">
			<form class="mt-4 form" action="" method="post">
				
				<?php if($roletype=='1' || $roletype=='5'){ ?>
					<h4 class="card-title">Plumber Details</h4>
					<div class="row">
						<div class="col-md-8">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>Registration Number</label>
										<input type="text" class="form-control" value="<?php echo $plumberregno; ?>" disabled>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Plumbers Name and Surname</label>
										<input type="text" class="form-control" value="<?php echo $plumbername; ?>" disabled>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Phone (Work)</label>
										<input type="text" class="form-control" value="<?php echo $plumberwork; ?>" disabled>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Phone (Mobile)</label>
										<input type="text" class="form-control" value="<?php echo $plumbermobile; ?>" disabled>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<img src="<?php echo $plumberimage; ?>" width="100">
						</div>
					</div>
				<?php } ?>
				
				<h4 class="card-title">COC Details</h4>
				<p><a target="blank" href="<?php echo base_url().$viewcoc.'/'.$cocid.'/'.$plumberid; ?>">View COC Details in full</a></p>
				<div class="row">					
					<div class="col-md-6">
						<div class="form-group">
							<label>Certificate No</label>
							<input type="text" class="form-control" name="name" value="<?php echo $cocid; ?>" disabled>
						</div>
					</div>					
					<div class="col-md-6">
						<div class="form-group">
							<label>Plumbing Work Completion Date</label>
							<div class="input-group">
								<input type="text" class="form-control completion_date" name="completion_date" data-date="datepicker" value="<?php echo $completiondate; ?>" disabled>
								<div class="input-group-append">
									<span class="input-group-text"><i class="icon-calender"></i></span>
								</div>
							</div>
						</div>
					</div>					
					<div class="col-md-12">
						<div class="form-group">
							<label>Owners Name</label>
							<input type="text" class="form-control" name="name" value="<?php echo $name; ?>" disabled>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label>Name of Complex/Flat (if applicable)</label>
							<input type="text" class="form-control" name="address" value="<?php echo $address; ?>" disabled>
						</div>
					</div>
					<div class="col-md-8">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Street</label>
									<input type="text" class="form-control" name="street" value="<?php echo $street; ?>" disabled>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Number</label>
									<input type="text" class="form-control" name="number" value="<?php echo $number; ?>" disabled>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Province</label>
									<?php
										echo form_dropdown('province', $province, $provinceid, ['id' => 'province', 'class'=>'form-control', 'disabled' => 'disabled']);
									?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>City</label>
									<?php 
										echo form_dropdown('city', [], $cityid, ['id' => 'city', 'class' => 'form-control', 'disabled' => 'disabled']); 
									?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Suburb</label>
									<?php
										echo form_dropdown('suburb', [], $suburbid, ['id' => 'suburb', 'class'=>'form-control', 'disabled' => 'disabled']);
									?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Contact Mobile</label>
									<input type="text" class="form-control" name="contact_no" id="contact_no" value="<?php echo $contactno; ?>" disabled>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Alternate Contact</label>
									<input type="text" class="form-control" name="alternate_no" id="alternate_no" value="<?php echo $alternateno; ?>" disabled>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div id="addressmap" style="height:100%"></div>
					</div>
				</div>
				
				<h4 class="card-title">Audit Review</h4>		
				<?php if($roletype=='1' || $roletype=='3'){ ?>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Audit Status</label>
								<?php
								if ($roletype =='1') $disableFlag = 'disabled';
								else $disableFlag = ['disabled' => 'disabled'];

								//echo form_dropdown('auditorstatus', $auditstatus_array, $audit_statusid, ['id' => 'auditorstatus', 'class'=>'form-control', $disableFlag]); ?>
								 <input type="text" class="form-control" value="<?php echo $auditorstatus; ?>" <?php if ($roletype=='1' || $roletype=='3') { ?> disabled <?php } ?>> 
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Auditors Name and Surname</label>
								<input type="text" class="form-control" value="<?php echo $auditorname; ?>" disabled>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Phone (Mobile)</label>
								<input type="text" class="form-control" value="<?php echo $auditormobile; ?>" disabled>
							</div>
						</div>
					</div>
				<?php } ?>
				<div class="row">
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>Date of Audit</label>
									<div class="input-group">
										<input type="text" class="form-control auditdate" name="auditdate" data-date="datepicker" value="<?php echo $auditdate; ?>" <?php echo $disabled1; ?>>
										<div class="input-group-append">
											<span class="input-group-text"><i class="icon-calender"></i></span>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Overall Workmanship</label>
									<?php
										echo form_dropdown('workmanship', ['' => 'Select Option']+$workmanship, $workmanshipid, ['id' => 'workmanship', 'class'=>'form-control', 'data-select' => 'select2']+$disabled1array);
									?>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Licensed Plumber Present</label>
									<?php
										echo form_dropdown('plumberverification', ['' => 'Select Option']+$yesno, $plumberverification, ['id' => 'plumberverification', 'class'=>'form-control', 'data-select' => 'select2']+$disabled1array);
									?>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Was COC Completed Correctly</label>
									<?php
										echo form_dropdown('cocverification', ['' => 'Select Option']+$yesno, $cocverification, ['id' => 'cocverification', 'class'=>'form-control', 'data-select' => 'select2']+$disabled1array);
									?>
								</div>
							</div>
						</div>
					</div>					
					<div class="col-md-6">
						<div class="row">	
							<?php if(($roletype=='5' && $pagetype=='1') && ($as_buttonstatus == '0' || $hold=='1')){ ?>
								<div class="col-md-12">
									<div class="form-group custom-control custom-radio">							
										<input type="radio" class="custom-control-input" name="hold" id="hold" value="1" <?php if($hold=='1'){ echo 'checked'; } ?>>
										<label class="custom-control-label" for="hold">Place Audit on hold</label>
									</div>
								</div>
								<div class="col-md-12 reason_wrapper displaynone">
									<div class="form-group">
										<label>Why was Audit placed on hold?</label>	
										<textarea class="form-control"  name="reason" id="reason" rows="4" cols="50"><?php echo $reason; ?></textarea>			
									</div>
								</div>		
							<?php } ?>	
							<div class="col-md-12">
								<div class="form-group">
									<label>Date Allocated to Auditor</label>
									<div class="input-group">
										<input type="text" class="form-control" data-date="datepicker" value="<?php echo $auditordate; ?>" disabled>
										<div class="input-group-append">
											<span class="input-group-text"><i class="icon-calender"></i></span>
										</div>
									</div>
								</div>
							</div>							
							<?php if($auditcomplete=='1' && $pagetype=='2'){ ?>		
								<div class="col-md-12">
									<a href="<?php echo base_url().'/'.$auditreport; ?>">
										<img src="<?php echo $pdfimg; ?>" width="50">
										<span>Audit Report</span>
									</a>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
				
				<div class="row form-group">
					<div class="col-md-12">
						<div class="table-responsive">
							<table class="table table-bordered reviewtable">								
								<tr>
									<th>Review Type</th>
									<th>Statement</th>
									<th>Comments</th>
									<?php //if($pagetype=='2'){ ?>
										<th>SANS/Regulation/Bylaw Reference</th>
										<th>Knowledge Reference link</th>
									<?php //} ?>
									<th>Images</th>
									<th>Performance Points</th>
									<th style="min-width:160px!important;">Refix Status</th>
									<?php if(($roletype=='5' && $pagetype=='1') || ($roletype=='1' && $pagetype=='2')){ ?>
										<th>Action</th>
									<?php } ?>
								</tr>
								<tr class="reviewnotfound"> 
									<td colspan="9">No Record Found</td>
								</tr>
							</table>
							<input type="hidden" class="attachmenthidden" name="attachmenthidden">
						</div>
						<?php  if($pagetype=='2'){
								if($refixrefuse =='1'){ ?>
									<div style="font-weight:900 !important;"><h3>The Client has refused refix</h3></div>
								<?php }
							}?>
					</div>
					<?php if((($roletype=='5' && $pagetype=='1') && ($as_buttonstatus =='0' || $as_buttonstatus =='')) || (($roletype=='1' && $pagetype=='2') && ($result['audit_status'] !='4' && $result['audit_status'] !='1'))){ ?>
						<div class="row text-right">
							<button type="button" data-toggle="modal" id="addreviews" data-target="#reviewmodal" class="btn btn-primary">Add a Review</button>
						</div>
					<?php } ?>
				</div>
								
				<div class="row">
					<div class="col-md-6">
						<div class="row">
							<?php if($roletype=='5' && $pagetype=='1'){ ?> 
							<div class="col-md-6 refuserefix_wrapper displaynone">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" id="refuserefix" class="custom-control-input refuserefix" name="refuserefix" value="1" <?php if($refixrefuse =='1'){ echo "checked='checked'"; } ?>>
									<label class="custom-control-label" for="refuserefix">Client refused refixes</label>
								</div>
							</div>
							<?php } ?>
							<div class="col-md-12 refixcompletedate_wrapper displaynone">
								<div class="form-group">
									<label>Refix Completed date</label>
									<input type="text" class="form-control" name="refixcompletedate" id="refixcompletedate">
								</div>
							</div>
							<div class="col-md-12 refix_wrapper displaynone">
								<div class="form-group">
									<label><?php echo  $roletype=='5' ? "Refix Period (Days)" : "Refix's to this Audit review are to be completed by latest"; ?></label>
									<input type="text" class="form-control" name="refixperiod" id="refixperiod" value="<?php echo $settings['refix_period']; ?>" readonly>
								</div>
							</div>
							<?php if($pagetype=='1'){ ?>
								<div class="col-md-12 report_wrapper displaynone">
									<div class="form-group">
										<label>Date and Time of Report submitted:</label>
										<input type="text" class="form-control" name="reportdate" id="reportdate" value="<?php echo $datetime; ?>" readonly>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
					<?php if($roletype=='5' && $pagetype=='1'){ ?>
						<div class="col-md-6 auditcomplete_wrapper displaynone">
							<div class="custom-control custom-checkbox">
								<input type="checkbox" id="auditcomplete" class="custom-control-input auditcomplete" name="auditcomplete" value="1">
								<label class="custom-control-label" for="auditcomplete">Audit Complete</label>
							</div>											
						</div>
					<?php } ?>
				</div>
				
				<?php if($roletype=='3' && $pagetype=='2'){ ?>
					<div class="col-md-12">
						<h3>NOTICE TO LICENSED PLUMBER</h3>
						<p>It is your responsible to complete your refix's with in the allocted time. Failure to do so within the alloated time will result in the refix being marked as Audit Complete (with Refix(s)) and relevant remedial action will follow.</p>
					</div>
				<?php } ?>
				<!-- <?php
					//if ($pagetype=='2' && $roletype =='1') { ?>
						<div class="row">
							<div class="col-md-6">
								<h4 class="card-title">Reason</h4>
								<div class="form-group">
									<textarea class="form-control chattext" id="reasontext" name="reasontext" placeholder="Type your reason here"><?php// echo $adminreason; ?></textarea> 
								</div>
							</div>
							<div class="col-md-3">
								<h4 class="card-title">Photo</h4>
								<div class="form-group">
									<div>
										<a href="<?php// echo $opphotoidurl; ?>" target="_blank"><img src="<?php// echo $opprofileimg; ?>" class="photo_image" width="100"></a>
									</div>
									<input type="file" id="file_2" class="photo_file">
									<label for="file_2" class="choose_file">Choose File</label>
									<input type="hidden" name="image2" class="photo" value="<?php// echo $opfile2; ?>">
									<p>(Image/File Size Smaller than 5mb)</p>
								</div>
							</div>
						</div>
						
				<?php// } ?> -->
				
				<?php if(($pagetype=='1' && $roletype !='1') || ($pagetype=='2' && $roletype =='1')){ ?>
					<div class="col-md-12 text-right">					
						<input type="hidden" value="<?php echo $statementid; ?>" name="id">
						<input type="hidden" value="<?php echo $cocid; ?>" name="cocid">
						<input type="hidden" value="<?php echo $userid; ?>" name="auditorid">
						<?php if (isset($adminid)) { ?>
							<input type="hidden" value="<?php echo $adminid; ?>" name="adminid">
							<input type="hidden" value="1" name="update_device">
						<?php } ?>
						<input type="hidden" value="<?php echo $plumberid; ?>" name="plumberid">
						<input type="hidden" name="workmanshippoint" id="workmanshippoint">
						<input type="hidden" name="plumberverificationpoint" id="plumberverificationpoint">
						<input type="hidden" name="cocverificationpoint" id="cocverificationpoint">
						<input type="hidden" name="reviewpoint" id="reviewpoint">
						<input type="hidden" name="point" id="point">
						<input type="hidden" name="refuse_point" id="refuse_point">
						<input type="hidden" name="auditstatus" id="auditstatus" value="1">

						<?php if ($roletype !='1') { ?>

							<button type="button" id="submitreport1" class="btn btn-primary displaynone">Finalize Audit</button>
							<?php if ($as_buttonstatus =='0' || $as_buttonstatus =='') { ?>
								<button type="button" id="submitreport" class="btn btn-primary displaynone">Send Report</button>
								<button type="button" id="save"  class="btn btn-primary">Save for later</button>
							<?php } ?>

						<?php }elseif($roletype =='1'){ ?>
							<div class="custom-control custom-checkbox">
								<input type="checkbox" id="applychanges" class="custom-control-input applychanges" name="applychanges" value="1">
								<label class="custom-control-label" for="applychanges">Apply changes</label>
							</div>
							<button type="button" id="adminsubmit"  class="btn btn-primary admin-btn displaynone">Finalize Audit</button>
							<input type="hidden" name="edit_reviewid" id="edit_reviewid">
							<!-- <input type="hidden" name="admin_addreview" id="admin_addreview"> -->
							<input type="hidden" name="admin_review_status" id="admin_review_status">
							<!-- <input type="hidden" name="delete_reviewstmt" id="delete_reviewstmt"> -->
							<!-- <input type="hidden" name="delete_reviewtype" id="delete_reviewtype"> -->
						<?php } ?>
						
						<input type="submit" name="submit" id="submit" class="displaynone">
					</div>		
				<?php } ?>
			</form>			
		</div>
	</div>
</div>		

<div class="row">
	<div class="col-12">
		<h4 class="card-title">Chat (History) <span id="chatviewed"></span></h4>
		<div class="card">
			<div class="chatcontent" id="chatcontent">
				<p><a href="javascript:void(0);" data-url="<?php echo isset($seperatechat) ? base_url().$seperatechat : ''; ?>" id="seperatechat">Open In a Seperate Chat Window</a></p>
			</div>
			<?php if(($pagetype=='1' && $roletype=='5') || ($pagetype=='2' && $roletype=='3' && $auditcomplete!='1')){ ?>
				<div class="chatfooter">
					<div class="input-group">
						<textarea class="form-control chattext" id="chattext" placeholder="Type your message here"></textarea> 
						<div class="input-group-append">
							<span class="input-group-text">
								<i class="fa fa-paperclip" id="chatattachment"></i>
								<input type="file" name="file" class="displaynone" id="chatattachmentfile">
							</span>
						</div>
					</div>
				</div>
				<audio id="beeepaudio">
					<source src="<?php echo base_url().'assets/music/beep.mp3'; ?>" type="audio/mpeg">
					Your browser does not support the audio element.
				</audio>
			<?php } ?>
		</div>
	</div>
</div>


<div id="reviewmodal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form class="reviewform">
				<div class="modal-header">
					<h4 class="modal-title add-title">Review</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Review Type</label>
								<div class="row">
									<?php
										foreach($reviewtype as $key => $value){
											if ($value =='No Audit Findings') {
												$newvalue = 'no_audit_findings';
											}else{
												$newvalue = $value;
											}
											
									?>
											<div class="col-md-3 reviewtyperadio" data-reviewtyperadio="<?php echo $key; ?>">
												<div class="custom-control custom-radio">
													<input type="radio" name="reviewtype" id="r_reviewtype<?php echo $key.'-'.$newvalue; ?>" class="custom-control-input r_reviewtype" value="<?php echo $key; ?>">
													<label class="custom-control-label" for="r_reviewtype<?php echo $key.'-'.$newvalue; ?>"><?php echo $value; ?></label>
												</div>
											</div>
									<?php
										}
									?>
								</div>
							</div>
						</div>
						<div class="col-md-12 section1 displaynone">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label>My Report Listings/Favourites</label>
										<?php
											echo form_dropdown('favourites', $auditorreportlist, '', ['id' => 'r_auditorreportlist', 'class'=>'r_auditorreportlist form-control']);
										?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Installation Type</label>
										<?php
											echo form_dropdown('installationtype', $installationtype, '', ['id' => 'r_installationtype', 'class'=>'r_installationtype form-control']);
										?>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Sub Type</label>
										<?php
											echo form_dropdown('subtype', [], '', ['id' => 'r_subtype', 'class'=>'r_subtype form-control']);
										?>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label>Statement</label>
										<?php
											echo form_dropdown('statement', [], '', ['id' => 'r_statement', 'class'=>'form-control']);
										?>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label>SANS/Regulation/Bylaw Reference</label>
										<input type="text" name="reference" class="r_reference form-control" id="r_reference">
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label>Knowledge Reference link</label>
										<input type="text" name="link" class="r_link form-control" id="r_link">
										<p class="tagline displaynone referencelinktagline">Eg : http://www.google.com</p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-12 section2 displaynone">
							<div class="form-group">
								<label>Comments</label>
								<textarea name="comments" rows="6" class="r_comments form-control" id="r_comments"></textarea>
							</div>
						</div>
						<div class="col-md-12 section3 displaynone">
							<div class="form-group">
								<div>
									<img src="<?php echo $profileimg; ?>" width="100">
								</div>
								<input type="file" id="r_file" class="r_file">
								<p>(Image/File Size Smaller than 5mb)</p>
								<div class="rfileappend"></div>
							</div>						
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Performance Point Allocation</label>
								<input type="text" name="point" class="r_point form-control" id="r_point" readonly>
							</div>
						</div>

						<?php
						if ($pagetype=='2' && $roletype =='1') { ?>
							<!-- <div class="row"> -->
								<div class="col-md-6">
									<h4 class="card-title">Reason</h4>
									<div class="form-group">
										<textarea class="form-control" id="reviewreason" name="reviewreason" placeholder="Type your reason here"></textarea> 
									</div>
								</div>
								<div class="col-md-6 up1" style="margin-left: 538px;margin-top: -165px;">
									<h4 class="card-title">Photo</h4>
									<div class="form-group">
										<div>
											<a href="<?php echo $opphotoidurl; ?>" target="_blank"><img src="<?php echo $opprofileimg; ?>" class="photo_image" width="100"></a>
										</div>
										<input type="file" id="file_2" class="photo_file">
										<label for="file_2" class="choose_file">Choose File</label>
										<input type="hidden" name="image2" class="photo" value="">
										<p>(Image/File Size Smaller than 5mb)</p>
									</div>
								</div>
							<!-- </div> -->
							
					<?php } ?>

					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="id" id="r_id" class="r_id">
					<input type="hidden" value="1" name="status" id="r_status">
					<input type="hidden" value="<?php echo $cocid; ?>" name="cocid">
					<input type="hidden" value="<?php echo $userid; ?>" name="auditorid">
					<input type="hidden" value="<?php echo $plumberid; ?>" name="plumberid">
					<input type="hidden" value="0" name="incompletepoint" id="incompletepoint">
					<input type="hidden" value="0" name="completepoint" id="completepoint">
					<input type="hidden" value="0" name="cautionarypoint" id="cautionarypoint">
					<input type="hidden" value="0" name="complimentpoint" id="complimentpoint">
					<input type="hidden" value="0" name="noauditpoint" id="noauditpoint">

					<input type="hidden" value="<?php echo $roletype; ?>" name="hiddenroletype" id="hiddenroletype">

					<input type="hidden" value="<?php echo $settings['refix_period']; ?>" name="refixperiod">
					<button type="button" class="btn btn-success reviewsubmit">Submit</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="changestatusmodal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form class="statusform">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<?php
						if ($pagetype=='2' && $roletype =='1') { ?>
							<!-- <div class="row"> -->
								<div class="col-md-6">
									<h4 class="card-title">Reason</h4>
									<div class="form-group">
										<textarea class="form-control" id="reviewreason1" name="reviewreason1" placeholder="Type your reason here"></textarea> 
									</div>
								</div>
								<div class="col-md-6" style="margin-left: 538px;margin-top: -165px;">
									<h4 class="card-title">Photo</h4>
									<div class="form-group">
										<div>
											<a href="<?php echo $opphotoidurl; ?>" target="_blank"><img src="<?php echo $opprofileimg; ?>" class="photo_image3" width="100"></a>
										</div>
										<input type="file" id="file_2" class="photo_file">
										<label for="file_2" class="choose_file">Choose File</label>
										<input type="hidden" name="image2" class="photo3" value="<?php echo $opfile2; ?>">
										<p>(Image/File Size Smaller than 5mb)</p>
									</div>
								</div>
							<!-- </div> -->
							
					<?php } ?>

					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" value="<?php echo $cocid; ?>" name="cocid">
					<input type="hidden" value="<?php echo $userid; ?>" name="auditorid">
					<input type="hidden" value="<?php echo $plumberid; ?>" name="plumberid">

					<input type="hidden" value="1" name="adminreview">

					<input type="hidden" value="<?php echo $roletype; ?>" name="hiddenroletype" id="hiddenroletype">
					
					<button type="button" class="btn btn-success proceed_status">Proceed</button>
					<button type="button" class="btn btn-default" id="cancel_status" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="deletemodal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form class="statusform">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"></h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<?php
						if ($pagetype=='2' && $roletype =='1') { ?>
							<!-- <div class="row"> -->
								<div class="col-md-6">
									<h4 class="card-title">Reason</h4>
									<div class="form-group">
										<textarea class="form-control" id="reviewreason2" name="reviewreason2" placeholder="Type your reason here"></textarea> 
									</div>
								</div>
								<div class="col-md-6" style="margin-left: 538px;margin-top: -165px;">
									<h4 class="card-title">Photo</h4>
									<div class="form-group">
										<div>
											<a href="<?php echo $opphotoidurl; ?>" target="_blank"><img src="<?php echo $opprofileimg; ?>" class="photo_image2" width="100"></a>
										</div>
										<input type="file" id="file_2" class="photo_file">
										<label for="file_2" class="choose_file">Choose File</label>
										<input type="hidden" name="image3" class="photo2" value="<?php echo $opfile2; ?>">
										<p>(Image/File Size Smaller than 5mb)</p>
									</div>
								</div>
							<!-- </div> -->
							
					<?php } ?>

					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" value="<?php echo $cocid; ?>" name="cocid">
					<input type="hidden" value="<?php echo $userid; ?>" name="auditorid">
					<input type="hidden" value="<?php echo $plumberid; ?>" name="plumberid">
					
					<button type="button" class="btn btn-success proceed_delete">Proceed</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="confirmmodal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12 text-center">
						<h4 class="mb-15">Confirm to submit your Audit report for <?php echo $plumbername; ?> undertaken for COC <?php echo $cocid; ?>?</h4>
						<p class="refixmodaltext displaynone">The refix for this COC is required by lastests: <span class="refixdateappend"></span>.</p>
					</div>
					<div class="col-md-12 text-center">
						<button type="button" class="btn btn-success confirmsubmit">Confirm</button>
						<button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
var reviewtype 				= JSON.parse('<?php echo json_encode($reviewtype); ?>');
var reviewclass 			= JSON.parse('<?php echo json_encode($reviewtableclass); ?>');
var workmanshippt 			= JSON.parse('<?php echo json_encode($workmanshippt); ?>');
var plumberverificationpt 	= JSON.parse('<?php echo json_encode($plumberverificationpt); ?>');
var cocverificationpt 		= JSON.parse('<?php echo json_encode($cocverificationpt); ?>');
var refixcompletept 		= '<?php echo $refixcompletept; ?>';
var cautionarypt 			= '<?php echo $cautionarypt; ?>';
var complimentpt 			= '<?php echo $complimentpt; ?>';
var cpdpt 					= '<?php echo $cpdpt; ?>';
var noaudit		= '<?php echo $noaudit; ?>';
var filepath 	= '<?php echo $filepath; ?>';
var chatpath 	= '<?php echo $chatfilepath; ?>';
var downloadurl	= '<?php echo $downloadurl; ?>';
var reviewpath 	= '<?php echo $reviewpath; ?>';
var pdfimg		= '<?php echo $pdfimg; ?>';
var pagetype	= '<?php echo $pagetype; ?>';
var roletype	= '<?php echo $roletype; ?>';
var cocid 		= '<?php echo $cocid; ?>';
var plumberid 	= '<?php echo $plumberid; ?>';
var auditorid 	= '<?php echo $auditorid; ?>';
var fromid		= (roletype=='3') ? plumberid : auditorid;
var toid		= (roletype=='3') ? auditorid : plumberid;
var validator;
var as_buttonstatus = '<?php echo $as_buttonstatus; ?>';
var auditstatus = '<?php echo $result['audit_status']; ?>';

var strrefixdate	= '<?php echo $strrefixdate; ?>';
var curdatestr 		= '<?php echo $curdatestr; ?>';

$(function(){
	if($('#hold').is(':checked')) $('#hold').data('approvalHoldValue', true);
	reason()
	
	// submitRerpotFunc();
	datepicker('#refixcompletedate', ['enddate']);
	datepicker('.auditdate', ['enddate'], {'customstartdate' : '<?php echo date('Y-m-d', strtotime($completiondate)); ?>'});	
	select2('#workmanship, #plumberverification, #cocverification, #province, #city, #suburb');
	citysuburb(['#province','#city', '#suburb'], ['<?php echo $cityid; ?>', '<?php echo $suburbid; ?>']);
	subtypereportinglist(['#r_installationtype','#r_subtype','#r_statement'], ['', ''], reviewpoint);
	fileupload(["#r_file", "./assets/uploads/auditor/statement/", ['jpg','gif','jpeg','png','pdf','tiff']], ['file[]', '.rfileappend', reviewpath, pdfimg], 'multiple');
	fileupload([".photo_file", "./assets/uploads/auditor/statement/", ['jpg','gif','jpeg','png','pdf','tiff']], ['.photo1', '.photo_image1', reviewpath, pdfimg]);
	fileupload([".photo_file", "./assets/uploads/auditor/statement/", ['jpg','gif','jpeg','png','pdf','tiff']], ['.photo', '.photo_image', reviewpath, pdfimg]);
	fileupload([".photo_file", "./assets/uploads/auditor/statement/", ['jpg','gif','jpeg','png','pdf','tiff']], ['.photo2', '.photo_image2', reviewpath, pdfimg]);
	fileupload([".photo_file", "./assets/uploads/auditor/statement/", ['jpg','gif','jpeg','png','pdf','tiff']], ['.photo3', '.photo_image3', reviewpath, pdfimg]);
	chat(['.chattext', '.chatcontent'], [cocid, fromid, toid], [chatpath, pdfimg, downloadurl]);
	
	var reviewlist = $.parseJSON('<?php echo addslashes(json_encode($reviewlist)); ?>');
	if(reviewlist.length > 0){
		$(reviewlist).each(function(i, v){
			var reviewlistdata 	= {status : 1, result : { id: v.id, reviewtype: v.reviewtype, statementname: v.statementname, comments: v.comments, file: v.file, point: v.point, status: v.status, incomplete_point: v.incomplete_point, complete_point: v.complete_point, reference: v.reference, link: v.link, created_at: v.created_at }}
			review(reviewlistdata, 'pageload');
		})
	}

	if ($('#refuserefix').is(':checked')) {
		$('.auditcomplete_wrapper').removeClass('displaynone');
	}

	validator = validation(
		'.form',
		{
			workmanship : {
				required	: true
			},
			plumberverification : {
				required	: true
			},
			cocverification : {
				required	: true
			},
			auditdate : {
				required	: true
			},
			reason : {
				required:  	function() {
								return $('#hold').is(':checked');
							}
			},
			attachmenthidden : {
				required	: true
			},
			auditcomplete : {
				// required	: true
				required:  	function() {
								return $('#refuserefix').is(':checked');
							}
			},
			refixcompletedate : {
				required	: true
			}
		},
		{
			workmanship 	: {
				required	: "Please select Overall Workmanship."
			},
			plumberverification 	: {
				required	: "Please select Licensed Plumber Present."
			},
			cocverification 	: {
				required	: "Please select Was COC Completed Correctly."
			},
			auditdate 	: {
				required	: "Please select Date of Audit."
			},
			reason 	: {
				required	: "Please fill Why was Audit placed on hold?."
			},
			attachmenthidden : {
				required	: "Please fill one review."
			},
			auditcomplete : {
				required	: "Please check audit complete."
			}
		},
		{
			ignore : [],
			callback : 1
		}
	);
	
	validation(
		'.reviewform',
		{
			reviewtype : {
				required	: true,
			},
			installationtype : {
				required	: true,
			},
			subtype : {
				required	: true,
			},
			statement : {
				required	: true,
			},
			reference : {
				required	: true,
			},
			link : {
				required	: true,
			},
			comments : {
				required:  	function() {
								return $('.r_reviewtype:checked').val()!=4;
							}
			},
			point : {
				required	: true,
			}
		},
		{
			reviewtype 	: {
				required	: "Please select Review Type.",
			},
			installationtype : {
				required	: "Please select Installation Type.",
			},
			subtype : {
				required	: "Please select Sub Type.",
			},
			statement : {
				required	: "Please select Statement",
			},
			reference : {
				required	: "Please enter SANS/Regulation/Bylaw Reference.",
			},
			link : {
				required	: "Please enter Knowledge Reference link.",
			},
			comments : {
				required	: "Please enter Comments.",
			},
			point : {
				required	: "Please enter Performance Point Allocation.",
			}
		}
	);

	submitBtn();

	// if($('.form').valid()){
	// 	$("p.error_class_1").css("display", "none");
	//     $('#submitreport').removeClass('displaynone');
	// }else {
	// 	$("p.error_class_1").css("display", "none");
	//     $('#submitreport').addClass('displaynone');
	// }

	// if (auditstatus !=='4' && auditstatus !=='1') {
	// 	$('.admin-btn').removeClass('displaynone');
	// }else{
	// 	$('.admin-btn').addClass('displaynone');
	// }

	$('#addreviews').click(function(){
		$('#reviewreason').val('');
		$('.photo_image').attr('src', '<?php echo $profileimg; ?>');
		$('.photo').val('');
		//$profileimg
	});

});

window.addEventListener('message', function(e) {
	var splitid = e.data.split('-');
	if(splitid[1]) processparent(splitid[1]); 
} , false);

function processparent(id) {
	chat(['.chattext', '.chatcontent'], [cocid, fromid, toid, id], [chatpath, pdfimg, downloadurl], 'childparent');
}

$('#workmanship, .auditdate, #plumberverification, #cocverification').on('keyup, change', function() {
    /*if($('.form').valid()){
    	$("p.error_class_1").css("display", "none");
        $('#submitreport').removeClass('displaynone');
    }else {
    	$("p.error_class_1").css("display", "none");
        $('#submitreport').addClass('displaynone');
    }*/
    submitBtn();
});

/*$('#auditorstatus').on('change', function() {
    if($(this).val() !== '4' && $(this).val() !=='1'){
    	$('.admin-btn').removeClass('displaynone');
    }else {
    	$('.admin-btn').addClass('displaynone');
    }
});*/



if (($('#auditcomplete').is(':checked'))) {
	$('#submitreport1').removeClass('displaynone');
}else{
	$('#submitreport1').addClass('displaynone');
}

$('#auditcomplete').click(function(){
	if ($(this).is(':checked')) {
		$('#submitreport1').removeClass('displaynone');
	}else{
		$('#submitreport1').addClass('displaynone');
	}
})

$('#applychanges').click(function(){
	if ($(this).is(':checked')) {
		$('#adminsubmit').removeClass('displaynone');
	}else{
		$('#adminsubmit').addClass('displaynone');
	}
})

$('#adminsubmit').click(function(){
	// $('#auditstatus').val(1);
	if ($('.refixcompletedate_wrapper').hasClass('displaynone') == true) {
		$(".form").validate().cancelSubmit = true;	
	}
	
	// if ($('#reasontext').val() !='') {
		$('#submit').attr('value', 'adminsubmitreport').click();
	// }else{
		// alert('Reason Required');
		// return false;
	// }
	
})

$('#save').click(function(){
	// validator.destroy();
	// $('.form').rules('remove');
	$(".form").validate().cancelSubmit = true;
	// if($('.form').valid())
	// {
		$('#submit').attr('value', 'save').click();
	// }
})

/*$('#submitreport').click(function(){
	if($('.form').valid())
	{
		$('#confirmmodal').modal('show');
	}
})*/
$('#submitreport1').click(function(){

	if (($('#refuserefix').is(':checked')) || (curdatestr > strrefixdate)) {
		$('#refixcompletedate').rules('remove', 'required');
	}

	if($('.form').valid())
	{
		$('#confirmmodal').modal('show');
	}
})

$('.confirmsubmit').click(function(){
	if($('.form').valid())
	{
		// $('#submit').attr('value', 'submitreport').click();
		$('#submit').attr('value', 'finalizereport').click();
	}
})
$('#submitreport').click(function(){
	// if($('.form').valid())
	// {
		if ($('.refixcompletedate_wrapper').hasClass('displaynone') === true) {
			$('#refixcompletedate').rules('remove', 'required');
		}
		$('#submit').attr('value', 'submitreport').click();
	// }
})




$('#hold').click(function(){
	var previousValue = $(this).data('approvalHoldValue');
    if (previousValue){
		$(this).prop('checked', !previousValue);
		$(this).data('approvalHoldValue', !previousValue);
    }else{
		$(this).data('approvalHoldValue', true);
		$("#hold:not(:checked)").data("approvalHoldValue", false);
    }
	
	reason()
})

function reason(){
	$('.reason_wrapper').addClass('displaynone');
	if($('#hold').is(':checked')){
		$('.reason_wrapper').removeClass('displaynone');
	}
	
	refixcheck();
}


$(document).on('change', '.reviewstatus', function(){
	var _this 		= $(this);
	var refixperiod = '<?php echo $settings['refix_period']; ?>';
	var r_id 		= _this.parent().parent().attr('data-id');
	var statusVal 	= _this.val();
	var itration 	= 0 // to avoid multipl ajax from the admin interface

	if (roletype ==='1') {
		// if ($('#reasontext').val() !=='' || $('#reasontext').val() ==='NaN') {
			if(_this.val()==0){
				var point = _this.parent().parent().attr('data-incompletept');
			}else{
				var point = _this.parent().parent().attr('data-completept');
			}

			/* Get review deatails */
			// ajax('<?php //echo base_url()."ajax/index/ajaxreviewaction"; ?>', {'id' : r_id, 'action' : 'edit', 'action2' : 'reviewstatus', "roletype" : roletype}, fetchReviewData);
			fetchReviewData();

			$('#changestatusmodal').modal('show');
			$('.proceed_status').click(function(){
				if ($('#reviewreason1').val() === '' && $('#reviewreason1').val() === '') {
					alert('Please enter reason before submit the review');
					return false;
				}else{

					if (itration === 0) {
							ajax('<?php echo base_url()."ajax/index/ajaxreviewaction"; ?>', {'id' : _this.parent().parent().attr('data-id'), 'point' : point, 'refixperiod' : refixperiod, 'status' : _this.val(), "roletype" : roletype, 'reviewreason' : $('#reviewreason1').val(), 'image2' : $('.photo3').val(), 'rqst_type' : "change_status", 'auditorid' : "<?php echo $auditorid; ?>", 'cocid' : "<?php echo $cocid; ?>", 'plumberid' : "<?php echo $plumberid; ?>"}, '', { success : function(data){ 
								sweetalertautoclose('successfully saved'); 
								refixcheck(); 
								_this.parent().parent().find('td:eq(6)').text(point);
								$('.photo3').val('');
								$('#changestatusmodal').modal('hide');

								$('#admin_review_status').val(function(i,val) {
									return val + (!val ? '' : ',') + _this.parent().parent().attr('data-id');
								});
							}
						});
					}
					itration = parseInt(itration)+1;

					
				}
			});
		/*}else{
			alert('Please fill the Reason');
			$(this).val('0');
			return false;
		}*/

		$('#cancel_status').click(function(){
			if (statusVal ==='1') {
				_this.val('0');
			}else{
				_this.val('1');
			}
		});

	}else if(roletype ==='5'){
		if(_this.val()==0){
			var point = _this.parent().parent().attr('data-incompletept');
		}else{
			var point = _this.parent().parent().attr('data-completept');
		}
		
		ajax('<?php echo base_url()."ajax/index/ajaxreviewaction"; ?>', {'id' : _this.parent().parent().attr('data-id'), 'point' : point, 'refixperiod' : refixperiod, 'status' : _this.val()}, '', { success : function(data){ 
			sweetalertautoclose('successfully saved'); 
			refixcheck(); 
			_this.parent().parent().find('td:eq(6)').text(point)
		}});
	}
	
	
})

function fetchReviewData(data){

	$('#reviewreason1').val('');
	$('.photo_image3').attr('src', '<?php echo $profileimg; ?>');
	$('.photo3').val('');
	
	/*if (result.image2 =='') {
		$('.photo_image1').attr('src', '<?php// echo base_url().'assets/images/profile.jpg'?>');
	}else{
		$('.photo_image1').attr('src', '<?php// echo base_url().'assets/uploads/auditor/statement/'?>'+result.image2);
	}

	$('.photo3').val(result.image2);
	// $('#reviewreason').val(result.reason);
	$('#reviewreason1').val(result.reason);*/
}

function fetchdeleteReviewData(data){

	$('#reviewreason2').val('');
	$('.photo_image2').attr('src', '<?php echo $profileimg; ?>');
	$('.photo2').val('');
	
	/*if (result.image2 =='') {
		$('.photo_image2').attr('src', '<?php// echo base_url().'assets/images/profile.jpg'?>');
	}else{
		$('.photo_image2').attr('src', '<?php// echo base_url().'assets/uploads/auditor/statement/'?>'+result.image2);
	}
	$('.photo2').val(result.image2);
	// $('#reviewreason').val(result.reason);
	$('#reviewreason2').val(result.reason);*/
}


$('.r_reviewtype').click(function(){
	reviewtoggle($(this).val());
	reviewpoint();
})

function reviewtoggle(data){
	// reviewmodalclear(1);
	
	if(data==1 || data==2 || data==3){
		$('.section1, .section2, .section3').removeClass('displaynone');
	}else if(data==4){
		$('.section2').removeClass('displaynone');
	}
}

function reviewpoint(){
	//setTimeout(function(){
		var statement = $('#r_statement');
		var reviewtype = $('.r_reviewtype:checked').val();
		
		$('.r_point').val('');
		$('#r_status').val('1');
	
		if(statement.val()!='' && statement.val()!=undefined){
			var statementoption 	= statement.find('option:selected');
			var refixincompletecalc = statementoption.attr('data-refixincomplete');
			if(reviewtype==1){
				var refixcompletecalc 	= (parseFloat(refixincompletecalc) * (parseFloat(refixcompletept))).toFixed(2);
				$('.r_point').val(refixincompletecalc);
				$('#incompletepoint').val(refixincompletecalc);
				$('#completepoint').val(refixcompletecalc);
				$('#r_status').val('0');
			}else if(reviewtype==2){
				var cautionarycalc = (parseFloat(refixincompletecalc) * (parseFloat(cautionarypt))).toFixed(2);
				$('.r_point').val(cautionarycalc);
				$('#cautionarypoint').val(cautionarycalc);
			}else if(reviewtype==3){
				var complimentcalc = (parseFloat(refixincompletecalc) * (parseFloat(complimentpt))).toFixed(2);
				$('.r_point').val(complimentcalc);
				$('#complimentpoint').val(complimentcalc);
			}
			
			$('#r_reference').val(statementoption.attr('data-reference'));
			$('#r_link').val(statementoption.attr('data-link'));
			$('#r_comments').val(statementoption.attr('data-comments'));
		} 
		
		if(reviewtype==4){
			$('.r_point').val(noaudit);
			$('#noauditpoint').val(noaudit);
		} 
	//}, 1000);
}

$('.r_auditorreportlist').change(function(){
	ajax('<?php echo base_url()."ajax/index/ajaxauditorreportinglist"; ?>', {'id' : $(this).val()}, '', { success : function(data){
		if(data.status==1){
			var result = data.result;
			
			$('#r_installationtype').val(result.installationtype_id)
			$('#r_comments').val(result.comments)
			subtypereportinglist(['#r_installationtype','#r_subtype','#r_statement'], [result.subtype_id, result.statement_id], reviewpoint);
		}	
	}});
})

$('#reviewmodal').on('hidden.bs.modal', function(){
    reviewmodalclear();
})

$('.reviewsubmit').click(function(){
	if($('.reviewform').valid())
	{
		if (roletype ==='1') {
			if ($('#reviewreason').val() === '' && $('#reviewreason').val() === '') {
				alert('Please enter reason before submit the review');
				return false;
			}else{
				// $('#reasontext1').val($('#reasontext').text());
				// $('#photo1').val($('.photo').val());
				var data = $('.reviewform').serialize();
				ajax('<?php echo base_url()."ajax/index/ajaxreviewaction"; ?>', data, review);
			}
		}else{
			var data = $('.reviewform').serialize();
			ajax('<?php echo base_url()."ajax/index/ajaxreviewaction"; ?>', data, review);
		}
	}
})

function review(data, type =''){

	if(data.status==1){		
		var extrafield	= 	'';
		var dropdown	= 	'';
		var action 		= 	'';
		var result 		= 	data.result; 
		
		$(document).find('.reviewappend[data-id="'+result.id+'"]').remove();
		
		//if(pagetype=='2'){
			extrafield	=	'<td>'+((result.reference!=null) ? result.reference : "")+'</td><td>'+((result.link!=null) ? '<a href="'+result.link+'" target="_blank">'+result.link+'</a>' : "")+'</td>';
		//}
		
		if(result.reviewtype==1){
			var status 	= 	result.status;
			if(pagetype=='1'){
				dropdown	= 	'<select class="form-control reviewstatus">\
									<option value="0" '+((status=='0') ? "selected" : "")+'>Incomplete</option>\
									<option value="1" '+((status=='1') ? "selected" : "")+'>Complete</option>\
								</select>';		
			}else if(pagetype=='2' && roletype =='1'){
				dropdown	= 	'<select class="form-control reviewstatus">\
									<option value="0" '+((status=='0') ? "selected" : "")+'>Incomplete</option>\
									<option value="1" '+((status=='1') ? "selected" : "")+'>Complete</option>\
								</select>';		
			}else{
				dropdown	=	(status=='0') ? '<i class="fa fa-times"></i><p>Incomplete</p>' : '<i class="fa fa-check"></i><p>Complete</p>';
			}							
		}
		
		if(pagetype=='1' && (as_buttonstatus =='0' || as_buttonstatus =='') && roletype !='1'){
			action 	= 	'<td>\
							<a href="javascript:void(0);" class="reviewedit" data-id="'+result.id+'"><i class="fa fa-pencil-alt"></i></a>\
							<a href="javascript:void(0);" class="reviewremove" data-id="'+result.id+'"><i class="fa fa-trash"></i></a>\
						</td>';
		}else if(pagetype=='2' && roletype =='1'){
			action 	= 	'<td>\
							<a href="javascript:void(0);" class="reviewedit" data-id="'+result.id+'"><i class="fa fa-pencil-alt"></i></a>\
							<a href="javascript:void(0);" class="reviewremove" data-id="'+result.id+'" data-stmt-name="'+result.statementname+'" data-rtype="'+reviewtype[result.reviewtype]+'"><i class="fa fa-trash"></i></a>\
						</td>';
		}else{
			action 	= '<td></td>';
		}
		
		var appenddata 	= 	'\
								<tr class="reviewappend '+reviewclass[result.reviewtype]+'" data-id="'+result.id+'" data-date="'+formatdate(result.created_at, 2)+'" data-incompletept="'+result.incomplete_point+'" data-completept="'+result.complete_point+'">\
									<td data-reviewtype="'+result.reviewtype+'">'+reviewtype[result.reviewtype]+'</td>\
									<td>'+((result.statementname!=null) ? result.statementname : "")+'</td>\
									<td>'+((result.comments!=null) ? result.comments : "")+'</td>\
									'+extrafield+'\
									<td class="reviewimageview"></td>\
									<td>'+((result.point!=null) ? result.point : "")+'</td>\
									<td>'+dropdown+'</td>\
									'+action+'\
								</tr>\
							';
					
		$('.reviewtable').append(appenddata);
		submitBtn();
		if (result.reviewtype =='1' && type !== 'pageload') {
			
			$('#refuse_point').val(function(i,val) { 
			     return val + (!val ? '' : ',') + result.id;
			});
		}
		// if (roletype ==='1' && type !== 'pageload') {
		// 	$('#admin_addreview').val(result.id);
		// }
				
		if(result.file!='') mutiplereviewfile(result.file, 2, result.id);
	}
	
	$('#reviewmodal').modal('hide');
	
	reviewextras();
}

$(document).on('click', '.reviewedit', function(){
	var _this = $(this);
	var itration 	= 0
	if (roletype ==='1') {
		// if ($('#reasontext').val() !='') {
			if (itration === 0) {
				ajax('<?php echo base_url()."ajax/index/ajaxreviewaction"; ?>', {'id' : $(this).attr('data-id'), 'action' : 'edit', "roletype" : roletype}, reviewedit);
			}
			

			$('#edit_reviewid').val(function(i,val) { 
			    return val + (!val ? '' : ',') + _this.attr('data-id');
			});
			itration = parseInt(itration)+1;
		/*}else{
			alert('Please fill the Reason');
			return false;
		}*/
	}else if(roletype ==='5'){
		ajax('<?php echo base_url()."ajax/index/ajaxreviewaction"; ?>', {'id' : $(this).attr('data-id'), 'action' : 'edit'}, reviewedit);
	}
	
})

function submitBtn(){
	
	var reviewlgth 			= $(document).find('.reviewappend').length;
	var workmanship 		= $('#workmanship').val();
	var auditdate 			= $('.auditdate').val();
	var plumberverification = $('#plumberverification').val();
	var cocverification 	= $('#cocverification').val();

	if (reviewlgth =='1' && workmanship !='' && auditdate !='' && plumberverification !='' && cocverification !='') {

		if ($('.refixcompletedate_wrapper').hasClass('displaynone') ===true) {
			$('#submitreport').removeClass('displaynone');
		}else{
		 	$('#submitreport').addClass('displaynone');
		}
	}
}

function reviewedit(data){
	if(data.status==1){
		var result 	= 	data.result;
		
		reviewtoggle(result.reviewtype);
		
		$('.r_reviewtype[value="'+result.reviewtype+'"]').prop('checked', true);
		$('.r_auditorreportlist').val(result.favourites);
		$('.r_installationtype').val(result.installationtype);
		subtypereportinglist(['#r_installationtype','#r_subtype','#r_statement'], [result.subtype, result.statement], reviewpoint);
		$('.r_reference').val(result.reference);
		$('.r_link').val(result.link);
		$('.r_comments').val(result.comments);
		$('.r_point').val(result.point);
		$('.r_id').val(result.id);

		$('#reviewreason').val('');
		$('.photo_image').attr('src', '<?php echo $profileimg; ?>');
		$('.photo').val('');

		
		/*if (result.image2 =='') {
			$('.photo_image').attr('src', '<?php// echo base_url().'assets/images/profile.jpg'?>');
		}else{
			$('.photo_image').attr('src', '<?php// echo base_url().'assets/uploads/auditor/statement/'?>'+result.image2);
		}
		$('.photo').val(result.image2);
		$('#reviewreason').val(result.reason);*/
		
		if(result.file!='') mutiplereviewfile(result.file, 1);
		
		$('#reviewmodal').modal('show');
	} 
}

function mutiplereviewfile(file, type, id=''){
	if(file!=''){
		var filesplit = file.split(',');
		
		$(filesplit).each(function(i, v){
			
			var ext 		= v.split('.').pop().toLowerCase();
			if(ext=='jpg' || ext=='jpeg' || ext=='png'){
				var filesrc = reviewpath+v;	
				var fileurl = reviewpath+v;	
			}else if(ext=='pdf'){
				var filesrc = '<?php echo base_url()."assets/images/pdf.png"?>';	
				var fileurl = reviewpath+v;	
			}
			
			if(type==1){
				$('.rfileappend').append('<div class="multipleupload"><input type="hidden" value="'+v+'" name="file[]"><a href="'+fileurl+'" target="_blank"><img src="'+filesrc+'" width="100"></a><i class="fa fa-times"></i></div>');
			}else{
				if(id!='') $(document).find('.reviewappend[data-id="'+id+'"] .reviewimageview').append('<a href="'+fileurl+'" target="_blank"><img src="'+filesrc+'" width="100"></a>');
			}
		})
		
	} 
}


$(document).on('click', '.reviewremove', function(){

	var last_reviewID = $(".reviewtable").find("tr").last().find(':last-child').find('.reviewremove').attr('data-id');
	var _this = $(this);
	if (roletype ==='1') {
		if ($('#reasontext').val() !=='' || $('#reasontext').val() ==='NaN') {

			if ($(this).attr('data-id') === last_reviewID) {
				alert('Please choose other review to delete or add new review to delete this review');
				return false;
			}else{
				/*$('#delete_reviewstmt').val(function(i,val) { 
				    return val + (!val ? '' : ',') + _this.attr('data-stmt-name');
				});
				$('#delete_reviewtype').val(function(i,val) { 
				    return val + (!val ? '' : ',') + _this.attr('data-rtype');
				});*/

				/* Get review deatails */
				/*ajax('<?php// echo base_url()."ajax/index/ajaxreviewaction"; ?>', {'id' : _this.attr('data-id'), 'action' : 'edit', 'action2' : 'deleterview', "roletype" : roletype}, fetchdeleteReviewData);*/
				$('#deletemodal').modal('show');

				$('.proceed_delete').click(function(){
					if ($('#reviewreason2').val() === '' && $('#reviewreason2').val() === '') {
						alert('Please enter reason before delete the review');
						return false;
					}else{
						ajax('<?php echo base_url()."ajax/index/ajaxreviewaction"; ?>', {'id' : _this.attr('data-id'), 'action' : 'delete', 'reasontext' : $('#reviewreason2').val(), 'image3' : $('.photo').val(), "roletype" : roletype}, reviewremove);
						_this.parent().parent().remove();
						reviewextras();
						
						$('.attachmenthidden').valid();

						var str = $('#refuse_point').val();
						var strArray = str.split(',');
					    for (var i = 0; i < strArray.length; i++) {
					        if (strArray[i] === $(this).attr('data-id')) {
					            strArray.splice(i, 1);
					            $('#refuse_point').val(strArray.join(","));
					        }
					    }
					    $('#deletemodal').modal('hide');
					}
				});
			}

		}else{
			alert('Please fill the Reason');
			return false;
		}
	}else if(roletype ==='5'){
		ajax('<?php echo base_url()."ajax/index/ajaxreviewaction"; ?>', {'id' : $(this).attr('data-id'), 'action' : 'delete'}, reviewremove);
			$(this).parent().parent().remove();
			reviewextras();
			
			$('.attachmenthidden').valid();

			var str = $('#refuse_point').val();
			var strArray = str.split(',');
		    for (var i = 0; i < strArray.length; i++) {
		        if (strArray[i] === $(this).attr('data-id')) {
		            strArray.splice(i, 1);
		            $('#refuse_point').val(strArray.join(","));
		        }
		    }
	}
	
})

function reviewremove(data){}

function reviewmodalclear(data=''){
	$('.section1, .section2, .section3').addClass('displaynone');
	if(data=='') $('.r_reviewtype').prop('checked', false);
	
	$('#incompletepoint, #completepoint, #cautionarypoint, #complimentpoint, #noauditpoint').val(0);
	$('.r_auditorreportlist, .r_installationtype, .r_reference, .r_link, .r_comments, .r_file, .r_point, .r_id').val('');
	subtypereportinglist(['#r_installationtype','#r_subtype','#r_statement'], ['', ''], reviewpoint);
	$('.rfileappend').html('');
	$('.reviewform').find("p.error_class_1").remove();
	$('.reviewform').find(".error_class_1").removeClass('error_class_1');
	
	$('.attachmenthidden').valid();
}

function reviewextras(){
	if($(document).find('.reviewappend').length){
		$('.reviewnotfound').hide();
		$('.attachmenthidden').val('1');
	}else{
		$('.reviewnotfound').show();
		$('.attachmenthidden').val('');
	}
	
	refixcheck()
}

function refixcheck(){
	var refuserefix 	= '<?php echo $refixrefuse ; ?>';
	var refixperiod 	= '<?php echo $settings['refix_period']; ?>';
	var dbrefixdate 	= '<?php echo $result['ar1_refix_date']; ?>';
	

	$('.refix_wrapper, .report_wrapper, .auditcomplete_wrapper, .refixmodaltext, #submitreport1, .refixcompletedate_wrapper').addClass('displaynone');
	$('#refixcompletedate').val('');
	$('.reviewtyperadio').removeClass('displaynone');

	if(refixperiod =='0'){
		$('#refuserefix').prop('checked',true);
		$('#refuserefix').val(1);
	}else if(refixperiod !='0'){
		if(refuserefix !='1'){
			$('#refuserefix').prop('checked',false);
			$('#refuserefix').val(0);
		}
		// else if(refuserefix =='1'){
		// 	$('#submitreport1').removeClass('displaynone');
		// }
	}
	

	$('#refuserefix').click(function() {
	    if($(this).is(':checked')){
	    	// $('#submitreport1, .auditcomplete_wrapper').removeClass('displaynone');
	    	$('.auditcomplete_wrapper').removeClass('displaynone');
	   		$('#refuserefix').val(1);
	    }else{
	    	$('#submitreport1, .auditcomplete_wrapper').addClass('displaynone');
	    	$('#refuserefix').val(0);
	    }
	        
	});
	
	var reportcheck = 0;
	var newdate;
	
	$(document).find('.reviewappend').each(function(){
		var reviewtypecolumn 	= $(this).find('td:eq(0)').attr('data-reviewtype');
		var statuscolumn		= $(this).find('.reviewstatus').val();
		
		var date 		= new Date($(this).attr('data-date')); 
		newdate			= date.setDate(date.getDate() + Number($('#refixperiod').val()));
		var todaydate 	= new Date();
		var expirydate 	= new Date(formatdate(newdate, 2));
		// alert(reviewtypecolumn);
		if(reviewtypecolumn==1 || reviewtypecolumn==2){
			$('div[data-reviewtyperadio="4"]').addClass('displaynone');
		}else if(reviewtypecolumn==4){
			// $('div[data-reviewtyperadio="4"], div[data-reviewtyperadio="1"], div[data-reviewtyperadio="2"]').addClass('displaynone');
			$('div[data-reviewtyperadio="1"], div[data-reviewtyperadio="2"]').addClass('displaynone');
			$('#r_reviewtype4-no_audit_findings').prop('disabled', true)
		}
		
		if(reviewtypecolumn==1 && statuscolumn==0){
			if(expirydate >= todaydate){
				reportcheck = 1;
				return false;
			}else{
				reportcheck = 2;
				return false;
			}
		}else if(reviewtypecolumn==1 && statuscolumn==1){
			reportcheck = 3;
		}
	})

	$('#auditstatus').val(1);

	if($('#hold').is(':checked')){
		$('.refix_wrapper, .report_wrapper, .auditcomplete_wrapper, .refixmodaltext, #submitreport1, .refuserefix_wrapper').addClass('displaynone');
		$('#auditcomplete').prop('checked', false);
		
		if(reportcheck==1){
			$('#auditstatus').val(0);
		}else if(reportcheck==1){
			if($('.attachmenthidden').val()!=''){
				$('.refix_wrapper, .report_wrapper, .auditcomplete_wrapper, .refixmodaltext, #submitreport1, .refuserefix_wrapper').removeClass('displaynone');
				// if ($('#refuserefix').is(':checked')) {
				if ($('#auditcomplete').is(':checked')) {
					$('.auditcomplete_wrapper, #submitreport1').removeClass('displaynone');
					$('#refuserefix').val(1);
				}else{
					$('.auditcomplete_wrapper, #submitreport1').addClass('displaynone');
					$('#refuserefix').val(0);
				}
				$('.refixdateappend').text(formatdate(newdate, 1));
				$('#auditstatus').val(0);
			}
		}
	}else{
		if(reportcheck==1){
			$('#refuserefix').prop('checked',false);
			$('.refix_wrapper').removeClass('displaynone');
			$('.refixdateappend').text(formatdate(newdate, 1));
			$('#auditstatus').val(0);
			$('.refuserefix_wrapper').removeClass('displaynone');
			// if ($('#refuserefix').is(':checked')) {
			if ($('#auditcomplete').is(':checked')) {
				$('.auditcomplete_wrapper, #submitreport1').removeClass('displaynone');
				$('#refuserefix').val(1);
			}else{
				$('.auditcomplete_wrapper, #submitreport1').addClass('displaynone');
				$('#refuserefix').val(0);
			}
			
			
			
		}else if(reportcheck==2){
			if($('.attachmenthidden').val()!=''){
				$('.refix_wrapper, .report_wrapper, .auditcomplete_wrapper, .refixmodaltext, .refuserefix_wrapper').removeClass('displaynone');

				if(refuserefix ==='0'){
					if (curdatestr > strrefixdate) {
						$('#refuserefix').prop('checked',false);
						$('#refuserefix').val(0);
						$('.auditcomplete_wrapper').removeClass('displaynone');
						$('.refuserefix_wrapper').addClass('displaynone');
					}else{
						$('#refuserefix').prop('checked',false);
						$('#refuserefix').val(0);
						$('.auditcomplete_wrapper, #submitreport1').addClass('displaynone');
					}
			}else{
				$('#refuserefix').prop('checked',true);
				$('#refuserefix').val(1);
				$('.auditcomplete_wrapper, #submitreport1').removeClass('displaynone');
			}

				$('.refixdateappend').text(formatdate(newdate, 1));
				$('#auditstatus').val(0);
			}
		}else if(reportcheck==3){
			// $('#refixcompletedate').val(refixcompletedate);
			$('#refixcompletedate').val('');
			if(refuserefix ==='0'){
				$('#refuserefix').prop('checked',false);
				$('#refuserefix').val(0);
				if($('.attachmenthidden').val()!=''){
					// $('.report_wrapper, .auditcomplete_wrapper, #submitreport, .refixcompletedate_wrapper').removeClass('displaynone');
					// $('.auditcomplete_wrapper, #submitreport').addClass('displaynone');
					$('.auditcomplete_wrapper').removeClass('displaynone');
					$('.report_wrapper, .refixcompletedate_wrapper').removeClass('displaynone');

				} 
			}else{
				$('#refuserefix').prop('checked',true);
				$('#refuserefix').val(1);
				if($('.attachmenthidden').val()!=''){
					$('.report_wrapper, .auditcomplete_wrapper, .refixcompletedate_wrapper').removeClass('displaynone');
				} 
			}
			// $('#refuserefix').prop('checked',true);
			// $('#refuserefix').val(1);
			// if($('.attachmenthidden').val()!='') $('.report_wrapper, .auditcomplete_wrapper, #submitreport, .refixcompletedate_wrapper').removeClass('displaynone');
		}else{

			if (reportcheck==0) {
				$('.refuserefix_wrapper').addClass('displaynone');
			}
			
			if($('.attachmenthidden').val()!=''){
				// $('.refuserefix_wrapper').removeClass('displaynone');
				$('.report_wrapper, .auditcomplete_wrapper').removeClass('displaynone');
			} 
		}
	}
}

$('.form').submit(function(){
	pointcalculation();
})

function pointcalculation(){
	var workmanship = $('#workmanship').val();
	var plumberverification = $('#plumberverification').val();
	var cocverification = $('#cocverification').val();
	
	var workmanshipval 			= (workmanshippt[workmanship]) ? parseFloat(workmanshippt[workmanship].replace("+", "")) : 0;
	var plumberverificationval 	= (plumberverificationpt[plumberverification]) ? parseFloat(plumberverificationpt[plumberverification].replace("+", "")) : 0;
	var cocverificationval 		= (cocverificationpt[cocverification]) ? parseFloat(cocverificationpt[cocverification].replace("+", "")) : 0;
	
	var reviewval = 0;
	$(document).find('.reviewappend').each(function(){
		reviewval += parseFloat($(this).find('td:eq(6)').text().replace("+", ""));
	})

	// console.log(workmanshipval);
	// console.log(plumberverificationval);
	// console.log(cocverificationval);
	// console.log(reviewval);
	
	$('#workmanshippoint').val(workmanshipval);
	$('#plumberverificationpoint').val(plumberverificationval);
	$('#cocverificationpoint').val(cocverificationval);
	$('#reviewpoint').val(reviewval);
	$('#point').val(workmanshipval + plumberverificationval + cocverificationval + reviewval);
}



$('#r_link').focusin(function() {
  $('.referencelinktagline').removeClass('displaynone');
});

$('#r_link').focusout(function() {
  $('.referencelinktagline').addClass('displaynone');
});

function formaddress(){
	return new Promise((resolve, reject) => {
		setTimeout(function(){
			var address = [];
		
			if($('[name="address"]').val()!='') 						address.push($.trim($('[name="address"]').val()));
			if($('[name="street"]').val()!='') 							address.push($.trim($('[name="street"]').val()));
			if($('[name="number"]').val()!='') 							address.push($.trim($('[name="number"]').val()));
			if($('#province').val()!='') 								address.push($.trim($('#province option:selected').text()));
			if($('#city option').length && $('#city').val()!='') 		address.push($.trim($('#city option:selected').text()));
			if($('#suburb option').length && $('#suburb').val()!='') 	address.push($.trim($('#suburb option:selected').text()));
			
			if(address.join('')!=''){
				address.push('South Africa');
				var result = address.join(', ');
			}else{
				var result = '';
			}

			console.log(result);
			resolve(result);
		}, 1000);
	});
}

async function addressmap(){
	var address 	= await formaddress();
	var geocoder 	= new google.maps.Geocoder();

	geocoder.geocode(
		{
			'address': address,
			'componentRestrictions': {
				country: 'ZA'
			}
		}, 
		function(results, status){
			var locationtype = new Array('ROOFTOP');
			
			if (address!='' && status == google.maps.GeocoderStatus.OK && results[0].geometry && $.inArray(results[0].geometry.location_type, locationtype)!= -1){
				var latitude 		= results[0].geometry.location.lat();
				var longitude 		= results[0].geometry.location.lng();
				var markertoggle 	= 1;
			}else{
				var latitude 		= -26.195246;
				var longitude 		= 28.034088;
				var markertoggle 	= 0;
			} 
			
			var myLatLng = {lat: latitude, lng: longitude};
			
			var map = new google.maps.Map(document.getElementById('addressmap'), {
				zoom: 9,
				center: myLatLng,
				scrollwheel: false,
				draggable:false,
				disableDefaultUI: true
			});
			
			if(markertoggle==1){
				var marker = new google.maps.Marker({
					position: myLatLng,
					map: map
				});
			}
		}
	);
}

$(window).on('load', function(){
	addressmap();
})
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $this->config->item('googleapikey'); ?>"></script>