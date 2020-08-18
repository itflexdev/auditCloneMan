<!DOCTYPE html>
<html>

<head>
	
	</script>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title></title>
</head>

<body>
	<body onload="document.form.submit()">
	<form class="form" name="form" method="post" action="https://sandbox.payfast.co.za/eng/process">
		<input id="merchant_id" name="merchant_id" value="10018056" type="hidden">
		<input id="merchant_key" name="merchant_key" value="u1jqr1spqbpes" type="hidden">
		<input id="return_url" name="return_url" value="http://diyesh.com/auditit_new/pirb_new/pirb/webservice/services/purchasecocreturn" type="hidden">
		<input id="cancel_url" name="cancel_url" value="http://diyesh.com/auditit_new/pirb_new/pirb/webservice/services/purchasecoccancel" type="hidden">
		<input id="notify_url" name="notify_url" value="http://diyesh.com/auditit_new/pirb_new/pirb/webservice/services/purchasecocnotify" type="hidden">
		
		<input id="name_first" name="name_first" value="Bala" type="hidden">
		<input id="name_last" name="name_last" value="SM" type="hidden">
		<input id="email_address" name="email_address" value="smbala_diye@itflexsolutions.com" type="hidden">
		
		<input name="amount" value="100" type="hidden">
		<input name="item_name" value="Coc Purchase" type="hidden">
		<input name="item_description" value="coc" type="hidden">
		<input name="payment_method" value="cc" type="hidden">
		
		<input type="hidden" name="custom_str1" name="'{'coc_type': 1,'delivery_type': 1,'cost_value': 10.00,'quantity': 1,'vat': 15.00,'total_due': 28.00,'delivery_cost': 1.00,'permittedcoc': 12,'userid': 28}'">
	</form>
</body>
</body>

</html>