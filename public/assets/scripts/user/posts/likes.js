// Likes scripts
const Likes = function () {
    const handleUp = function () {
        $(document).on('click', '.btn-like-post', function () {
            const $btn = $(this);
            const $parent = $btn.parents('.post-bottom');

            let postId = $parent.find('.post-bottom-comments').attr('data-post-id');

            App.AjaxStandard({
                preloader: false,

                url: '/user/likes/up/' + postId,
                auth: true,

                success: function () {
                    App.alert('Post został polubiony!', '', 'success');
                    handleLike($parent, 1);

                    $btn.removeClass('btn-like-post').addClass('btn-unlike-post');
                }
            });
        });
    };

    const handleDown = function () {
        $(document).on('click', '.btn-unlike-post', function () {
            const $btn = $(this);
            const $parent = $btn.parents('.post-bottom');

            let postId = $parent.find('.post-bottom-comments').attr('data-post-id');

            App.AjaxStandard({
                preloader: false,

                url: '/user/likes/down/' + postId,
                auth: true,

                success: function () {
                    App.alert('Polubienie posta zostało cofnięte!', '', 'success');
                    handleLike($parent, -1);

                    $btn.removeClass('btn-unlike-post').addClass('btn-like-post');
                }
            });
        });
    };

    const handleLike = function ($parent, value) {
        const $likes = $parent.find('.qnty-likes');
        let likes = parseInt($likes.text(), 10) + value;

        $likes.text(likes > 0 ? likes : 0);
    };

    return {
        init: function () {
            handleUp();
            handleDown();
        }
    }
}();

$(function () {
    Likes.init();
});
