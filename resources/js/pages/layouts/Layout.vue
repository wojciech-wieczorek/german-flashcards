<script setup>
import { Link } from '@inertiajs/vue3';
import clsx from 'clsx';
import { computed } from 'vue';

const links = computed(() => ({
    Create: '/flashcards/new',
    Collection: '/flashcards',
    Review: '/boxes',
}));

const mainClass = clsx('mx-auto my-[4svh] flex h-full w-full flex-col rounded border-2 border-sky-900 sm:h-[90svh] sm:w-7xl');
const headerClass = clsx('flex w-full justify-center gap-20 border-b-2 border-sky-950 bg-sky-200 p-3 text-xl font-semibold text-sky-950');
const articleClass = clsx('size-full p-3');
</script>

<template>
    <main :class="mainClass">
        <header :class="headerClass">
            <Link v-for="(href, name) in links" :href>{{ name }}</Link>
        </header>
        <article :class="articleClass">
            <slot></slot>
        </article>
    </main>
</template>
