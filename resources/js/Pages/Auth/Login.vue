<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <v-container>
                <v-row>
                    <v-text-field 
                        label="Email"
                        v-model="form.email"
                        required
                        :error-messages="form.errors.email"
                    />
                </v-row>

                <v-row class="mt-4">
                    <v-text-field 
                        label="Senha"
                        v-model="form.password"
                        required
                        :error-messages="form.errors.password"
                    />
                </v-row>
            </v-container>

            <div class="mt-4 block">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600"
                        >Permanecer conectado</span
                    >
                </label>
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Btn
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    type="submit"
                >
                    Log in
                </Btn>
            </div>
        </form>
    </GuestLayout>
</template>
