<script setup>
    import { ref, watch } from 'vue';
    import { useForm } from '@inertiajs/vue3'
    import NumberInput from './NumberInput.vue';

    let data = {
        nome: null,
        cpf: null,
        data_nascimento: null,
        sexo: null,
        email: null,
        telefone: null
    }

    const props = defineProps ({
        pessoa: Object,
        textBtn: String
    })

    if (props.pessoa != null) {
        data = {
            id: props.pessoa.id,
            nome: props.pessoa.nome,
            cpf: props.pessoa.cpf,
            data_nascimento: props.pessoa.data_nascimento,
            sexo: props.pessoa.sexo,
            email: props.pessoa.email,
            telefone: props.pessoa.telefone
        }
    }

    const form = useForm(data)

    // Date Picker
    const dataSelecionada = ref()
    const dataFront = ref()
    
    const formatDateBack = (dateString) => {
        const options = {year: 'numeric', month: '2-digit', day: '2-digit'}
        
        const data = new Date(dateString)
        .toLocaleDateString('en-CA', options)
        
        return `${data}`
    }
    
    const formatDate = (dateString) => {
        let [ano, mes, dia] = dateString.split('-')
        return `${dia}/${mes}/${ano}`
    }
    
    if (data.data_nascimento != null) {
        dataFront.value = ref(formatDate(data.data_nascimento))
    }

    watch (dataSelecionada, () => {
        dataFront.value = formatDate(formatDateBack(dataSelecionada.value))
        form.data_nascimento = formatDateBack(dataSelecionada.value)

    })

    // CPF
    const formatCpf = (cpf) => {
        if (cpf == null) return ''

        cpf = cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4')

        return cpf
    }

    let useCpf = ref(formatCpf(form.cpf))

    const cpfDinamico = () => {
        if(useCpf.value.length == 3 || useCpf.value.length == 7){    
            useCpf.value += '.'
        }else if(useCpf.value.length == 11) {
            useCpf.value += '-';
        }
    }
    
    watch (useCpf, () => {
        form.cpf = useCpf.value
    })

    // Sexo Select
    const items = [
        'Masculino',
        'Feminino',
        'Outro'
    ]

    // Telefone
    const formatTel = (tel) => {
        if (tel == null) return ''

        tel = tel.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3')

        return tel
    }

    let useTel = ref(formatTel(form.telefone))

    const telDinamico = () => {
        if (useTel.value.length == 0) {
            useTel.value = '('
        } else if (useTel.value.length == 3) {
            useTel.value += ') '
        } else if (useTel.value.length == 10) {
            useTel.value += '-'
        }
    }

    watch (useTel, () => {
        form.telefone = useTel.value
    })

    // Envio do Form
    const submit = () => {
        if (props.textBtn == 'Atualizar') {
            form.put(route('pessoa.update', data.id))
        } else {
            form.post('/pessoas/store', data)
        }
    }

</script>

<template>
    <v-form @submit.prevent="submit">
        <v-container>
            <v-row class="pl-3 pt-6">
                <h1 class="text-2xl">Cadastro Pessoa</h1>
            </v-row>
            <v-row>
                <v-col
                    cols="12"
                    md="8"
                >
                    <v-text-field 
                        v-model="form.nome"
                        label="Nome Completo"
                        required
                        :error-messages="data.errors?.nome"
                    />
                </v-col>

                <v-col
                    cols="12"
                    md="4"
                >
                    <NumberInput 
                        v-model="useCpf"
                        @keydown="cpfDinamico()"
                        label="CPF"
                        minlength="14" 
                        maxlength="14"
                        required
                        :disabled="props.textBtn == 'Atualizar'"
                        :error-messages="data.errors?.cpf"
                    />
                </v-col>
            </v-row>

            <v-row>
                <v-col
                    cols="12"
                    md="5"
                >
                    <v-text-field 
                        v-model="form.email"
                        label="Email"
                        :error-messages="data.errors?.email"
                    />
                </v-col>

                <v-col
                    cols="12"
                    md="4"
                >
                    <v-menu
                        :close-on-content-click="false"
                        location="end"
                        transition="slide-y-transition"
                    >
                        <template v-slot:activator="{ props }">
                            <v-text-field
                                label="Data de Nascimento"
                                prepend-icon="mdi-calendar-today"
                                v-bind="props"
                                :model-value="dataFront"
                                required
                                :error-messages="data.errors?.data_nascimento"
                            />
                        </template>

                        <v-date-picker
                            color="indigo"
                            title="Selecione a data"
                            header="Data de Nasc"
                            v-model="dataSelecionada"
                            hide-weekdays
                            :max="new Date()"
                        >
                            Data de Nascimento
                        </v-date-picker> 
                    </v-menu>
                </v-col>

                <v-col>
                    <v-select 
                        label="Sexo"
                        :items="items"
                        v-model="form.sexo"
                        required
                        :error-messages="data.errors?.sexo"
                    />
                </v-col>
            </v-row>

            <v-row class="items-center justify-between">
                <v-col
                    cols="4"
                >
                    <NumberInput  
                        label="Telefone"
                        placeholder="(00) 00000-0000"
                        minlength="15" 
                        maxlength="15"
                        @keydown="telDinamico"
                        v-model="useTel"
                        :error-messages="data.errors?.telefone"
                    />
                </v-col>

                <Btn
                    :disabled="data.processing"
                    type="submit"
                >
                    {{ textBtn }}
                </Btn>
            </v-row>
        </v-container>
    </v-form>
</template>