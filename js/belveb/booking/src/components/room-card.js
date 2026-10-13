BX.Vue3.BitrixVue.mutableComponent('belveb-booking-room-card', {
    props: {
        room: {type: Object, required: true},
        variant: {type: String, default: 'list'},
        booking: {type: Object, default: null},
        assets: {type: Object, required: true},
        available: {type: Boolean, default: true},
    },
    computed: {
        badge: function () {
            if (this.variant === 'booked') {
                return 'booked';
            }
            if (this.variant === 'detail') {
                return this.available ? 'available' : 'busy';
            }
            return '';
        },
    },
    template: `
        <article class="card flex items-center gap-[45px] border-2 border-border py-[26px] pr-[21px] pl-[31px]">
            <div class="relative h-[290px] w-[403px] shrink-0">
                <img class="h-[290px] w-[403px] rounded-[16px] object-cover" :src="room.image" width="403" height="290" :alt="room.title">
                <span v-if="badge" class="absolute top-4 left-4">
                    <belveb-booking-status-badge :status="badge"></belveb-booking-status-badge>
                </span>
            </div>
            <div class="flex min-w-0 flex-1 flex-col gap-6">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex flex-col gap-px">
                        <p class="text-[25px] font-medium leading-[43px] text-muted">{{ room.label }}</p>
                        <h3 class="text-[30px] font-bold leading-[43px] text-foreground">{{ room.title }}</h3>
                    </div>
                </div>
                <ul v-if="variant !== 'booked'" class="flex max-w-[555px] flex-wrap gap-x-[10px] gap-y-[15px]">
                    <belveb-booking-amenity-tag v-for="amenity in room.amenities" :key="amenity.name" :amenity="amenity"></belveb-booking-amenity-tag>
                </ul>
                <div v-if="variant === 'booked' && booking" class="flex flex-wrap gap-6">
                    <belveb-booking-meta-item :icon="assets.people" :text="booking.participantsLabel" width="24" height="22"></belveb-booking-meta-item>
                    <belveb-booking-meta-item :icon="assets.time" :text="booking.timeLabel" width="22" height="22"></belveb-booking-meta-item>
                    <belveb-booking-meta-item :icon="assets.calendar" :text="booking.dateLabel" width="22" height="22"></belveb-booking-meta-item>
                    <belveb-booking-meta-item :icon="assets.person" :text="booking.organizer.name" width="20" height="22"></belveb-booking-meta-item>
                </div>
                <div class="flex flex-col gap-6">
                    <span class="h-px w-full bg-border"></span>
                    <div class="flex items-center justify-between gap-4">
                        <belveb-booking-meta-item v-if="variant !== 'booked'" :icon="assets.person" :text="room.capacityLabel" width="20" height="22"></belveb-booking-meta-item>
                        <belveb-booking-meta-item v-if="variant !== 'booked'" :icon="assets.building" :text="room.floorLabel" width="25" height="25"></belveb-booking-meta-item>
                        <a v-if="variant === 'list'" class="btn btn-primary w-[212px]" :href="room.detailUrl">Забронировать</a>
                        <button v-if="variant === 'detail'" type="button" class="btn btn-primary w-[212px]" @click="$emit('book')">Забронировать</button>
                        <div v-if="variant === 'booked'" class="ml-auto flex gap-3">
                            <button type="button" class="btn btn-ghost" @click="$emit('details')">Смотреть детали</button>
                            <button v-if="booking && booking.canCancel" type="button" class="btn btn-ghost" @click="$emit('cancel')">Отменить встречу</button>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    `,
});
