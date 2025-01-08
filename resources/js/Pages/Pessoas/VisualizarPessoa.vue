<script setup>
    import FormPessoa from '@/Components/FormPessoa.vue';

    const props = defineProps ({
        pessoa: Object
    })
</script>

<template>

    <Head :title="`Editar ${props.pessoa.nome}`" />

    <FormPessoa 
        :pessoa="props.pessoa" 
        textBtn="Atualizar" 
        method="put" 
        route="/pessoa/update"
    />

</template>