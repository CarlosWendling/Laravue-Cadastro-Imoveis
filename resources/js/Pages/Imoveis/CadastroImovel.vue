<script setup>
    import FormImovel from '@/Components/FormImovel.vue'

    const props = defineProps({
        pessoas: Object
    })
</script>

<template>
    <Head title="Cadastro Imóvel" />

    <FormImovel textBtn="Cadastrar" :pessoas="props.pessoas" />
</template>