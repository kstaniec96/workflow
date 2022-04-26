// Auth Scripts
const Forms = function () {
    const handleForm = function ($form, config) {
        let isOk = true;
        let text = 'Uzupełnij wymagane pola!';

        let $btn = $form.find('button[type="submit"]');
        const $required = $form.find('.required:visible');

        $required.each(function () {
            let $t = $(this);
            const $parent = $(this).parent();

            if ((App.empty($t.val())) || ($t.is(':checked') && $t.attr('type') === 'checkbox')) {
                $parent.addClass('input-danger');
                isOk = false;
            }
        });

        let password = App.isset(config.password, false);

        if (isOk) {
            const $email = $form.find('#email');

            if (Validation.email($email.val()) === false && password === false && !App.empty($email.val())) {
                isOk = false;
                text = 'Podany adres e-mail jest nieprawidłowy!';

                $email.parent().addClass('input-danger');
            }

            if (config.password === true) {
                const $password = $form.find('#password');
                const $passwordConfirm = $form.find('#password-confirm');

                if ($form.find('#password').val() !== $passwordConfirm.val()) {
                    isOk = false;
                    text = 'Podane hasła są różne!';

                    $password.parent().addClass('input-danger');
                    $passwordConfirm.parent().addClass('input-danger');
                }
            }

            if (isOk) {
                if ($btn.hasClass('stoped')) {
                    return false;
                }

                $btn.addClass('stoped');
                let stopped = App.isset(config.stopped, false);

                App.AjaxStandard({
                    isAlertFormValid: false,
                    preloaderButton: true,
                    preloaderType: 'inside',

                    btn: $btn,
                    url: config.url,
                    method: App.isset(config.method, 'POST'),
                    data: config.data,
                    auth: App.isset(config.auth, false),

                    success: function (response) {
                        if (config.callback && typeof (config.callback) === 'function') {
                            config.callback.call(this, response);
                        }

                        if (!stopped) {
                            location.reload();
                        }
                    }
                });
            }
        }

        if (!isOk) {
            App.alert(text);
        }

        $required.on('keyup change', function () {
            $(this).parent().removeClass('input-danger');
        });
    };

    const handleLoginForm = function () {
        const $form = $('#form-login');

        if (App.exists($form)) {
            $form.on('submit', function (e) {
                e.preventDefault();

                handleForm($form, {
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

                handleForm($form, {
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

                handleForm($form, {
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

                handleForm($form, {
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

                handleForm($form, {
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
