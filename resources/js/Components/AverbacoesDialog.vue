<script setup>
    const props = defineProps ({
        imovel: Object,
        averbacoes: Array,
    })

    const emit = defineEmits(['close'])

    const formatDate = (dateString) => {
        let [ano, mes, dia] = dateString.split('-')
        return `${dia}/${mes}/${ano}`
    }
</script>

<template>
    <v-card class="pt-2 pb-1 px-3">
        <v-card-title>Averbações</v-card-title>

        <v-table class="my-2">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Evento</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="props.averbacoes?.length == 0">
                    <td>
                        Nenhuma averbação adicionada
                    </td>
                </tr>
                <tr 
                    v-if="props.averbacoes?.length != 0"
                    v-for="averbacao in props.averbacoes"    
                >
                    <td>{{ averbacao.id }}</td>
                    <td>{{ averbacao.evento }}</td>
                    <td>{{ formatDate(averbacao.data_averbacao) }}</td>
                </tr>
            </tbody>
        </v-table>

        <v-card-actions>
            <Btn
                variant="flat"
            >
                <Link :href="route('averbacao.create', props.imovel.inscricao_municipal)">
                    Adicionar
                </Link>
            </Btn>

            <Btn
                variant="tonal"
                 @click="emit('close')"
            >
                Fechar
            </Btn>
        </v-card-actions>
    </v-card>
</template>