BX.namespace('Belveb.Booking.Domain');

(function (Domain) {
    Domain.Room = function (row) {
        this.row = row;
        this.roomGrid = new Domain.Grid(
            new Domain.MinuteOfDay(row.grid.workFrom),
            new Domain.MinuteOfDay(row.grid.workTo),
            row.grid.stepMinutes
        );
    };

    Domain.Room.prototype.code = function () {
        return this.row.code;
    };

    Domain.Room.prototype.capacity = function () {
        return this.row.capacity;
    };

    Domain.Room.prototype.floor = function () {
        return this.row.floor;
    };

    Domain.Room.prototype.type = function () {
        return this.row.type;
    };

    Domain.Room.prototype.grid = function () {
        return this.roomGrid;
    };

    Domain.Room.prototype.fits = function (participants) {
        return participants.fitsIn(this);
    };

    Domain.Room.prototype.availableFor = function (range, timeline) {
        return timeline.accepts(range);
    };

    Domain.Room.prototype.view = function () {
        return this.row;
    };

    Domain.RoomFilter = function (date, range, participants, floor, type) {
        if (participants < 1) {
            throw new Error('Participant count must be positive');
        }
        this.filterDate = date;
        this.filterRange = range;
        this.filterParticipants = participants;
        this.filterFloor = floor || '';
        this.filterType = type || '';
    };

    Domain.RoomFilter.prototype.date = function () {
        return this.filterDate;
    };

    Domain.RoomFilter.prototype.range = function () {
        return this.filterRange;
    };

    Domain.RoomFilter.prototype.participants = function () {
        return this.filterParticipants;
    };

    Domain.RoomFilter.prototype.matches = function (room, timeline) {
        if (this.filterFloor !== '' && String(room.floor()) !== String(this.filterFloor)) {
            return false;
        }
        if (this.filterType !== '' && room.type() !== this.filterType) {
            return false;
        }
        if (room.capacity() < this.filterParticipants) {
            return false;
        }
        return room.availableFor(this.filterRange, timeline);
    };

    Domain.RoomFilter.prototype.withFloor = function (floor) {
        return new Domain.RoomFilter(this.filterDate, this.filterRange, this.filterParticipants, floor, this.filterType);
    };

    Domain.RoomFilter.prototype.withType = function (type) {
        return new Domain.RoomFilter(this.filterDate, this.filterRange, this.filterParticipants, this.filterFloor, type);
    };

    Domain.RoomFilter.prototype.withParticipants = function (count) {
        return new Domain.RoomFilter(this.filterDate, this.filterRange, count, this.filterFloor, this.filterType);
    };

    Domain.RoomFilter.prototype.withRange = function (range) {
        return new Domain.RoomFilter(this.filterDate, range, this.filterParticipants, this.filterFloor, this.filterType);
    };

    Domain.RoomFilter.prototype.withDate = function (date) {
        return new Domain.RoomFilter(date, this.filterRange, this.filterParticipants, this.filterFloor, this.filterType);
    };

    Domain.RoomFilter.prototype.view = function () {
        return {
            date: this.filterDate.text(),
            timeFrom: this.filterRange.start().text(),
            timeTo: this.filterRange.end().text(),
            timeLabel: this.filterRange.label(),
            participants: this.filterParticipants,
            floor: this.filterFloor,
            type: this.filterType,
        };
    };

    Domain.Booking = function (id, room, organizer, date, range, title, description, participants, status, series, sourceId) {
        this.bookingId = id;
        this.bookingRoom = room;
        this.bookingOrganizer = organizer;
        this.bookingDate = date;
        this.bookingRange = range;
        this.bookingTitle = title;
        this.bookingDescription = description;
        this.bookingParticipants = participants;
        this.bookingStatus = status;
        this.bookingSeries = series;
        this.bookingSourceId = sourceId;
    };

    Domain.Booking.prototype.id = function () {
        return this.bookingId;
    };

    Domain.Booking.prototype.room = function () {
        return this.bookingRoom;
    };

    Domain.Booking.prototype.date = function () {
        return this.bookingDate;
    };

    Domain.Booking.prototype.range = function () {
        return this.bookingRange;
    };

    Domain.Booking.prototype.status = function () {
        return this.bookingStatus;
    };

    Domain.Booking.prototype.organizer = function () {
        return this.bookingOrganizer;
    };

    Domain.Booking.prototype.title = function () {
        return this.bookingTitle;
    };

    Domain.Booking.prototype.description = function () {
        return this.bookingDescription;
    };

    Domain.Booking.prototype.participants = function () {
        return this.bookingParticipants;
    };

    Domain.Booking.prototype.series = function () {
        return this.bookingSeries;
    };

    Domain.Booking.prototype.sourceId = function () {
        return this.bookingSourceId;
    };

    Domain.Booking.prototype.occupies = function () {
        return this.bookingStatus === 'booked';
    };

    Domain.Booking.prototype.canCancel = function (person) {
        return this.bookingStatus === 'booked' && this.bookingOrganizer.same(person);
    };

    Domain.Booking.prototype.isPast = function (clock) {
        if (this.bookingDate.compare(clock.date()) < 0) {
            return true;
        }
        if (this.bookingDate.compare(clock.date()) > 0) {
            return false;
        }
        return this.bookingRange.end().total() <= clock.time().total();
    };

    Domain.Booking.prototype.view = function (viewer, clock) {
        return {
            id: this.bookingId,
            room: this.bookingRoom.view(),
            status: this.bookingStatus,
            participantsCount: this.bookingParticipants.count(),
            participantsLabel: this.bookingParticipants.countLabel(),
            timeLabel: this.bookingRange.label(),
            timeFrom: this.bookingRange.start().text(),
            timeTo: this.bookingRange.end().text(),
            date: this.bookingDate.text(),
            dateLabel: this.bookingDate.text(),
            organizer: this.bookingOrganizer.view(),
            title: this.bookingTitle,
            description: this.bookingDescription,
            repeatLabel: this.bookingSeries.label(),
            repeat: this.bookingSeries.view(),
            participants: this.bookingParticipants.view(),
            canCancel: this.canCancel(viewer) && !this.isPast(clock),
            sourceId: this.bookingSourceId,
        };
    };

    Domain.RejectedDraft = function (code, message) {
        this.reasonCode = code;
        this.reasonMessage = message;
    };

    Domain.RejectedDraft.prototype.accepted = function () {
        return false;
    };

    Domain.RejectedDraft.prototype.code = function () {
        return this.reasonCode;
    };

    Domain.RejectedDraft.prototype.view = function () {
        return {accepted: false, code: this.reasonCode, message: this.reasonMessage};
    };

    Domain.ClearRange = function () {};
    Domain.ClearRange.prototype.blocked = function () {
        return false;
    };

    Domain.NearestFree = function (range) {
        this.freeRange = range;
    };
    Domain.NearestFree.prototype.found = function () {
        return true;
    };
    Domain.NearestFree.prototype.range = function () {
        return this.freeRange;
    };

    Domain.NoFreeSlot = function () {};
    Domain.NoFreeSlot.prototype.found = function () {
        return false;
    };

    Domain.Slot = function (range, state, title, organizer) {
        this.slotRange = range;
        this.slotState = state;
        this.slotTitle = title || '';
        this.slotOrganizer = organizer || '';
    };

    Domain.Slot.prototype.paint = function (selection) {
        if (this.slotState === 'busy' || this.slotState === 'mine') {
            return this;
        }
        if (!this.slotRange.overlaps(selection)) {
            return this;
        }
        return new Domain.Slot(this.slotRange, 'selected', this.slotTitle, this.slotOrganizer);
    };

    Domain.Slot.prototype.view = function () {
        return {
            from: this.slotRange.start().text(),
            to: this.slotRange.end().text(),
            state: this.slotState,
            title: this.slotTitle,
            organizer: this.slotOrganizer,
        };
    };

    Domain.Timeline = function (grid, bookings, viewer, day, clock, ignoreId) {
        this.lineGrid = grid;
        this.lineBookings = bookings;
        this.lineViewer = viewer;
        this.lineDay = day;
        this.lineClock = clock;
        this.lineIgnore = ignoreId || 0;
    };

    Domain.Timeline.prototype.ignore = function (bookingId) {
        return new Domain.Timeline(this.lineGrid, this.lineBookings, this.lineViewer, this.lineDay, this.lineClock, bookingId);
    };

    Domain.Timeline.prototype.slots = function () {
        var self = this;
        return this.lineGrid.cells().map(function (cell) {
            return self.slotFor(cell);
        });
    };

    Domain.Timeline.prototype.painted = function (selection) {
        var aligned = this.lineGrid.contains(selection) ? this.lineGrid.align(selection) : selection;
        return this.slots().map(function (slot) {
            return slot.paint(aligned);
        });
    };

    Domain.Timeline.prototype.accepts = function (range) {
        return this.conflict(range) instanceof Domain.ClearRange;
    };

    Domain.Timeline.prototype.conflict = function (range) {
        if (this.isPast(range)) {
            return new Domain.RejectedDraft('PAST_TIME', 'Нельзя бронировать прошедшее время');
        }
        if (!this.lineGrid.contains(range)) {
            return new Domain.RejectedDraft('TIME_BUSY', 'Время занято. Выберите другой слот');
        }
        var clash = this.active().some(function (booking) {
            return booking.range().overlaps(range);
        });
        if (clash) {
            return new Domain.RejectedDraft('TIME_BUSY', 'Время занято. Выберите другой слот');
        }
        return new Domain.ClearRange();
    };

    Domain.Timeline.prototype.nearestFree = function (duration) {
        if (duration < 1) {
            return new Domain.NoFreeSlot();
        }
        var cursor = this.searchStart();
        var limit = this.lineGrid.to().total();
        while (cursor.total() + duration <= limit) {
            var range = new Domain.TimeRange(cursor, cursor.plus(duration));
            if (this.accepts(range)) {
                return new Domain.NearestFree(range);
            }
            cursor = cursor.plus(this.lineGrid.stepMinutes());
        }
        return new Domain.NoFreeSlot();
    };

    Domain.Timeline.prototype.view = function () {
        return {
            updatedAt: this.lineClock.time().text(),
            now: this.lineClock.date().compare(this.lineDay) === 0 ? this.lineClock.time().text() : '',
            slots: this.slots().map(function (slot) {
                return slot.view();
            }),
        };
    };

    Domain.Timeline.prototype.slotFor = function (cell) {
        var booking = this.active().find(function (item) {
            return item.range().overlaps(cell);
        });
        if (booking) {
            var state = booking.organizer().same(this.lineViewer) ? 'mine' : 'busy';
            return new Domain.Slot(cell, state, booking.title(), booking.organizer().name());
        }
        if (this.lineClock.date().compare(this.lineDay) === 0 && cell.covers(this.lineClock.time())) {
            return new Domain.Slot(cell, 'now');
        }
        return new Domain.Slot(cell, 'free');
    };

    Domain.Timeline.prototype.active = function () {
        var self = this;
        return this.lineBookings.filter(function (booking) {
            return booking.id() !== self.lineIgnore
                && booking.occupies()
                && booking.date().compare(self.lineDay) === 0;
        });
    };

    Domain.Timeline.prototype.isPast = function (range) {
        if (this.lineDay.compare(this.lineClock.date()) < 0) {
            return true;
        }
        if (this.lineDay.compare(this.lineClock.date()) > 0) {
            return false;
        }
        return range.start().total() < this.lineClock.time().total();
    };

    Domain.Timeline.prototype.searchStart = function () {
        var from = this.lineGrid.from();
        if (this.lineClock.date().compare(this.lineDay) !== 0) {
            return from;
        }
        var now = this.lineClock.time().ceilTo(this.lineGrid.stepMinutes());
        return now.total() < from.total() ? from : now;
    };

    Domain.NoBooking = function () {};
    Domain.NoBooking.prototype.known = function () {
        return false;
    };

    Domain.AcceptedDraft = function (booking) {
        this.acceptedBooking = booking;
    };
    Domain.AcceptedDraft.prototype.accepted = function () {
        return true;
    };
    Domain.AcceptedDraft.prototype.booking = function () {
        return this.acceptedBooking;
    };
    Domain.AcceptedDraft.prototype.view = function () {
        return {accepted: true, message: 'Встреча забронирована'};
    };

    Domain.BookingDraft = function (room, organizer, date, range, title, description, participants, series, timeline, clock, origin) {
        this.draftRoom = room;
        this.draftOrganizer = organizer;
        this.draftDate = date;
        this.draftRange = range;
        this.draftTitle = title;
        this.draftDescription = description;
        this.draftParticipants = participants;
        this.draftSeries = series;
        this.draftTimeline = timeline;
        this.draftClock = clock;
        this.draftOrigin = origin;
    };

    Domain.BookingDraft.prototype.room = function () {
        return this.draftRoom;
    };

    Domain.BookingDraft.prototype.range = function () {
        return this.draftRange;
    };

    Domain.BookingDraft.prototype.withTitle = function (title) {
        return this.copy({title: title});
    };

    Domain.BookingDraft.prototype.withDescription = function (description) {
        return this.copy({description: description});
    };

    Domain.BookingDraft.prototype.withRange = function (range) {
        return this.copy({range: range});
    };

    Domain.BookingDraft.prototype.withDate = function (date, timeline) {
        return this.copy({date: date, timeline: timeline});
    };

    Domain.BookingDraft.prototype.withRoom = function (room, timeline) {
        return this.copy({room: room, timeline: timeline});
    };

    Domain.BookingDraft.prototype.withParticipants = function (participants) {
        return this.copy({participants: participants});
    };

    Domain.BookingDraft.prototype.withSeries = function (series) {
        return this.copy({series: series});
    };

    Domain.BookingDraft.prototype.unchanged = function () {
        var origin = this.draftOrigin;
        if (!(origin instanceof Domain.Booking)) {
            return false;
        }
        return origin.title() === this.draftTitle
            && origin.description() === this.draftDescription
            && origin.room().code() === this.draftRoom.code()
            && origin.date().compare(this.draftDate) === 0
            && origin.range().same(this.draftRange)
            && origin.participants().same(this.draftParticipants)
            && origin.series().same(this.draftSeries);
    };

    Domain.BookingDraft.prototype.confirm = function () {
        var title = (this.draftTitle || '').trim();
        if (title === '') {
            return new Domain.RejectedDraft('TITLE_REQUIRED', 'Укажите тему встречи');
        }
        if (!this.draftParticipants.fitsIn(this.draftRoom)) {
            return new Domain.RejectedDraft('CAPACITY_EXCEEDED', 'Слишком много участников для этой переговорной');
        }
        var conflict = this.draftTimeline.conflict(this.draftRange);
        if (conflict instanceof Domain.RejectedDraft) {
            return conflict;
        }
        var id = this.draftOrigin instanceof Domain.Booking ? this.draftOrigin.id() : 9000;
        var sourceId = this.draftOrigin instanceof Domain.Booking ? this.draftOrigin.sourceId() : 0;
        return new Domain.AcceptedDraft(new Domain.Booking(
            id,
            this.draftRoom,
            this.draftOrganizer,
            this.draftDate,
            this.draftRange,
            title,
            this.draftDescription,
            this.draftParticipants,
            'booked',
            this.draftSeries,
            sourceId
        ));
    };

    Domain.BookingDraft.prototype.view = function () {
        return {
            room: this.draftRoom.view(),
            date: this.draftDate.text(),
            timeFrom: this.draftRange.start().text(),
            timeTo: this.draftRange.end().text(),
            timeLabel: this.draftRange.label(),
            title: this.draftTitle,
            description: this.draftDescription,
            participants: this.draftParticipants.view(),
            participantsCount: this.draftParticipants.count(),
            ratio: this.draftParticipants.ratio(this.draftRoom),
            fits: this.draftParticipants.fitsIn(this.draftRoom),
            repeat: this.draftSeries.view(),
            unchanged: this.unchanged(),
            canSubmit: this.confirm().accepted(),
            originId: this.draftOrigin instanceof Domain.Booking ? this.draftOrigin.id() : 0,
        };
    };

    Domain.BookingDraft.prototype.copy = function (patch) {
        return new Domain.BookingDraft(
            patch.room || this.draftRoom,
            this.draftOrganizer,
            patch.date || this.draftDate,
            patch.range || this.draftRange,
            patch.title === undefined ? this.draftTitle : patch.title,
            patch.description === undefined ? this.draftDescription : patch.description,
            patch.participants || this.draftParticipants,
            patch.series || this.draftSeries,
            patch.timeline || this.draftTimeline,
            this.draftClock,
            this.draftOrigin
        );
    };
})(BX.Belveb.Booking.Domain);
