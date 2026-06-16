<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Phpml\Clustering\KMeans;

class SupplierClustering extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static string $view = 'filament.pages.supplier-clustering';

    protected static ?string $navigationGroup = 'Analisis';

    protected static ?string $navigationLabel = 'Clustering Supplier';

    public function getChart()
    {
        $data = DB::table('supplier')
            ->join('pengeluaran_operasional', 'supplier.kode_supplier', '=', 'pengeluaran_operasional.kode_supplier')
            ->select(
                'supplier.nama_supplier',
                DB::raw('COUNT(pengeluaran_operasional.id) as total_transaksi'),
                DB::raw('SUM(pengeluaran_operasional.jumlah) as total_pengeluaran')
            )
            ->groupBy('supplier.kode_supplier', 'supplier.nama_supplier')
            ->get();

        if ($data->isEmpty()) {
            return ['datasets' => []];
        }

        $items = [];
        $samples = [];

        foreach ($data as $index => $row) {
            $items[$index] = [
                'nama_supplier' => $row->nama_supplier,
                'total_pengeluaran' => (float) $row->total_pengeluaran,
                'total_transaksi' => (float) $row->total_transaksi,
            ];

            $samples[$index] = [
                (float) $row->total_pengeluaran,
                (float) $row->total_transaksi,
            ];
        }

        $scaledSamples = $samples;
        $this->minMaxScale($scaledSamples);

        $jumlahCluster = min(3, count($scaledSamples));

        $kmeans = new KMeans($jumlahCluster);
        $clusters = $kmeans->cluster($scaledSamples);

        $clusterResults = [];

        foreach ($clusters as $clusterIndex => $cluster) {
            $points = [];
            $totalPengeluaranCluster = 0;
            $jumlahDataCluster = 0;

            foreach ($cluster as $clusterSample) {
                foreach ($scaledSamples as $sampleIndex => $scaledSample) {
                    if ($clusterSample === $scaledSample) {
                        $item = $items[$sampleIndex];

                        $points[] = [
                            'x' => $item['total_pengeluaran'],
                            'y' => $item['total_transaksi'],
                            'label' => $item['nama_supplier'],
                        ];

                        $totalPengeluaranCluster += $item['total_pengeluaran'];
                        $jumlahDataCluster++;

                        unset($scaledSamples[$sampleIndex]);
                        break;
                    }
                }
            }

            $rataPengeluaran = $jumlahDataCluster > 0
                ? $totalPengeluaranCluster / $jumlahDataCluster
                : 0;

            $clusterResults[] = [
                'average' => $rataPengeluaran,
                'points' => $points,
            ];
        }

        usort($clusterResults, function ($a, $b) {
            return $a['average'] <=> $b['average'];
        });

        $labels = [
            'Pengeluaran Kecil',
            'Pengeluaran Sedang',
            'Pengeluaran Besar',
        ];

        $colors = [
            '#10B981',
            '#36A2EB',
            '#FF6384',
        ];

        $datasets = [];

        foreach ($clusterResults as $index => $cluster) {
            $datasets[] = [
                'label' => $labels[$index] ?? 'Cluster ' . ($index + 1),
                'data' => $cluster['points'],
                'backgroundColor' => $colors[$index] ?? '#999999',
                'borderColor' => $colors[$index] ?? '#999999',
                'pointRadius' => 7,
                'pointHoverRadius' => 10,
            ];
        }

        return [
            'datasets' => $datasets,
        ];
    }

    private function minMaxScale(array &$samples): void
    {
        $numFeatures = count($samples[0]);

        $mins = array_fill(0, $numFeatures, INF);
        $maxs = array_fill(0, $numFeatures, -INF);

        foreach ($samples as $sample) {
            foreach ($sample as $i => $value) {
                $mins[$i] = min($mins[$i], $value);
                $maxs[$i] = max($maxs[$i], $value);
            }
        }

        foreach ($samples as &$sample) {
            foreach ($sample as $i => &$value) {
                if (($maxs[$i] - $mins[$i]) == 0) {
                    $value = 0;
                } else {
                    $value = ($value - $mins[$i]) / ($maxs[$i] - $mins[$i]);
                }
            }
        }
    }

    protected function getViewData(): array
    {
        return [
            'chart' => $this->getChart(),
        ];
    }
}