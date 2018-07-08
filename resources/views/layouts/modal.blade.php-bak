{{-- <div class="modal fade" id="modal-default"> --}}
    <div class="modal-dialog @yield('modalClass')">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">{{ $title }}</h4>
            </div>
           
            @yield('sub-content')
            
            <div class="modal-footer" style="display:none">
                <button type="button" id="close-popup" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
{{-- </div> --}}
<!-- /.modal -->
@stack('css')
<style type="text/css"> 
    table.dataTable thead th {
        border-bottom: 0;
    }
    table.dataTable.no-footer {
        border-bottom: 0;
    }
</style>

@stack('js')
<script type="text/javascript">
	$(document).ready(function(){
		//$('.tt-hint').removeClass('required');
    	//$('.tt-hint').hide();
	});
</script>