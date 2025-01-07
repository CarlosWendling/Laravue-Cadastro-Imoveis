<script setup>
    import Filtro from '@/Components/Filtro.vue';
    import Pagination from '@/Components/Pagination.vue'

    const props = defineProps ({
        imoveis: Object,
        pessoas: Object,
        filtros: Array,
        routeName: String,
        errors: Object,
        auth: Object,
        flash: Object
    })
</script>

<template>
    <Head title="Imóveis" />

    <div class="flex justify-between py-5 border-b-2 items-center">
        <h1 class="text-2xl">Imóveis</h1>

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
                <th class="flex justify-center items-center pr-12">Ações</th>
            </tr>
        </thead>
        <tbody>
            <!-- CRIAR UM COMPONENTE PARA MENSAGENS DE NENHUM CADASTRO/PESQUISA NÃO ENCONTRADA -->
            <tr v-if="props.imoveis.length == 0">
                <td>Nenhum imóvel cadastrado</td>
            </tr>
            <tr 
                v-for="imovel in props.imoveis.data" 
                :key="imovel.inscricao_municipal"
            >
                <td>{{ imovel.inscricao_municipal }}</td>
                <td>{{ imovel.tipo }}</td>
                <td>{{ imovel.logradouro }}</td>
                <td>{{ imovel.numero }}</td>
                <td>{{ imovel.bairro }}</td>
                <td>{{ imovel.pessoa?.nome }}</td>
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