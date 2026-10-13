BX.Vue3.BitrixVue.mutableComponent('belveb-booking-participants', {
    props: {
        people: {type: Array, required: true},
        selected: {type: Array, required: true},
        ratio: {type: String, required: true},
        fits: {type: Boolean, default: true},
    },
    data: function () {
        return {open: false};
    },
    methods: {
        picked: function (id) {
            return this.selected.some(function (person) {
                return person.id === id;
            });
        },
    },
    template: `
        <div>
            <div class="flex items-center justify-between">
                <span class="text-[13px] font-medium text-muted">Участники</span>
                <span class="text-[13px] font-medium" :class="fits ? 'text-muted' : 'text-[#e25b5b]'">{{ ratio }}</span>
            </div>
            <ul class="mt-2 flex flex-col gap-2">
                <li v-for="person in selected" :key="person.id" class="flex items-center justify-between rounded-[12px] bg-canvas px-3 py-2 text-[15px]">
                    <span>{{ person.name }}</span>
                    <button type="button" class="text-muted" @click="$emit('remove', person.id)">×</button>
                </li>
            </ul>
            <button type="button" class="mt-2 text-[15px] font-medium text-primary" @click="open = !open">+ Добавить</button>
            <ul v-if="open" class="mt-2 rounded-[12px] border border-border p-2">
                <li v-for="person in people" :key="person.id">
                    <button type="button" class="w-full px-2 py-1 text-left text-[15px]" :disabled="picked(person.id)" @click="$emit('add', person.id); open = false">{{ person.name }}</button>
                </li>
            </ul>
        </div>
    `,
});

BX.Vue3.BitrixVue.mutableComponent('belveb-booking-repeat', {
    props: {
        rule: {type: String, required: true},
    },
    template: `
        <label class="flex flex-col gap-2 text-[13px] font-medium text-muted">
            Повтор
            <select class="h-12 rounded-[15px] border border-border bg-white px-3 text-[15px] text-foreground" :value="rule" @change="$emit('change', $event.target.value)">
                <option value="none">Без повтора</option>
                <option value="daily">Ежедневно</option>
                <option value="weekly">Еженедельно</option>
                <option value="monthly">Ежемесячно</option>
            </select>
        </label>
    `,
});

