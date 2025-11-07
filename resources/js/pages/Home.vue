<script setup>
import PageHeader from './components/HeaderComponent.vue';
import Layout from './layouts/Layout.vue';
import clsx from 'clsx';

defineOptions({ layout: Layout });

const articleClass = clsx('flex size-full flex-col items-center p-2');
const subHeaderClass = clsx('text-3xl text-center text-sky-900 mt-5');
const paragrahpClass = clsx('text-xl text-sky-950 mx-auto');
</script>

<template>
    <article :class="articleClass">
        <PageHeader>Home</PageHeader>

        <h2 :class="subHeaderClass">Add flashcards</h2>
        <p :class="paragrahpClass">Click '<b>Create</b>' to add new flashcards with:
            <ul class="list-disc list-inside">
                <li>an <b>English/Polish</b> word as a <b>question</b>,</li>
                <li>a <b>German</b> word as a <b>answer</b>.</li>
            </ul>
        </p>
        <h2 :class="subHeaderClass">Review daily</h2>

        <p :class="paragrahpClass">Once a day, click '<b>Review</b>' to review your flashcards.</p>
        <p :class="paragrahpClass">Click boxes with flashcards going from <b>right to left</b>.</p>
        <br>
        <p :class="paragrahpClass"><b>Correct</b> answers move a flashcard <b>one box to the right</b>.</p>
        <p :class="paragrahpClass"><b>Incorrect</b> move it back to the <b>Box 1</b>.</p>
    </article>
</template>
