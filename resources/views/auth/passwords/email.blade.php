@extends('layouts.simple')

@section('page-content')


<div class="container">
    <div class="box box-info login-box">
        <div class="login-box-body">
        <p class="login-box-msg">{{  __('Reset your password') }}</p>

        @if (session('status'))
            <div class="alert alert-success alert-dismissible">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-group row">

                <div class="col-md-12">
                    <input id="email" placeholder="{{ __('Email address') }}" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                    @if ($errors->has('email'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-4 col-offset-sm-8">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Send Password Reset Link') }}
                    </button>
                </div>
            </div>
        </form>

        </div>
    </div> 
</div>



@endsection