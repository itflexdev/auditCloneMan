<?php

$openaudits			= $history['openaudits'];
$total 				= $history['total'];
$refixincomplete 	= $history['refixincomplete'];
$refixcomplete 		= $history['refixcomplete'];
$compliment 		= $history['compliment'];
$cautionary 		= $history['cautionary'];
$noaudit 			= $history['noaudit'];

?>


<div class="row page-titles">
	<div class="col-md-5 align-self-center">
		<h4 class="text-themecolor">Dashboard</h4>
	</div>
	<div class="col-md-7 align-self-center text-right">
		<div class="d-flex justify-content-end align-items-center">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">Home</li>
			</ol>
		</div>
	</div>
</div>

<style>
.a_round {
  width: 70px;
    height: 70px;
    border-radius: 50%;
    font-size: 14px;
    line-height: 70px;
	font-weight: bold;
    text-align: center;
    background: #000;
    margin: 0 0 5px 10px;
}
.a_round2{
	width: 80px;
    height: 80px;
    border-radius: 50%;
    font-size: 14px;
    line-height: 118px;
    font-weight: bold;
    text-align: center;
    background: #000;
    margin: 0 0 5px 10px;
}
.a_label{
	    font-size: 10px!important;
    font-weight: bold!important;
    color: #000!important;
}
.a_sale_graph{
	width: 80px;
    height: 50px;
    position: absolute;
    left: 50%;
    margin: -25px 0 0 -25px;
}
.a_ratio{
	    font-size: 18px;
    font-weight: bold;
}
.racial{
	margin: 0 0 0 -26px;width: 100px;
}
.racial2 {
    margin: 0 -20px 0 0;
    width: 100px;
	height: 100px;
}
.round_img{
	position: relative;
    width: 35px;
    top: 45px;
    left: 13%;
}
</style>
<div class="row">	
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				
				<h4 class="font-weight-bold">REGISTRATION DETAILS</h4>
				<div class="row">
					<div class="col-md-12 message_sec">
						<div class="cus_msg">
							<p>Registrar Status</p>
							<div class="row">
								<div class="col-md-2 offset-1">
									<div class="form-group coc_pur_sec">
									<div class="a_round text-white" style="background-color: rgb(68,114,196);"><?php echo $statusactive;?></div>
									<label class="a_label">Active</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group coc_pur_sec">
									<div class="a_round text-white" style="background-color: rgb(255,192,0);"><?php echo $statussuspended;?></div>
									<label class="a_label">Suspended</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group coc_pur_sec">
									<div class="a_round text-white" style="background-color: rgb(255,0,0);"><?php echo $statusexpired;?></div>
									<label class="a_label">Expired & Resigned</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group coc_pur_sec">
									<div class="a_round text-white" style="background-color: rgb(192,0,0);"><?php echo $statuspending;?></div>
									<label class="a_label">Pending</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group coc_pur_sec">
									<div class="a_round text-white" style="background-color: rgb(255,196,0);"><?php echo $statuscpdsuspend;?></div>
									<label class="a_label">CPD Suspended</label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 message_sec">
						<div class="cus_msg">
							<p>Registrar Designation</p>
							<div class="row">
								<div class="col-md-2">
									<div class="form-group coc_pur_sec">
									<div class="a_round text-white" style="background-color: rgb(68,114,196);"><?php echo $designationl;?></div>
									<label class="a_label">Learner</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group coc_pur_sec">
									<div class="a_round text-white" style="background-color: rgb(255,192,0);"><?php echo $designationtap;?></div>
									<label class="a_label">Technical Assistance Practitioner</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group coc_pur_sec">
									<div class="a_round text-white" style="background-color: rgb(255,0,0);"><?php echo $designationtop;?></div>
									<label class="a_label">Technical Operator Practitioner</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group coc_pur_sec">
									<div class="a_round text-white" style="background-color: rgb(192,0,0);"><?php echo $designationqp;?></div>
									<label class="a_label">Registered Plumber</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group coc_pur_sec">
									<div class="a_round text-white" style="background-color: rgb(112,173,71);"><?php echo $designationlp;?></div>
									<label class="a_label">Licensed Plumber</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group coc_pur_sec">
									<div class="a_round text-white" style="background-color: rgb(0,0,0);"><?php echo $designationmp;?></div>
									<label class="a_label">Master Plumber</label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-5 message_sec">
						<div class="cus_msg">
							<p>Racial Grouping</p>
							<div class="row">
								<div class="col-md-3">
									<div class="form-group coc_pur_sec">
									<label class="a_label">Coloured</label>
									<img class="racial" src="<?php echo base_url().'assets/images/icons/coloured.png'; ?>" alt="">
									<div class=""><?php echo round(($racial1/$totalplumber) * 100, 2).'%'; ?></div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group coc_pur_sec">
									<label class="a_label">Indian</label>
									<img class="racial" src="<?php echo base_url().'assets/images/icons/indian.png'; ?>" alt="">
									<div class=""><?php echo round(($racial2/$totalplumber) * 100, 2).'%'; ?></div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group coc_pur_sec">
									<label class="a_label">Black</label>
									<img class="racial" src="<?php echo base_url().'assets/images/icons/black.png'; ?>" alt="">
									<div class=""><?php echo round(($racial3/$totalplumber) * 100, 2).'%'; ?></div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group coc_pur_sec">
									<label class="a_label">White</label>
									<img class="racial" src="<?php echo base_url().'assets/images/icons/white.png'; ?>" alt="">
									<div class=""><?php echo round(($racial4/$totalplumber) * 100, 2).'%'; ?></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-md-3 message_sec" style="margin-left: 18px;">
						<div class="cus_msg">
							<p>Gender</p>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group coc_pur_sec">
									<label class="a_label">Male</label>
									<img class="racial" style="margin-right:-25px;" src="<?php echo base_url().'assets/images/icons/male.png'; ?>" alt="">
									<div class=""><?php echo round(($genderm/$totalplumber) * 100, 2).'%'; ?></div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group coc_pur_sec">
									<label class="a_label">Female</label>
									<img class="racial" style="margin-right:-25px;" src="<?php echo base_url().'assets/images/icons/female.png'; ?>" alt="">
									<div class=""><?php echo round(($genderf/$totalplumber) * 100, 2).'%'; ?></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div><br>
				
				
				
				<h4 class="font-weight-bold">COC DETAILS</h4>
				<div class="row">
					<div class="col-md-12 message_sec">
						<div class="cus_msg row"><p>COC Sales Graph last 6 months</p></div>
						<div class="row">
							<div class="col-md-2">
								<p class="a_sale_graph" style="top:50%"><span style="background:#ED7D31;padding:0 7px;margin-right: 4px;"></span><span>Paper</span></p>
								<p class="a_sale_graph" style="top:60%"><span style="background:#4472C4;padding:0 7px;margin-right: 4px;"></span><span>Electronic</span></p>
							</div>
							<div class="col-md-10">
								<div id="sixmonthchart" style="width:100%; height:400px;"></div>
							</div>
						</div>
					</div>
					<div class="col-md-12 message_sec">
						<div class="cus_msg">
							<p>COC Status</p>
							<div class="row">
								<div class="col-md-1"></div>
								<div class="col-md-2">
									<div class="form-group coc_pur_sec">
									<img class="round_img" src="<?php echo base_url().'assets/images/icons/pic5.png'; ?>" alt="">
									<div class="a_round2" style="background-color: rgb(255,192,0);"><?php echo $totalcoc;?></div>
									<label class="a_label">COC Issued to Date</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group coc_pur_sec">
									<img class="round_img" src="<?php echo base_url().'assets/images/icons/pic6.png'; ?>" alt="">
									<div class="a_round2" style="background-color: rgb(255,242,204);"><?php echo $totalpapercoc;?></div>
									<label class="a_label">Paper COC Issued to Date</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group coc_pur_sec">
									<img class="round_img" src="<?php echo base_url().'assets/images/icons/pic7.png'; ?>" alt="">
									<div class="a_round2" style="background-color: rgb(255,242,204);"><?php echo $totalelectroniccoc;?></div>
									<label class="a_label">Electronic COC Issued to Date</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group coc_pur_sec">
									<img class="round_img" src="<?php echo base_url().'assets/images/icons/pic8.png'; ?>" alt="">
									<div class="a_round2" style="background-color: rgb(255,192,0);"><?php echo $totallogged;?></div>
									<label class="a_label">COC Logged to Date</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group coc_pur_sec">
									<img class="round_img" src="<?php echo base_url().'assets/images/icons/pic9.png'; ?>" alt="">
									<div class="a_round2" style="background-color: rgb(112,173,71);"><?php echo $totalreseller;?></div>
									<label class="a_label">COC Resellers (Unallocated)</label>
									</div>
								</div>
								<div class="col-md-1"></div>
							</div>
						</div>
					</div>
				</div><br>
				
				
				
				<h4 class="font-weight-bold">Audit Details</h4>
				<div class="row">
					<div class="col-md-12 message_sec">
						<div class="cus_msg">
							<p>Audit Ratio</p>
							<div class="row">
								<div class="col-md-3">
									<div class="form-group coc_pur_sec">
									<img class="racial2" src="<?php echo base_url().'assets/images/icons/y_pic1.png'; ?>" alt="">
									<div class="a_ratio"><?php echo $totalcoc;?></div>
									<label class="a_label">COC Issued to Date</label>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group coc_pur_sec">
									<img class="racial2" src="<?php echo base_url().'assets/images/icons/y_pic2.png'; ?>" alt="">
									<div class="a_ratio"><?php echo $totallogged;?></div>
									<label class="a_label">COC Logged to Date</label>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group coc_pur_sec">
									<img class="racial2" src="<?php echo base_url().'assets/images/icons/y_pic3.png'; ?>" alt="">
									<div class="a_ratio"><?php echo $totalaudit;?></div>
									<label class="a_label">COC Audited to date</label>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group coc_pur_sec">
									<img class="racial2" src="<?php echo base_url().'assets/images/icons/y_pic4.png'; ?>" alt="">
									<div class="a_ratio"><?php echo round(($totalaudit/$totallogged) * 100, 2).'%'; ?></div>
									<label class="a_label">Audit Ratio</label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 message_sec">
						<div class="cus_msg">
							<p>Audit Finding</p>
							<div class="row">
								<div class="col-md-2">
									<div class="form-group coc_pur_sec">
									<div class="a_round text-white" style="background-color: #385723;"><?php echo $total;?></div>
									<label class="a_label">Total Number of Audit Findings</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group coc_pur_sec">
									<div class="a_round text-white" style="background-color: #548235;"><?php echo $noaudit;?></div>
									<label class="a_label">Total Number of No Audit Findings</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group coc_pur_sec">
									<div class="a_round text-white" style="background-color: #6da945;"><?php echo $cautionary;?></div>
									<label class="a_label">Cautionary Audit Findings</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group coc_pur_sec">
									<div class="a_round text-white" style="background-color: #a9d18e;"><?php echo $refixincomplete;?></div>
									<label class="a_label">Refix (In -Complete) Audit Findings</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group coc_pur_sec">
									<div class="a_round text-white" style="background-color: #bedeaa;"><?php echo $refixcomplete;?></div>
									<label class="a_label">Refix (Complete) Audit Findings</label>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group coc_pur_sec">
									<div class="a_round text-white" style="background-color: #c5e0b4;"><?php echo $compliment;?></div>
									<label class="a_label">Compliment Audit Findings</label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-4 cpd_section_sec">
								<div class="cpd_points_sec">
									<p class="cus_my_cpd">Open Audits</p>
									<div class="text-center">
										<img src="<?php echo base_url().'assets/images/search.png'; ?>" alt=""> 
										<input data-plugin="knob" data-width="200" data-height="200" data-min="0" data-max="10000" data-thickness="0.2" data-fgColor="#ff0000" data-angleOffset=-125 data-angleArc=250 value="<?php echo $openaudits; ?>" readonly/>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				
				
				
			</div>
		</div>
	</div>
</div>

<script>
	var sixmonthgraph = $.parseJSON('<?php echo json_encode($sixmonthgraph); ?>');
	
	$(function(){
		knobchart();
		
		var xaxis = [], yaxis1 = [], yaxis2 = [];
		$(sixmonthgraph).each(function(i,v){
			$(v).each(function(ii,vv){
				xaxis.push(vv.month);
				yaxis1.push(vv.electronic);
				yaxis2.push(vv.paper);
			})
		})
		
		barchart(
			'sixmonthchart',
			{
				xaxis : xaxis,
				series : [
					{
						name : 'Electronic',
						yaxis : yaxis1,
						color : '#4472C4'
					},
					{
						name : 'Paper',
						yaxis : yaxis2,
						color : '#ED7D31'
					}
				]
			}
		);
	});
</script>
