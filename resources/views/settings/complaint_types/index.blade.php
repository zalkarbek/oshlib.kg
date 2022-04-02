@extends('layouts.settings.default')
@section('settings_title', 'Типы жалоб')
@section('settings_content')
  @include('flash::message')
  <div class="card">
    <div class="card-header">
      <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-list mr-2"></i>Типы жалоб</a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="{!! route('complaintTypes.create') !!}"><i class="fa fa-plus mr-2"></i>Создать</a>
        </li>
      </ul>
    </div>
    <div class="card-body">
      @include('settings.complaint_types.table')
      <div class="clearfix"></div>
    </div>
  </div>
</div>
@endsection

