<script setup>
    import Layout from '@/Layouts/Layout.vue';

    const props = defineProps ({
        pessoas: Object
    })

</script>

<template>
    <Head title="Pessoas" />

    <Layout>
        <div class="flex justify-between py-7 border-b-2 items-center">
            <h1 class="text-2xl">Pessoas</h1>

            <v-btn color="primary">
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
    </Layout>
</template>