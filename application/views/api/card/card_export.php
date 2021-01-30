<?php
if (!function_exists('imageconversion')) {
    function imageconversion($path){
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
}
    $userid                 =isset($result['id']) ? $result['id'] : '';

    $name                   = isset($result['name']) ? $result['name'] : '';
    $surname                = isset($result['surname']) ? $result['surname'] : '';
    
    $designation2id         = isset($result['designation']) ? $result['designation'] : '';
    $registration_no        = isset($result['registration_no']) ? $result['registration_no'] : '';
    $registration_date      = isset($result['registration_date']) && $result['registration_date']!='1970-01-01' ? date('d-m-Y', strtotime($result['registration_date'])) : '';
    $renewal_date           = date('d-m-Y', strtotime($result['expirydate']));
    $specialisationsid      = isset($result['specialisations']) ? array_filter(explode(',', $result['specialisations'])) : [];
    $companyname            = isset($result['companyname']) ? $result['companyname'] : '';

    $work_phone             = isset($settings['work_phone']) ? $settings['work_phone'] : '';

    $filepath               = base_url().'assets/uploads/plumber/'.$userid.'/';
    $pdfimg                 = imageconversion(base_url()."assets/images/pdf.png");
    $profileimg             = imageconversion(base_url()."assets/images/profile.jpg");
    $logo                   = imageconversion(base_url()."assets/images/card/logo.png");
    $spl1                   = imageconversion(base_url()."assets/images/card/Solar.png");
    $spl2                   = imageconversion(base_url()."assets/images/card/Gas.png");
    $spl3                   = imageconversion(base_url()."assets/images/card/Estimator.png");
    $spl4                   = imageconversion(base_url()."assets/images/card/Heatpump.png");
    $spl5                   = imageconversion(base_url()."assets/images/card/Training_Assessor.png");
    $spl6                   = imageconversion(base_url()."assets/images/card/Arbitrator.png");
    $backcard               = imageconversion(base_url()."assets/images/card/back-card/Above-Ground-Drainage-Icon.png");
    $backcard1              = imageconversion(base_url()."assets/images/card/back-card/Below-Ground-Drainage-Icon.png");
    $backcard2              = imageconversion(base_url()."assets/images/card/back-card/Cold-Water-Icon.png");
    $backcard3              = imageconversion(base_url()."assets/images/card/back-card/Drainage-Icon.png");
    $backcard4              = imageconversion(base_url()."assets/images/card/back-card/Gas.png");
    $backcard5              = imageconversion(base_url()."assets/images/card/back-card/Heatpump.png");
    $backcard6              = imageconversion(base_url()."assets/images/card/back-card/Hot-Water-Icon.png");
    $backcard7              = imageconversion(base_url()."assets/images/card/back-card/Rainwater-Disposal-Icon.png");
    $backcard8              = imageconversion(base_url()."assets/images/card/back-card/Solar.png");
    $backcard9              = imageconversion(base_url()."assets/images/card/back-card/Water-Energy-Efficiency-Icon.png");


    $file2                  = isset($result['file2']) ? $result['file2'] : '';
    if($file2!=''){
        $explodefile2   = explode('.', $file2);
        $extfile2       = array_pop($explodefile2);
        $photoidimg     = (in_array($extfile2, ['pdf', 'tiff'])) ? $pdfimg : $filepath.$file2;
    }else{
        $photoidimg     = $profileimg;
    }
    
    $filepath               = base_url().'assets/uploads/plumber/'.$userid.'/';
    $pdfimg                 = base_url().'assets/images/pdf.png';
    $profileimg             = base_url().'assets/images/profile.jpg';
    $photoidimg_con         = imageconversion($photoidimg);

    $cardcolor = ['1' => 'learner_plumber', '2' => 'technical_assistant', '3' => 'technical_operator', '4' => 'licensed_plumber', '5' => 'qualified_plumber', '6' => 'master_plumber'];
    
    if ($designation2[$designation2id] == 'Learner Plumber') {
        $plumber_icon = imageconversion(base_url()."assets/images/card/Learner_plumber.png");

    }elseif($designation2[$designation2id] == 'Technical Assistant'){
        $plumber_icon = imageconversion(base_url()."assets/images/card/Technical_Assistant.png");

    }elseif($designation2[$designation2id] == 'Technical Operator'){
        $plumber_icon = imageconversion(base_url()."assets/images/card/Technical_Operator.png");

    }elseif($designation2[$designation2id] == 'Licensed Plumber'){
        $plumber_icon = imageconversion(base_url()."assets/images/card/licensed_plumber.png");

    }elseif($designation2[$designation2id] == 'Qualified Plumber'){
        $plumber_icon = imageconversion(base_url()."assets/images/card/licensed_plumber.png");

    }elseif($designation2[$designation2id] == 'Master Plumber'){
        $plumber_icon = imageconversion(base_url()."assets/images/card/Master-plumber.png");
    }
    $sitelogoimg = imageconversion(base_url()."assets/images/pitrb-logo.png");

    // foreach($specialisationsid as $specialisationsdata){
    //     echo $specialisations[$specialisationsdata];
    // }die;
    
?>
<!DOCTYPE html>
<html>
<head>
    <script src="<?php echo base_url().'assets/plugins/jquery/jquery-3.2.1.min.js?version=5.0'; ?>"></script>
      <meta name="viewport" content="width=device-width, initial-scale=1">

    <title></title>

    <style>
   
/* card back */
.pb_inner {
        display: block;
    width: 100%;
    margin: 0 auto;
    top: 25px;
        padding-top: 0px;
    position: relative;
}

.pb_rotate {
    transform: revert;
}

.cur_em {
    padding-top: 3px;
}

.add_top_value.master_plumber.pb_inner.pb_rotate{
    margin-top: 0px;
}

.pb_card {
        padding-right: 0px;
    padding-left: 15px;
    overflow: hidden;
    height: auto;
    width: 400px;
    margin: 0 auto;
    position: relative;
    top: auto;
    background: #F5F8FD;
    padding: 20px 0 20px 0;
    font-family: 'Helvetica' !important;
    transform: scale(1.65);
    display: inline-block;
  }
.p_top {
   width: 92%;
    border-bottom: 1px solid #737476;
    padding: 0px 0px 0px 22px;
    margin: 0 0 0 -16px;
    height: auto;
        min-height:auto;
    position: relative;
    display: inline-block;
    top: 0px;
    float: left;
    
} 
.pb_first_txt {
   font-size: 12px;
    text-align: center;
    width: 90%;
    margin-bottom: 10px;
}

p.add_width.img-txt {
    margin-left: 6px;
}

.pb_title {
       font-weight: bold;
    font-size: 6px !important;
}

.pb_bottom_left {
        border-right: 1px solid #737476;
    width: 50%;
    padding: 0;
    height: 90px;
    font-size: 8px !important;
    display: inline-block;
    /*float: left;*/
    vertical-align: top;

}

.back_seccdiv {
    display: inline-block;
    width: 100%;
        height: auto;
}

.pb_right {
       width: 48%;
    display: inline-block;
    /*float: right;*/
    vertical-align: top;
}

.pb_lost {
       padding: 3px 0 0 15px;
       width: auto;
       padding-right: 0 !important;
       margin-right: 0 !important;
}

.pb_p {
 font-size: 8px;
    margin-left: -15px;
    padding-left: 15px;
    margin-top: 0px;
    padding-bottom: 0px;
    width: auto !important;
    display: inline-block;
}

.bto_fot {
    border-top: 1px solid #737476;
    padding-left: 12px;
        position: relative;
}


.pb_bar {
  position: absolute;
    width: 26px;
    background: #ff8000;
    top: 0;
    right: 0;
    height: 362px;
}
p.plumber_name.add_style{
       top: 0;
    position: initial;
    right: 0;
    margin: 0 0;
    font-size: 10px;
    width: auto;
    min-height: auto;
    margin-top: 8px;

}

.cur_em .pb_title {
    font-size: 10px !important;
}

h2.pb_lic {
  position: absolute;
  top: 100px;
  transform: rotate(-90deg);
  right: -73px;
    font-size: 23px;
  color: white;
}
.p_top span img {
    width: 13px;
}


.p_top span {
font-size: 9px;
    letter-spacing: 0.2px;
    margin: 0 0 0 2px;
}
.p_bot_img{
    width: 15px;
}

.pb_right p {
       font-size: 9px !important;
    margin-bottom: 0px !important;
    margin-top: 2px !important;
    margin-left: 12px !important;
    width: 70%;
}

.add_width.img-txt span{
    /*clear: both;*/
}

.add_width.img-txt span:nth-child(4n + 1) {
    clear: both;
    display: inline-block;
}

.abve--img {
    width: auto;
    padding-right: 0px;
    margin-bottom: 6px !important;
    display: inline-block;
}

span.txt-img-card.abve-grnd--ttle {
      margin-left: 0;
    margin-right: 0;
    width: auto;
    /*padding-left: 7px;
    padding-right: 7px;*/
    vertical-align: top;
    align-items: center;
    display: inline-block;
}

.docicon {
    width: 48%;
    display: inline-block;
}

p.plumber_name.add_style:after {
    content: "";
    display: none;
    border-bottom: 1px solid #00000073;
    position: absolute;
    left: 23px;
    width: 47%;
    top: 6.5px;
    height: 13px;
}


/*  CPD */
    .cpd_tab_img img {
    width: 100%;
    height: auto;
}

.row.cPd_table {
    border: 1px solid;
        width: 100%;
    margin: 0;
}

.head_expan {
    width: 100%;
    padding: 18px;
    background-color: #7c7c7c;
    color: #fff;
    border: 1px solid #000;
    cursor: pointer;
}

h3.cpd_head {
    width: 100%;
    padding-bottom: 20px;
    margin-bottom: 20px;
    border-bottom: 1px solid #eaecee;
}

.tab_right {
    background-color: #afabab;
    width: 100%;
    padding: 10px;
    border-left: 1px solid;
}

.cpd_tab_img {
    padding: 0;
}

h4.cpd_sub_hed {
    display: inline-block;
}

p.cus_cpd_para {
    margin-bottom: 0;
}

p.cus_cpd_para span {
    font-weight: 700;
}

.fa-arrow-up:before {
content: "\f062";
    display: inline-block;
    font-size: 41px;
}

p.pb_p.pb_lost{
  border-top: none !important;
}

i.fa.fa-arrow-up {
    float: right;
}

.head_expan.open i.fa.fa-arrow-up {
    transform: rotate(180deg);
}


 /* card front */
.p_card{
        height: auto;
        width: 400px;
        position: relative;
        /*background: #F5F8FD;*/
        padding: 0;
        font-family: 'Helvetica' !important;
        transform: scale(1.65);
        margin: 0 auto;
        /*padding: 30px 0 30px 0;*/
}
.p_admin{
         width: 75px;
         height: 86px;
         position: relative;
         object-fit: cover;
}
.p_card p{
    margin-bottom: 20px;
    font-size: 12px !important;
    margin-top: 0;
}
.p_logo{
 width: 160px;
 height: auto;
 margin-bottom: 0px;
 padding-top: 0px;
}
.p_left{
    width: 70%;
}
.p_right{
    width: 30%;
}
.p_profile{
        width: 25%;
        height: 150px;
        position: relative;
        display: inline-block;
        float: right;
        margin-right: 19px;
}
.p_bottom{
  background: #ff8000;
    position: initial;
    width: 100%;
    left: 0;
    bottom: 0px;
    color: white;
    text-align: center;
    height: 55px;
    margin-top: 0px;
}
.inner {
    width: 100%;
    display: block;
    margin: 0 auto;
    position: relative;
    top: 40px;
}

div#card_back {
    margin: 70px 0;
}


