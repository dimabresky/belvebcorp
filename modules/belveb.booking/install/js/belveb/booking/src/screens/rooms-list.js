BX.Vue3.BitrixVue.mutableComponent('belveb-booking-rooms-list', {
    props: {
        payload: {type: Object, required: true},
    },
    data: function () {
        var model = new BX.Belveb.Booking.Domain.ViewMapper().capture(this.payload);
        return {
            model: model,
            filter: model.filter,
            applied: false,
        };
    },
    computed: {
        filterView: function () {
            return this.filter.view();
        },
        rooms: function () {
            if (!this.applied) {
                return this.model.rooms.map(function (room) {
                    return room.view();
                });
            }
            var filter = this.filter;
            var model = this.model;
            return this.model.rooms.filter(function (room) {
                return filter.matches(room, model.timelineFor(room, filter.date()));
            }).map(function (room) {
                return room.view();
            });
        },
    },
    methods: {
        replace: function (next) {
            this.filter = next;
        },
        onDate: function (date) {
            this.replace(this.filter.withDate(new BX.Belveb.Booking.Domain.CalendarDate(date)));
        },
        onDuration: function (minutes) {
            this.replace(this.filter.withRange(this.filter.range().withDuration(minutes)));
        },
        onParticipants: function (count) {
            this.replace(this.filter.withParticipants(count));
        },
        onFloor: function (floor) {
            this.replace(this.filter.withFloor(floor));
            this.applied = true;
        },
        onType: function (type) {
            this.replace(this.filter.withType(type));
            this.applied = true;
        },
        onSearch: function () {
            this.applied = true;
        },
        onReset: function () {
            this.filter = this.model.filter;
            this.applied = false;
        },
    },
    template: `
        <div class="flex w-full flex-col">
            <belveb-booking-tabs :page="payload.page" :urls="payload.urls"></belveb-booking-tabs>
            <belveb-booking-search-bar
                :filter="filterView"
                :options="payload.filterOptions"
                :assets="payload.assets"
                @date="onDate"
                @duration="onDuration"
                @participants="onParticipants"
                @search="onSearch"
            ></belveb-booking-search-bar>
            <belveb-booking-filter-chips
                :filter="filterView"
                :options="payload.filterOptions"
                :assets="payload.assets"
                @floor="onFloor"
                @type="onType"
                @reset="onReset"
            ></belveb-booking-filter-chips>
            <h2 class="mt-12 text-[30px] font-bold leading-[43px] text-foreground">Доступные переговорные</h2>
            <p v-if="rooms.length === 0" class="mt-8 text-[15px] text-muted">Нет переговорных на выбранные параметры</p>
            <ul class="mt-[31px] flex flex-col gap-[25px]">
                <li v-for="room in rooms" :key="room.code">
                    <belveb-booking-room-card :room="room" variant="list" :assets="payload.assets"></belveb-booking-room-card>
                </li>
            </ul>
        </div>
    `,
});
