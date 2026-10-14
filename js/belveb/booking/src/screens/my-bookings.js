BX.Vue3.BitrixVue.mutableComponent('belveb-booking-my-bookings', {
    props: {
        payload: {type: Object, required: true},
    },
    data: function () {
        var model = new BX.Belveb.Booking.Domain.ViewMapper().capture(this.payload);
        return {
            model: model,
            filter: model.filter,
            page: payloadPage(this.payload),
            openId: 0,
            hidden: {},
            modal: null,
            toast: null,
        };
    },
    computed: {
        filterView: function () {
            return this.filter.view();
        },
        active: function () {
            var self = this;
            return this.model.bookings.filter(function (booking) {
                return !self.hidden[booking.id()]
                    && booking.status() === 'booked'
                    && !booking.isPast(self.model.clock)
                    && (booking.organizer().same(self.model.user) || booking.participants().has(self.model.user));
            }).map(function (booking) {
                return booking.view(self.model.user, self.model.clock);
            });
        },
        previous: function () {
            var self = this;
            return this.model.bookings.filter(function (booking) {
                return booking.status() === 'completed' || booking.status() === 'cancelled' || self.hidden[booking.id()];
            }).map(function (booking) {
                var row = booking.view(self.model.user, self.model.clock);
                if (self.hidden[booking.id()]) {
                    row.status = 'cancelled';
                    row.canCancel = false;
                }
                return row;
            });
        },
        pageRows: function () {
            var size = 3;
            var start = (this.page - 1) * size;
            return this.previous.slice(start, start + size);
        },
        pages: function () {
            return Math.max(1, Math.ceil(this.previous.length / 3));
        },
        modalView: function () {
            if (!this.modal) {
                return null;
            }
            return {
                mode: this.modal.mode,
                draft: this.modal.draft.view(),
                slots: this.modal.draft.range ? this.painted() : [],
                notice: this.modal.notice,
            };
        },
    },
    methods: {
        painted: function () {
            var draft = this.modal.draft;
            return draft.room ? this.model.timelineFor(draft.room(), draft.draftDate, draft.view().originId).painted(draft.range()).map(function (slot) {
                return slot.view();
            }) : [];
        },
        find: function (id) {
            return this.model.bookings.find(function (booking) {
                return booking.id() === id;
            });
        },
        replaceFilter: function (next) {
            this.filter = next;
        },
        details: function (id) {
            var booking = this.find(id);
            if (!booking || !booking.canCancel(this.model.user)) {
                this.toast = {kind: 'error', text: 'Изменить эту встречу нельзя'};
                return;
            }
            var timeline = this.model.timelineFor(booking.room(), booking.date(), booking.id());
            this.modal = {
                mode: 'edit',
                notice: '',
                draft: this.draftFrom(booking, timeline, booking),
            };
        },
        rebook: function (id) {
            var booking = this.find(id);
            var day = this.model.clock.date().plusDays(1);
            var timeline = this.model.timelineFor(booking.room(), day, 0);
            this.modal = {
                mode: 'rebook',
                notice: 'Данные перенесены из встречи ' + booking.date().text(),
                draft: new BX.Belveb.Booking.Domain.BookingDraft(
                    booking.room(),
                    this.model.user,
                    day,
                    booking.range(),
                    booking.title(),
                    booking.description(),
                    booking.participants(),
                    booking.series(),
                    timeline,
                    this.model.clock,
                    new BX.Belveb.Booking.Domain.NoBooking()
                ),
            };
        },
        draftFrom: function (booking, timeline, origin) {
            return new BX.Belveb.Booking.Domain.BookingDraft(
                booking.room(),
                booking.organizer(),
                booking.date(),
                booking.range(),
                booking.title(),
                booking.description(),
                booking.participants(),
                booking.series(),
                timeline,
                this.model.clock,
                origin
            );
        },
        cancel: function (id) {
            var booking = this.find(id);
            if (!booking.canCancel(this.model.user) || booking.isPast(this.model.clock)) {
                this.toast = {kind: 'error', text: 'Отменить эту встречу нельзя'};
                return;
            }
            if (!BX.Belveb.Booking.isStored(this.payload, id)) {
                this.hidden[id] = true;
                this.toast = {kind: 'success', text: 'Встреча отменена'};
                return;
            }
            var self = this;
            BX.Belveb.Booking.action('booking.cancel', {id: id}).then(function () {
                self.hidden[id] = true;
                self.toast = {kind: 'success', text: 'Встреча отменена'};
                BX.Belveb.Booking.action('booking.my');
            }, function (response) {
                self.toast = {kind: 'error', text: BX.Belveb.Booking.actionError(response)};
            });
        },
        patch: function (next) {
            this.modal = {mode: this.modal.mode, notice: this.modal.notice, draft: next};
        },
        onTitle: function (title) {
            this.patch(this.modal.draft.withTitle(title));
        },
        onDescription: function (description) {
            this.patch(this.modal.draft.withDescription(description));
        },
        onSpan: function (from, to) {
            var Domain = BX.Belveb.Booking.Domain;
            var start = from < to ? from : to;
            var end = from < to ? to : from;
            if (start === end) {
                return;
            }
            this.patch(this.modal.draft.withRange(new Domain.TimeRange(new Domain.MinuteOfDay(start), new Domain.MinuteOfDay(end))));
        },
        onDate: function (text) {
            var Domain = BX.Belveb.Booking.Domain;
            var date;
            try {
                date = new Domain.CalendarDate(text);
            } catch (error) {
                return;
            }
            var originId = this.modal.draft.view().originId;
            var timeline = this.model.timelineFor(this.modal.draft.room(), date, originId);
            this.patch(this.modal.draft.withDate(date, timeline));
        },
        onFrom: function (text) {
            this.onEdge(text, true);
        },
        onTo: function (text) {
            this.onEdge(text, false);
        },
        onEdge: function (text, isStart) {
            var Domain = BX.Belveb.Booking.Domain;
            var range = this.modal.draft.range();
            var next;
            try {
                var minute = new Domain.MinuteOfDay(text);
                next = isStart
                    ? new Domain.TimeRange(minute, range.end())
                    : new Domain.TimeRange(range.start(), minute);
            } catch (error) {
                return;
            }
            this.patch(this.modal.draft.withRange(next));
        },
        onRepeat: function (rule) {
            var Domain = BX.Belveb.Booking.Domain;
            this.patch(this.modal.draft.withSeries(new Domain.SeriesRule(rule, this.modal.draft.draftDate, 90)));
        },
        onAdd: function (id) {
            var person = this.model.people.find(function (item) {
                return item.id() === id;
            });
            this.patch(this.modal.draft.withParticipants(this.modal.draft.draftParticipants.with(person)));
        },
        onRemove: function (id) {
            var person = this.model.people.find(function (item) {
                return item.id() === id;
            });
            this.patch(this.modal.draft.withParticipants(this.modal.draft.draftParticipants.without(person)));
        },
        onRoom: function (code) {
            var room = this.model.roomByCode(code);
            var timeline = this.model.timelineFor(room, this.modal.draft.draftDate, 0);
            this.patch(this.modal.draft.withRoom(room, timeline));
        },
        submit: function () {
            var result = this.modal.draft.confirm();
            if (!result.accepted()) {
                this.toast = {kind: 'error', text: result.view().message};
                return;
            }
            var booking = result.booking();
            var fields = BX.Belveb.Booking.bookingFields(booking);
            var editing = this.modal.mode === 'edit';
            var originId = editing ? this.modal.draft.view().originId : 0;
            if (editing && !BX.Belveb.Booking.isStored(this.payload, originId)) {
                this.remember(booking);
                this.toast = {kind: 'success', text: result.view().message};
                this.modal = null;
                return;
            }
            if (editing) {
                fields.originId = originId;
            }
            var action = editing ? 'booking.update' : 'booking.add';
            var self = this;
            BX.Belveb.Booking.action(action, fields).then(function (response) {
                self.remember(booking);
                self.toast = {kind: 'success', text: (response.data && response.data.message) || result.view().message};
                self.modal = null;
                BX.Belveb.Booking.action('booking.my');
            }, function (response) {
                self.toast = {kind: 'error', text: BX.Belveb.Booking.actionError(response)};
            });
        },
        remember: function (booking) {
            var Domain = BX.Belveb.Booking.Domain;
            var editing = this.modal.mode === 'edit';
            var stored = editing ? booking : new Domain.Booking(
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
            );
            var index = this.model.bookings.findIndex(function (item) {
                return item.id() === stored.id();
            });
            if (index >= 0) {
                this.model.bookings.splice(index, 1, stored);
                return;
            }
            this.model.bookings.push(stored);
        },
        nextId: function () {
            return this.model.bookings.reduce(function (max, item) {
                return Math.max(max, item.id());
            }, 0) + 1;
        },
        names: function (row) {
            return row.participants.map(function (person) {
                return person.name;
            }).join(', ');
        },
        download: function () {
            var lines = ['Комната,Дата,Время,Тема,Организатор,Участники,Статус'];
            this.previous.concat(this.active).forEach(function (row) {
                lines.push([
                    row.room.title,
                    row.date,
                    row.timeLabel,
                    row.title,
                    row.organizer.name,
                    row.participants.map(function (person) { return person.name; }).join('; '),
                    row.status,
                ].join(','));
            });
            var blob = new Blob([lines.join('\n')], {type: 'text/csv;charset=utf-8'});
            var link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'bookings.csv';
            link.click();
        },
    },
    template: `
        <div class="flex w-full flex-col">
            <belveb-booking-tabs :page="payload.page" :urls="payload.urls"></belveb-booking-tabs>
            <belveb-booking-search-bar :filter="filterView" :options="payload.filterOptions" :assets="payload.assets" @date="(date) => replaceFilter(filter.withDate(new BX.Belveb.Booking.Domain.CalendarDate(date)))" @duration="(minutes) => replaceFilter(filter.withRange(filter.range().withDuration(minutes)))" @participants="(count) => replaceFilter(filter.withParticipants(count))"></belveb-booking-search-bar>
            <belveb-booking-filter-chips :filter="filterView" :options="payload.filterOptions" :assets="payload.assets" @floor="(floor) => replaceFilter(filter.withFloor(floor))" @type="(type) => replaceFilter(filter.withType(type))" @reset="filter = model.filter"></belveb-booking-filter-chips>
            <ul class="mt-12 flex flex-col gap-[25px]">
                <li v-for="row in active" :key="row.id">
                    <belveb-booking-room-card :room="row.room" :booking="row" variant="booked" :assets="payload.assets" @details="details(row.id)" @cancel="cancel(row.id)"></belveb-booking-room-card>
                </li>
            </ul>
            <div class="mt-12 flex items-center justify-between">
                <h2 class="text-[30px] font-bold leading-[43px] text-foreground">Предыдущие бронирования</h2>
                <button type="button" class="btn btn-ghost" @click="download">Скачать отчет</button>
            </div>
            <ul class="mt-6 flex flex-col gap-3">
                <li v-for="row in pageRows" :key="row.id" class="card border-border px-5 py-4">
                    <button type="button" class="flex w-full items-center justify-between text-left" @click="openId = openId === row.id ? 0 : row.id">
                        <span class="text-[18px] font-bold text-foreground">{{ row.title }}</span>
                        <belveb-booking-status-badge :status="row.status"></belveb-booking-status-badge>
                    </button>
                    <div v-if="openId === row.id" class="mt-4 flex flex-col gap-3 text-[15px] text-foreground">
                        <p>{{ row.dateLabel }}, {{ row.timeLabel }}</p>
                        <p v-if="row.description">{{ row.description }}</p>
                        <p>Повтор: {{ row.repeatLabel }}</p>
                        <p>Участники: {{ names(row) }}</p>
                        <button type="button" class="btn btn-primary w-fit" @click="rebook(row.id)">Забронировать снова</button>
                    </div>
                </li>
            </ul>
            <belveb-booking-pagination :page="page" :pages="pages" @change="(next) => page = next"></belveb-booking-pagination>
            <belveb-booking-modal
                v-if="modalView"
                :mode="modalView.mode"
                :draft="modalView.draft"
                :rooms="payload.rooms"
                :people="payload.people"
                :slots="modalView.slots"
                :grid="payload.grid"
                :notice="modalView.notice"
                @close="modal = null"
                @title="onTitle"
                @description="onDescription"
                @span="onSpan"
                @date="onDate"
                @from="onFrom"
                @to="onTo"
                @repeat="onRepeat"
                @add="onAdd"
                @remove="onRemove"
                @room="onRoom"
                @submit="submit"
            ></belveb-booking-modal>
            <belveb-booking-toast v-if="toast" :kind="toast.kind" :text="toast.text"></belveb-booking-toast>
        </div>
    `,
});

function payloadPage(payload) {
    return payload.nav && payload.nav.page ? payload.nav.page : 1;
}
