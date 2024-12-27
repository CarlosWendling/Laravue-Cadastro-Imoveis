<script setup>
    import Layout from '@/Layouts/Layout.vue';
    import FormPessoa from '@/Components/FormPessoa.vue';

    const props = defineProps ({
        pessoa: Object
    })
</script>

<template>

    <Head :title="`Editar ${props.pessoa.nome}`" />

    <FormPessoa :pessoa="props.pessoa" textBtn="Atualizar" />

</template>