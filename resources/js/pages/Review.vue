<script setup>
import clsx from 'clsx';
import BoxComponent from './components/BoxComponent.vue';
import PageHeader from './components/HeaderComponent.vue';
import Layout from './layouts/Layout.vue';

defineOptions({ layout: Layout });
defineProps({ boxSizes: Object });

const boxesContainerClass = clsx('my-5 flex justify-center gap-5');
</script>

<template>
    <PageHeader>Review</PageHeader>
    <div :class="boxesContainerClass">
        <BoxComponent v-for="i in 5" :number="i" :size="boxSizes[i]">{{ `Box ${i}` }}</BoxComponent>
    </div>
    <!-- <div :class="boxesContainerClass">
        <BoxComponent :number="'archive'" :size="boxSizes['archive']">{{ 'Archive' }}</BoxComponent>
    </div> -->
</template>
