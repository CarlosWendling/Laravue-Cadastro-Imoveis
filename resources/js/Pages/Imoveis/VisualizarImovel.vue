<script setup>
    import FormImovel from '@/Components/FormImovel.vue';

    const props = defineProps ({
        imovel: Object,
        pessoas: Object,
        averbacoes: Object,
        arquivos: Object
    })
</script>

<template>

    <Head :title="`Editar ${props.imovel.logradouro}`" />

    <FormImovel 
        :imovel="props.imovel"
        :pessoas="props.pessoas" 
        :averbacoes="props.averbacoes" 
        :arquivos="props.arquivos" 
        textBtn="Atualizar" 
        method="put" 
        route="/imovel/update"
    />

</template>