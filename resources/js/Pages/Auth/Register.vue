<script setup>
    import { useForm } from 'laravel-precognition-vue-inertia';
    import { ref, watch } from 'vue'
    import NumberInput from '@/Components/NumberInput.vue'

    let data = {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        perfil: '',
        cpf: '',
        ativo: 'N',
    };

    const form = useForm('post', route('register'), data)

    const perfil = ref(null)
    const perfils = [
        'Administrador da TI',
        'Administrador do sistema',
        'Atendente',
    ]

    watch(perfil, value => {
        if (value == 'Administrador da TI') {
            form.perfil = 'T'
        } else if (value == 'Administrador do sistema') {
            form.perfil = 'S'
        } else {
            form.perfil = 'A'
        }
    })

    const formatCpfBack = (cpf) => {
            cpf = cpf.replace(/\D/g, '')

            return cpf
    }

    let cpfFront = ref(form.cpf)
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
                <h1 class="text-2xl">Cadastro Usuário</h1>
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
                    />
                </v-col>
            </v-row>

            <v-row>
                <v-col
                    cols="12"
                    md="6"
                >
                    <v-text-field 
                        label="Senha"
                        v-model="form.password"
                        type="password"
                        required
                        @change="form.validate('password')"
                        :error-messages="form.errors.password"
                    />
                </v-col>

                <v-col
                    cols="12"
                    md="6"
                >
                    <v-text-field 
                        label="Confirme a senha"
                        v-model="form.password_confirmation"
                        type="password"
                        required
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
                        label="Perfil"
                        v-model="perfil"
                        :items="perfils"
                        required
                        @change="form.validate('perfil')"
                        :error-messages="form.errors.perfil"
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
                    />
                </v-col>

            </v-row>
            
            <v-row class="flex justify-end pb-5">                
                <Btn
                    type="submit"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Cadastrar
                </Btn>
            </v-row>
        </v-container>
    </form>
</template>
