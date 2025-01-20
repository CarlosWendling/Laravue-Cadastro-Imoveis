<script setup>
import Btn from '@/Components/Btn.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps ({
    user: Object
})

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update', props.user.id), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
            <v-container>
                <v-row class="pt-3 mb-3">
                    <h1 class="text-2xl">Atualizar a Senha</h1>
                </v-row>

                <v-row>
                    <v-text-field 
                        label="Senha atual"
                        ref="currentPasswordInput"
                        v-model="form.current_password"
                        type="password"
                        :error-messages="form.errors.current_password"
                    />
                </v-row>

                <v-row>
                    <v-text-field 
                        label="Nova senha"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        :error-messages="form.errors.password"
                    />
                </v-row>
                <v-row>
                    <v-text-field 
                        label="Confirme a nova senha"
                        v-model="form.password_confirmation"
                        type="password"
                        :error-messages="form.errors.password_confirmation"
                    />
                </v-row>
            </v-container>

            <div class="flex items-center gap-4 pb-4 justify-end">
                <Btn 
                    :disabled="form.processing"
                    type="submit"
                >
                    Salvar
                </Btn>
            </div>
        </form>
    </section>
</template>
