<?php

namespace App\Livewire\Components\Views\Obras;

use App\Models\ObraRelatorio;
use App\Models\Obras;
use App\Models\ObrasEtapas;
use App\Models\ObrasEvolucoes;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;

class EtapasTabRelatorios extends Component
{
    use WithPagination;

    public int $obra;
    public string $path = 'app/public/relatorios/';
    public bool $modal = false;
    public string $formato = "pdf";

    public function filename($csv = false): string {
        $hash = sha1(rand(0, 9999999)).time();

        return $csv ? "$hash.csv" : "$hash.pdf";
    }

    public function gerarRelatorio() {
        $obra = Obras::find($this->obra);
        $etapas = ObrasEtapas::where('id_obra', $this->obra)->orderBy('created_at', 'desc')->get();
        $porcGeral = ObrasEtapas::where('id_obra', $this->obra)->sum('porc_geral');
        $evolucoes = ObrasEvolucoes::with(['etapa', 'usuario'])->where('id_obra', $this->obra)->orderBy('created_at', 'desc')->get();

        try {
            DB::beginTransaction();

            ObraRelatorio::create([
                'id_obra' => $this->obra,
                'id_usuario' => Auth::user()->id,
                'filename' => $this->formato == 'pdf'
                    ? $this->gerarPdf($obra, $etapas, $porcGeral, $evolucoes)
                    : $this->gerarCSV($obra, $etapas, $porcGeral, $evolucoes)
            ]);

            $this->reset('modal', 'formato');

            DB::commit();

            $this->dispatch('toast-event', 'Relatório gerado!', 'success');
        }
        catch (Exception $e) {
            DB::rollBack();

            $this->dispatch('toast-event', 'Não foi possivel gerar o relatório. '.$e->getMessage(), 'error');
        }
    }

    public function gerarPdf($obra, $etapas, $porcGeral, $evolucoes) {
        $filename = $this->filename();

        $pdf = Pdf::loadView('pdf.obras-relatorios', compact('obra', 'etapas', 'porcGeral', 'evolucoes'));
        $pdf->save(storage_path($this->path.$filename));

        return $filename;
    }

    public function gerarCSV($obra, $etapas, $porcGeral, $evolucoes) {
        $filename = $this->filename(csv: true);
        file_put_contents(storage_path($this->path.$filename), '');

        $file = fopen(storage_path($this->path.$filename), 'w');

        fputcsv($file, ['Aragão Construtora © '.now()->year]);
        fputcsv($file, []);

        fputcsv($file, [
            'Código', 'Nome', 'Data de início', 'Data de previsão',
            'Data de término', 'Valor', 'Saldo', 'Progresso'
        ]);
        fputcsv($file, [
            "#$obra->id",
            $obra->nome,
            date_format(date_create($obra->dt_inicio), 'd/m/Y'),
            date_format(date_create($obra->dt_previsao_termino), 'd/m/Y'),
            $obra->dt_termino ? date_format(date_create($obra->dt_termino), 'd/m/Y') : 'Não definido',
            \App\Services\Helpers\MoneyService::formatToUICurrency($obra->valor),
            \App\Services\Helpers\MoneyService::formatToUICurrency($obra->valor_saldo),
            "$porcGeral%"
        ]);

        fputcsv($file, []);

        fputcsv($file, ['Etapas da obra']);
        fputcsv($file, [
            'Nome',
            'Execução da etapa',
            'Execução da obra',
            'Incidência',
            'Valor gasto',
            'Valor da etapa',
            'Situação',
            'Status'
        ]);
        foreach ($etapas as $etapa) {
            fputcsv($file, [
                $etapa->nome,
                "{$etapa->porc_etapa}%",
                "{$etapa->porc_geral}%",
                "{$etapa->incidencia}%",
                "R$ ".number_format($etapa->valor_gasto, 2, ',', '.'),
                "R$ ".number_format($etapa->valor, 2, ',', '.'),
                $etapa->quitada ? 'Quitado' : 'Em aberto',
                $etapa->concluida ? 'Concluída': 'Em andamento'
            ]);
        }

        fputcsv($file, []);

        fputcsv($file, ['Evoluções da obra']);
        fputcsv($file, [
            'Etapa',
            'Data da evolução',
            'Responsável',
            'Descrição'
        ]);
        foreach ($evolucoes as $evolucao) {
            fputcsv($file, [
                $evolucao->etapa->nome,
                date_format(date_create($evolucao->dt_evolucao), 'd/m/Y'),
                $evolucao->usuario?->name,
                $evolucao->descricao
            ]);
        }

        fclose($file);

        return $filename;
    }

    public function downloadFile(int $idRelatorio) {
        $relatorio = ObraRelatorio::find($idRelatorio);
        $extension = str_contains($relatorio->filename, '.pdf') ? '.pdf' : '.csv';
        
        return response()->download(
            Storage::path('public/relatorios/'.$relatorio->filename),
            'relatorio_'.now().'_'.$relatorio->id.$extension
        );
    }

    public function excluirRelatorio(int $idRelatorio) {
        try {
            ObraRelatorio::find($idRelatorio)->delete();

            $this->dispatch('toast-event', 'Relatório excluído!', 'success');
        }
        catch (Exception $e) {
            $this->dispatch('toast-event', 'Não foi possivel excluir o relatório. '.$e->getMessage(), 'error');
        }
    }

    public function render()
    {
        $relatorios = ObraRelatorio::where('id_obra', $this->obra)->orderBy('created_at', 'desc')->paginate(12);

        return view('livewire.components.views.obras.etapas-tab-relatorios', compact('relatorios'));
    }
}
