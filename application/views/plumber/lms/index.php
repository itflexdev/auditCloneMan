<?php
$id 					= isset($userdetails['id']) ? $userdetails['id'] :'';
$name 					= isset($result['name']) ? $result['name'] :'';
$surname 				= isset($result['surname']) ? $result['surname'] :'';
$password 				= isset($userdetails['password_raw']) ? $userdetails['password_raw'] :'';
$regno 					= isset($result['registration_no']) ? $result['registration_no'] :'';
$email 					= isset($userdetails['email']) ? $userdetails['email'] :'';

$lms_registration 		= isset($result['lms_registration']) ? $result['lms_registration'] : '';
$lms_status 			= isset($result['lms_status']) ? $result['lms_status'] : '';
?>

<div class="row page-titles">
	<div class="col-md-5 align-self-center">
		<h4 class="text-themecolor">LMS Registration</h4>
	</div>
	<div class="col-md-7 align-self-center text-right">
		<div class="d-flex justify-content-end align-items-center">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?php echo base_url().'plumber/dashboard'; ?>">Home</a></li>
				<li class="breadcrumb-item active">LMS Registration</li>
			</ol>
		</div>
	</div>
</div>
<?php echo $notification; ?>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<div class="row">
							<div class="form-group col-md-12">
								<label for="registerlms">Register yourself for iopsatraining.co.za:</label>
								<?php if ($lms_status =='0' && $lms_registration =='1') { ?>
									<button type="button" id="registerlmsbtn" name="submit" value="Register" class="btn btn-primary">Register Now</button>
								<?php } ?>
								</div>	
							</div>
						
						<div class="modalloader"></div>
						<?php if ($lms_status =='1' && $lms_registration =='1') { ?>
						<div class="row lms-exist">
							<div class="form-group col-md-12">
								<label for="registerlms-exist">Account Linked</label>
						</div>
						<?php } ?>
						<div class="row lms-sucess">
							<div class="form-group col-md-12 appendmessage">
								
							</div>	
						</div>

						</div>	
					</div>
				</div>
				<div class="col-md-6 text-right">
					<input type="hidden" id='firstname' name="firstname" value="<?php echo $name; ?>">
					<input type="hidden" id='surname' name="surname" value="<?php echo $surname; ?>">
					<input type="hidden" id='password' name="password" class="password" value="<?php echo $password; ?>">
					<input type="hidden" id='username' name="username" class="username" value="<?php echo $regno; ?>">
					<input type="hidden" id='email' name="email" class="email" value="<?php echo $email; ?>">
					<input type="hidden" id='nickname' name="nickname" class="nickname" value="<?php echo $regno; ?>">
					<input type="hidden" id='userid' name="id" value="<?php  echo $id; ?>">
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function(){
		

		$('.lms-sucess').hide();
		// $('.lms-exist').hide();
		$('.modalcontant').hide();

		// $('#registerlmsbtn').click(function(){
		// 	$(this).hide();
		// 	$('.lms-sucess').show();
		// });

		// if ($('#registerlmsbtn').is(":hidden")) {
		// 	$('.lms-exist').show();
		// }
		$('#registerlmsbtn').click(function(){
			$(this).attr("disabled", true);
			var firstname 	= $('#firstname').val();
			var surname 	= $('#surname').val();
			var password 	= $('#password').val();
			var username 	= $('#username').val();
			var email 		= $('#email').val();
			var nickname 	= $('#nickname').val();
			var uid 		= $('#userid').val();
		      $.ajax({
		      	data: {
		      		firstname: firstname,
			      	surname: surname,
			      	password: password,
			      	username: username,
			      	email: email,
			      	nickname: nickname,
					},
				crossDomain: true,
		        type: 'GET',
		        url: 'https://iopsatraining.co.za/wp-json/lms/v2/users/register/',
		        beforeSend:function(){
		          $('.modalloader').html('<img src="<?php echo base_url().'assets/images/ajax-loader.gif'; ?>" width="50" height="50"/>');
		        },
	            success:function(data)
		        {
		        	// var obj = $.parseJSON(data)
		        	var status = data.status;
		        	var message = data.message;
		        	$('.appendmessage').append('<label for="registerlms-sucess">'+message+'</label>');
		        	$('.modalloader').hide();
		        	userupdate(uid);
				 // console.log(data);
		        }
		      });
		});

	function userupdate(uid){
		var firstname 	= $('#firstname').val();
		var surname 	= $('#surname').val();
		var password 	= $('#password').val();
		var username 	= $('#username').val();
		var email 		= $('#email').val();
		var nickname 	= $('#nickname').val();
		var uid 		= $('#userid').val();
		$.ajax({
		      	data: {
		      		uid: uid,
			      	lms_status: '1',
					},
				async: false,
		        type: 'POST',
		        url: '<?php echo base_url().'plumber/lms/index/lmsaction' ?>',
	            success:function(data)
		        {
		        	$('.lms-sucess').show();
		        	$('#registerlmsbtn').prop("disabled", true);
		        }
		      });
	}
	});
</script>

