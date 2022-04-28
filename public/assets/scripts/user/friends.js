// Friends scripts
const Friends = function () {
    const handleAdd = function () {
        $('.btn-add-friend').on('click', function (e) {
            e.preventDefault();

            const $btn = $(this);
            let friendId = $btn.parents('.friend-item-right').attr('data-friend-id');

            App.AjaxStandard({
                isAlertFormValid: false,
                preloaderButton: true,
                preloaderType: 'inside',

                btn: $btn,
                url: '/user/friends/add/' + friendId,
                method: 'POST',
                auth: true,

                success: function () {
                    App.alert('Użytkownik został dodany do grona znajomych!', '', 'success').then(function (result) {
                        if (result.value) {
                            location.reload();
                        }
                    });
                }
            });
        });
    };

    const handleRemove = function () {
        $('.btn-remove-friend').on('click', function (e) {
            e.preventDefault();

            const $btn = $(this);
            let friendId = $btn.parents('.friend-item-right').attr('data-friend-id');

            App.AjaxStandard({
                isAlertFormValid: false,
                preloaderButton: true,
                preloaderType: 'inside',

                btn: $btn,
                url: '/user/friends/remove/' + friendId,
                method: 'DELETE',
                auth: true,

                success: function () {
                    App.alert('Użytkownik został usunięty z grona znajomych!', '', 'success').then(function (result) {
                        if (result.value) {
                            location.reload();
                        }
                    });
                }
            });
        });
    };

    return {
        init: function () {
            handleAdd();
            handleRemove();
        }
    }
}();

$(function () {
    Friends.init();
});
