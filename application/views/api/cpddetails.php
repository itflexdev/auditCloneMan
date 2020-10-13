<!DOCTYPE html>
<html>
<head>
    <title>CPD</title><!-- 
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script> -->
<!--    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js" integrity="sha384-LtrjvnR4Twt/qOuYxE721u19sVFLVSA4hf/rRt6PrZTmiPltdZcI7q7PXQBYTKyf" crossorigin="anonymous"></script> -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="./style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>


</head>
<body><section class="cpd_menu_sec">
    <h3 class="cpd_head">CPD Menu</h3>
<div class="cpd_cus_sec dev_sec">
    <div class="head_expan dev_act">
    <h4 class="cpd_sub_hed">Developmental Activities</h4>
    <i class="fa fa-arrow-up" aria-hidden="true"></i>
    </div>
    <?php 
    if (isset($developmental) && count($developmental) > 0) { 
        foreach ($developmental as $developmentalkey => $developmentalvalue) {
            ?>
                <div class="table_cpd">
                    <div class="row cPd_table">
                        <div class="col-sm-12 col-md-12 col-lg-4 cpd_tab_img">
                            <a href="<?php echo 'http://'.$developmentalvalue['link']; ?>" target = "_blank"> <img src="<?php echo base_url().'assets/uploads/cpdtypes/images/'.$developmentalvalue['image'].'' ?>"> </a>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-8 tab_right">
                            <h2 class="tab_right_head"><?php echo $developmentalvalue['activity']; ?></h2>
                            <h2 class="tab_right_head"><?php echo $developmentalvalue['points']; ?> CPD points</h2>
                            <p class="cus_cpd_para"><span>Description:</span><?php echo $developmentalvalue['description']; ?></p>
                            <p class="cus_cpd_para"><span>Proof:</span><?php echo $developmentalvalue['proof']; ?></p>
                            <p class="cus_cpd_para"><span>End date:</span><?php echo date('d F Y', strtotime($developmentalvalue['enddate'])); ?></p>
                        </div>
                    </div>
                </div>
           <?php } ?>
    <?php }else{ ?>
            <div class="table_cpd">
                <div class="row cPd_table">
                    <div class="col-sm-12 col-md-12 col-lg-4 cpd_tab_img">
                        No Activity Found
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-8 tab_right">
                       No Activity Found
                    </div>
                </div>
            </div>
     <?php } ?>
</div>

<div class="cpd_cus_sec work_bas_sec">
    <div class="head_expan work_bas">
    <h4 class="cpd_sub_hed">Work-Based Activities</h4>
    <i class="fa fa-arrow-up" aria-hidden="true"></i>
    </div>
    <?php 
    if (isset($workbased) && count($workbased) > 0) { 
        foreach ($workbased as $workbasedkey => $workbasedvalue) {
             ?>
                <div class="table_cpd">
                    <div class="row cPd_table">
                        <div class="col-sm-12 col-md-12 col-lg-4 cpd_tab_img">
                            <a href="<?php echo 'http://'.$workbasedvalue['link']; ?>" target = "_blank"> <img src="<?php echo base_url().'assets/uploads/cpdtypes/images/'.$workbasedvalue['image'].'' ?>"> </a>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-8 tab_right">
                            <h2 class="tab_right_head"><?php echo $workbasedvalue['activity']; ?></h2>
                            <h2 class="tab_right_head"><?php echo $workbasedvalue['points']; ?> CPD points</h2>
                            <p class="cus_cpd_para"><span>Description:</span><?php echo $workbasedvalue['description']; ?></p>
                            <p class="cus_cpd_para"><span>Proof:</span><?php echo $workbasedvalue['proof']; ?></p>
                            <p class="cus_cpd_para"><span>End date:</span><?php echo date('d F Y', strtotime($workbasedvalue['enddate'])); ?></p>
                        </div>
                    </div>
                </div>
           <?php }
        } else{ ?>
            <div class="table_cpd">
                <div class="row cPd_table">
                    <div class="col-sm-12 col-md-12 col-lg-4 cpd_tab_img">
                        No Activity Found
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-8 tab_right">
                       No Activity Found
                    </div>
                </div>
            </div>
     <?php } ?>
</div>


<div class="cpd_cus_sec Ind_sec">
    <div class="head_expan indiv_act">
    <h4 class="cpd_sub_hed">Indiviual Activities</h4>
    <i class="fa fa-arrow-up" aria-hidden="true"></i>
    </div>
    <?php
    if (isset($individual) && count($individual) > 0) { 

       foreach ($individual as $individualkey => $individualvalue) {
        ?>
            <div class="table_cpd">
                <div class="row cPd_table">
                    <div class="col-sm-12 col-md-12 col-lg-4 cpd_tab_img">
                        <a href="<?php echo 'http://'.$individualvalue['link']; ?>" target = "_blank"> <img src="<?php echo base_url().'assets/uploads/cpdtypes/images/'.$individualvalue['image'].'' ?>"> </a>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-8 tab_right">
                        <h2 class="tab_right_head"><?php echo $individualvalue['activity']; ?></h2>
                        <h2 class="tab_right_head"><?php echo $individualvalue['points']; ?> CPD points</h2>
                        <p class="cus_cpd_para"><span>Description:</span><?php echo $individualvalue['description']; ?></p>
                        <p class="cus_cpd_para"><span>Proof:</span><?php echo $individualvalue['proof']; ?></p>
                        <p class="cus_cpd_para"><span>End date:</span><?php echo date('d F Y', strtotime($individualvalue['enddate'])); ?></p>
                    </div>
                </div>
            </div>
       <?php }
    }else{ ?>
        <div class="table_cpd">
            <div class="row cPd_table">
                <div class="col-sm-12 col-md-12 col-lg-4 cpd_tab_img">
                    No Activity Found
                </div>
                <div class="col-sm-12 col-md-12 col-lg-8 tab_right">
                  No Activity Found
                </div>
            </div>
        </div>
    <?php } ?>
</div>

</section>
</body>
<!-- <script type="text/javascript" src="./custom.js"></script> -->
<script type="text/javascript">
var action ='click';
 var speed ="500";
    $(document).ready(function(){
        // $('.table_cpd').hide();
 $('.head_expan').click('.head_expan', function(){
    //$('.head_expan').addClass('open');
      $(this).closest('.head_expan').toggleClass('open').click(action);
 $(this).next().slideToggle().siblings('.table_cpd').slideUp();
 });
 });

 // var action ='click';
 // var speed ="500";
 // $(document).on('click', '.head_expan', function(){
 //      $(this).closest('.head_expan').toggleClass('open').click(action);
 // $(this).next().slideToggle().siblings('.table_cpd').slideUp();
 // });
</script>
</html> 