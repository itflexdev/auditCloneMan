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
	// echo "<pre>";print_r($billingaddress);die;
	
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
		$dynamictabtitle 	= 'Plumbers';
		$dynamicheading 	= 'Plumber Register';
		$dynamictitle 		= 'Plumbers Registration Details';
	}elseif($roletype=='3'){
		$dynamictabtitle 	= 'My';
		$dynamicheading 	= 'My Profile';
		$dynamictitle 		= 'My PIRB Registration Details';
	}
?>
<div class="row page-titles">
	<div class="col-md-5 align-self-center">
		<h4 class="text-themecolor">Company register</h4>
	</div>
	<div class="col-md-7 align-self-center text-right">
		<div class="d-flex justify-content-end align-items-center">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
				<li class="breadcrumb-item active">Company register</li>
			</ol>
		</div>
	</div>
</div>
<?php echo $notification; ?>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="col-md-12 breadcrumb_tab">
					<a href="javascript:void(0);" class="stepbar" data-id="1">Welcome</a>
					<a href="javascript:void(0);" class="stepbar" data-id="2">Company Details</a>
					<a href="javascript:void(0);" class="stepbar" data-id="3">Billing Details</a>
					<a href="javascript:void(0);" class="stepbar" data-id="4">Declaration</a>
				</div>
				
				<div class="col-md-12 pagination">
					<a href="javascript:void(0);" id="previous">Previous</a>
					<div class="progress-circle p10" data-id="1">
					   <span>10%</span>
					   <div class="left-half-clipper">
						  <div class="first10-bar"></div>
						  <div class="value-bar"></div>
					   </div>
					</div>
					<div class="progress-circle p50" data-id="2">
					   <span>50%</span>
					   <div class="left-half-clipper">
						  <div class="first10-bar"></div>
						  <div class="value-bar"></div>
					   </div>
					</div>
					<div class="progress-circle p50" data-id="3">
					   <span>80%</span>
					   <div class="left-half-clipper">
						  <div class="first10-bar"></div>
						  <div class="value-bar"></div>
					   </div>
					</div>
					<div class="progress-circle 50" data-id="4">
					   <span>100%</span>
					   <div class="left-half-clipper">
						  <div class="first10-bar"></div>
						  <div class="value-bar"></div>
					   </div>
					</div>

					
					<a href="javascript:void(0);" id="next">Next</a>
				</div>
				
				<div class="steps active" data-id="1">
					<h4 class="card-title">Registered Company Details</h4>
					<p>
						Donec augue enim, volutpat at ligula et, dictum laoreet sapien. Sed maximus feugiat tincidunt. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla eu mollis leo, eu elementum nisl. Curabitur cursus turpis nibh, egestas efficitur diam tristique non. Proin faucibus erat ligula, nec interdum odio rhoncus vel. Nulla facilisi. Nulla vehicula felis lorem, sed molestie lacus maximus quis. Mauris dolor enim, fringilla ut porta sed, ullamcorper id quam. Integer in eleifend justo, quis cursus odio. Pellentesque fermentum sapien elit, aliquam rhoncus neque semper in. Duis id consequat nisl, vitae semper elit. Nulla tristique lorem sem, et pretium magna cursus sit amet. Maecenas malesuada fermentum mauris, at vestibulum arcu vulputate a.
					</p>
				</div>
				<div class="steps displaynone" data-id="2">
					<form class="form2" id="form2" method="post" action="">					
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
							<textarea class="form-control" rows="5" name="companydescription" <?php echo $disabled2; ?>><?php echo $description; ?></textarea>

								</div>

								<div class="custom-control custom-radio cust_btmsp">
								<input type="checkbox" name="includeprofile" id="includeprofile" class="custom-control-input" value="1" <?php if($includeprofile =='1'){ echo 'checked="checked"'; } if($result['formstatus'] == '1'){ echo "disabled"; } ?>>
								<label class="custom-control-label" for="includeprofile">Include profile in PIRB company listings <a href="javascript:void(0)" id="executequery" data-toggle="tooltip" data-placement="top" title='kindly replace the new content on "inclue profile in PIRB " popup When enabled, your company will appear on www.pirb.co.za's Company Search Engine'><i class="fa fa-exclamation-circle"></i></a></label>
								
							</div>
						</div>
						<div class="col-md-6">
							<!-- <div class="custom-control custom-radio">
								<input type="checkbox" name="includeprofile" id="includeprofile" class="custom-control-input" value="1" <?php if($includeprofile =='1'){ echo 'checked="checked"'; } if($result['formstatus'] == '1'){ echo "disabled"; } ?>>
								<label class="custom-control-label" for="includeprofile">Include profile in PIRB company listings <a href="javascript:void(0)" id="executequery" data-toggle="tooltip" data-placement="top" title='kindly replace the new content on "inclue profile in PIRB " popup When enabled, your company will appear on www.pirb.co.za's Company Search Engine'><i class="fa fa-exclamation-circle"></i></a></label>
								
							</div> -->

							<div class="img-sectn">

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
						</div>

						</div>
					</div>
					<!-- <div class="row"> -->
						<!-- <div class="col-md-6">
							<div class="form-group">
								<label>Company Registration Number *</label>
								<input type="text" class="form-control" id="reg_no" name="reg_no" value="<?php // echo $reg_no; ?>">
							</div>
						</div> -->

						<!-- <div class="col-md-3">
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
						</div>
					</div> -->
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
							<textarea class="form-control" rows="5" name="companydescription" <?php echo $disabled2; ?>><?php echo $description; ?></textarea> -->
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
					<div class="row">
						<div class="col-md-6">
							<h4 class="card-title">Company Categories of Service</h4>
							<div class="col-md-6 cus_wd">
							<?php foreach ($worktype1 as $key => $value) {?>
								<div class="ord_div"><input type="checkbox" name="worktype[]" value="<?php echo $key ?>"<?php echo (in_array($key, $work_type)) ? 'checked="checked"' : ''; ?> > <?php echo $value ?><br>
							</div><?php };?>
							</div>
						</div>
					</div>				
					<div class="row">
						<!-- <h4 class="card-title">Company Categories</h4> -->
						<div class="col-md-6">
							<h5 class="card-title">Company Specialisations</h5>
							<div class="col-md-6 cus_wd">
							<?php foreach ($specialization as $key => $value) { 
								?>
								<div class="ord_div_sep"><input type="checkbox" name="specilisations[]" value="<?php echo $key ?>"<?php echo (in_array($key, $specialisations)) ? 'checked="checked"' : ''; ?>> <?php echo $value ?><br></div>
							<?php }; ?>
								
							</div>
						</div>
					</div>
						<?php if ($roletype !='4') { ?>
							<input type="hidden" name="roletype" value="<?php echo $roletype; ?>">
						<?php } ?>
						<?php if ($roletype!='1') {
							if((!isset($disablebtn) &&  !isset($save_flag)) || ($pagetype=='registration') || ($pagetype=='companyprofile' && $result['formstatus'] =='0')){ ?>
								<div class="col-md-12 text-right">
								<button type="button" id="submit2" name="save" value="save" class="btn btn-primary">Save</button>
							</div>
						<?php }
						 } ?>
						 <input type="hidden" name="vatvendor" id="vatvendor-fr2">
				</form>
				</div>
				<div class="steps displaynone" data-id="3">
					<form class="form3">
						<h4 class="card-title">Billing Details</h4>
						<p>All invoices generated, will be used this billing information.</p>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Billing Name *</label>
									<input type="text" class="form-control percentageslide" name="company_name" value="<?php echo $companyname; ?>">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Company Reg Number *</label>
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
										<input type="checkbox" class="custom-control-input" <?php echo ($vat_vendor =='1') ? 'checked="checked"' : ''; ?> value="1" name="vat_vendor" id="vatvendor">
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
						<div class="col-md-12 text-right">
							<button type="button" id="submit3" class="btn btn-primary">Save</button>
						</div>
					</form>
				</div>
				<div class="steps displaynone" data-id="4">
					<form class="form4" method="post" action="">
						<div class="row">
							<?php echo $registerprocedure; ?>
							<label class="checkbox">
								<input type="checkbox" name="registerprocedure" data-checkbox="checkbox1">
								<p>I declare that I have fully read and understood the Procedure of Registration</p>
							</label>
							<?php  echo $acknowledgement; ?>
							<label class="checkbox">
								<input type="checkbox" name="acknowledgement" data-checkbox="checkbox1">
								<p>I declare that I have fully read and understood the Procedure of Acknowledgement</p>
							</label>
							 <?php  echo $codeofconduct; ?>
							<div class="col-md-12">
								<label class="checkbox">
									<input type="checkbox" name="codeofconduct" data-checkbox="checkbox1">
									<p>I declare that I have fully read and understood the PIRB's Code of Conduct</p>
								</label>
							</div>
							<div class="col-md-12">
								<label class="checkbox">								
									<input type="checkbox" name="declaration" data-checkbox="checkbox1">
									<p class="inlineblock">I</p>
									<input type="text" class="declarationname" name="declarationname" data-textbox="textbox1" placeholder="Name and surname"> 
								</label>
							</div>
							<?php echo $declaration; ?>
							<div class="col-md-12 text-right">
								<input type="hidden" name="application_received" value="<?php echo date('Y-m-d'); ?>">
								<input type="hidden" name="usersdetailid" id="usersdetailid" value="<?php echo $usersdetailid; ?>">
								<input type="hidden" name="userscompanyid" id="userscompanyid" value="<?php echo $userscompanyid; ?>">
								<input type="hidden" name="vatvendor" id="vatvendor-fr3">
								<button type="button" name="submit" value="submit" id="submit" class="btn btn-primary">Submit Application</button>
								<input type="submit" name="completeapplication" value="submit" id="completeapplication" class="displaynone">
							</div>
						</div>
					</form>
					<input type="hidden" name="id" value="<?php echo $id; ?>">
					<input type="hidden" name="user_id" value="<?php echo $id; ?>">
					<input type="hidden" name="usersdetailid" value="<?php echo $usersdetailid; ?>">
					<input type="hidden" name="userscompanyid" value="<?php echo $userscompanyid; ?>">


					<div id="skillmodal" class="modal fade" role="dialog">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<form class="skillform">
									<div class="modal-body">
										<div class="row">
											<div class="col-md-12 text-center">
												<div class="form-group">
													<h4 class="mb-15">Please confirm that you wish to submit your PIRB Company Application.</h4>
													<h4>A One Time Pin (OTP) was sent to the following Mobile Number : <span id="otpmobile"></span></h4>
												</div>
											</div>
											<div class="col-md-12">
												<div class="form-group">
													<input id="sampleotp" type="text" class="form-control skill_training" readonly>
													<label>Enter OTP</label>
													<input name="otpnumber" id="otpnumber" type="text" class="form-control">
												</div>
												<div class="otp-status"></div>
											</div>
											<div class="col-md-12 text-center">
												<input type="hidden" name="skill_id" class="skill_id">
												<button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
												<button type="button" class="btn btn-success resend">Resend</button>
												<button type="button" class="btn btn-success verify">Verify</button>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

