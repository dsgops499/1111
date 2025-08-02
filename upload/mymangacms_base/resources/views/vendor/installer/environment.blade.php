@extends('vendor.installer.layouts.master')

@section('template_title')
    {{ trans('installer_messages.environment.wizard.templateTitle') }}
@endsection

@section('title')
    <i class="fa fa-magic fa-fw" aria-hidden="true"></i>
    {{ trans('installer_messages.environment.wizard.title') }}
@endsection

@section('container')
    <div class="tabs tabs-full">

        <input id="tab1" type="radio" name="tabs" class="tab-input" checked />
        <label for="tab1" class="tab-label">
            <i class="fa fa-database fa-2x fa-fw" aria-hidden="true"></i>
            <br />
            {{ trans('installer_messages.environment.wizard.tabs.database') }}
        </label>

        <input id="tab2" type="radio" name="tabs" class="tab-input" />
        <label for="tab2" class="tab-label">
            <i class="fa fa-cogs fa-2x fa-fw" aria-hidden="true"></i>
            <br />
            {{ trans('installer_messages.environment.wizard.tabs.application') }}
        </label>

        <form method="post" action="{{ route('LaravelInstaller::environmentSaveWizard') }}" class="tabs-wrap">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="environment" id="environment" value="production">
            <input type="hidden" name="app_debug" id="app_debug" value="false">
            <input type="hidden" name="app_log_level" id="app_log_level" value="debug">
            <input type="hidden" name="broadcast_driver" id="broadcast_driver" value="log">
            <input type="hidden" name="cache_driver" id="cache_driver" value="file">
            <input type="hidden" name="session_driver" id="session_driver" value="file">
            <input type="hidden" name="queue_driver" id="queue_driver" value="sync">
            <input type="hidden" name="mail_driver" id="mail_driver" value="sendmail">
            <input type="hidden" name="mail_host" id="mail_host" value="smtp.host.com">
            <input type="hidden" name="mail_port" id="mail_port" value="587">
            <input type="hidden" name="mail_username" id="mail_username" value="username">
            <input type="hidden" name="mail_password" id="mail_password" value="password">
            <input type="hidden" name="mail_encryption" id="mail_encryption" value="tls">
            <input type="hidden" name="mail_from_address" id="mail_from_address" value="admin@mydomain.com">
            <input type="hidden" name="mail_from_name" id="mail_from_name" value="admin">
            <input type="hidden" name="file_driver" id="file_driver" value="uploads">
            <input type="hidden" name="allow_subscribe" id="allow_subscribe" value="0">
            <input type="hidden" name="confirm_admin" id="confirm_admin" value="0">
            <input type="hidden" name="confirm_mail" id="confirm_mail" value="0">
            <input type="hidden" name="default_role" id="default_role" value="3">
            <input type="hidden" name="nocaptcha_secret" id="nocaptcha_secret" value="secret">
            <input type="hidden" name="nocaptcha_sitekey" id="nocaptcha_sitekey" value="key">

            <div class="tab" id="tab1content">
                <div class="form-group {{ $errors->has('database_connection') ? ' has-error ' : '' }}">
                    <label for="database_connection">
                        {{ trans('installer_messages.environment.wizard.form.db_connection_label') }}
                    </label>
                    <select name="database_connection" id="database_connection">
                        <option value="mysql" selected>{{ trans('installer_messages.environment.wizard.form.db_connection_label_mysql') }}</option>
                    </select>
                    @if ($errors->has('database_connection'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('database_connection') }}
                        </span>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('database_hostname') ? ' has-error ' : '' }}">
                    <label for="database_hostname">
                        {{ trans('installer_messages.environment.wizard.form.db_host_label') }}
                    </label>
                    <input type="text" name="database_hostname" id="database_hostname" value="localhost" placeholder="{{ trans('installer_messages.environment.wizard.form.db_host_placeholder') }}" />
                    @if ($errors->has('database_hostname'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('database_hostname') }}
                        </span>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('database_port') ? ' has-error ' : '' }}">
                    <label for="database_port">
                        {{ trans('installer_messages.environment.wizard.form.db_port_label') }}
                    </label>
                    <input type="number" name="database_port" id="database_port" value="3306" placeholder="{{ trans('installer_messages.environment.wizard.form.db_port_placeholder') }}" />
                    @if ($errors->has('database_port'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('database_port') }}
                        </span>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('database_name') ? ' has-error ' : '' }}">
                    <label for="database_name">
                        {{ trans('installer_messages.environment.wizard.form.db_name_label') }}
                    </label>
                    <input type="text" name="database_name" id="database_name" value="" placeholder="{{ trans('installer_messages.environment.wizard.form.db_name_placeholder') }}" />
                    @if ($errors->has('database_name'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('database_name') }}
                        </span>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('database_username') ? ' has-error ' : '' }}">
                    <label for="database_username">
                        {{ trans('installer_messages.environment.wizard.form.db_username_label') }}
                    </label>
                    <input type="text" name="database_username" id="database_username" value="" placeholder="{{ trans('installer_messages.environment.wizard.form.db_username_placeholder') }}" />
                    @if ($errors->has('database_username'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('database_username') }}
                        </span>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('database_password') ? ' has-error ' : '' }}">
                    <label for="database_password">
                        {{ trans('installer_messages.environment.wizard.form.db_password_label') }}
                    </label>
                    <input type="password" name="database_password" id="database_password" value="" placeholder="{{ trans('installer_messages.environment.wizard.form.db_password_placeholder') }}" />
                    @if ($errors->has('database_password'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('database_password') }}
                        </span>
                    @endif
                </div>

                <div class="buttons">
                    <button class="button" onclick="showApplicationSettings();return false">
                        {{ trans('installer_messages.environment.wizard.form.buttons.setup_application') }}
                        <i class="fa fa-angle-right fa-fw" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
            <div class="tab" id="tab2content">
                <div class="form-group {{ $errors->has('app_url') ? ' has-error ' : '' }}">
                    <label for="app_url">
                        {{ trans('installer_messages.environment.wizard.form.app_url_label') }}
                    </label>
                    <input type="url" name="app_url" id="app_url" value="http://localhost" placeholder="{{ trans('installer_messages.environment.wizard.form.app_url_placeholder') }}" />
                    @if ($errors->has('app_url'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('app_url') }}
                        </span>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('app_name') ? ' has-error ' : '' }}">
                    <label for="app_name">
                        {{ trans('installer_messages.environment.wizard.form.app_name_label') }}
                    </label>
                    <input type="text" name="app_name" id="app_name" value="My Manga Reader CMS" placeholder="{{ trans('installer_messages.environment.wizard.form.app_name_placeholder') }}" />
                    @if ($errors->has('app_name'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('app_name') }}
                        </span>
                    @endif
                </div>
                
                <div class="form-group {{ $errors->has('admin_slug') ? ' has-error ' : '' }}">
                    <label for="admin_slug">Administration Slug</label>
                    <input type="text" name="admin_slug" id="admin_slug" value="admin" placeholder="prefix in dashboard urls" />
                    @if ($errors->has('admin_slug'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('admin_slug') }}
                        </span>
                    @endif
                </div>
                
                <div class="form-group {{ $errors->has('auth_slug') ? ' has-error ' : '' }}">
                    <label for="auth_slug">Authentication Slug</label>
                    <input type="text" name="auth_slug" id="auth_slug" value="auth" placeholder="prefix in auth urls" />
                    @if ($errors->has('auth_slug'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('auth_slug') }}
                        </span>
                    @endif
                </div>
                
                <div class="form-group {{ $errors->has('manga_slug') ? ' has-error ' : '' }}">
                    <label for="manga_slug">Manga Slug</label>
                    <input type="text" name="manga_slug" id="manga_slug" value="manga" placeholder="prefix in manga urls" />
                    @if ($errors->has('manga_slug'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('manga_slug') }}
                        </span>
                    @endif
                </div>
                
                <div class="form-group {{ $errors->has('post_slug') ? ' has-error ' : '' }}">
                    <label for="post_slug">Post Slug</label>
                    <input type="text" name="post_slug" id="post_slug" value="news" placeholder="prefix of posts" />
                    @if ($errors->has('post_slug'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('post_slug') }}
                        </span>
                    @endif
                </div>
                
                <div class="buttons">
                    <button class="button" type="submit">
                        {{ trans('installer_messages.environment.wizard.form.buttons.install') }}
                        <i class="fa fa-angle-right fa-fw" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </form>

    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        function checkEnvironment(val) {
            var element=document.getElementById('environment_text_input');
            if(val=='other') {
                element.style.display='block';
            } else {
                element.style.display='none';
            }
        }
        function showApplicationSettings() {
            document.getElementById('tab2').checked = true;
        }
    </script>
@endsection