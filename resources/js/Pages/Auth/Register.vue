<script setup>
    import FormUsuario from '@/Components/FormUsuario.vue';

    const props = defineProps ({
        usuarioAtual: Object
    })
</script>

<template>    
    <Head title="Register" />

    <FormUsuario 
        :usuarioAtual="props.usuarioAtual"
        :usuario="props.usuario" 
        textBtn="Cadastrar" 
        method="post" 
        route="/register"
    />
</template>
