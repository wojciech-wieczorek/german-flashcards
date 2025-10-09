<script setup>
import clsx from 'clsx';

defineProps({
    title: { type: String, default: 'Are you sure?' },
});

defineEmits(['close', 'confirm']);

const containerClass = clsx('fixed inset-0 z-50 flex items-center justify-center');
const backdropClass = clsx('absolute inset-0 bg-sky-950/50');
const messageContainerClass = clsx('relative mx-4 w-full max-w-md rounded-lg bg-white p-6 shadow-xl');
const titleClass = clsx('mb-2 text-xl font-semibold text-gray-900');
const messageClass = clsx('mb-6 text-gray-600');
const buttonsContainerClass = clsx('flex justify-end gap-3');
const buttonClass = {
    close: clsx('bg-gray-200 text-gray-950 hover:bg-gray-400 hover:text-gray-50'),
    confirm: clsx('bg-sky-200 text-sky-950 hover:bg-sky-400 hover:text-sky-50'),
    all: clsx('rounded px-4 py-2 shadow-sm hover:shadow-lg'),
};
</script>

<template>
    <div :class="containerClass">
        <div :class="backdropClass" @click="$emit('close')"></div>
        <div :class="messageContainerClass">
            <h2 :class="titleClass">
                {{ title }}
            </h2>
            <p :class="messageClass">
                <slot></slot>
            </p>
            <div :class="buttonsContainerClass">
                <button :class="[buttonClass['close'], buttonClass['all']]" @click="$emit('close')">CANCEL</button>
                <button :class="[buttonClass['confirm'], buttonClass['all']]" @click="$emit('confirm')">CONFIRM</button>
            </div>
        </div>
    </div>
</template>
