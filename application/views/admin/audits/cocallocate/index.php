<div class="row page-titles">
	<div class="col-md-5 align-self-center">
		<h4 class="text-themecolor">COC Allocation for Audit</h4>
	</div>
	<div class="col-md-7 align-self-center text-right">
		<div class="d-flex justify-content-end align-items-center">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?php echo base_url().'admin/dashboard'; ?>">Home</a></li>
				<li class="breadcrumb-item active">COC Allocation for Audit</li>
			</ol>
		</div>
	</div>
</div>
<?php echo $notification; ?>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<form class="mt-4 form" action="" method="post" id="filter">
					
					<h5 class="card-title">Search Range:</h5>
					<div class="row">
						<div class="col-md-6">						
							<div class="form-group">
								<label>Start Date Range</label>
								<div class="input-group">
									<input type="text" autocomplete="off" class="form-control" id="start_date_range" name="start_date_range" data-date="datepicker" value="">
									<div class="input-group-append">
										<span class="input-group-text"><i class="icon-calender"></i></span>
									</div>
								</div>								
							</div>
						</div>
						<div class="col-md-6">						
							<div class="form-group">
								<label>End Date Range</label>
								<div class="input-group">
									<input type="text" autocomplete="off" class="form-control" id="end_date_range" name="end_date_range" data-date="datepicker" value="">
									<div class="input-group-append">
										<span class="input-group-text"><i class="icon-calender"></i></span>
									</div>
								</div>								
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Start CoC Range</label>
								<input type="text" class="form-control" id="start_coc_range" name="start_coc_range" value="">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>End CoC Range</label>
								<input type="text" class="form-control" id="end_coc_range" name="end_coc_range" value="">
							</div>
						</div>
					</div>
					
					<h5 class="card-title">Filters:</h5>						
					<div class="row">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label>Plumber Name and Surname Registration Number</label>
										<input type="search" autocomplete="off" class="form-control" name="user_search" id="user_search">
										<div id="user_suggestion"></div>
										<div class="search_icon search_icon_user">
											<i class="fa fa-search" aria-hidden="true"></i>
										</div>
										<input type="hidden" id="user_id" name="user_id">
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>Province</label>
										<?php echo form_dropdown('province', $province, '',['id' => 'province1', 'class' => 'form-control']); ?>
									</div>
								</div>
							
								<div class="col-md-6">
									<div class="form-group">
										<label>City</label>
										<?php echo form_dropdown('city', [], '', ['id' => 'city1', 'class' => 'form-control']);  ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<h5 class="card-title">COC Ranking Criteria:</h5>	
					<div class="row">
						<div class="col-md-12">
							<div class="row form-group">
								<div class="col-md-4">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" id="compulsory_audit" name="compulsory_audit" value="1" class="custom-control-input">
										<label class="custom-control-label" for="compulsory_audit">Compulsory Audit</label>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="row form-group">
								<div class="col-md-4">
									<label>Audit Ratio</label>
								</div>
								<div class="col-md-3">
									<label>Greater than</label>
									<input type="number" class="custom_number_box" name="audit_ratio_start" id="audit_ratio_start">
								</div>
								<div class="col-md-3">
									<label>but less than</label>
									<input type="number" class="custom_number_box" name="audit_ratio_end" id="audit_ratio_end">
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="row form-group">
								<div class="col-md-4">
									<label>Overall Performance Rating</label>
								</div>
								<div class="col-md-3">
									<label>Greater than</label>
									<input type="number" class="custom_number_box" name="rating_start" id="rating_start">
								</div>
								<div class="col-md-3">
									<label>but less than</label>
									<input type="number" class="custom_number_box" name="rating_end" id="rating_end">
								</div>
							</div>
						</div>

						<div class="col-md-12">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>Number COC for Allocation</label>
										<input type="text" class="form-control" name="no_coc_allocation" id="no_coc_allocation">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Maximum number of Audits allocated per plumber</label>
										<input type="number" class="form-control" name="max_allocate_plumber" id="max_allocate_plumber">
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-12 text-right">
							<button type="button" name="submit" value="submit" class="btn btn-primary search">Search</button>
							<button type="button" name="reset" value="reset" class="btn btn-primary reset">Reset</button>
						</div>
					</div>
				</form>
				
				<div class="tableaccordion table-responsive m-t-40 displaynone">
					<h4 class="card-title">Recommend Allocation Results</h4>
					<table class="parenttable">
						<thead>
							<tr>
								<th>Plumber</th>
								<th>Reg Number</th>
								<th>Employee Company</th>
								<th>Employee City</th>
								<th>Employee Province</th>
								<th>Audit Ratio</th>
								<th>Cautionary Ratio</th>
								<th>Refix (Complete) Ratio</th>
								<th>Refix (In-Complete) Ratio</th>
								<th>Overall Performance Rating</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<tr class="norecordfound displaynone"><td colspan="11">No Record Found</td></tr>
						</tbody>
					</table>
					<div class="dataTables_wrapper">
						<div class="dataTables_info"></div>
						<div id="pagination" class="dataTables_paginate"></div>
					</div>
				</div>
				
				<div class="audit_queue displaynone m-t-30">
					<h4 class="card-title">Allocation Queue</h4>
						<table class="table table-bordered table-striped queue_table fullwidth m-t-15">
							<thead>
								<tr>
									<th>COC Number</th>
									<th>Installation Code(s) of COC</th>
									<th>Suburb</th>
									<th>City</th>
									<th>Province</th>
									<th>Auditor Name</th>
									<th>Audit Allocation MTD</th>
									<th>Open Audits</th>
									<th>Allocate</th>
									<th>Cancel</th>
								</tr>
							</thead>
						</table>
					</form>
				</div>
				
				<div class="audit_summary displaynone m-t-30">
					<h4 class="card-title">Audit Summary</h4>
					<div class="row">
						<div class="col-md-4">
							<p>Number COC recommended for audit:</p>
						</div>
						<div class="col-md-4">
							<input type="text" class="form-control markedcount" placeholder="Count of all COC marked for allocation" disabled>
						</div>
						<div class="col-md-4">
							<input type="text" class="form-control markedcountpercentage" placeholder="Count of all COC marked for allocation as a % of the cocs brought back per date range" disabled>
						</div>
					</div>
					<form action="" method="post">
						<table class="table table-bordered table-striped auditor_table fullwidth m-t-15">
							<thead>
								<tr>
									<th>Auditor Name</th>
									<th>Audit Allocation MTD</th>
									<th>Open Audits</th>
									<th>Allocation for above selection</th>
								</tr>
							</thead>
						</table>
						<div class="text-right">
							<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#confirmmodal">Allocate Audits</button>
							<button type="submit" class="summarysubmit displaynone"></button>
						</div>
					</form>
				</div>
				
			</div>
		</div>
	</div>
