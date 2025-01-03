<script setup>
    import Filtro from '@/Components/Filtro.vue';

    const props = defineProps ({
        imoveis: Object,
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
            
            <Filtro :filtros="props.filtros" :routeName="props.routeName" />
            
            <Btn class="ml-3">
                <Link href="#">
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
            <tr v-if="props.imoveis.length == 0">
                <td>Nenhum imóvel cadastrado</td>
            </tr>
        </tbody>
    </v-table>
</template>