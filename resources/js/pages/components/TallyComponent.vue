<script setup>
import { Link } from '@inertiajs/vue3';
import clsx from 'clsx';
import { onBeforeUnmount, onMounted, useTemplateRef } from 'vue';

defineProps({ tally: Object });

const backButton = useTemplateRef('backButton');

const handleGlobalEnter = function (event) {
    if (event.key === 'Enter') {
        backButton.value.$el.click();
    }
};

onMounted(() => {
    window.addEventListener('keydown', handleGlobalEnter);
});

onBeforeUnmount(() => {
    window.removeEventListener('keydown', handleGlobalEnter);
});

const tallyContainerClass = clsx('mx-auto my-3 flex w-xl flex-col items-center rounded border-2 border-sky-300 bg-sky-50');
const subheaderClass = clsx('my-3 text-center text-4xl font-bold text-sky-900');
const tallyCorrectClass = clsx('my-1 text-3xl text-green-800');
const tallyIncorrectClass = clsx('my-1 text-3xl text-red-800');
const tallySkippedClass = clsx('my-1 text-3xl text-sky-800');
const buttonClass = clsx(
    'my-5 rounded border border-transparent bg-sky-600 px-3 py-1 text-center text-xl text-white shadow-sm hover:border-sky-900 hover:bg-sky-300 hover:text-sky-900 hover:shadow-lg',
);
</script>

<template>
    <div :class="tallyContainerClass">
        <h2 :class="subheaderClass">Results</h2>
        <div :class="tallyCorrectClass">
            <span>correct: </span><span>{{ tally.correct }}</span>
        </div>
        <div :class="tallyIncorrectClass">
            <span>incorrect: </span><span>{{ tally.incorrect }}</span>
        </div>
        <div :class="tallySkippedClass">
            <span>skipped: </span><span>{{ tally.skipped }}</span>
        </div>
        <Link :class="buttonClass" href="/boxes" as="button" ref="backButton">Go back</Link>
    </div>
</template>
