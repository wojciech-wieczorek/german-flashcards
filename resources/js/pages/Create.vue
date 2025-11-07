<script setup>
import clsx from 'clsx';
import { useForm } from 'laravel-precognition-vue-inertia';
import { useTemplateRef } from 'vue';
import CalloutComponent from './components/CalloutComponent.vue';
import HeaderComponent from './components/HeaderComponent.vue';
import Layout from './layouts/Layout.vue';

defineOptions({ layout: Layout });
const props = defineProps({ message: String });

const form = useForm('post', '/flashcards', {
    question: '',
    answer: '',
});

const handleSubmit = () => {
    form.submit({
        onSuccess: () => form.reset(),
    });
};

const answerInput = useTemplateRef('answerInput');
const germanCharacters = ['ä', 'ö', 'ü', 'ß'];

const handleCharClick = (event) => {
    form.answer += event.target.value;
    answerInput.value.focus();
};

const articleClass = clsx('flex size-full flex-col items-center p-2');
const fieldsetClass = clsx('flex flex-col items-center p-2');
const labelClass = clsx('text-md mt-5 mb-2 block text-xl font-medium text-sky-900');
const inputClass = {
    all: clsx('block w-md rounded-lg border bg-sky-50 p-2.5 text-center text-xl text-sky-900'),
    default: clsx('border-sky-300 focus:border-sky-500 focus:outline-sky-500'),
    invalid: clsx('border-red-300 focus:border-red-500 focus:outline-red-500'),
};
const characterButtonsContainerClass = clsx('mt-5 flex justify-center gap-3');
const characterButtonClass = clsx(
    'rounded border border-sky-400 bg-sky-50 px-2 py-1 text-center text-base text-sky-900 shadow-sm hover:bg-sky-300 hover:shadow-lg',
);
const errorMessageClass = clsx('text-red-500');
const buttonClass = clsx(
    'my-5 rounded bg-green-300 px-3 py-2 text-center text-xl text-green-950 shadow-sm hover:bg-green-500 hover:text-green-50 hover:shadow-lg',
);
</script>

<template>
    <article :class="articleClass">
        <HeaderComponent> Create a new flashcard </HeaderComponent>

        <CalloutComponent v-if="props.message" type="success">{{ message }}</CalloutComponent>

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
                <input :class="buttonClass" type="submit" value="Create" />
            </fieldset>
        </form>
    </article>
</template>
