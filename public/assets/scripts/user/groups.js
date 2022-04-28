// Groups scripts
const Groups = function () {
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
                url: '/user/groups/add/' + groupId,
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
                url: '/user/groups/remove/' + groupId,
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

    const handleCreate = function () {
        const $form = $('#form-create-group');

        $form.on('submit', function (e) {
            e.preventDefault();

            const $description = $form.find('#group-desc');
            const $name = $form.find('#group-name');

            let description = $description.val();
            let name = $name.val();

            if (App.empty(description) || App.empty(name)) {
                App.alert('Wszystkie pola są wymagane!');
                return false;
            }

            App.AjaxStandard({
                isAlertFormValid: false,
                preloaderButton: true,
                preloaderType: 'inside',

                btn: $form.find('button'),
                url: '/user/own-groups/create',
                method: 'POST',
                data: {
                    name: name,
                    description: description
                },
                auth: true,

                success: function () {
                    App.alert('Grupa została utworzona!', 'Udało się!', 'success').then(function (result) {
                        if (result.value) {
                            location.reload();
                        }
                    });
                }
            });
        });
    };

    const handlEdit = function () {
        $('.btn-edit-group').on('click', function () {
            const $t = $(this);
            const $text = $t.find('span');

            const $parent = $t.parents('.friend-item');
            const $content = $parent.find('.friend-item-content');
            const $name = $parent.find('.friend-name');

            if (!$parent.hasClass('group-edit')) {
                $parent.find('.friend-item-content span, .friend-name strong').hide();
                $parent.addClass('group-edit');

                let view =
                    '<div class="post-edit">' +
                    '<textarea class="form-control edit-message">' + $content.find('span').text() + '</textarea>' +
                    '<button class="custom-button bg-color-green btn-save-edit"><span>Zapisz</span></button>' +
                    '</div>';

                $content.append(view);

                view = '<input class="form-control edit-name" value="' + $name.find('strong').text() + '">';
                $name.append(view);

                $parent.addClass('group-edit');
                $text.text('Anuluj');

                $(document).on('click', '.btn-save-edit', function () {
                    const $btn = $(this);

                    let groupId = $t.parents('.friend-item-right').attr('data-group-id');
                    let message = $parent.find('.edit-message').val();

                    if (App.empty(message)) {
                        return false;
                    }

                    const $description = $parent.find('.edit-message');
                    const $name = $parent.find('.edit-name');

                    let description = $description.val();
                    let name = $name.val();

                    if (App.empty(description) || App.empty(name)) {
                        App.alert('Wszystkie pola są wymagane!');
                        return false;
                    }

                    App.AjaxStandard({
                        isAlertFormValid: false,
                        preloaderButton: true,
                        preloaderType: 'inside',

                        btn: $btn,
                        url: '/user/own-groups/edit/' + groupId,
                        method: 'POST',
                        auth: true,
                        data: {
                            name: name,
                            description: description
                        },

                        success: function () {
                            App.alert('Post został zaktualizowany!', '', 'success').then(function (result) {
                                if (result.value) {
                                    location.reload();
                                }
                            });
                        }
                    });
                });
            } else {
                $parent.find('.friend-item-content span, .friend-name strong').show();
                $parent.find('.edit-name, .edit-message, .btn-save-edit').remove();

                $parent.removeClass('group-edit');
                $text.text('Edytuj');
            }
        });
    };

    const handleDelete = function () {
        $('.btn-delete-group').on('click', function () {
            let groupId = $(this).parents('.friend-item-right').attr('data-group-id');

            App.AjaxStandard({
                preloader: false,

                url: '/user/own-groups/delete/' + groupId,
                method: 'DELETE',
                auth: true,

                success: function () {
                    App.alert('Grupa została usunięta!', '', 'success').then(function (result) {
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

            handleCreate();
            handlEdit();
            handleDelete();
        }
    }
}();

$(function () {
    Groups.init();
});
