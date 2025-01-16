<script setup>
    import Filtro from '@/Components/Filtro.vue'

    const props = defineProps ({
        usuarios: Object,
        errors: Object,
        auth: Object,
        flash: Object
    })

    const formatSituacao = (char) => {
        if (char == 'A') {
            return 'Ativo'
        } else {
            return 'Inativo'
        }
    }

    const formatPerfil = (char) => {
        if (char == 'T') {
            return 'Administrador da TI'
        } else if (char == 'S') {
            return 'Administrador do sistema'
        } else {
            return 'Atendente'
        }
    }
</script>

<template>
    <Head title="Usuários" />

    <div class="flex justify-between py-5 border-b-2 items-center">
        <h1 class="text-2xl">Usuários</h1>

        <div class="flex items-center">
            <Filtro />
            
            <Btn class="ml-3">
                <Link href="/register">
                    Cadastrar
                </Link>
            </Btn>
        </div>
    </div>
    <v-table>
        <thead>
            <tr class="text-base">
                <th>Id</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Perfil</th>
                <th>Situação</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <tr
                v-for="usuario in props.usuarios"
                :key="usuario.id"
            >
                <td>{{ usuario.id }}</td>
                <td>{{ usuario.name }}</td>
                <td>{{ usuario.email }}</td>
                <td>{{ formatPerfil(usuario.perfil) }}</td>
                <td>{{ formatSituacao(usuario.ativo) }}</td>
                <td>
                    <Btn
                        variant="tonal"
                        prepend-icon="mdi-pencil"
                    >
                        Visualizar
                    </Btn>
                </td>
            </tr>
        </tbody>
    </v-table>
</template>