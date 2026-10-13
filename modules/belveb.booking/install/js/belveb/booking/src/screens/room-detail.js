BX.Vue3.BitrixVue.mutableComponent('belveb-booking-room-detail', {
    props: {
        payload: {type: Object, required: true},
    },
    data: function () {
        var Domain = BX.Belveb.Booking.Domain;
        var model = new Domain.ViewMapper().capture(this.payload);
        var room = model.roomByCode(this.payload.room.code);
        var day = new Domain.CalendarDate(this.payload.day);
        var range = new Domain.TimeRange(new Domain.MinuteOfDay('13:00'), new Domain.MinuteOfDay('14:00'));
        return {
            model: model,
            room: room,
            day: day,
            range: range,
            modal: null,
            toast: null,
            hidden: {},
        };
    },
    computed: {
        timeline: function () {
            return this.model.timelineFor(this.room, this.day, 0);
        },
        slots: function () {
            return this.timeline.painted(this.range).map(function (slot) {
                return slot.view();
            });
        },
        available: function () {
            return this.timeline.accepts(this.range);
        },
        days: function () {
            var selected = this.day.text();
            return this.payload.days.map(function (day) {
                return {
                    date: day.date,
                    label: day.label,
                    isToday: day.isToday,
                    selected: day.date === selected,
                };
            });
        },
        rows: function () {
            var self = this;
            return this.model.bookings.filter(function (booking) {
                return booking.room().code() === self.room.code()
                    && booking.date().text() === self.day.text()
                    && !self.hidden[booking.id()];
            }).map(function (booking) {
                return booking.view(self.model.user, self.model.clock);
            });
        },
        duration: function () {
            return this.range.durationMinutes();
        },
        nearestFound: function () {
            return this.timeline.nearestFree(this.duration).found();
        },
        modalView: function () {
            if (!this.modal) {
                return null;
            }
            return this.modal.view();
        },
    },
    methods: {
        onDay: function (date) {
            this.day = new BX.Belveb.Booking.Domain.CalendarDate(date);
        },
        onSelect: function (from, to) {
            var Domain = BX.Belveb.Booking.Domain;
            if (from === to) {
                return;
            }
            var start = from < to ? from : to;
            var end = from < to ? to : from;
            try {
                this.range = new Domain.TimeRange(new Domain.MinuteOfDay(start), new Domain.MinuteOfDay(end));
            } catch (error) {
                return;
            }
        },
        onDuration: function (minutes) {
            try {
                this.range = this.range.withDuration(minutes);
            } catch (error) {
                this.toast = {kind: 'error', text: 'Время занято. Выберите другой слот'};
            }
        },
        onNearest: function () {
            var offer = this.timeline.nearestFree(this.duration);
            if (!offer.found()) {
                this.toast = {kind: 'error', text: 'Время занято. Выберите другой слот'};
                return;
            }
            this.range = offer.range();
        },
        scrollToTime: function () {
            this.$refs.time.scrollIntoView({behavior: 'smooth', block: 'start'});
        },
        openConfirm: function () {
            var Domain = BX.Belveb.Booking.Domain;
            var draft = new Domain.BookingDraft(
                this.room,
                this.model.user,
                this.day,
                this.range,
                '',
                '',
                new Domain.ParticipantList([this.model.user]),
                new Domain.SeriesRule('none', this.day, 90),
                this.timeline,
                this.model.clock,
                new Domain.NoBooking()
            );
            var result = draft.confirm();
            if (!result.accepted() && result.code() !== 'TITLE_REQUIRED') {
                this.toast = {kind: 'error', text: result.view().message};
                return;
            }
            this.modal = draft;
        },
        submit: function () {
            var Domain = BX.Belveb.Booking.Domain;
            var result = this.modal.confirm();
            if (!result.accepted()) {
                this.toast = {kind: 'error', text: result.view().message};
                return;
            }
            var booking = result.booking();
            this.model.bookings.push(new Domain.Booking(
                this.nextId(),
                booking.room(),
                booking.organizer(),
                booking.date(),
                booking.range(),
                booking.title(),
                booking.description(),
                booking.participants(),
                booking.status(),
                booking.series(),
                booking.sourceId()
            ));
            this.toast = {kind: 'success', text: 'Встреча забронирована'};
            this.modal = null;
        },
        nextId: function () {
            return this.model.bookings.reduce(function (max, item) {
                return Math.max(max, item.id());
            }, 0) + 1;
        },
        cancel: function (id) {
            var booking = this.model.bookings.find(function (item) {
                return item.id() === id;
            });
            if (!booking || !booking.canCancel(this.model.user)) {
                return;
            }
            this.hidden[id] = true;
            this.toast = {kind: 'success', text: 'Встреча отменена'};
        },
        onTitle: function (title) {
            this.modal = this.modal.withTitle(title);
        },
        onDescription: function (description) {
            this.modal = this.modal.withDescription(description);
        },
        onRepeat: function (rule) {
            this.modal = this.modal.withSeries(new BX.Belveb.Booking.Domain.SeriesRule(rule, this.day, 90));
        },
        onAdd: function (id) {
            var person = this.model.people.find(function (item) {
                return item.id() === id;
            });
            this.modal = this.modal.withParticipants(this.modal.draftParticipants.with(person));
        },
        onRemove: function (id) {
            var person = this.model.people.find(function (item) {
                return item.id() === id;
            });
            this.modal = this.modal.withParticipants(this.modal.draftParticipants.without(person));
        },
        onSpan: function (from, to) {
            this.onSelect(from, to);
            this.modal = this.modal.withRange(this.range);
        },
        onDate: function (text) {
            var Domain = BX.Belveb.Booking.Domain;
            var date;
            try {
                date = new Domain.CalendarDate(text);
            } catch (error) {
                return;
            }
            this.day = date;
            this.modal = this.modal.withDate(date, this.model.timelineFor(this.room, date, 0));
        },
        onFrom: function (text) {
            this.onEdge(text, true);
        },
        onTo: function (text) {
            this.onEdge(text, false);
        },
        onEdge: function (text, isStart) {
            var Domain = BX.Belveb.Booking.Domain;
            var next;
            try {
                var minute = new Domain.MinuteOfDay(text);
                next = isStart
                    ? new Domain.TimeRange(minute, this.range.end())
                    : new Domain.TimeRange(this.range.start(), minute);
            } catch (error) {
                return;
            }
            this.range = next;
            this.modal = this.modal.withRange(next);
        },
    },
    template: `
        <div v-if="payload.found" class="flex w-full flex-col">
            <belveb-booking-room-card :room="room.view()" variant="detail" :assets="payload.assets" :available="available" @book="scrollToTime"></belveb-booking-room-card>
            <section ref="time" class="mt-12">
                <h2 class="text-[30px] font-bold leading-[43px] text-foreground">Выберите время</h2>
                <div class="mt-6">
                    <belveb-booking-day-picker :days="days" @change="onDay"></belveb-booking-day-picker>
                </div>
                <belveb-booking-timeline :slots="slots" :now="timeline.view().now" :grid="room.grid().view()" @select="onSelect"></belveb-booking-timeline>
                <p class="mt-3 text-[13px] text-muted">Обновлено в {{ timeline.view().updatedAt }}</p>
                <belveb-booking-time-range
                    :range-label="range.label()"
                    :durations="payload.filterOptions.durations"
                    :active-duration="duration"
                    :disabled="!available"
                    :nearest-found="nearestFound"
                    @duration="onDuration"
                    @nearest="onNearest"
                    @submit="openConfirm"
                ></belveb-booking-time-range>
            </section>
            <belveb-booking-day-table :rows="rows" :date-label="day.text()" @cancel="cancel"></belveb-booking-day-table>
            <belveb-booking-modal
                v-if="modal"
                mode="confirm"
                :draft="modalView"
                :people="payload.people"
                :grid="room.grid().view()"
                @close="modal = null"
                @title="onTitle"
                @description="onDescription"
                @repeat="onRepeat"
                @add="onAdd"
                @remove="onRemove"
                @span="onSpan"
                @date="onDate"
                @from="onFrom"
                @to="onTo"
                @submit="submit"
            ></belveb-booking-modal>
            <belveb-booking-toast v-if="toast" :kind="toast.kind" :text="toast.text"></belveb-booking-toast>
        </div>
    `,
});
