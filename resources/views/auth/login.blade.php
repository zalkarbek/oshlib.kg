@extends('layouts.auth.default')
@section('content')
    <div class="card-body login-card-body">
        <div class="card-body login-card-body">
            <p class="login-box-msg">{{__('auth.login_title')}}</p>

            <form action="{{ url('/login') }}" method="post">
                {!! csrf_field() !!}

                <div class="input-group mb-3">
                    <input value="{{ old('phone') }}" type="tel" class="form-control {{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" placeholder="{{ __('auth.phone') }}" aria-label="{{ __('auth.phone') }}">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                    </div>
                    @if ($errors->has('phone'))
                        <div class="invalid-feedback">
                            {{ $errors->first('phone') }}
                        </div>
                    @endif
                </div>

                <div class="input-group mb-3">
                    <input value="{{ old('password') }}" type="password" class="form-control  {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="{{__('auth.password')}}" aria-label="{{__('auth.password')}}">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                    </div>
                    @if ($errors->has('password'))
                        <div class="invalid-feedback">
                            {{ $errors->first('password') }}
                        </div>
                    @endif
                </div>

                <div class="row mb-2">
                    <div class="col-8">
                        <div class="checkbox icheck">
                            <label> <input type="checkbox" name="remember"> {{__('auth.remember_me')}}
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">{{__('auth.login')}}</button>
                    </div>
                    <!-- /.col -->
                </div>

            </form>

        </div>
    </div>
@endsection


