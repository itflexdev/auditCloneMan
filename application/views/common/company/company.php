<?php
	$id						= $result['id'];
	$usersdetailid 			= isset($result['usersdetailid']) ? $result['usersdetailid'] : '';
	$userscompanyid 		= isset($result['userscompanyid']) ? $result['userscompanyid'] : '';
	
	$email 					= isset($result['email']) ? $result['email'] : '';
	$email2 				= isset($result['email2']) ? $result['email2'] : '';
	$createdat 				= isset($result['created_at']) ? $result['created_at'] : '';
	
	$company 				= isset($result['company']) ? $result['company'] : '';
	$reg_no 				= isset($result['reg_no']) ? $result['reg_no'] : '';
	$vat_no 				= isset($result['vat_no']) ? $result['vat_no'] : '';
	$contact_person 		= isset($result['contact_person']) ? $result['contact_person'] : '';
	$mobilephone 			= isset($result['mobile_phone']) ? $result['mobile_phone'] : '';
	$mobile_phone2 			= isset($result['mobile_phone2']) ? $result['mobile_phone2'] : '';
	$workphone 				= isset($result['work_phone']) ? $result['work_phone'] : '';
	$home_phone 			= isset($result['home_phone']) ? $result['home_phone'] : '';
	$companystatusid 		= isset($result['companystatus']) ? $result['companystatus'] : '';

	$description 			= isset($result['companydescription']) ? $result['companydescription'] : '';
	$websiteurl 			= isset($result['websiteurl']) ? $result['websiteurl'] : '';
	$vat_vendor 			= isset($result['vat_vendor']) ? $result['vat_vendor'] : '0';

	$includeprofile 		= isset($result['includeprofile']) ? $result['includeprofile'] : '';
	$file1 					= isset($result['file1']) ? $result['file1'] : '';
	$cocpurchaselimit 		= isset($result['coc_purchase_limit']) && $result['coc_purchase_limit']!='0' ? $result['coc_purchase_limit'] : 0;
	
	$physicaladdress 		= isset($result['physicaladdress']) ? explode('@-@', $result['physicaladdress']) : [];
	$addressid1 			= isset($physicaladdress[0]) ? $physicaladdress[0] : '';
	$address1				= isset($physicaladdress[2]) ? $physicaladdress[2] : '';
	$suburb1 				= isset($physicaladdress[3]) ? $physicaladdress[3] : '';
	$city1 					= isset($physicaladdress[4]) ? $physicaladdress[4] : '';
	$province1 				= isset($physicaladdress[5]) ? $physicaladdress[5] : '';
	$postalcode1 			= isset($physicaladdress[6]) ? $physicaladdress[6] : '';
	
	$postaladdress 			= isset($result['postaladdress']) ? explode('@-@', $result['postaladdress']) : [];
	$addressid2 			= isset($postaladdress[0]) ? $postaladdress[0] : '';
	$address2				= isset($postaladdress[2]) ? $postaladdress[2] : '';
	$suburb2 				= isset($postaladdress[3]) ? $postaladdress[3] : '';
	$city2 					= isset($postaladdress[4]) ? $postaladdress[4] : '';
	$province2 				= isset($postaladdress[5]) ? $postaladdress[5] : '';
	$postalcode2 			= isset($postaladdress[6]) ? $postaladdress[6] : '';


	$companyname 			= isset($result['company_name']) ? $result['company_name'] : '';
	$billingemail 			= isset($result['billing_email']) ? $result['billing_email'] : '';
	$billingcontact 		= isset($result['billing_contact']) ? $result['billing_contact'] : '';

	$billingaddress 		= isset($result['billingaddress']) ? explode('@-@', $result['billingaddress']) : [];
	$addressid3 			= isset($billingaddress[0]) ? $billingaddress[0] : '';
	$address3				= isset($billingaddress[2]) ? $billingaddress[2] : '';
	$suburb3 				= isset($billingaddress[3]) ? $billingaddress[3] : '';
	$city3 					= isset($billingaddress[4]) ? $billingaddress[4] : '';
	$province3 				= isset($billingaddress[5]) ? $billingaddress[5] : '';
	$postalcode3 			= isset($billingaddress[6]) ? $billingaddress[6] : '';
	
	$work_type 				= isset($result['work_type']) ? array_filter(explode(',', $result['work_type'])) : [];
	$specialisations 		= isset($result['specialisations']) ? array_filter(explode(',', $result['specialisations'])) : [];
	
	$message 				= isset($result['message']) ? $result['message'] : '';
	$approval_status 		= isset($result['approval_status']) ? $result['approval_status'] : '0';
	$reject_reason 			= isset($result['reject_reason']) ? explode(',', $result['reject_reason']) : [];
	$reject_reason_other 	= isset($result['reject_reason_other']) ? $result['reject_reason_other'] : '';	

	$filepath				= base_url().'assets/uploads/company/'.$id.'/';
	$pdfimg 				= base_url().'assets/images/pdf.png';
	$profileimg 			= base_url().'assets/images/profile.jpg';
	
	if($file1!=''){
		$explodefile2 	= explode('.', $file1);
		$extfile2 		= array_pop($explodefile2);
		$photoidimg 	= (in_array($extfile2, ['pdf', 'tiff'])) ? $pdfimg : $filepath.$file1;
		$photoidurl		= $filepath.$file1;
	}else{
		$photoidimg 	= $profileimg;
		$photoidurl 	= 'javascript:void(0);';
	}
	
	if($roletype=='4' && $approval_status=='0'){
		$disabled1 			= 'disabled';
		$disabled1array 	= ['disabled' => 'disabled'];
		$disabled2 			= '';
		$disabled2array 	= [];
		
		$disablebtn			= '1';
	}elseif($roletype=='4' && $approval_status=='1'){
		$disabled1 			= '';
		$disabled1array 	= [];
		$disabled2 			= 'disabled';
		$disabled2array 	= ['disabled' => 'disabled'];
		$save_flag 			= '1';
	}elseif($roletype=='4' && $approval_status=='2'){
		$disabled1 			= 'disabledrej';
		$disabled1array 	= ['disabled' => 'disabled'];
		$disabled2 			= '';
		$disabled2array 	= [];
		
		$disablebtn			= '1';
	}else{
		$disabled1 			= '';
		$disabled1array 	= [];
		$disabled2 			= '';
		$disabled2array 	= [];
	}
	
	if($roletype=='1'){
		$dynamictabtitle 	= 'Company';
		$dynamicheading 	= 'Plumber Register';
		$dynamictitle 		= 'Plumbers Registration Details';
	}elseif($roletype=='4'){
		$dynamictabtitle 	= 'My';
		$dynamicheading 	= 'My Profile';
		$dynamictitle 		= 'My PIRB Registration Details';
	}
