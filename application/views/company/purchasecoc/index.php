<?php
echo $customview;

$companystatus 			= $userdata1['companystatus'];
$mobile_phone 			= $username['mobile_phone'];
$userid		 			= $username['id'];
$log_coc 				= $logcoc;
$VAT 					= $settings["vat_percentage"];
$coc_purchase_limit   	= $username["coc_purchase_limit"]=='' ? '0' : $username["coc_purchase_limit"];
$electronic_coc_log   	= 1;

$coc_counts 			= $coc_count['count']=='' ? '0' : $coc_count['count'];

$cocpaperwork 			= $cocpaperwork["amount"];
$cocelectronic 			= $cocelectronic["amount"]; 

$postage 				= $postage["amount"];
$couriour 				= $couriour["amount"];
$collectedbypirb 		= $collectedbypirb["amount"];

$type		 			= $username['type'];

if($type==4){
	$modalmsg = $username['contact_person']." - ".$mobile_phone;
}else{
	$modalmsg = $username['name']." / ".$username['surname']." - ".$mobile_phone;
}

$admin_allot 			= isset($userorderstock) ? $userorderstock : '';
//$coc_counts = $coc_purchase_limit - $admin_allot;

$regno 					= $username['reg_no'];
if($regno==''){
	$disabled 		= 'disabled="disabled"';
	$disabledarray 	= ['disabled' => 'disabled'];
}else{
	$disabled 		= '';
	$disabledarray 	= [];
}
?>
<?php
$company_status = array(3, 4, 5);
if (in_array($companystatus, $company_status)) {
	echo " Access denied ";
}else{

?>

<div class="row page-titles">
	<div class="col-md-5 align-self-center">
		<h4 class="text-themecolor">Purchase COC</h4>
	</div>
	<div class="col-md-7 align-self-center text-right">
		<div class="d-flex justify-content-end align-items-center">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?php echo base_url().'company/dashboard'; ?>">Home</a></li>
				<li class="breadcrumb-item active">Purchase COC</li>
			</ol>
		</div>
	</div>
</div>
<?php echo $notification; ?>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<form class="form" method="post">
					<div class="row">
						<div class="col-md-3">
							<div class="form-group coc_pur_sec">
								<div class="coc_pur_num1"><?php  echo $admin_allot; ?></div>
								<label class="add_max_height">Electronic COC's Stock</label>
								<input type="hidden" id="admin_allot" class="form-control" name="admin_allot" value="<?php  echo $admin_allot; ?>" readonly>
							</div>
						</div>
						
						<div class="col-md-3">
							<div class="form-group coc_pur_sec">
								<div class="coc_pur_num4"><?php  echo $coc_counts; ?></div>
								<label class="add_max_height">Number of Electronic able to purchase</label>
								<input type="hidden" class="form-control" id="number_of_purchase_coc" name="number_of_purchase_coc" readonly value="<?php echo $coc_counts; ?>">
							</div>
						</div>					
					</div>	
					

					<div class="row">						
						<div class="col-md-6">
							<div class="form-group">
								<input type="hidden" name="coc_type" class="coc_type" value="1">
								<label>Number of COC's You wish to Purchase</label>
								<input onchange="modifycost();" type="number" id="coc_purchase" class="form-control" min="1" value="1" name="coc_purchase" for="coc_purchase" max="<?php echo $coc_counts; ?>" <?php echo $disabled; ?>>
							</div>
						</div>
						<div class="alert-msg">Your Purchase Limit is Reached</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Cost of Electronic COC</label>
								<input type="number" id="coc_cost" class="form-control coc_cost" readonly name="coc_cost">
							</div>
						</div>						
						<div class="col-md-6">
							<div class="form-group">
								<label>VAT @<?php echo $VAT; ?>%</label>
								<input type="number" id="vat" class="form-control" readonly name="vat">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Total Due</label>
								<input type="text" id="totaldue" class="form-control" readonly name="totaldue">
							</div>
						</div>
					</div>
					<!-- <?php // if (!in_array($companystatus, [2, 6])) { ?> -->
					
					<h4 class="card-title add_top_value">Disclaimer</h4>
					<div class="custom-control custom-checkbox">
						<input type="checkbox" id="disclaimer" name="disclaimer" class="custom-control-input" <?php echo $disabled; ?>>
						<label class="custom-control-label" for="disclaimer">We declare and understand</label>
					</div>
					<div class="mt-3">
						<ul>
							<li>That all the plumbing works comply in all respect to the plumbing regulations and laws as defined by the National Compulsory Standards and Local By-Laws. The PIRB's auditing, rectification and disciplinary policy and procedures and that we fully comply to them. If I fail to comply with the policy and procedures it may result in disciplinary action being taken against us, which could result in the suspension from the PIRB.</li>
						</ul>
					</div>

					<input type="hidden" id="dbvat" name="dbvat" value="<?php echo $VAT; ?>">					
					<input type="hidden" id="dbcocelectronic" name="dbcocelectronic" value="<?php echo $cocelectronic; ?>">
					<!-- 					<input type="hidden" id="description" name="description" value="Purchase of {number} PIRB Certificate of Compliance"> -->
					<div class="row text-right">
						<div class="col-md-12">
							<button type="button" name="cancel" id="cancel" class="btn btn-block btn-primary btn-rounded">Cancel</button>

							<button type="button" id="purchase" name="purchase" value="purchase" class="btn btn-block btn-primary btn-rounded">Purchase</button>
						</div>
					</div>
					
					<!---	Payment	--->
					<input id="merchant_id" name="merchant_id" value="<?php echo $this->config->item('paymentid'); ?>" type="hidden">
					<input id="merchant_key" name="merchant_key" value="<?php echo $this->config->item('paymentkey'); ?>" type="hidden">
					<input id="return_url" name="return_url" value="<?php echo base_url().'company/purchasecoc/index/paymentsuccess'; ?>" type="hidden">
					<input id="cancel_url" name="cancel_url" value="<?php echo base_url().'company/purchasecoc/index/paymentcancel'; ?>" type="hidden">
					<input id="notify_url" name="notify_url" value="<?php echo base_url().'company/purchasecoc/index/paymentnotify'; ?>" type="hidden">
					
					<input id="name_first" name="name_first" value="<?php echo isset($username['name']) ? $username['name'] : ''; ?>" type="hidden">
					<input id="name_last" name="name_last" value="<?php echo isset($username['surname']) ? $username['surname'] : ''; ?>" type="hidden">
					<input id="email_address" name="email_address" value="<?php echo $username['email']; ?>" type="hidden">
					
					<input type="hidden" id="totaldue1" class="form-control" readonly name="amount">
					<input id="item_name" name="item_name" value="Coc Purchase" type="hidden">
					<input id="item_description" name="item_description" value="coc" type="hidden">
					<!--- <input id="payment_method" name="payment_method" value="cc" type="hidden"> --->
					
					<input type="hidden" name="custom_str1" id="paymentcustomdata">
					<!-- <?php // } ?> -->
				</form>
				
				<div id="skillmodal" class="modal fade" role="dialog">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<form class="skillform">
								<div class="modal-body">
									<div class="row">
										<div class="col-md-12 text-center">
											<div class="form-group">
												<h4 class="mb-15">A One Time Pin (OTP) was sent to the Company Representative with the following Mobile Number:</h4>
												<h4><?php echo $modalmsg; ?></h4>
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												<input id="sampleOtp" type="text" class="form-control skill_training displaynone" readonly>
												<label>Enter OTP</label>
												<input name="otpnumber" id="otpnumber" type="text" class="form-control skill_training">
												<div class="invalidOTP" style="color: red;"> Given OTP is Invalid ! </div>
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

<script type="text/javascript">
	$(function(){	
		$('#skillmodal').modal('hide');
		$('#purchase').prop('disabled', true);
		coctype1($('.coc_type').val());
		delivery(0);

		$('#cancel').click(function (){
			location.reload();
		});

		$('.alert-msg').hide();
		
		if($('#coc_purchase').attr('max')=='0'){
			var cocmaxerror = 'Purchase limit has been exceeded. Contact our support for further assistance.';
		}else{
			var cocmaxerror = 'You cannot purchase more than '+$('#coc_purchase').attr('max')+' COCs. Contact our support for further assistance.';
		}
		
		validation(
			'.form',
			{
				coc_purchase : {
					required	: true,
				},
				disclaimer : {
					required	: true,
				},							
			},
			{
				coc_purchase 	: {
					required	: "Number of COC's You Wish to Purchase field is required.",
					max 		: cocmaxerror
				},
				disclaimer 	: {
					required	: "Disclaimer is required."
				},				
			}
		);
		
		if ($('#log_coc').val()!='') {
			var coccount = 0
			coccount = Math.abs(parseInt($('#log_coc').val())-parseInt($('#coc_permitted').val()));
		}
		
		$("#coc_purchase").keyup(function(e){
			calc();
			delivery(0);
		});


		$('#purchase').on('click',function(){
			if(!$('.form').valid()) return false;
			ajaxotp();
			
			$('#skillmodal').modal('show');
			$('.invalidOTP').hide();

			
		});

		$('.resend').on('click',function(){
			ajaxotp();
		});

		var disclimerClickCount = 0;
		$('#disclaimer').on('click',function(){
			disclimerClickCount += 1;
			if (disclimerClickCount%2 == 1) {
				$('#purchase').prop('disabled', false);
			}else{
				$('#purchase').prop('disabled', true);
			}	
		});
		

		$('.verify').on('click',function(){
			var otpver = $('#otpnumber').val();

			var delivery_type = 0;
			var cocType = 0;
			var delivery_cost = 0;
			if ($('#1-Electronic').is(":checked")) {
				delivery_type = 0;
				delivery_cost = 0;
				cocType = 1;
			}

			ajax('<?php echo base_url().'ajax/index/ajaxotpverification'; ?>', {otp: otpver}, '', { 
				success:function(data){
					if (data == 0) {
						$('.invalidOTP').show();
					}else{
						var customdata = { 
							coc_type: $('.coc_type').val(), 
							delivery_type: 0,
							cost_value: $('#coc_cost').val(), 
							quantity: $('#coc_purchase').val(), 
							vat: $('#vat').val(), 
							total_due: $('#totaldue').val(), 
							delivery_cost: 0,
							permittedcoc: $('#number_of_purchase_coc').val(),
							userid: '<?php echo $userid; ?>'
						};
						// console.log(customdata);
						// return false;
						if(getTotal()!=customdata.total_due){
							alert('Try Again');
							window.location.href = '<?php echo base_url()."company/purchasecoc/index"; ?>';
							return false;
						}
							
						$('#paymentcustomdata').val(JSON.stringify(customdata));
						$('.form').prop('action','<?php echo $this->config->item('paymenturl'); ?>');
						$('.form').submit();
					}
				}
			})
		});	
	});
	
	var coc = 0;
	var coc_amount = 0;
	function coctype1(value){
		if(value=='1'){
			coc_amount = $('#dbcocelectronic').val();			
			$('#cost_f_delivery').val('0');
		}
	}

	function ajaxotp(){
		ajax('<?php echo base_url().'ajax/index/ajaxotp'; ?>', {}, '', { 
			success:function(data){
				if(data!=''){
					$('#sampleOtp').removeClass('displaynone').val(data);
				}
			}
		})
	}

	function calc(){
		var coc_cost 		= parseFloat($('#coc_cost').val());
		var costdelivery 	= 0;
		var vat 			= parseFloat($('#dbvat').val());


		var vat1 = parseFloat(removelastchr(((costdelivery + coc_cost ) * vat) / 100));
		var total = vat1 + coc_cost + costdelivery;

		$('#vat').val(vat1);
		$('#totaldue').val(currencyconvertor(total));
		$('#totaldue1').val(currencyconvertor(total));
	}

	function modifycost()
	{
		var quan = $("#coc_purchase").val();
		
		var cost = parseFloat($("#dbcocelectronic").val());	

		var total = cost * quan;
		$("#coc_cost").val(removelastchr(total));

		calc();
	}

	function delivery(value)
	{		
		modifycost();
	}
	
	function getTotal(){		
		var quantity = $('#coc_purchase').val();
		
		var coctypeval = 0;
		coctypeval = parseFloat($("#dbcocelectronic").val()) * quantity;		
		coctypeval = parseFloat(removelastchr(coctypeval));
						
		var vat 	= parseFloat($('#dbvat').val());
		var vatval 	= 0;
		if(coctypeval!=0){
			vatval = parseFloat(((coctypeval) * vat)/100);
		}
		vatval = parseFloat(removelastchr(vatval));
		
		return currencyconvertor(coctypeval + vatval);
	}
</script>

<?php

	}
?>