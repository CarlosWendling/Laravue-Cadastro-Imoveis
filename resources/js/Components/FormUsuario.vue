<script setup>
    import { useForm } from 'laravel-precognition-vue-inertia';
    import { ref, watch } from 'vue'
    import { router } from '@inertiajs/vue3'
    import NumberInput from '@/Components/NumberInput.vue'

    const props = defineProps ({
        usuarioAtual: Object,
        usuario: Object,
        method: String,
        route: String,
        textBtn: String,
    })

    let data = {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        perfil: '',
        cpf: '',
        ativo: 'S',
    };

    if (props.usuario != null) {
        data = {
            id: props.usuario.id,
            name: props.usuario.name,
            email: props.usuario.email,
            password: null,
            password_confirmation: null,
            perfil: props.usuario.perfil,
            cpf: props.usuario.cpf,
            ativo: props.usuario.ativo,
        }
    }

    const form = useForm(props.method, props.route, data)

    const situacaoAtual = ref(props.usuario?.ativo)

    const situacao = [
        { label: 'Ativo', value: 'S' },
        { label: 'Inativo', value: 'N' }
    ]

    watch(situacaoAtual, value => {
        form.ativo = value
    })

    const perfil = ref(props.usuario?.perfil)
    
    const perfilsTI = [
        { label: 'Administrador da TI', value: 'T' },
        { label: 'Administrador do sistema', value: 'S' },
        { label: 'Atendente', value: 'A' }
    ]

    const perfilsSistema = [
        { label: 'Atendente', value: 'A' }
    ]

    watch(perfil, value => {
        form.perfil = value
    })

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

    const submit = () => {
        form.submit(form, {
            onFinish: () => form.reset('password', 'password_confirmation'),
        })
    };
</script>

<template>    
    <Head title="Register" />

    <form @submit.prevent="submit">
        <v-container>
            <v-row class="pl-3 pt-6 pb-1">
                <h1 v-if="props.textBtn == 'Cadastrar'" class="text-2xl">Cadastrar Usuário</h1>
                <h1 v-if="props.textBtn == 'Atualizar'" class="text-2xl">Editar Usuário</h1>
                
            </v-row>

            <v-row>
                <v-col
                    cols="12"
                    md="6"
                >
                    <v-text-field 
                        label="Nome Completo"
                        v-model="form.name"
                        required
                        @change="form.validate('name')"
                        :error-messages="form.errors.name"
                    />
                </v-col>

                <v-col
                    cols="12"
                    md="6"
                >
                    <v-text-field 
                        label="Email"
                        v-model="form.email"
                        required
                        @change="form.validate('email')"
                        :error-messages="form.errors.email"
                        :disabled="props.textBtn == 'Atualizar'"
                    />
                </v-col>
            </v-row>

            <v-row>
                <v-col
                    cols="12"
                    md="6"
                >
                    <v-text-field
                        v-if="props.textBtn == 'Cadastrar'"
                        label="Senha"
                        v-model="form.password"
                        type="password"
                        
                        @change="form.validate('password')"
                        :error-messages="form.errors.password"
                    />
                </v-col>

                <v-col
                    cols="12"
                    md="6"
                >
                    <v-text-field
                        v-if="props.textBtn == 'Cadastrar'"
                        label="Confirme a senha"
                        v-model="form.password_confirmation"
                        type="password"
                        
                        @change="form.validate('password_confirmation')"
                        :error-messages="form.errors.password_confirmation"
                    />
                </v-col>
            </v-row>

            <v-row>
                <v-col
                    cols="12"
                    md="6"
                >
                    <v-select
                        v-if="props.usuarioAtual?.perfil == 'T'"
                        label="Perfil"
                        v-model="perfil"
                        :items="perfilsTI"
                        item-title="label"
                        item-value="value"
                        required
                        @change="form.validate('perfil')"
                        :error-messages="form.errors.perfil"
                        clearable
                    />

                    <v-select
                        v-if="props.usuarioAtual?.perfil == 'S'"
                        label="Perfil"
                        v-model="perfil"
                        :items="perfilsSistema"
                        item-title="label"
                        item-value="value"
                        required
                        @change="form.validate('perfil')"
                        :error-messages="form.errors.perfil"
                        clearable
                    />
                </v-col>

                <v-col
                    cols="12"
                    md="6"
                >
                    <NumberInput
                        label="CPF"
                        v-model="cpfFront"
                        @keydown="cpfDinamico()"
                        maxlength="14"
                        required
                        @change="form.validate('cpf')"
                        :error-messages="form.errors.cpf"
                        :disabled="props.textBtn == 'Atualizar'"
                    />
                </v-col>

            </v-row>
            
            <v-row 
                class="flex justify-between items-center"
                :class="{'pb-5' : props.textBtn == 'Cadastrar'}"
            >
                <v-col
                    cols="12"
                    md="6"
                >
                    <v-select 
                        v-if="props.textBtn == 'Atualizar'"
                        label="Situação"
                        v-model="situacaoAtual"
                        :items="situacao"
                        item-title="label"
                        item-value="value"
                        required
                        @change="form.validate('perfil')"
                        :error-messages="form.errors.perfil"
                        clearable
                    />
                </v-col>

                <div>
                    <Btn
                        v-if="props.textBtn == 'Atualizar'"
                        variant="tonal"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        class="mr-3"
                    >
                        <Link :href="`/usuarios/nova-senha/${props.usuario.id}`">
                            Mudar senha
                        </Link>
                    </Btn>
    
                    <Btn
                        type="submit"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        {{ props.textBtn }}
                    </Btn>
                </div>

            </v-row>
        </v-container>
    </form>
</template>