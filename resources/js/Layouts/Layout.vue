<script setup>
    import { watch, ref } from 'vue';
    import NavDrawer from '../Components/NavDrawer.vue'
    import { usePage } from '@inertiajs/vue3';
    import { toast } from 'vue3-toastify';

    const isDrawerOpen = ref(false)

    const page = usePage()

    watch(
        () =>  page.props.flash.success_message,
        (newMessage) => {
            if (newMessage) {
                toast(`${newMessage}`, {
                    autoClose: 3000,
                    type: 'success'
                })
            }
        })
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