div#card_front {
    margin: 70px 0;
}

.rotate {
    transform: revert;
}
.p_lic{
     position: absolute;
    background: white;
    width: 43px;
    top: auto;
    object-fit: contain;
    left: 0;
    margin-left: 33px;
}
.p_h2{
          width: 100%;
    padding: 0;
    font-size: 23px;
    float: right;
    text-align: right;
    margin-right: 19px;
    margin-top: 12px;
}

.log_ren {
        width: 62%;
    display: inline-block;
    position: relative;
    margin-left: 19px;
    margin-bottom: 20px;

}

.frca_top{
    background: #F5F8FD;
    padding: 40px 0 40px 0;
}

.pr_img {
    width: 75px;
    height: 86px;
    margin: 0 auto;
    text-align: center;
    border: 5px solid #ff8000;
    margin-top: 12px;
    margin-bottom: 15px;
}

.master_plumber .pr_img{
border: 5px solid #ff8000;
}

.log_ren p {
    font-size: 12px !important;
    width: 170px;
        margin-top: 20px;
    letter-spacing: 0px;
}

.log_ren p:first-child {
    margin-bottom: 20px !important;
}

.displaynone {
    display: none !important;;
}

.pr_img.licensed_plumber {
    border: 5px solid #e80000;
}

.p_bottom.licensed_plumber {
    background: #e80000;
}

