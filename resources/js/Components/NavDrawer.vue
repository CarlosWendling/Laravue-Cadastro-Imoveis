<script setup>
    import NavDrawerItem from './NavDrawerItem.vue';
    import NavLink from './NavLink.vue'

</script>

<template>
    <NavDrawerItem
        prepend-icon="mdi-home-city"
        class="text-3xl text-gray-300 font-bold"
    >
        CadImob
    </NavDrawerItem>

    <NavDrawerItem
        prepend-icon="mdi-home"
        class="mt-10"
    >
        <NavLink
            :href="route('home')"
            :active="$page.component === 'Home'"
        >
            Home
        </NavLink>
    </NavDrawerItem>

    <NavDrawerItem
        prepend-icon="mdi-folder-account-outline"
    >
        <NavLink
            :href="route('pessoas')"
            :active="$page.component.includes('Pessoa')"
        >
            Pessoas
        </NavLink>
    </NavDrawerItem>
    
    <NavDrawerItem
        prepend-icon="mdi-domain"
    >
        <NavLink
            :href="route('imoveis')"
            :active="$page.component.includes('Imoveis')"
        >
            Imóveis
        </NavLink>
    </NavDrawerItem>
    
    <NavDrawerItem
        prepend-icon="mdi-folder-table-outline"
    >
        <NavLink
            :href="route('usuarios')"
            :active="$page.component.includes('Usuarios') || $page.component.includes('Register')"
        >
            Usuários
        </NavLink>
    </NavDrawerItem>

    <v-list-group>
        <template #activator="{props}">
            <NavDrawerItem
                v-bind="props"
                prepend-icon="mdi-cog"
            >
                Configurações
            </NavDrawerItem>
        </template>

        <NavDrawerItem 
            prepend-icon="mdi-logout"
            >
                <NavLink
                    :href="route('logout')"
                    method="post"
                    as="button"
                >
                    Log out
                </NavLink>
        </NavDrawerItem>
    </v-list-group>
</template>