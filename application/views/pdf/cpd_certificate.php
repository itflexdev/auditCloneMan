<?php
if (!function_exists('imageconversion')) {
        function imageconversion($path){
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            return 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
    }

    $borderimage    = imageconversion(base_url().'assets/images/Signed_PIRB_CPD_Certificate_2020_11_16-pdf.png');
    $simione        = imageconversion(base_url().'assets/images/simone.png');
    $cropperlogo    = imageconversion(base_url().'assets/images/cropped-logo.png');
    $marlise        = imageconversion(base_url().'assets/images/marlise.png');
    $saqa           = imageconversion(base_url().'assets/images/saqa.png');
    $ribbon         = imageconversion(base_url().'assets/images/ribbon.png');


?>

<!DOCTYPE html>
<html lang="en">
<head>
    
    <style>
.certif-cont {
    padding: 20px;
    width: 100%;
    margin: 0 auto;
    text-align: center;
    border-radius: 4px;
    margin-top: 40px;
    margin-bottom: 40px;
    position: absolute;
    left: 60px;
    right: 0;
    top: 150px;
}

h2.cer-hed {
    font-size: 74px;
    color: #585858;
    margin-bottom: 10px;
    font-weight: 400;
    font-family: 'Helvetica' !important;

}

.bltxt {
    color: #00adee;
}

.bltxt.spntxt {
    font-size: 19px;
    display: block;
}

.bltxt.ptx {
    font-size: 23px;
    margin-top: 8px;
    margin-bottom: 20px;
}

.cus-inp {
    border: none;
    border-bottom: 1px solid;
    width: 50%;
    background-color: #e6e7e8;
    text-align: center;
    margin: 0 auto;
    margin-top: 50px;
    margin-bottom: 10px;
    font-size:28px;
}
.sign-sec img { 
    vertical-align: middle;
}

.sign-sec img:nth-child(2) {
    width: 450px;
    height: auto;
}

.sign-sec img {
    width: 300px;
    height: auto;
    object-fit: contain;
    margin:20px 10px;
}

.sign-sec {
    margin-bottom: 20px;
}
.border-image img {
    width: 100%;
    height: auto;
}

.border-image {
    width: 100%;
    margin: 0 auto;
}

.border-image img {
    width: 100%;
    height: 940px;
    box-shadow: 0 0 7px 2px #cacaca;
    border-radius: 4px;
}

.border-image {
    width: 100%;
    margin: 0 auto;
}

.certi-container{
    padding-top:10px;
    vertical-align: middle;

}

/* .certi-container:before {
    background-image: url("https://www.staging.audit-it.co.za/assets/images/ribbon.png");
    background-size: 50%;
    content: "";
    height: 264px;
    width: 135px;
    background-position: center;
    position: absolute;
    top: -26px;
    left: 270px;
    background-repeat: no-repeat;
} */

.certi-container {
    background-color: #f3f3f3;
}

body {
    margin: 0;
}

html, body {
    font-family: 'Helvetica' !important;
}

.saq-p {
    font-size: 17px;
    color: #505050;
}   

.ribn-img {
    width: 100px;
    height: 170px;
    position: absolute;
    object-fit: contain;
    left: 180px;
    top: 0px;
}


    </style>
</head>
<body>

    <div class="certi-container">
    <img  class="ribn-img" src="<?php echo $ribbon; ?>">
        <div class="border-image">
            <img src="<?php echo $borderimage; ?>">
        </div>
            <div class="certif-cont">
                <h2 class="cer-hed">CERTIFICATE</h2>
                <p class="bltxt ptx">CONTINUOUS PROFESSIONAL DEVELOPMENT COMPLIANCE</p>
                <span class="bltxt spntxt">This is to certify that</span>
                <p class="cus-inp"> <?php echo $plumbername; ?> </p>
                <span class="bltxt spntxt">is CPD compliant for <?php echo $cyclestart; ?>/<?php echo $cycleend; ?> cycle</span>
                <div class="sign-sec">
                    <img src="<?php echo $simione; ?>">
                    <img src="<?php echo $cropperlogo; ?>">
                    <img src="<?php echo $marlise; ?>">
                </div>
                <div class="saq-sec">
                    <img src="<?php echo $saqa; ?>">
                    <p class="saq-p">PIRB is a trusted professional body recognised by SAQA: PIRB831</p>
                </div>
            </div>
    </div>
    
</body>
</html>