.pb_bar.licensed_plumber {
    background: #e80000;
}

.licensed_plumber h2.pb_lic {
    right: -85px !important;
}

.pr_img.qualified_plumber {
    border: 5px solid #804000;
}

.p_bottom.qualified_plumber {
    background: #804000;
}

.pb_bar.qualified_plumber {
    background: #804000;
}

.qualified_plumber h2.pb_lic {
    right: -85px !important;
}


.pr_img.technical_operator {
    border: 5px solid #800080;
}

.p_bottom.technical_operator {
    background: #800080;
}

.pb_bar.technical_operator {
    background: #800080;
}

.technical_operator h2.pb_lic {
    right: -90px !important;
}

span.txt-img-card {
    vertical-align: super;
}

.pr_img.learner_plumber {
    border: 5px solid #009b00;
}

.p_bottom.learner_plumber {
    background: #009b00;
}

.pb_bar.learner_plumber {
    background: #009b00;
}

.pr_img.technical_assistant {
    border: 5px solid #0000ff;
}

.p_bottom.technical_assistant {
    background: #0000ff;
}

.pb_bar.technical_assistant {
    background: #0000ff;
}

.technical_assistant .pb_card .p_top {
    width: 90%;
    text-align: center;
    border-bottom: none;
}

.technical_assistant .pb_bottom_left {
    width: 100%;
    padding-top: 70px;
    border-right: none;
    position: static;
    display: inline-block;
}

