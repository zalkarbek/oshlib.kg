@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{trans('lang.publisher_plural')}}<small class="ml-3 mr-3">|</small><small>{{trans('lang.publisher_desc')}}</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
          <li class="breadcrumb-item"><a href="{!! route('publishers.index') !!}">{{trans('lang.publisher_plural')}}</a>
          </li>
          <li class="breadcrumb-item active">{{trans('lang.publisher_table')}}</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<div class="content">
  <div class="clearfix"></div>
  @include('flash::message')
  <div class="card">
    <div class="card-header">
      <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.publisher_table')}}</a>
        </li>
        @can('publishers.create')
        <li class="nav-item">
          <a class="nav-link" href="{!! route('publishers.create') !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.publisher_create')}}</a>
        </li>
        @endcan
        {{-- @include('layouts.right_toolbar', compact('dataTable')) --}}
      </ul>
    </div>
    <div class="card-body">
      @include('publishers.table')
      <div class="clearfix"></div>
    </div>
  </div>
</div>
@endsection

