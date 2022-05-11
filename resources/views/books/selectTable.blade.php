<link rel="stylesheet" href="{{asset('dist/css/adminlte.css')}}">
@include('layouts.datatables_css')

{!! $dataTable->table(['width' => '100%']) !!}

<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
@include('layouts.datatables_js')
{!! $dataTable->scripts() !!}
