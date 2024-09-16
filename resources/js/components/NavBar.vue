<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <main>
        <nav class="navbar navbar-expand-lg bg-warning">
          <div class="container-fluid">
            <Link class="navbar-brand" :href="route('home')">
              <img src="@\assets\images\logo.jpg" width="45" height="45" alt="logo"></Link>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                </li>
                <li class="nav-item">
                  <Link class="nav-link" :href="route('login')">Login</Link>
                </li>
                <!-- <li><RouterLink class="nav-link" to="/venda-chave-troca">Venda-Chave-Troca</RouterLink></li> -->
                <li><Link class="nav-link" :href="route('venda-chave-troca')">Venda-Chave-Troca</Link></li>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Taxas
                  </a>
                  <ul class="dropdown-menu">
                    <li><Link class="dropdown-item" :href="route('taxas')">MarketPlaces</Link></li>
                    <li><Link class="dropdown-item" :href="route('ranges-taxa-G2A')">Ranges Taxa G2A</Link></li>
                    <li><Link class="dropdown-item" :href="route('taxas')">Calculadora</Link></li>
                  </ul>
                </li>
                <!-- <li><Link class="nav-link" :href="route('recursos')">Recursos</Link></li> -->
              </ul>
            </div>
          </div>
        </nav>
      </main>
</template>