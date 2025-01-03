<script setup>
    const onlyNumbers = (evt) => {
        // Teclas permitidas no input
        const allowedKeys = [
            'Backspace',
            'Delete', 
            'ArrowLeft',
            'ArrowRight',
            'Tab'
        ]

        // Analisa caractere por caractere 
        const isNumber = /^[0-9]$/.test(evt.key)

        if (!isNumber && !allowedKeys.includes(evt.key)) {
            evt.preventDefault()
        }
    }

</script>

<template>
    <v-text-field  
        @keydown="onlyNumbers"
    />
</template>