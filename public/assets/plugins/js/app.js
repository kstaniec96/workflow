const App = function () {
    const handleIsset = function (issetlet, caselet) {
        return issetlet !== undefined && issetlet !== null ? issetlet : caselet;
    };

    const handleExists = function (el) {
        return el.length > 0;
    }

    const handleAlert = function (text, title, type) {
        return swal.fire({
            icon: handleIsset(type, 'error'),
            title: handleIsset(title, 'Błąd!'),
            html: handleIsset(text, 'Uzupełnij wymagane pola!')
        });
    };

    const handleAlertConfirm = function (config) {
        return swal.fire({
            icon: handleIsset(config.type, 'warning'),
            title: handleIsset(config.title, 'Wymagane potwierdzenie'),
            html: handleIsset(config.text, 'Ta akcja wymagana dodatkowego potwierdzenia'),
            showCancelButton: handleIsset(config.showCancelButton, true),
            cancelButtonText: 'Nie',
            confirmButtonText: 'Tak',
        }).then(function (result) {
            if (result.value) {
                if (config.yes && typeof (config.yes) === 'function') {
                    config.yes.call(this);
                }
            }
        });
    };

    const handleEmpty = function (mixedlet) {
        let undef, key, i, len, emptyValues = [undef, null, false, '']

        for (i = 0, len = emptyValues.length; i < len; i++) {
            if (mixedlet === emptyValues[i]) return true
        }

        if (typeof mixedlet === 'object') {
            for (key in mixedlet) {
                if (mixedlet.hasOwnProperty(key)) return false;
            }

            return true
        }

        return false
    };

    const handleInArray = function (needle, haystack, argStrict) {
        let key = ''
        let strict = !!argStrict;

        if (strict) {
            for (key in haystack) {
                if (haystack[key] === needle) {
                    return true
                }
            }
        } else {
            for (key in haystack) {
                if (haystack[key] == needle) {
                    return true
                }
            }
        }

        return false
    };

    const handleGetUrl = function (path) {
        return handleGetHost(window.location.pathname.substring(1) + '/' + path);
    };

    const handleGetHost = function (path) {
        return window.location.protocol + '//' + window.location.hostname + '/' + path;
    };

    const handleGetAsset = function (path) {
        return handleGetHost('assets/' + path);
    };

    const handleGetPageData = function () {
        let $container = $('.n2i7xgqFWq');

        if ($container.length > 0) {
            let data = $container.data('data').toString();

            if (data.indexOf(',') !== -1) {
                data = data.split(',');
            }

            return data;
        }

        return null;
    };

    const handleCswal = function (config) {
        return swal.fire({
            title: config.title,
            html: config.text,
            icon: config.type,

            allowOutsideClick: false
        });
    };

    const handleGetURLParams = function () {
        return (new URL(document.location)).searchParams;
    };

    const handleGetUrlPathName = function (toArray) {
        let pathname = window.location.pathname.substring(1);

        if (toArray === true) {
            return pathname.split('/');
        }

        return pathname;
    };

    const handleAjaxProblem = function (config) {
        swal.fire({
            title: config.title,
            icon: 'error',
            html: !handleEmpty(config.info) ? config.text + '<br><br>' + config.info : config.text,

            showCancelButton: handleIsset(config.cancelButton, true),
            showConfirmButton: handleIsset(config.confirmButton, true),
            cancelButtonText: !handleEmpty(config.cancelButtonText) ? config.cancelButtonText : 'Zamknij',

            confirmButtonText: 'Ponów',
            allowOutsideClick: false
        }).then(function (result) {
            if (result.value) {
                if (config.callbackValue && typeof (config.callbackValue) === 'function') {
                    config.callbackValue.call(this);
                }
            } else {
                if (config.reload) {
                    location.reload();
                } else if (config.callback && typeof (config.callback) === 'function') {
                    config.callback.call(this);
                }
            }
        });
    };

    const handleAjaxStandard = function (config) {
        let $btn = config.btn;

        let preloader = handleIsset(config.preloader, true);
        let preloaderType = handleIsset(config.preloaderType, 'normal');
        let url = App.isset(config.url, '');
        let auth = App.isset(config.auth, false);

        $().Ajax().load({
            preloader: {
                show: false,
            },

            statutes: {
                allowed: handleIsset(config.allowed, [true, null]),
            },

            source: {
                method: config.method,
                url: url,
                cache: config.cache,
                processData: config.processData,
                contentType: config.contentType,
                data: config.data,
                async: handleIsset(config.async, true),
                auth: auth,

                before: function () {
                    if (preloader) {
                        $btn.preloaderButton(preloaderType).show();
                    }

                    if (config.before && typeof (config.before) === 'function') {
                        return config.before.call(this);
                    }
                },

                done: function (response) {
                    if ($btn !== undefined) {
                        $btn.removeClass('stoped');
                        $btn.prop('disabled', false);
                    }

                    if (preloader) {
                        $btn.preloaderButton(preloaderType).hide();
                    }

                    if (config.success && typeof (config.success) === 'function') {
                        return config.success.call(this, response);
                    }
                },

                fail: function () {
                    if ($btn !== undefined) {
                        $btn.removeClass('stoped');
                        $btn.prop('disabled', false);
                    }

                    if (preloader) {
                        $btn.preloaderButton(preloaderType).hide();
                    }

                    if (config.fail && typeof (config.fail) === 'function') {
                        return config.fail.call(this);
                    }
                }
            }
        });
    };

    const handleRand = function () {
        return Math.random().toString(36).slice(2);
    };

    const handleHashCode = function (str) {
        let hash = 0, i, chr;

        for (i = 0; i < str.length; i++) {
            chr = str.charCodeAt(i);
            hash = ((hash << 5) - hash) + chr;
            hash |= 0; // Convert to 32bit integer
        }

        return hash;
    };

    const handleAppendToStorage = function (name, data, value) {
        let old = localStorage.getItem(name);

        if (old === null) {
            old = ''
        }

        let array = JSON.parse(old) || [];

        if (array.indexOf(value) === -1) {
            array.push(data);
        }

        localStorage.setItem(name, array + data);
    };

    const handleToggleStorage = function (key, value) {
        localStorage.removeItem(key);
        localStorage.setItem(key, value);
    };

    const handleBoolToInt = function (val) {
        return val ? 1 : 0;
    };

    const handleToInt = function (val) {
        return parseInt(val, 10);
    };

    const handleRefreshPage = function (_s) {
        let s = _s !== undefined ? _s : 300;

        (function (seconds) {
            let refresh,
                intvrefresh = function () {
                    clearInterval(refresh);
                    refresh = setTimeout(function () {
                        location.href = location.href;
                    }, seconds * 1000);
                };

            $(document).on('keypress click mousemove', function () {
                intvrefresh()
            });
            intvrefresh();
        }(s));
    };

    const handleGetData = function (index) {
        return $('#data').attr('data-' + index);
    };

    const handleReadURL = function (input) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();

            reader.onload = function (e) {
                const $preview = $('.preview-upload');

                $preview.css('background-image', 'url(' + e.target.result + ')');
                $preview.attr('data-base64-image', true);
            }

            reader.readAsDataURL(input.files[0]);
        }
    };

    const handleIsSafari = function () {
        let ua = navigator.userAgent.toLowerCase();

        if (ua.indexOf('safari') != -1) {
            return ua.indexOf('chrome') <= -1;
        }
    };

    const handleCompare = function (a, b) {
        return a == b;
    };

    return {
        alert: function (text, title, type) {
            return handleAlert(text, title, type);
        },

        alertConfirm: function (config) {
            return handleAlertConfirm(config);
        },

        empty: function (mixedlet) {
            return handleEmpty(mixedlet);
        },

        inArray: function (needle, haystack, argStrict) {
            return handleInArray(needle, haystack, argStrict);
        },

        isset: function (issetlet, caselet) {
            return handleIsset(issetlet, caselet);
        },

        exists: function (el) {
            return handleExists(el);
        },

        getUrl: function (path) {
            return handleGetUrl(path);
        },

        getHost: function (path) {
            return handleGetHost(path);
        },

        getAsset: function (path) {
            return handleGetAsset(path);
        },

        getPageData: function () {
            return handleGetPageData();
        },

        cswal: function (config) {
            return handleCswal(config);
        },

        getURLParams: function () {
            return handleGetURLParams();
        },

        getUrlPathName: function (toArray) {
            return handleGetUrlPathName(toArray);
        },

        AjaxProblem: function (config) {
            return handleAjaxProblem(config);
        },

        AjaxStandard: function (config) {
            return handleAjaxStandard(config);
        },

        appendToStorage: function (name, data) {
            handleAppendToStorage(name, data);
        },

        toggleStorage: function (key, value) {
            handleToggleStorage(key, value);
        },

        rand: function () {
            return handleRand();
        },

        hashCode: function (str) {
            return handleHashCode(str);
        },

        boolToInt: function (val) {
            return handleBoolToInt(val);
        },

        toInt: function (val) {
            return handleToInt(val);
        },

        refreshPage: function (_s) {
            handleRefreshPage(_s);
        },

        getData: function (index) {
            return handleGetData(index);
        },

        readURL: function (input) {
            return handleReadURL(input);
        },

        isSafari: function () {
            return handleIsSafari();
        },

        compare: function (a, b) {
            return handleCompare(a, b);
        },
    }
}();


