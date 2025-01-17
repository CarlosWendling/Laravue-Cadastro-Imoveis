<script setup>
    import UpdatePasswordForm from '../Profile/Partials/UpdatePasswordForm.vue';

    const props = defineProps ({
        user: Object
    })
</script>

<template>
    <UpdatePasswordForm :user="props.user" />
</template>