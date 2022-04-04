@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{trans('lang.attribute_plural')}}<small class="ml-3 mr-3">|</small><small>{{trans('lang.attribute_desc')}}</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/console/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
          <li class="breadcrumb-item"><a href="{!! route('attributes.index') !!}">{{trans('lang.attribute_plural')}}</a>
          </li>
          <li class="breadcrumb-item active">{{trans('lang.attribute_table')}}</li>
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
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.attribute_table')}}</a>
        </li>
        @can('attributes.create')
        <li class="nav-item">
          <a class="nav-link" href="{!! route('attributes.create') !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.attribute_create')}}</a>
        </li>
        @endcan
        @include('layouts.right_toolbar', compact('dataTable'))
      </ul>
    </div>
    <div class="card-body">
      @include('attributes.table')
      <div class="clearfix"></div>
    </div>
  </div>
</div>
@endsection

