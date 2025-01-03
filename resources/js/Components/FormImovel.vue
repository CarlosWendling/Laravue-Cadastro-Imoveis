<script setup>
    import NumberInput from '@/Components/NumberInput.vue'
    import DecNumberInput from './DecNumberInput.vue'
    import { useForm } from '@inertiajs/vue3'
    import { ref, watch } from 'vue'

    const props = defineProps({
        pessoas: Array,
        textBtn: String
    })

    let data = {
        logradouro: null,
        bairro: null,
        complemento: null,
        numero: null,
        contribuinte: null,
        area_terreno: null,
        area_edificacao: null,
        tipo: null
    }

    const form = useForm(data)

    // Tipo
    const tipo = [
        'Terreno',
        'Casa',
        'Apartamento'
    ]

    // Formatação das areas
    const formatAreaFront = (area) => {
        if (area == null || area === null) return ''

        return `${area} m²`
    }

    const formatAreaBack = (area) => {
        return area?.replace(' m²', '').trim()
    }

    let terrenoFront = ref('')
    let edificacaoFront = ref('')
    let isEditing = ref(false)

    terrenoFront.value = form.area_terreno
    edificacaoFront.value = form.area_edificacao

    // Observando o campo de entrada
    watch(terrenoFront, (value) => {
        form.area_terreno = formatAreaBack(value) // Mantém o valor do campo sincronizado no formato para o back
    })

    watch(edificacaoFront, (value) => {
        form.area_edificacao = formatAreaBack(value)
    })

    // Caso tenha algum valor válido no campo, a unidade de medida é adicionada
    const handleBlur = () => {
        isEditing.value = false

        if (terrenoFront.value !== 'm²' && terrenoFront.value !== '') {
            terrenoFront.value = formatAreaFront(form.area_terreno) 
        }

        if (edificacaoFront.value !== 'm²' && edificacaoFront.value !== '') {
            edificacaoFront.value = formatAreaFront(form.area_edificacao) 
        }
    }

    // Enquanto está inserindo o valor, não mostra a unidade de medida
    const handleFocus = () => {
        isEditing.value = true
        terrenoFront.value = formatAreaBack(terrenoFront.value)
        edificacaoFront.value = formatAreaBack(form.area_edificacao)
    }
</script>

<template>
    <v-form>
        <v-container>
            <v-row class="pl-3 pt-6">
                <h1 class="text-2xl">Cadastro Imóvel</h1>
            </v-row>
            <v-row>
                <v-col
                    cols="12"
                    md="8"
                >
                    <v-text-field 
                        label="Logradouro"
                        v-model="form.logradouro"
                        required
                    />
                </v-col>

                <v-col
                    cols="12"
                    md="4"
                >
                    <v-text-field 
                        label="Bairro"
                        v-model="form.bairro"
                        required
                    />
                </v-col>
            </v-row>
            <v-row>
                <v-col
                    cols="12"
                    md="4"
                >
                    <v-text-field 
                        label="Complemento"
                        v-model="form.complemento"
                    />
                </v-col>

                <v-col
                    cols="12"
                    md="4"
                >
                    <NumberInput 
                        label="Número"
                        v-model="form.numero"
                        required
                    />
                </v-col>

                <v-col
                    cols="12"
                    md="4"
                >
                    <v-select 
                    label="Contribuinte"
                    v-model="form.contribuinte"
                    :items="pessoas"
                    item-title="nome"
                    item-value="id"
                    clearable
                    required
                    />
                </v-col>
            </v-row>
            <v-row>
                <v-col
                    cols="12"
                    md="4"
                >
                    <DecNumberInput 
                        label="Área do Terreno" 
                        v-model="terrenoFront"
                        @focus="handleFocus" 
                        @blur="handleBlur" 
                    />
                </v-col>

                
                <v-col
                    cols="12"
                    md="4"
                >
                    <DecNumberInput
                        label="Área da Edificação"
                        v-model="edificacaoFront"
                        @focus="handleFocus" 
                        @blur="handleBlur" 
                    />
                </v-col>

                <v-col
                    cols="12"
                    md="4"
                >
                    <v-select 
                        label="Tipo"
                        :items="tipo"
                        v-model="form.tipo"
                        clearable
                        required
                    />
                </v-col>
            </v-row>
            <v-row class="pb-3 flex justify-end">
                <Btn
                    type="submit"
                >
                    {{ props.textBtn }}
                </Btn>
            </v-row>
        </v-container>
    </v-form>
</template>