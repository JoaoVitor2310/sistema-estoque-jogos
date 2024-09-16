<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import NavBar from './NavBar.vue';
</script>

<template>
    <NavBar/>
    <article>
        <slot />
    </article>
    <footer>
        Desenvolvido por: João Vitor Matos Gouveia
    </footer>
</template>