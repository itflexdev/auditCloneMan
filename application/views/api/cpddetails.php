<!DOCTYPE html>
<html>
   <head>
      <title>CPD</title>
      <!--
         <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
         <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script> -->
      <!--    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js" integrity="sha384-LtrjvnR4Twt/qOuYxE721u19sVFLVSA4hf/rRt6PrZTmiPltdZcI7q7PXQBYTKyf" crossorigin="anonymous"></script> -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <link rel="stylesheet" type="text/css" href="./style.css">
   </head>
   <body>
      <section class="cpd_menu_sec">
         <h3 class="cpd_head">CPD Menu</h3>
         <div class="head_expan dev_act">
            <h4 class="cpd_sub_hed">Developmental Activities</h4>
            <i class="fa fa-arrow-up" aria-hidden="true"></i>
         </div>
         <div class="table_cpd" id="table-cpd-one">
            <div class="row cPd_table frst-slide">
               <div class="table_cpd--parent slider responsive responsive-one">
                  <?php
                     if(isset($url)) unset($url);
                     if(isset($validURL)) unset($validURL);
                     if (isset($developmental) && count($developmental) > 0) {
                     foreach ($developmental as $developmentalkey => $developmentalvalue) {
                         $url = $developmentalvalue['link'];
                         if (filter_var($url, FILTER_VALIDATE_URL)) {
                             // echo("$url is a valid URL");
                             $validURL = $url;
                         } else {
                             // echo("$url is not a valid URL");
                             $validURL = 'https://'.$url;
                         }
                         ?>
                  <div class="main--test--cpd imgert">
                     <div class="img--data">
                        <a href="<?php echo $validURL; ?>" target = "_blank"> <img src="<?php echo base_url().'assets/uploads/cpdtypes/images/'.$developmentalvalue['image'].'' ?>"> </a>
                     </div>
                     <div class="tab_right">
                        <a class="tab--cntnt" href="<?php echo $validURL; ?>" target = "_blank">
                           <h2 class="tab_right_head"><?php echo $developmentalvalue['activity']; ?></h2>
                           <h2 class="tab_right_head"><?php echo $developmentalvalue['points']; ?> CPD points</h2>
                           <p class="cus_cpd_para"><span>Description: </span><?php echo $developmentalvalue['description']; ?></p>
                           <p class="cus_cpd_para"><span>Proof: </span><?php echo $developmentalvalue['proof']; ?></p>
                           <p class="cus_cpd_para"><span>End date: </span><?php echo date('d F Y', strtotime($developmentalvalue['enddate'])); ?></p>
                        </a>
                            <a href="javascript:void(0);" class="button read-more">Read More</a>
                     </div>
                  </div>
                  <?php } ?>
                  <?php }else{ ?>
                  <div class="col-sm-12 col-md-12 col-lg-4 cpd_tab_img">
                     No Activity Found
                  </div>
                  <div class="col-sm-12 col-md-12 col-lg-8 tab_right">
                     No Activity Found
                  </div>
                  <?php } ?>
               </div>
               <div class="prev prev-one">
                  <span class="fa fa-chevron-left" aria-hidden="true"></span>
               </div>
               <div class="next next-one">
                  <span class="fa fa-chevron-right" aria-hidden="true"></span>
               </div>
            </div>
         </div>
         <div class="head_expan work_bas">
            <h4 class="cpd_sub_hed">Work-Based Activities</h4>
            <i class="fa fa-arrow-up" aria-hidden="true"></i>
         </div>
         <div class="table_cpd" id="table-cpd-two">
            <div class="row cPd_table scnd-slide">
               <div class="table_cpd--parent slider responsive responsive-two">
                  <?php
                     if(isset($url)) unset($url);
                     if(isset($validURL)) unset($validURL);
                     if (isset($workbased) && count($workbased) > 0) {
                     foreach ($workbased as $workbasedkey => $workbasedvalue) {
                         $url = $workbasedvalue['link'];
                         if (filter_var($url, FILTER_VALIDATE_URL)) {
                             // echo("$url is a valid URL");
                             $validURL = $url;
                         } else {
                             // echo("$url is not a valid URL");
                             $validURL = 'https://'.$url;
                         }
                         ?>
                  <div class="main--test--cpd imgert">
                     <div class="cpd_tab_img img--data">
                        <a href="<?php echo $validURL; ?>" target = "_blank"> <img src="<?php echo base_url().'assets/uploads/cpdtypes/images/'.$workbasedvalue['image'].'' ?>"> </a>
                     </div>
                     <div class="tab_right">
                        <a class="tab--cntnt" href="<?php echo $validURL; ?>" target = "_blank">
                           <h2 class="tab_right_head"><?php echo $workbasedvalue['activity']; ?></h2>
                           <h2 class="tab_right_head"><?php echo $workbasedvalue['points']; ?> CPD points</h2>
                           <p class="cus_cpd_para"><span>Description: </span><?php echo $workbasedvalue['description']; ?></p>
                           <p class="cus_cpd_para"><span>Proof: </span><?php echo $workbasedvalue['proof']; ?></p>
                           <p class="cus_cpd_para"><span>End date: </span><?php echo date('d F Y', strtotime($workbasedvalue['enddate'])); ?></p>
                        </a>
                            <a href="javascript:void(0);" class="button read-more">Read More</a>
                     </div>
                  </div>
                  <?php } ?>
                  <?php }else{ ?>
                  <div class="col-sm-12 col-md-12 col-lg-4 cpd_tab_img">
                     No Activity Found
                  </div>
                  <div class="col-sm-12 col-md-12 col-lg-8 tab_right">
                     No Activity Found
                  </div>
                  <?php } ?>
               </div>
               <div class="prev prev-two">
                  <span class="fa fa-chevron-left" aria-hidden="true"></span>
               </div>
               <div class="next next-two">
                  <span class="fa fa-chevron-right" aria-hidden="true"></span>
               </div>
            </div>
         </div>
         <div class="head_expan indiv_act">
            <h4 class="cpd_sub_hed">Individual Activities</h4>
            <i class="fa fa-arrow-up" aria-hidden="true"></i>
         </div>
         <div class="table_cpd" id="table-cpd-three">
            <div class="row cPd_table thrd-slide">
               <div class="table_cpd--parent slider responsive responsive-three">
                  <?php
                     if(isset($url)) unset($url);
                     if(isset($validURL)) unset($validURL);
                     if (isset($individual) && count($individual) > 0) {
                     foreach ($individual as $individualkey => $individualvalue) {
                         $url = $individualvalue['link'];
                         if (filter_var($url, FILTER_VALIDATE_URL)) {
                             // echo("$url is a valid URL");
                             $validURL = $url;
                         } else {
                             // echo("$url is not a valid URL");
                             $validURL = 'https://'.$url;
                         }
                         ?>
                  <div class="main--test--cpd imgert">
                     <div class="cpd_tab_img img--data">
                        <a href="<?php echo $validURL; ?>" target = "_blank"> <img src="<?php echo base_url().'assets/uploads/cpdtypes/images/'.$individualvalue['image'].'' ?>"> </a>
                     </div>
                     <div class="tab_right">
                        <a class="tab--cntnt" href="<?php echo $validURL; ?>" target = "_blank">
                           <h2 class="tab_right_head"><?php echo $individualvalue['activity']; ?></h2>
                           <h2 class="tab_right_head"><?php echo $individualvalue['points']; ?> CPD points</h2>
                           <p class="cus_cpd_para"><span>Description: </span><?php echo $individualvalue['description']; ?></p>
                           <p class="cus_cpd_para"><span>Proof: </span><?php echo $individualvalue['proof']; ?></p>
                           <p class="cus_cpd_para"><span>End date: </span><?php echo date('d F Y', strtotime($individualvalue['enddate'])); ?></p>
                        </a>
                        <a href="javascript:void(0);" class="button read-more">Read More</a>
                     </div>
                  </div>
                 
                  <?php } ?>
                  <?php }else{ ?>
                  <div class="col-sm-12 col-md-12 col-lg-4 cpd_tab_img">
                     No Activity Found
                  </div>
                  <div class="col-sm-12 col-md-12 col-lg-8 tab_right">
                     No Activity Found
                  </div>
                  <?php } ?>
               </div>
               <div class="prev prev-three">
                  <span class="fa fa-chevron-left" aria-hidden="true"></span>
               </div>
               <div class="next next-three">
                  <span class="fa fa-chevron-right" aria-hidden="true"></span>
               </div>
            </div>
         </div>
      </section>
   </body>
   <!-- <script type="text/javascript" src="./custom.js"></script> -->
   <script type="text/javascript">
      function initSlider(){
        $('.responsive-one').slick({
                 dots: true,
                 autoplay:false,
                 infinite: true,
                 autoplaySpeed:1500,
                 // variableWidth: true,
                 prevArrow: $('.prev-one'),
                 nextArrow: $('.next-one'),
                 speed: 300,
                 slidesToShow: 3,
                 slidesToScroll: 3,
                 adaptiveHeight: true,
                 responsive: [

                 {
                   breakpoint: 3000,
                   settings: {
                     slidesToShow: 3,
                     slidesToScroll: 3,
                     adaptiveHeight: true,
                     variableWidth: true,
                     prevArrow: false,
                     dots: true,
                     prevArrow: $('.prev-one'),
                     nextArrow: $('.next-one'), 
                   } 
                },

                 {
                   breakpoint: 1024,
                   settings: {
                     slidesToShow: 2,
                     slidesToScroll: 2,
                     adaptiveHeight: true,
                     variableWidth: true,
                     vertical: false,
                     prevArrow: false,
                     dots: true,
                     prevArrow: $('.prev-one'),
                     nextArrow: $('.next-one'), } 
                },
               
               
                 {
                   breakpoint: 767,
                   settings: {
                     slidesToShow: 1,
                     slidesToScroll: 1,
                     vertical: true,
                     infinite: false,
                     verticalSwiping: true,
                     variableHeight: true,
                     adaptiveHeight: true,
                     adaptiveWidth: true,
                     mobileFirst: true,
                     prevArrow: false,
                     prevArrow: $('.prev-one'),
                     nextArrow: $('.next-one') } }
                 ] 
               });

        $('.responsive-two').slick({
                 dots: true,
                 autoplay:false,
                 infinite: true,
                 autoplaySpeed:1500,
                 prevArrow: $('.prev-two'),
                 nextArrow: $('.next-two'),
                 speed: 300,
                 slidesToShow: 3,
                 slidesToScroll: 3,
                 adaptiveHeight: true,
                 responsive: [

                  {
                   breakpoint: 3000,
                   settings: {
                     slidesToShow: 3,
                     slidesToScroll: 3,
                     adaptiveHeight: true,
                     variableWidth: true,
                     prevArrow: false,
                     dots: true,
                     prevArrow: $('.prev-two'),
                     nextArrow: $('.next-two'), 
                   } 
                },

                 {
                   breakpoint: 1024,
                   settings: {
                     slidesToShow: 2,
                     slidesToScroll: 2,
                     adaptiveHeight: true,
                     variableWidth: true,
                     vertical: false,
                     prevArrow: false,
                     dots: true,
                     prevArrow: $('.prev-two'),
                     nextArrow: $('.next-two'), } },
               
               
                 {
                   breakpoint: 767,
                   settings: {
                     slidesToShow: 1,
                     slidesToScroll: 1,
                     adaptiveHeight: true,
                     variableHeight: true,
                     adaptiveWidth: true,
                     mobileFirst: true,
                     vertical: true,
                     infinite: false,
                     verticalSwiping: true,
                     prevArrow: false,
                     prevArrow: $('.prev-two'),
                     nextArrow: $('.next-two') } }
                 ] 
               });

        $('.responsive-three').slick({
                 dots: true,
                 autoplay:false,
                 infinite: true,
                 autoplaySpeed:1500,
                 prevArrow: $('.prev-three'),
                 nextArrow: $('.next-three'),
                 
                 speed: 300,
                 slidesToShow: 3,
                 slidesToScroll: 3,
                 adaptiveHeight: true,
                 responsive: [

                  {
                   breakpoint: 3000,
                   settings: {
                     slidesToShow: 3,
                     slidesToScroll: 3,
                     adaptiveHeight: true,
                     variableWidth: true,
                     prevArrow: false,
                     dots: true,
                     prevArrow: $('.prev-three'),
                     nextArrow: $('.next-three'), 
                   } 
                },

                 {
                   breakpoint: 1024,
                   settings: {
                     slidesToShow: 2,
                     slidesToScroll: 2,
                     adaptiveHeight: true,
                     variableWidth: true,
                     vertical: true,
                     prevArrow: false,
                     dots: true,
                     prevArrow: $('.prev-three'),
                     nextArrow: $('.next-three'), } },
               
               
                 {
                   breakpoint: 767,
                   settings: {
                     slidesToShow: 1,
                     slidesToScroll: 1,
                     variableHeight: true,
                     adaptiveHeight: true,
                     adaptiveWidth: true,
                     mobileFirst: true,
                     vertical: true,
                     infinite: false,
                     verticalSwiping: true,
                     prevArrow: false,
                     prevArrow: $('.prev-three'),
                     nextArrow: $('.next-three') } }
                 ] 
               });
              }
               //# sourceURL=pen.js
               
      var action ='click';
      var speed ="500";
      $(document).ready(function(){
        initSlider();


      // $(".head_expan.dev_act").click(function(){
      //     $("#table-cpd-one").slideToggle("slow");
      // });

      // $(".head_expan.work_bas").click(function(){
      //     $("#table-cpd-two").slideToggle("slow");
      // });
      
      // $(".head_expan.indiv_act").click(function(){
      //     $("#table-cpd-three").slideToggle("slow");
      // });

      $('.head_expan').click('.head_expan', function(){
      //$('.head_expan').addClass('open');
      $(this).closest('.head_expan').toggleClass('open').click(action);
      $(this).next().slideToggle().siblings('.table_cpd').slideUp();
      $('.responsive').slick('setPosition').slick();
      });
       


       // $(".table_cpd").hide();
       // console.log($('.responsive .imgert').length);
        var slidecount =  $('.frst-slide .table_cpd--parent .slick-list .slick-track .main--test--cpd').length;
        if ( slidecount > 3 ) {
          $('.frst-slide .prev').show();
          $('.frst-slide .next').show();
        }
        else{
          $('.frst-slide .prev').hide();
          $('.frst-slide .next').hide(); 
        }

         var slidecount =  $('.scnd-slide .table_cpd--parent .slick-list .slick-track .main--test--cpd').length;
        if ( slidecount > 3 ) {
          $('.scnd-slide .prev').show();
          $('.scnd-slide .next').show();
        }
        else{
          $('.scnd-slide .prev').hide();
          $('.scnd-slide .next').hide(); 
        }

         var slidecount =  $('.thrd-slide .table_cpd--parent .slick-list .slick-track .main--test--cpd').length;
        if ( slidecount > 3 ) {
          $('.thrd-slide .prev').show();
          $('.thrd-slide .next').show();
        }
        else{
          $('.thrd-slide .prev').hide();
          $('.thrd-slide .next').hide(); 
        }
      });

      $(window).on('load', function() {
        $('.responsive').slick("refresh");
      });


      $(".tab_right .read-more").click(function() {
        $(this).parent().toggleClass("showcontent");
      });
      

    //   $(function() {
    
    //   var $el, $ps, $up, totalHeight;
      
    //   $(".tab_right .button").click(function() {
            
    //     totalHeight = 0
      
    //     $el = $(this);
    //     $p  = $el.parent();
    //     $up = $p.parent();
    //     $ps = $up.find("p:not('.read-more')");
        
    //     $ps.each(function() {
    //       totalHeight += $(this).outerHeight();
    //     });
              
    //     $up
    //       .css({
    //         "height": $up.height(),
    //         "max-height": 9999
    //       })
    //       .animate({
    //         "height": totalHeight
    //       });
                
    //         $p.fadeOut();

    //         return false;
          
    //   });
    
    // });
      
   </script>

   <style type="text/css">
   @media screen and (max-width: 767px){
      .tab_right .read-more{
        display: none !important;
      }
   }
   @media screen and (min-width: 768px){
     .tab_right {
        height: 120px !important;
        padding: 20px;
        overflow: hidden;
     }

     .tab_right .tab--cntnt, 
     .tab_right .tab--cntnt * {
        display: inline-block;
        width: 290px;
        height: 130px;
        overflow: hidden;
        text-overflow: ellipsis;
     }

     .tab_right.showcontent {
        height: auto !important;
        overflow: visible;
      }

     .tab_right .read-more{
        position: absolute;
        margin-top: -63px;
        left: 50%;
        transform: translateX(-50%);
        width: 60%;
        text-align: center;
        color: #fff !important;
        font-size: 14px;
        border-radius: 3px !important;
     }

     .tab_right.showcontent .tab--cntnt, 
     .tab_right.showcontent .tab--cntnt * {
        width: auto;
        height: auto;
        white-space: normal;
        overflow: visible;
     }

     .tab_right.showcontent .read-more {
        display: none;
      }

     .read-more {
        color: #fff !important;
     }
     }
   </style>
</html>