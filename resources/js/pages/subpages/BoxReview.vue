<script setup>
import clsx from 'clsx';
import { ref } from 'vue';
import CalloutComponent from '../components/CalloutComponent.vue';
import FlashcardComponent from '../components/FlashcardComponent.vue';
import HeaderComponent from '../components/HeaderComponent.vue';
import TallyComponent from '../components/TallyComponent.vue';
import Layout from '../layouts/Layout.vue';

defineOptions({ layout: Layout });
defineProps({ flashcards: Array });

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

const articleClass = clsx('flex size-full flex-col items-center p-2');
const containerClass = clsx('flex flex-col items-center gap-5');
</script>

<template>
    <article :class="articleClass">
        <HeaderComponent>Review cards</HeaderComponent>

        <div :class="containerClass">
            <CalloutComponent :type="callout.type" v-if="callout.isOpen">
                {{ callout.message }}
            </CalloutComponent>

            <FlashcardComponent
                v-if="flashcardIndex < flashcards.length"
                @check="(msg) => handleCheck(msg)"
                @skip="handleSkip"
                v-bind:flashcard="flashcards[flashcardIndex]"
            ></FlashcardComponent>

            <TallyComponent v-else :tally></TallyComponent>
        </div>

    </article>
</template>
