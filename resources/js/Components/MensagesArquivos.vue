<script setup>
    const props = defineProps({
        textBtn: String,
        selectedFiles: Array,
        countFiles: Number,
        storedFiles: Array
    })

    const maxFiles = 5
    const maxSize = 3 * 1024 * 1024; // 3MB

    const checkOversizedFiles = () => {
        if (props.selectedFiles?.length > 0) {
            const oversizedFiles = props.selectedFiles.filter((file) => file.size > maxSize)

            if (oversizedFiles.length > 0) return true
        }

        return false
    }
</script>

<template>
    <p
        v-if="props.textBtn == 'Cadastrar'"
        class="text-xs mt-1"
        :class="{'text-red-600' : props.countFiles > maxFiles || checkOversizedFiles() }"
    >
        Arquivos selecionados: {{ props.selectedFiles?.length || 0 }}
    </p>

    <p
        v-if="props.textBtn == 'Atualizar'"
        class="text-xs mt-1"
        :class="{'text-red-600' : props.countFiles > maxFiles || checkOversizedFiles() }"
    >
        Arquivos cadastrados: {{ props.storedFiles?.length || 0 }}
    </p>
</template>