var userid				= '<?php echo $id; ?>';
var filepath 			= '<?php echo $filepath; ?>';
var pdfimg				= '<?php echo $pdfimg; ?>';


$(function(){
	checkstep();

	$('#contact_person').bind('keyup blur', function() { 
	    $(this).val(function(i, val) {
	        return val.replace(/[^a-z\s]/gi,''); 
	    });
	});

	$('.resend').on('click',function(){
			ajaxotp();
		});
		// $('.verify').on('click',function(){
		// 	var otpver = $('#otpnumber').val();
		// 	ajaxOTPVerify(otpver);
		// });

	$('#vatvendor').on('click',function(){
		if($('#vatvendor').is(':checked')){
			$('#vatvendor-fr2').val('1');
			$('#vatvendor-fr3').val('1');
		}else{
			$('#vatvendor-fr2').val('');
			$('#vatvendor-fr3').val('');
		}
	});

	if($('#vatvendor').is(':checked')){
		$('#vatvendor-fr2').val('1');
		$('#vatvendor-fr3').val('1');
	}else{
		$('#vatvendor-fr2').val('');
		$('#vatvendor-fr3').val('');
	}

	select2('#province1, #city1, #suburb1, #province2, #city2, #suburb2, #province3, #city3, #suburb3');
	inputmask('#work_phone, #mobile_phone,#home_phone,#secondary_phone,#billing_contact', 1);
	citysuburb(['#province1','#city1', '#suburb1'], ['<?php echo $city1; ?>', '<?php echo $suburb1; ?>'], ['#addcity1', '#addcitysubmit1', '#addsuburb1', '#addsuburbsubmit1']);
	citysuburb(['#province2','#city2', '#suburb2'], ['<?php echo $city2; ?>', '<?php echo $suburb2; ?>'], ['#addcity2', '#addcitysubmit2', '#addsuburb2', '#addsuburbsubmit2']);
	citysuburb(['#province3','#city3', '#suburb3'], ['<?php echo $city3; ?>', '<?php echo $suburb3; ?>'], ['#addcity3', '#addcitysubmit3', '#addsuburb3', '#addsuburbsubmit3']);
	fileupload([".photo_file", "./assets/uploads/company/"+userid+"/", ['jpg','gif','jpeg','png','pdf','tiff','tif']], ['.photo', '.photo_image', filepath, pdfimg]);

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
			// vat_no : {
			// 	required	: true,
			// },
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
			
		},
		{
			name : {
				required	: "Company name field is required.",
			},
			reg_no : {
				required	: "Registration number field is required.",
			},
			// vat_no : {
			// 	required	: "VAT field is required.",
			// },
			contact_person : {
				required	: "Contact preson field is required.",
			},
			'address[1][address]' : {
				required	: "Physical address field is required.",
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
		},
		{
			ignore : '.test'
		}
			
	);

	validation( 
		'.form3',
		{
			company_name : {
				required	: true,
				lettersandhypen	: true
			},
			vat_no : {
				required	: function() {
								return $('#vatvendor').is(':checked');
							},
			},
			billing_email : {
				required	: true,
			},
			billing_contact : {
				required	: true,
			},
			reg_no1 : {
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
				number 		: true,
			},
			
		},
		{
			company_name : {
				required	: "Billing name field is required.",
			},
			vat_no : {
				required	: "VAT field is required.",
			},
			billing_email : {
				required	: "Billing email field is required.",
			},
			billing_contact : {
				required	: "Billing contact is required.",
			},
			reg_no1 : {
				required	: "Registration number field is required.",
			},
			'address[3][address]' : {
				required	: "Billing address field is required.",
			},
			'address[3][province]' : {
				required	: "Billing Province field is required.",
			},
			'address[3][city]' : {
				required	: "Billing City field is required.",
			},
			'address[3][suburb]' : {
				required	: "Billing Suburb field is required.",
			},
			'address[3][postal_code]' : {
				required	: "Postal Code field is required.",
				number 		: "Numbers Only.",
			},
		},
		{
			ignore : []
		}

			
	);

	validation( 
		'.form4',
		{
			registerprocedure : {
				required	: true,
			},
			acknowledgement : {
				required	: true,
			},
			codeofconduct : {
				required	: true,
			},
			declaration : {
				required	: true,
			},
			declarationname : {
				required	: true,
			},
			declarationidno : {
				required	: true,
			}
		},
		{
			registerprocedure 	: {
				required	: "Please Check registration process.",
			},
			acknowledgement 	: {
				required	: "Please Check acknowledgement.",
			},
			codeofconduct 	: {
				required	: "Please Check code of conduct.",
			},
			declaration 	: {
				required	: "Please Check declaration.",
			},
			declarationname : {
				required	: "Please enter name.",
			},
			declarationidno : {
				required	: "Please enter ID number.",
			}
		},
	);

})
	$('#submit2,#submit3,#submit4,#submit5').click(function(){
		var _this 	= $(this);
		var data 	= _this.parents('form').serialize()+'&'+$.param({ 'usersdetailid': $('#usersdetailid').val(), 'userscompanyid': $('#userscompanyid').val(), 'vatvendor' : $('#vatvendor-fr2').val() });
		ajax('<?php echo base_url()."company/registration/index/ajaxregistration"; ?>', data, registration, { beforesend : function(){ _this.attr('disabled','disabled') }, complete : function(){ _this.removeAttr('disabled'); sweetalertautoclose('Successfully Saved.'); } });
	})


