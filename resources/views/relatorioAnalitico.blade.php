<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório Analítico Imóveis</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            width: 100%;
            text-align: center;
            font-family: 'Arial';
        }

        header {
            display: block;
            margin-top: 4rem;
        }

        header h3 {
            margin: 0.5rem 0 0.2rem;
        }

        main {
            padding-bottom: 2.5rem; 
        }

        main h3 {
            margin: 3rem 0;
        }

        table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:nth-child(odd) {
            background-color: #ffffff;
        }

        th, td {
            vertical-align: middle;
            border: 1px solid #ddd;
            padding: 14px;
            text-align: left;
        }

        table span {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header>
        <img 
            src="{{ public_path('storage/imagens/Prefeitura_Sao_Leopoldo-logo-png.png') }}" 
            alt="Brasão Prefeitura de São Leopoldo" 
            width="75px"  
        >

        <h3>PREFEITURA MUNICIPAL DE SÃO LEOPOLDO</h3>

        <h4>Estado do Rio Grande do Sul</h4>
    </header>

    <main>
        <h3>RELATÓRIO DETALHADO DE IMÓVEL</h3>

        <table>
            <tbody>
                <tr>
                    <td><span>Inscrição: </span>{{ $imovel['inscricao_municipal'] }}</td>
                    <td><span>Situação: </span>{{ $imovel['situacao'] == 1 ? 'Ativo' : 'Inativo' }}</td>
                </tr>
                <tr>
                    <td><span>Contribuinte: </span>{{ $imovel['pessoa']['nome'] }}</td>
                    <td><span>Tipo: </span>{{ $imovel['tipo'] }}</td>
                </tr>
                <tr>
                    <td><span>Bairro: </span>{{ $imovel['bairro'] }}</td>
                    <td><span>Logradouro: </span>{{ $imovel['logradouro'] }}</td>
                </tr>
                <tr>
                    <td><span>Número: </span>{{ $imovel['numero'] }}</td>
                    <td><span>Complemento: </span>{{ $imovel['complemento'] }}</td>
                </tr>
                <tr>
                    <td><span>Área do Terreno: </span>{{ $imovel['area_terreno'] }} m²</td>
                    <td><span>Área da Edificação: </span>{{ $imovel['area_edificacao'] }} m²</td>
                </tr>
            </tbody>
        </table>

        <h3>AVERBAÇÕES</h3>
        @if (count($averbacoes) == 0)
            <p>Nenhuma averbação registrada neste imóvel.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Evento</th>
                        <th>Medida</th>
                        <th>Descrição</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($averbacoes as $averbacao)
                    <tr>
                        <td>{{ date('d/m/Y'), strtotime($averbacao['data_averbacao']) }}</td>
                        <td>{{ $averbacao['evento'] }}</td>
                        <td>{{ $averbacao['medida'] != null ? $averbacao['medida'] . ' m²' : '' }}</td>
                        <td>{{ $averbacao['descricao'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </main>
</body>
</html>