BX.Vue3.BitrixVue.mutableComponent('belveb-booking-modal', {
    props: {
        mode: {type: String, required: true},
        draft: {type: Object, required: true},
        rooms: {type: Array, default: function () { return []; }},
        people: {type: Array, default: function () { return []; }},
        slots: {type: Array, default: function () { return []; }},
        grid: {type: Object, required: true},
        notice: {type: String, default: ''},
    },
    computed: {
        badge: function () {
            if (this.mode === 'edit') {
                return 'editing';
            }
            if (this.mode === 'rebook') {
                return 'rebook';
            }
            return 'booked';
        },
        title: function () {
            if (this.mode === 'edit') {
                return 'Редактирование брони';
            }
            if (this.mode === 'rebook') {
                return 'Повторное бронирование';
            }
            return 'Подтверждение брони';
        },
        submitLabel: function () {
            if (this.mode === 'edit') {
                return this.draft.unchanged ? 'Изменений нет' : 'Сохранить';
            }
            return 'Забронировать ' + this.draft.timeLabel;
        },
    },
    template: `
        <div class="fixed inset-0 z-40 flex items-start justify-center overflow-y-auto bg-black/40 px-4 py-10" @click.self="$emit('close')">
            <div class="card w-full max-w-[760px] border-border p-6" role="dialog" aria-modal="true">
                <div class="flex items-start gap-4">
                    <img class="h-[92px] w-[128px] rounded-[12px] object-cover" :src="draft.room.image" alt="">
                    <div class="flex flex-1 flex-col gap-1">
                        <p class="text-[15px] text-muted">{{ draft.room.label }}</p>
                        <h3 class="text-[24px] font-bold text-foreground">{{ draft.room.title }}</h3>
                        <belveb-booking-status-badge :status="badge"></belveb-booking-status-badge>
                    </div>
                    <button type="button" class="text-[24px] text-muted" @click="$emit('close')" aria-label="Закрыть">×</button>
                </div>
                <h2 class="mt-6 text-[30px] font-bold text-foreground">{{ title }}</h2>
                <div class="mt-4 flex flex-col gap-4">
                    <belveb-booking-info-banner v-if="notice" :text="notice"></belveb-booking-info-banner>
                    <label v-if="mode === 'rebook'" class="flex flex-col gap-2 text-[13px] font-medium text-muted">
                        Переговорная
                        <select class="h-12 rounded-[15px] border border-border px-3 text-[15px] text-foreground" :value="draft.room.code" @change="$emit('room', $event.target.value)">
                            <option v-for="room in rooms" :key="room.code" :value="room.code">{{ room.title }}</option>
                        </select>
                    </label>
                    <label class="flex flex-col gap-2 text-[13px] font-medium text-muted">
                        Тема
                        <input class="h-12 rounded-[15px] border border-border px-3 text-[15px] text-foreground" :value="draft.title" @input="$emit('title', $event.target.value)">
                    </label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="flex flex-col gap-2 text-[13px] font-medium text-muted">Дата
                            <input class="h-12 rounded-[15px] border border-border px-3 text-[15px]" :value="draft.date" @change="$emit('date', $event.target.value)">
                        </label>
                        <label class="flex flex-col gap-2 text-[13px] font-medium text-muted">Начало
                            <input class="h-12 rounded-[15px] border border-border px-3 text-[15px]" :value="draft.timeFrom" @change="$emit('from', $event.target.value)">
                        </label>
                        <label class="flex flex-col gap-2 text-[13px] font-medium text-muted">Окончание
                            <input class="h-12 rounded-[15px] border border-border px-3 text-[15px]" :value="draft.timeTo" @change="$emit('to', $event.target.value)">
                        </label>
                    </div>
                    <belveb-booking-timeline v-if="mode !== 'confirm'" :slots="slots" now="" :grid="grid" :legend="mode === 'rebook'" @select="(from, to) => $emit('span', from, to)"></belveb-booking-timeline>
                    <p v-if="mode === 'rebook'" class="text-[13px] text-[#e25b5b]">Пересечение подсвечивается занятыми слотами</p>
                    <belveb-booking-participants :people="people" :selected="draft.participants" :ratio="draft.ratio" :fits="draft.fits" @add="(id) => $emit('add', id)" @remove="(id) => $emit('remove', id)"></belveb-booking-participants>
                    <belveb-booking-repeat :rule="draft.repeat.rule" @change="(rule) => $emit('repeat', rule)"></belveb-booking-repeat>
                    <label class="flex flex-col gap-2 text-[13px] font-medium text-muted">
                        Описание
                        <textarea class="min-h-[96px] rounded-[15px] border border-border px-3 py-2 text-[15px] text-foreground" :value="draft.description" @input="$emit('description', $event.target.value)"></textarea>
                    </label>
                </div>
                <div class="mt-6 flex items-center justify-between gap-4">
                    <p class="text-[15px] text-foreground">{{ draft.room.title }}, {{ draft.date }}, {{ draft.timeLabel }}</p>
                    <button type="button" class="btn btn-primary px-6 disabled:opacity-40" :disabled="!draft.canSubmit || (mode === 'edit' && draft.unchanged)" @click="$emit('submit')">{{ submitLabel }}</button>
                </div>
            </div>
        </div>
    `,
});

BX.Vue3.BitrixVue.mutableComponent('belveb-booking-toast', {
    props: {
        kind: {type: String, required: true},
        text: {type: String, required: true},
    },
    template: `
        <div class="fixed top-6 right-6 z-50 max-w-sm rounded-[15px] px-4 py-3 text-[15px] font-medium text-white shadow" :class="kind === 'error' ? 'bg-[#e25b5b]' : 'bg-success'">{{ text }}</div>
    `,
});
