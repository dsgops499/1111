$(function () {
    var url = window.location;
    var element = $('ul.sidebar-menu a').filter(function () {
        return this.href == url || url.href.indexOf(this.href) == 0;
    }).parent();

    if (element.is('li')) {
        if (element.parent().is('ul.treeview-menu')) {
            element.parent().parent().addClass('active open');
        }

        element.addClass('active');
    }
});

