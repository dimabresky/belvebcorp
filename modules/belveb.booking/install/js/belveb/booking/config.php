<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

return [
    'js' => [
        './src/domain/values.js',
        './src/domain/people.js',
        './src/domain/schedule.js',
        './src/domain/view-mapper.js',
        './src/components/bits.js',
        './src/components/room-card.js',
        './src/components/filters.js',
        './src/components/timeline.js',
        './src/components/modal.js',
        './src/screens/rooms-list.js',
        './src/screens/my-bookings.js',
        './src/screens/room-detail.js',
        './src/app.js',
    ],
    'rel' => [
        'ui.vue3',
        'main.core',
    ],
];
