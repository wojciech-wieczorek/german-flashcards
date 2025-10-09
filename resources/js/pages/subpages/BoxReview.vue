<script setup>
import { Link } from '@inertiajs/vue3';
import clsx from 'clsx';
import { ref } from 'vue';
import CalloutComponent from '../components/CalloutComponent.vue';
import FlashcardComponent from '../components/FlashcardComponent.vue';
import PageHeader from '../components/HeaderComponent.vue';
import Layout from '../layouts/Layout.vue';

defineOptions({ layout: Layout });
const props = defineProps({ flashcards: Array });

const flashcardIndex = ref(0);
const callout = ref({
    isOpen: false,
    type: '',
    message: '',
});
const tally = ref({
    correct: 0,
    incorrect: 0,
    skipped: 0,
});

const handleCheck = ([isCorrect, msg]) => {
    if (isCorrect) {
        callout.value.message = msg;
        callout.value.type = 'success';
        callout.value.isOpen = true;
        tally.value.correct++;
        flashcardIndex.value++;
    } else {
        callout.value.message = msg;
        callout.value.type = 'failure';
        callout.value.isOpen = true;
        tally.value.incorrect++;
        flashcardIndex.value++;
    }
};

const handleSkip = () => {
    tally.value.skipped++;
    flashcardIndex.value++;
};

const containerClass = clsx('my-5 flex flex-col items-center justify-center gap-5');
const subheaderClass = clsx('my-3 text-center text-4xl font-bold text-sky-900');
const tallyContainerClass = clsx('mx-auto my-5 flex w-xl flex-col items-center rounded border-2 border-sky-300 bg-sky-50');
const tallyCorrectClass = clsx('my-1 text-3xl text-green-800');
const tallyIncorrectClass = clsx('my-1 text-3xl text-red-800');
const tallySkippedClass = clsx('my-1 text-3xl text-sky-800');
const buttonClass = clsx(
    'my-5 rounded border border-transparent bg-sky-600 px-3 py-1 text-center text-xl text-white shadow-sm hover:border-sky-900 hover:bg-sky-300 hover:text-sky-900 hover:shadow-lg',
);
</script>

<template>
    <PageHeader>Review cards</PageHeader>
    <div v-if="flashcardIndex < flashcards.length" :class="containerClass">
        <CalloutComponent :type="callout.type" v-if="callout.isOpen">
            {{ callout.message }}
        </CalloutComponent>
        <FlashcardComponent
            v-if="flashcardIndex < flashcards.length"
            @check="(msg) => handleCheck(msg)"
            @skip="handleSkip"
            v-bind:flashcard="flashcards[flashcardIndex]"
        ></FlashcardComponent>
    </div>
    <div v-else :class="tallyContainerClass">
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
        <Link :class="buttonClass" href="/boxes">Go back</Link>
    </div>
</template>
