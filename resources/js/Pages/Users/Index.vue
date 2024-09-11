<template>
    <h1>User: {{ name }}</h1>
</template>

<script setup>
    defineProps({ name: String })
</script>