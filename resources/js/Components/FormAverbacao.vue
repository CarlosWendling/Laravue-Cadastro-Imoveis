<script setup>
    import DecNumberInput from './DecNumberInput.vue'
    import { useForm } from 'laravel-precognition-vue-inertia'
    import { ref, watch } from 'vue'

    const props = defineProps ({
        imovel: Object,
        method: String,
        route: String,
    })

    let averbacao = {
        evento: null,
        medida: null,
        descricao: null,
        inscricao_municipal_imovel: props.imovel.inscricao_municipal,
        data_averbacao: null,
    }

    const form = useForm(props.method, props.route, averbacao)

    const eventos = [
        'Aumento Área Construída',
        'Redução Área Construída',
        'Observação',
        'Cancelamento',
        'Reativação',
    ]

    const evento = ref(null)
    
    watch (evento, value  => {
        form.evento = value
        
        if (value != 'Aumento Área Construída' && value != 'Redução Área Construída') {
            form.medida = null
        }
    })

    const formatDateBack = (dateString) => {
        const options = {year: 'numeric', month: '2-digit', day: '2-digit'}
        
        const data = new Date(dateString)
        .toLocaleDateString('en-CA', options)
        
        return `${data}`
    }

    const submit = () => {
        form.data_averbacao = formatDateBack(new Date())

        form.submit(form)
    }
</script>

<template>
    <v-form @submit.prevent="submit">
        <v-container>
            <v-row class="pt-6 mb-1 text-2xl">
                <h1>Averbações</h1>
            </v-row>

            <v-row>
                <v-col
                    cols="12"
                    md="6"
                >
                    <v-select
                        label="Evento"
                        v-model="evento"
                        required
                        :items="eventos"
                        clearable
                        @change="form.validate('evento')"
                        :error-messages="form.errors.evento"
                    />                    
                </v-col>

                <v-col
                    cols="12"
                    md="6"
                >
                    <DecNumberInput 
                        label="Medida"
                        :disabled="form.evento != 'Aumento Área Construída' &&  form.evento != 'Redução Área Construída'"
                        v-model="form.medida"
                        @change="form.validate('medida')"
                        :error-messages="form.errors.medida"
                    />
                </v-col>
            </v-row>

            <v-row>
                <v-col
                    cols="12"
                    md="6"
                >
                    <v-textarea 
                        label="Descrição"
                        v-model="form.descricao"
                        required
                        @change="form.validate('descricao')"
                        :error-messages="form.errors.descricao"
                    />
                </v-col>

                <v-col
                    cols="12"
                    md="6"
                    class="flex place-content-end place-items-end"
                >       
                    <Btn
                        class="mb-4"
                        type="submit"
                        :disabled="form.processing"
                    >
                        Salvar
                    </Btn>
                </v-col>
            </v-row>
        </v-container>
    </v-form>
</template>