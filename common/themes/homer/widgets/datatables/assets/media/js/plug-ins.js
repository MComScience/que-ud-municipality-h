jQuery.extend(jQuery.fn.dataTableExt.oSort, {
    "date-uk-pre": function (a) {
        if (a == null || a == "") {
            return 0;
        }
        var ukDatea = a.split('/');
        return (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1;
    },

    "date-uk-asc": function (a, b) {
        return ((a < b) ? -1 : ((a > b) ? 1 : 0));
    },

    "date-uk-desc": function (a, b) {
        return ((a < b) ? 1 : ((a > b) ? -1 : 0));
    },
    "currency-pre": function (a) {
        a = (a === "-") ? 0 : a.replace(/(\/.*)/i, "");
        a = (a === "-") ? 0 : a.replace(/[^\d\-\.]/g, "");
        return parseFloat(a);
    },

    "currency-asc": function (a, b) {
        return a - b;
    },

    "currency-desc": function (a, b) {
        return b - a;
    }
});