<script setup>
    import NumberInput from '@/Components/NumberInput.vue'
    import DecNumberInput from './DecNumberInput.vue'
    import { useForm } from 'laravel-precognition-vue-inertia'
    import { ref, watch, computed } from 'vue'
    import { router } from '@inertiajs/vue3'
    import MensagesArquivos from './MensagesArquivos.vue'
    import axios from 'axios'
    import fileDownload from 'js-file-download'

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
        situacao: true
    }

    if (props.imovel != null) {
        data = {
            inscricao_municipal: props.imovel.inscricao_municipal,
            logradouro: props.imovel.logradouro,
            bairro: props.imovel.bairro,
            complemento: props.imovel.complemento,
            numero: props.imovel.numero,
            contribuinte: props.imovel.pessoa_id,
            area_terreno: props.imovel.area_terreno,
            area_edificacao: props.imovel.area_edificacao,
            tipo: props.imovel.tipo,
            situacao: props.imovel.situacao
        }

        if (data.situacao == '1') {
            data.situacao = 'Ativo'
        } else {
            data.situacao = 'Inativo'
        }
    }

    let arquivos = {
        inscricao_municipal_imovel: null,
        files: null
    }

    if (props.arquivos != null) {
        arquivos = {
            inscricao_municipal_imovel: props.imovel.inscricao_municipal,
            files: props.arquivos.files
        }
    }
    
    const form = useForm(props.method, props.route, data)
    const formArquivos = useForm('post', route('arquivos.store'), arquivos)

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
    const dialogExcluir = ref(false)
    const maxFiles = 5
    const maxSize = 3 * 1024 * 1024; // 3MB
    
    const countFiles = computed(() => {
        return (formArquivos.files?.length || 0) + (props.arquivos?.length || 0);
    });

    const maxFilesRule = () => {
        
        if (countFiles.value > maxFiles) {
            return `Anexar no máximo ${maxFiles} documentos`
        }

        return true
    }

    const maxSizeRule = (value) => {
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

    const submitArquivos = () => {
        isDialogOpen.value = false
        formArquivos.submit(formArquivos)
        formArquivos.files = null
    }

    let arquivoExcluir = {
        id: null,
        nome: null
    }

    const excluirArquivos = (id) => {
        router.delete(`/arquivos/destroy/${id}`)
        dialogExcluir.value = false
    }

    const downloadArquivos = (id, name) => {
        axios
        .get(`/arquivos/download/${id}`, {
            responseType: 'blob',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        })
        .then((response) => {
            fileDownload(response.data, name);
        })
};

    // Envio do formulário
    const submit = () => {
        if (props.textBtn == 'Atualizar') {
            form.submit(form.inscricao_municipal)
        } else {
            const combinedData = {
                ...form.data(),
                files: formArquivos.files
            }

            const combinedDataForm = useForm('post', route('imoveis.store'), combinedData)
            combinedDataForm.submit(combinedDataForm)
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
                    v-model="dialogExcluir"
                    width="600px"
                    attach="body"
                >
                    <v-card class="pt-3 pb-1 px-3">
                        <v-card-title>Deseja excluir o arquivo: {{ arquivoExcluir.nome }} ?</v-card-title>

                        <v-card-actions>
                            <Btn
                                variant="flat"
                                @click="excluirArquivos(arquivoExcluir.id)"
                            >
                                Excluir
                            </Btn>

                            <Btn
                                @click="dialogExcluir = false"
                                variant="tonal"
                            >
                                Fechar
                            </Btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>

                <v-dialog
                    v-model="isDialogOpen"
                    width="600px"
                    attach="body"
                >
                    <v-card class="pt-2 pb-1 px-3">
                        <v-form enctype="multipart/form-data" @submit.prevent="submitArquivos">
                            <v-card-title>Adicione arquivos (max: 5)</v-card-title>

                            <v-file-input
                                label="Anexar Documento"
                                v-model="formArquivos.files"
                                name="files"
                                accept=".jpg, .jpeg, .png, .pdf"
                                density="compact"
                                counter
                                chips
                                multiple
                                @change="form.validateFiles()"
                                :disabled="props.arquivos?.length >= 5"
                                :rules="[maxFilesRule, maxSizeRule]"
                            />

                            <v-table v-if="props. arquivos && props.arquivos?.length != 0">
                                <thead>
                                    <tr>
                                        <th>Arquivos</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        
                                        v-for="arquivo in props.arquivos"
                                        :key="arquivo.id"
                                    >
                                        <td>{{ arquivo.name }}</td>
                                        <td>
                                            <Btn
                                                variant="tonal"
                                                size="small"
                                                @click="dialogExcluir = true;
                                                        arquivoExcluir.id = arquivo.id;
                                                        arquivoExcluir.nome = arquivo.name"
                                            >
                                                Excluir
                                            </Btn>

                                            <Btn
                                                class="ml-2"
                                                variant="tonal"
                                                size="small"
                                                @click="downloadArquivos(arquivo.id, arquivo.name)"
                                            >
                                                Download   
                                            </Btn>
                                        </td>
                                    </tr>
                                </tbody>
                            </v-table>
                            
                            <v-card-actions class="flex justify-end mt-1">
                                <Btn
                                    variant="flat"
                                    v-if="props.textBtn == 'Atualizar'"
                                    :disabled="formArquivos.processing || countFiles > 5"
                                    type="submit"
                                >
                                    Adicionar
                                </Btn>

                                <Btn
                                    @click="isDialogOpen = false"
                                    variant="tonal"
                                >
                                    Fechar
                                </Btn>
                            </v-card-actions>
                        </v-form>
                    </v-card>
                </v-dialog>

                <div>
                    <Btn
                        @click="isDialogOpen = true"
                        variant="tonal"
                    >
                        Adicionar arquivos
                    </Btn>
    
                    <MensagesArquivos 
                        :textBtn="props.textBtn" 
                        :selectedFiles="formArquivos.files" 
                        :storedFiles="props.arquivos"
                        :countFiles="countFiles"
                    />
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
                        :disabled="tipo != 'Casa' && tipo != 'Terreno'"
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
                        :disabled="tipo != 'Casa' && tipo != 'Apartamento'"
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
                    :disabled="form.processing"
                >
                    {{ props.textBtn }}
                </Btn>
            </v-row>
        </v-container>
    </v-form>
</template>