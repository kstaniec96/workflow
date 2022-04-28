// Comments scripts
const Comments = function () {
    const handleShowComments = function () {
        $(document).on('click', '.btn-show-comments', function () {
            $(this).parents('.post-bottom').find('.post-bottom-comments').stop().slideToggle();
        });
    };

    const handleCreate = function () {
        $(document).on('click', '.btn-comment-create', function (e) {
            e.preventDefault();

            const $btn = $(this);
            const $parent = $btn.parents('.post-bottom-comments');
            const $message = $parent.find('.post-comment');

            let message = $message.val();
            let postId = $parent.attr('data-post-id');

            if (App.empty(message)) {
                App.alert('Wszystkie pola są wymagane!');
                return false;
            }

            App.AjaxStandard({
                isAlertFormValid: false,
                preloaderButton: true,
                preloaderType: 'inside',

                btn: $btn,
                url: '/user/comments/create/' + postId,
                method: 'POST',
                data: {
                    message: message
                },
                auth: true,

                success: function () {
                    App.alert('Komentarz został opublikowany!', 'Udało się!', 'success').then(function (result) {
                        if (result.value) {
                            location.reload();
                        }
                    });
                }
            });
        });
    };

    const handleEdit = function () {
        $(document).on('click', '.btn-comment-edit', function () {
            const $t = $(this);
            const $parent = $(this).parents('.post-comment-item').find('.post-comment-message');

            if (!App.exists($parent.find('.comment-edit'))) {
                $parent.find('span').hide();

                let view =
                    '<div class="comment-edit">' +
                    '<textarea class="form-control edit-comment-message">' + $parent.find('span').text() + '</textarea>' +
                    '<button class="custom-button bg-color-green btn-save-comment-edit"><span>Zapisz</span></button>' +
                    '</div>';

                $parent.append(view);
                $t.text('close');

                $(document).on('click', '.btn-save-comment-edit', function () {
                    const $btn = $(this);

                    let commentId = $t.parents('.post-comment-item').attr('data-comment-id');
                    let message = $parent.find('.edit-comment-message').val();

                    if (App.empty(message)) {
                        App.alert('Wszystkie pola są wymagane!');
                        return false;
                    }

                    App.AjaxStandard({
                        isAlertFormValid: false,
                        preloaderButton: true,
                        preloaderType: 'inside',

                        btn: $btn,
                        url: '/user/comments/edit/' + commentId,
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
                $parent.find('.comment-edit').remove();
                $t.text('edit');
            }
        });
    };

    const handleDelete = function () {
        $(document).on('click', '.btn-comment-delete', function () {
            const $btn = $(this);
            let commentId = $btn.parents('.post-comment-item').attr('data-comment-id');

            App.AjaxStandard({
                preloader: false,

                url: '/user/comments/delete/' + commentId,
                method: 'DELETE',
                auth: true,

                success: function () {
                    App.alert('Komentarz został usunięty!', '', 'success');
                    $btn.parents('.post-comment-item').remove();
                }
            });
        });
    };

    return {
        init: function () {
            handleShowComments();
            handleCreate();
            handleEdit();
            handleDelete();
        }
    }
}();

$(function () {
    Comments.init();
});
