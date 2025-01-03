<script setup>
    import { ref, watch } from 'vue'
    import { router, useForm } from '@inertiajs/vue3'
    import NumberInput from './NumberInput.vue'
    import throttle from 'lodash/throttle'

    const props = defineProps({
        filtros: Array,
        routeName: String
    })

    const sexo = [
        'Masculino',
        'Feminino',
        'Outro',
    ]

    const form = useForm({
        campo: "",
        pesquisa: ""
    })

    let campo = ref(null)
    watch(campo, value => {
        if (value == "Data de Nascimento") {
            form.campo = "data_nascimento"
        } else {
            form.campo = campo.value
        }
    })

    // Search cpf
    const formatCpfBack = (cpf) => {
        if (cpf == null) return ''

        cpf = cpf.replace(/\D/g, '')

        return cpf
    }

    let searchCpf = ref('')

    const cpfDinamico = () => {
        if(searchCpf.value.length == 3 || searchCpf.value.length == 7){    
            searchCpf.value += '.'
        }else if(searchCpf.value.length == 11) {
            searchCpf.value += '-';
        }
    }
    
    watch (searchCpf, () => {
        search.value = formatCpfBack(searchCpf.value)
    })

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
        search.value = formatDateBack(dataSelecionada.value)
    })

    // Envio da pesquisa
    const search = ref(form.pesquisa)

    watch([campo, search], ([campoValue]) => {
        if (!campoValue) {
            // Reenvia a rota sem filtros quando o campo ou é limpo
            router.get(route(props.routeName), {}, {
                preserveState: true,
                replace: true
            });
            
            // Reinicia os valores do campo e da pesquisa
            campo.value = null
            search.value = ''
        }
    });

    watch(search, throttle(function (value) {
        router.get(route(props.routeName), { campo: form.campo?.toLowerCase(), pesquisa: value }, {
            preserveState: true,
            replace: true
        })
    }, 500))
</script>

<template>
    <v-form class="flex items-center">
        <v-select
            label="Filtrar por"
            :items="props.filtros"
            v-model="campo"
            class="w-32 mr-3"
            clearable
        />

        <v-text-field
            v-if="campo == 'Nome'"
            class="w-64"
            v-model="search"
            clearable
        />

        <NumberInput 
            v-if="campo == 'Cpf'"
            class="w-64"
            v-model="searchCpf"
            @input="cpfDinamico()"
            clearable
            maxlength="14"
        />

        <v-select
            v-if="campo == 'Sexo'"
            class="w-64"
            v-model="search"
            :items="sexo"
            clearable
        />

        <v-menu
            :close-on-content-click="false"
            location="end"
            transition="slide-y-transition"
            v-if="campo == 'Data de Nascimento'"
        >
            <template v-slot:activator="{ props }">
                <v-text-field
                    label="Data de Nascimento"
                    prepend-icon="mdi-calendar-today"
                    class="w-64"
                    v-bind="props"
                    :model-value="dataFront"
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

        <Btn type="submit" style="display: none" />
    </v-form>
</template>