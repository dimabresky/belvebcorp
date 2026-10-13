BX.Vue3.BitrixVue.mutableComponent('belveb-booking-search-bar', {
    props: {
        filter: {type: Object, required: true},
        options: {type: Object, required: true},
        assets: {type: Object, required: true},
    },
    data: function () {
        return {open: ''};
    },
    template: `
        <div class="card mt-[43px] flex h-20 items-center justify-between border-border px-5 shadow-[0_4px_9px_rgba(154,154,154,0.12)]">
            <div class="flex items-center gap-5">
                <div class="relative flex items-center gap-[25px]">
                    <button type="button" class="flex w-[260px] items-center gap-[10px] rounded-[15px] bg-canvas px-2 py-[11px] text-left" @click="open = open === 'date' ? '' : 'date'">
                        <img :src="assets.calendar" width="25" height="25" alt="">
                        <span class="flex flex-col gap-[5px]">
                            <span class="text-[13px] font-medium leading-[14px] text-muted">Дата</span>
                            <span class="text-[15px] font-medium leading-[15px] text-foreground">{{ filter.date }}</span>
                        </span>
                    </button>
                    <ul v-if="open === 'date'" class="absolute top-[70px] z-10 w-[260px] rounded-[15px] border border-border bg-white p-2 shadow">
                        <li v-for="date in options.dates" :key="date">
                            <button type="button" class="w-full rounded-lg px-3 py-2 text-left text-[15px] hover:bg-canvas" @click="$emit('date', date); open = ''">{{ date }}</button>
                        </li>
                    </ul>
                    <span class="h-[34px] w-0.5 rounded-[2px] bg-[#d2d2d2]" aria-hidden="true"></span>
                </div>
                <div class="relative">
                    <button type="button" class="flex w-[260px] items-center justify-between rounded-[15px] bg-canvas px-2 py-[11px] text-left" @click="open = open === 'time' ? '' : 'time'">
                        <span class="inline-flex items-center gap-[10px]">
                            <img :src="assets.time" width="22" height="22" alt="">
                            <span class="flex flex-col gap-[5px]">
                                <span class="text-[13px] font-medium leading-[14px] text-muted">Время встречи</span>
                                <span class="text-[15px] font-medium leading-[15px] text-foreground">{{ filter.timeLabel }}</span>
                            </span>
                        </span>
                        <img :src="assets.chevronSm" width="20" height="20" alt="">
                    </button>
                    <ul v-if="open === 'time'" class="absolute top-[70px] z-10 w-[260px] rounded-[15px] border border-border bg-white p-2 shadow">
                        <li v-for="duration in options.durations" :key="duration">
                            <button type="button" class="w-full rounded-lg px-3 py-2 text-left text-[15px] hover:bg-canvas" @click="$emit('duration', duration); open = ''">{{ duration }} мин</button>
                        </li>
                    </ul>
                </div>
                <span class="h-[34px] w-0.5 rounded-[2px] bg-[#d2d2d2]" aria-hidden="true"></span>
                <div class="relative">
                    <button type="button" class="flex w-[202px] items-center justify-between rounded-[15px] bg-canvas px-2 py-[11px] text-left" @click="open = open === 'people' ? '' : 'people'">
                        <span class="inline-flex items-center gap-[10px]">
                            <img :src="assets.people" width="24" height="23" alt="">
                            <span class="flex flex-col gap-[5px]">
                                <span class="text-[13px] font-medium leading-[14px] text-muted">Участники</span>
                                <span class="text-[15px] font-medium leading-[15px] text-foreground">{{ filter.participants }}</span>
                            </span>
                        </span>
                        <img :src="assets.chevronSm" width="20" height="20" alt="">
                    </button>
                    <ul v-if="open === 'people'" class="absolute top-[70px] z-10 w-[202px] rounded-[15px] border border-border bg-white p-2 shadow">
                        <li v-for="count in options.participants" :key="count">
                            <button type="button" class="w-full rounded-lg px-3 py-2 text-left text-[15px] hover:bg-canvas" @click="$emit('participants', count); open = ''">{{ count }}</button>
                        </li>
                    </ul>
                </div>
            </div>
            <button type="button" class="flex h-14 w-[85px] cursor-pointer items-center justify-center rounded-lg border-0 bg-[#4470e2]" aria-label="Найти переговорные" @click="$emit('search')">
                <img :src="assets.search" width="25" height="25" alt="">
            </button>
        </div>
    `,
});

BX.Vue3.BitrixVue.mutableComponent('belveb-booking-filter-chips', {
    props: {
        filter: {type: Object, required: true},
        options: {type: Object, required: true},
        assets: {type: Object, required: true},
    },
    data: function () {
        return {open: ''};
    },
    computed: {
        floorLabel: function () {
            return this.filter.floor ? this.filter.floor + ' этаж' : 'Этаж';
        },
        typeLabel: function () {
            var selected = this.options.types.find(function (type) {
                return type.xmlId === this.filter.type;
            }, this);
            return selected ? selected.name : 'Тип переговорной';
        },
    },
    template: `
        <div class="mt-[27px] flex items-center justify-between gap-5">
            <div class="flex items-center gap-5">
                <div class="relative">
                    <button type="button" class="flex w-[155px] cursor-pointer items-center justify-between rounded-[20px] border border-border bg-white px-[10px] py-4 text-[15px] font-medium leading-[22px] text-[#211c1c]" @click="open = open === 'floor' ? '' : 'floor'">
                        <span>{{ floorLabel }}</span>
                        <img :src="assets.chevron" width="24" height="24" alt="">
                    </button>
                    <ul v-if="open === 'floor'" class="absolute top-[60px] z-10 w-[155px] rounded-[15px] border border-border bg-white p-2 shadow">
                        <li><button type="button" class="w-full px-3 py-2 text-left text-[15px]" @click="$emit('floor', ''); open = ''">Все</button></li>
                        <li v-for="floor in options.floors" :key="floor">
                            <button type="button" class="w-full px-3 py-2 text-left text-[15px]" @click="$emit('floor', String(floor)); open = ''">{{ floor }} этаж</button>
                        </li>
                    </ul>
                </div>
                <div class="relative">
                    <button type="button" class="flex cursor-pointer items-center gap-[65px] rounded-[20px] border border-border bg-white px-[10px] py-4 text-[15px] font-medium leading-[22px] text-[#211c1c]" @click="open = open === 'type' ? '' : 'type'">
                        <span>{{ typeLabel }}</span>
                        <img :src="assets.chevron" width="24" height="24" alt="">
                    </button>
                    <ul v-if="open === 'type'" class="absolute top-[60px] z-10 min-w-full rounded-[15px] border border-border bg-white p-2 shadow">
                        <li><button type="button" class="w-full px-3 py-2 text-left text-[15px]" @click="$emit('type', ''); open = ''">Все</button></li>
                        <li v-for="type in options.types" :key="type.xmlId">
                            <button type="button" class="w-full px-3 py-2 text-left text-[15px]" @click="$emit('type', type.xmlId); open = ''">{{ type.name }}</button>
                        </li>
                    </ul>
                </div>
            </div>
            <button type="button" class="cursor-pointer border-0 bg-transparent text-[15px] font-medium leading-[22px] text-primary" @click="$emit('reset')">Сбросить параметры</button>
        </div>
    `,
});