.technical_assistant .cur_em {
    text-align: left;
    border-right: none;
}

.technical_assistant p.plumber_name.add_style {
    display: inline-block;
    position: relative;
    margin-top: 0;
    vertical-align: super;
    text-align: center;
    width: 40%;
}

.technical_assistant .cur_em .pb_title {
    display: inline-block;
    width: auto;
    position: relative;
}

.technical_assistant .bto_fot {
     border-top: none;
    padding-top: 0px;
    display: inline-block;
    margin-top: 30px;
    width: auto;
    bottom: 20px;
    padding-bottom: 30px;
}

.technical_assistant .pb_card {
    height: 250px !important;
}

.technical_assistant .p_card {padding: 0 0 6px 0 !important; top: 0px !important;}

.technical_assistant .pb_card {
    top: 80px;
    padding-top: 30px 0 20px 0 !important;
}

.technical_assistant .frca_top {
    padding: 30px 0 50px 0 !important;
}

.technical_assistant p.pb_p.pb_lost {
    display: block;
    margin-bottom: 0;
    width: 40% !important;
}

.technical_assistant .pb_card img.p_lic {
    margin-left: 0;
    position: absolute;
    top: 170px;
    right: 20px;
    left: auto;
    background-color: transparent;
}

.technical_assistant h2.pb_lic {
  right: -93px;
    top: 96px;
}
.technical_assistant .pb_right {
    display: none;
}

.learner_plumber .pb_card .p_top {
    width: 90%;
    text-align: center;
    border-bottom: none;
}

.learner_plumber .pb_bottom_left {
    width: 100%;
    padding-top: 70px;
    border-right: none;
    position: static;
    display: inline-block;
}

.learner_plumber .cur_em {
    text-align: left;
    border-right: none;
}

.learner_plumber p.plumber_name.add_style {
    display: inline-block;
    position: relative;
    margin-top: 0;
    vertical-align: super;
    text-align: center;
    width: 40%;
}

.learner_plumber .cur_em .pb_title {
    display: inline-block;
    width: auto;
    position: relative;
}

