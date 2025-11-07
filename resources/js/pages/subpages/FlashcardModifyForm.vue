<script setup>
import { router } from '@inertiajs/vue3';
import clsx from 'clsx';
import { useForm } from 'laravel-precognition-vue-inertia';
import { useTemplateRef } from 'vue';
import HeaderComponent from '../components/HeaderComponent.vue';
import Layout from '../layouts/Layout.vue';

defineOptions({ layout: Layout });
const props = defineProps({ flashcard: Object });

const form = useForm('patch', `/flashcards/${props.flashcard.index}`, {
    question: props.flashcard.question,
    answer: props.flashcard.answer,
});

const answerInput = useTemplateRef('answerInput');
const germanCharacters = ['ä', 'ö', 'ü', 'ß'];

const handleCharClick = (event) => {
    form.answer += event.target.value;
    answerInput.value.focus();
};

const handleSubmit = () => {
    form.submit({
        onSuccess: () => {
            router.get('/flashcards');
        },
    });
};

const handleCancel = () => {
    router.get('/flashcards');
};

const articleClass = clsx('flex size-full flex-col items-center p-2');
const fieldsetClass = clsx('flex flex-col items-center p-2');
const labelClass = clsx('text-md mt-5 mb-2 block text-xl font-medium text-sky-900');
const inputClass = {
    all: clsx('block w-md rounded-lg border bg-sky-50 p-2.5 text-center text-xl text-sky-900'),
    default: clsx('border-sky-300 focus:border-sky-500 focus:outline-sky-500'),
    invalid: clsx('border-red-300 focus:border-red-500 focus:outline-red-500'),
};
const errorMessageClass = clsx('text-red-500');
const characterButtonsContainerClass = clsx('mt-5 flex justify-center gap-3');
const characterButtonClass = clsx(
    'rounded border border-sky-400 bg-sky-50 px-2 py-1 text-center text-base text-sky-900 shadow-sm hover:bg-sky-300 hover:shadow-lg',
);
const buttonsContainerClass = clsx('my-5 flex gap-3');
const buttonClass = {
    confirm: clsx('bg-green-300 text-green-950 hover:bg-green-500 hover:text-green-50'),
    cancel: clsx('bg-red-300 text-red-950 hover:bg-red-500 hover:text-red-50'),
    all: clsx('rounded px-3 py-2 text-center text-xl shadow-sm hover:shadow-lg'),
};
</script>

<template>
    <article :class="articleClass">
        <HeaderComponent> Modify the flashcard </HeaderComponent>

        <form @submit.prevent="handleSubmit" autocomplete="off">
            <fieldset :class="fieldsetClass">
                <label :class="labelClass" for="question">Side A (question)</label>
                <input
                    :class="[inputClass['all'], form.invalid('question') ? inputClass['invalid'] : inputClass['default']]"
                    v-model="form.question"
                    @change="form.validate({ only: ['question'] })"
                    v-focus
                    type="text"
                    id="question"
                />
                <div :class="errorMessageClass" v-if="form.invalid('question')">{{ form.errors.question }}</div>

                <label :class="labelClass" for="answer">Side B (answer)</label>
                <input
                    :class="[inputClass['all'], form.invalid('answer') ? inputClass['invalid'] : inputClass['default']]"
                    v-model="form.answer"
                    ref="answerInput"
                    @change="form.validate({ only: ['answer'] })"
                    type="text"
                    id="answer"
                />
                <div :class="errorMessageClass" v-if="form.invalid('answer')">{{ form.errors.answer }}</div>

                <div :class="characterButtonsContainerClass">
                    <input v-for="char in germanCharacters" :class="characterButtonClass" type="button" :value="char" @click="handleCharClick" />
                </div>

                <div :class="buttonsContainerClass">
                    <input :class="[buttonClass['confirm'], buttonClass['all']]" type="submit" value="Confirm" />
                    <input :class="[buttonClass['cancel'], buttonClass['all']]" type="button" value="Cancel" @click="handleCancel" />
                </div>
            </fieldset>
        </form>
    </article>
</template>
