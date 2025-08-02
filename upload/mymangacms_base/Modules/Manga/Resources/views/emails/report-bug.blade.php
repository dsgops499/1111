<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    </head>
    <body>
        <style type="text/css">
            body {font-family:'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;box-sizing:border-box;font-size:14px;width:100%!important;height:100%;line-height:1.6;background:#f6f6f6;margin:0;padding:0}
            .main {max-width:600px;border-radius:3px;background: #fff;margin: 20px auto;padding:20px;border: 1px solid #e9e9e9}
            .content-block {margin:0;padding:0 0 20px}
            .btn-primary {color:#FFF;text-decoration:none;line-height:2; font-weight:bold;text-align:center;cursor:pointer;display:inline-block;border-radius:5px;text-transform:capitalize;background:#348eda;margin:0;padding:0;border-color:#348eda;border-style:solid;border-width:10px 20px}
        </style>
        <div class="main">
            <p>
                {{ Lang::get('messages.front.reader.image-broken', array('image' => $data['broken-image'], 'email' => $data['email'])) }}
            </p>

            <p>
                <b>Message Body:</b>
                <br>{{$data['subject'] }}
            </p>

            <div class="content-block">
                &mdash;<br/>
                This e-mail was sent from a contact form on <a href="{{ env('APP_URL')}}">{{ env('APP_NAME')}}</a>
            </div>
        </div>
    </body>
</html>


