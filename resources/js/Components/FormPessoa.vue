<script setup>
    import { ref, watch } from 'vue';
    import { useForm } from '@inertiajs/vue3'
    import NumberInput from './NumberInput.vue';

    const data = useForm ({
        nome: null,
        cpf: null,
        dataNascimento: null,
        sexo: null,
        email: null,
        telefone: null
    })

    let erros = ref(0)

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

    watch (dataSelecionada, () => {
        dataFront.value = formatDate(formatDateBack(dataSelecionada.value))
        data.dataNascimento = formatDateBack(dataSelecionada.value)

    })

    const dateRules = [
        value => {
            if (value) return true

            erros.value += 1
            return 'Selecione uma data'
        }
    ]

    // Name
    const nameRules = [
        value => {
            if (value && value.length > 8) return true

            erros.value += 1
            return 'Nome inválido'
        }
    ]

    // CPF
    const cpfRules = [
        value => {
            if (value && value.length == 14) return true

            erros.value += 1
            return 'CPF inválido'
        }
    ]

    const formatCpf = (cpf) => {
        if (cpf == null) return ''

        cpf = cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4')

        return cpf
    }

    let useCpf = ref(formatCpf(data.cpf))

    const cpfDinamico = () => {
        if(useCpf.value.length == 3 || useCpf.value.length == 7){    
            useCpf.value += '.'
        }else if(useCpf.value.length == 11) {
            useCpf.value += '-';
        }
    }
    
    watch (useCpf, () => {
        data.cpf = useCpf.value
    })

    // Email
    const emailRules = [
        value => {
            if (value) {
                if (value.includes('@')) return true

                erros.value += 1
                return 'Email inválido'
            }
            
            return true
        }
    ]

    // Sexo Select
    const items = [
        'Masculino',
        'Feminino',
        'Outro'
    ]

    const selectRules = [
        value => {
            if (value) return true

            erros.value += 1
            return 'Selecione uma opção'
        }
    ]

    // Telefone
    const telRules = [
        value => {
            if (value) {
                if (value.length == 15) return true

                erros.value += 1
                return 'Telefone inválido'
            }
            
            return true
        }
    ]

    const formatTel = (tel) => {
        if (tel == null) return ''

        tel = tel.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3')

        return tel
    }

    let useTel = ref(formatTel(data.telefone))

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
        data.telefone = useTel.value
    })

    // Envio do Form
    const submit = () => {
        if (erros.value == 0) {
            data.post('/pessoas/store', data)
        }
        erros.value = 0
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
                        v-model="data.nome"
                        :rules="nameRules"
                        label="Nome Completo"
                        required
                    />
                </v-col>

                <v-col
                    cols="12"
                    md="4"
                >
                    <NumberInput 
                        v-model="useCpf"
                        @keydown="cpfDinamico()"
                        :rules="cpfRules"
                        label="CPF"
                        minlength="14" 
                        maxlength="14"
                        required
                    />
                </v-col>
            </v-row>

            <v-row>
                <v-col
                    cols="12"
                    md="5"
                >
                    <v-text-field 
                        v-model="data.email"
                        :rules="emailRules"
                        label="Email"
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
                                :rules="dateRules"
                                required
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
                        v-model="data.sexo"
                        :rules="selectRules"
                        required
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
                        :rules="telRules"
                    />
                </v-col>

                <v-btn
                    color="primary"
                    :disabled="data.processing"
                    type="submit"
                >
                    Cadastrar
                </v-btn>
            </v-row>
        </v-container>
    </v-form>
</template>