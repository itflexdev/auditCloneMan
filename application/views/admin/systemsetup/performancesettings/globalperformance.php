<?php
if(isset($result) && $result){
	$heading		= 'Update';
}else{
	$id 			= '';
	$name			= set_value('name');
	$status			= set_value('status');

	$heading		= 'Save';
}
?>

<div class="row page-titles">
	<div class="col-md-5 align-self-center">
		<h4 class="text-themecolor">Global Performance Settings</h4>
	</div>
	<div class="col-md-7 align-self-center text-right">
		<div class="d-flex justify-content-end align-items-center">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?php echo base_url().'admin/dashboard'; ?>">Home</a></li>
				<li class="breadcrumb-item active">Global Performance Settings</li>
			</ol>
		</div>
	</div>
</div>
<?php echo $notification; ?>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="table-responsive no_sccroll m-t-40">
					<h4 class="card-title">Global Performance Settings - Point Allocations</h4>
					<form class="mt-4 form" action="" method="post">
					    <table class="table  fullwidth" border="1">
					    	<thead>
								<tr>
									<th>Description</th>
									<th>Point Allocation</th>
									<th>Performance Wording</th>
								</tr>
					 		</thead>
					 		<tbody>
								<?php 
									foreach($results as $key=>$val){
										if(!in_array($val['type'], [0])){
								?>
											<tr>
												<?php if($val['type'] == 1 || $val['type'] == 3 || $val['type'] == 5|| $val['type'] == 7 ){ ?>
													<td class="key" style="font-weight:bold;"><?php echo $val['description'];?></td>
												<?php }else {  ?>
													<td class="key"><?php echo $val['description'];?></td>
												<?php } ?>
												<td class="point">
													<?php if($val['type']!=1 && $val['type']!=3 && $val['type']!=5 ){ ?>
														<input type="text" size="2" min="0" name="points[<?php echo $val['id']; ?>]"  value="<?php echo $val['point'];?>" style="margin: 0px 20px;width: 40%;">
													<?php } ?>
												</td>
												<td class="wording" >
													<?php if($val['type']!=1 && $val['type']!=3 && $val['type']!=5 ){ ?>
														<?php echo $val['wording'];?>
													<?php } ?>
												</td>
											</tr>
								<?php 
										} 
									} 
								?>
							</tbody>
					    </table>
						</br></br>
					
			   
						<h4 class="card-title">Global Performance Settings - Warning Notifications to Plumbers</h4>
					    <table class="table  fullwidth no_padd" border="1">
					    	<thead>
							<tr>
								<th>Performance Warning Status</th>
								<th>Point threshold at which the warning notificaton is sent</th>
								<th>Active</th>
							</tr>
					 		</thead>
					 		<tbody>
					 			<?php foreach($result as $key1=>$val1){ ?>
									<tr>
										<td class="key1"><?php echo $val1['warning'];?></td>
										<td class="point">
											<input type="text" size="2" min="0" name="points1[<?php echo $val1['id'];?>]"  value="<?php echo $val1['point'];?>" style="margin: 0px 20px;width: 10%;">
										</td>
										<td>
											<div class="custom-control custom-checkbox mr-sm-2 mb-3 pt-2">
												<input type="checkbox" class="custom-control-input" name="status[<?php echo $val1['id'];?>]" id="status_<?php echo $val1['id'];?>" <?php echo ($val1['status']=='1') ? 'checked="checked"' : ''; ?>>
												<label class="custom-control-label" for="status_<?php echo $val1['id'];?>"></label>
											</div>
										</td>
									</tr>
								<?php } ?>
							</tbody>
					    </table>
						</br>
					    <div class="row">
							<div class="col-md-8">
								<div class="form-group">
									<label style="font-weight:bold;">Performance Rolling Averages</label>&nbsp&nbsp&nbsp &nbsp &nbsp 
									<input type="text" class="form-group" id="avg" name="points[13]"  value="<?php echo $results['12']['point']; ?>" placeholder="months" >	
									<span>Month(s)</span>				
								</div>			
							</div>
						</div>
						<h4 class="card-title">Global Performance listing factors</h4>
					    <div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<div class="row">
										<label class="col-md-5 mt-3" style="font-weight:bold;"><?php echo $results['13']['description']; ?></label>
										<input type="text" class="form-group col-md-6" name="points[14]"  value="<?php echo $results['13']['point']; ?>" placeholder="points">	
									</div>			
								</div>			
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<div class="row">
										<label class="col-md-5 mt-3" style="font-weight:bold;"><?php echo $results['14']['description']; ?></label>
										<input type="text" class="form-group  col-md-6" name="points[15]"  value="<?php echo $results['14']['point']; ?>" placeholder="points">	
									</div>			
								</div>			
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<div class="row">
										<label class="col-md-5 mt-3" style="font-weight:bold;"><?php echo $results['15']['description']; ?></label>
										<input type="text" class="form-group col-md-6" name="points[16]"  value="<?php echo $results['15']['point']; ?>" placeholder="points">	
									</div>			
								</div>			
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<div class="row">
										<label class="col-md-5 mt-3" style="font-weight:bold;"><?php echo $results['16']['description']; ?></label>
										<input type="text" class="form-group col-md-6" name="points[17]"  value="<?php echo $results['16']['point']; ?>" placeholder="points">	
									</div>			
								</div>			
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<div class="row">
										<label class="col-md-5 mt-3" style="font-weight:bold;"><?php echo $results['17']['description']; ?></label>
										<input type="text" class="form-group col-md-6" name="points[18]"  value="<?php echo $results['17']['point']; ?>" placeholder="points">	
									</div>			
								</div>			
							</div>
						</div>
					    <div class="row">
							<div class="col-md-12 text-right">
								<?php if($checkpermission){ ?>
									<button type="submit" name="submit" value="submit" class="btn btn-primary"><?php echo $heading; ?></button>
								<?php } ?>
							</div>
						</div>
			      </form>		
		    </div>
	    </div>
    </div>
</div>



