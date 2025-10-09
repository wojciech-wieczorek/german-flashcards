<script setup>
const headerClass = 'text-5xl text-center text-sky-900 my-4';
</script>

<template>
    <h1 :class="headerClass">
        <slot></slot>
    </h1>
</template>
