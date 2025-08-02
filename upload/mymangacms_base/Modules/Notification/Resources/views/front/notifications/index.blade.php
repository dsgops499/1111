@extends('front.layouts.'.$theme)

@section('header')
<link rel="stylesheet" href="{{asset('vendor/datatables/jquery.dataTables.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/datatables/buttons.dataTables.min.css')}}">

<script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendor/datatables/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('vendor/datatables/buttons.server-side.js')}}"></script>
@endsection

@include('front.themes.'.$theme.'.blocs.menu')

@section('allpage')
@if (Session::has('success'))
<div class="alert alert-success fade in alert-dismissable" style="border-radius: 0">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    {{ Session::get('success') }}
</div>
@endif

<h2 class="widget-title">{{ trans('notification::messages.notifications') }}</h2>
<hr/>

<div class="row">
    <div class="col-md-12">
        <div class="pull-right" style="margin-bottom: 5px">
            <a href="#" class="btn btn-success btn-xs" data-toggle="modal" data-target="#notification-settings">{{ trans('notification::messages.notification settings') }}</a>
            <a href="{{ route('front.notification.markAllAsRead') }}" class="btn btn-primary btn-xs">{{ trans('notification::messages.mark all as read') }}</a>
            {{ Form::open(['route' => ['front.notification.destroyAll'], 'method' => 'delete', 'class' => 'pull-right', 'style' => 'margin-left:5px']) }}
            <input class="btn btn-xs btn-danger" type="submit" onclick="if (!confirm('{{Lang::get('notification::messages.delete all confirmation')}}')){return false; }" value="{{ trans('notification::messages.delete all') }}"/>
            {{ Form::close() }}
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            {!! $dataTable->table() !!}
        </div>
        <!-- /.box-body -->
    </div>
</div>

<div class="modal fade modal-danger" id="notification-settings" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="z-index: 99999;">
        <div class="modal-content">
            {{ Form::open(['route' => ['front.notification.saveSettings'], 'method' => 'post']) }}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('notification::messages.notification settings') }}</h4>
            </div>
            <div class="modal-body">
                <b>{{ trans('notification::messages.notification.modal.tips') }}</b>
                <br/><br/>
                <b>{{ trans('notification::messages.notification.modal.msg') }}</b>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="notif_post" value="1"
                               <?php if (isset($notifSettings->post) && $notifSettings->post == '1') { ?> checked="checked" <?php } ?> >
                        Posts
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="notif_manga" value="1" 
                               <?php if (isset($notifSettings->manga) && $notifSettings->manga == '1') { ?> checked="checked" <?php } ?> >
                        Manga
                    </label>
                </div>
                <div class="form-group">
                    <label style="vertical-align: text-top;">Chapters</label>
                    <label class="radio-inline">
                        <input type="radio" name="notif_chapter" value="0" <?php if (isset($notifSettings->chapter) && $notifSettings->chapter == '0'): ?>
                                   checked="checked"<?php endif ?>/>Disabled
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="notif_chapter" value="1" <?php if (isset($notifSettings->chapter) && $notifSettings->chapter == '1'): ?>
                                   checked="checked"<?php endif ?>/>All Chapters
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="notif_chapter" value="2" <?php if (isset($notifSettings->chapter) && $notifSettings->chapter == '2'): ?>
                                   checked="checked"<?php endif ?>/>Only chapters of bookmarked Manga
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline btn-flat" data-dismiss="modal">{{ trans('messages.admin.settings.cancel') }}</button>
                <button type="submit" class="btn btn-success btn-flat"><i class="fa fa-floppy-o"></i> {{ trans('messages.admin.settings.save') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
{!! $dataTable->scripts() !!}
@endsection
