<script setup>
    import Filtro from '@/Components/Filtro.vue';
    import Pagination from '@/Components/Pagination.vue';

    const props = defineProps ({
        auditorias: Object
    })
</script>

<template>
    <Head title="Home" />

    <div class="flex justify-between py-5 border-b-2 items-center">
        <h1 class="text-2xl">Últimas Ações</h1>

        <Filtro class="w-72" />
    </div>

    <v-table>
        <thead>
            <tr class="text-base">
                <th>Id</th>
                <th>Usuário</th>
                <th>Evento</th>
                <th>Data e Hora</th>
                <th>Tabela</th>
                <th>Id Auditado</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <tr v-if="props.auditorias.data.length == 0">
                <td>Nenhuma ação foi realizada</td>
            </tr>
            <tr 
                v-if="props.auditorias.data.length != 0"
                v-for="auditoria in props.auditorias.data"
                :key="auditoria.id"
            >
                <td>{{ auditoria.id }}</td>
                <td>{{ auditoria.user.name }}</td>
                <td>{{ auditoria.evento }}</td>
                <td>{{ auditoria.created_at }}</td>
                <td>{{ auditoria.tabela }}</td>
                <td>{{ auditoria.id_auditado }}</td>
                <td>
                    <Btn
                        variant="tonal"
                    >
                        <Link href="#">
                            Detalhes
                        </Link>
                    </Btn>
                </td>
            </tr>
        </tbody>
    </v-table>

    <Pagination 
        :links="props.auditorias.links"
        class="mt-6 pb-2"
    />
</template>
