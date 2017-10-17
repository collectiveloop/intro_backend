@extends('layouts.login')

@section('content')
<div id="loginbox">
  @if (trim($error) !== '')
  <div class="alert alert-danger">
    {{ $error }}
</div>
@endif
  <div class="control-group normal_text"> <h3><img class="logo" src="{{url('images/logo.png')}}" alt="Logo" /></h3></div>
    <form id="loginform" class="form-vertical" method="post" action="{{url('administration/login')}}">
        <div class="control-group">
            <div class="controls">
                <div class="main_input_box">
                    <span class="add-on bg_lg"><i class="icon-user"> </i></span><input name="email" type="email" placeholder="@lang('form.username')" />
                </div>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <div class="main_input_box">
                    <span class="add-on bg_ly"><i class="icon-lock"></i></span><input name="password" type="password" placeholder="@lang('form.password')" />
                </div>
            </div>
        </div>
        <div class="form-actions">
            <span class="pull-left"><a href="#" class="flip-link btn btn-info" id="to-recover">@lang('form.lost_password')</a></span>
            <input type="hidden" name="login" value="login" />
            <button type="submit" class="btn btn-success" value ="" />@lang('form.login')</button>
        </div>
    </form>
    <form id="recoverform" action="{{url('administration/login')}}" method="post" class="form-vertical">
<p class="normal_text">@lang('form.remember_text').</p>

            <div class="controls">
                <div class="main_input_box">
                    <span class="add-on bg_lo"><i class="icon-envelope"></i></span><input type="text" placeholder="@lang('form.email')" />
                </div>
            </div>

        <div class="form-actions">
            <span class="pull-left"><a href="#" class="flip-link btn btn-success" id="to-login">&laquo; @lang('form.back_login')</a></span>

            <input type="hidden" name="recover" value="recover" />
            <button type="submit" class="btn btn-success" value ="" />@lang('form.recover_password')</button>
        </div>
    </form>
</div>
<script src="{{url('js/matrix.login.js')}}" ></script>

@endsection
