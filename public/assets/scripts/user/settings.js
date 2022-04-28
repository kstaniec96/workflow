// Settings scripts
const Settings = function () {
    const handleUpdate = function () {
        const $form = $('#form-basic-settings');

        if (App.exists($form)) {
            $form.on('submit', function (e) {
                e.preventDefault();

                App.form($form, {
                    url: '/user/settings/update',
                    auth: true,
                    stopped: true,
                    data: {
                        email: $form.find('#email').val(),
                        username: $form.find('#username').val(),
                    },

                    callback: function () {
                        App.alert('Dane zostały zapisane!', '', 'success');
                    }
                });
            });
        }
    };

    const handleChange = function () {
        const $form = $('#form-change-password');

        if (App.exists($form)) {
            $form.on('submit', function (e) {
                e.preventDefault();

                App.form($form, {
                    url: '/user/settings/change',
                    auth: true,
                    stopped: true,
                    data: {
                        current_password: $form.find('#current-password').val(),
                        new_password: $form.find('#new-password').val(),
                    },

                    callback: function () {
                        App.alert('Hasło zostało zmienione!', '', 'success').then(function (result) {
                            if (result.value) {
                                location.reload();
                            }
                        });
                    }
                });
            });
        }
    };

    return {
        init: function () {
            handleUpdate();
            handleChange();
        }
    }
}();

$(function () {
    Settings.init();
});
