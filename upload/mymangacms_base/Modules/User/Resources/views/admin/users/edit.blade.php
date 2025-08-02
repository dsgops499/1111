@extends('base::layouts.default')

@section('head')
<link rel="stylesheet" href="{{asset('css/bootstrap-select.min.css')}}">
<script src="{{asset('js/vendor/bootstrap-select.min.js')}}"></script>
@endsection

@section('breadcrumbs')
{!!Breadcrumbs::render()!!}
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-plus-square-o"></i> {{ Lang::get('messages.admin.users.edit-user') }}
                </h3>
                <div class="box-tools">
                    {{ link_to_route('admin.user.index', Lang::get('messages.admin.settings.back'), [], array('class' => 'btn btn-default btn-xs', 'role' => 'button')) }}
                </div>
            </div>
            {{ Form::open(array('route' => array('admin.user.update', $user->id), 'method' => 'PUT')) }}
            <div class="box-body">
                <div class="row">
                    <fieldset>
                        <legend style="padding-left: 20px">Details</legend>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('name', Lang::get('messages.admin.settings.profile.name')) }}
                                {{ Form::text('name', $user->name, array('class' => 'form-control')) }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('username', Lang::get('messages.admin.settings.profile.username')) }}
                                {{ Form::text('username', $user->username, array('class' => 'form-control')) }}
                                {!! $errors->first('username', '<label class="error" for="username">:message</label>') !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {{ Form::label('email', Lang::get('messages.admin.settings.profile.email')) }}
                                {{ Form::email('email', $user->email, ['class' => 'form-control']) }}
                                {!! $errors->first('email', '<label class="error" for="email">:message</label>') !!}
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend style="padding-left: 20px">Authentication</legend>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('password', 'Change Password') }}
                                {{ Form::password('password', array('class' => 'form-control')) }}
                                {!! $errors->first('password', '<label class="error" for="password">:message</label>') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('password_confirmation', 'Password Confirmation') }}
                                {{ Form::password('password_confirmation', array('class' => 'form-control')) }}
                                {!! $errors->first('password_confirmation', '<label class="error" for="password_confirmation">:message</label>') !!}
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend style="padding-left: 20px">Authorization</legend>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{Form::label('roles', 'Roles')}}
                                <select name="roles[]" class="selectpicker" multiple="multiple" data-selected-text-format="count>7" data-width="100%">
                                    @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" @if($user->hasRoleId($role->id)) selected="selected" @endif>
                                            {{ $role->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="activated">Activated</label>
                                {{ Form::select('activated', [
                                '1' => 'Yes',
                                '0' => 'No',
                                ], $user->isActivated() ? '1' : '0', ['class' => 'form-control']) }}
                            </div>
                        </div>
                    </fieldset>
                </div>
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
@endsection