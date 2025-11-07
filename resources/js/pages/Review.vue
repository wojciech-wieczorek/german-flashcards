<script setup>
import clsx from 'clsx';
import BoxComponent from './components/BoxComponent.vue';
import HeaderComponent from './components/HeaderComponent.vue';
import Layout from './layouts/Layout.vue';

defineOptions({ layout: Layout });
defineProps({ boxSizes: Object });

const articleClass = clsx('flex size-full flex-col items-center p-2');
const boxesContainerClass = clsx('my-5 flex justify-center gap-5');
</script>

<template>
    <article :class="articleClass">
        <HeaderComponent>Review</HeaderComponent>

        <div :class="boxesContainerClass">
            <BoxComponent v-for="i in 5" :number="i" :size="boxSizes[i]">{{ `Box ${i}` }}</BoxComponent>
        </div>

        <div :class="boxesContainerClass">
            <BoxComponent :number="6" :size="boxSizes[6]">{{ 'Archive' }}</BoxComponent>
        </div>
    </article>
</template>
