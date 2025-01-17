<script setup>
    import FormUsuario from '@/Components/FormUsuario.vue';

    const props = defineProps ({
        usuarioAtual: Object,
        usuario: Object
    })
</script>

<template>
    <Head :title="`Editar ${props.usuario.name}`" />

    <FormUsuario 
        :usuarioAtual="props.usuarioAtual"
        :usuario="props.usuario" 
        textBtn="Atualizar" 
        method="put" 
        route="/usuario/update"
    />
</template>