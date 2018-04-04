	
    
    
    

	<link rel="stylesheet" href="assets/js/datatables/responsive/css/datatables.responsive.css">
	<link rel="stylesheet" href="assets/js/select2/select2-bootstrap.css">
	<link rel="stylesheet" href="assets/js/select2/select2.css">
	<link rel="stylesheet" href="assets/js/selectboxit/jquery.selectBoxIt.css">

   	<!-- Bottom Scripts -->
	<script src="assets/js/gsap/main-gsap.js"></script>
	<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
	<script src="assets/js/bootstrap.js"></script>
	<script src="assets/js/joinable.js"></script>
	<script src="assets/js/resizeable.js"></script>
	<script src="assets/js/neon-api.js"></script>
	<script src="assets/js/toastr.js"></script>
    <script src="assets/js/jquery.validate.min.js"></script>
	<script src="assets/js/moment.min.js"></script>
	<script src="assets/js/fullcalendar/fullcalendar.min.js"></script>
    <script src="assets/js/bootstrap-datepicker.js"></script>
    <script src="assets/js/fileinput.js"></script>
    
    <script src="assets/js/jquery.dataTables.min.js"></script>
	<script src="assets/js/datatables/TableTools.min.js"></script>
	<script src="assets/js/dataTables.bootstrap.js"></script>
	<script src="assets/js/datatables/jquery.dataTables.columnFilter.js"></script>
	<script src="assets/js/datatables/lodash.min.js"></script>
	<script src="assets/js/datatables/responsive/js/datatables.responsive.js"></script>
    <script src="assets/js/select2/select2.min.js"></script>
	<script src="assets/js/selectboxit/jquery.selectBoxIt.min.js"></script>

<!--	<script src="assets/tinymce/tinymce.min.js"></script>-->

	<script src="assets/js/neon-calendar.js"></script>
	<script src="assets/js/neon-chat.js"></script>
	<script src="assets/js/neon-custom.js"></script>
	<script src="assets/js/neon-demo.js"></script>
	<script src="assets/js/mine.js"></script>
	<script src="assets/js/sketch.js"></script>
	<script type="text/javascript" src="assets/ckeditor/ckeditor.js"></script>
	<script type="text/javascript" src="assets/js/datetime/DateTimePicker.js"></script>
	<script type="text/javascript" src="assets/adminlte/js/fastclick.min.js"></script>
	<script type="text/javascript" src="assets/adminlte/js/app.js"></script>
	<script type="text/javascript" src="assets/adminlte/js/jquery.slimscroll.min.js"></script>



	<!-- SHOW TOASTR NOTIFIVATION -->
<?php if ($this->session->flashdata('flash_message') != ""):?>

<script type="text/javascript">
	toastr.success('<?php echo $this->session->flashdata("flash_message");?>');
</script>



<?php endif;?>


<!-----  DATA TABLE EXPORT CONFIGURATIONS ---->                      
<script type="text/javascript">
var datatable;
	jQuery(document).ready(function($)
	{


		var datatable = $("#table_export").dataTable({
			"sPaginationType": "bootstrap",
			"sDom": "<'row'<'col-xs-3 col-left'l><'col-xs-9 col-right'<'export-data'T>f>r>t<'row'<'col-xs-3 col-left'i><'col-xs-9 col-right'p>>",
			"oTableTools": {
				"aButtons": [

					{
						"sExtends": "xls",
						"mColumns": [1,2,3,4,5]
					},
					{
						"sExtends": "pdf",
						"mColumns": [1,2,3,4,5]
					},
					{
						"sExtends": "print",
						"fnSetText"    : "Press 'esc' to return",
						"fnClick": function (nButton, oConfig) {
							datatable.fnSetColumnVis(5, false);

							this.fnPrint( true, oConfig );

							window.print();

							$(window).keyup(function(e) {
								if (e.which == 27) {
									datatable.fnSetColumnVis(5, true);
								}
							});
						},

					},
				]
			},

		});

		
		$(".dataTables_wrapper select").select2({
			minimumResultsForSearch: -1
		});

		$('#dtBox').DateTimePicker({
			dateFormat:	"dd-MM-yyyy"
		});

		$('.number').blur(function(){
			if(this.value.trim() == "")
			return;
				this.value = parseFloat(this.value.replace(/,/g, "").replace("N",""))
					.toFixed(2)
					.toString()
					.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

				// document.getElementById("display").value = this.value.replace(/,/g, "")

		});


		format_numbers_now();
	});

	function format_numbers_now(mz,fixed){
		if(mz == undefined)
			mz = "";

		if(fixed == undefined)
			fixed = 2;

		$.each($('.format_number'),function(){
			if(this.innerHTML.trim() == "")
				return;

			this.innerHTML = mz+" "+parseFloat(this.innerHTML.replace(/,/g, ""))
					.toFixed(fixed)
					.toString()
					.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

			// document.getElementById("display").value = this.value.replace(/,/g, "")

		});
	}

	$('select').select2();
		
</script>

<style>
	.label{
		font-size: 12px !important;
	}
</style>