// Default Groups scripts
const DefaultGroups = function () {
    const handleAdd = function () {
        $('.btn-add-group').on('click', function (e) {
            e.preventDefault();

            const $btn = $(this);
            let groupId = $btn.parents('.friend-item-right').attr('data-group-id');

            App.AjaxStandard({
                isAlertFormValid: false,
                preloaderButton: true,
                preloaderType: 'inside',

                btn: $btn,
                url: '/user/default-groups/add/' + groupId,
                method: 'POST',
                auth: true,

                success: function () {
                    App.alert('Dołączyłeś/aś do grupy!', '', 'success').then(function (result) {
                        if (result.value) {
                            location.reload();
                        }
                    });
                }
            });
        });
    };

    const handleRemove = function () {
        $('.btn-remove-group').on('click', function (e) {
            e.preventDefault();

            const $btn = $(this);
            let groupId = $btn.parents('.friend-item-right').attr('data-group-id');

            App.AjaxStandard({
                isAlertFormValid: false,
                preloaderButton: true,
                preloaderType: 'inside',

                btn: $btn,
                url: '/user/default-groups/remove/' + groupId,
                method: 'DELETE',
                auth: true,

                success: function () {
                    App.alert('Opuściłeś/aś grupę!', '', 'success').then(function (result) {
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
    DefaultGroups.init();
});
