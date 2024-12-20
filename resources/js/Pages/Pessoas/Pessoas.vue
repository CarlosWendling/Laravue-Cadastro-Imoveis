<script setup>
    import Layout from '@/Layouts/Layout.vue';

    const props = defineProps ({
        pessoas: Object
    })

    // Formatar a data de nascimento para o formato brasileiro
    const formatDate = (dateString) => {
        let [ano, mes, dia] = dateString.split('-')
        return `${dia}/${mes}/${ano}`
    }

</script>

<template>
    <Head title="Pessoas" />

    <Layout>
        <div class="flex justify-between py-7 border-b-2 items-center">
            <h1 class="text-2xl">Pessoas</h1>

            <Btn>
                <Link href="/pessoas/cadastro">
                    Cadastrar
                </Link>
            </Btn>
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
                <tr v-for="pessoa in props.pessoas">
                    <td>{{ pessoa.id }}</td>
                    <td>{{ pessoa.nome }}</td>
                    <td>{{ pessoa.cpf }}</td>
                    <td>{{ formatDate(pessoa.data_nascimento) }}</td>
                    <td>{{ pessoa.sexo }}</td>
                    <td>
                        <Link :href="route('pessoa.show', pessoa.id)">
                            <v-btn
                                variant="tonal"
                                prepend-icon="mdi-pencil"
                                class="mr-2"
                            >
                                Visualizar
                            </v-btn>
                        </Link>

                        <Link href="#">
                            <v-btn
                                variant="tonal"
                                prepend-icon="mdi-delete"
                            >
                                Excluir
                            </v-btn>
                        </Link>

                    </td>
                </tr>
            </tbody>
        </v-table>
    </Layout>
</template>