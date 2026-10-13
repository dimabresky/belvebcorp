BX.Vue3.BitrixVue.mutableComponent('belveb-booking-status-badge', {
    props: {
        status: {type: String, required: true},
    },
    computed: {
        label: function () {
            var labels = {
                booked: 'Забронировано',
                available: 'Доступно',
                busy: 'Занято',
                completed: 'Завершена',
                cancelled: 'Отменена',
                editing: 'Редактирование брони',
                rebook: 'Повторное бронирование',
            };
            return labels[this.status] || this.status;
        },
        tone: function () {
            var tones = {
                booked: 'bg-primary text-white',
                available: 'bg-success text-white',
                busy: 'bg-[#e25b5b] text-white',
                completed: 'bg-canvas text-muted',
                cancelled: 'bg-[#fde8e8] text-[#e25b5b]',
                editing: 'bg-tint text-heading',
                rebook: 'bg-tint text-heading',
            };
            return tones[this.status] || 'bg-canvas text-foreground';
        },
    },
    template: `
        <span class="inline-flex h-[28px] items-center rounded-[15px] px-3 text-[13px] font-medium leading-[15px]" :class="tone">{{ label }}</span>
    `,
});

BX.Vue3.BitrixVue.mutableComponent('belveb-booking-amenity-tag', {
    props: {
        amenity: {type: Object, required: true},
    },
    template: `
        <li class="inline-flex h-[35px] items-center gap-[10px] rounded-[15px] bg-tint px-[22px] text-[15px] font-medium leading-[22px] text-heading">
            <img :src="amenity.icon" :width="amenity.width" :height="amenity.height" alt="">
            <span>{{ amenity.name }}</span>
        </li>
    `,
});

BX.Vue3.BitrixVue.mutableComponent('belveb-booking-meta-item', {
    props: {
        icon: {type: String, required: true},
        text: {type: String, required: true},
        width: {type: String, default: '20'},
        height: {type: String, default: '20'},
    },
    template: `
        <span class="inline-flex items-center gap-[7px] text-[15px] font-medium leading-[15px] text-heading">
            <img :src="icon" :width="width" :height="height" alt="">
            {{ text }}
        </span>
    `,
});

BX.Vue3.BitrixVue.mutableComponent('belveb-booking-tabs', {
    props: {
        page: {type: String, required: true},
        urls: {type: Object, required: true},
    },
    template: `
        <div class="inline-flex items-end gap-[46px] border-b border-border" role="tablist">
            <a
                class="-mb-px min-w-[302px] border-b-[3px] pb-0 text-center text-[30px] font-bold leading-[43px]"
                :class="page === 'rooms' ? 'border-primary text-foreground' : 'border-transparent pb-[3px] text-muted'"
                :href="urls.rooms"
                role="tab"
                :aria-selected="page === 'rooms' ? 'true' : 'false'"
            >Переговорные</a>
            <a
                class="text-[30px] font-bold leading-[43px]"
                :class="page === 'my' ? '-mb-px border-b-[3px] border-primary text-foreground' : 'pb-[3px] text-muted'"
                :href="urls.my"
                role="tab"
                :aria-selected="page === 'my' ? 'true' : 'false'"
            >Мои бронирования</a>
        </div>
    `,
});

BX.Vue3.BitrixVue.mutableComponent('belveb-booking-info-banner', {
    props: {
        text: {type: String, required: true},
    },
    template: `
        <p class="rounded-[15px] bg-tint px-4 py-3 text-[15px] font-medium leading-[22px] text-heading">{{ text }}</p>
    `,
});

BX.Vue3.BitrixVue.mutableComponent('belveb-booking-pagination', {
    props: {
        page: {type: Number, required: true},
        pages: {type: Number, required: true},
    },
    computed: {
        items: function () {
            var rows = [];
            var number;
            for (number = 1; number <= this.pages; number++) {
                rows.push(number);
            }
            return rows;
        },
    },
    template: `
        <nav class="mt-6 flex items-center justify-center gap-2" aria-label="Страницы">
            <button type="button" class="btn h-10 w-10 border border-border text-foreground" :disabled="page <= 1" @click="$emit('change', page - 1)">‹</button>
            <button
                v-for="item in items"
                :key="item"
                type="button"
                class="btn h-10 w-10 border text-[15px] font-medium"
                :class="item === page ? 'border-primary bg-primary text-white' : 'border-border text-foreground'"
                @click="$emit('change', item)"
            >{{ item }}</button>
            <button type="button" class="btn h-10 w-10 border border-border text-foreground" :disabled="page >= pages" @click="$emit('change', page + 1)">›</button>
        </nav>
    `,
});
