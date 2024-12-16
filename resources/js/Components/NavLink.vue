<script setup>
    const props = defineProps({
        active: Boolean
    })
</script>

<template>
    <Link
    class="text-black hover:underline"
    :class="{'font-bold underline': active}"
    >
        <slot />
    </Link>
</template>