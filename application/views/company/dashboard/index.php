<?php 
	$pdfimg 				= base_url().'assets/images/pdf.png';
	$profileimg 			= base_url().'assets/images/profile.jpg';
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


<div class="row">	
	<div class="col-12">
		<div class="card">
			<div class="card-body">
		
		<style>
			img.bar_profil {
                width: 37% !important;
                height: 60px !important;
			}

			img.bar_profil {
                margin-left: 44px !important;
            }

			span.ver_name1 {
    			left: -64px !important;
    			top: 120px !important;
			}

			span.bar_bot {
    			text-align: left !important;
    			width: 44% !important;
			}

			span.ver_name2 {
    			left: -64px !important;
    			top: 60px !important;
			}

			span.ver_name3 {
    			left: -64px !important;
    			top: 12px !important;
			}

			.message_sec {
    			padding: 24px 7px !important;
			}

			div#mycocs {
    			top: 10px !important;
				left: 60px !important;
    			height: 474px !important;
			}
			
			.text-themecolor {
    			font-size: 20px;
    			font-weight: 600;
			}

			@media only screen and (max-width: 767px) {

			.mini-sidebar .left-sidebar, .mini-sidebar .sidebar-footer {
    			left: -270px;
			}

			.page-wrapper {
    			margin-left: 0px !important;
			}

			div#mycocs {
    			left: 0px !important;
			}

			span.ver_name1 {
    			left: -54px !important;
    			top: 130px;
			}

			span.ver_name2 {
    			left: -54px !important;
    			top: 70px !important;
			}

			span.ver_name3 {
    			left: -54px !important;
    			top: 20px !important;
			}

			}

			@media only screen and (min-width:768px) and (max-width: 1024px) {

				.mini-sidebar .left-sidebar, .mini-sidebar .sidebar-footer {
					left: -270px;
				}

				.page-wrapper {
					margin-left: 0px !important;
				}
				
				div#mycocs {
					left: 0px !important;
				}

				.ver_name1 {
    				left: -30px !important;
    				top: 150px !important;
				}

				.ver_name2 {
    				left: -30px !important;
    				top: 90px !important;
				}

				.ver_name3 {
    				left: -30px !important;
    				top: 42px !important;
				}

				div#mycocs{
					height:420px !important;
				}
			}

			</style>
				<div class="row">
				<div class="col-md-6 coc_sectiom_cus">
					<div class="coc_sec">
						<p class="cus_my_coc">My COC’s</p>
						<div id="mycocs" style="width:100%; height:400px;" class="cus_line"></div>
					</div>
				</div>

				<div class="col-md-6 my_perform_sec displaynone">
					<div class="cus_perform"> 
						<p class="perf_hed">My Performance Status</p>
						<div id="performancechart" style="width:100%; height:0px;"></div>
						<div class="perform_graph">
                         <img src="<?php echo base_url().'assets/images/graph_icon.png'; ?>">
                        </div>
						<p class="per_scr_hed">My Performance Score</p>	
						<p class="per_scr_box"><?php echo $performancestatus; ?></p>
						<div class="my_Rank">
							<p class="per_coun_hed">My Country Ranking</p>
							<p class="cus_co_rank"><?php echo $countrytotal; ?></p>
						</div>
						<div class="my_Rank">
							<p class="per_coun_hed">My Regional  Ranking</p>
							<p class="cus_co_rank"><?php echo $reginoaltotal; ?></p>
						</div>
					</div>
				</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="col-md-12 message_sec displaynone">
							<div class="cus_msg">
								<p>My Pirb Messages</p>
								<?php 
									$data 	= $this->db->where("groups='4' AND status='1'")->get('messages')->result_array();
									$msg 	= "";
									foreach ($data as $key => $value) {
										$currentDate = date('Y-m-d');
										$startdate   = date('Y-m-d',strtotime($value['startdate']));
										$enddate = date('Y-m-d',strtotime($value['enddate']));
										if ($currentDate>= $startdate && $currentDate<=$enddate){
											$msg = $msg.$value['message'].'</br></br>'; 
										}
									}
									
									echo '<div class="col-md-12">'.$msg.'</div>';
								?>
							</div>
						</div>
						<div class="table-responsive m-t-40">
					<label for="name">Employee Listing</label>
					<table class="table table-bordered table-striped datatables fullwidth">
						<thead>
							<tr>
								<th>Status</th>
								<th>Plumbers Name and Surname</th>
								<th>Industry Ranking - Regional</th>
								<th>Industry Ranking - National</th>
							</tr>
						</thead>
					</table>
				</div>
						<div class="col-md-12 message_sec">
							<div class="cus_msg">
								<p>My Pirb Messages</p>
								<?php 
									echo '<div class="col-md-12">'.$pirbmsg.'</div>';
								?>
							</div>
						</div>
				
						<div class="row">
							<div class="col-md-6 cus_reg_sec">
								<div class="cus_regt">
									<p class="reg_he">Current Top 3 Regional Ranking (Country)</p>
									<div class="row reg_grap">
										<?php 
											foreach($overallperformancestatuslimit as $key => $performance){ 
												$userid					= $performance['userid'];
												$filepath				= base_url().'assets/uploads/plumber/'.$userid.'/';
												$file2 					= isset($performance['image']) ? $performance['image'] : '';
												if($file2!=''){
													$explodefile2 	= explode('.', $file2);
													$extfile2 		= array_pop($explodefile2);
													$photoidimg 	= (in_array($extfile2, ['pdf', 'tiff'])) ? $pdfimg : $filepath.$file2;
													$photoidurl		= $filepath.$file2;
												}else{
													$photoidimg 	= $profileimg;
													$photoidurl		= 'javascript:void(0);';
												}
										?>
												<div class="col-md-4 cus_bar_">
													<div class="bar_img">
														<img src="<?php echo $photoidimg; ?>" class="bar_profil">
														<span class="ver_name<?php echo $key+1; ?>"><?php echo $performance['name']; ?></span>
														<img src="<?php echo base_url().'assets/images/bar'.($key+1).'.png'; ?>" class="bar_3d cus_pirb<?php echo $key+1; ?>">
														<span class="bar_bot"><?php echo round(($performance['point']), 2); ?></span>
													</div>
												</div>
										<?php } ?>
									</div>
								</div>
							</div>
							<div class="col-md-6 cus_reg_sec">
								<div class="cus_regt">
									<p class="reg_he">Current Top 3 Regional Ranking (Province)</p>
									<div class="row reg_grap">
										<?php 
											foreach($provinceperformancestatuslimit as $key => $performance){ 
												$userid					= $performance['userid'];
												$filepath				= base_url().'assets/uploads/plumber/'.$userid.'/';
												$file2 					= isset($performance['image']) ? $performance['image'] : '';
												if($file2!=''){
													$explodefile2 	= explode('.', $file2);
													$extfile2 		= array_pop($explodefile2);
													$photoidimg 	= (in_array($extfile2, ['pdf', 'tiff'])) ? $pdfimg : $filepath.$file2;
													$photoidurl		= $filepath.$file2;
												}else{
													$photoidimg 	= $profileimg;
													$photoidurl		= 'javascript:void(0);';
												}
										?>
												<div class="col-md-4 cus_bar_">
													<div class="bar_img">
														<img src="<?php echo $photoidimg; ?>" class="bar_profil">
														<span class="ver_name<?php echo $key+1; ?>"><?php echo $performance['name']; ?></span>
														<img src="<?php echo base_url().'assets/images/bar'.($key+1).'.png'; ?>" class="bar_3d cus_pirb<?php echo $key+1; ?>">
														<span class="bar_bot"><?php echo round(($performance['point']), 2); ?></span>
													</div>
												</div>
										<?php } ?>
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
	$(function(){
		var nonlogcoc 				= '<?php echo $nonlogcoc; ?>';
		var adminstock 				= '<?php echo $adminstock; ?>';
		var coccount 				= '<?php echo $coccount; ?>';
		var userid 					= '<?php echo $id; ?>';

		barchart2(
			'mycocs',
			{
				xaxis : [
					'Number of \n Non Logged \n COCs',
					'COC’s yet \n to allocated',
					'Permitted COCs \n that you are \n able to purchase'
				],
				series : [{
					name : 'My COC’s',
					yaxis : [
						nonlogcoc,
						adminstock,
						coccount
					],
					colors : ['#C4E0B2','#CEF57F','#9ADD11']
				}]
			}
		);

		var options = {
			url 	: 	'<?php echo base_url()."company/dashboard/index/DTemplist"; ?>',
			data 	: {"comp_id": userid},
			columns : 	[
							{ "data": "status" },
							{ "data": "namesurname" },
							{ "data": "rating" },
							{ "data": "rating1" },
						],
						
		};
		
		ajaxdatatables('.datatables', options);
	});
</script>
