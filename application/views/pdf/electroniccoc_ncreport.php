<?php
$cocid          = isset($result['id']) ? $result['id'] : '';  

$cocid = '   '.$cocid;

$completiondate     = isset($result['cl_completion_date']) && $result['cl_completion_date']!='1970-01-01' ? date('d-m-Y', strtotime($result['cl_completion_date'])) : '';

$completiondate = '   '.$completiondate;

$cl_order_no      = isset($result['cl_order_no']) ? $result['cl_order_no'] : '';

$cl_order_no = '   '.$cl_order_no;

$name           = isset($result['cl_name']) ? $result['cl_name'] : '';

$address        = isset($result['cl_address']) ? $result['cl_address'] : '';

$street         = isset($result['cl_street']) ? $result['cl_street'] : '';

$number         = isset($result['cl_number']) ? $result['cl_number'] : '';

$province         = isset($result['cl_province_name']) ? $result['cl_province_name'] : '';

$city           = isset($result['cl_city_name']) ? $result['cl_city_name'] : '';

$suburb         = isset($result['cl_suburb_name']) ? $result['cl_suburb_name'] : '';



$cl_contact_no      = isset($result['cl_contact_no']) ? $result['cl_contact_no'] : '';

$installationtypeid   = isset($result['cl_installationtype']) ? explode(',', $result['cl_installationtype']) : [];

$specialisationsid    = isset($result['cl_specialisations']) ? explode(',', $result['cl_specialisations']) : [];



$agreementid      = isset($result['cl_agreement']) ? $result['cl_agreement'] : '';

$logdate        = isset($result['cl_log_date']) &&  date('Y-m-d', strtotime($result['cl_log_date']))!='1970-01-01' ? date('d/m/Y', strtotime($result['cl_log_date'])) : '';

$logtime        = isset($result['cl_log_date']) &&  date('Y-m-d', strtotime($result['cl_log_date']))!='1970-01-01' ? date('H', strtotime($result['cl_log_date'])).'H'.date('i', strtotime($result['cl_log_date'])) : '';

$plumberid        = isset($result['u_id']) ? $result['u_id'] : '';
$plumbername      = isset($result['u_name']) ? $result['u_name'] : '';
$plumbercompany     = isset($result['plumbercompany']) ? $result['plumbercompany'] : '';
$plumberwork      = isset($result['u_work']) ? $result['u_work'] : '';
$cocid1           = isset($result['id']) ? $result['id'] : '';
  
$completiondate1    = isset($result['cl_completion_date']) && $result['cl_completion_date']!='1970-01-01' ? date('d-m-Y', strtotime($result['cl_completion_date'])) : '';
    

function base64conversion($path){

  $type = pathinfo($path, PATHINFO_EXTENSION);

  $data = file_get_contents($path);

  return 'data:image/' . $type . ';base64,' . base64_encode($data);

}



$textimg  = base64conversion(base_url().'assets/images/text-img.png');

$roundimg   = base64conversion(base_url()."assets/images/round.png");

$logoimg  = base64conversion(base_url()."assets/images/logo-img.png");

$tickimg  = base64conversion(base_url()."assets/images/tick.png");

$ownersimg  = base64conversion(base_url()."assets/images/owners.png");



echo'<!DOCTYPE html> 

<html>

  <head>

    <title>Electronic COC PDF</title>

    <style>
              .hot---water--pdf{
                  color: #d0343c;
              }

              .cold--water--pdf{
                  color: #00b0f0;
              }

              .sanitary--water--pdf{
                  color: #6062a8;
              }

              .below--ground--pdf{
                  color: #805844;
              }

              .above--ground--pdf{
                  color: #4ab050;
              }

              .rain--water--pdf{
                  color: #144472;
              }

              .solar--water---pdf{
                  color: #f38135;
              }

              .heat--pump--pdf{
                  color: #7c2824;
              }

              table, th, td{
                border: 1px solid #a7a9ab;
                font-family: Helvetica !important;
              }

              table{
                border-collapse: collapse;
                width: 100%
              }

              .hot---water--pdf-bg{
                background-color: #fcf2f1;
              }       

              .cold--water--pdf-bg{
                background-color: #f0fafc;
              }

              .sanitary--water--pdf-bg{
                background-color: #f1eff6;
              }

              .below--ground--pdf-bg{
                background-color: #f6f1ee;
              }

              .above--ground--pdf-bg{
                background-color: #f4f9f3;
              }

              .rain--water--pdf-bg{
                background-color: #e9ecf1;
              }

              .solar--water---pdf--bg{
                background-color: #fef5ee;
              }

              .heat--pump--pdf--bg{
                background-color: #f5ece5;
              }


              table.page_overall_auditreport {
    margin: 10px auto;
}
table.head-all {
    width: 100%;
    margin-bottom: 20px;
    padding-bottom: 20px;    
}
table.coc_details_overall {
    width: 100%;
    border-bottom: 1.5px solid;   
    margin-bottom: 20px;
    padding-bottom: 20px;    
}
table.coc_details_overall h3 {
    border-bottom: 1.5px solid #000;
    display: inline-block;
    margin: 0;
    margin-bottom: 10px;
} 
table.coc_details_overall td, table.auditor_details_overall td {
    padding: 5px;
    width: 33.3%;
}
table.page_overall_auditreport input[type="text"] {
    padding: 5px;
}
table.auditor_details_overall h3 {
    border-bottom: 1.5px solid #000;
    display: inline-block;
    margin: 0;
    margin-bottom: 10px;
}

