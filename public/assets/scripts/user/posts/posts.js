// Posts scripts
const Posts = function () {
    const handleCreate = function () {
        const $form = $('#form-share-post');

        $form.on('submit', function (e) {
            e.preventDefault();

            const $message = $form.find('#message');
            let message = $message.val();

            if (App.empty(message)) {
                App.alert('Wszystkie pola są wymagane!');
                return false;
            }

            App.AjaxStandard({
                isAlertFormValid: false,
                preloaderButton: true,
                preloaderType: 'inside',

                btn: $form.find('button'),
                url: '/user/posts/create',
                method: 'POST',
                data: {
                    message: message
                },
                auth: true,

                success: function () {
                    App.alert('Post został opublikowany!', 'Udało się!', 'success').then(function (result) {
                        if (result.value) {
                            location.reload();
                        }
                    });
                }
            });
        });
    };

    const handleEdit = function () {
        $('.btn-post-edit').on('click', function () {
            const $t = $(this);
            const $parent = $(this).parents('.post-item').find('.post-content');

            if (!App.exists($parent.find('.post-edit'))) {
                $parent.find('span').hide();

                let view =
                    '<div class="post-edit">' +
                    '<textarea class="form-control edit-message">' + $parent.find('span').text() + '</textarea>' +
                    '<button class="custom-button bg-color-green btn-save-edit"><span>Zapisz</span></button>' +
                    '</div>';

                $parent.append(view);
                $t.text('close');

                $(document).on('click', '.btn-save-edit', function () {
                    const $btn = $(this);

                    let postId = $t.parents('.post-options').attr('data-id');
                    let message = $parent.find('.edit-message').val();

                    if (App.empty(message)) {
                        App.alert('Wszystkie pola są wymagane!');
                        return false;
                    }

                    App.AjaxStandard({
                        isAlertFormValid: false,
                        preloaderButton: true,
                        preloaderType: 'inside',

                        btn: $btn,
                        url: '/user/posts/edit/' + postId,
                        method: 'POST',
                        auth: true,
                        data: {
                            message: message,
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
                $parent.find('span').show();
                $parent.find('.post-edit').remove();
                $t.text('edit');
            }
        });
    };

    const handleDelete = function () {
        $('.btn-post-delete').on('click', function () {
            const $btn = $(this);
            let postId = $btn.parents('.post-options').attr('data-id');

            App.AjaxStandard({
                preloader: false,

                url: '/user/posts/delete/' + postId,
                method: 'DELETE',
                auth: true,

                success: function () {
                    App.alert('Post został usunięty!', '', 'success');
                    $btn.parents('.post-item').remove();
                }
            });
        });
    };

    return {
        init: function () {
            handleCreate();
            handleEdit();
            handleDelete();
        }
    }
}();

$(function () {
    Posts.init();
});
