<script setup>
    import Layout from '@/Layouts/Layout.vue';

    const props = defineProps ({
        pessoas: Object
    })

</script>

<template>
    <Head title="Pessoas" />

    <Layout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm px-7">
                    <div class="flex justify-between py-7 border-b-2 items-center">
                        <h1 class="text-2xl">Pessoas</h1>

                        <v-btn class="bg-indigo-500">
                            <Link href="/pessoas/cadastro">
                                Cadastrar
                            </Link>
                        </v-btn>
                    </div>
                    <v-table>
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Nome</th>
                                <th>CPF</th>
                                <th>Data Nasc</th>
                                <th>Sexo</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr></tr>
                        </tbody>
                    </v-table>
                </div>
            </div>
        </div>
    </Layout>
</template>