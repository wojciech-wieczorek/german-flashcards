<script setup>
import { Link } from '@inertiajs/vue3';
import clsx from 'clsx';

const props = defineProps(['number', 'size']);

const boxColorClass = {
    1: 'bg-lime-200 hover:border-lime-400 text-lime-950',
    2: 'bg-yellow-200 hover:border-yellow-400 text-yellow-950',
    3: 'bg-amber-200 hover:border-amber-400 text-amber-950',
    4: 'bg-orange-200 hover:border-orange-400 text-orange-950',
    5: 'bg-red-200 hover:border-red-400 text-red-950',
    archive: 'bg-gray-950 text-gray-50 hover:border-gray-400',
};
const boxClass = clsx('rounded-xl border-2 border-transparent bg-amber-200 p-5 text-2xl select-none', boxColorClass[props.number]);
const sizeClass = clsx('text-center text-base');
</script>

<template>
    <Link :class="boxClass" :href="`/boxes/${number}`">
        <slot></slot>
        <p :class="sizeClass">{{ `[${size || 0}]` }}</p>
    </Link>
</template>
