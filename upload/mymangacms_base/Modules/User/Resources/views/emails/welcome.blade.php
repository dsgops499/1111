<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>{{ trans('user::messages.front.auth.welcome_title') }}</title>
        <style type="text/css">
            body {font-family:'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;box-sizing:border-box;font-size:14px;width:100%!important;height:100%;line-height:1.6;background:#f6f6f6;margin:0;padding:0}
            .main {max-width:600px;border-radius:3px;background: #fff;margin: 20px auto;padding:20px;border: 1px solid #e9e9e9}
            .content-block {margin:0;padding:0 0 20px}
            .btn-primary {color:#FFF;text-decoration:none;line-height:2; font-weight:bold;text-align:center;cursor:pointer;display:inline-block;border-radius:5px;text-transform:capitalize;background:#348eda;margin:0;padding:0;border-color:#348eda;border-style:solid;border-width:10px 20px}
        </style>
    </head>
    <body>
        <div class="main">
            <div style="font-size: 20px; font-weight:bold; padding: 0 0 20px;">
                {{ trans('user::messages.front.auth.welcome_title') }}
            </div>
            <div class="content-block">
                {{ trans('user::messages.front.auth.confirm_email') }}
            </div>
            <div class="content-block">
                <?php $authPrefix = env('AUTH_PREFIX', 'auth'); ?>
                <a href='{{ URL::to("$authPrefix/activate/{$id}/{$activationCode}") }}' class="btn-primary">
                    {{ trans('user::messages.front.auth.confirm_email_btn') }}
                </a>
            </div>
            <div class="content-block">
                &mdash; {{ trans('user::messages.front.auth.regards') }}
            </div>
        </div>
    </body>
</html>