?>


<?php if($pagetype!='registration'){ ?>

<div class="row page-titles">
	<div class="col-md-5 align-self-center">
		<h4 class="text-themecolor">Company Details</h4>
	</div>
	<div class="col-md-7 align-self-center text-right">
		<div class="d-flex justify-content-end align-items-center">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">Home</li>
				<li class="breadcrumb-item active">Company Details</li>
			</ol>
		</div>
	</div>
</div>

<?php echo $notification; ?>
<?php if($roletype=='1'){ echo isset($menu) ? $menu : ''; } ?>
<h5 class="card-title app_status">Application Status:</h5>
<?php if($roletype=='1' && ($approval_status=='0' || $approval_status=='2')){ ?>
	<form class="form1" method="post">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-body">
						
						
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<div class="row">
										<div class="col-md-6">
											<label>Approval Status *</label>
										</div>
										<?php
											foreach($approvalstatus as $key => $value){
										?>
												<div class="col-md-3">
													<div class="custom-control custom-radio">
														<input type="radio" name="approval_status" id="<?php echo $key.'-'.$value; ?>" class="custom-control-input approvalstatus" value="<?php echo $key; ?>" <?php echo ($key==$approval_status) ? 'checked="checked"' : ''; ?>>
														<label class="custom-control-label" for="<?php echo $key.'-'.$value; ?>"><?php echo $value; ?></label>
													</div>
												</div>
										<?php
											}
										?>
									</div>
								</div>
								<div class="form-group reject_wrapper displaynone">
									<div class="row">
										<div class="col-md-6">
											<label>Reason for Rejection</label>
										</div>
										<div class="col-md-6">
											<?php
												foreach ($companyrejectreason as $key => $value) {
											?>
													<div class='custom-control custom-checkbox'>
														<input type='checkbox' class='custom-control-input reject_reason' name='reject_reason[]' id="<?php echo $key.'-'.$value; ?>" value="<?php echo $key; ?>" <?php echo (in_array($key, $reject_reason)) ? 'checked="checked"' : ''; ?>>
														<label class='custom-control-label' for="<?php echo $key.'-'.$value; ?>"><?php echo $value; ?></label>
													</div>
											<?php
												}
											?>
											<div class="form-group reject_reason_other_wrapper displaynone">
												<input type="text" class="form-control" placeholder="If Other specify" name="reject_reason_other" value="<?php echo $reject_reason_other; ?>">		
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Comments</label>
									<div id="commentdisplay">
										<?php
											foreach($comments as $comment){
										?>
												<p><?php echo date('d-m-Y', strtotime($comment['created_at'])).' '.$comment['createdby'].' '.$comment['comments']; ?></p>
										<?php
											}
										?>
									</div>
									<input type="text" class="form-control" placeholder="Type your comments here" name="comments">		
									<div class="text-right">
										<input type="hidden" name="usersdetailid" value="<?php echo $usersdetailid; ?>">										
										<input type="hidden" name="userscompanyid" value="<?php echo $userscompanyid; ?>">
										<button type="submit" name="submit1" id="submit1" value="approvalsubmit" class="btn btn-primary">Submit</button>
									</div>
								</div>
							</div>
						</div>
				
					</div>
				</div>
			</div>
		</div>
	</form>
<?php } ?>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">			
<?php } ?>			
				<form class="form2" method="post" action="">	
				<?php if(($roletype=='1' && $approval_status=='1') || ($pagetype!='registration' && $roletype=='4' && $result['formstatus'] !='0')){ ?>
									<div class="col-md-12 application_field_wrapper mb-15">
										<?php if($disabled1=='disabled'){ ?>
											<div class="application_field_status">
												<p>Application Pending</p>
											</div>
										<?php } ?>
										<?php if($disabled1=='disabledrej'){ ?>
											<div class="application_field_status">
												<p>Application Rejected</p>
											</div>
										<?php } ?>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label>PIRB Company ID</label>
													<input type="text" class="form-control" value="<?php echo $id; ?>" disabled>						
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Registration Date</label>
													<input type="text" class="form-control" value="<?php echo date('d-m-Y', strtotime($createdat)); ?>" disabled>						
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Status</label>
													<?php
														echo form_dropdown('companystatus', $companystatus, $companystatusid, ['id' => 'companystatus', 'class'=>'form-control']+$disabled2array);
													?>
												</div>
											</div>
											<div class="col-md-12">
												<label>Specific Message to Company</label>
												<textarea class="form-control" rows="5" name="message" <?php echo $disabled2; ?>><?php echo $message; ?></textarea>
											</div>
										</div>					
									</div>
								<?php } ?>	
					<div class="accordion add_top_value" id="companyaccordion">
						<div class="card">
							<div class="card-header" id="CompanyDetails">
								<h2 class="mb-0">
									<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#tab1" aria-expanded="true" aria-controls="tab1">
										Company Details
									</button>
								</h2>
							</div>
							<div id="tab1" class="collapse show" aria-labelledby="CompanyDetails" data-parent="#companyaccordion">
								<div class="card-body">
									
					
									<h4 class="card-title">Company Details</h4>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Company Name *</label>
												<input type="text" class="form-control" id="name" name="name" value="<?php echo $company; ?>">
											</div>

											<div class="form-group">
												<label>Website URL</label>
												<input type="text" class="form-control" id="websiteurl" name="websiteurl" value="<?php echo $websiteurl; ?>">
											</div>

											<div class="form-group">
												<label>Primary Contact Person *</label>
												<input type="text" class="form-control" id="contact_person" name="contact_person" value="<?php echo $contact_person; ?>">
												</div>

												<div class="form-group">
													<label>Company description</label>
											<textarea class="form-control" rows="5" name="companydescription" <?php //echo $disabled2; ?>><?php echo $description; ?></textarea>
												</div>

												<div class="custom-control custom-radio cust_btmsp">
												<input type="checkbox" name="includeprofile" id="includeprofile" class="custom-control-input" value="1" <?php if($includeprofile =='1'){ echo 'checked="checked"'; }  ?>>
												<label class="custom-control-label" for="includeprofile">Include profile in PIRB company listings <a href="javascript:void(0)" id="executequery" data-toggle="tooltip" data-placement="top" title='kindly replace the new content on "inclue profile in PIRB " popup When enabled, your company will appear on www.pirb.co.za's Company Search Engine'><i class="fa fa-exclamation-circle"></i></a></label>
											</div>
										</div>
										<div class="col-md-6">
											<!-- <div class="custom-control custom-radio">
												<input type="checkbox" name="includeprofile" id="includeprofile" class="custom-control-input" value="1" <?php if($includeprofile =='1'){ echo 'checked="checked"'; }  ?>>
												<label class="custom-control-label" for="includeprofile">Include profile in PIRB company listings <a href="javascript:void(0)" id="executequery" data-toggle="tooltip" data-placement="top" title='kindly replace the new content on "inclue profile in PIRB " popup When enabled, your company will appear on www.pirb.co.za's Company Search Engine'><i class="fa fa-exclamation-circle"></i></a></label>
											</div> -->
											<div class="row img-sectn">
										<!-- <div class="col-md-6">
											<div class="form-group">
												<label>Company Registration Number *</label>
												<input type="text" class="form-control" id="reg_no" name="reg_no" value="<?php //echo $reg_no; ?>">
											</div>
										</div> -->

											<h4 class="card-title">Company Image</h4>
											<div class="form-group">
												<div>
													<a href="<?php echo $photoidurl; ?>" target="_blank"><img src="<?php echo $photoidimg; ?>" class="photo_image" width="100"></a>
												</div>
												<input type="file" id="file_2" class="photo_file">
												<label for="file_2" class="choose_file">Choose File</label>
												<input type="hidden" name="image2" class="photo percentageslide" value="<?php echo $file1; ?>">
												<p>(Image/File Size Smaller than 5mb)</p>
											</div>
										<div class="nub_coc">
											<label>Number of CoC's Able to purchase:</label>
											<input type="number" class="form-control coc_purchase_limit" name="coc_purchase_limit" value="<?php echo $cocpurchaselimit; ?>" <?php echo $disabled1.$disabled2; ?>>
										</div>
									</div>
										</div>
									</div>
									
									<div class="row">
										<div class="col-md-6">
											<!-- <div class="form-group">
												<label>Website URL</label>
												<input type="text" class="form-control" id="websiteurl" name="websiteurl" value="<?php echo $websiteurl; ?>">
											</div> -->
										</div>
										<!-- <div class="col-md-6">
											<div class="form-group">
												<label>VAT Number</label>
												<input type="text" class="form-control" id="vat_no" name="vat_no" value="<?php // echo $vat_no; ?>">
											</div>
										</div> -->
									</div>
									<div class="row">
										<div class="col-md-6">
											<!-- <div class="form-group">
												<label>Primary Contact Person *</label>
												<input type="text" class="form-control" id="contact_person" name="contact_person" value="<?php echo $contact_person; ?>">
												</div> -->
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<!-- <label>Company description</label>
											<textarea class="form-control" rows="5" name="companydescription" <?php //echo $disabled2; ?>><?php echo $description; ?></textarea> -->
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<h4 class="card-title">Physical Address</h4>
											<p class="tagline">Note all delivery services will be sent to this address</p>
											<div class="form-group">
												<label>Physical Address *</label>
												<input type="hidden" class="form-control" name="address[1][id]" value="<?php echo $addressid1; ?>">
												<input type="hidden" class="form-control" name="address[1][type]" value="1">
												<input type="text" class="form-control" id="physicallsaddr" name="address[1][address]"  value="<?php echo $address1; ?>">
											</div>
										</div>
										<div class="col-md-6">
											<h4 class="card-title">Postal Address</h4>
											<p class="tagline">Note all postal services will be sent to this address</p>
											<div class="form-group">
												<label>Postal Address *</label>
												<input type="hidden" class="form-control" name="address[2][id]" value="<?php echo $addressid2; ?>">
												<input type="hidden" class="form-control" name="address[2][type]" value="2">
												<input type="text" class="form-control" id="postalarrs" name="address[2][address]" value="<?php echo $address2; ?>">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">								
											<div class="form-group"> 
												<label>Province *</label>
												<?php 
													echo form_dropdown('address[1][province]', $province, $province1, ['id' => 'province1', 'class' => 'form-control']); 
												?>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Province *</label>
												<?php
													echo form_dropdown('address[2][province]', $province, $province2, ['id' => 'province2', 'class'=>'form-control']);
												?>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>City *</label>
												<?php 
													echo form_dropdown('address[1][city]', [], $city1, ['id' => 'city1', 'class' => 'form-control']); 
												?>
												<div>
														<a href="javascript:void(0);" id="addcity1">Add City</a>
														<div class="input-group addcity_wrapper displaynone">
															<input type="text" class="form-control" placeholder="Add City">
															<div class="input-group-append">
																<button class="btn btn-outline-secondary" id="addcitysubmit1" type="button">Add</button>
															</div>
														</div>
													</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>City *</label>
												<?php 
													echo form_dropdown('address[2][city]', [], $city2, ['id' => 'city2', 'class' => 'form-control']); 
												?>
												<div>
														<a href="javascript:void(0);" id="addcity2">Add City</a>
														<div class="input-group addcity_wrapper displaynone">
															<input type="text" class="form-control" placeholder="Add City">
															<div class="input-group-append">
																<button class="btn btn-outline-secondary" id="addcitysubmit2" type="button">Add</button>
															</div>
														</div>
													</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Suburb *</label>
												<?php
													echo form_dropdown('address[1][suburb]', [], $suburb1, ['id' => 'suburb1', 'class'=>'form-control']);
												?>
												<div>
														<a href="javascript:void(0);" id="addsuburb1">Add Suburb</a>
														<div class="input-group addsuburb_wrapper displaynone">
															<input type="text" class="form-control" placeholder="Add Suburb">
															<div class="input-group-append">
																<button class="btn btn-outline-secondary" id="addsuburbsubmit1" type="button">Add</button>
															</div>
														</div>
													</div>
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<label>Suburb *</label>
												<?php
													echo form_dropdown('address[2][suburb]', [], $suburb2, ['id' => 'suburb2', 'class'=>'form-control']);
												?>
												<div>
														<a href="javascript:void(0);" id="addsuburb2">Add Suburb</a>
														<div class="input-group addsuburb_wrapper displaynone">
															<input type="text" class="form-control" placeholder="Add Suburb">
															<div class="input-group-append">
																<button class="btn btn-outline-secondary" id="addsuburbsubmit2" type="button">Add</button>
															</div>
														</div>
													</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6 offset-6">
											<div class="form-group">
												<label>Postal Code *</label>
												<input type="text" class="form-control" id="postaladdrcomp" name="address[2][postal_code]" value="<?php echo $postalcode2; ?>">
											</div>
										</div>
									</div>
									<h4 class="card-title">Contact Details</h4>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Home Phone:</label>
												<input type="text" class="form-control" name="home_phone" id="home_phone" value="<?php echo $home_phone; ?>">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Work Phone*:</label>
												<input type="text" class="form-control" name="work_phone" id="work_phone" value="<?php echo $workphone; ?>">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<?php if ($pagetype=='registration') {
													$mobile_lable = "Mobile Phone of Primary Contact*:";
												}else{
													$mobile_lable = "Mobile Phone*:";
												}
												?>
												<label><?php echo $mobile_lable; ?></label>
												<input type="text" class="form-control" name="mobile_phone" id="mobile_phone" value="<?php echo $mobilephone; ?>">
												<p>Note all SMS and OTP notifications will be sent to this mobile number above</p>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Secondary Mobile Phone:</label>
												<input type="text" class="form-control" name="secondary_phone" id="secondary_phone" value="<?php echo $mobile_phone2; ?>">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Email Address *</label>
												<input type="text" class="form-control" name="email" value="<?php echo $email; ?>" <?php if($roletype!='1'){ echo 'readonly'; } ?>>
												<p>Note: this email will be used as your user profile name and all emails notifications will be sent to it</p>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Secondary Email Address:</label>
												<input type="text" class="form-control" name="email2" id="email2" value="<?php echo $email2; ?>">
											</div>
										</div>
									</div>	
								</div>
							</div>
						</div>	

						<div class="card">
							<div class="card-header" id="CompanyBillingDetails">
								<h2 class="mb-0">
									<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#tab3" aria-expanded="true" aria-controls="tab3">
										<?php echo $dynamictabtitle; ?> Billing Details
									</button>
								</h2>
							</div>
							<div id="tab3" class="collapse" aria-labelledby="CompanyBillingDetails" data-parent="#companyaccordion">
								<div class="card-body">

									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label>Billing Name *</label>
												<input type="text" class="form-control percentageslide" name="company_name" value="<?php echo $companyname; ?>">
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label>Company Reg Number</label>
												<input type="text" class="form-control" name="reg_no1" value="<?php echo $reg_no; ?>">
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label>Company VAT</label>
												<input type="text" class="form-control" id="vat_no" name="vat_no" value="<?php echo $vat_no; ?>">
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<div class="custom-control custom-checkbox mr-sm-2 mb-3 pt-2">	
													<input type="checkbox" class="custom-control-input" <?php echo ($vat_vendor =='1') ? 'checked="checked"' : ''; ?> value="1" name="vatvendor" id="vatvendor">
													<label class="custom-control-label" for="vatvendor">VAT Vendor</label>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Billing Email *</label>
												<input type="text" class="form-control percentageslide" name="billing_email" value="<?php echo $billingemail; ?>">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Billing Contact *</label>
												<input type="text" class="form-control percentageslide" id="billing_contact" name="billing_contact" value="<?php echo $billingcontact; ?>">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Billing Address *</label>
												<input type="hidden" class="form-control" name="address[3][id]" value="<?php echo $addressid3; ?>">
												<input type="hidden" class="form-control" name="address[3][type]" value="3">
												<input type="text" class="form-control percentageslide" name="address[3][address]" value="<?php echo $address3; ?>">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Province *</label>
												<?php
													echo form_dropdown('address[3][province]', $province, $province3, ['id' => 'province3', 'class'=>'form-control percentageslide']);
												?>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>City *</label>
												<?php 
													echo form_dropdown('address[3][city]', [], $city3, ['id' => 'city3', 'class' => 'form-control percentageslide']); 
												?>
												<div>
													<a href="javascript:void(0);" id="addcity3">Add City</a>
													<div class="input-group addcity_wrapper displaynone">
														<input type="text" class="form-control" placeholder="Add City">
														<div class="input-group-append">
															<button class="btn btn-outline-secondary" id="addcitysubmit3" type="button">Add</button>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Suburb *</label>
												<?php 
													echo form_dropdown('address[3][suburb]', [], $suburb3, ['id' => 'suburb3', 'class'=>'form-control percentageslide']);
												?>
												<div>
													<a href="javascript:void(0);" id="addsuburb3">Add Suburb</a>
													<div class="input-group addsuburb_wrapper displaynone">
														<input type="text" class="form-control" placeholder="Add Suburb">
														<div class="input-group-append">
															<button class="btn btn-outline-secondary" id="addsuburbsubmit3" type="button">Add</button>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Postal Code *</label>
												<input type="text" class="form-control percentageslide" name="address[3][postal_code]" value="<?php echo $postalcode3; ?>">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="card">
							<div class="card-header" id="CompanyCategoriesDetails">
								<h2 class="mb-0">
									<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#tab4" aria-expanded="true" aria-controls="tab4">
										<?php echo 'Company'; ?> Categories
									</button>
								</h2>
							</div>
							<div id="tab4" class="collapse" aria-labelledby="CompanyCategoriesDetails" data-parent="#companyaccordion">
								<div class="card-body">
									<div class="row">
									<div class="col-md-6">
										<h4 class="card-title">Specialisations and Categories</h4>
										<div class="col-md-6 cus_wd">
										<?php foreach ($worktype1 as $key => $value) {?>
											<div class="ord_div"><input type="checkbox" name="worktype[]" value="<?php echo $key ?>"<?php echo (in_array($key, $work_type)) ? 'checked="checked"' : ''; ?> > <?php echo $value ?><br></div>
										<?php };?>
										</div>
									</div>
								</div>
									<div class="row">
									<!-- <h4 class="card-title">Company Categories</h4> -->
									<div class="col-md-6">
										<h5 class="card-title">Company Specialisations</h5>
										<div class="col-md-6 cus_wd rm_pad">
										<?php foreach ($specialization as $key => $value) { 
											?>
											<div class="ord_div_sep"><input type="checkbox" name="specilisations[]" value="<?php echo $key ?>"<?php echo (in_array($key, $specialisations)) ? 'checked="checked"' : ''; ?>> <?php echo $value ?><br></div>
										<?php }; ?>
											
										</div>
									</div>
								</div>				
								
								</div>
							</div>
						</div>
