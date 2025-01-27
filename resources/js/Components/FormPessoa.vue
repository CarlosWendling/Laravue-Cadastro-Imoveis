<script setup>
    import { ref, watch } from 'vue';
    import NumberInput from './NumberInput.vue'
    import { useForm } from 'laravel-precognition-vue-inertia';

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
        method: String,
        route: String,
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

    const form = useForm(props.method, props.route, data)

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
    const formatCpfFront = (cpf) => {
        if (cpf == null) return ''

        cpf = cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4')

        return cpf
    }

    const formatCpfBack = (cpf) => {
        cpf = cpf.replace(/\D/g, '')

        return cpf
    }

    let cpfFront = ref(formatCpfFront(form.cpf))
    form.cpf = formatCpfBack(cpfFront.value)

    const cpfDinamico = () => {
        if(cpfFront.value.length == 3 || cpfFront.value.length == 7){    
            cpfFront.value += '.'
        }else if(cpfFront.value.length == 11) {
            cpfFront.value += '-';
        }
    }
    
    watch (cpfFront, () => {
        form.cpf = formatCpfBack(cpfFront.value)
    })

    // Sexo Select
    const items = [
        'Masculino',
        'Feminino',
        'Outro'
    ]

    // Telefone
    const formatTelFront = (tel) => {
        if (tel == null) return ''

        tel = tel.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3')

        return tel
    }

    const formatTelBack = (tel) => {
        tel = tel.replace(/\D/g, '')

        return tel
    }

    let telFront = ref(formatTelFront(form.telefone))
    form.telefone = formatTelBack(telFront.value)

    const telDinamico = () => {
        if (telFront.value.length == 0) {
            telFront.value = '('
        } else if (telFront.value.length == 3) {
            telFront.value += ') '
        } else if (telFront.value.length == 10) {
            telFront.value += '-'
        }
    }

    watch (telFront, () => {
        form.telefone = formatTelBack(telFront.value)
    })

    // Envio do Form
    const submit = () => {
        if (props.textBtn == 'Atualizar') {
            form.submit(form.id)
        } else {
            form.submit(form)
        }
    }

</script>

<template>
    <v-form @submit.prevent="submit">
        <v-container>
            <v-row class="pl-3 pt-6">
                <h1 v-if="textBtn == 'Cadastrar'" class="text-2xl">Cadastro Pessoa</h1>
                <h1 v-if="textBtn == 'Atualizar'" class="text-2xl">Atualizar Pessoa</h1>
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
                        @change="form.validate('nome')"
                        :error-messages="form.errors.nome"
                    />
                </v-col>

                <v-col
                    cols="12"
                    md="4"
                >
                    <NumberInput 
                        v-model="cpfFront"
                        @keydown="cpfDinamico()"
                        label="CPF"
                        maxlength="14"
                        required
                        :disabled="props.textBtn == 'Atualizar'"
                        @change="form.validate('cpf')"
                        :error-messages="form.errors.cpf"
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
                        @change="form.validate('email')"
                        :error-messages="form.errors.email"
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
                                @change="form.validate('data_nascimento')"
                                :error-messages="form.errors.data_nascimento"
                            />
                        </template>

                        <v-date-picker
                            color="indigo"
                            title="Selecione a data"
                            header="Data de Nasc"
                            v-model="dataSelecionada"
                            hide-weekdays
                            @change="form.validate('data_nascimento')"
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
                        @change="form.validate('sexo')"
                        :error-messages="form.errors.sexo"
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
                        v-model="telFront"
                        @change="form.validate('telefone')"
                        :error-messages="form.errors.telefone"
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