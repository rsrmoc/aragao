<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style>
            * { font-family: Arial, Helvetica, sans-serif; }
            @page { margin: 200px 32px; }
            table { width: 100% }
            .space-table-cell-content { display: inline-block; padding: 4px 10px; font-size: .9rem }
            .table-font-small th, .table-font-small td { font-size: .9rem }
            .pagenum:before { content: counter(page); }
            header { position: fixed; top: -180px; width: 100%; }
            footer { position: fixed; bottom: -180px; width: 100%; }

            #tabela-etapas th { background-color: #cfcfcf; padding: 6px 12px; }
            #tabela-etapas td { padding: 6px 12px; }
        </style>
    </head>
    <body>
        <header>
            <table>
                <tbody>
                    <tr>
                        <td>
                            <img src="data:image/webp;base64,{{ base64_encode(file_get_contents(public_path('images/big_logo.webp'))) }}" style="width: 160px" />
                        </td>
            
                        <td style="text-align: right">
                            <h1>Aragão Construtora</h1>
                        </td>
                    </tr>
                </tbody>
            </table>
        </header>

        <hr />

        <section style="margin: 20px 0">
            <h3>{{ $obra->nome }} | {{ $porcGeral }}%</h3>
            <table>
                <tbody>
                    <tr>
                        <td>
                            <div class="space-table-cell-content">
                                <strong>Codigo:</strong>
                                <span>#{{ $obra->id }}</span>
                            </div>
                            <div class="space-table-cell-content">
                                <strong>Nome:</strong>
                                <span>{{ $obra->nome }}</span>
                            </div>
                            <div class="space-table-cell-content">
                                <strong>Status:</strong>
                                <span>{{ $obra->status }}</span>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <div class="space-table-cell-content">
                                <strong>Data de início:</strong>
                                <span>{{ date_format(date_create($obra->dt_inicio), 'd/m/Y') }}</span>
                            </div>
                            <div class="space-table-cell-content">
                                <strong>Data de previsão:</strong>
                                <span>{{ date_format(date_create($obra->dt_previsao_termino), 'd/m/Y') }}</span>
                            </div>
                            <div class="space-table-cell-content">
                                <strong>Data de término:</strong>
                                <span>{{ $obra->dt_termino ? date_format(date_create($obra->dt_termino), 'd/m/Y') : 'Não definido' }}</span>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <div class="space-table-cell-content">
                                <strong>Valor:</strong>
                                <span>{{ App\Services\Helpers\MoneyService::formatToUICurrency($obra->valor) }}</span>
                            </div>
                            <div class="space-table-cell-content">
                                <strong>Saldo:</strong>
                                <span>{{ App\Services\Helpers\MoneyService::formatToUICurrency($obra->valor_saldo) }}</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <h3>Etapas da obra</h3>

            <table id="tabela-etapas" class="table-font-small">
                <thead>
                    <tr>
                        <th style="text-align: left">Nome</th>
                        <th style="text-align: left">Progresso</th>
                        <th style="text-align: left">Geral</th>
                        <th style="text-align: left">Status</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($etapas as $item)    
                        <tr>
                            <td>{{ $item->nome }}</td>
                            <td>{{ $item->porc_etapa }}%</td>
                            <td>{{ $item->porc_geral }}%</td>
                            <td>{{ $item->concluida ? 'Concluída': 'Em andamento' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        <footer>
            <table>
                <tbody>
                    <tr>
                        <td>
                            <span>&copy; Aragão Construtora {{ now()->year }}</span>
                        </td>
            
                        <td style="text-align: right">
                            <span class="pagenum"></span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </footer>
    </body>
</html>