<!-- 						<?php// if ($roletype =='4') { ?>
							<div class="card">
								<div class="card-header" id="CompanyDocuments">
									<h2 class="mb-0">
										<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#tab5" aria-expanded="true" aria-controls="tab5">
											Documents
										</button>
									</h2>
								</div>
								<div id="tab5" class="collapse" aria-labelledby="CompanyDocuments" data-parent="#companyaccordion">
									<div class="card-body">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label>Description *</label>
													<input type="text" class="form-control"  name="docdescription" id="docdescription"  value="">
													</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Document Type</label>
													<?php// echo form_dropdown('docmenttype', $documenttype, [], ['id' => 'docmenttype', 'class'=>'form-control']); ?>
												</div>
											</div>
										</div>
										<?php //if ($approval_status=='1') { ?>
											<div class="text-right">
												<button type="button" name="docsubmit" id="docsubmit" value="upload" class="btn btn-primary">Upload</button>
											</div>
										<?php //} ?>
										
											<input type="hidden" name="id" value="<?php// echo $id; ?>">
										<div class="table-responsive mt_20">
											<table class="table documentstable table-bordered table-striped datatables fullwidth text_left">
												<thead>
													<tr>
														<th>Date</th>
														<th>Description</th>
														<th>Document Type</th>
														<th>Action</th>
													</tr>
												</thead>
											</table>
										</div>
									</div>
								</div>
							</div>
						<?php// } ?> -->
					</div>
							
					<div class="col-md-6 text-right">
						<input type="hidden" name="id" value="<?php echo $id; ?>">
						<input type="hidden" name="user_id" value="<?php echo $id; ?>">
						<input type="hidden" name="usersdetailid" value="<?php echo $usersdetailid; ?>">
						<input type="hidden" name="userscompanyid" value="<?php echo $userscompanyid; ?>">
						<?php if ($roletype !='4') { ?>
							<input type="hidden" name="roletype" value="<?php echo $roletype; ?>">
						<?php } ?>
						<?php if ($roletype!='1') {
							?>
							
						<?php if($pagetype=='companyprofile' && $result['formstatus'] =='1' && $approval_status=='1'){
							echo '<button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">Update</button>';
						}
						 }else{ ?> 
							<button type="submit" id="submit" name="submit" value="submit" class="btn btn-primary">Submit</button>
						<?php } ?>
					</div>
				</form>
				
