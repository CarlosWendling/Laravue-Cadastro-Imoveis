<script setup>
    import NumberInput from '@/Components/NumberInput.vue'
    import { useForm } from '@inertiajs/vue3'

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
                    <NumberInput 
                        label="Área do Terreno" 
                    />
                </v-col>

                
                <v-col
                    cols="12"
                    md="4"
                >
                    <NumberInput 
                        label="Área da Edificação"
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