$('#submit').click(function(e){
	var formvalid = 0;
	for(var i=2; i<=4; i++){
		$('.form'+i).valid();
		if($('.form'+i).valid()==false){
			// console.log('.form'+i);
			if(formvalid==0) formvalid = i; 
		}
	}
	
	if(formvalid==0){		
		for(var i=2; i<=5; i++){
			var data = $('#submit'+i).parents('form').serialize()+'&'+$.param({ 'usersdetailid': $('#usersdetailid').val(), 'userscompanyid': $('#userscompanyid').val(), 'vatvendor' : $('#vatvendor-fr2').val() });
			ajax('<?php echo base_url()."company/registration/index/ajaxregistration"; ?>', data, registration, { asynchronous : 1 });				
		}
		
		ajaxotp();
		$('#otpmobile').text($('#mobile_phone').val());
		$('#skillmodal').modal('show');
		return true;
	}else{
		alert('Before submitting please check the form');
		$('.stepbar[data-id="'+formvalid+'"]').click();
		return false;
	}
})

function ajaxotp(){
	ajax('<?php echo base_url().'ajax/index/ajaxotp'; ?>', {}, '', { 
		success:function(data){
			if(data!=''){
				$('#sampleotp').removeClass('displaynone').val(data);
			}
		}
	})
}