table.auditor_details_overall {
    width: 100%;
    border-bottom: 1.5px solid #000;
    margin-bottom: 20px; 
    padding-bottom: 20px;       
}
table.notice-license-text {
    width: 100%;
    border: 1px solid #000;
    border-collapse: collapse;
    margin-bottom: 25px; 
}
table.notice-license-text th {
    background: #f00;
}
table.notice-license-text th h3 {
    color: #fff;
    font-weight: 600;
    margin: 0;
    padding: 5px 0;
}
table.table.table-bordered.reviewtable {
    width: 100%;
    border-collapse: collapse;
}
h3.audit-table-heading {
    border-bottom: 1.5px solid #000;
    display: inline-block;
    margin: 20px 0 20px;
}
table.table.table-bordered.reviewtable td {
    border: 1px solid #000;
    padding: 10px;
    text-align: center;
}
table.table.table-bordered.reviewtable th {
    border: 1px solid #000;
  padding: 15px;    
  text-align: center;
}
table.coc_details_overall label, table.auditor_details_overall label {
    font-weight: bold;
}
table.notice-license-text tbody tr td {
    padding: 5px;
}

    </style>

  </head>                

  <body style=" font-family:Helvetica; -webkit-print-color-adjust: exact; margin:0; font-family: arial; width:auto;">

      <div id="wrapper">

          <div class="certificate-block" >

              <div class="container" style=" font-family:Helvetica; width:auto; margin:0 auto; padding:0 15px; margin-bottom: 10px; padding-top:4px; border: 4px solid #00aeef; border-radius:20px;">
              <style>
              body{
                border-radius:20px;
                padding-top:10px;
                padding-bottom: 15px;
                font-family: "Helvetica" !important;
              }

              *{
                font-family: Helvetica !important;
              }

              </style>

                  <div class="uper-block">

                      <h2 align="center" style=" font-family:Helvetica; padding-bottom:10px; margin:0;"><img src="'.$textimg.'"  style=" font-family:Helvetica; width:100%; margin:0 auto;"></h2>

                    <div class="logo-block" style=" font-family:Helvetica; width:48%; float: left;">                         

                      <img style=" font-family:Helvetica; margin-top: 20px; width: 100%; height: auto;" src="'.$logoimg.'" />

                      </div>

                      <div class="rt-side" style=" font-family:Helvetica; height: 40px; float: right;width: 50%;padding: 10px 0 0 15px;margin-top: 20px; box-sizing: border-box;">

                          <div class="box" style="font-family:Helvetica; height: 20px; padding:5px;background: #d1d3d4;">

                              <h4 style=" font-family:Helvetica; width: 30%; display: inline-block; font-size: 15px;font-weight: 400;color: #000;padding-top: 5px;padding-left:5px;margin:0;">Certificate No:</h4>

                              <span style=" font-family:Helvetica; background: #fff;display: inline-block;width: 60%;float: right;height: 20px;padding-left: 5px;">'.$cocid.'</span>

                              <div style=" font-family:Helvetica; clear:both;"></div>

                          </div>

                          <div class="box" style="margin-top: 3px;text-align:center; height: 20px; padding:5px;background: #f3cfc1;">

                              <p style="font-family:Helvetica; font-size:9px; line-height:10px; font-weight: 400;color: #000;padding: 0;margin:0;">ONLY PIRB REGISTERED LICENSED PLUMBERS ARE AUTHORISED TO ISSUE THIS PLUMBING CERTIFICATE OF COMPLIANCE</p>

                          </div>

                          <div class="box" style="margin-top: 3px;font-family:Helvetica; height: 20px; text-align: center; background: #d2232a;padding:5px;">

                              <p style="color: #fff;font-family:Helvetica; font-size: 9px;font-weight: 400;padding:0; margin: 0;">TO VERIFY AND AUTHENTICATE THIS CERTIFICATE OF COMPLIANCE VISIT PIRB.CO.ZA AND CLICK ON VERIFY/AUTHENTICATE LINK</p>

                          </div>

                      </div>

                      <div style=" font-family:Helvetica; clear:both;"></div>

                  </div>

                  <div class="down-block" style=" font-family:Helvetica; padding-bottom: 4px; margin-top: 70px;">

                      <div class="lt-side" style=" font-family:Helvetica; float: left; width:50%;">

                          <label style=" font-family:Helvetica; width: 30%;font-size: 12px; line-height: 15px; font-weight: 400; color: #000; padding: 0; margin: 0px 0px; float: left;">Plumbing Work <br>Completion Date:</label> 

                          <label style="position: relative;line-height: 20px;top: -31px; bottom:20px; margin-bottom:20px;font-family: Helvetica;width: 70%; height: 30px;border: 1px solid #bbbcbe;display: inline-block;padding: 0px;margin: 0;position: relative;float: right;">

                          <span style="position:absolute;top: 5px;left: 12px; font-size: 12px;"> '.$completiondate.'</span>
                          </label>

                      </div>

                      <div class="rt-side" style=" font-family:Helvetica; float: right; width:50%; clear: both;">

                          <label style=" font-family:Helvetica; font-size: 10px;width: 65%;line-height: 20px;font-weight: 400;color: #000;padding: 0;margin:0px 0 0;float: left; padding-left: 5px;">INSURANCE CLAIM/ORDER NO. (If relevant)
                          </label>

                          <label style="font-family: Helvetica;height: 25px;width: 35%;border: 1px solid #bbbcbe;display: inline-block;padding: 0;margin: 0;float: right;position: relative;">
                          <span style="position: relative;top: 4px;"> '.$cl_order_no.'</span>
                          </label>

                      </div>

                      <div style=" font-family:Helvetica; clear:both;"></div>

                  </div>

                  <div style=" font-family:Helvetica; clear:both;"></div>




                  <div class="address-block" style=" margin-bottom: 4px; font-family:Helvetica;">

                  <table height="200px" cellpadding="0" cellspacing="0">
                    <tr style="text-align: center;">
                      <th colspan="2" style="background: #c9db82; padding: 2px 5px; font-size: 12px;color: #000;">
                        Physical Address Details of Installation:
                      </th>
                    </tr>
                    <tr>
                      <td colspan="2" style="font-size:12px;color:#000;text-indent: 5px;">
                        Owners name: '.$name.'
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2" style="font-size:12px;color:#000;text-indent: 5px;">
                        Name of Complex/Flat and Unit Number (if applicable): '.$address.'
                      </td>
                    </tr>
                    <tr>
                      <td style="font-size:12px;color:#000;text-indent: 5px;">
                      Street: '.$street.'
                      </td>
                      <td style="font-size:12px;color:#000;text-indent: 5px;">
                      Number: '.$number.'
                      </td>
                    </tr>
                    <tr>
                      <td style="text-align: left;font-size: 12px;color: #000;text-indent: 5px;">
                        Suburb: '.$suburb.'
                      </td>
                      <td style="text-align: left;font-size: 12px;color: #000;text-indent: 5px;">
                        City: '.$city.'
                      </td>
                    </tr>
                    <tr>
                      <td style="font-size: 12px;color: #000;text-align: left;text-indent: 5px;">
                         Tel No: '.$cl_contact_no.'
                      </td>
                      <td style="font-size: 12px;color: #000;text-align: left;text-indent: 5px;">
                       
                      </td>
                    </tr>
                  </table>

                  </div>



                  <div style=" font-family:Helvetica; clear:both;"></div>



                  <div class="up_block" style=" font-family:Helvetica; margin: 4px 0 0;">'; 
                        ?>

                        
                      
                        
                        <div class="top">
                          <table width="100%" cellpadding="0" style="border-collapse: collapse;border-spacing: 0;table-layout: fixed;">
                            <tr>
                              <th style="width: 84%;background: #d1e28d; padding: 2px 5px; text-align: center;font-size: 12px;font-weight: 700;">
                                  Type of Installation Carried Out by Licensed Plumber
                                  <br/><small style="font-size: 9px;font-weight:400">(Clearly tick the appropriate Installation Category Code and complete the installation details below)</small>
                              </th>
                              <th style="font-size: 12px; width: 8%; text-align: center;padding: 0; font-weight: noraml;">Code</th>
                              <th style="font-size: 12px; width: 8%; text-align: center;padding: 0; font-weight: noraml;">Tick</th>
                            </tr>
                                  <?php
                                    $installationclassbg = ['hot---water--pdf-bg', 'cold--water--pdf-bg', 'sanitary--water--pdf-bg', 'below--ground--pdf-bg', 'above--ground--pdf-bg', 'rain--water--pdf-bg'];
                                    $installationclass = ['hot---water--pdf', 'cold--water--pdf', 'sanitary--water--pdf', 'below--ground--pdf', 'above--ground--pdf', 'rain--water--pdf'];
                                    $docurl = $_SERVER['DOCUMENT_ROOT'];
                                    foreach ($installation as $key => $value) {
                                  ?>
                            <tr>
                              <td style="width: 84%;font-family:Helvetica;font-size:12px;color: #000; font-weight: 400; margin:0; padding: 0 0 0 5px; " class="<?php echo isset($installationclassbg[$key]) ? $installationclassbg[$key] : ''; ?>">
                                  <?php echo substr($value['name'],0,45); ?>
                                <span class="<?php echo isset($installationclass[$key]) ? $installationclass[$key] : ''; ?>" style="font-weight: 700;font-family:Helvetica;">
                                  <?php echo substr($value['name'],45); ?>
                                </span>
                              </td>
                                
                                <td style="width: 8%; font-family:Helvetica;text-align:center;font-size: 12px;line-height: 15px; margin:0;">
                                <?php echo $value['code'];?>
                                </td>
                                <td style="font-family:Helvetica;text-align: center;margin: 0;width: 8%">
                                <?php echo (in_array($value['id'], $installationtypeid)) ? '<img style="width: 30%; height: 30%;" src="'.$tickimg.'" />' : ''; ?>
                                </td>
                              </tr>
                          <?php } ?>
                          </table>
                        </div>
                
                  

                      

          <?php   

          echo' </div>

                  <div class="up_block" style=" font-family:Helvetica; margin: 4px 0 0;">';