.learner_plumber .bto_fot {
     border-top: none;
    padding-top: 0px;
    display: inline-block;
    margin-top: 30px;
    width: auto;
    bottom: 20px;
    padding-bottom: 30px;
}

.learner_plumber .pb_card {
    height: 250px !important;
}

.learner_plumber .p_card {padding: 0 0 6px 0 !important; top: 0px !important;}

.learner_plumber .pb_card {
    top: 80px;
    padding-top: 30px 0 20px 0 !important;
}

.learner_plumber .frca_top {
    padding: 30px 0 50px 0 !important;
}

.learner_plumber p.pb_p.pb_lost {
    display: block;
    margin-bottom: 0;
    width: 40% !important;
}

.learner_plumber .pb_card img.p_lic {
    margin-left: 0;
    position: absolute;
    top: 170px;
    right: 20px;
    left: auto;
    background-color: transparent;
}

.learner_plumber h2.pb_lic {
    right: -79px;
    top: 96px;
}
.learner_plumber .pb_right {
    display: none;
}

@page{
    size: 500pt 360pt; 
}

    </style>
</head>

<body>
  

   <div class="add_top_value <?php echo (isset($cardcolor[$designation2id]) ? $cardcolor[$designation2id] : ''); ?>  inner rotate">
      <div class="p_card">
    <div class="frca_top">
    <div class="log_ren">
         <img class="p_logo" src="<?php echo $sitelogoimg;?>">
         <p>Reg No: <?php echo ($registration_no!='') ? $registration_no : '-'; ?></p>
         <p>Renewal Date: <?php echo $renewal_date; ?></p>
    </div>
    <div class="p_profile">
            <div class="pr_img <?php echo (isset($cardcolor[$designation2id]) ? $cardcolor[$designation2id] : ''); ?>"><img class="p_admin" src="<?php echo $photoidimg_con; ?>"></div>
            <p style="text-align:center"><?php echo $name.' '.$surname; ?></p>
    </div>
    </div>
    <div class="p_bottom <?php echo (isset($cardcolor[$designation2id]) ? $cardcolor[$designation2id] : ''); ?>">
            <img class="p_lic" src="<?php echo $plumber_icon; ?>">
            <h2 class="p_h2"><?php echo isset($designation2[$designation2id]) ? $designation2[$designation2id] : '-'; ?></h2>
         </div>
      </div>
   </div>

