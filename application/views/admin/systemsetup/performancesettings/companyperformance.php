<div class="row page-titles">
	<div class="col-md-5 align-self-center">
		<h4 class="text-themecolor">Global Performance Settings for Companies</h4>
	</div>
	<div class="col-md-7 align-self-center text-right">
		<div class="d-flex justify-content-end align-items-center">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?php echo base_url().'admin/dashboard'; ?>">Home</a></li>
				<li class="breadcrumb-item active">Company Performance Types</li>
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
									<th><b>Description</b></th>
									<th><b>Point Allocation</b></th>								
								</tr>
					 		</thead>
					 		<tbody>
					 			<?php 
									$company_performance = $this->config->item('company_performance'); 
									$i =0;
									foreach ($result as $key => $value) {
										if ($i>6) {
											break;
										}
									?>
								<tr>									
									<td class="key1"> <?php echo $company_performance[$value['document_type']]; ?>	</td>
									<td class="point">
										<input type="number" required size="3" min="1" name="points[<?php echo $value['id']; ?>]" value="<?php echo $value['points']; ?>" style="margin: 0px 20px;width: 10%;">
									</td>									
								</tr>
								<?php $i++;} ?>
							</tbody>
					    </table><br>
						<h4 class="card-title">Plumber Performance Factors</h4>
					    <div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="installationtype_id">NON Licensed Plumber</label>
									<input type="number" class="form-control" id="licensed" name="points[8]" value="<?php echo $result[7]['points']; ?>">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="name">Licensed Plumbers and Above</label>
									<input type="number" class="form-control" id="nonlicensed" name="points[9]" value="<?php echo $result[8]['points']; ?>">
								</div>
							</div>
						</div>
					    <div class="row">
							<div class="col-md-12 text-right">								
								<button type="submit" name="submit" value="submit" class="btn btn-primary">Update</button>		
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>				