?>


                    <div class="top">
                     <table cellpadding="0" style="border-collapse: collapse;table-layout: fixed; border-spacing: 0;">
                      <tr>
                        <th style="width: 84%; background: #d1e28d;text-align: center; padding: 2px 5px; font-size: 12px;font-weight: 700;">
                            Specialisations: To be Carried Out by Licensed Plumber Only Registered to do the Specialised Work 
                            <label style=" font-family:Helvetica; font-weight: 400;text-align: center; padding: 0; margin: 0; font-size:9px;">(To verify and authenticate Licensed Plumbers specialisations visit pirb.co.za)</label>
                        </th>
                        <th style="font-size: 12px; width: 8%; text-align: center; font-weight: noraml;">Code</th>
                        <th style="font-size: 12px; width: 8%; text-align: center; font-weight: noraml;">Tick</th>
                      </tr>
                      <?php
                      $specialisationsclassbg = ['solar--water---pdf--bg', 'heat--pump--pdf--bg'];
                      $specialisationsclass = ['solar--water---pdf', 'heat--pump--pdf'];
                      foreach ($specialisations as $key => $value) { 
                      ?>
                      <tr>
                        <td class="<?php echo isset($specialisationsclassbg[$key]) ? $specialisationsclassbg[$key] : ''; ?>">
                          <h5 style="font-family:Helvetica;font-size:12px; line-height:15px; color: #000; font-weight: 400; margin:0; padding: 0 0 0 5px; "><?php echo substr($value['name'],0,45); ?>
                          <span class="<?php echo isset($specialisationsclass[$key]) ? $specialisationsclass[$key] : ''; ?>" style="font-family:Helvetica;font-weight: 700;"><?php echo substr($value['name'],45); ?></span>
                          </h5>
                        </td>
                        <td style="text-align: center;font-family:Helvetica;font-size: 12px;line-height: 15px; margin:0;">
                          <?php echo $value['code'];?>
                        </td>
                        <td style="text-align: center;font-family:Helvetica;margin:0;">
                          <?php echo (in_array($value['id'], $specialisationsid)) ? '<img style="width: 30%; height: 30%;" src="'.$tickimg.'" />' : ''; ?>
                        </td>
                      </tr>
                    <?php   }  ?>
                    </table>
                  </div>



            <?php     

      

if($agreementid=='1'){
	$agreementselectedclass1 = 'background-color:#F9ACB0;';
	$agreementselectedclass2 = 'background-color:#fff;';
}elseif($agreementid=='2'){
	$agreementselectedclass1 = 'background-color:#fff;';
	$agreementselectedclass2 = 'background-color:#F9ACB0;';
}

$agreement = ' 

<div class="block" style="margin-bottom: 0px;font-family:Helvetica;">

  <div class="lt-left" style="font-family:Helvetica;'.$agreementselectedclass1.'">
  <table style="table-layout: fixed;">
  <tr>
  <td style="width: 5%; text-align: center;font-family:Helvetica;font-weight: 700;font-size: 10px;">A</td>
   <td style="width: 95%; font-family:Helvetica; font-size: 10px;padding:2px 5px; text-align: justify;">
   The above plumbing work was carried out by me or under my supervision, and that it complies in all respects to the plumbing regulations, laws, National Compulsory Standards and Local bylaws.
   </td>
   </tr>
   </table>

  </div>

  <div class="clear"></div>

</div>
<div class="block" style="font-family:Helvetica;">

 <div class="lt-left" style="font-family:Helvetica;'.$agreementselectedclass2.'">
 <table>
 <tr>
  <td style="width: 5%; text-align: center;font-family:Helvetica;font-weight: 700;font-size: 10px;">B</td>
  <td style="width: 95%; font-family:Helvetica; font-size: 10px;padding:2px 5px; text-align: justify;">
   I have fully inspected and tested the work started but not completed by another Licensed plumber. I further certify that the inspected and tested work and the necessary completion work was carried out by me or under my supervision, complies in all respects to the plumbing regulations, laws, National Compulsory Standards and Local bylaws.
   </td>
   </tr>
   </table>

 </div>

 <div class="clear"></div>