$(document).on('click', '.verify', function(){
	$('.error_otp').remove();
	var otp = $('#otpnumber').val();
	
	ajax('<?php echo base_url().'ajax/index/ajaxotpverification'; ?>', {otp: otp}, '', { 
		success:function(data){
			if (data == 0) {
				$('#otpnumber').parent().append('<p class="tagline error_otp">Incorrect OTP</p>');
			}else{
				$('#completeapplication').click();
			}
		}
	})
});

$('.progress-circle[data-id="1"]').addClass('active');
$('a.stepbar[data-id="1"]').addClass('active');

$('.stepbar').click(function(){
	var step = $(this).attr('data-id');
	$('.steps.active').addClass('displaynone').removeClass('active');
	$('.steps[data-id="'+step+'"]').removeClass('displaynone').addClass('active');
	
	$('.stepbar.active').addClass('un_active').removeClass('active');
	$('.stepbar[data-id="'+step+'"]').removeClass('un_active').addClass('active');

	$('.progress-circle.active').addClass('prog_hide').removeClass('active');
	$('.progress-circle[data-id="'+step+'"]').removeClass('prog_hide').addClass('active');
	checkstep();
})

$('#next').click(function(){
	var step = parseInt($('.steps.active').attr('data-id'))+1;
	
	$('.steps.active').addClass('displaynone').removeClass('active');
	$('.steps[data-id="'+step+'"]').removeClass('displaynone').addClass('active');
	
	$('.stepbar.active').addClass('un_active').removeClass('active');	
	$('.stepbar[data-id="'+step+'"]').removeClass('un_active').addClass('active');	

	$('.progress-circle.active').addClass('prog_hide').removeClass('active');	
	$('.progress-circle[data-id="'+step+'"]').removeClass('prog_hide').addClass('active');
	checkstep();
})