//------------------------------------------------------------------
// PLUGINS
//------------------------------------------------------------------
(function ($) {
    $.fn.preloaderButton = function (type) {
        const $t = $(this);
        const $parent = $t.parent();

        return {
            show: function (callback) {
                if (type === 'normal') {
                    let view = '<div class="loader-circle-small loader-circle-black preloader-button"></div>';

                    $parent.append(view);
                    $parent.css({
                        display: 'flex',
                        'align-items': 'center',
                    });
                } else {
                    $t.find('span').hide();
                    $t.append('<div class="loader-circle-small loader-circle-black preloader-button"></div>');
                }

                if (callback && typeof (callback) === 'function') {
                    callback.call(this);
                }
            },

            hide: function (callback) {
                if (type === 'normal') {
                    $parent.find('.preloader-button').fadeOut(300, function () {
                        if (callback && typeof (callback) === 'function') {
                            callback.call(this);
                        }

                        $(this).remove();
                    });
                } else {
                    $('.preloader-button').fadeOut(300, function () {
                        if (callback && typeof (callback) === 'function') {
                            callback.call(this);
                        }

                        $t.find('span').show();
                        $(this).remove();
                    });
                }
            }
        }
    };

    $.fn.preloaderData = function () {
        const $t = $(this);
        const $preloader = $('#preloader');

        const sleep = function (milliseconds) {
            let start = new Date().getTime();

            for (let i = 0; i < 1e7; i++) {
                if ((new Date().getTime() - start) > milliseconds) break;
            }
        };

        return {
            show: function (fadeIn, callback) {
                if (App.empty($preloader)) {
                    let $view = '<div class="preloader" id="preloader"><div class="loader-circle"></div><span>Trwa ładowanie danych...</span></div>';

                    if (fadeIn == true) {
                        $view = $($view);
                        $view.hide().fadeIn(300);
                    }

                    if ($t.is('body')) $('body').addClass('overflow-hidden');
                    $t.prepend($view);
                }

                if (callback && typeof (callback) === 'function') {
                    callback.call(this);
                }
            },

            hide: function (callback) {
                sleep(300);

                if (!App.empty($preloader)) {
                    $preloader.fadeOut(300, function () {
                        $(this).remove();

                        if ($t.is('body')) $('body').removeClass('overflow-hidden');
                        if (callback && typeof (callback) === 'function') {
                            callback.call(this);
                        }
                    });
                }
            }
        };
    };

    $.fn.Ajax = function () {
        let $t = $(this);

        // Temp Ajax Data
        let loadSettings = {};

        const load = function (config) {
            let settings = $.extend({
                preloader: true,
                errors: true,
                failName: null,
                statutes: {},
                source: {},
                text: {}
            }, config);

            let success = false;
            let preloader = App.isset(settings.preloader.show, true);

            let url = App.isset(settings.source.url, '');
            let cache = App.isset(settings.source.cache, true);
            let processData = App.isset(settings.source.processData, true);
            let dataType = App.isset(settings.source.dataType, 'json');
            let contentType = App.isset(settings.source.contentType, 'application/x-www-form-urlencoded;charset=UTF-8');
            let method = App.isset(settings.source.method, 'POST');
            let allowed = App.isset(settings.statutes.allowed, [true, null]);
            let getStatus = App.isset(settings.statutes.getStatus, false);
            let async = App.isset(settings.source.async, true);
            let auth = App.isset(settings.source.auth, false);
            let none_data = App.isset(settings.text.none_data, 'Brak danych do wyświetlenia.');

            loadSettings = {
                preloader: {
                    show: preloader,
                    before: settings.preloader.before
                },

                failName: settings.failName,
                statutes: {
                    allowed: allowed,
                    getStatus: getStatus
                },

                source: {
                    url: url,
                    dataType: dataType,
                    contentType: contentType,
                    processData: processData,
                    cache: cache,
                    method: method,
                    data: settings.source.data,
                    before: settings.source.before,
                    done: settings.source.done,
                    fail: settings.source.fail,
                    reload: settings.source.reload,
                    async: async,
                    auth: auth,
                },

                text: {
                    none_data: none_data
                }
            };

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Authorization': auth ? 'Bearer ' + localStorage.getItem('access_token') : 'none',
                }
            });

            $.ajax({
                url: App.getHost('api' + url),
                method: method,
                dataType: dataType,
                contentType: contentType,
                processData: processData,
                cache: cache,
                async: async,
                data: settings.source.data,

                beforeSend: function () {
                    // Reload Ajax
                    if (settings.source.reload && typeof (settings.source.reload) === 'function') {
                        settings.source.reload.call(this);
                    }

                    // Preloader
                    if (settings.source.before && typeof (settings.source.before) === 'function') {
                        return settings.source.before.call(this);
                    } else if (preloader) {
                        $t.preloaderData().show(true);
                    }
                },
            }).done(function (response) {
                if (preloader) {
                    if (settings.preloader.before && typeof (settings.preloader.before) === 'function') {
                        return settings.preloader.before.call(this);
                    }

                    $t.preloaderData().hide(function () {
                        if ((response.status == null || (App.empty(response) && response !== undefined)) && none_data != false) {
                            $t.append('<div style="text-align:center"><p style="margin:10px 0">' + none_data + '</p></div>');
                        }

                        if (settings.source.done && typeof (settings.source.done) === 'function') {
                            return settings.source.done.call(this, response);
                        }
                    });
                } else {
                    if (settings.source.done && typeof (settings.source.done) === 'function') {
                        return settings.source.done.call(this, response);
                    }
                }

                success = true;
            }).fail(function (jqXHR, textStatus, errorThrown) {
                if (App.inArray(jqXHR.status, [422, 400])) {
                    App.alert(jqXHR.responseJSON.message, '', 'error');
                } else {
                    App.AjaxProblem({
                        title: 'Bład połączenia z serwerem!',
                        text: window.navigator.onLine ?
                            'Wystąpił problem z serwerem i Twoje żądanie nie może zostać obsłużone! Spróbuj ponownie ' +
                            'wysłać żądanie, bądź skontakuj się z administratorem.' :
                            'Sprawdź swoje połączenie z internetem.',

                        callbackValue: function () {
                            reload();
                        },

                        callback: function () {
                            $t.preloaderData().hide();
                        }
                    });
                }

                if (settings.source.fail && typeof (settings.source.fail) === 'function') {
                    return settings.source.fail.call(this, jqXHR, textStatus, errorThrown);
                }
            });

            return success;
        };

        const reload = function () {
            load(loadSettings);
        };

        const params = function (config) {
            for (let key in config) loadSettings.data[key] = config[key];
            reload();
        };

        return {
            load: function (settings) {
                load(settings)
            },
            params: function (settings) {
                params(settings)
            },
            reload: function () {
                reload()
            }
        };
    }
})(jQuery);

$(function () {
    autosize(document.querySelector('textarea'));
});