</div>
';


          echo' </div>

                  <div class="block" style=" font-family:Helvetica; text-align: center;">

                                          

                      <p style=" font-family:Helvetica; padding: 2px 0; margin: 0; font-size: 8px; color: #000; font-weight: 400; font-style: italic;word-wrap: break-word">See explanations of the above on the reverse of this certificate</p>

                      <div style="clear:both;"></div>

                  </div>

                
                  <div class="installetion" id="preexisting_table" style=" font-family:Helvetica;">

                      <table class="table table-striped table-bordered">
                          <tr style="background: #d1e28d;">
                            <td colspan="2" style="font-size: 12px;text-align: center; padding: 2px 5px; font-weight: 700;">Installation Details <small style="font-size: 9px;font-weight: 400;">(Details of the work undertaken or scope of work for which the COC is being issued for)</small></td>
                          </tr>
                          <tr>
                            <td colspan="2" style="height: 48px;font-family:Helvetica; font-size:10px;font-weight: 400; text-align: justify; padding: 2px 10px;vertical-align: top;">'.$result['cl_installation_detail'].'</td>
                          </tr>
                      </table>

                  </div>

                  <div class="installetion" style=" font-family:Helvetica;margin:4px 0 0">

                      <div class="box" style="font-family:Helvetica;">

                      <table cellpadding="0" cellspacing="0" style="border-collapse: collapse; border-spacing: 0px;">
                        <tr style="background: #d1e28d;text-align: center;">
                          <td style="font-family:Helvetica; font-size:12px; font-weight: 700; padding: 2px 5px;">Pre- Existing Non Compliance* Conditions <br> <span style=" font-family:Helvetica; font-size:9px;font-weight: 400;">(Details of any non-compliance of the pre-existing plumbing installation on which work was done  that needs to be brought to the attention of owner/user)</span></h4>
                          </td>
                        </tr>
                        <tr>
                        <td style="height: 48px;font-family:Helvetica; vertical-align: top; font-size:10px;font-weight: 400; text-align: justify; padding: 2px 10px;">
                          Refer to Non-Compliance report attached below
                        </td>
                        </tr>
                      </table>
                      </div>
                  </div>

                  <div class="white_box" style="font-family:Helvetica;margin: 4px 0 0 0;">

                      <div class="block">
                      <table cellpadding="0" cellspacing="0" style="border-collapse: collapse; border-spacing: 0px">
                      <tr>
                      <td style="font-family:Helvetica; padding: 5px 10px;color: #000;font-size: 10px; text-align: justify;">I <small style="border-bottom: 2px dotted;">'.$userdata['name'].'&nbsp;'.$userdata['surname'].'</small> (Licensed Plumber\'s Name and Surname), Licensed registration number <small style="border-bottom: 2px dotted;padding-bottom: 5px;">'.$userdata['registration_no'].'</small>, certify that, the above compliance certificate details are true and correct and will be logged in accordance with the prescribed requirements as defined by the PIRB. I further certify that (as highlighted);
                      </td>
                      </tr>
                      </table>

                      </div>                      

                        

            '.$agreement.'



                      <div class="test">

                      <img src="" style="padding:5px 0;border-top:1px dashed #adafb1;font-family:Helvetica;">
                       <table cellpadding="0" cellspacing="0" style="border-collapse: collapse; border-spacing: 0px;">
                      <tr>
                          <td style="width: 100%;text-indent: 5px;padding: 5px 0;font-family:Helvetica;font-size:10px;border-right:none;">
                            This Certificate of Compliance has been electronically signed by means of a verification PIN sent to
						  <span class="brdr-dshed" style="border-left: none;padding: 5px 0;font-size: 10px;">
							<small style="border-bottom: 2px dotted;">'.$userdata['name'].' '.$userdata['surname'].', '.$userdata['registration_no'].', at '.$logdate.' '.$logtime.'</small>
						  </span>
						  </td>
                          </tr>
                          </table>

                      </div>

                      <div style=" font-family:Helvetica; clear:both;"></div>

                  </div>



                  <div class="notoice" style=" font-family:Helvetica; border:1px solid #adafb1; border-top: none; margin: 5px 0 0 0; background: #f3cfc1;">

                      <div class="text" style=" font-family:Helvetica; padding:2px 5px; background: #d2232a; text-align: center; color: #fff;">

                          <p style=" font-family:Helvetica; padding: 0; margin: 0; font-size:12px; line-height:15px; font-weight:700;">IMPORTANCE NOTICE</p>

                      </div>

  

                      <div class="text" style=" font-family:Helvetica; padding:2px 10px 0 5px;">

                          <span style=" font-family:Helvetica; padding: 0 0 0 0; float: left; width:3%;">
                          <img src="'.$roundimg.'" style=" font-family:Helvetica; margin: 3px 10px 0 0" width="3px" height="3px"></span>

                          <p style=" font-family:Helvetica; float: right;width: 97%; font-size:8px; color: #000; font-weight:400; padding: 0; margin: 0;">An incorrect statement of fact, including an omission, is an offence in terms of the PIRB Code of conduct, and will be subjected to PIRB disciplinary procedures.</p>

                          <div style=" font-family:Helvetica; clear:both;"></div>

                      </div>

                      <div class="text" style=" font-family:Helvetica; padding:2px 10px 0 5px;">

                          <span style=" font-family:Helvetica; padding: 0 0 0 0; float: left; width:3%;">
                          <img src="'.$roundimg.'" width="3px" height="3px" style=" font-family:Helvetica; margin: 4px 10px 0 0;"/>
                          </span>

                          <p style=" font-family:Helvetica; float: right;width: 97%; font-size:8px; line-height:10px; color: #000; font-weight:400; padding: 0; margin: 0;">The completed Certificate of Compliance must be provided to the owner/consumer within 5 days of the completion of the plumbing works and the details of the Certificate of Compliance must be logged electronically with the PIRB within that period.</p>

                          <div style=" font-family:Helvetica; clear:both;"></div>

                      </div>

                      <div class="text" style=" font-family:Helvetica; padding:2px 10px 0 5px;">

                          <span style=" font-family:Helvetica; float: left; width: 3%; padding: 0 0 0 0;">
                          <img src="'.$roundimg.'" width="3px" height="3px" style=" font-family:Helvetica; margin: 4px 10px 0 0;"/>
                          </span>

                          <p style=" font-family:Helvetica; float: right; width: 97%; font-size:8px; line-height:10px; color: #000; font-weight:400; padding: 0; margin: 0;">The relevant plumbing work that was certified as complaint through the issuing of this certificate may possibly be audited by a PIRB Auditor for compliance to the regulations, workmanship and health and safety of the plumbing.</p>
                          <div style=" font-family:Helvetica; clear:both;"></div>

                      </div>

                      <div class="text" style=" font-family:Helvetica; padding:2px 10px 0 5px;">

                        <span style=" font-family:Helvetica; float: left; width: 3%; padding: 0 0 0 0;">
                        <img src="'.$roundimg.'" width="3px" height="3px" style=" font-family:Helvetica;margin: 3px 10px 0 0;"/></span>

                          <p style=" font-family:Helvetica; float: right; width: 97%; font-size:8px;color: #000; font-weight:400; padding: 0; margin: 0;">If this Certificate of Compliance has been chosen for an audit you must cooperate fully with the PIRB Auditor in allowing them to carry out the relevant audit.</p>

                          <div style=" font-family:Helvetica; clear:both;"></div>

                      </div>

                      <div class="text" style=" font-family:Helvetica; padding:2px 10px 0 5px;">

                          <span style=" font-family:Helvetica; float: left; width: 3%; padding: 0 0 0 0;">
                          <img src="'.$roundimg.'"  width="3px" height="3px" style=" font-family:Helvetica; float:left;margin: 3px 10px 0 0;" /></span>

                          <p style=" font-family:Helvetica; float: right; width:97%; font-size:8px;color: #000; font-weight:400; padding: 0; margin: 0;">See reverse side of this Certificate of Compliance for further details </p>

                          <div style=" font-family:Helvetica; clear:both;"></div>

                      </div>

                  </div>

                  <div class="owners" style=" font-family:Helvetica; margin: 2px 0 0;">

                      <img style=" font-family:Helvetica; width: 100%; height: auto;" src="'.$ownersimg.'" />

                  </div>

              </div>

          </div>

      </div>

  

    

  <div style="page-break-after: always;"></div>

  <div id="wrapper" >

  <div class="certificate-block" style="margin-top:0px; ">

    <div class="container--pdf" style="border: 4px solid #00aeef; font-family:Helvetica; width:100%; margin: 0 auto; padding:15px 15px 15px 15px;  border-radius:20px;">

        <div class="title" style=" font-family:Helvetica; background: #d1e28d; text-align: center; color: #000; padding:4px 0; margin-bottom:4px;">

            <h3 style=" font-family:Helvetica; margin:0; margin:0; font-size:12px; line-height: 14px; font-weight: 700;">TERMS & CONDITIONS</h3>

        </div>

        


        <div class="lt-block" style=" vertical-align: top; left: 20px; top:30px; position: relative; font-family:Helvetica; text-align: justify;display: inline-block; width:48%;">

            <h3 style=" font-family:Helvetica; margin:0px 0 0 0; font-size:8px;text-align: left; color: #000; text-transform: uppercase; ">WHAT IS A PluMBINg CERTIfICATE Of COMPlIANCE (COC)?</h3>

            <p style=" font-family:Helvetica; font-size: 8px; line-height: 8px; padding: 0; margin: 2px 0px 1px 0; color: #000; font-weight: 400;text-align: justify;"> A  Plumbing  COC  is  a  means  by  which  the  Plumbing  Industry  Registration  Board  (PIRB) 

              licensed plumber self certifies that their work complies with all the current plumbing regulations and laws as defined by the National Compulsory Standards and Local Bylaws. COCs may only be purchased, and used by a registered and approved PIRB licensed persons and at the time of purchase, the COC is captured against the PIRB licensed plumber, and becomes their responsibility. Upon issuing of a COC the PIRB licensed plumber has to log the relevant COC into the PIRB\'s Plumbing audit/data management system within five days. Each day, a computer random selection of jobs for which a COC has logged with the PIRB, is selected for an audit. Upon which a PIRB auditor will be sent out to carry out the audit. If the installation is found to be incorrect or not up to standard the PIRB licensed plumber will be sent a rectification notice on which the licensed plumber will have to react within the specified period as by the auditor. This is usually 5 days.</p>

              <h3 style=" font-family:Helvetica; margin:2px 0 0px 0; font-size: 8px; line-height: 10px; color: #000; text-transform: uppercase; ">JOBS wHICH REqUIRE A COC</h3>

              <p style=" font-family:Helvetica; font-size: 8px; line-height: 10px; padding: 0; margin: 2px 0; color: #000; font-weight: 400;">COC must be provided to the consumer for all plumbing jobs which fall into one or more of the following categories:</p>

              <ul style=" margin: 2px 0; padding-left: 10px; font-family:Helvetica; font-size: 8px;color: #000; font-weight: 400;">

                    <li style=" font-family:Helvetica;margin-bottom: 5px; padding-left: 5px; line-height: 10px; font-size: 8px; padding-bottom:0px;">Where the total value of the work, including materials, labour and VAT, is more than the prescribed value as defined by the PIRB (material costs must be included, regardless of whether the materials were supplied by another person) a certificate must be issued for the following:</li><br>

                          <li style=" line-height: 10px; padding-left: 15px;font-family:Helvetica; font-size: 8px; margin-bottom: 2px;">When an Installation, Replacement and/or Repair of Hot Water Systems and/ or Components is carried out</li><br>

                          <li style="line-height: 10px; padding-left: 15px;font-family:Helvetica; font-size: 8px; margin-bottom: 2px;">When an Installation, Replacement and/or Repair of Cold Water Systems and/ or Components is carried out</li><br>

                          <li style="line-height: 10px; padding-left: 15px;font-family:Helvetica; margin-bottom: 2px; font-size: 8px;">Installation, Replacement and/or Repair of Sanitary-Ware and Sanitary-Fittings is carried out</li><br>

                          <li style="line-height: 10px; padding-left: 15px;font-family:Helvetica; margin-bottom: 2px;font-size: 8px;">Installation, Replacement and/or Repair of a Solar Water Heating System</li><br>

                          <li style="line-height: 10px; padding-left: 15px;font-family:Helvetica; margin-bottom: 2px; font-size: 8px;">Installation, Replacement and/or Repair of a Below-ground Drainage System</li><br>

                          <li style="line-height: 10px; padding-left: 15px;font-family:Helvetica; margin-bottom: 2px; font-size: 8px;">Installation, Replacement and/or Repair of an Above-ground Drainage System</li><br>

                          <li style="line-height: 10px; padding-left: 15px;font-family:Helvetica; margin-bottom: 2px; font-size: 8px;">Installation, Replacement and/or Repair of an Rain-Water Disposal System</li><br>

                          <li style="line-height: 10px; padding-left: 15px;font-family:Helvetica; margin-bottom: 2px; font-size: 8px;">Installation, Replacement and/or Repair of a Heat Pump Water Heating System</li><br>

                          <div style=" font-family:Helvetica; clear:both;"></div>

                    <li style=" font-family:Helvetica; line-height: 10px; padding-left: 5px;margin:0px 0px; font-size: 8px;">Any  work  that  requires  the  installation,  replacement  and/or  repair  of  any  of  an electrical/solar hot water cylinder valves or components must have a COC issued to the consumer regardless of the cost.</li><br>

                    </ul>

                    <div style=" font-family:Helvetica; clear:both;"></div>

                    <h3 style=" font-family:Helvetica; margin:2px 0 1px 0; font-size: 8px; line-height:8px; color: #000; text-transform: uppercase;">STAgE AT wHICH COC MUST BE COMPLETED</h3>

              <p style=" font-family:Helvetica; font-size: 8px; line-height: 10px; padding: 0; margin: 1px 0; color: #000; font-weight: 400; margin-bottom: 0px;">A completed COC must be provided to the consumer within <span style=" font-family:Helvetica; font-weight: 700;">5 DAYS</span> of the completion of the 

              plumbing work and the details of the COC must be logged electronically with the PIRB within that 

              period. A job is considered to be completed when the plumbing work is practically completed or 

              when plumbing work is capable of being used within an existing system - whichever comes first.</p>

              <h3 style=" font-family:Helvetica; margin:2px 0 1px 0; font-size: 8px; line-height: 10px; color: #000; text-transform: uppercase;">PROTECTION OF PERSONAL INFORMATION</h3>

              <p style=" font-family:Helvetica; font-size: 8px; line-height: 10px; margin:1px 0 0px; color: #000; font-weight: 400;  margin-bottom: 1px;">The PIRB respects your privacy and your personal information. Therefore, the information given and obtained on completion of this Certificate of Compliance (COC) will be used to ensure the validity of this COC and to carry out the services associated with this COC.</p>
        </div>

        <div class="rt-block" style="vertical-align: top; right: 20px; top:31px; position: relative; text-align: justify; padding-left: 10px; font-family:Helvetica; display: inline-block; width: 48%;">
            <h3 style=" font-family:Helvetica; margin:1px 0 0 0; font-size: 8px; line-height: 10px; text-transform: uppercase;">HOw COMPLIANCE CERTIFICATES MAY BE PURCHASED</h3>

            <p style=" font-family:Helvetica; font-size: 8px; line-height: 10px; padding: 0; margin: 1px 0;">Compliance Certificates may be purchased by licensed persons or authorized persons through any of the following methods:</p>
            <ul style=" font-family:Helvetica; font-size: 8px; line-height: 10px; color: #000; font-weight: 400; padding-left: 10px; margin: 1px 0 0 0;"> 
              <li style=" font-family:Helvetica; padding-bottom: 0px;font-size: 8px;padding-left: 10px;">
              <span style=" font-family:Helvetica; font-weight: 700;font-size: 8px;line-height: 10px;">Over the counter at the Plumbing Industry Registration Board offices.</span>Purchasers will need to present their current license card. Compliance certificates may only be given on-the spot where payment is by cash, credit card, bank transfer (confirmation required) and or bank cheque.
              </li>
              <li style=" font-family:Helvetica; font-size: 8px; padding-bottom: 0px;padding-left: 10px;">
              <span style=" font-family:Helvetica; line-height: 10px;font-size: 8px; font-weight: 700; display: block;">Online:</span>Purchasers should log on to www.pirb.co.za, click on \'Order COC\' and follow the prompts.
              </li>
              <li style=" font-family:Helvetica; font-size: 8px; padding-bottom: 0px;padding-left: 10px;">
              <span style=" font-family:Helvetica; font-weight: 700;font-size: 8px;line-height: 10px;">Resellers (paper COC\'s only): </span>The PIRB Licensed Plumber will need to present his/her current licensed card upon purchasing a compliance certificate from a participating reseller (merchant) outlet.  No third parties may purchase from a reseller unless preapproved and verified by the PIRB first.</li>
            </ul>

            <h3 style=" font-family:Helvetica; margin:1px 0 1px 0; font-size: 8px; line-height: 10px; color: #000; text-transform: uppercase;">DISPOSAL OF COMPLIANCE CERTIFICATES</h3>
            <p style=" font-family:Helvetica; font-size: 8px; line-height: 8px; padding: 0; margin: 1px 0; color: #000; font-weight: 400;">If for any reason, a licensed person does not intend to use a compliance certificate for its intended purpose they should return it to the PIRB office and, if all is found to be in order, a refund could be arranged. If a licensed person has a compliance certificate stolen or loses a compliance certificate, he should report it immediately to the PIRB in the form of a statutory declaration. </p>

            <h3 style=" font-family:Helvetica; margin:1px 0 1px 0; font-size: 8px; line-height: 10px; color: #000; text-transform: uppercase;">THE PURPOSE OF AN AUDIT</h3>
            <p style=" font-family:Helvetica; font-size: 8px; line-height: 10px; padding: 0; margin: 1px 0; color: #000; font-weight: 400;">Audits  are  conducted  to  provide  a  measure  of  the  standard  of  the  plumbing  work  being performed across the country. The aim is to ensure a correct and consistent application of the standards is reflected in the work done.</p>

            <h3 style=" font-family:Helvetica; margin:1px 0 0px 0; font-size: 8px; line-height: 10px; color: #000; text-transform: uppercase; ">AUDIT PROCESS</h3>
            <p style=" font-family:Helvetica; font-size: 8px; line-height: 10px; padding: 0; margin: 0px 0; color: #000; font-weight: 400;">A computer random selection of COC for which a compliance certificate logged with the  PIRB,  is  selected  for  an  audit. Audits  are  conducted  by  qualified,  experienced,  trained, plumbers and experts authorized by the PIRB to perform the function. PIRB Plumbing Auditors are registered with the PIRB and carry identification cards. When one of your COC has been selected for an audit you will be contacted by the PIRB Auditor. You will be asked for details of where the work was performed and arrangements will be made by the Auditor with the relevant consumer. You will be requested by the Auditor to attend the audit.</p>

            <h3 style=" font-family:Helvetica; margin:2px 0 1px 0; font-size: 8px; line-height: 10px; color: #000; text-transform: uppercase; ">wHAT HAPPENS IF MY wORK DOES NOT PASS AN AUDIT?</h3>
            <p style=" font-family:Helvetica; font-size: 8px; line-height: 10px; margin: 1px 0; padding: 0; color: #000; font-weight: 400;">If the audited work is found not to comply, you will be advised of the work requiring attention in the form of a Rectification Notice. You are required to rectify the work in the time period specified by the auditor. This is usually 5 days. The work may then be re-audited.  Failure to respond, act or co-operate will result in disciplinary procedures</p>

            <h3 style=" font-family:Helvetica; margin:2px 0 1px 0; font-size: 8px; line-height: 10px; color: #000; text-transform: uppercase;">IF YOU DISAgREE wITH AN AUDIT RESULT</h3>
            <p style=" font-family:Helvetica; font-size: 8px; line-height: 10px; padding: 0; margin: 1px 0; color: #000; font-weight: 400;">If you believe  that the rectification notice is incorrect, you may contact the PIRB and your objection will be reviewed. Objections must be submitted in writing on the relevant PIRB form, obtainable from PIRB\'s office. </p>
        </div>
      

        <div style=" font-family:Helvetica; clear:both;"></div>
        <div class="type" style="font-family:position: relative; Helvetica; width: 100%; font-size: 10px;font-weight: 400;margin-top: 10px;">
          <table cellpadding="0" cellspacing="0">
            <tr style="text-align: center;">
              <th style=" font-family:Helvetica;font-size: 12x;font-weight: 400; color: #000;">Code</th>
              <th style=" font-family:Helvetica;background: #d1e28d;font-size: 10px;font-weight: 700; color: #000;">Type of Installation Carried Out:</th>
            </tr>

            <tr>
              <td style="text-align: center; color: #d64349; font-weight: bold;">01</td>
              <td style="font-size: 8px;padding: 2px;background: #fcf2f1;">Installation, Replacement and/or Repair of a <span style=" font-family:Helvetica; color:#d2232a; font-weight: 700;">Hot Water System and /or Components</span>
              <p style=" font-family:Helvetica; font-size: 8px;padding:0; margin: 0;">(A Certificate of Compliance is to be issued for  the  installation,  replacement  and/or  repair  of any plumbing work carried out on the hot water reticulation system upstream of the pressure regulating valve, which shall include but not be limited to: the pressure regulating valve; an electrical hot water cylinder; all relevant valves and components and all hot water pipe and fittings, and shall end at any of the hot water terminal fittings;  but shall exclude  any sanitary  fittings,  solar and heat pump installations. The scope of work and non-compliance on pre-existing installations by others must be clearly noted in the installation details provided overleaf.)</p>
              </td>
            </tr>

            <tr>
              <td style="text-align: center; color: #31b8f0; font-weight: bold;">02</td>
              <td style="font-size: 8px;padding: 2px;background: #edf9fb;">Installation, Replacement and/or Repair of a <span style=" font-family:Helvetica; color:#00aeef; font-weight: 700;">Cold Water System and /or Components</span>
              <p style=" font-family:Helvetica; font-size: 8px;padding:0; margin:0;">(A Certificate of Compliance is to be issued for the installation, replacement and/or repair of any plumbing works where work has been carried out on the cold water reticulation system upstream of the municipal metering valve, which shall include but not be limited to: all relevant valves and components relating to the cold water system and all cold water pipe and fittings, and shall end at any of the relevant cold water terminal fittings; but shall exclude any sanitary fittings. The scope of work and any non-compliance pre-existing installations by others must be clearly noted in the installation details provided overleaf.)</p>
              </td>
            </tr>

            <tr>
              <td style="text-align: center;color: #7869a0; font-weight: bold;">03</td>
              <td style="font-size: 8px;padding: 2px;background: #f1f0f6;">Installation, Replacement and/or Repair of a <span style=" font-family:Helvetica; color:#5b579c; font-weight: 700;">Sanitary-ware and Sanitary-fittings</span>
               <p style=" font-family:Helvetica; font-size: 8px;padding: 0; margin: 0;">(A Certificate of Compliance is to be issued for the installation, replacement and/or repair of any plumbing works r where work has been carried out on the Sanitary- ware and Sanitary-fittings. The scope of work and any non-compliance pre-existing installations by others must be clearly noted in the installation details provided overleaf.)</p>
              </td>
            </tr>

            <tr>
              <td style="text-align: center;color: #f17f3d; font-weight: bold;">04</td>
              <td style="font-size: 8px;padding: 2px;background: #fef5ee;">Installation, Replacement and/or Repair of a <span style=" font-family:Helvetica; color:#f36f21; font-weight: 700;">Solar Water Heating System</span>
              <p style=" font-family:Helvetica; font-size: 8px;padding: 0; margin: 0;">(A Certificate of Compliance is to be issued for the installation, replacement and/or repair of any plumbing works where work has been carried out on the Solar Water Heating System which shall include but not be limited to: the hot water reticulation system upstream of the pressure regulating valve; the pressure regulating valve; if applicable the electrical hot water cylinder; a solar (electrical) hot water cylinder; all relevant valves and components and all hot water pipe and fittings, and shall end at any of the relevant hot water terminal fittings; but shall exclude any sanitary fittings. The scope of work and non-compliance pre-existing installations by others must be clearly noted in the installation details provided overleaf.) <span style=" font-family:Helvetica; color: #d2232a; font-weight: 700; font-size: 8px;">work can only be undertaken by a Licensed Plumber registered to do this specialised work.</span></p>
              </td>
            </tr>

            <tr>
              <td style="text-align: center;font-weight: bold;color: #835d53;">05</td>
              <td style="font-size: 8px;padding: 2px;background: #f6f1ee;">Installation, Replacement and/or Repair of a <span style=" font-family:Helvetica; color:#7d4e35; font-weight: 700;">Below-ground Drainage System</span>
              <p style=" font-family:Helvetica; font-size: 8px;padding: 0; margin: 0;">(A Certificate of Compliance is to be issued for the installation, replacement and/or repair of any plumbing works where work has been carried out on a below- ground drainage system, which shall include but not be limited to: septic tank and French drain installations. The scope of work and any non-compliance pre-existing installations by others must be clearly noted in the installation details provided overleaf.)</p>
              </td>
            </tr>

            <tr>
              <td style="text-align: center;font-weight: bold;color: #64b658;">06</td>
              <td style="font-size: 8px;padding: 2px;background: #f4f9f3;">Installation, Replacement and/or Repair of a <span style=" font-family:Helvetica; color:#41ad49; font-weight: 700;">Above-ground Drainage System</span>
              <p style=" font-family:Helvetica; font-size: 8px;padding: 0; margin: 0;">(A Certificate of Compliance is to be issued for the installation, replacement and/or repair of any plumbing works where work has been carried out on an above-ground drainage system, which shall include but not be limited to: all internal and external waste water and soil drainage but shall excluded any sanitary ware fixtures. The scope of work and any non-compliance pre-existing installations by others must be clearly noted in the installation details provided overleaf.)</p>
              </td>
            </tr>

            <tr>
              <td style="text-align: center;font-weight: bold;color: #2d4e84;">07</td>
              <td style="font-size: 8px;padding: 2px;background: #e8ebf0;">Installation, Replacement and/or Repair of a <span style=" font-family:Helvetica; color:#00386c; font-weight: 700;">Rain Water Disposal System</span>
              <p style=" font-family:Helvetica; font-size: 8px; padding: 0; margin: 0;">(A Certificate of Compliance is to be issued for the installation, replacement and/or repair of any plumbing works i or where work has been carried out on a rain water disposal system, which shall include but not be limited to: storm water drainage, guttering and flashing. The scope of work and any non-compliance pre-existing installations by others must be clearly noted in the installation details provided overleaf.)</p>
              </td>
            </tr>

            <tr>
              <td style="text-align: center;font-weight: bold;color: #903a2e;">08</td>
              <td style="font-size: 8px;padding: 2px;background: #f4ebe4;">Installation, Replacement and/or Repair of a <span style=" font-family:Helvetica; color:#790000; font-weight: 700;">Heat Pump</span>
              <p style=" font-family:Helvetica; font-size: 8px;padding: 0; margin: 0;">(A Certifcate of Compliance is to be issued for the installation, replacement and/or repair of any plumbing works where work has been carried out on the Heat Pump Water Heating System which shall include but not be limited to: the hot water reticulation system upstream of the pressure regulating valve; the pressure regulating valve; if applicable the electrical hot water cylinder; a heat pump unit; all relevant valves and components and all hot water pipe and lttings, and shall end at any of the relevant hot water terminal fittings; but shall exclude any sanitary fttings. The scope of work and non-compliance pre-existing installations by others must be clearly noted in the installation details provided overleaf.) <span style=" font-family:Helvetica; color: #d2232a; font-weight: 700; font-size: 8px;">work can only be undertaken by a Licensed Plumber registered to do this specialised work.</span></p>
              </td>
            </tr>
          </table>
        </div>

    </div>

  </div>

  </div>

  <table class="page_overall_auditreport" style="page-break-before: always; border:none;">
  <tbody style=" border:none;">

    <tr>
      <td>
        <table class="head-all" style="margin-bottom: 0 !important; padding-bottom: 0 !important;">
          <tbody>
            <tr>
              <td><h2  style="padding-left: 5px">NOTICE OF NON-COMPLIANCE</h2></td>
              <td style="text-align: right; width:250px;"><img width="200px" src='.$logoimg.'></td>                   
            </tr>
          </tbody>
        </table>
      </td>
    </tr>

    <tr>
      <td style="border-bottom: 1.5px solid #000">
        <table class="notice-license-text" style="margin-bottom: 0;">
          <thead>
            <tr>
            <th style="text-align: center;"><h3>NOTICE TO HOME OWNERS</h3></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td style="padding: 5px 15px; text-align: justify; font-size: 15px;">In terms of the, Water Services Act of 8 June 2001; Occupational Health & Safety Act (Pressure Equipment Regulations, 2009); the National Standard SANS 10254 (The Installation, Maintenance, Replacement and Reparation of Fixed Electric Storage Water Heating Systems); the National Standard SANS 1352 (The Installation, Maintenance, Replacement and Reparation of Domestic Air Source Water Heating Heat Pump Systems); the National Standard SANS 10106 (The Installation, Maintenance, Replacement and Reparation of Domestic Solar Water Heating Systems); as well as Section 58 of the Consumer Protection Act, any aspect of your plumbing installation that does not comply with the latest requirements of the above-mentioned national standards and legislation must be notified in writing to the user/owner by the relevant Licensed Plumber. THIS NOTICE SERVES NOTIFY TO YOU OF SUCH NON-COMPLIANCES.  Unless otherwise stated, this inspection is a visual inspection of component(s) and part(s) of your plumbing system, as listed.  These non-compliances are reasonably visible and capable of being inspected without creating damage(s).  Although the applicable plumbing at your premises may have been compliant at the time that it was installed, changes to the above-mentioned requirements are made from time to time for improvement and to minimise any potential risks. In doing so, it ensures that your safety, as the consumer, is the highest priority.</td>             
            </tr>
          </tbody>
        </table>
      </td>
    </tr>

    <tr> 
      <td style="border:none;">
        <table class="coc_details_overall" style="margin-bottom: 0; border:none;">
          <tbody>
          <tr> 
            <td style="padding: 0; border:none; padding-left: 5px"> <h3 style="margin: 20px 0 10px">COC DETAILS</h3> </td>
          </tr>

          <tr>
            <td style="padding: 0;padding-left: 5px"><label>Certificate No</label></td>
            <td style="padding-left: 5px"><label>Plumbing Work Completion Date</label></td>
            <td style="padding-left: 5px"><label>Owners Name</label></td>
          </tr>

          <tr>
            <td style="padding: 0;padding-left: 5px"><span class = "nc-td-cls">'.$cocid1.'</span></td>
            <td style="padding-left: 5px"><span class = "nc-td-cls">'.$completiondate1.'</span></td>
            <td style="padding-left: 5px"><span class = "nc-td-cls">'.$name.'</span></td>
          </tr>           

          <tr>
            <td style="padding: 0;padding-left: 5px"><label>Name of Complex/Flat (if applicable)</label></td>
            <td style="padding-left: 5px"><label>Street</label></td>
            <td style="padding-left: 5px"><label>Number</label></td>
          </tr>

          <tr>
          
            <td style="padding: 0;padding-left: 5px"><span class = "nc-td-cls">'.$address.'</span></td>
            <td style="padding-left: 5px"><span class = "nc-td-cls">'.$street.'</span></td>
            <td style="padding-left: 5px"><span class = "nc-td-cls">'.$number.'</span></td>
          </tr>

          <tr>
            <td style="padding: 0;padding-left: 5px"><label>Province</label></td>
            <td style="padding-left: 5px"><label>City</label></td>
            <td style="padding-left: 5px"><label>Suburb</label></td>
          </tr>

          <tr>
            <td style="padding: 0;padding-left: 5px"><span class = "nc-td-cls">'.$province.'</span></td>
            <td style="padding-left: 5px"><span class = "nc-td-cls">'.$city.'</span></td>
            <td style="padding-left: 5px"><span class = "nc-td-cls">'.$suburb.'</span></td>
          </tr>
            
          </tbody>
        </table>
      </td>
    </tr>
    

    <tr>
      <td style="border:none;">
        <table class="auditor_details_overall" style="border:none; margin-bottom: 77px;">
          <tbody>
            <tr>
              <td colspan="3" style="padding-left: 6px;"><h3>PLUMBERS DETAILS</h3></td>
            </tr>
            <tr>
              <td style="padding: 0;padding-left: 5px"><label>Plumbers Name and Surname</label></td>
              <td style="padding-left: 5px"><label>Company of Plumber</label></td>
              <td style="padding-left: 5px"><label>Company Contact (work number)</label></td>
            </tr>
            <tr>
              <td style="padding: 0;padding-left: 5px"><span class = "nc-td-cls">'.$plumbername.'</span></td>
              <td style="padding-left: 5px"><span class = "nc-td-cls">'.$plumbercompany.'</span></td>
              <td style="padding-left: 5px"><span class = "nc-td-cls">'.$plumberwork.'</span></td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>

  </tbody>
</table>
<h3 style="margin: 10 0 20px;" class="audit-table-heading">NON COMPLIANCES</h3>
<table class="table table-bordered reviewtable">    
  <thead>
    <tr>
      <th>Non Compliance Details</th>
      <th>Possible remedial actions</th>
      <th>SANS/Regulation/Bylaw Reference</th>
    </tr>
  </thead>
  <tbody>';
  ?>
 <?php foreach($noncompliance as $list){ ?>
    <tr>
      <td><?php echo $list['details']; ?></td>
      <td><?php echo $list['action']; ?></td>
      <td><?php echo $list['reference']; ?></td>
    </tr>
    <?php if($list['file']!=''){ ?>
      <tr>
        <td colspan="3">
          <?php 
            $filelist = array_filter(explode(',', $list['file'])); 
            $i=1;
            foreach($filelist as $file){
              if(!file_exists('./assets/uploads/plumber/'.$plumberid.'/log/'.$file)) continue;
              $plumberimg = base64conversion(base_url().'assets/uploads/plumber/'.$plumberid.'/log/'.$file);
          ?>
              <p style="display:inline-block;margin-right:10px;margin-bottom:10px;"><img src="<?php echo $plumberimg; ?>" width="215" height="200"></p>
          <?php
              if($i%3==0) echo '<br>';
              $i++;
            }
          ?>
        </td>
      </tr>
    <?php } ?>      
  <?php } ?>
<?php
  echo '
  </tbody>                            
</table>
  <h5 style="    padding: 0 15px 0 15px;">DISCLAIMER: This document was developed by the PIRB to assist plumbers in providing a Non-Compliance notice.  The responsibility for notifying the user & owner, lies with the licenced plumber. This document simply is a guide and is not exhaustive.  </h5>
  </body>

</html>';
?>