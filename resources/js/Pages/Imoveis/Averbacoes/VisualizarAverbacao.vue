<script setup>
    import FormAverbacao from '@/Components/FormAverbacao.vue'

    const props = defineProps ({
        averbacao: Object
    })
</script>

<template>
    <Head title="Visualizar Averbação" />

    <FormAverbacao 
        :averbacao="props.averbacao"
    />
</template>