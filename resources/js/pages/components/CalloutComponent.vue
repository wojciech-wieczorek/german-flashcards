<script setup>
import clsx from 'clsx';
import { computed } from 'vue';

const props = defineProps(['type']);

const containerClass = computed(() => {
    const typeColorClass = {
        'bg-green-200 text-green-950 border-green-700': props.type == 'success',
        'bg-yellow-200 text-yellow-950': props.type == 'warninig',
        'bg-red-200 text-red-950': props.type == 'failure',
    };

    return clsx('w-lg rounded border p-2 text-center', typeColorClass);
});
</script>

<template>
    <div :class="containerClass">
        <slot></slot>
    </div>
</template>
