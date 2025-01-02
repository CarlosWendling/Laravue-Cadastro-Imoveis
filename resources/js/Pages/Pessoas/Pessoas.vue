<script setup>
    import { ref } from 'vue'
    import { router } from '@inertiajs/vue3'
    import Pagination from '@/Components/Pagination.vue'
    import Filtro from '@/Components/Filtro.vue'

    const props = defineProps ({
        pessoas: Object,
        errors: Object,
        auth: Object,
        flash: Object
    })

    // Formatar a data de nascimento para o formato brasileiro
    const formatDate = (dateString) => {
        let [ano, mes, dia] = dateString.split('-')
        return `${dia}/${mes}/${ano}`
    }

    // Formatar o CPF
    const formatCpfFront = (cpf) => {
        cpf = cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4')
        return cpf
    }

    // Exclusão de cadastro
    const isDialogOpen = ref(false)

    let pessoaExcluir = {
        id: null,
        nome: null
    }

    const excluir = (id) => {
        router.delete(`/pessoa/destroy/${id}`)
        isDialogOpen.value = false
    }

</script>

<template>
    <Head title="Pessoas" />
    
    <div class="flex justify-between py-5 border-b-2 items-center">
        <h1 class="text-2xl">Pessoas</h1>

        <v-dialog
            v-model="isDialogOpen"
            width="450px"
            attach="body"
        >
            <v-card class="pt-2 pb-1 px-3">
                <div class="flex justify-center">
                    <v-card-title>Deseja excluir a pessoa: {{ pessoaExcluir.nome }}?</v-card-title>
                </div>

                <v-card-actions>
                    <Btn
                        @click="isDialogOpen = false"
                        variant="tonal"
                    >
                        Cancelar
                    </Btn>

                    <Btn
                        @click="excluir(pessoaExcluir.id)"
                        variant="flat"
                    >
                        Excluir
                    </Btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <div class="flex items-center">
            
            <Filtro />
            
            <Btn class="ml-3">
                <Link href="/pessoas/cadastro">
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
                <th>CPF</th>
                <th>Data Nasc</th>
                <th>Sexo</th>
                <th class="flex justify-center items-center pr-12">Ações</th>
            </tr>
        </thead>
        <tbody>
            <tr v-if="props.pessoas.length == 0">
                <td>Nenhuma pessoa cadastrada</td>
            </tr>
            <tr 
                v-for="pessoa in props.pessoas.data"
                :key="pessoa.id"
            >
                <td>{{ pessoa.id }}</td>
                <td>{{ pessoa.nome }}</td>
                <td>{{ formatCpfFront(pessoa.cpf) }}</td>
                <td>{{ formatDate(pessoa.data_nascimento) }}</td>
                <td>{{ pessoa.sexo }}</td>
                <td class="flex items-center">
                    <Btn
                        variant="tonal"
                        prepend-icon="mdi-pencil"
                        class="mr-2"
                    >
                        <Link :href="route('pessoa.show', pessoa.id)">
                                Visualizar
                        </Link>
                    </Btn>
                    <Btn
                        variant="tonal"
                        prepend-icon="mdi-delete"
                        @click="isDialogOpen = true; pessoaExcluir.id = pessoa.id; pessoaExcluir.nome = pessoa.nome"
                    >
                        Excluir
                    </Btn>
                </td>
            </tr>
        </tbody>
    </v-table>
    <!--Paginator-->
    <Pagination 
        :links="props.pessoas.links"
        class="mt-6 pb-2"
    />

</template>