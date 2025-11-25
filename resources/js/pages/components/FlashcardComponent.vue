<script setup>
import axios from 'axios';
import clsx from 'clsx';
import { nextTick, ref } from 'vue';

const props = defineProps(['flashcard']);
const emit = defineEmits(['check', 'skip']);

const answer = ref('');
const answerInput = ref(null);
const germanCharacters = ['ä', 'ö', 'ü', 'ß'];

const handleSubmit = () => {
    if (answer.value == props.flashcard.answer) {
        emit('check', [true, 'Correct!']);
        axios.patch(`/flashcards/${props.flashcard.index}`, { box: Math.min(parseInt(props.flashcard.box) + 1, 6) });
        answer.value = '';
    } else {
        emit('check', [false, `Incorrect! The correct answer: ${props.flashcard.answer}`]);
        axios.patch(`/flashcards/${props.flashcard.index}`, { box: 1 });
        answer.value = '';
    }
};

const handleCharacterButtonClick = (event) => {
    answer.value += event.target.value;
    nextTick(() => {
        answerInput.value?.focus();
    });
};
const flashcardClass = clsx('w-xl rounded border-2 border-sky-700 bg-sky-100 p-5 text-2xl');
const legendClass = clsx('my-5 text-center text-3xl font-bold text-sky-950');
const fieldsetClass = clsx('flex flex-col items-center');
const labelClass = clsx('hidden');
const inputClass = clsx(
    'block w-sm rounded-lg border border-sky-400 bg-sky-50 p-2.5 text-sm text-sky-900 focus:border-sky-500 focus:ring-sky-500 focus:outline-sky-500',
);
const buttonsContainerClass = clsx('mt-5 flex justify-center gap-3');
const characterButtonClass = clsx(
    'rounded border border-sky-400 bg-sky-50 px-2 py-1 text-center text-base text-sky-900 shadow-sm hover:bg-sky-300 hover:shadow-lg',
);
const buttonClass = clsx(
    'rounded border border-transparent bg-sky-600 px-3 py-1 text-center text-xl text-white shadow-sm hover:border-sky-900 hover:bg-sky-300 hover:text-sky-900 hover:shadow-lg',
);
</script>

<template>
    <div :class="flashcardClass">
        <form @submit.prevent="handleSubmit" id="flashcardReviewForm" autocomplete="off">
            <fieldset :class="fieldsetClass">
                <legend :class="legendClass">{{ flashcard.question }}</legend>
                <label :class="labelClass" for="answer">Answer</label>
                <input :class="inputClass" v-model="answer" ref="answerInput" v-focus type="text" name="answer" id="answer" />
                <div :class="buttonsContainerClass">
                    <input
                        v-for="char in germanCharacters"
                        :class="characterButtonClass"
                        type="button"
                        :value="char"
                        @click="handleCharacterButtonClick"
                    />
                </div>
            </fieldset>
        </form>
        <div :class="buttonsContainerClass">
            <input :class="buttonClass" type="submit" form="flashcardReviewForm" value="Check" />
            <input :class="buttonClass" type="button" value="Skip" @click="$emit('skip')" />
        </div>
    </div>
</template>
