BX.Vue3.BitrixVue.mutableComponent('belveb-booking-day-picker', {
    props: {
        days: {type: Array, required: true},
    },
    template: `
        <div class="flex items-center gap-3 overflow-x-auto">
            <button
                v-for="day in days"
                :key="day.date"
                type="button"
                class="shrink-0 rounded-[15px] px-4 py-2 text-[15px] font-medium"
                :class="day.selected ? 'bg-primary text-white' : 'bg-canvas text-foreground'"
                @click="$emit('change', day.date)"
            >{{ day.label }}</button>
        </div>
    `,
});

BX.Vue3.BitrixVue.mutableComponent('belveb-booking-timeline', {
    props: {
        slots: {type: Array, required: true},
        now: {type: String, default: ''},
        grid: {type: Object, required: true},
        legend: {type: Boolean, default: true},
    },
    data: function () {
        return {dragFrom: ''};
    },
    computed: {
        nowLeft: function () {
            if (!this.now) {
                return '';
            }
            var start = this.minutes(this.grid.workFrom);
            var end = this.minutes(this.grid.workTo);
            var current = this.minutes(this.now);
            var ratio = (current - start) / (end - start);
            return Math.max(0, Math.min(1, ratio)) * 100 + '%';
        },
    },
    methods: {
        minutes: function (hhmm) {
            var parts = hhmm.split(':');
            return Number(parts[0]) * 60 + Number(parts[1]);
        },
        tone: function (slot) {
            var tones = {
                free: 'bg-canvas',
                busy: 'bg-[#e7e7f0]',
                mine: 'bg-tint',
                selected: 'bg-primary/30',
                now: 'bg-canvas',
            };
            return tones[slot.state] || 'bg-canvas';
        },
        startDrag: function (slot) {
            this.dragFrom = slot.from;
            this.$emit('select', slot.from, slot.to);
        },
        moveDrag: function (slot) {
            if (!this.dragFrom) {
                return;
            }
            var from = this.dragFrom < slot.from ? this.dragFrom : slot.from;
            var to = this.dragFrom < slot.from ? slot.to : this.slotEnd(this.dragFrom);
            this.$emit('select', from, to);
        },
        slotEnd: function (from) {
            var match = this.slots.find(function (slot) {
                return slot.from === from;
            });
            return match ? match.to : from;
        },
        stopDrag: function () {
            this.dragFrom = '';
        },
    },
    template: `
        <div>
            <div class="relative mt-4 overflow-x-auto" @mouseup="stopDrag" @mouseleave="stopDrag">
                <div class="flex h-[72px] min-w-[960px]">
                    <button
                        v-for="slot in slots"
                        :key="slot.from"
                        type="button"
                        class="relative h-full min-w-[12px] flex-1 border-l border-white"
                        :class="tone(slot)"
                        :title="slot.title"
                        @mousedown.prevent="startDrag(slot)"
                        @mouseenter="moveDrag(slot)"
                    >
                        <span v-if="slot.state === 'busy' || slot.state === 'mine'" class="pointer-events-none absolute inset-x-0 top-1 truncate px-1 text-[10px] text-heading">{{ slot.title }}</span>
                    </button>
                </div>
                <span v-if="nowLeft" class="pointer-events-none absolute top-0 bottom-0 w-0.5 bg-primary" :style="{left: nowLeft}"></span>
            </div>
            <div class="mt-2 flex min-w-[960px] justify-between text-[12px] text-muted">
                <span>{{ grid.workFrom }}</span>
                <span>{{ grid.workTo }}</span>
            </div>
            <ul v-if="legend" class="mt-4 flex flex-wrap gap-4 text-[13px] text-foreground">
                <li class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded bg-canvas border border-border"></span>Свободно</li>
                <li class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded bg-[#e7e7f0]"></span>Занято</li>
                <li class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded bg-tint"></span>Ваша бронь</li>
                <li class="inline-flex items-center gap-2"><span class="h-3 w-3 rounded bg-primary/30"></span>Выбрано</li>
                <li class="inline-flex items-center gap-2"><span class="h-3 w-0.5 bg-primary"></span>Сейчас</li>
            </ul>
        </div>
    `,
});

BX.Vue3.BitrixVue.mutableComponent('belveb-booking-time-range', {
    props: {
        rangeLabel: {type: String, required: true},
        durations: {type: Array, required: true},
        activeDuration: {type: Number, required: true},
        disabled: {type: Boolean, default: false},
        nearestFound: {type: Boolean, default: true},
    },
    template: `
        <div class="mt-6 flex flex-col gap-4">
            <div class="flex flex-wrap items-center gap-3">
                <button
                    v-for="duration in durations"
                    :key="duration"
                    type="button"
                    class="rounded-[15px] border px-4 py-2 text-[15px] font-medium"
                    :class="duration === activeDuration ? 'border-primary bg-primary text-white' : 'border-border bg-white text-foreground'"
                    @click="$emit('duration', duration)"
                >{{ duration === 30 ? '30 мин' : duration === 60 ? '1 ч' : duration === 90 ? '1,5 ч' : '2 ч' }}</button>
                <button type="button" class="text-[15px] font-medium text-primary disabled:text-muted" :disabled="!nearestFound" @click="$emit('nearest')">Ближайшее свободное</button>
            </div>
            <button type="button" class="btn btn-primary w-fit px-6 disabled:opacity-40" :disabled="disabled" @click="$emit('submit')">Забронировать {{ rangeLabel }}</button>
        </div>
    `,
});

BX.Vue3.BitrixVue.mutableComponent('belveb-booking-day-table', {
    props: {
        rows: {type: Array, required: true},
        dateLabel: {type: String, required: true},
    },
    template: `
        <section class="mt-12">
            <h2 class="text-[30px] font-bold leading-[43px] text-foreground">Бронирования на {{ dateLabel }}</h2>
            <table class="mt-6 w-full text-left text-[15px]">
                <thead class="text-muted">
                    <tr>
                        <th class="py-3 font-medium">Время</th>
                        <th class="py-3 font-medium">Тема</th>
                        <th class="py-3 font-medium">Организатор</th>
                        <th class="py-3 font-medium"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="row in rows" :key="row.id" class="border-t border-border">
                        <td class="py-4">{{ row.timeLabel }}</td>
                        <td class="py-4">{{ row.title }}</td>
                        <td class="py-4">{{ row.organizer.name }}</td>
                        <td class="py-4 text-right">
                            <button v-if="row.canCancel" type="button" class="text-primary" @click="$emit('cancel', row.id)">Отменить</button>
                        </td>
                    </tr>
                    <tr v-if="rows.length === 0">
                        <td colspan="4" class="py-4 text-muted">На этот день броней нет</td>
                    </tr>
                </tbody>
            </table>
        </section>
    `,
});
