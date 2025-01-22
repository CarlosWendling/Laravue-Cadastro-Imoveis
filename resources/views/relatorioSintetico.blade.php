<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório Sintético Imóveis</title>
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
            margin: 4rem 0 3rem;
        }
        
        header h3 {
            margin: 0.5rem 0 0.2rem;
        }

        main {
            padding-bottom: 2.5rem; 
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
            border: 1px solid #ddd;
            padding: 18px 16px 8px 5px;
            text-align: left;
        }

        main h3 {
            margin-bottom: 3rem;
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
        <h3>RELATÓRIO DE IMÓVEIS</h3>

        <table>
            <thead>
                <tr>
                    <th>Inscrição</th>
                    <th>Contribuinte</th>
                    <th>Tipo</th>
                    <th>Logradouro</th>
                    <th>Número</th>
                    <th>Bairro</th>
                    <th>Área do Terreno</th>
                    <th>Área da Edificação</th>
                    <th>Situação</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($imoveis as $imovel)
                <tr>
                    <td>{{ $imovel['inscricao_municipal'] }}</td>
                    <td>{{ $imovel['pessoa']['nome'] }}</td>
                    <td>{{ $imovel['tipo'] }}</td>
                    <td>{{ $imovel['logradouro'] }}</td>
                    <td>{{ $imovel['numero'] }}</td>
                    <td>{{ $imovel['bairro'] }}</td>
                    <td>{{ $imovel['area_terreno'] }} m²</td>
                    <td>{{ $imovel['area_edificacao'] }} m²</td>
                    <td>{{ $imovel['situacao'] == 1 ? 'Ativo' : 'Inativo' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </main>


</body>
</html>