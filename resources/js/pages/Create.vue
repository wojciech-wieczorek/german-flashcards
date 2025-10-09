<script setup>
import clsx from 'clsx';
import { useForm } from 'laravel-precognition-vue-inertia';
import CalloutComponent from './components/CalloutComponent.vue';
import HeaderComponent from './components/HeaderComponent.vue';
import Layout from './layouts/Layout.vue';

defineOptions({ layout: Layout });
const props = defineProps(['message']);

const form = useForm('post', '/flashcards', {
    question: '',
    answer: '',
});

const handleSubmit = () => {
    form.submit({
        onSuccess: () => form.reset(),
    });
};

const fieldsetClass = clsx('flex flex-col items-center p-2');
const labelClass = clsx('text-md mt-5 mb-2 block text-xl font-medium text-sky-900');
const inputClass = {
    all: clsx('block w-md rounded-lg border bg-sky-50 p-2.5 text-center text-xl text-sky-900'),
    default: clsx('border-sky-300 focus:border-sky-500 focus:outline-sky-500'),
    invalid: clsx('border-red-300 focus:border-red-500 focus:outline-red-500'),
};
const errorMessageClass = clsx('text-red-500');
const buttonClass = clsx(
    'my-5 rounded bg-green-300 px-3 py-2 text-center text-xl text-green-950 shadow-sm hover:bg-green-500 hover:text-green-50 hover:shadow-lg',
);
</script>

<template>
    <HeaderComponent> Create a new flashcard </HeaderComponent>
    <CalloutComponent v-if="props.message" type="success">{{ message }}</CalloutComponent>
    <form @submit.prevent="handleSubmit">
        <fieldset :class="fieldsetClass">
            <label :class="labelClass" for="question">Side A (question)</label>
            <input
                :class="[inputClass['all'], form.invalid('question') ? inputClass['invalid'] : inputClass['default']]"
                v-model="form.question"
                @change="form.validate('question')"
                type="text"
                id="question"
            />
            <div :class="errorMessageClass" v-if="form.invalid('question')">{{ form.errors.question }}</div>
            <label :class="labelClass" for="answer">Side B (answer)</label>
            <input
                :class="[inputClass['all'], form.invalid('answer') ? inputClass['invalid'] : inputClass['default']]"
                v-model="form.answer"
                type="text"
                id="answer"
            />
            <input :class="buttonClass" type="submit" value="Create" />
        </fieldset>
    </form>
</template>
