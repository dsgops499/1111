@extends('base::layouts.default')

@section('breadcrumbs')
{!!Breadcrumbs::render('admin.settings.general')!!}
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/bootstrap-select.min.css')}}">
<script src="{{asset('js/vendor/bootstrap-select.min.js')}}"></script>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-sliders fa-fw"></i> {{ Lang::get('messages.admin.settings.general.header') }}
                </h3>
            </div>

            {{ Form::open(array('route' => 'admin.settings.general.save', 'role' => 'form')) }}
            <div class="box-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist" style="margin: 10px -10px 15px;padding:0 10px">
                    <li role="presentation" class="active">
                        <a href="#info" aria-controls="info" role="tab" data-toggle="tab">{{Lang::get('messages.admin.settings.info')}}</a>
                    </li>
                    <li role="presentation">
                        <a href="#pagination" aria-controls="pagination" role="tab" data-toggle="tab">{{Lang::get('messages.admin.settings.pagination')}}</a>
                    </li>
                    <?php if (is_module_enabled('MySpace')): ?>
                    <li role="presentation">
                        <a href="#comment" aria-controls="comment" role="tab" data-toggle="tab">{{Lang::get('messages.admin.settings.comment')}}</a>
                    </li>
                    <?php endif; ?>
                    <li role="presentation">
                        <a href="#reader" aria-controls="reader" role="tab" data-toggle="tab">{{Lang::get('messages.admin.settings.reader')}}</a>
                    </li>
                    <li role="presentation">
                        <a href="#storage" aria-controls="storage" role="tab" data-toggle="tab">{{Lang::get('messages.admin.settings.storage')}}</a>
                    </li>
                    <li role="presentation">
                        <a href="#captcha" aria-controls="captcha" role="tab" data-toggle="tab">{{Lang::get('messages.admin.settings.captcha')}}</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="info">
                        <div class="form-group">
                            {{Form::label('site.lang', Lang::get('messages.admin.settings.general.select-lang'))}}
                            {{Form::select('site.lang', $languages, $options['site.lang'], array('class' => 'selectpicker', 'data-width' => 'auto', 'data-size' => 'false'))}}
                        </div>
                        <div class="form-group">
                            <label style="vertical-align: text-top;">{{ Lang::get('messages.admin.settings.general.site-orientation') }}</label>
                            <label class="radio-inline">
                                <input type="radio" name="site.orientation" value="ltr" <?php if ($options['site.orientation'] === 'ltr'): ?>
                                       checked="checked"<?php endif ?>/>{{ Lang::get('messages.admin.settings.general.orientation-ltr') }}
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="site.orientation" value="rtl" <?php if ($options['site.orientation'] === 'rtl'): ?>
                                       checked="checked"<?php endif ?>/>{{ Lang::get('messages.admin.settings.general.orientation-rtl') }}
                            </label>
                        </div>
                        <div class="form-group">
                            {{ Form::label('site.name', Lang::get('messages.admin.settings.general.site-name')) }}
                            {{ Form::text('site.name', $options['site.name'], ['class' => 'form-control']) }}
                            {!! $errors->first('site.name', '<label class="error" for="site.name">:message</label>') !!}
                        </div>
                        <div class="form-group">
                            {{ Form::label('site.slogan', Lang::get('messages.admin.settings.general.slogan')) }}
                            {{ Form::text('site.slogan', $options['site.slogan'], ['class' => 'form-control']) }}
                            {!! $errors->first('site.slogan', '<label class="error" for="site.slogan">:message</label>') !!}
                        </div>
                        <div class="form-group">
                            {{ Form::label('site.description', Lang::get('messages.admin.settings.general.description')) }}
                            {{ Form::textarea('site.description', $options['site.description'], ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="pagination">
                        <div class="form-group">
                            {{Form::label('pagination_homepage', Lang::get('messages.admin.settings.general.pagination-homepage'))}}
                            {{Form::number('site.pagination[homepage]', $pagination->homepage, ['min' => 5])}}
                        </div>
                        <div class="form-group">
                            {{Form::label('pagination_mangalist', Lang::get('messages.admin.settings.general.pagination-mangalist'))}}
                            {{Form::number('site.pagination[mangalist]', $pagination->mangalist, ['min' => 10])}}
                        </div>
                        <div class="form-group">
                            {{Form::label('pagination_latest_release', Lang::get('messages.admin.settings.general.pagination-latest-release'))}}
                            {{Form::number('site.pagination[latest_release]', $pagination->latest_release, ['min' => 10])}}
                        </div>

                        <hr/>
                        <div class="form-group">
                            {{Form::label('pagination_news_homepage', Lang::get('messages.admin.settings.general.pagination-news-homepage'))}}
                            {{Form::number('site.pagination[news_homepage]', isset($pagination->news_homepage)?$pagination->news_homepage:5, ['min' => 5])}}
                        </div>
                        <div class="form-group">
                            {{Form::label('pagination_newslist', Lang::get('messages.admin.settings.general.pagination-newslist'))}}
                            {{Form::number('site.pagination[newslist]', isset($pagination->newslist)?$pagination->newslist:10, ['min' => 10])}}
                        </div>
                    </div>
                    <?php if (is_module_enabled('MySpace')): ?>
                    <div role="tabpanel" class="tab-pane" id="comment">
                        <b>{{Lang::get('messages.admin.settings.comment.system')}}</b>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="site.comment[builtin]" value="1" 
                                       <?php if (isset($comment->builtin) && $comment->builtin == '1') { ?> checked="checked" <?php } ?> >
                                Built-in comments
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="site.comment[fb]" value="1" 
                                       <?php if (isset($comment->fb) && $comment->fb == '1') { ?> checked="checked" <?php } ?> >
                                Facebook
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input id="disqusOption" type="checkbox" name="site.comment[disqus]" value="1"
                                       <?php if (isset($comment->disqus) && $comment->disqus == '1') { ?> checked="checked" <?php } ?> >
                                Disqus
                            </label>
                            <input id="disqusUrl" type="text" name="site.comment[disqusUrl]" value="<?php echo isset($comment->disqusUrl) ? $comment->disqusUrl : '' ?>" 
                                   placeholder="your Disqus URL, ex: myblog.disqus.com" size="50" />
                        </div>

                        <hr/>

                        <b>{{Lang::get('messages.admin.settings.comment.show-on-page')}}</b>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="site.comment[page][news]" value="1" 
                                       <?php if (isset($comment->page->news) && $comment->page->news == '1') { ?> checked="checked" <?php } ?> >
                                {{Lang::get('messages.admin.settings.comment.news-page')}}
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="site.comment[page][mangapage]" value="1" 
                                       <?php if (isset($comment->page->mangapage) && $comment->page->mangapage == '1') { ?> checked="checked" <?php } ?> >
                                {{Lang::get('messages.admin.settings.comment.manga-page')}}
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="site.comment[page][reader]" value="1" 
                                       <?php if (isset($comment->page->reader) && $comment->page->reader == '1') { ?> checked="checked" <?php } ?> >
                                {{Lang::get('messages.admin.settings.comment.reader-page')}}
                            </label>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div role="tabpanel" class="tab-pane" id="reader">
                        <div class="form-group">
                            <label style="vertical-align: text-top;">{{ Lang::get('messages.admin.settings.general.reader-type') }}</label>
                            <label class="radio-inline">
                                <input type="radio" name="reader.type" value="all" <?php if ($options['reader.type'] === 'all'): ?>
                                           checked="checked"<?php endif ?>/>{{ Lang::get('messages.admin.settings.general.reader-type-all') }}
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="reader.type" value="ppp" <?php if ($options['reader.type'] === 'ppp'): ?>
                                           checked="checked"<?php endif ?>/>{{ Lang::get('messages.admin.settings.general.reader-type-ppp') }}
                            </label>
                        </div>
                        <div class="form-group">
                            <label style="vertical-align: text-top;">{{ Lang::get('messages.admin.settings.general.reader-mode') }}</label>
                            <div style="margin-left: 20px;">
                                <label class="radio">
                                    <input type="radio" name="reader.mode" value="noreload" <?php if ($options['reader.mode'] === 'noreload'): ?>
                                               checked="checked"<?php endif ?>/>{{ Lang::get('messages.admin.settings.general.reader-mode-noreload') }}
                                </label>
                                <label class="radio">
                                    <input type="radio" name="reader.mode" value="reload" <?php if ($options['reader.mode'] === 'reload'): ?>
                                               checked="checked"<?php endif ?>/>{{ Lang::get('messages.admin.settings.general.reader-mode-reload') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="storage">
                        <div class="form-group">
                            <label style="vertical-align: text-top;">{{ Lang::get('messages.admin.settings.general.storage-type') }}</label>
                            <div style="margin-left: 20px;">
                                <label class="radio">
                                    <input type="radio" name="storage.type" value="server" <?php if ($options['storage.type'] === 'server'): ?>
                                               checked="checked"<?php endif ?>/>{{ Lang::get('messages.admin.settings.general.storage-type-server') }}
                                </label>
                                <?php if (is_module_enabled('GDrive')): ?>
                                    <label class="radio">
                                        <input type="radio" name="storage.type" value="gdrive" <?php if ($options['storage.type'] === 'gdrive'): ?>
                                                   checked="checked"<?php endif ?>/>{{ Lang::get('messages.admin.settings.general.storage-type-gdrive') }}
                                        {{ link_to_route('admin.settings.gdrive', '(Configuration)') }}
                                    </label>
                                <?php endif; ?>
                                <label class="radio">
                                    <input type="radio" name="storage.type" value="mirror" <?php if ($options['storage.type'] === 'mirror'): ?>
                                               checked="checked"<?php endif ?>/>{{ Lang::get('messages.admin.settings.general.storage-type-mirror') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="captcha">
                        <div class="form-group">
                            <label>{!! Lang::get('messages.admin.settings.captcha.info') !!}</label>
                        </div>
                        <div class="form-group">
                            {{ Form::label('site.captcha[secret_key]', Lang::get('messages.admin.settings.captcha.secret-key')) }}
                            {{ Form::text('site.captcha[secret_key]', isset($captcha->secret_key)?$captcha->secret_key:'', ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('site.captcha[site_key]', Lang::get('messages.admin.settings.captcha.site-key')) }}
                            {{ Form::text('site.captcha[site_key]', isset($captcha->site_key)?$captcha->site_key:'', ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group">
                            <label style="vertical-align: text-top;">{{ Lang::get('messages.admin.settings.captcha.activate-captcha') }}</label>
                            <div style="margin-left: 20px;">
                                <label class="checkbox">
                                    <input type="checkbox" name="site.captcha[form_login]" value="1" <?php if (isset($captcha->form_login) && $captcha->form_login === '1'): ?>
                                               checked="checked"<?php endif ?>/>{{ Lang::get('messages.admin.settings.captcha.form_login') }}
                                </label>
                                <label class="checkbox">
                                    <input type="checkbox" name="site.captcha[form_register]" value="1" <?php if (isset($captcha->form_register) && $captcha->form_register === '1'): ?>
                                               checked="checked"<?php endif ?>/>{{ Lang::get('messages.admin.settings.captcha.form_register') }}
                                </label>
                                <label class="checkbox">
                                    <input type="checkbox" name="site.captcha[form_reset]" value="1" <?php if (isset($captcha->form_reset) && $captcha->form_reset === '1'): ?>
                                               checked="checked"<?php endif ?>/>{{ Lang::get('messages.admin.settings.captcha.form_reset') }}
                                </label>
                                <label class="checkbox">
                                    <input type="checkbox" name="site.captcha[form_report]" value="1" <?php if (isset($captcha->form_report) && $captcha->form_report === '1'): ?>
                                               checked="checked"<?php endif ?>/>{{ Lang::get('messages.admin.settings.captcha.form_report') }}
                                </label>
                                <label class="checkbox">
                                    <input type="checkbox" name="site.captcha[form_contact]" value="1" <?php if (isset($captcha->form_contact) && $captcha->form_contact === '1'): ?>
                                               checked="checked"<?php endif ?>/>{{ Lang::get('messages.admin.settings.captcha.form_contact') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                {{ Form::submit(Lang::get('messages.admin.settings.save'), ['class' => 'btn btn-primary pull-right']) }}
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#disqusOption').click(function () {
            disqus();
        });

        function disqus() {
            if ($('#disqusOption').is(':checked')) {
                $('#disqusUrl').show();
            } else {
                $('#disqusUrl').hide().val('');
            }
        }
        disqus();
    });
</script>
@endsection