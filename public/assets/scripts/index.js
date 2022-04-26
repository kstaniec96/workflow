const Index = function () {
    const handleSessionAlert = function () {
        const $alert = $('#session-alert');

        if (App.exists($alert)) {
            let timeout = setTimeout(function () {
                $alert.fadeOut();
                clearTimeout(timeout);
            }, 4000);
        }
    };

    const handleDropdownMenu = function () {
        $('.dropdown-toggle').on('click', function () {
            const $btn = $(this);

            const $dropdown = $btn.parents('.dropdown-menu');
            const $target = $dropdown.find('.dropdown-menu-target');

            $target.stop().fadeToggle();
            $btn.toggleClass('clicked');

            $(document).mouseup(e => {
                if (!$dropdown.is(e.target) && $dropdown.has(e.target).length === 0) {
                    $target.stop().fadeOut();
                    $btn.removeClass('clicked');
                }
            });
        });
    };

    return {
        init: function () {
            handleSessionAlert();
            handleDropdownMenu();
        }
    }
}();

$(function () {
    Index.init();
});
