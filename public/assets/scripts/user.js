const User = function () {
    const $parent = $('#user-pages');
    const $sidebar = $parent.find('#sidebar-page');
    const $navbar = $parent.find('#navbar-page');
    const $main = $parent.find('#section-main-content');

    const handlePosts = function () {
        $(window).resize(function () {
            let sidebarHeight = $sidebar.outerHeight();
            let navbarHeight = $navbar.outerHeight();

            $main.css('min-height', (sidebarHeight > navbarHeight ? sidebarHeight : navbarHeight) + 'px');
        }).resize();
    };

    const handleStickyBoxes = function () {
        $sidebar.theiaStickySidebar({
            updateSidebarHeight: !1,
            additionalMarginTop: 140
        });

        $navbar.theiaStickySidebar({
            updateSidebarHeight: !1,
            additionalMarginTop: 140
        });
    };

    return {
        init: function () {
            if (App.exists($parent)) {
                handlePosts();
                handleStickyBoxes();
            }
        },
    }
}();

$(function () {
    User.init();
});
