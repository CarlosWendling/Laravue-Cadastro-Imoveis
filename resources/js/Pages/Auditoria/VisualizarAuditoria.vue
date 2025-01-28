<script setup>
    const props = defineProps ({
        auditoria: Object,
        dados_anteriores: Object,
        dados_novos: Object,
        errors: Object,
        auth: Object,
        flash: Object
    })

    const formatEvento = (value) => {
        if (value == 'created') return 'Criação'
        if (value == 'updated') return 'Atualização'
        if (value == 'deleted') return 'Exclusão'
    }

    const formatCpfFront = (cpf) => {
        cpf = cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4')
        return cpf
    }

    const formatDate = (dateString) => {
        let [ano, mes, dia] = dateString.split('-')
        return `${dia}/${mes}/${ano}`
    }

    const formatTelFront = (tel) => {
        if (tel == null) return ''

        tel = tel.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3')

        return tel
    }

    const formatTabela = (value) => {
        let tabela = value.replace('App\\Models\\', '') + 's'

        if (tabela == 'Users') 
            return 'Usuários'
        else if (tabela == 'Averbacaos')
            return 'Averbações'
        else if (tabela == 'Imovels')
            return 'Imóveis'

        return tabela
    }

    const formatKey = (value) => {
        if (!value) return ''

        // Mapeamento dos valores
        const keyMappings = {
            name: 'Nome',
            password: 'Senha',
            ativo: 'Situação',
            situacao: 'Situação',
            data_averbacao: 'Data Averbação',
            inscricao_municipal_imovel: 'Inscrição Municipal',
            area_edificacao: 'Área Edificação',
            area_terreno: 'Área Terreno',
            numero: 'Número',
        }

        // Se for uma chave do mapeamento, vai retorná-la
        if (keyMappings[value]) return keyMappings[value]

        // Tratamento das chaves padrões
        return value
            .replaceAll('_', ' ')
            .split(' ')
            .map(word => word.charAt(0).toUpperCase() + word.slice(1))
            .join(' ')
    }

    const formatValue = (value, key) => {
        if (value == null) return '--------'

        const valueMappings = {
            T: 'Administrador da TI',
            S: key === 'perfil' ? 'Administrador do sistema' : 'Ativo',
            A: 'Atendente',
            N: key === 'ativo' ? 'Inativo' : value,
            1: key === 'situacao' ? 'Ativo' : value,
            0: key === 'situacao' ? 'Inativo' : value,
        };

        if (valueMappings[value]) return valueMappings[value]

        if (key == 'data_averbacao' || key === 'data_nascimento') return formatDate(value)
        if (key == 'telefone') return formatTelFront(value)
        if (key == 'cpf') return formatCpfFront(value)
        if (key == 'password') return '########'
        if (key == 'medida' && value != null) return value + ' m²'

        return value;
    }
</script>

<template>
    <v-table class="pb-8">
        <tbody class="text-base">
            <tr class="text-2xl">
                <td class="pl-3 pt-6 pb-1 mb-3">Auditoria</td>
            </tr>
            <tr>
                <td class="border-2"><span class="font-bold">Id: </span> {{ props.auditoria.id }}</td>
                <td class="border-2"><span class="font-bold">Data e Hora: </span>{{ props.auditoria.data }}</td>
            </tr>
            <tr>
                <td class="border-2"><span class="font-bold">Usuário: </span>{{ props.auditoria.user.name }}</td>
                <td class="border-2"><span class="font-bold">Evento: </span>{{ formatEvento(props.auditoria.evento) }}</td>
            </tr>
            <tr>
                <td class="border-2"><span class="font-bold">Tabela: </span>{{ formatTabela(props.auditoria.tabela) }}</td>
                <td class="border-2"><span class="font-bold">Id Auditado: </span>{{ props.auditoria.id_auditado }}</td>
            </tr>
            <tr>
                <td class="border-2"><span class="font-bold">URL: </span>{{ props.auditoria.url }}</td>
                <td class="border-2"><span class="font-bold">IP: </span>{{ props.auditoria.ip }}</td>
            </tr>
        </tbody>
    </v-table>

    <v-table 
        v-if="props.dados_anteriores.length != 0"
        class="pb-8"
    >
        <tbody class="text-base">
            <tr class="text-2xl">
                <td class="pl-3 pt-6 pb-1 mb-3">Dados Anteriores</td>
            </tr>
            <tr
                v-for="(value, key) in props.dados_anteriores"
                :key="key"
            >
                <td class="border-2"><span class="font-bold">{{ formatKey(key) }} </span>: {{ formatValue(value, key) }}</td>
            </tr>
        </tbody>
    </v-table>

    <v-table 
        v-if="props.dados_novos.length != 0"
        class="pb-8"
    >
        <tbody class="text-base">
            <tr class="text-2xl">
                <td class="pl-3 pt-6 pb-1 mb-3">Dados Novos</td>
            </tr>
            <tr
                v-for="(value, key) in props.dados_novos"
                :key="key"
            >
                <td class="border-2"><span class="font-bold">{{ formatKey(key) }} </span>: {{ formatValue(value, key) }}</td>
            </tr>
        </tbody>
    </v-table>
</template>