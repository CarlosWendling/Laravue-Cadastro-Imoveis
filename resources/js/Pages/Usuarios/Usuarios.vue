<script setup>
    import Filtro from '@/Components/Filtro.vue'
    import Pagination from '@/Components/Pagination.vue'

    const props = defineProps ({
        usuarios: Object,
        filtros: Array,
        routeName: String,
        usuarioAtual: Object,
        errors: Object,
        auth: Object,
        flash: Object
    })

    const formatSituacao = (char) => {
        if (char == 'S') {
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
            <Filtro :filtros="props.filtros" :routeName="props.routeName" />
            
            <Btn :disabled="props.usuarioAtual.perfil == 'A'" class="ml-3">
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
                v-for="usuario in props.usuarios.data"
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
                        :disabled=" (props.usuarioAtual.perfil == 'S' && usuario.perfil != 'A') || props.usuarioAtual.perfil == 'A'"
                    >
                        <Link :href="route('usuario.show', usuario.id)">
                            Visualizar
                        </Link>
                    </Btn>
                </td>
            </tr>
        </tbody>
    </v-table>
    <Pagination 
        :links="props.usuarios.links"
        class="mt-6 pb-2"
    />
</template>