</div>

<div id="confirmmodal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12 text-center">
						<div class="form-group">
							<h4 class="mb-15">Confirm that you wish to Allocate <span class="confirmcoc"></span> Audits.</h4>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<input type="text" class="form-control confirmtext" name="confirmtext" placeholder="Type in Yes to Confirm">
							<span class="tagline confirmtagline displaynone">Please fill with confirm text.</span>
						</div>
					</div>
					<div class="col-md-12 text-center">
						<button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-success confirmsubmit">Confirm</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

	
<script>
	var totalcoccount = '<?php echo $totalcoccount; ?>';
	
	$(function(){
		select2('#province1, #city1');
		datepicker('#start_date_range');
		datepicker('#end_date_range');
		citysuburb(['#province1','#city1'], ['']);
		
		queuelist();
	});

	$('.search').on('click',function(){		
		datatable();
		$('.tableaccordion').removeClass('displaynone');
	});

	$('.reset').on('click',function(){		
		$('#start_date_range,#end_date_range,#start_coc_range,#end_coc_range,#user_search,#user_id,#audit_ratio_start,#audit_ratio_end,#rating_start,#rating_end,#rating_end,#no_coc_allocation,#max_allocate_plumber').val('');
		$('#province1,#city1').val('').trigger("change");
		$('#compulsory_audit').prop('checked', false);
		$('.tableaccordion').addClass('displaynone');
		$(document).find('.parenttablecontent').remove();
		$(document).find('.childrow').remove();
		$(document).find('.auditorallocate').remove();
		$('.audit_summary').addClass('displaynone');
	});
	
	function datatable(pagination=0){
		var max_allocate_plumber = $('#max_allocate_plumber').val() ;
		
		$('.norecordfound').addClass('displaynone');
		$(document).find('.parenttablecontent').remove();
		$(document).find('.childrow').remove();
		$(document).find('.auditorallocate').remove();
		$('.audit_summary').addClass('displaynone');
		
		var url 	= 	'<?php echo base_url()."admin/audits/cocallocate/index/DTAllocateAudit/"; ?>'+pagination;
		var data	= 	{ 
							start_date_range		: $('#start_date_range').val(), 
							end_date_range			: $('#end_date_range').val(), 
							start_coc_range			: $('#start_coc_range').val(), 
							end_coc_range			: $('#end_coc_range').val(), 
							user_id					: $('#user_id').val(), 
							province				: $('#province1').val(), 
							city					: $('#city1').val(), 
							compulsory_audit		: ($('#compulsory_audit').is(':checked')) ? $('#compulsory_audit').val() : '', 
							audit_ratio_start		: $('#audit_ratio_start').val(), 
							audit_ratio_end			: $('#audit_ratio_end').val(), 
							rating_start			: $('#rating_start').val(), 
							rating_end				: $('#rating_end').val(), 
							no_coc_allocation		: $('#no_coc_allocation').val()
						};
						
		ajax(url, data, '', {
			success : function(result){
				
				var table = [];
				
				$(result.data).each(function(i,v){
					var row = '\
						<tr class="parenttablecontent" data-index="'+i+'">\
							<td>'+v.plumbername+'</td>\
							<td>'+v.regno+'</td>\
							<td>'+v.company+'</td>\
							<td>'+v.city+'</td>\
							<td>'+v.province+'</td>\
							<td>'+v.audit+'</td>\
							<td>'+v.cautionary+'</td>\
							<td>'+v.refix_incomplete+'</td>\
							<td>'+v.refix_complete+'</td>\
							<td>'+v.rating+'</td>\
							<td>'+v.coc_link+'</td>\
						</tr>\
						<tr class="childrow" id="childrow'+i+'">\
							<td colspan="11">\
								<div class="childwrapper">\
									<table class="childtable">\
										<thead>\
											<tr>\
												<th>COC Number</th>\
												<th>Installation Code(s) of COC</th>\
												<th>Suburb</th>\
												<th>City</th>\
												<th>Province</th>\
												<th>Auditor Name</th>\
												<th>Audit Allocation MTD</th>\
												<th>Open Audits</th>\
												<th>Add to Queue</th>\
											</tr>\
										</thead>\
										<tbody></tbody>\
									</table>\
								</div>\
							</td>\
						</tr>\
					';
					
					table.push(row);
				})
				
				$('#pagination').html(result.pagination);
				$('.dataTables_info').html(result.info);
				
				if(table.length > 0){
					$('.parenttable tbody').append(table.join(""));
					
					if(max_allocate_plumber!=''){
						$(document).find('.parenttablecontent').each(function(){
							$(this).find('.cocaccordion').click();
						})
					}
				}else{
					$('.norecordfound').removeClass('displaynone');
				}
			}
		})
	}
	
	$(document).on('click', '#pagination span', function(e){
		e.preventDefault(); 
		datatable($(this).find('a').attr('data-ci-pagination-page'));
	});
	
	$(document).on('click', '.cocaccordion', function(){
		var _this					= $(this);
		var rowindex				= $(this).parent().parent().attr('data-index');
		var userid 					= $(this).attr('data-user-id');
		var coc_count 				= $(this).attr('data-coc-count');
		var max_allocate_plumber 	= $('#max_allocate_plumber').val();
		
		_this.parent().parent().toggleClass("open").next(".childrow").toggleClass("open");
		
		if(_this.parent().parent().hasClass("open")){
			_this.parent().find('.fa-caret-down').removeClass('displaynone');
			_this.parent().find('.fa-caret-up').addClass('displaynone');
		}else{
			_this.parent().find('.fa-caret-down').addClass('displaynone');
			_this.parent().find('.fa-caret-up').removeClass('displaynone');
		}
		
		$(document).find('#childrow'+rowindex+' .removecoc').remove();
				
		var data = {
			user_id					: userid, 
			coc_count				: coc_count, 
			start_date_range		: $('#start_date_range').val(), 
			end_date_range			: $('#end_date_range').val(), 
			start_coc_range			: $('#start_coc_range').val(), 
			end_coc_range			: $('#end_coc_range').val(), 
			no_coc_allocation		: $('#no_coc_allocation').val(), 
			max_allocate_plumber	: $('#max_allocate_plumber').val(),
			province				: $('#province1').val(), 
			city					: $('#city1').val(), 
		}
		
		ajax('<?php echo base_url()."admin/audits/cocallocate/index/coc"; ?>', data, '', {
			success: function(data){
				
				if(data.result.length > 0){
					var table = [];
					
					$(data.result).each(function(i, v){			
						var checkallocate 	= '';
						var postauditorid 	= '';
						var postauditorname = '';
						var openaudit		= '0';
						var allowedaudit 	= '0';
						var mtd				= '0';
						var automation		= '0';
						
						if((max_allocate_plumber!='' && i < max_allocate_plumber)){
							automation = '1';
						}
						
						if(max_allocate_plumber==''){						
							var autoresult 		= userautocomplete([], ['', 5, {suburb : v.suburbname,city : v.cityname,province : v.provincename, row : '1'}], '', 1);
							$(autoresult).each(function(index, values){
								if(values==null) return false;
								postauditorname = values.name;
								postauditorid 	= values.id;
								openaudit 		= values.openaudit;
								allowedaudit 	= values.allowedaudit;
								mtd 			= values.mtd;
							})
						}
						
						var installationcode = (v.installationcode.charAt(0)==',') ? v.installationcode.substring(1) : v.installationcode;
						
						var data 	= '\
							<tr class="removecoc">\
								<td>'+v.coc_id+'</td>\
								<td>'+installationcode+'</td>\
								<td>'+v.suburbname+'</td>\
								<td>'+v.cityname+'</td>\
								<td>'+v.provincename+'</td>\
								<td>\
									<input type="text" autocomplete="off" class="form-control auditor_search" id="auditor_search_'+v.coc_id+'" data-cocid="'+v.coc_id+'" data-suburb="'+v.suburbname+'"  data-city="'+v.cityname+'"  data-province="'+v.provincename+'" data-allowedaudit="'+allowedaudit+'" value="'+postauditorname+'" style="width:100px">\
									<input type="hidden" class="auditor_id" id="auditor_id_'+v.coc_id+'" value="'+postauditorid+'">\
									<input type="hidden" class="plumber_id" id="plumber_id_'+v.coc_id+'" value="'+userid+'">\
									<div class="auditor_suggestion" id="auditor_suggestion_'+v.coc_id+'"></div>\
								</td>\
								<td class="">'+mtd+'</td>\
								<td class="openaudit">'+openaudit+'</td>\
								<td><input type="checkbox" name="queue" class="queue" data-automation="'+automation+'" data-cocid="'+v.coc_id+'" '+checkallocate+'></td>\
							</tr>\
						'; 
						
						table.push(data);
					})
					
					$('#childrow'+rowindex+' tbody').append(table.join(''));	

					if(max_allocate_plumber!=''){
						$(document).find('#childrow'+rowindex+' tbody tr').each(function(){
							if($(this).find('.queue').attr('data-automation')=='1'){
								$(this).find('.queue').click();
							}
						})
					}
					
				}
			}
		});
	})	
	
	$(document).on('click', '.queue', function(){		
		var _thistr			= $(this).parent().parent();
			
		if($(this).attr('data-automation')=='1'){
			var suburbname 		= _thistr.find('td:nth-child(3)').text();
			var cityname 		= _thistr.find('td:nth-child(4)').text();
			var provincename 	= _thistr.find('td:nth-child(5)').text();
			
			var autoresult 		= userautocomplete([], ['', 5, {suburb : suburbname,city : cityname,province : provincename}], '', 1);
			$(autoresult).each(function(ii, vv){
				$(vv).each(function(index, values){
					var postauditorname1 		= values.name;
					var postauditorid1 			= values.id;
					var openaudit1 				= values.openaudit;
					var allowedaudit1 			= values.allowedaudit;
					var mtd1 					= values.mtd;
					var dynamicallowedaudit1 	= (parseInt($(document).find('.postauditorid[value="'+postauditorid1+'"]').length) + parseInt(openaudit1));
					
					if(allowedaudit1 <= dynamicallowedaudit1){
						return;
					}
					
					_thistr.find('.auditor_search').attr('data-allowedaudit', allowedaudit1).val(postauditorname1);
					_thistr.find('.auditor_id').val(postauditorid1);
					_thistr.find('td:nth-child(7)').text(mtd1);
					_thistr.find('td:nth-child(8)').text(openaudit1);
					
					return false;
				})
			})
		}

		$(this).removeAttr('data-automation');
		
		var cocid 			= $(this).attr('data-cocid');
		var auditorid 		= _thistr.find('#auditor_id_'+cocid).val();
		
		if(auditorid==''){
			$(this).prop('checked', false);
			_thistr.find('#auditor_id_'+cocid).focus();
			return false;
		}
		
		queueaction(_thistr, 'insert');
		_thistr.remove();
	});
	
	$(document).on('click', '.cancel', function(){
		$(document).find('.parenttablecontent').removeClass('open');
		$(document).find('.childrow').removeClass('open');
		$(document).find('.fa-caret-up').removeClass('displaynone');
		$(document).find('.fa-caret-down').addClass('displaynone');
		
		queueaction($(this).parent().parent(), 'delete');
		$(this).parent().parent().remove();
	});
	
	function queueaction(_this, type){
		var data = {
			coc_id 				: _this.find('td:eq(0)').text(),
			installation_code 	: _this.find('td:eq(1)').text(),
			suburb 				: _this.find('td:eq(2)').text(),
			city 				: _this.find('td:eq(3)').text(),
			province 			: _this.find('td:eq(4)').text(),
			plumber_id 			: _this.find('td:eq(5) .plumber_id').val(),
			auditor_id 			: _this.find('td:eq(5) .auditor_id').val(),
			type				: type
		}
		
		ajax('<?php echo base_url()."admin/audits/cocallocate/index/actionQueue"; ?>', data, '', {success: function(data){ queuelist(); }});
	}
	
	function queuelist(){
		ajax('<?php echo base_url()."admin/audits/cocallocate/index/getQueue"; ?>', {}, '', {success: function(data){
			if(data.result.length > 0){
				var table 			= [];
				
				$(data.result).each(function(i, v){	
					var auditorname 	= '';
					var allowedaudit 	= '0';
					var mtd				= '0';
					var openaudit		= '0';
					var checkallocate 	= ($(document).find('.postcocid[value="'+v.coc_id+'"]').length > 0) ? 'checked="checked"' : '';
					var autoresult 		= userautocomplete([], ['', 5, {auditorid : v.auditor_id, row : '1'}], '', 1);
					
					$(autoresult).each(function(index, values){
						auditorname 	= values.name;
						allowedaudit 	= values.allowedaudit;
						mtd				= values.mtd;
						openaudit		= values.openaudit;
					});
					
					var data 	= '\
						<tr class="removequeue">\
							<td>'+v.coc_id+'</td>\
							<td>'+v.installation_code+'</td>\
							<td>'+v.suburb+'</td>\
							<td>'+v.city+'</td>\
							<td>'+v.province+'</td>\
							<td>\
								<input type="text" autocomplete="off" class="form-control auditor_search" id="auditor_search_'+v.coc_id+'" data-cocid="'+v.coc_id+'" data-suburb="'+v.suburb+'"  data-city="'+v.city+'"  data-province="'+v.province+'" data-allowedaudit="'+allowedaudit+'" value="'+auditorname+'" style="width:100px">\
								<input type="hidden" class="auditor_id" id="auditor_id_'+v.coc_id+'" value="'+v.auditor_id+'">\
								<input type="hidden" class="plumber_id" id="plumber_id_'+v.coc_id+'" value="'+v.plumber_id+'">\
								<div class="auditor_suggestion" id="auditor_suggestion_'+v.coc_id+'"></div>\
							</td>\
							<td class="">'+mtd+'</td>\
							<td class="openaudit">'+openaudit+'</td>\
							<td><input type="checkbox" name="allocate" class="allocate" data-cocid="'+v.coc_id+'" '+checkallocate+'></td>\
							<td><input type="checkbox" name="cancel" class="cancel" data-cocid="'+v.coc_id+'" ></td>\
						</tr>\
					'; 
					
					table.push(data);
				})
				
				
				$('.audit_queue').removeClass('displaynone');
				$(document).find('.queue_table .removequeue').remove();
				$('.queue_table').append(table);
			}else{
				$('.audit_queue').addClass('displaynone');
			}
		}});
	}
	
	$(document).on('keyup', '.auditor_search', function(){
		var cocid 		= $(this).attr('data-cocid');
		var suburb 		= $(this).attr('data-suburb');
		var city 		= $(this).attr('data-city');
		var province 	= $(this).attr('data-province');
		
		$("#auditor_id_"+cocid).val('');
		removeauditsummary(cocid)
		$(this).parent().parent().find('.allocate').prop('checked', false);
		userautocomplete(['#auditor_search_'+cocid, '#auditor_id_'+cocid, '#auditor_suggestion_'+cocid], [$(this).val(), 5, {suburb : suburb,city : city,province : province}], auditextras);
	})
	
	function auditextras(_this, openaudit, mtd, allowedaudit){
		_this.attr('data-allowedaudit', allowedaudit);
		_this.parent().parent().find('td:nth-child(7)').text(mtd);
		_this.parent().parent().find('td:nth-child(8)').text(openaudit);
		queueaction(_this.parent().parent(), 'update');
	}
	
	$(document).on('keyup', '#user_search', function(){
		$("#user_id").val('');
		userautocomplete(['#user_search', '#user_id', '#user_suggestion'], [$(this).val(), 3]);
	})
	
	$(document).on('click', '.allocate', function(){		
		var _thistr			= $(this).parent().parent();
			
		if($(this).attr('data-automation')=='1'){
			var suburbname 		= _thistr.find('td:nth-child(3)').text();
			var cityname 		= _thistr.find('td:nth-child(4)').text();
			var provincename 	= _thistr.find('td:nth-child(5)').text();
			
			var autoresult 		= userautocomplete([], ['', 5, {suburb : suburbname,city : cityname,province : provincename}], '', 1);
			$(autoresult).each(function(ii, vv){
				$(vv).each(function(index, values){
					var postauditorname1 		= values.name;
					var postauditorid1 			= values.id;
					var openaudit1 				= values.openaudit;
					var allowedaudit1 			= values.allowedaudit;
					var mtd1 					= values.mtd;
					var dynamicallowedaudit1 	= (parseInt($(document).find('.postauditorid[value="'+postauditorid1+'"]').length) + parseInt(openaudit1));
					
					if(allowedaudit1 <= dynamicallowedaudit1){
						return;
					}
					
					_thistr.find('.auditor_search').attr('data-allowedaudit', allowedaudit1).val(postauditorname1);
					_thistr.find('.auditor_id').val(postauditorid1);
					_thistr.find('td:nth-child(7)').text(mtd1);
					_thistr.find('td:nth-child(8)').text(openaudit1);
					
					return false;
				})
			})
		}

		$(this).removeAttr('data-automation');
		
		var cocid 			= $(this).attr('data-cocid');
		var auditorsearch	= _thistr.find('#auditor_search_'+cocid);
		var auditorid 		= _thistr.find('#auditor_id_'+cocid).val();
		var auditorname 	= _thistr.find('#auditor_search_'+cocid).val();
		var plumberid 		= _thistr.find('#plumber_id_'+cocid).val();
		var mtd 			= _thistr.find('td:nth-child(7)').text();
		var openaudit 		= _thistr.find('td:nth-child(8)').text();
		
		if(auditorid==''){
			$(this).prop('checked', false);
			_thistr.find('#auditor_id_'+cocid).focus();
			return false;
		}
		
		if($(this).is(':checked')){
			var allowedaudit 		= auditorsearch.attr('data-allowedaudit');
			var dynamicallowedaudit = (parseInt($(document).find('.postauditorid[value="'+auditorid+'"]').length) + parseInt(openaudit));
			
			if(allowedaudit <= dynamicallowedaudit){
				sweetalertautoclose('Auditor has reached maximum no of allocation.');
				$(this).prop('checked', false);
				return false;
			}
			
			auditsummary(cocid, plumberid, auditorid, auditorname, mtd, openaudit);
			var coclength 		= $(document).find('.auditorcocid').length;
			var cocpercentage 	= ((coclength/totalcoccount)*100).toFixed(2)+'%';
			$('.confirmcoc').text(coclength);
			$('.markedcount').val(coclength);
			$('.markedcountpercentage').val(cocpercentage);
		}else{
			removeauditsummary(cocid, auditorid);
		}
	});
	
	function removeauditsummary(cocid, auditorid=''){
		if(auditorid!=''){
			$(document).find('.auditorcocid[data-auditorcocid="'+cocid+'"]').remove();
			if($(document).find('.auditorallocate[data-auditorid="'+auditorid+'"]').find('.auditorcocid').length==0){
				$(document).find('.auditorallocate[data-auditorid="'+auditorid+'"]').remove();
			}else{
				var auditval = $(document).find('.auditorallocate[data-auditorid="'+auditorid+'"] td:nth-child(4) span').text();
				$(document).find('.auditorallocate[data-auditorid="'+auditorid+'"] td:nth-child(4) span').text(parseInt(auditval)-1);			
			}
		}else{
			var cocidremove = $(document).find('.auditorcocid[data-auditorcocid="'+cocid+'"]');
			
			if(cocidremove.parent().find('.auditorcocid').length < 2){
				cocidremove.parent().parent().remove();
			}else{
				var auditval = cocidremove.parent().parent().find('td:nth-child(4) span').text();
				cocidremove.parent().parent().find('td:nth-child(4) span').text(parseInt(auditval)-1);
			}
		}
		
		if($(document).find('.auditorallocate').length==0) $('.audit_summary').addClass('displaynone');
	}
	
	var auditorcount = 1;
	
	function auditsummary(cocid, plumberid, auditorid, auditorname, mtd, openaudit){
		var checkauditor 	= 	$(document).find('.auditorallocate[data-auditorid="'+auditorid+'"]').length;
		var appendfield		= 	'<div class="auditorcocid" data-auditorcocid="'+cocid+'">\
									<input type="hidden" name="allocate['+(auditorcount)+'][auditorid]" class="postauditorid" value="'+auditorid+'">\
									<input type="hidden" name="allocate['+(auditorcount)+'][plumberid]" class="postplumberid" value="'+plumberid+'">\
									<input type="hidden" name="allocate['+(auditorcount++)+'][cocid]" class="postcocid" value="'+cocid+'">\
								</div>';
						
		if(checkauditor > 0){
			var auditval = $(document).find('.auditorallocate[data-auditorid="'+auditorid+'"] td:nth-child(4) span').text();
			$(document).find('.auditorallocate[data-auditorid="'+auditorid+'"] td:nth-child(4) span').text(parseInt(auditval)+1);
			$(document).find('.auditorallocate[data-auditorid="'+auditorid+'"] td:nth-child(4)').append(appendfield);
		}else{
			var auditortable = 	'<tr data-auditorid="'+auditorid+'" class="auditorallocate">\
									<td>'+auditorname+'</td>\
									<td>'+mtd+'</td>\
									<td>'+openaudit+'</td>\
									<td>\
										<span>1</span>\
										'+appendfield+'\
									</td>\
								</tr>';
								
			$('.auditor_table').append(auditortable);		
			$('.audit_summary').removeClass('displaynone');
		}
	}
	
	$('.confirmsubmit').click(function(){
		$('.confirmtagline').addClass('displaynone');
		
		if($('.confirmtext').val().toLowerCase()=='yes'){
			$('.summarysubmit').click();
		}else{
			$('.confirmtagline').removeClass('displaynone');
		}
	})
</script>