<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style>
            * { font-family: Arial, Helvetica, sans-serif; }
            body { background-color: white; }
            @page { margin: 200px 32px; }
            table { width: 100% }
            .space-table-cell-content { display: inline-block; padding: 4px 10px; font-size: .9rem }
            .table-font-small th, .table-font-small td { font-size: .9rem }
            .pagenum:before { content: counter(page); }
            header { width: 100%; }
            footer { width: 100%; }

            #tabela-etapas th { background-color: #cfcfcf; padding: 6px 12px; font-size: .7rem }
            #tabela-etapas td { padding: 6px 12px; font-size: .7rem }
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
                                <strong>Saldo recebido:</strong>
                                <span>{{ App\Services\Helpers\MoneyService::formatToUICurrency($obra->valor_aberto) }}</span>
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
                        <th style="text-align: left">Inc. etapa</th>
                        <th style="text-align: left">Exec. da etapa</th>
                        <th style="text-align: left">Inc. executada</th>
                        <th style="text-align: left">Val. da etapa</th>
                        <th style="text-align: left">Val. gasto</th>
                        <th style="text-align: left">Situação</th>
                        <th style="text-align: left">Status</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($etapas as $item)    
                        <tr>
                            <td>{{ $item->nome }}</td>
                            <td style="text-align: right">{{ $item->porc_geral }}%</td>
                            <td style="text-align: right">{{ $item->porc_etapa }}%</td>
                            <td style="text-align: right">{{ $item->insidencia_executada }}%</td>
                            <td style="text-align: right">R$ {{ number_format($item->valor_etapa, 2, ',', '.') }}</td>
                            <td style="text-align: right">R$ {{ number_format($item->valor_gasto, 2, ',', '.') }}</td>
                            <td>{{ $item->quitada ? 'Quitado' : 'Em aberto' }}</td>
                            <td>{{ App\Services\Helpers\StatusService::textObraEtapa($item->status) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <h3>Evoluções da obra</h3>

            <table id="tabela-etapas" class="table-font-small">
                <thead>
                    <tr>
                        <th style="text-align: left">Etapa</th>
                        <th style="text-align: left">Data da evolução</th>
                        <th style="text-align: left">Responsável</th>
                        <th style="text-align: left">Descrição</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($evolucoes as $evolucao)    
                        <tr wire:loading.class="active" wire:target="excluirEvolucao({{ $evolucao->id }})">
                            <td>{{ $evolucao->etapa->nome }}</td>
                            <td>{{ date_format(date_create($evolucao->dt_evolucao), 'd/m/Y') }}</td>
                            <td>{{ $evolucao->usuario?->name }}</td>
                            <td>{{ $evolucao->descricao }}</td>
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
                    </tr>
                </tbody>
            </table>
        </footer>
    </body>
</html>
