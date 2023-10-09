<script setup lang="ts">
import { markRaw } from 'vue';
import { IonNav } from '@ionic/vue';
import AppNav from './AppNav.vue';
</script>

<template>
    <ion-nav :root="markRaw(AppNav)"></ion-nav>
</template>