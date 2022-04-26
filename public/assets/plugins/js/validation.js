const Validation = function () {
    const handleValidEmail = function (email) {
        const regex_email = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return regex_email.test(email);
    };

    const handleValidPhone = function (phone) {
        const regex_phone = /^([0-9]{9})$/;
        return regex_phone.test(phone);
    };

    return {
        email: function (email) {
            return handleValidEmail(email);
        },

        phone: function (phone) {
            return handleValidPhone(phone);
        },
    }
}();
