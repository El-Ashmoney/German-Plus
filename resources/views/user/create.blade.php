@extends('layouts.dashboard')
@section('title', 'Create user')
@section('page-header', 'Create user')
@section('page-description', 'Create new user')
@section('page-content')
@section('header-scripts')

<link rel="stylesheet" href="{{ asset('css/global.css') }}">

@endsection

@section('breadcrumb')

<ol class="breadcrumb">
    <li><a href="{{ route('user.index') }}"><i class="fa fa-dashboard"></i> All users</a></li>
    <li class="active">Create user</li>
</ol>

@endsection

<div class="row">

    <div class="col-sm-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">user info</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" action="{{ route('user.store') }}" method="post">
                @csrf
                <div class="box-body">
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-9">
                            <input required type="email" class="form-control" id="email" name="email" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-sm-3 control-label">Password</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation" class="col-sm-3 control-label">Password Confirmation</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="role" class="col-sm-3 control-label">Role</label>
                        <div class="col-sm-9">
                            <select name="role_id" id="role" class="form-control">
                                @foreach ($roles as $role)
                                    <option value="{{$role->id}}">{{$role->name}}</option>                                    
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-info pull-right">Create</button>
                </div>
                <!-- /.box-footer -->
            </form>
            @include('partials.formerrors')
    
        </div>
    </div>

</div>


@endsection


