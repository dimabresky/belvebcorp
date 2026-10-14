BX.namespace('Belveb.Booking');

BX.Belveb.Booking.action = function (name, data) {
    return BX.ajax.runAction('belveb:booking.' + name, {data: data || {}});
};

BX.Belveb.Booking.actionError = function (response) {
    var errors = response && response.errors;
    if (errors && errors[0] && errors[0].message) {
        return errors[0].message;
    }

    return 'Не удалось выполнить действие';
};

BX.Belveb.Booking.mount = function (payload) {
    var tags = {
        rooms: 'belveb-booking-rooms-list',
        my: 'belveb-booking-my-bookings',
        room: 'belveb-booking-room-detail',
    };
    var tag = tags[payload.page] || tags.rooms;
    var bitrixVue = BX.Vue3.BitrixVue;
    var app = bitrixVue.createApp({
        data: function () {
            return {payload: payload};
        },
        template: '<' + tag + ' :payload="payload"></' + tag + '>',
    });
    Object.keys(bitrixVue.components).forEach(function (name) {
        app.component(name, bitrixVue.getMutableComponent(name));
    });
    app.mount('#belveb-booking-app');
};

BX.Belveb.Booking.isStored = function (payload, id) {
    return (payload.bookings || []).some(function (row) {
        return row.id === id;
    });
};

BX.Belveb.Booking.bookingFields = function (booking) {
    return {
        room: booking.room().code(),
        date: booking.date().text(),
        timeFrom: booking.range().start().text(),
        timeTo: booking.range().end().text(),
        title: booking.title(),
        description: booking.description(),
        participants: booking.participants().view().map(function (person) {
            return person.id;
        }),
        repeat: booking.series().rule(),
    };
};
