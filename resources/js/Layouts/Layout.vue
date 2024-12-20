<script setup>
    import { ref } from 'vue';

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
        </v-app-bar>

        <v-main  class="bg-gray-100">
            <div class="py-12">
                <div class="mx-auto max-w-6xl sm:px-6 lg:px-8">
                    <div class="overflow-hidden bg-white shadow-sm px-7">
                        <slot />
                    </div>
                </div>
            </div>
        </v-main>
    </v-app>
</template>