<script setup>
    import FormImovel from '@/Components/FormImovel.vue';

    const props = defineProps ({
        imovel: Object,
        pessoas: Object
    })
</script>

<template>

    <Head :title="`Editar ${props.imovel.logradouro}`" />

    <FormImovel :imovel="props.imovel" :pessoas="props.pessoas" textBtn="Atualizar" />

</template>