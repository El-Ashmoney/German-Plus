@extends('layouts.simple')

@section('page-content')

<div class="container">
    <div class="box box-info login-box">
      
        <div class="register-box-body">
            <p class="login-box-msg">{{__('Register a new membership')}}</p>
            @include('partials.formerrors')
            <form  action="{{ route('user.register') }}" method="post">
                @csrf
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" id="name" name="name" placeholder="{{__('Name')}}">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="email" class="form-control" id="email" name="email" placeholder="{{__('Email')}}">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" id="password" name="password" placeholder="{{__('Password')}}">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="{{__('retype password')}}">
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-sm-4 col-sm-offset-8">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">{{__('Register')}}</button>
                    </div>
                </div>            
            </form>
            <a href="{{route('user.login')}}" class="text-center">{{__('I already have a membership')}}</a>
            
        </div>

    </div>
</div>
@endsection