<?php if($pagetype!='registration'){ ?>			
			</div>
		</div>
	</div>
</div>
<?php } ?>

<script type="text/javascript">
var userid				= '<?php echo $id; ?>';
var filepath 			= '<?php echo $filepath; ?>';
var pdfimg				= '<?php echo $pdfimg; ?>';
var companyid			= '<?php echo $id; ?>';
var doctype				= JSON.parse('<?php echo json_encode($documenttype); ?>');

$(function(){


	var documentlist = $.parseJSON('<?php echo addslashes(json_encode($documentlist)); ?>');
	if(documentlist.length > 0){
		$(documentlist).each(function(i, v){
			var documentlistdata 	= {status : 1, result : { id: v.id, userid: v.user_id, description: v.description, document_type: v.document_type, created_at: v.created_at}}
			documents(documentlistdata);
		})
	}

	fileupload([".photo_file", "./assets/uploads/\company/"+userid+"/", ['jpg','gif','jpeg','png','pdf','tiff','tif']], ['.photo', '.photo_image', filepath, pdfimg]);
		
	$('#contact_person').bind('keyup blur', function() { 
	    $(this).val(function(i, val) {
	        return val.replace(/[^a-z\s]/gi,''); 
	    });
	});

		 //     $("#contact_person").filter(function(value) {
			// 		return /^[A-Za-z]+$/.test(value); // Allow digits only, using a RegExp
			// });
		

	select2('#province1, #city1, #suburb1, #province2, #city2, #suburb2, #province3, #city3, #suburb3');
	inputmask('#work_phone, #mobile_phone,#home_phone,#secondary_phone', 1);
	citysuburb(['#province1','#city1', '#suburb1'], ['<?php echo $city1; ?>', '<?php echo $suburb1; ?>'], ['#addcity1', '#addcitysubmit1', '#addsuburb1', '#addsuburbsubmit1']);
	citysuburb(['#province2','#city2', '#suburb2'], ['<?php echo $city2; ?>', '<?php echo $suburb2; ?>'], ['#addcity2', '#addcitysubmit2', '#addsuburb2', '#addsuburbsubmit2']);
	citysuburb(['#province3','#city3', '#suburb3'], ['<?php echo $city3; ?>', '<?php echo $suburb3; ?>'], ['#addcity3', '#addcitysubmit3', '#addsuburb3', '#addsuburbsubmit3']);
	
	var approvalstatus = '<?php echo $approval_status; ?>';
	if(approvalstatus!='') $('.approvalstatus[value="'+approvalstatus+'"]').data('approvalStatusValue', true);
	rejectwrapper(approvalstatus);
	
	rejectother();

	validation( 
		'.form2',
		{
			name : {
				required	: true,
				lettersandhypen	: true
			},
			reg_no : {
				required	: true,
			},
			email : {
				email		: true,
				remote		: 	{
								url	: "<?php echo base_url().'authentication/login/emailvalidation'; ?>",
								type: "post",
								async: false,
								data: {
										id 		: '<?php echo $id; ?>',
										type 	: '4'
										}
									}
								},
			vat_no : {
				required	: function() {
								return $('#vatvendor').is(':checked');
							},
			},
			contact_person : {
				required	: true,
			},
			'address[1][address]' : {
				required	: true,
			},
			'address[2][address]' : {
				required	: true,
			},
			 'address[1][province]' : {
				required	: true,
			},
			'address[2][province]' : {
				required	: true,
			},
			'address[1][city]' : {
				required	: true,
			},
			'address[2][city]' : {
				required	: true,
			},
			'address[1][suburb]' : {
				required	: true,
			},
			'address[2][suburb]' : {
				required	: true,
			},
			'address[2][postal_code]' : {
				required	: true,
				number 		: true,
			},
			'worktype[]' : {
				required	: true,
			},
			'specilisations[]' : {
				required	: true,
			},
			 work_phone : {
				required	: true,
			},
			mobile_phone : {
				required	: true,
			},
			company_name : {
				required	: true,
				lettersandhypen	: true
			},
			billing_email : {
				email		: true,
			},
			billing_contact : {
				required	: true,
			},
			'address[3][address]' : {
				required	: true,
			},
			 'address[3][province]' : {
				required	: true,
			},
			'address[3][city]' : {
				required	: true,
			},
			'address[3][suburb]' : {
				required	: true,
			},
			'address[3][postal_code]' : {
				required	: true,
				number	: true,
			},
			
		},
		{
			name : {
				required	: "Company name field is required.",
			},
			reg_no : {
				required	: "Registration number field is required.",
			},
			vat_no : {
				required	: "VAT field is required.",
			},
			contact_person : {
				required	: "Contact preson field is required.",
			},
			'address[1][address]' : {
				required	: "Phydical address field is required.",
			},
			'address[2][address]' : {
				required	: "Postal address field is required.",
			},
			email : {
					remote		: "Email already exists."
				},
			'address[1][province]' : {
				required	: "Physical Province field is required.",
			},
			'address[2][province]' : {
				required	: "Postal Province field is required.",
			},
			'address[1][city]' : {
				required	: "Physical City field is required.",
			},
			'address[2][city]' : {
				required	: "Postal City field is required.",
			},
			'address[1][suburb]' : {
				required	: "Physical Suburb field is required.",
			},
			'address[2][suburb]' : {
				required	: "Postal Suburb field is required.",
			},
			'address[2][postal_code]' : {
				required	: "Postal Code field is required.",
				number 	: "Numbers Only.",
			},
			'address[3][postal_code]' : {
				required	: "Postal Code field is required.",
				number 		: "Numbers Only.",
			},
			'worktype[]' : {
				required	: "Worktype is required.",
			},
			'specilisations[]' : {
				required	: "Specilisations is required.",
			},
			work_phone : {
				required	: "Work phone field is required.",
			},
			mobile_phone : {
				required	: "Mobile phone field is required.",
			},
		}	
	);

		validation( 
		'.form1',
		{
			approval_status : {
				required	: true,
			},
			comments : {
				required	: true,
			},
			'reject_reason[]' : {
				required:  	function() {
								return $('#2-Rejected').is(":checked");
							}
			},
			reject_reason_other : {
				required:  	function() {
								return $('#2-Other').is(":checked");
							}
			},
			
		},
		{
			approval_status : {
				required	: "Approval status is required.",
			},
			comments : {
				required	: "Comments field is required.",
			},
			'reject_reason[]' : {
				required	: "Reject reason field is required.",
			},
			reject_reason_other : {
				required	: "Reject reason other field is required.",
			},
		}
	);

	$('#submit1').click(function(e){
		$('.form1').valid()==true
	});

	$('#docsubmit').click(function(e){
		 if ($("#docdescription").val() != '') {
		 	var data = {companyid : userid,description : $('#docdescription').val(), docmenttype : $('#docmenttype').val()};
			ajax('<?php echo base_url()."company/profile/index/actionDocuments"; ?>', data, documents);
			sweetalertautoclose('Document uploaded successfully.');
		 }else{
		 	alert('Document description required.');
		 }
	});

	$('#submit').click(function(e){
		$('.form2').valid()==true
	});

	$('.deletedoc').click(function(e){
		ajax('<?php echo base_url()."company/profile/index/Deletefunc"; ?>', {'id' : $(this).attr('data-id'), 'action' : 'delete'}, removedoc);
		$(this).parent().parent().remove();
		sweetalertautoclose('Document deleted successfully.');
	});
})
	
	
$('.approvalstatus').click(function(){

	var previousValue = $(this).data('approvalStatusValue');
    if (previousValue){
		$(this).prop('checked', !previousValue);
		$(this).data('approvalStatusValue', !previousValue);
    }else{
		$(this).data('approvalStatusValue', true);
		$(".approvalstatus:not(:checked)").data("approvalStatusValue", false);
    }

	rejectwrapper((($(this).is(':checked')) ? $(this).val() : 0));
})

