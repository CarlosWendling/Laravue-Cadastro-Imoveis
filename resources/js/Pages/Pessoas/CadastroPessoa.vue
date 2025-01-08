<script setup>
    import FormPessoa from '@/Components/FormPessoa.vue';
</script>

<template>
    <Head title="Cadastro Pessoa" />

    <FormPessoa 
        textBtn="Cadastrar" 
        method="post" 
        route="/pessoas/store" 
    />
</template>