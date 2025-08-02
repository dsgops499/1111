<style>
    h3  {
        border-bottom: 1px solid #eee;
    }
    .permission {
        padding: 6px 0 4px 0;
    }
</style>
<div class="box-body">
    <div class="row">
        <div class="col-md-12">
            <?php foreach ($permissions as $name => $value): ?>
                <div class="row">
                    <div class="col-md-12">
                        <h3>{{ ucfirst($name) }}</h3>
                    </div>
                </div>
                <?php foreach ($value as $subPermissionTitle => $permissionActions): ?>
                    <div class="permissionGroup">
                        <div class="row">
                            <div class="col-md-4">
                                <h4 class="pull-left">{{ ucfirst($subPermissionTitle) }}</h4>
                            </div>
                            <div class="col-md-8">
                                <p class="pull-left" style="margin-top:10px">
                                    <a href="" class="jsSelectAllAllow">allow all</a>
                                    <span style="margin:0 10px;">|</span>
                                    <a href="" class="jsSelectAllDeny">deny all</a>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <?php foreach ($permissionActions as $permissionAction => $permissionLabel): ?>
                                <div class="col-md-12">
                                    @include('user::admin.partials.permission-part')
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
@include('user::admin.partials.permissions-script')
