<script setup>
    import DecNumberInput from './DecNumberInput.vue'
    import { useForm } from 'laravel-precognition-vue-inertia'
    import { ref, watch } from 'vue'

    const props = defineProps ({
        imovel: Object,
        averbacao: Object,
        method: String,
        route: String,
    })

    let averbacao = {
        evento: null,
        medida: null,
        descricao: null,
        inscricao_municipal_imovel: props.imovel?.inscricao_municipal,
        data_averbacao: null,
    }

    if (props.averbacao != null) {
        averbacao = {
            id: props.averbacao.id,
            evento: props.averbacao.evento,
            medida: props.averbacao.medida,
            descricao: props.averbacao.descricao,
            inscricao_municipal_imovel: props.averbacao.inscricao_municipal_imovel,
            data_averbacao: props.averbacao.data_averbacao,
        }
    }

    const form = useForm(props.method, props.route, averbacao)

    const eventos = [
        'Aumento Área Construída',
        'Redução Área Construída',
        'Observação',
        'Cancelamento',
        'Reativação',
    ]

    const evento = ref(form.evento)
    
    watch (evento, value  => {
        form.evento = value
        
        if (value != 'Aumento Área Construída' && value != 'Redução Área Construída') {
            form.medida = null
        }
    })

    const formatDate = (dateString) => {
        let [ano, mes, dia] = dateString.split('-')
        return `${dia}/${mes}/${ano}`
    }

    const formatDateBack = (dateString) => {
        const options = {year: 'numeric', month: '2-digit', day: '2-digit'}
        
        const data = new Date(dateString)
        .toLocaleDateString('en-CA', options)
        
        return `${data}`
    }

    const formatAreaFront = (area) => {
        if (area == null) return ''

        return `${area} m²`
    }

    const formatAreaBack = (area) => {
        if (typeof area !== 'string') return area

        return area?.replace(' m²', '').trim()
    }

    let medidaFront = ref(formatAreaFront(form.medida))
    let isEditing = ref(false)

    watch(medidaFront, (value) => {
        form.medida = formatAreaBack(value)
    })

    const handleBlur = () => {
        isEditing.value = false

        if (medidaFront.value !== 'm²' && medidaFront.value !== '') {
            medidaFront.value = formatAreaFront(form.medida) 
        }
    }

    const handleFocus = () => {
        isEditing.value = true
        medidaFront.value = formatAreaBack(medidaFront.value)
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
                        :disabled="props.method != 'post'"
                    />                    
                </v-col>

                <v-col
                    cols="12"
                    md="6"
                >
                    <DecNumberInput 
                        label="Medida"
                        :disabled="form.evento != 'Aumento Área Construída' &&  form.evento != 'Redução Área Construída' || props.method != 'post'"
                        v-model="medidaFront"
                        @focus="handleFocus" 
                        @blur="handleBlur"
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
                        :disabled="props.method != 'post'"
                    />
                </v-col>

                <v-col
                    cols="12"
                    md="4"
                >
                    <v-text-field
                        v-if="props.method != 'post'"
                        disabled
                    >
                        {{ formatDate(form.data_averbacao) }}
                    </v-text-field>
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
                        v-if="props.method == 'post'"
                    >
                        Salvar
                    </Btn>
                </v-col>
            </v-row>
        </v-container>
    </v-form>
</template>