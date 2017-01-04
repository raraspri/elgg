define(function(require) {
    
    var $ = require("jquery");
    var elgg = require('elgg');
    require('ticketup/jquery.dataTables.min');

	$(document).on('change', '#inputUploadTicket', changeUploadTicket);


	function changeUploadTicket(event) {
		$('.elgg-form-ticketup-upload').submit();
	};


	//Table
	$(document).ready(function() {
	    $('#tableTicket').DataTable( {
	        "pagingType": "full_numbers",
	        "language": {
	            "lengthMenu": elgg.echo('ticketup:datatable:displayElements') + " _MENU_ " + elgg.echo('ticketup:datatable:displayElementsPerPage'),
	            "zeroRecords": elgg.echo('ticketup:datatable:notfoundresult'),
	            "info": elgg.echo('ticketup:datatable:showPage') + "_PAGE_ / _PAGES_",
	            "infoEmpty": "No records available",
	            "infoFiltered": "(filtered from _MAX_ total records)"
	        }	

	    } );
	});
});