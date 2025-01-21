<script setup>
    import Filtro from '@/Components/Filtro.vue';
    import Pagination from '@/Components/Pagination.vue'
    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3'

    const props = defineProps ({
        imoveis: Object,
        pessoas: Object,
        filtros: Array,
        routeName: String,
        errors: Object,
        auth: Object,
        flash: Object
    })

    const isDialogOpen = ref(false)

    const formatCpfFront = (cpf) => {
        cpf = cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4')
        return cpf
    }

    const formatSituacao = (num) => {
        if (num == 1) {
            return 'Ativo'
        } else {
            return 'Inativo'
        }
    }

    let imovelExcluir = {
        inscricao_municipal: null,
        contribuinte: null,
        cpfContribuinte: null,
        logradouro: null
    }

    const excluir = (inscricao_municipal) => {
        router.delete(`/imovel/destroy/${inscricao_municipal}`)
        isDialogOpen.value = false
    }
</script>

<template>
    <Head title="Imóveis" />

    <div class="flex justify-between py-5 border-b-2 items-center">
        <h1 class="text-2xl">Imóveis</h1>

        <v-dialog
            v-model="isDialogOpen"
            width="500px"
            attach="body"
        >
            <v-card class="pt-2 pb-1 px-3">
                <v-card-title>Deseja excluir o imóvel: {{ imovelExcluir.logradouro }}?</v-card-title>
                <v-card-subtitle>Contribuinte: {{ imovelExcluir.contribuinte }}</v-card-subtitle>
                <v-card-subtitle>Cpf do contribuinte: {{ formatCpfFront(imovelExcluir.cpfContribuinte) }}</v-card-subtitle>
                <v-card-actions>
                    <Btn
                        @click="isDialogOpen = false"
                        variant="tonal"
                    >
                        Cancelar
                    </Btn>

                    <Btn
                        @click="excluir(imovelExcluir.inscricao_municipal)"
                        variant="flat"
                    >
                        Excluir
                    </Btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <div class="flex items-center">
            
            <Filtro 
                :filtros="props.filtros" 
                :routeName="props.routeName" 
                :pessoas="props.pessoas"
            />
            
            <Btn class="ml-3">
                <Link href="/imoveis/cadastro">
                    Cadastrar
                </Link>
            </Btn>
        </div>
    </div>
    <v-table>
        <thead>
            <tr class="text-base">
                <th>Inscrição Municipal</th>
                <th>Tipo</th>
                <th>Logradouro</th>
                <th>Número</th>
                <th>Bairro</th>
                <th>Contribuinte</th>
                <th>Situação</th>
                <th class="flex justify-center items-center pr-12">Ações</th>
            </tr>
        </thead>
        <tbody>
            <tr v-if="props.imoveis.data.length == 0">
                <td>Nenhum imóvel encontrado</td>
            </tr>
            <tr 
                v-for="imovel in props.imoveis.data" 
                :key="imovel.inscricao_municipal"
            >
                <td class="">{{ imovel.inscricao_municipal }}</td>
                <td>{{ imovel.tipo }}</td>
                <td>{{ imovel.logradouro }}</td>
                <td>{{ imovel.numero }}</td>
                <td>{{ imovel.bairro }}</td>
                <td>{{ imovel.pessoa?.nome }}</td>
                <td>
                    <span
                        class="situacao-visual"
                        :class="imovel.situacao == 1 ? 'ativo' : 'inativo'"
                    >
                    </span>
                    {{ formatSituacao(imovel.situacao) }}
                </td>
                <td class="flex items-center">
                    <Btn
                        variant="tonal"
                        prepend-icon="mdi-pencil"
                        class="mr-2"
                    >
                        <Link
                            :href="route('imovel.show', imovel.inscricao_municipal)"
                        >
                            Visualizar
                        </Link>
                    </Btn>
                    <Btn
                        variant="tonal"
                        prepend-icon="mdi-delete"
                        @click="isDialogOpen = true; 
                                imovelExcluir.inscricao_municipal = imovel.inscricao_municipal; 
                                imovelExcluir.contribuinte = imovel.pessoa?.nome;
                                imovelExcluir.cpfContribuinte = imovel.pessoa?.cpf;
                                imovelExcluir.logradouro = imovel.logradouro"
                    >
                        Excluir
                    </Btn>
                </td>
            </tr>
        </tbody>
    </v-table>

    <Pagination 
        :links="props.imoveis.links"
        class="mt-6 pb-2"
    />
</template>

<style scoped>
    .situacao-visual {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 6px;
        flex-shrink: 0;
    }

    .situacao-visual.ativo {
        background-color: green;
    }

    .situacao-visual.inativo {
        background-color: red;
    }
</style>