<div class="back-ca" style="page-break-before: always;"></div>
    <div class="add_top_value <?php echo (isset($cardcolor[$designation2id]) ? $cardcolor[$designation2id] : ''); ?>  pb_inner pb_rotate">
      <div class="pb_card">
         <div class="p_top">
        <?php if($designation2id != '1' and $designation2id != '2'){ ?>
         <p class="pb_first_txt">This card holder is only entitled to purchase and issue Plumbing COC’s for the following categories of plumbing and plumbing specialisations</p>
         <p class="add_width img-txt" style="margin-top: 2px; height: auto; width: 90%;/*display: inline-block;*/ margin-bottom: 0;/*overflow: hidden;*/">
            <?php if($designation2id == '3'){ ?>
                <span class="docicon">
                    <span  class="wtr--efncy---img"><img src="<?php echo $backcard9; ?>"></span>
                    <span class="txt-img-card wtr--efncy--ttle">Water Energy Efficiency</span>
                </span>
                <span class="docicon">
                    <span  class="drngee---img"><img src="<?php echo $backcard3; ?>"></span>
                    <span class="txt-img-card drngee--ttle">Drainage</span>
                </span>
                <span class="docicon" style="clear: both;">
                    <span  class="cold--wtr---img"><img src="<?php echo $backcard2; ?>"></span>
                    <span class="txt-img-card cold---ttle">Cold Water</span>
                </span>
                <span class="docicon">
                    <span  class="hot--wtr---img"><img src="<?php echo $backcard6; ?>"></span>
                    <span class="txt-img-card ht--wtr--ttle">Hot Water</span>
                </span>
            <?php }else{ ?>

            
        <span class="docicon">
            <span  class="abve--img"><img src="<?php echo $backcard; ?>"></span>
            <span class="txt-img-card abve-grnd--ttle">Above Ground Drainage</span>
        </span>
         <span class="docicon">
            <span class="hot--wtr---img"><img src="<?php echo $backcard6; ?>"></span>
            <span  class="txt-img-card ht--wtr--ttle">Hot Water</span>
        </span>
         <span class="docicon" style="clear: both;">
            <span class="blw---img"><img src="<?php echo $backcard1; ?>"></span>
            <span  class="txt-img-card blw-grnd--ttle">Below Ground Drainage</span>
        </span>
         <span class="docicon <?php if($designation2id =='5' || $designation2id =='4'){ echo "displaynone"; } ?>">
            <span class="solar---img"><img src="<?php echo $backcard8; ?>"></span>
            <span  class="txt-img-card slr--wtr-ttle">Solar Water Heating</span>
        </span>
         <span class="docicon">
            <span class="rain--wtr---img"><img src="<?php echo $backcard; ?>"></span>
            <span  class="txt-img-card rain---ttle">Rain Water Drainage</span>
        </span>
         <span class="docicon <?php if($designation2id =='5' || $designation2id =='4'){ echo "displaynone"; } ?>">
            <span class="heat---img"><img src="<?php echo $backcard7; ?>"></span>
            <span  class="txt-img-card heat---ttle">Heat Pumps</span>
        </span>
         <span class="docicon" style="clear: both;">
            <span class="cold--wtr---img"><img src="<?php echo $backcard2; ?>"></span>
            <span  class="txt-img-card cold---ttle">Cold Water</span>
        </span>
         <span class="docicon <?php if($designation2id =='5' || $designation2id =='4'){ echo "displaynone"; } ?>">
            <span class="gas---img"><img src="<?php echo $backcard4; ?>"></span>
            <span  class="txt-img-card gas-title">Gas</span>
        </span></p>
    <?php } ?>
         </div>
         <div class="back_seccdiv" style="clear: both;">
         <div class="pb_bottom_left" style="">
          <div class="cur_em">
        <div class="bac_lefpad" style="padding-left: 12px;">
            <span class="pb_title">Current</span>
            <span class="pb_title">Employer</span>
            <p class="plumber_name add_style"><?php echo  $companyname; ?></p>
        </div>
        </div>
            <!-- <img class="tchn--asiiisss" src="<?php// echo base_url().$plumber_icon; ?>"> -->
          
         </div>
     <?php }else{
            echo "<img src='".$logo."'>"; ?>
             <div class="pb_bottom_left" style="">
              <div class="cur_em">
            <div class="bac_lefpad" style="padding-left: 12px;">
                <span class="pb_title">Current</span>
                            <br>
                <span class="pb_title">Employer</span>
                <p class="plumber_name add_style"><?php echo  $companyname; ?></p>
            </div>
            </div>
                <img class="p_lic" src="<?php echo $plumber_icon; ?>">
              
             </div>

     <?php } ?>
       
         <div class="pb_right">
            
            <?php if($designation2id != '1' and $designation2id != '2' and $designation2id != '3'){ ?>
                <p style="font-weight: 600;font-size: 10px;">specialisations</p>
                        <?php 
                            if(count($specialisationsid) > 0){
                                foreach($specialisationsid as $specialisationsdata){
                                    
                               
                                
                        ?>
                                    <p style="font-size: 9px;margin-bottom: -10px;margin-left: 20px;"><?php echo  isset($specialisations[$specialisationsdata]) ? $specialisations[$specialisationsdata] : '-';?></p>
                        <?php   
                                }
                            }else{
                            
                        ?>
                                <p>-</p>
                        <?php 
                            }
                        }

                        ?>
         </div>
         </div>
           <div class="bto_fot">
           <p class="pb_p pb_lost">Lost or Found <?php echo $work_phone; ?></p>
            <p class="pb_p pb_vrfy">Verification can be done via</p>
            <p class="pb_p pb_web">www.pirb.co.za</p>
         </div>
         <div class="pb_bar <?php echo (isset($cardcolor[$designation2id]) ? $cardcolor[$designation2id] : ''); ?>">
         </div>
         <h2 class="pb_lic"><?php echo isset($designation2[$designation2id]) ? $designation2[$designation2id] : '-'; ?></h2>
      </div>
   </div>
</body>
</html>

