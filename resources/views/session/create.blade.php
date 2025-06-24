@extends('layouts.simple')

@section('page-content')

<div class="container">
    <div class="box box-info login-box">
 
        <div class="register-box-body">
            <p class="login-box-msg">{{ __('Sign in to start your session') }}</p>
            @include('partials.formerrors')
            <form  action="{{ route('user.login') }}" method="post">
                @csrf
                <div class="form-group has-feedback">
                    <input type="email" class="form-control" id="email" name="email" placeholder="{{__('Email')}}">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" id="password" name="password" placeholder="{{__('Password')}}">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-sm-4 col-sm-offset-8">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">{{__('Signin')}}</button>
                    </div>
                </div>
            </form>
            <a href="{{route('password.reset')}}">{{__('I forgot my password')}}</a><br>
            <a href="{{route('user.register')}}" class="text-center">{{__('Register a new membership')}}</a>
        </div>

    </div>
</div>
@endsection