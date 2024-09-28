<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import NavBar from './NavBar.vue';
import Footer from './Footer.vue';
import { Suspense } from 'vue';
</script>

<template>
    <div class="d-flex flex-column min-vh-100">
        <NavBar />
        <Suspense>
            <article class="flex-grow-1">
                <slot />
            </article>
            <template #fallback>
                <h1>
                    Loading...
                </h1>
              </template>
        </Suspense>
        <Footer />
    </div>
</template>