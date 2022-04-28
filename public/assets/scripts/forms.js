// Auth Scripts
const Forms = function () {
    const handleLoginForm = function () {
        const $form = $('#form-login');

        if (App.exists($form)) {
            $form.on('submit', function (e) {
                e.preventDefault();

                App.form($form, {
                    url: '/auth/login',
                    data: {
                        email: $form.find('#email').val(),
                        password: $form.find('#password').val(),
                    },

                    callback: function (response) {
                        localStorage.setItem('access_token', response.access_token);
                    }
                });
            });
        }
    };

    const handleRegister = function () {
        const $form = $('#form-register');

        if (App.exists($form)) {
            $form.on('submit', function (e) {
                e.preventDefault();

                App.form($form, {
                    url: '/auth/register',
                    stopped: true,
                    register: true,
                    data: {
                        email: $form.find('#email').val(),
                        username: $form.find('#username').val(),
                        password: $form.find('#password').val(),
                    },

                    callback: function () {
                        $form.find('input').val('');
                        App.alert('Na Twój adres e-mail została wysłana wiadomość w celu potwierdzenia konta.', '', 'success');
                    }
                });
            });
        }
    };

    const handleFirstStepPassword = function () {
        const $form = $('#form-first-password');

        if (App.exists($form)) {
            $form.on('submit', function (e) {
                e.preventDefault();

                App.form($form, {
                    url: '/auth/password',
                    stopped: true,
                    data: {
                        email: $form.find('#email').val(),
                    },

                    callback: function () {
                        $('#email').val('');
                        App.alert('Na Twój adres e-mail została wysłana wiadomość z instrukcją zmiany hasła.', '', 'success');
                    }
                });
            });
        }
    };

    const handleSecondStepPassword = function () {
        const $form = $('#form-second-password');

        if (App.exists($form)) {
            $form.on('submit', function (e) {
                e.preventDefault();

                App.form($form, {
                    url: '/auth/password/change',
                    password: true,
                    stopped: true,
                    data: {
                        password: $form.find('#password').val(),
                        password_confirm: $form.find('#password-confirm').val(),
                        token: $('#token').val(),
                    },

                    callback: function () {
                        App.cswal({
                            title: '',
                            text: 'Twoje hasło zostało zmienione!',
                            type: 'success',
                        }).then(function (result) {
                            if (result.value) {
                                location.href = '/auth/login';
                            }
                        });
                    }
                });
            });
        }
    };

    const handleSecondStepRegister = function () {
        const $form = $('#form-second-register');

        if (App.exists($form)) {
            $form.on('submit', function (e) {
                e.preventDefault();

                let serialize = [];
                let exists = false;

                $form.find('.groups input:checked').each(function () {
                    serialize.push($(this).val());
                    exists = true;
                });

                if (!exists) {
                    App.alert('Musisz wybrać przynajmniej jedną grupę!');
                    return false;
                }

                App.form($form, {
                    url: '/auth/register/groups',
                    stopped: true,
                    data: {
                        groups: serialize,
                        token: $('#token').val(),
                    },

                    callback: function () {
                        location.href = '/auth/login';
                    }
                });
            });
        }
    };

    return {
        init: function () {
            handleLoginForm();
            handleFirstStepPassword();
            handleSecondStepPassword();
            handleRegister();
            handleSecondStepRegister();
        }
    }
}();

$(function () {
    Forms.init();
});
