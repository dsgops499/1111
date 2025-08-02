@extends('base::layouts.default')

@section('head')
<link rel="stylesheet" href="{{asset('css/bootstrap-select.min.css')}}">
<script src="{{asset('js/vendor/bootstrap-select.min.js')}}"></script>
@stop

@section('breadcrumbs')
{!!Breadcrumbs::render()!!}
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-plus-square-o"></i> {{ Lang::get('user::messages.admin.settings.subscription_options') }}
                </h3>
            </div>
            {{ Form::open(array('route' => 'admin.settings.subscription.post')) }}
            <div class="box-body">
                <fieldset>
                    <legend style="padding-left: 20px">Subscription</legend>
                    <div class="row">
                        <div class="col-md-4"><label>{{ Lang::get('messages.admin.users.options.allo-subscribe') }}</label></div>
                        <div class="col-md-8">
                            <label class="radio-inline">
                                <input type="radio" name="subscribe" value="true" <?php if ($subscription->subscribe === 'true'): ?>
                                       checked="checked"<?php endif ?>/>{{ Lang::get('messages.admin.users.options.yes') }}</label>
                            <label class="radio-inline">
                                <input type="radio" name="subscribe" value="false" <?php if ($subscription->subscribe === 'false'): ?>
                                       checked="checked"<?php endif ?>/>{{ Lang::get('messages.admin.users.options.no') }}</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><label>{{ Lang::get('messages.admin.users.options.admin-activate-it') }}</label></div>
                        <div class="col-md-8">
                            <label class="radio-inline">
                                <input type="radio" name="admin_confirm" value="true" <?php if ($subscription->admin_confirm === 'true'): ?>
                                       checked="checked"<?php endif ?>/>{{ Lang::get('messages.admin.users.options.yes') }}</label>
                            <label class="radio-inline">
                                <input type="radio" name="admin_confirm" value="false" <?php if ($subscription->admin_confirm === 'false'): ?>
                                       checked="checked"<?php endif ?>/>{{ Lang::get('messages.admin.users.options.no') }}</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><label>{{ Lang::get('messages.admin.users.options.send-confim-email') }}</label></div>
                        <div class="col-md-8">
                            <label class="radio-inline">
                                <input type="radio" name="email_confirm" value="true" <?php if ($subscription->email_confirm === 'true'): ?>
                                       checked="checked"<?php endif ?>/>{{ Lang::get('messages.admin.users.options.yes') }}</label>
                            <label class="radio-inline">
                                <input type="radio" name="email_confirm" value="false" <?php if ($subscription->email_confirm === 'false'): ?>
                                       checked="checked"<?php endif ?>/>{{ Lang::get('messages.admin.users.options.no') }}</label>
                        </div>
                    </div>
                    <div class="help-block">{{ Lang::get('messages.admin.users.subscribe.hint') }}</div>
                    <br/>
                    <div class="form-group">
                        {{Form::label('default_role', Lang::get('messages.admin.users.options.default-role'))}}
                        <select name="default_role" class="selectpicker" data-width="100%">
                            @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </fieldset>
                <fieldset>
                    <legend style="padding-left: 20px">Email</legend>        
                    <div id="mailing" class="form-group">
                        <div class="form-group">
                            <label>{{ Lang::get('messages.admin.users.options.address') }}</label>
                            <input type="text" name="address" value="{{ $subscription->address }}" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label>{{ Lang::get('messages.admin.users.options.name') }}</label>
                            <input type="text" name="name" value="{{ $subscription->name }}" class="form-control"/>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="mailing" value="sendmail" <?php if ($subscription->mailing === 'sendmail'): ?>
                                       checked="checked"<?php endif ?>/>{{ Lang::get('messages.admin.users.options.use-php-mail') }}</label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="mailing" value="smtp" <?php if ($subscription->mailing === 'smtp'): ?>
                                       checked="checked"<?php endif ?>/>{{ Lang::get('messages.admin.users.options.config-smtp') }}</label>
                        </div>

                        <div id="smtp-conf" style="display: none;">
                            <div class="form-group">
                                <label>{{ Lang::get('messages.admin.users.options.host') }}</label>
                                <input type="text" name="host" value="{{ $subscription->host }}" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label>{{ Lang::get('messages.admin.users.options.port') }}</label>
                                <input type="text" name="port" value="{{ $subscription->port }}" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label>{{ Lang::get('user::messages.admin.settings.encryption_protocol') }}</label>
                                <input type="text" name="encryption" placeholder="ssl or tls" value="{{ isset($subscription->encryption)?$subscription->encryption:'tls' }}" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label>{{ Lang::get('messages.admin.users.options.username') }}</label>
                                <input type="text" name="username" value="{{ $subscription->username }}" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label>{{ Lang::get('messages.admin.users.options.password') }}</label>
                                <input type="password" name="password" value="{{ $subscription->password }}" class="form-control"/>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="box-footer">
                <div class="pull-right">
                    {{ Form::submit(Lang::get('messages.admin.settings.update'), array('class' => 'btn btn-primary center-block save', 'style' => 'width: 100%')) }}
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.selectpicker').val("{{$subscription->default_role}}");
        smtpChecked();
    });

    $('#mailing input[type="radio"]').change(function () {
        smtpChecked();
    });

    function smtpChecked() {
        if ($('#mailing input[value="smtp"]').is(':checked')) {
            $('#smtp-conf').show();
        } else {
            $('#smtp-conf').hide();
        }
    }
</script>
@stop
