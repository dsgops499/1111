<div class="box box-primary box-link-menus"
     data-type="custom-link">
    <div class="box-header with-border">
        <h3 class="box-title">
            <i class="icon-layers font-dark"></i>
            {{ Lang::get('messages.admin.settings.custom_link') }}
        </h3>
        <div class="box-tools">
            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                <i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <div class="form-group">
            <label class="control-label"><b>{{ Lang::get('messages.admin.settings.title') }}</b></label>
            <input type="text"
                   class="form-control"
                   placeholder=""
                   value=""
                   name=""
                   data-field="title"
                   autocomplete="off">
        </div>
        <div class="form-group">
            <label class="control-label"><b>{{ Lang::get('messages.admin.settings.url') }}</b></label>
            <input type="text"
                   class="form-control"
                   placeholder="http:// or # when creating parent Menu"
                   value=""
                   name=""
                   data-field="url"
                   autocomplete="off">
        </div>
        <div class="form-group">
            <label class="control-label"><b>{{ Lang::get('messages.admin.settings.css_class') }}</b></label>
            <input type="text"
                   class="form-control"
                   placeholder=""
                   value=""
                   name=""
                   data-field="css_class"
                   autocomplete="off">
        </div>
        <div class="form-group">
            <label class="control-label"><b>{{ Lang::get('messages.admin.settings.icon_font') }}</b></label>
            <input type="text"
                   class="form-control"
                   placeholder="fa fa-times"
                   value=""
                   name=""
                   data-field="icon_font"
                   autocomplete="off">
        </div>
        <div class="form-group">
            <label class="control-label"><b>{{ Lang::get('messages.admin.settings.target_type') }}</b></label>
            <select name="" class="form-control" data-field="target">
                <option value="">not set</option>
                <option value="_self">self</option>
                <option value="_blank">blank</option>
                <option value="_parent">parent</option>
                <option value="_top">top</option>
            </select>
        </div>
    </div>
    <div class="box-footer text-right">
        <button class="btn btn-primary add-item"
                type="submit">
            <i class="fa fa-plus"></i> {{ Lang::get('messages.admin.settings.add') }}
        </button>
    </div>
</div>