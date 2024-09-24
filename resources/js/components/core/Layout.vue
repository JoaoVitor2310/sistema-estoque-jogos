<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import NavBar from './NavBar.vue';
import Footer from './Footer.vue';
</script>

<template>
    <div class="d-flex flex-column min-vh-100">
        <NavBar/>
        <article class="flex-grow-1">
            <slot />
        </article>
        <Footer/>
    </div>
</template>