function removedoc(){}

function rejectwrapper(value){
	$('.reject_wrapper').addClass('displaynone');
	$('.pending_approval_status').remove();
	
	if(value=='0'){
		$('.reject_wrapper').append('<input type="hidden" value="0" name="approval_status" class="pending_approval_status">');
	}else if(value=='2'){
		$('.reject_wrapper').removeClass('displaynone');
	}
}

$('.reject_reason').click(function(){
	rejectother();
})

function rejectother(){
	var flag = 0;
	
	$('.reject_reason').each(function(){
		if($(this).is(':checked') && $(this).val()=='2'){
			flag = 1;
		}
	})
	
	if(flag==1){
		$('.reject_reason_other_wrapper').removeClass('displaynone');
	}else{
		$('.reject_reason_other_wrapper').addClass('displaynone');
	}
}

function documents(data){
	var result 		= 	data.result; 
	var date 		= 	result.created_at;
	action 	= 	'<td>\
							<a href="javascript:void(0);" class="deletedoc" data-id="'+result.id+'"><i class="fa fa-trash" style="color:red;"></i></a>\
						</td>';
	var appenddata 	= 	'\
								<tr class="documentappend">\
									<td>'+date+'</td>\
									<td>'+result.description+'</td>\
									<td>'+((result.document_type!=null) ? doctype[result.document_type] : "")+'</td>\
									'+action+'\
								</tr>\
							';
	$('.documentstable').append(appenddata);
}

</script>

