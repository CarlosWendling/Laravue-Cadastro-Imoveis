<script setup>
    import NumberInput from '@/Components/NumberInput.vue'
    import DecNumberInput from './DecNumberInput.vue'
    import { useForm } from 'laravel-precognition-vue-inertia'
    import { ref, watch } from 'vue'
    import { router } from '@inertiajs/vue3'

    const props = defineProps({
        imovel: Object,
        pessoas: Array,
        arquivos: Array,
        method: String,
        route: String,
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
        tipo: null,
        files: null,
        situacao: true
    }

    if (props.imovel != null) {
        data = {
            inscricao_municipal: props.imovel[0]?.inscricao_municipal,
            logradouro: props.imovel.logradouro,
            bairro: props.imovel.bairro,
            complemento: props.imovel.complemento,
            numero: props.imovel.numero,
            contribuinte: props.imovel.pessoa_id,
            area_terreno: props.imovel.area_terreno,
            area_edificacao: props.imovel.area_edificacao,
            tipo: props.imovel.tipo,
            files: null,
            situacao: props.imovel.situacao
        }

        if (data.situacao == '1') {
            data.situacao = 'Ativo'
        } else {
            data.situacao = 'Inativo'
        }
    }

    const form = useForm(props.method, props.route, data)

    // Tipo
    const tipos = [
        'Terreno',
        'Casa',
        'Apartamento'
    ]
    const tipo = ref(form.tipo)

    watch(tipo, value => {
        form.tipo = tipo.value
    })    

    // Formatação das areas
    const formatAreaFront = (area) => {
        if (area == null) return ''

        return `${area} m²`
    }

    const formatAreaBack = (area) => {
        if (typeof area !== 'string') return area

        return area?.replace(' m²', '').trim()
    }

    let terrenoFront = ref('')
    let edificacaoFront = ref('')
    let isEditing = ref(false)

    terrenoFront.value = formatAreaFront(form.area_terreno)
    edificacaoFront.value = formatAreaFront(form.area_edificacao)

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

    const handleBlurTipo = () => {
        if (tipo.value == 'Terreno') {
            edificacaoFront.value = '0 m²'
            form.area_edificacao = formatAreaBack(edificacaoFront.value)
        }

        if (tipo.value == 'Apartamento') {
            terrenoFront.value = '0 m²'
            form.area_terreno = formatAreaBack(edificacaoFront.value)
        }
    }

    // Inserção de arquivos
    const isDialogOpen = ref(false)
    const maxFiles = 5

    const maxFilesRule = (value) => {
        
        if (form.files?.length > maxFiles) {
            return `Anexar no máximo ${maxFiles} documentos`
        }

        return true
    }

    const maxSizeRule = (value) => {
        const maxSize = 3 * 1024 * 1024; // 3MB

        if (value && value.length > 0) {
            // Adicionar em uma lista os arquivos que excedem o tamanho permitido
            const oversizedFiles = value.filter((file) => file.size > maxSize)

            if (oversizedFiles.length > 0) {
                // Percorre a lista com o map e extrai o nome, adicionando em uma string
                const oversizedFileNames = oversizedFiles.map((file) => file.name).join(', ')
                return `Os seguintes arquivos excedes o tamanho máximo de 3MB: ${oversizedFileNames}`
            }
        }

        return true
    }

    // Envio do formulário
    const submit = () => {
        if (props.textBtn == 'Atualizar') {
            form.submit(form.inscricao_municipal)
        } else {
            form.submit(form)
        }
    }
</script>

<template>
    <v-form enctype="multipart/form-data" @submit.prevent="submit">
        <v-container>
            <v-row class="pt-6 mb-1 flex items-center justify-between">
                <v-col
                    cols="12"
                    md="3"
                >
                    <h1 v-if="textBtn == 'Cadastrar'" class="text-2xl">Cadastro Imóvel</h1>
                    <h1 v-if="textBtn == 'Atualizar'" class="text-2xl">Atualizar Imóvel</h1>
                </v-col>

                <v-dialog
                    v-model="isDialogOpen"
                    width="500px"
                    attach="body"
                >
                    <v-card class="pt-2 pb-1 px-3">
                        <v-card-title>Adicione arquivos (max: 5)</v-card-title>

                        <v-file-input
                            label="Anexar Documento"
                            v-model="form.files"
                            name="files"
                            accept=".jpg, .jpeg, .png, .pdf"
                            density="compact"
                            counter
                            chips
                            multiple
                            @change="form.validateFiles()"
                            :rules="[maxFilesRule, maxSizeRule]"
                        />
                    
                        
                        <v-card-actions>
                            <Btn
                                @click="isDialogOpen = false"
                                variant="tonal"
                            >
                                Fechar
                            </Btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>

                <div class="">
                    <Btn
                        @click="isDialogOpen = true"
                        variant="tonal"
                    >
                        Adicionar arquivos
                    </Btn>

                    <p
                        v-if="form.files != null"
                        class="text-xs mt-1"
                        :class="{'text-red-600' : form.files?.length > maxFiles}"
                    >
                        Quantidade de arquivos: {{ form.files?.length }}
                    </p>
                    
                </div>
            </v-row>
            <v-row>
                <v-col
                    cols="12"
                    md="8"
                >
                    <v-text-field 
                        label="Logradouro"
                        v-model="form.logradouro"
                        @change="form.validate('logradouro')"
                        :error-messages="form.errors.logradouro"
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
                        @change="form.validate('bairro')"
                        :error-messages="form.errors.bairro"
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
                        @change="form.validate('complemento')"
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
                        @change="form.validate('numero')"
                        :error-messages="form.errors.numero"
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
                    @change="form.validate('contribuinte')"
                    :error-messages="form.errors.contribuinte"
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
                        :disabled="tipo == 'Apartamento'"
                        v-model="terrenoFront"
                        @focus="handleFocus" 
                        @blur="handleBlur"
                        @change="form.validate('area_terreno')"
                        :error-messages="form.errors.area_terreno"
                    />
                </v-col>

                
                <v-col
                    cols="12"
                    md="4"
                >
                    <DecNumberInput
                        label="Área da Edificação"
                        :disabled="tipo == 'Terreno'"
                        v-model="edificacaoFront"
                        @focus="handleFocus" 
                        @blur="handleBlur" 
                        @change="form.validate('area_edificacao')"
                        :error-messages="form.errors.area_edificacao"
                    />
                </v-col>

                <v-col
                    cols="12"
                    md="4"
                >
                    <v-select 
                        label="Tipo"
                        :items="tipos"
                        v-model="tipo"
                        clearable
                        @blur="handleBlurTipo"
                        @change="form.validate('tipo')"
                        :error-messages="form.errors.tipo"
                        required
                    />
                </v-col>
            </v-row>
            <v-row 
                class="flex items-center justify-between"
                :class="{'pb-3' : textBtn == 'Cadastrar'}"
            >
                <v-col
                    cols="12"
                    md="4"
                >
                    <v-select 
                        v-if="textBtn == 'Atualizar'"
                        label="Situação"
                        v-model="form.situacao"
                        disabled
                    />
                </v-col>

                <Btn
                    type="submit"
                    :disable="form.processing"
                >
                    {{ props.textBtn }}
                </Btn>
            </v-row>
        </v-container>
    </v-form>
</template>