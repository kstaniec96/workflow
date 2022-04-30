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

    const handleDeleteAccount = function () {
        const $form = $('#form-delete-account');

        if (App.exists($form)) {
            $form.on('submit', function (e) {
                e.preventDefault();

                App.alertConfirm({
                    text: 'Czy na pewno chcesz usunąć konto? Nie ma możliwości jego przywrócenia.',
                    yes: function () {
                        App.form($form, {
                            url: '/user/settings/delete',
                            auth: true,
                            stopped: true,

                            callback: function () {
                                App.alert('Konto zostało usunięte!', '', 'success').then(function (result) {
                                    if (result.value) {
                                        location.reload();
                                    }
                                });
                            }
                        });
                    },
                });
            });
        }
    };

    return {
        init: function () {
            handleUpdate();
            handleChange();
            handleDeleteAccount();
        }
    }
}();

$(function () {
    Settings.init();
});
