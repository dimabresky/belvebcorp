BX.namespace('Belveb.Booking.Domain');

(function (Domain) {
    Domain.ViewMapper = function () {};

    Domain.ViewMapper.prototype.capture = function (payload) {
        var clock = new Domain.FixedClock(
            new Domain.CalendarDate(payload.clock.date),
            new Domain.MinuteOfDay(payload.clock.time)
        );
        var rooms = payload.rooms.map(function (row) {
            return new Domain.Room(row);
        });
        var people = payload.people.map(function (row) {
            return row.id ? new Domain.Person(row.id, row.name) : new Domain.Nobody();
        });
        var user = new Domain.Person(payload.user.id, payload.user.name);
        var bookings = payload.bookings.map(function (row) {
            return this.booking(row, rooms, people);
        }, this);

        return {
            payload: payload,
            clock: clock,
            user: user,
            rooms: rooms,
            people: people,
            bookings: bookings,
            filter: this.filter(payload.filter),
            roomByCode: function (code) {
                var found = rooms.find(function (room) {
                    return room.code() === code;
                });
                return found || null;
            },
            timelineFor: function (room, date, ignoreId) {
                var rows = bookings.filter(function (booking) {
                    return booking.room().code() === room.code();
                });
                return new Domain.Timeline(room.grid(), rows, user, date, clock, ignoreId || 0);
            },
        };
    };

    Domain.ViewMapper.prototype.filter = function (row) {
        return new Domain.RoomFilter(
            new Domain.CalendarDate(row.date),
            new Domain.TimeRange(new Domain.MinuteOfDay(row.timeFrom), new Domain.MinuteOfDay(row.timeTo)),
            row.participants,
            row.floor,
            row.type
        );
    };

    Domain.ViewMapper.prototype.booking = function (row, rooms, people) {
        var room = rooms.find(function (item) {
            return item.code() === row.room.code;
        });
        var organizer = people.find(function (person) {
            return person.id() === row.organizer.id;
        }) || new Domain.Person(row.organizer.id, row.organizer.name);
        var participants = new Domain.ParticipantList(row.participants.map(function (item) {
            var known = people.find(function (person) {
                return person.id() === item.id;
            });
            return known || new Domain.Person(item.id, item.name);
        }));
        var series = new Domain.SeriesRule(row.repeat.rule, new Domain.CalendarDate(row.repeat.from), 90);

        return new Domain.Booking(
            row.id,
            room || new Domain.Room(row.room),
            organizer,
            new Domain.CalendarDate(row.date),
            new Domain.TimeRange(new Domain.MinuteOfDay(row.timeFrom), new Domain.MinuteOfDay(row.timeTo)),
            row.title,
            row.description || '',
            participants,
            row.status,
            series,
            row.sourceId || 0
        );
    };

    Domain.ViewMapper.prototype.series = function (rule, from) {
        return new Domain.SeriesRule(rule, new Domain.CalendarDate(from), 90);
    };
})(BX.Belveb.Booking.Domain);
