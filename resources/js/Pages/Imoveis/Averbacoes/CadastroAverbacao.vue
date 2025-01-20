<script setup>
    import FormAverbacao from '@/Components/FormAverbacao.vue'

    const props = defineProps({
        imovel: Object
    })
</script>

<template>
    <Head title="Cadastro Averbação" />

    <FormAverbacao 
        :imovel="props.imovel"
        method="post" 
        route="/imovel/averbacoes/store"
    />
</template>