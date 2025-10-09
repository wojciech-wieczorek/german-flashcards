<script setup>
import { router } from '@inertiajs/vue3';
import clsx from 'clsx';
import { useForm } from 'laravel-precognition-vue-inertia';
import { ref } from 'vue';
import CalloutComponent from '../components/CalloutComponent.vue';
import HeaderComponent from '../components/HeaderComponent.vue';
import Layout from '../layouts/Layout.vue';

defineOptions({ layout: Layout });
const props = defineProps({ flashcard: Object });

const isCalloutOpen = ref(false);

const form = useForm('patch', `/flashcards/${props.flashcard.index}`, {
    question: props.flashcard.question,
    answer: props.flashcard.answer,
});

const handleSubmit = () => {
    form.submit({
        onSuccess: () => {
            isCalloutOpen.value = false;
            router.get('/flashcards');
        },
        onError: () => {
            isCalloutOpen.value = true;
        },
    });
};

const handleCancel = () => {
    router.get('/flashcards');
};

const fieldsetClass = clsx('flex flex-col items-center p-2');
const labelClass = clsx('text-md mt-5 mb-2 block text-xl font-medium text-sky-900');
const inputClass = clsx(
    'block w-md rounded-lg border border-sky-300 bg-sky-50 p-2.5 text-center text-xl text-sky-900 focus:border-sky-500 focus:ring-sky-500 focus:outline-sky-500',
);
const errorMessageClass = clsx('text-red-500');
const buttonsContainerClass = clsx('my-5 flex gap-3');
const buttonClass = {
    confirm: clsx('bg-green-300 text-green-950 hover:bg-green-500 hover:text-green-50'),
    cancel: clsx('bg-red-300 text-red-950 hover:bg-red-500 hover:text-red-50'),
    all: clsx('rounded px-3 py-2 text-center text-xl shadow-sm hover:shadow-lg'),
};
</script>

<template>
    <HeaderComponent> Modify the flashcard </HeaderComponent>
    <CalloutComponent v-if="isCalloutOpen" type="failure">Something went wrong...</CalloutComponent>
    <form @submit.prevent="handleSubmit">
        <fieldset :class="fieldsetClass">
            <label :class="labelClass" for="question">Side A (question)</label>
            <input :class="inputClass" v-model="form.question" @change="form.validate('question')" type="text" id="question" />
            <div :class="errorMessageClass" v-if="form.invalid('question')">{{ form.errors.question }}</div>
            <label :class="labelClass" for="answer">Side B (answer)</label>
            <input :class="inputClass" v-model="form.answer" type="text" id="answer" />
            <div :class="buttonsContainerClass">
                <input :class="[buttonClass['confirm'], buttonClass['all']]" type="submit" value="CONFIRM" />
                <input :class="[buttonClass['cancel'], buttonClass['all']]" type="button" value="CANCEL" @click="handleCancel" />
            </div>
        </fieldset>
    </form>
</template>
