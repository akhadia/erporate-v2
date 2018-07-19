
{{-- <div class="modal fade in" id="modal-default" style="display: block; padding-right: 15px;"> --}}
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" id="modal-btn-close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span></button>
          <h4 class="modal-title">{{ $title }}</h4>
        </div>

        {{-- <div class="modal-body">
          <p>One fine body…</p>
        </div> --}}
        @yield('sub-content')

        <div class="modal-footer" style="display:none">
          <button type="button" id="close-popup" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          {{-- <button type="button" class="btn btn-primary" id="modal-btn-submit">Save changes</button> --}}
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
{{-- </div> --}}

@stack('modal-js')
