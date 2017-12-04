@extends('layouts.intro')
@section('content')
<div class="header">
  <div class="title">
    @lang('sections.change_password')
  </div>
</div>
<div class="container">
  <div class="row">
    <div class="col-sm-12 col-xs-12 col-md-12 col-lg-12 logo">
      <img src="../images/intro_small.png" />
    </div>
  </div>
  @if(!isset($error_token))
  <div class="row">
    <div class="col-sm-12 col-xs-12 col-md-12 col-lg-12 center instructions">
      @lang('sections.change_password_intructions')
    </div>
  </div>
  @endif
  @if(isset($success) && $success)
  <div class="row">
    <div class="col-sm-12 col-xs-12 col-md-12 col-lg-12 center">
      <div class = "alert alert-success">@lang('validation.messages.success_reset_password')</div>
    </div>
  </div>
  @endif
  @if(isset($error) && $error)
  <div class="row">
    <div class="col-sm-12 col-xs-12 col-md-12 col-lg-12 center">
      <div class = "alert alert-danger">@lang('validation.messages.must_complete_fields')</div>
    </div>
  </div>
  @endif

  @if(isset($error_message))
  <div class="row">
    <div class="col-sm-12 col-xs-12 col-md-12 col-lg-12 center">
      <div class = "alert alert-danger">{{$error_message}}</div>
    </div>
  </div>
  @endif

  @if(isset($error_match) && $error_match)
  <div class="row">
    <div class="col-sm-12 col-xs-12 col-md-12 col-lg-12 center">
      <div class = "alert alert-danger">@lang('validation.messages.confirm_password_match_error')</div>
    </div>
  </div>
  @endif
  @if(!isset($error_token))
  <div class="row">
    <div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
      <form role = "form" method="post" action="{{url('/remember-link/'.$token)}}">
         <div class = "form-group">
            <label for = "email">@lang('sections.user_email')</label>
            <input type = "text" class = "form-control input-intro @if(isset($error_email_class) && trim($error_email_class)!=='') {{$error_email_class}} @endif" id = "email" name = "email" value="{{ $email }}">
            @if(isset($error_form) && trim($error_form->first('email'))!=='')
              <div class="invalid-feedback">
                {{trim($error_form->first('email'))}}
              </div>
            @endif
         </div>

         <div class = "form-group">
            <label for = "password">@lang('sections.password')</label>
            <input type = "text" class = "form-control  input-intro @if(isset($error_password_class) && trim($error_password_class)!=='') {{$error_password_class}} @endif" id = "password" name = "password" value="{{ $password }}">
            @if(isset($error_form) && trim($error_form->first('password'))!=='')
              <div class="invalid-feedback">
                {{trim($error_form->first('password'))}}
              </div>
            @endif
         </div>
         <div class = "form-group">
            <label for = "confirm_password">@lang('sections.confirm_password')</label>
            <input type = "text" class = "form-control  input-intro @if(isset($error_confirm_password_class) && trim($error_confirm_password_class)!=='') {{$error_confirm_password_class}} @endif" id = "confirm_password" name = "confirm_password" value="{{ $confirm_password }}">
            @if(isset($error_form) && trim($error_form->first('confirm_password'))!=='')
              <div class="invalid-feedback">
                {{trim($error_form->first('confirm_password'))}}
              </div>
            @endif
         </div>
         <div class="row">
           <div class="col-sm-12 col-xs-12 col-md-12 col-lg-12 center">
              <button type = "submit" class = "btn btn-intro btn-lg">@lang('sections.change')</button>
              <input type = "hidden" name="hidden" value="form">
            </div>
          </div>
      </form>
    </div>
  </div>
  @endif
</div>

@endsection
