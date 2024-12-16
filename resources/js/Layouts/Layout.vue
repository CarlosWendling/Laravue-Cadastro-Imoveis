<script setup>
    import { ref } from 'vue';
    import NavLink from '@/Components/NavLink.vue';

    import NavDrawer from '../Components/NavDrawer.vue'

    const isDrawerOpen = ref(false)
</script>

<template>
    <v-app>
        <v-navigation-drawer 
            v-model="isDrawerOpen"
            >
                <v-list>
                    <NavDrawer />
                </v-list>
        </v-navigation-drawer>

        <v-app-bar class="px-5">
            <v-app-bar-nav-icon @click="isDrawerOpen = !isDrawerOpen"></v-app-bar-nav-icon>
            <v-app-bar-title>{{ $page.component }}</v-app-bar-title>
        </v-app-bar>

        <v-main  class="bg-gray-100">

            <slot />
        </v-main>
    </v-app>
</template>