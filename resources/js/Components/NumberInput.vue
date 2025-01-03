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

        const pontoDecimal = evt.key === '.';

        if (!isNumber && !allowedKeys.includes(evt.key) && !pontoDecimal) {
            evt.preventDefault()
        }

        // Impedir a inserção de mais um ponto
        const inputValue = evt.target.value
        if (pontoDecimal && inputValue.includes('.')) {
            evt.preventDefault()
        }

        // Permite até duas casas decimais        
        const [inteiro, decimal] = inputValue.split('.')
        if (decimal && decimal.length >= 2) {
            evt.preventDefault()
        }
        
    }

</script>

<template>
    <v-text-field  
        @keydown="onlyNumbers"
    />
</template>