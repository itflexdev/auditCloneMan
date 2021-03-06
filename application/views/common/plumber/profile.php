<?php
	$usersdetailid 			= isset($result['usersdetailid']) ? $result['usersdetailid'] : '';
	$usersplumberid 		= isset($result['usersplumberid']) ? $result['usersplumberid'] : '';
	
	$email 					= isset($result['email']) ? $result['email'] : '';
	$type 					= isset($result['type']) ? $result['type'] : '';
	
	$titleid 				= isset($result['title']) ? $result['title'] : '';
	$dob 					= isset($result['dob']) && $result['dob']!='1970-01-01' ? date('d-m-Y', strtotime($result['dob'])) : '';
	$name 					= isset($result['name']) ? $result['name'] : '';
	$surname 				= isset($result['surname']) ? $result['surname'] : '';
	$genderid 				= isset($result['gender']) ? $result['gender'] : '';
	$racialid 				= isset($result['racial']) ? $result['racial'] : '';
	$nationality 			= isset($result['nationality']) ? $result['nationality'] : '';
	$idcard 				= isset($result['idcard']) ? $result['idcard'] : '';
	$othernationalityid 	= isset($result['othernationality']) ? $result['othernationality'] : '';

	$otheridcard 			= isset($result['otheridcard']) ? $result['otheridcard'] : '';
	$homelanguageid 		= isset($result['homelanguage']) ? $result['homelanguage'] : '';
	$disabilityid 			= isset($result['disability']) ? $result['disability'] : '';
	$citizenid 				= isset($result['citizen']) ? $result['citizen'] : '';
	$file1 					= isset($result['file1']) ? $result['file1'] : '';
	$file2 					= isset($result['file2']) ? $result['file2'] : '';
	$registrationcard 		= isset($result['registration_card']) ? $result['registration_card'] : '';
	$deliverycardid 		= isset($result['delivery_card']) ? $result['delivery_card'] : '';
	$homephone 				= isset($result['home_phone']) ? $result['home_phone'] : '';
	$mobilephone 			= isset($result['mobile_phone']) ? $result['mobile_phone'] : '';
	$workphone 				= isset($result['work_phone']) ? $result['work_phone'] : '';
	$companyname 			= isset($result['company_name']) ? $result['company_name'] : '';
	$regno 					= isset($result['reg_no']) ? $result['reg_no'] : '';
	$vatno 					= isset($result['vat_no']) ? $result['vat_no'] : '';
	$billingemail 			= isset($result['billing_email']) ? $result['billing_email'] : '';
	$billingcontact 		= isset($result['billing_contact']) ? $result['billing_contact'] : '';
	$employmentdetailsid	= isset($result['employment_details']) ? $result['employment_details'] : '';
	$companydetailsid		= isset($result['company_details']) ? $result['company_details'] : '';
	$designationid			= isset($result['designation']) ? $result['designation'] : '';
	
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
	
	$billingaddress 		= isset($result['billingaddress']) ? explode('@-@', $result['billingaddress']) : [];
	$addressid3 			= isset($billingaddress[0]) ? $billingaddress[0] : '';
	$address3				= isset($billingaddress[2]) ? $billingaddress[2] : '';
	$suburb3 				= isset($billingaddress[3]) ? $billingaddress[3] : '';
	$city3 					= isset($billingaddress[4]) ? $billingaddress[4] : '';
	$province3 				= isset($billingaddress[5]) ? $billingaddress[5] : '';
	$postalcode3 			= isset($billingaddress[6]) ? $billingaddress[6] : '';

	$lms_registration 		= isset($result['lms_registration']) ? $result['lms_registration'] : '';
	$lms_status 			= isset($result['lms_status']) ? $result['lms_status'] : '';

	$skills 				= isset($result['skills']) ? array_filter(explode('@-@', $result['skills'])) : [];
	
	$filepath				= base_url().'assets/uploads/plumber/'.$userid.'/';
	$pdfimg 				= base_url().'assets/images/pdf.png';
	$profileimg 			= base_url().'assets/images/profile.jpg';
	
	if($file2!=''){
		$explodefile2 	= explode('.', $file2);
		$extfile2 		= array_pop($explodefile2);
		$photoidimg 	= (in_array($extfile2, ['pdf', 'tiff'])) ? $pdfimg : $filepath.$file2;
		$photoidurl		= $filepath.$file2;
	}else{
		$photoidimg 	= $profileimg;
		$photoidurl		= 'javascript:void(0);';
	}
	
	$email2 				= isset($result['email2']) ? $result['email2'] : '';
	$mobilephone2 			= isset($result['mobile_phone2']) ? $result['mobile_phone2'] : '';
	
	$application_status 	= isset($result['application_status']) ? explode(',', $result['application_status']) : [];
	$approval_status 		= isset($result['approval_status']) ? $result['approval_status'] : '';
	$reject_reason 			= isset($result['reject_reason']) ? explode(',', $result['reject_reason']) : [];
	$reject_reason_other 	= isset($result['reject_reason_other']) ? $result['reject_reason_other'] : '';
	
	$registration_no 		= isset($result['registration_no']) ? $result['registration_no'] : '';
	$registration_date 		= isset($result['registration_date']) && $result['registration_date']!='1970-01-01' ? date('d-m-Y', strtotime($result['registration_date'])) : '';
	$renewal_date 			= isset($result['expirydate']) && $result['expirydate']!='1970-01-01' ? date('d-m-Y', strtotime($result['expirydate'])) : '';
	$plumberstatusid 		= isset($result['plumberstatus']) ? $result['plumberstatus'] : '';
	$designation2id 		= isset($result['designation']) ? $result['designation'] : '';
	$qualificationyear 		= isset($result['qualification_year']) ? $result['qualification_year'] : '';
	$specialisationsid 		= isset($result['specialisations']) ? array_filter(explode(',', $result['specialisations'])) : [];
	$cocpurchaselimit 		= isset($result['coc_purchase_limit']) && $result['coc_purchase_limit']!='0' ? $result['coc_purchase_limit'] : 0;
	$cocelectronic 			= isset($result['coc_electronic']) ? $result['coc_electronic'] : '';
	$message 				= isset($result['message']) ? $result['message'] : '';
	
	$roletype 				= isset($roletype) ? $roletype : '';
	$pagetype 				= isset($pagetype) ? $pagetype : '';
	 				
	if($roletype=='3' && $approval_status=='0'){
		$disabled1 			= 'disabled';
		$disabled1array 	= ['disabled' => 'disabled'];
		$disabled2 			= '';
		$disabled2array 	= [];
		
		$disablebtn			= '1';
	}elseif($roletype=='3' && $approval_status=='1'){
		$disabled1 			= '';
		$disabled1array 	= [];
		$disabled2 			= 'disabled';
		$disabled2array 	= ['disabled' => 'disabled'];
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
	
	$cardcolor = ['1' => 'learner_plumber', '2' => 'technical_assistant', '3' => 'technical_operator', '4' => 'licensed_plumber', '5' => 'qualified_plumber', '6' => 'master_plumber'];

	if($roletype!='1' && in_array($plumberstatusid, $this->config->item('psidconfig'))){
		$psidconfig = '1';
	}
	
	$plumbercoc = $defaultsettings['plumber_certificate'];
?>

<div class="row page-titles">
	<div class="col-md-5 align-self-center">
		<h4 class="text-themecolor"><?php echo $dynamicheading; ?></h4>
	</div>	
	<div class="col-md-7 align-self-center text-right">
		<div class="d-flex justify-content-end align-items-center">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">Home</li>
				<li class="breadcrumb-item active"><?php echo $dynamicheading; ?></li>
			</ol>
		</div>
	</div>
</div>

<?php echo $notification; ?>
<?php if($roletype=='1'){ echo isset($menu) ? $menu : ''; }?>

<?php if($roletype=='1' && ($approval_status=='0' || $approval_status=='2')){ ?>
	<form class="form1" method="post">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-body">
						
						
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Application Status</label>
									<?php
										foreach($applicationstatus as $key => $value){
											if($key%2==1){						
												echo "<div class='row'>";
											}
									?>
												<div class="col-md-6">
													<div class="custom-control custom-checkbox">
														<input type="checkbox" id="<?php echo $key.'-'.$value; ?>" class="custom-control-input applicationstatus" name="application_status[]" value="<?php echo $key; ?>" <?php echo (in_array($key, $application_status)) ? 'checked="checked"' : ''; ?>>
														<label class="custom-control-label" for="<?php echo $key.'-'.$value; ?>"><?php echo $value; ?></label>
													</div>
												</div>
									<?php
											if($key%2==0 || count($applicationstatus)==$key){						
												echo "</div>";
											}
										}
									?>
								</div>
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
												foreach ($rejectreason as $key => $value) {
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
										<input type="hidden" name="usersplumberid" value="<?php echo $usersplumberid; ?>">										
										<input type="hidden" name="usersdetailid" value="<?php echo $usersdetailid; ?>">
										<button type="submit" name="submit" value="approvalsubmit" class="btn btn-primary">Submit</button>
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

<form class="form2" method="post" action="">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					
					<h4 class="card-title"><?php echo $dynamictitle; ?></h4>
					
					<?php if(($roletype=='1' && $approval_status=='1') || $roletype=='3'){ ?>
						<div class="col-md-12 application_field_wrapper">
							<?php if($disabled1!=''){ ?>
								<div class="application_field_status">
									<p>Application Pending</p>
								</div>
							<?php } ?>
							<div class="row">
								<div class="col-md-12 add_full_width">
									<div class="form-group">
										<label>Registration Number</label>
										<input type="text" class="form-control" value="<?php echo $registration_no; ?>" disabled>								
										<input type="hidden" value="<?php echo $registration_no; ?>" name="registration_no" <?php echo $disabled1.$disabled2; ?>>								
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>First Registration Date</label>
										<input type="text" class="form-control" value="<?php echo $registration_date; ?>" disabled>						
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Next Renewal Date</label>
										<input type="text" class="form-control" value="<?php echo $renewal_date; ?>" disabled>			
										<input type="hidden" name="plumberpreviousexpirydate" value="<?php echo $renewal_date; ?>">			
									</div>
								</div>
							</div>					
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>Status</label>
										<?php
											echo form_dropdown('plumberstatus', $plumberstatus, $plumberstatusid, ['id' => 'plumberstatus', 'class'=>'form-control']+$disabled1array+$disabled2array);
										?>
										<input type="hidden" name="plumberpreviousstatus" value="<?php echo $plumberstatusid; ?>">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>PIRB Designation</label>
										<?php
											echo form_dropdown('designation2', $designation2, $designation2id, ['id' => 'designation2', 'class' => 'form-control']+$disabled1array+$disabled2array);
										?>
									</div>
								</div>
								<div class="col-md-6 offset-md-6 qualificationyear_wrapper displaynone">
									<div class="form-group">
										<label>Year in which Plumbing Qualification was obtained</label>
										<input type="text" class="form-control" name="qualification_year" value="<?php echo $qualificationyear; ?>" <?php echo $disabled1.$disabled2; ?>>
									</div>
								</div>
							</div>						
							<div class="form-group row specialisations_wrapper displaynone">
								<div class="col-md-12">
									<label>PIRB Specialisations:</label>
								</div>
								<?php
									foreach ($specialisations as $key => $value) {
								?>
										<div class='col-md-4'>
											<div class='custom-control custom-checkbox'>
												<input type='checkbox' class='custom-control-input' name='specialisations[]' id='<?php echo $key.'-'.$value; ?>' value='<?php echo $key; ?>' <?php echo (in_array($key, $specialisationsid)) ? 'checked="checked"' : ''; ?> <?php echo $disabled1.$disabled2; ?>>
												<label class='custom-control-label' for='<?php echo $key.'-'.$value; ?>'><?php echo $value; ?></label>
											</div>
										</div>
								<?php
									}
								?>
							</div>						
							<div class="form-group row">
								<div class="col-md-6">
									<label>Number of CoC's Able to purchase:</label>
									<input type="number" class="form-control coc_purchase_limit" name="coc_purchase_limit" value="<?php echo $cocpurchaselimit; ?>" <?php echo $disabled1.$disabled2; ?>>
								</div>
								<div class="col-md-6">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="custom-control-input" id="coc_electronic" name="coc_electronic" value="1" <?php echo ($cocelectronic=='1') ? 'checked="checked"' : ''; ?> <?php echo $disabled1; ?> <?php if($roletype=='1'){ echo 'disabled="disabled"';} ?>>
										<label class="custom-control-label" for="coc_electronic">Allow for Electronic COC's loging</label>
									</div>
								</div>
							</div>						
							<div class="form-group row">
								<div class="col-md-12">
									<label>Specific Message to Plumber</label>
									<textarea class="form-control" rows="5" name="message" <?php echo $disabled1.$disabled2; ?>><?php echo $message; ?></textarea>
								</div>
							</div>
						</div>
					<?php } ?>
					
					
					<?php 
						if($roletype=='1' || ($roletype=='3' && $approval_status=='1')){ 
							echo $card; 
						}
						if($roletype=='1'){ ?>
							<a href='javascript:void(0)' class="active_link_btn exportcard-class">Export Card</a><br>
							<div class="modalloader"></div>
						<?php }
					 ?>

					<div class="accordion add_top_value" id="plumberaccordion">
						<div class="card">
							<div class="card-header" id="PlumbersPersonalDetails">
								<h2 class="mb-0">
									<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#tab1" aria-expanded="true" aria-controls="tab1">
										<?php echo $dynamictabtitle; ?> Personal Details
									</button>
								</h2>
							</div>
							<div id="tab1" class="collapse show" aria-labelledby="PlumbersPersonalDetails" data-parent="#plumberaccordion">
								<div class="card-body">

									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Title *</label>
												<?php
													echo form_dropdown('title', $titlesign, $titleid, ['id'=>'title', 'class'=>'form-control']+$disabled1array);
												?>
											</div>
										</div>
										<div class="col-md-6">
											<label>Date of Birth *</label>
											<div class="form-group">
												<div class="input-group">
													<input type="text" class="form-control dob" name="dob" value="<?php echo $dob; ?>" <?php echo $disabled1; ?>>
													<div class="input-group-append">
														<span class="input-group-text"><i class="icon-calender"></i></span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Name *</label>
												<input type="text" class="form-control"  id="name" name="name" value="<?php echo $name; ?>" <?php echo $disabled1; ?>>
												</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Surname *</label>
												<input type="text" class="form-control" name="surname" id="surname" value="<?php echo $surname; ?>" <?php echo $disabled1; ?>>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Gender *</label>
												<?php
													echo form_dropdown('gender', $gender, $genderid, ['id'=>'gender', 'class'=>'form-control']+$disabled1array);
												?>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Racial Status *</label>
												<?php
													echo form_dropdown('racial', $racial, $racialid,['id'=>'racial', 'class'=>'form-control', 'data-select' => 'select2']+$disabled1array);
												?>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>South African National *</label>
												<?php
													echo form_dropdown('nationality', $yesno, $nationality,['id' => 'nationality', 'class'=>'form-control']+$disabled1array);
												?>
												</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>ID Number</label>
												<input type="text" class="form-control" name="idcard" id="idcard" value="<?php echo $idcard; ?>" <?php echo $disabled1; ?>>
												</div>
										</div>
									</div>
									<div class="row othernationalityidcardbox">
										<div class="col-md-6">
											<div class="form-group">
												<label>Other Nationality <span class="othernationality_required">*</span></label>
												<?php
													echo form_dropdown('othernationality', $othernationality, $othernationalityid, ['id' => 'othernationality', 'class'=>'form-control']+$disabled1array);
												?>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Alternative ID *</label>
												<input type="text" class="form-control" name="otheridcard" id="otheridcard" value="<?php echo $otheridcard; ?>" <?php echo $disabled1; ?>>
												</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Home Language *</label>
												<?php
													echo form_dropdown('homelanguage', $homelanguage, $homelanguageid, ['id' => 'homelanguage', 'class'=>'form-control', 'data-select' => 'select2']+$disabled1array);
												?>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Disability *</label>
												<?php
												echo form_dropdown('disability', $disability, $disabilityid,['id' => 'disability', 'class'=>'form-control']+$disabled1array);
												?>
												</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Citizen Residential Status *</label>
												<?php
												echo form_dropdown('citizen', $citizen, $citizenid,['id' => 'citizen', 'class'=>'form-control']+$disabled1array);
												?>
												</div>
										</div>
									</div>
									<div class="row add_top_value">
										<div class="col-md-6">
											<h4 class="card-title">Photo ID *</h4>
											<div class="form-group">
												<div>
													<a href="<?php echo $photoidurl; ?>" target="_blank"><img src="<?php echo $photoidimg; ?>" class="photo_image" width="100"></a>
												</div>
												<input type="file" class="photo_file" <?php echo $disabled1; ?>>
												<input type="hidden" name="image2" class="photo" value="<?php echo $file2; ?>">
												<p>(Image/File Size Smaller than 5mb)</p>
											</div>
										</div>
										<?php if($roletype=='3'){ ?>
											<div class="col-md-6">
												<ul class="file_up_points">
													<li>Photos must be no more than 6 months old</li>
													<li>Photos must be high quality</li>
													<li>Photos must be in colour</li>
													<li>Photos must have clear preferably white background</li>
													<li>Photos must be in sharp focus and clear</li>
													<li>Photo must be only of your head and shoulders</li>
													<li>You must be looking directly at the camera</li>
													<li>No sunglasses or hats</li>
													<li>File name is your NAME and SURNAME.</li>
												</ul>
												<a href="javascript:void(0);" data-toggle="modal" data-target="#photomodal">See Examples</a>
											</div>
										<?php } ?>
									</div>


									<?php
									 if($roletype=='3' || ($roletype=='1' && $registration_no =='')){ ?>
										<div class="row add_top_value">
											<div class="row">
												<h4 class="card-title">Register on IOPSA's LMS system</h4>
												<p>Note: The E-Learning management system is a platform and marketplace that enables anyone to create and share relevant educational courses, be it for free or paid. Here you will find the PIRB???s mandatory induction programme, Continuous Professional Development (CPD) courses and so much more.</p>
												<div class="col-md-3">
													<div class="custom-control custom-radio">
														<input type="radio" name="lmsregistration" id="registrationyes" class="custom-control-input" value="1" <?php if($lms_registration =='1'){ echo 'checked="checked"'; } if ($pagetype != 'applications') { ?> disabled <?php } if ($lms_status == '1') { echo "disabled"; } ?>>
														<label class="custom-control-label" for="registrationyes">Yes</label>
													</div>
												</div>
												<div class="col-md-3">
													<div class="custom-control custom-radio">
														<input type="radio" name="lmsregistration" id="registrationno" class="custom-control-input" value="2" <?php if($lms_registration =='2'){ echo 'checked="checked"'; } if ($pagetype != 'applications') { ?> disabled <?php } if ($lms_status == '1') { echo "disabled"; } ?>>
														<label class="custom-control-label" for="registrationno">No</label>
													</div>
												</div>
												<?php if ($lms_status == '1') { ?>
													<div class="col-md-6">
														<label>An account with the email <?php echo $email; ?> already exists on iopsatraining.co.za.</label>
													</div>
												<?php } ?>
											</div>
										</div>
									<?php }?>

									<h4 class="card-title">Registration Card</h4>
									<p>Due to the high number of card returns and cost incurred, the registration fees do not include a registration card. Registration cards are available but must be requested separately.  If the registration card option is selected you will be billed accordingly.</p>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Registration Card Required *</label>
												<?php
													echo form_dropdown('registration_card', $yesno, $registrationcard,['id' => 'registration_card', 'class'=>'form-control', 'id' => 'registration_card']+$disabled1array);
												?>
												</div>
										</div>
										<div class="col-md-6 deliverycardbox">
											<div class="form-group">
												<label>Method of Delivery of Card <span class="delivery_card_required">*</span></label>
												<?php
													echo form_dropdown('delivery_card', $deliverycard, $deliverycardid,['id' => 'delivery_card', 'class'=>'form-control']+$disabled1array);
												?>
											</div>
										</div>
									</div>
																
								</div>
							</div>
						</div>	

						<div class="card">
							<div class="card-header" id="PlumbersContactDetails">
								<h2 class="mb-0">
									<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#tab2" aria-expanded="true" aria-controls="tab2">
										<?php echo $dynamictabtitle; ?> Contact Details
									</button>
								</h2>
							</div>
							<div id="tab2" class="collapse" aria-labelledby="PlumbersContactDetails" data-parent="#plumberaccordion">
								<div class="card-body">

									<div class="row">
										<div class="col-md-6">
											<h4 class="card-title">Physical Address</h4>
											<p class="tagline">Note: All delivery services will be sent to this address.</p>
											<div class="form-group">
												<label>Physical Address *</label>
												<input type="hidden" class="form-control" name="address[1][id]" value="<?php echo $addressid1; ?>">
												<input type="hidden" class="form-control" name="address[1][type]" value="1">
												<input type="text" class="form-control" name="address[1][address]"  value="<?php echo $address1; ?>" <?php echo $disabled1; ?>>
											</div>
										</div>
										<div class="col-md-6">
											<h4 class="card-title">Postal Address</h4>
											<p class="tagline">Note: All postal services will be sent to this address.</p>
											<div class="form-group">
												<label>Postal Address *</label>
												<input type="hidden" class="form-control" name="address[2][id]" value="<?php echo $addressid2; ?>">
												<input type="hidden" class="form-control" name="address[2][type]" value="2">
												<input type="text" class="form-control" name="address[2][address]" value="<?php echo $address2; ?>" <?php echo $disabled1; ?>>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">								
											<div class="form-group"> 
												<label>Province *</label>
												<?php 
													echo form_dropdown('address[1][province]', $province, $province1, ['id' => 'province1', 'class' => 'form-control']+$disabled1array); 
												?>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Province *</label>
												<?php
													echo form_dropdown('address[2][province]', $province, $province2, ['id' => 'province2', 'class'=>'form-control']+$disabled1array);
												?>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>City *</label>
												<?php 
													echo form_dropdown('address[1][city]', [], $city1, ['id' => 'city1', 'class' => 'form-control']+$disabled1array); 
												?>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>City *</label>
												<?php 
													echo form_dropdown('address[2][city]', [], $city2, ['id' => 'city2', 'class' => 'form-control']+$disabled1array); 
												?>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Suburb *</label>
												<?php
													echo form_dropdown('address[1][suburb]', [], $suburb1, ['id' => 'suburb1', 'class'=>'form-control']+$disabled1array);
												?>
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<label>Suburb *</label>
												<?php
													echo form_dropdown('address[2][suburb]', [], $suburb2, ['id' => 'suburb2', 'class'=>'form-control']+$disabled1array);
												?>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6 offset-6">
											<div class="form-group">
												<label>Postal Code *</label>
												<input type="text" class="form-control" name="address[2][postal_code]" value="<?php echo $postalcode2; ?>" <?php echo $disabled1; ?>>
											</div>
										</div>
									</div>
									<h4 class="card-title">Contact Details</h4>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Home Phone:</label>
												<input type="text" class="form-control" name="home_phone" id="home_phone" value="<?php echo $homephone; ?>" <?php echo $disabled1; ?>>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Mobile Phone *</label>
												<input type="text" class="form-control" name="mobile_phone" id="mobile_phone" value="<?php echo $mobilephone; ?>" <?php echo $disabled1; ?>>
												<p>Note: All SMS and OTP notifications will be sent to this mobile number above.</p>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Work Phone:</label>
												<input type="text" class="form-control" name="work_phone" id="work_phone" value="<?php echo $workphone; ?>" <?php echo $disabled1; ?>>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Secondary Mobile Phone</label>
												<input type="text" class="form-control" name="mobile_phone2" id="mobile_phone2" value="<?php echo $mobilephone2; ?>" <?php echo $disabled1; ?>>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Email Address *</label>
												<input type="text" class="form-control" id="email" name="email" value="<?php echo $email; ?>" <?php echo $disabled1.$disabled2; ?>>
												<p>Note: This email will be used as your user profile name and all emails notifications will be sent to it.</p>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Secondary Email Address</label>
												<input type="text" class="form-control" name="email2" value="<?php echo $email2; ?>" <?php echo $disabled1; ?>>
											</div>
										</div>
									</div>
									
								</div>
							</div>
						</div>


						<div class="card">
							<div class="card-header" id="PlumbersBillingDetails">
								<h2 class="mb-0">
									<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#tab3" aria-expanded="true" aria-controls="tab3">
										<?php echo $dynamictabtitle; ?> Billing Details
									</button>
								</h2>
							</div>
							<div id="tab3" class="collapse" aria-labelledby="PlumbersBillingDetails" data-parent="#plumberaccordion">
								<div class="card-body">

									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label>Billing Name *</label>
												<input type="text" class="form-control" name="company_name" value="<?php echo $companyname; ?>" <?php echo $disabled1; ?>>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label>Company Reg Number</label>
												<input type="text" class="form-control" name="reg_no" value="<?php echo $regno; ?>" <?php echo $disabled1; ?>>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label>Company VAT Number</label>
												<input type="text" class="form-control" name="vat_no" value="<?php echo $vatno; ?>" <?php echo $disabled1; ?>>
											</div>
										</div>                            
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Billing Email *</label>
												<input type="text" class="form-control percentageslide" name="billing_email" value="<?php echo $billingemail; ?>" <?php echo $disabled1; ?>>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Billing Contact *</label>
												<input type="text" class="form-control percentageslide" id="billing_contact" name="billing_contact" value="<?php echo $billingcontact; ?>" <?php echo $disabled1; ?>>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label>Billing Address *</label>
												<input type="hidden" class="form-control" name="address[3][id]" value="<?php echo $addressid3; ?>" <?php echo $disabled1; ?>>
												<input type="hidden" class="form-control" name="address[3][type]" value="3">
												<input type="text" class="form-control" name="address[3][address]" value="<?php echo $address3; ?>" <?php echo $disabled1; ?>>
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												<label>Province *</label>
												<?php
													echo form_dropdown('address[3][province]', $province, $province3, ['id' => 'province3', 'class'=>'form-control']+$disabled1array);
												?>
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												<label>City *</label>
												<?php 
													echo form_dropdown('address[3][city]', [], $city3, ['id' => 'city3', 'class' => 'form-control']+$disabled1array); 
												?>
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												<label>Suburb *</label>
												<?php 
													echo form_dropdown('address[3][suburb]', [], $suburb3, ['id' => 'suburb3', 'class'=>'form-control']+$disabled1array);
												?>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>Postal Code *</label>
												<input type="text" class="form-control" name="address[3][postal_code]" value="<?php echo $postalcode3; ?>" <?php echo $disabled1; ?>>
											</div>
										</div>
									</div>

								</div>
							</div>
						</div>


						<div class="card">
							<div class="card-header" id="PlumbersEmloymentDetails">
								<h2 class="mb-0">
									<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#tab4" aria-expanded="true" aria-controls="tab4">
										<?php echo $dynamictabtitle; ?> Emloyment Details
									</button>
								</h2>
							</div>
							<div id="tab4" class="collapse" aria-labelledby="PlumbersEmloymentDetails" data-parent="#plumberaccordion">
								<div class="card-body">
									
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label>Your Employment Status</label>
												<?php
													echo form_dropdown('employment_details', $employmentdetail, $employmentdetailsid,['id' => 'employment_details', 'class'=>'form-control', 'id' => 'employment_details']+$disabled1array);
												?>
											</div>
										</div>
										<div class="col-md-12 companydetailsbox">
											<div class="form-group">
												<label>Company *</label>
												<?php
													echo form_dropdown('company_details', $company, $companydetailsid,['id' => 'company_details', 'class'=>'form-control']+$disabled1array);
												?>
											</div>
											<?php if($roletype=='3'){ ?>
												<p>If the Company does not appear on this list please ask the company to register with the PIRB. Once they have been approved and registered, return to the list and select the company</p>
												<!--<a href="javascript:void(0)">Register Company with the PIRB</a>-->
											<?php } ?>
										</div>
									</div>
									
								</div>
							</div>
						</div>


						<div class="card qualification_tab_wrapper displaynone">
							<div class="card-header" id="PlumbersQualificationCertificateDetails">
								<h2 class="mb-0">
									<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#tab5" aria-expanded="true" aria-controls="tab5">
										<?php echo $dynamictabtitle; ?> Qualification/Certificate Details
									</button>
								</h2>
							</div>
							<div id="tab5" class="collapse" aria-labelledby="PlumbersQualificationCertificateDetails" data-parent="#plumberaccordion">
								<div class="card-body">
									
									<table class="table table-bordered skilltable">
										<tr>
											<td>Date of Qualification/Skill Obtained</td>
											<td>Certificate Number</td>											
											<td>Qualification Type</td>
											<td>Qualification Route</td>
											<td>Training Provider</td>
											<td>Attachments</td>
											<?php if(!isset($psidconfig) && $disabled1==''){ ?>
												<td>Action</td>
											<?php } ?>
										</tr>
										<tr class="skillnotfound"><td colspan="7">No Record Found</td></tr>
									</table>
									<?php if(!isset($psidconfig) && $roletype!='3'){ ?>
									<div>
										<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#skillmodal">Add Cert/Skill</button>
									</div>
									<?php } ?>
									<input type="hidden" class="attachmenthidden" name="attachmenthidden"> 

								</div>
							</div>
						</div>									
					</div>
					
					<?php if(!isset($psidconfig)){ ?>
						<div class="col-md-12 text-right">
							<input type="hidden" name="usersdetailid" value="<?php echo $usersdetailid; ?>">
							<input type="hidden" name="usersplumberid" value="<?php echo $usersplumberid; ?>">
							<input type="hidden" name="usertype" value="<?php echo $roletype; ?>">
							<?php if(!isset($disablebtn)){ ?>
								<button type="button" id="plumbersubmit" class="btn btn-primary">Submit</button>
							<?php } ?>
						</div>
					<?php } ?>
					
				</div>
			</div>
		</div>
	</div>
</form>

<div id="skillmodal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form class="skillform">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Add a Qualification/Skill</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Date of Qualification/Skill Obtained</label>
								<div class="input-group">
									<input type="text" class="form-control skill_date" name="skill_date" data-date='datepicker'>
									<div class="input-group-append">
										<span class="input-group-text"><i class="icon-calender"></i></span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Certificate Number</label>
								<input type="text" class="form-control skill_certificate" name="skill_certificate">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Qualification Type</label>
								<?php
								echo form_dropdown('skill_qualification_type', $qualificationtype, '', ['id' => 'skill_qualification_type', 'class'=>'form-control skill_qualification_type']);
								?>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Qualification/Skills Route</label>
								<?php
								echo form_dropdown('skill_route', $qualificationroute, '', ['id' => 'skill_route', 'class'=>'form-control skill_route']);
								?>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Training Provider</label>
								<input name="skill_training" type="text" class="form-control skill_training">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<div>
									<img src="<?php echo base_url().'assets/images/profile.jpg'; ?>" class="skill_attachment_image" width="100">
								</div>
								<input type="file" class="skill_attachment_file">
								<input type="hidden" name="skill_attachment" class="skill_attachment">
								<p>(Image/File Size Smaller than 5mb)</p>
							</div>						
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="skill_id" class="skill_id">
					<input type="hidden" name="user_id" value="<?php echo $userid; ?>">
					<button type="button" class="btn btn-success skillsubmit">Submit</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="photomodal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12 text-center">
						<img src="<?php echo base_url().'assets/images/photoid.png'; ?>">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

var userid				= '<?php echo $userid; ?>';
var filepath 			= '<?php echo $filepath; ?>';
var pdfimg				= '<?php echo $pdfimg; ?>';
var type				= '<?php echo $type; ?>';
var roletype			= '<?php echo $roletype; ?>';
var qualificationtype	= $.parseJSON('<?php echo json_encode($qualificationtype); ?>');

$(function(){
	select2('#plumberstatus, #designation2, #title, #gender, #racial, #nationality, #othernationality, #homelanguage, #disability, #citizen, #registration_card, #delivery_card, #province1, #city1, #suburb1, #province2, #city2, #suburb2, #province3, #city3, #suburb3, #employment_details, #company_details, #skill_qualification_type, #skill_route');
	datepicker('.dob, .skill_date');
	inputmask('#home_phone, #work_phone, #mobile_phone, #mobile_phone2, #billing_contact', 1);
	fileupload([".document_file", "./assets/uploads/plumber/"+userid+"/", ['jpg','gif','jpeg','png','pdf','tiff']], ['.document', '.document_image', filepath, pdfimg]);
	fileupload([".photo_file", "./assets/uploads/plumber/"+userid+"/", ['jpg','gif','jpeg','png','pdf','tiff']], ['.photo', '.photo_image', filepath, pdfimg]);
	fileupload([".skill_attachment_file", "./assets/uploads/plumber/"+userid+"/", ['jpg','gif','jpeg','png','pdf','tiff']], ['.skill_attachment', '.skill_attachment_image', filepath, pdfimg]);
	citysuburb(['#province1','#city1', '#suburb1'], ['<?php echo addslashes($city1); ?>', '<?php echo addslashes($suburb1); ?>']);
	citysuburb(['#province2','#city2', '#suburb2'], ['<?php echo addslashes($city2); ?>', '<?php echo addslashes($suburb2); ?>']);
	citysuburb(['#province3','#city3', '#suburb3'], ['<?php echo addslashes($city3); ?>', '<?php echo addslashes($suburb3); ?>']);
	
	var nationality = $('#nationality').val();
	othernationalityidcardbox(nationality, 1);
	
	var registrationcard = $('#registration_card').val();
	deliverycardbox(registrationcard);
	
	var employmentdetails = $('#employment_details').val();
	companydetailsbox(employmentdetails);
	
	var designationid = '<?php echo $designationid; ?>';
	if(designationid!='') $('input[name="designation"][value="'+designationid+'"]').prop('checked', true);
	designationattachment(designationid);
	
	var designation2id = '<?php echo $designation2id; ?>';
	designationcondition(designation2id);
	
	var approvalstatus = '<?php echo $approval_status; ?>';
	if(approvalstatus!='') $('.approvalstatus[value="'+approvalstatus+'"]').data('approvalStatusValue', true);
	rejectwrapper(approvalstatus);
	
	applicationstatus();
	rejectother();
	
	var skill = $.parseJSON('<?php echo addslashes(json_encode($skills)); ?>');
	if(skill.length > 0){
		$(skill).each(function(i, v){
			var skillsplit 	= v.split('@@@');
			var skilldata 	= {status : 1, result : { id: skillsplit[0], date: skillsplit[2], certificate: skillsplit[3], qualification: skillsplit[4], skillname: skillsplit[8], training: skillsplit[6], attachment: skillsplit[7] }}
			skills(skilldata);
		})
	}

	$('.exportcard-class').click(function(){
			var id = '<?php echo $userid; ?>';
			var form_data = new FormData();
			form_data.append("id", id);
			$.ajax({
				data: form_data,
		        type: 'POST',
		        url: '<?php echo base_url().'admin/plumber/index/exportcard'; ?>',
		        contentType: false,  
	            cache: false,  
	            processData:false,
	             beforeSend:function(){
			          $('.modalloader').html('<img src="<?php echo base_url().'assets/images/ajax-loader.gif'; ?>" height="50" width="50"/>');
			        },
		            success:function(data)
			        {
			        	$('.modalloader').hide();
			        	sweetalertautoclose('Card Exported Sucessfully.');

			        }
			});
		});
	
	validation(
		'.form2',
		{
			title : {
				required	: true,
			},
			dob : {
				required	: true,
			},
			name : {
				required	: true,
				lettersonly	: true
			},
			surname : {
				required	: true,
				lettersonly	: true
			},
			gender : {
				required	: true,
			},
			racial : {
				required	: true,
			},
			nationality : {
				required	: true,
			},
			idcard : {
				maxlength	: 	13,
				minlength	: 	13,
				remote		: 	{
									url		: 	"<?php echo base_url().'ajax/index/ajaxplumberidentitynumberprofile'; ?>",
									type	: 	"post",
									async	: 	false,
									data	: 	{
													idcard: function() { return $("#idcard").val();	},
													id : userid,
													roletype : roletype
												}
								}
			},
			othernationality : {
				required:  	function() {
								return $('#nationality').val() == "2";
							}
			},
			otheridcard 	: {
				required	:  	function() {
									return $('#nationality').val() == "2";
								},
				remote		: 	{
									url		: 	"<?php echo base_url().'ajax/index/ajaxplumberidentitynumberprofile'; ?>",
									type	: 	"post",
									async	: 	false,
									data	: 	{
													otheridcard: function() { return $("#otheridcard").val(); },
													id : userid,
													roletype : roletype
												}
								}
			},
			homelanguage : {
				required	: true,
			},
			disability : {
				required	: false,
			},
			citizen : {
				required	: true,
			},
			image1 : {
				required	: true,
			},
			image2 : {
				required	: true,
			},
			registration_card : {
				required	: true,
			},
			delivery_card : {
				required:  	function() {
								return $('#registration_card').val() == "1";
							}
			},
			'address[1][address]' : {
				required	: true,
			},
			'address[2][address]' : {
				required	: true,
			},
			'address[1][suburb]' : {
				required	: true,
			},
			'address[2][suburb]' : {
				required	: true,
			},
			'address[1][city]' : {
				required	: true,
			},
			'address[2][city]' : {
				required	: true,
			},
			'address[1][province]' : {
				required	: true,
			},
			'address[2][province]' : {
				required	: true,
			},
			'address[2][postal_code]' : {
				required	: true,
				number 	: true,
			},
			mobile_phone : {
				required	: true
			},
			email : {
				required	: true,
				email		: true,
				remote		: 	{
									url		: 	"<?php echo base_url().'authentication/login/emailvalidation'; ?>",
									type	: 	"post",
									async	: 	false,
									data	: 	{
													id : userid,
													type : type
												}
								}
			},
			company_name : {
				required	: true,
			},
			billing_email : {
				required	: true,
				email		: true
			},
			billing_contact : {
				required	: true
			},
			'address[3][address]' : {
				required	: true,
			},
			'address[3][suburb]' : {
				required	: true,
			},
			'address[3][city]' : {
				required	: true,
			},
			'address[3][province]' : {
				required	: true,
			},
			'address[3][postal_code]' : {
				required	: true,
				number 	: true
			},
			company_details : {
				required:  	function() {
								return $("#employment_details").val() == "1";
							}
			},
			//attachmenthidden 	: {
				//required:  	function() {
								//return roletype!="3" && ($("#designation2").val() == "4" || $("#designation2").val() == "5" || $("#designation2").val() == "6");
							//},
				//remote	: 	{
								//url		: 	"<?php echo base_url().'ajax/index/ajaxqualificationvalidation'; ?>",
								//type	: 	"post",
								//async	: 	false,
								//data	: 	{
												//id 			: 	userid,
												//roletype 	: 	roletype,
												//designation	: 	function() {
																	//return $("#designation2").val();
																//}
											//}
							//}
			//},
			qualification_year 	: {
				required:  	function() {
								return ($("#designation2").val() == "4" || $("#designation2").val() == "5" || $("#designation2").val() == "6");
							},				
				number		: true,
				maxlength	: 4,
				minlength	: 4
			}
			
		},
		{
			title : {
				required	: "Tittle field is required.",
			},
			name 	: {
				required	: "Name field is required."
			},
			dob : {
				required	: "DOB field is required.",
			},
			surname : {
				required	: "Surname field is required.",
			},
			gender : {
				required	: "Gender field is required.",
			},
			racial : {
				required	: "Racial field is required.",
			},
			nationality : {
				required	: "nationality field is required.",
			},
			idcard : {
				maxlength	: "Please Enter 13 Numbers Only.",
				minlength	: "Please Enter 13 Numbers Only.",
				remote		: "ID Number already exists."
			},
			othernationality : {
				required	: "Other nationality field is required.",
			},
			otheridcard : {
				required	: "Alternative ID Card field is required.",
				remote		: "Alternative ID already exists."
			},
			homelanguage : {
				required	: "Home Language field is required.",
			},
			disability : {
				required	: "Disability field is required.",
			},
			citizen : {
				required	: "Citizen field is required.",
			},
			image1 : {
				required	: "Identity Document field is required.",
			},
			image2 : {
				required	: "Photo ID field is required.",
			},
			registration_card : {
				required	: "Registration Card field is required.",
			},
			delivery_card : {
				required	: "Delivery_card field is required.",
			},
			'address[1][address]' : {
				required	: "Address  field is required.",
			},
			'address[2][address]' : {
				required	: "Address  field is required.",
			},
			'address[1][suburb]' : {
				required	: "Suburb  field is required.",
			},
			'address[2][suburb]' : {
				required	: "Suburb  field is required.",
			},
			'address[1][city]' : {
				required	: "City  field is required.",
			},
			'address[2][city]' : {
				required	: "City  field is required.",
			},
			'address[1][province]' : {
				required	: "Province  field is required.",
			},
			'address[2][province]' : {
				required	: "Province  field is required.",
			},
			'address[2][postal_code]' : {
				required	: "Postal code field is required.",
				number 	: "Numbers Only.",
			},
			mobile_phone : {
				required	: "Mobile phone  field is required."
			},
			email : {
				required	: "Email  field is required.",
				email       : "Please Enter Valid Mail",
				remote		: "Email already exists."
			},
			company_name 	: {
				required	: "Billing name field is required.",
			},
			billing_email : {
				required	: "Billing email field is required.",
				email		: "Enter valid email address"
			},
			billing_contact : {
				required	: "Billing contact field is required.",
			},
			'address[3][address]' 	: {
				required	: "Address field is required.",
			},
			'address[3][suburb]' 	: {
				required	: "Suburb field is required.",
			},
			'address[3][city]' 	: {
				required	: "City field is required.",
			},
			'address[3][province]' 	: {
				required	: "province field is required.",
			},
			'address[3][postal_code]' 	: {
				required	: "Postal Code field is required.",
				number 		: "Numbers Only",
			},
			company_details 	: {
				required	: "Please select company.",
			},
			attachmenthidden 	: {
				required	: "No qualifications found",
				remote		: "Add the relevant qualification to the table."
			},
			qualification_year 	: {
				required	: "Please select qualification year.",
				number 		: "Numbers Only",
				maxlength	: "Please enter 4 number.",
				minlength	: "Please enter 4 number"
			}
		},
		{
			ignore : []
		}
	);

	validation(
		'.skillform',
		{
			skill_date : {
				required	: true,
			},
			skill_certificate : {
				required	: true,
			},
			skill_qualification_type 	: {
				required	: true,
			},
			skill_route : {
				required	: true,
			},
			skill_training : {
				required	: true,
			},
			skill_attachment : {
				required	: true,
			}
		},
		{
			skill_date 	: {
				required	: "Please Enter Skill Date.",
			},
			skill_certificate 	: {
				required	: "Please Enter Skill certificate Number.",
			},
			skill_qualification_type 	: {
				required	: "Please Select Qualification Type.",
			},
			skill_route : {
				required	: "Please Select Employment Status.",
			},
			skill_training : {
				required	: "Please Enter Training Provider.",
			},
			skill_attachment : {
				required	: "Choose File Please",
			}
		}
	);
})

$('#nationality').change(function(){
	othernationalityidcardbox($(this).val());
})

function othernationalityidcardbox(value, citizen=''){
	if(value=='2'){
		$('#idcard').val('').prop('readonly', true);
		$('#otheridcard').prop('readonly', false);
		$('.othernationalityidcardbox').show();
	}else{
		$('#idcard').prop('readonly', false);
		$('#otheridcard').val('').prop('readonly', true);
		$('.othernationalityidcardbox').hide();
	}
	
	$('#citizen option[value="4"]').removeAttr('disabled');
		
	if(value=='1'){
		$('#citizen').val('4').trigger('change');;
	}else if(value=='2'){
		$('#citizen option[value="4"]').attr('disabled', 'disabled');
		$('#citizen').val('2').trigger('change');
	}
	
	if(citizen==1) $('#citizen').val('<?php echo $citizenid; ?>').trigger('change');
}

$('#registration_card').change(function(){
	deliverycardbox($(this).val());
})

function deliverycardbox(value){
	if(value=='1'){
		$('.deliverycardbox').show();
	}else{
		$('.deliverycardbox').hide();
	}
}

$('#employment_details').change(function(){
	companydetailsbox($(this).val());
})

function companydetailsbox(value){
	if(value=='1'){
		$('.companydetailsbox').show();
	}else{
		$('.companydetailsbox').hide();
	}
}

$('.designation').click(function(){
	designationattachment($(this).val());
})

function designationattachment(value){
	if(value=='4'){
		$('.designationattachment').removeClass('displaynone');
	}else{
		$('.designationattachment').addClass('displaynone');
	}	
}

$('#skillmodal').on('hidden.bs.modal', function(){
    skillsclear();
})

$('.skillsubmit').click(function(){
	if($('.skillform').valid())
	{
		var data = $('.skillform').serialize();
		ajax('<?php echo base_url()."ajax/index/ajaxskillaction"; ?>', data, skills);
	}
})

function skills(data){
	if(data.status==1){		
		var result 		= 	data.result; 
		
		$(document).find('.skillappend[data-id="'+result.id+'"]').remove();
		
		if(result.attachment!=undefined && result.attachment!=''){
			var ext 		= result.attachment.split('.').pop().toLowerCase();
			if(ext=='jpg' || ext=='jpeg' || ext=='png'){
				var attachment = '<a href="'+filepath+(result.attachment)+'" target="_blank"><img src="'+filepath+(result.attachment)+'" width="50"></a>';
			}else if(ext=='pdf'){
				var attachment = '<a href="'+filepath+(result.attachment)+'" target="_blank"><?php echo base_url()."assets/images/pdf.png"?></a>';
			}
		}else{
			var attachment = '';
		} 
		
		var psidconfig 	=	 '<?php echo !isset($psidconfig) ? "1" : ''; ?>';
		var disabled1 	=	 '<?php echo $disabled1; ?>';
		
		if(psidconfig=='1' && disabled1==''){
			var action 		= 	'<td>\
									<a href="javascript:void(0);" class="skilledit" data-id="'+result.id+'"><i class="fa fa-pencil-alt"></i></a>\
									<a href="javascript:void(0);" class="skillremove" data-id="'+result.id+'"><i class="fa fa-trash"></i></a>\
								</td>';
		}else{
			var action 	= 	'';
		}
		
		var appenddata 	= 	'\
								<tr class="skillappend" data-id="'+result.id+'">\
									<td>'+formatdate(result.date,1)+'</td>\
									<td>'+((result.certificate!=undefined && result.certificate!='') ? result.certificate :  "")+'</td>\
									<td>'+((result.qualification!=undefined && result.qualification!='' && qualificationtype[result.qualification]!=undefined) ? qualificationtype[result.qualification] :  "")+'</td>\
									<td>'+((result.skillname!=undefined && result.skillname!='') ? result.skillname :  "")+'</td>\
									<td>'+((result.training!=undefined && result.training!='') ? result.training :  "")+'</td>\
									<td>'+((attachment!=undefined && attachment!='') ? attachment :  "")+'</td>\
									'+action+'\
								</tr>\
							';
					
		$('.skilltable').append(appenddata);
	}
	
	$('#skillmodal').modal('hide');
	
	skillsextras();
}

$(document).on('click', '.skilledit', function(){
	ajax('<?php echo base_url()."ajax/index/ajaxskillaction"; ?>', {'skillid' : $(this).attr('data-id'), 'action' : 'edit'}, skillsedit);
})

function skillsedit(data){
	if(data.status==1){
		var result 	= 	data.result;
		
		$('.skill_date').val(formatdate(result.date, 1));
		$('.skill_certificate').val(((result.certificate!=undefined && result.certificate!='') ? result.certificate :  ""));
		$('.skill_qualification_type').val(((result.qualification!=undefined && result.qualification!='') ? result.qualification :  "")).trigger('change');
		$('.skill_route').val(((result.skills!=undefined && result.skills!='') ? result.skills :  "")).trigger('change');
		$('.skill_training').val(((result.training!=undefined && result.training!='') ? result.training :  ""));
		$('.skill_attachment').val(((result.attachment!=undefined && result.attachment!='') ? result.attachment :  ""));
		
		if(result.attachment!=undefined && result.attachment!=''){
			var ext 		= result.attachment.split('.').pop().toLowerCase();
			if(ext=='jpg' || ext=='jpeg' || ext=='png'){
				$('.skill_attachment_image').attr('src', filepath+(result.attachment));	
			}else if(ext=='pdf'){
				$('.skill_attachment_image').attr('src', '<?php echo base_url()."assets/images/pdf.png"?>');	
			}
		} 
		
		$('.skill_id').val(result.id);
		$('#skillmodal').modal('show');
	} 
}


$(document).on('click', '.skillremove', function(){
	ajax('<?php echo base_url()."ajax/index/ajaxskillaction"; ?>', {'skillid' : $(this).attr('data-id'), 'action' : 'delete'}, skillsremove);
	$(this).parent().parent().remove();
	skillsextras();
	
	$('.attachmenthidden').valid();
})

function skillsremove(data){}

function skillsclear(){
	$('.skill_date, .skill_certificate, .skill_training, .skill_attachment').val('');
	$('.skill_attachment_image').attr("src", "<?php echo base_url().'assets/images/profile.jpg'; ?>");
	$('.skillform').find("p.error_class_1").remove();
	$('.skillform').find(".error_class_1").removeClass('error_class_1');
	$('.skill_route').val('').trigger('change');
	$('.skill_qualification_type').val('').trigger('change');
	
	$('.attachmenthidden').valid();
}

function skillsextras(){
	if($(document).find('.skillappend').length){
		$('.skillnotfound').hide();
		$('.attachmenthidden').val('1');
	}else{
		$('.skillnotfound').show();
		$('.attachmenthidden').val('');
	}
	
	$(".attachmenthidden").removeData("previousValue");
}

$('#plumbersubmit').click(function(){
	if($('.form2').valid() && $('.form2').valid()){
		$('.form2').submit();
	}else{
		$('.error_class_1').parents('.collapse').addClass('show');
	}
})

$('.applicationstatus').click(function(){
	applicationstatus();
})

function applicationstatus(){
	var approveenable = 0;
	$('.applicationstatus').each(function(){
		if(!$(this).is(':checked')){
			approveenable = 1;
		}
	})
	
	if(approveenable==1){
		$('.approvalstatus[value="1"]').attr('disabled', 'disabled');
	}else if(approveenable==0){
		$('.approvalstatus[value="1"]').removeAttr('disabled');
	}	
}

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
		if($(this).is(':checked') && $(this).val()=='4'){
			flag = 1;
		}
	})
	
	if(flag==1){
		$('.reject_reason_other_wrapper').removeClass('displaynone');
	}else{
		$('.reject_reason_other_wrapper').addClass('displaynone');
	}
}

$('#designation2').change(function(){	
	var value = $(this).val();
	designationcondition(value);
	
	if(value=='4' || value=='6'){
		if($('.coc_purchase_limit').val()=='0'){
			$('.coc_purchase_limit').val('<?php echo $plumbercoc; ?>');
		}
	}else{
		$('.coc_purchase_limit').val('0');
	}	
})

function designationcondition(value){
	if(value=='4' || value=='5' || value=='6'){
		$('.qualificationyear_wrapper').removeClass('displaynone');
		$('.specialisations_wrapper').removeClass('displaynone');
		$('.qualification_tab_wrapper').removeClass('displaynone');
	}else{
		$('.qualificationyear_wrapper').addClass('displaynone');
		$('.specialisations_wrapper').addClass('displaynone');
		$('.qualification_tab_wrapper').addClass('displaynone');
	}	
}

</script>

