<script>
    $( document ).ready(function() {
        $('.jsSelectAllAllow').on('click',function (event) {
            event.preventDefault();
            $(this).closest('.permissionGroup').find('.jsAllow').each(function (index, value) {
                $(value).prop('checked','checked');
            });
            $(this).closest('.permissionGroup').find('.jsDeny').each(function (index, value) {
                $(value).removeAttr('checked');
            });
        });
        $('.jsSelectAllDeny').on('click',function (event) {
            event.preventDefault();
            $(this).closest('.permissionGroup').find('.jsDeny').each(function (index, value) {
                $(value).prop('checked','checked');
            });
            $(this).closest('.permissionGroup').find('.jsAllow').each(function (index, value) {
                $(value).removeAttr('checked');
            });
        });
    });
</script>
