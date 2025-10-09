<script setup>
import { useForm } from '@inertiajs/vue3';
import clsx from 'clsx';
import { ref } from 'vue';
import HeaderComponent from './components/HeaderComponent.vue';
import ModalComponent from './components/ModalComponent.vue';
import Layout from './layouts/Layout.vue';

defineOptions({ layout: Layout });
const props = defineProps({ flashcards: Array });

const isModalOpen = ref(false);
const flashcardIndex = ref(null);

const form = useForm();

const handleModify = (idx) => {
    form.get(`/flashcards/${idx}/edit`);
};

const handleDelete = () => {
    form.delete(`/flashcards/${flashcardIndex.value}`, {
        onSuccess: () => {
            flashcardIndex.value = null;
            isModalOpen.value = false;
        },
    });
};

const handleOpenModal = (idx) => {
    flashcardIndex.value = idx;
    isModalOpen.value = true;
};

const handleCloseModal = () => {
    flashcardIndex.value = null;
    isModalOpen.value = false;
};

const tableClass = clsx('mt-5 w-full table-auto text-left text-sm text-sky-900');
const theadClass = clsx('bg-sky-50 text-lg text-sky-700 uppercase');
const tbodyClass = clsx('text-md');
const tdClass = clsx('py-1');
const buttonsContainerClass = clsx('flex gap-1');
const buttonClass = {
    modify: clsx('bg-yellow-200 text-yellow-950 hover:bg-yellow-400 hover:text-yellow-50'),
    delete: clsx('bg-red-400 text-red-950 hover:bg-red-600 hover:text-red-50'),
    all: clsx(
        'rounded-md border border-transparent px-2 py-1 text-center text-base shadow-md transition-all hover:shadow-lg focus:shadow-none active:shadow-none',
    ),
};
</script>

<template>
    <HeaderComponent>Colllection</HeaderComponent>
    <table :class="tableClass">
        <thead :class="theadClass">
            <tr>
                <th>Side A</th>
                <th>Side B</th>
                <th>Box</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody :class="tbodyClass">
            <tr v-for="(flashcard, idx) in flashcards">
                <td :class="tdClass">{{ flashcard.question }}</td>
                <td :class="tdClass">{{ flashcard.answer }}</td>
                <td :class="tdClass">{{ flashcard.box }}</td>
                <td :class="tdClass">
                    <div :class="buttonsContainerClass">
                        <form @submit.prevent="handleModify(idx)">
                            <button :class="[buttonClass['modify'], buttonClass['all']]" type="submit">Modify</button>
                        </form>
                        <form @submit.prevent="handleOpenModal(idx)">
                            <button :class="[buttonClass['delete'], buttonClass['all']]" type="submit">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    <ModalComponent v-if="isModalOpen" @close="handleCloseModal" @confirm="handleDelete">{{
        `Do you want to delete '${flashcards[flashcardIndex] && flashcards[flashcardIndex].question}' flashcard?`
    }}</ModalComponent>
</template>
