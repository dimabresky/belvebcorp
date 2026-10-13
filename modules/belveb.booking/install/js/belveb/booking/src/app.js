BX.namespace('Belveb.Booking');

BX.Belveb.Booking.mount = function (payload) {
    var tags = {
        rooms: 'belveb-booking-rooms-list',
        my: 'belveb-booking-my-bookings',
        room: 'belveb-booking-room-detail',
    };
    var tag = tags[payload.page] || tags.rooms;
    BX.Vue3.BitrixVue.createApp({
        data: function () {
            return {payload: payload};
        },
        template: '<' + tag + ' :payload="payload"></' + tag + '>',
    }).mount('#belveb-booking-app');
};