$('#previous').click(function(){
	var step = parseInt($('.steps.active').attr('data-id'))-1;
	$('.steps.active').addClass('displaynone').removeClass('active');
	$('.steps[data-id="'+step+'"]').removeClass('displaynone').addClass('active');
	
	$('.stepbar.active').addClass('un_active').removeClass('active');	
	$('.stepbar[data-id="'+step+'"]').removeClass('un_active').addClass('active');	
	
	$('.progress-circle.active').addClass('prog_hide').removeClass('active');	
	$('.progress-circle[data-id="'+step+'"]').removeClass('prog_hide').addClass('active');
	checkstep();
})

function registration(data){
	console.log(data);
	if(data.status=='0'){
		alert('Try Later');
	}else{
		if(data.result.usersdetailinsertid) $('#usersdetailid').val(data.result.usersdetailinsertid);
		if(data.result.userscompanyinsertid) $('#userscompany').val(data.result.userscompanyinsertid);
		if(data.result.usersaddressinsertids){
			$.each(data.result.usersaddressinsertids, function(i, v){
				console.log(v);
				$('input[name="address['+i+'][id]"]').val(v);
			});
		}
	}
}

function checkstep(){
	$('#next, #previous').removeClass('not_working');
	
	var step = $('.steps.active').attr('data-id');
		
	if(step=='1'){
		$('#previous').addClass('not_working');
	}else if(step=='4'){
		$('#next').addClass('not_working');
	}
}
</script>

