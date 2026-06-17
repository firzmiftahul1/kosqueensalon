<x-filament-panels::page>
    <x-filament::section>
        <div class="bg-white p-4 rounded-xl shadow">
            <h2 class="text-lg font-bold mb-4">
                Total Pengeluaran vs Total Transaksi
            </h2>

            <div style="width: 700px; height: 500px; margin: 0 auto; position: relative;">
                <canvas id="supplierClusteringChart"></canvas>
            </div>
        </div>
    </x-filament::section>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        function formatRupiah(value) {
            return new Intl.NumberFormat('id-ID').format(value);
        }

        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('supplierClusteringChart');

            if (!canvas) {
                return;
            }

            new Chart(canvas, {
                type: 'scatter',
                data: @json($chart),
                options: {
                    responsive: true,
                    maintainAspectRatio: false,

                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },

                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    const totalPengeluaran = formatRupiah(ctx.raw.x);
                                    const totalTransaksi = formatRupiah(ctx.raw.y);

                                    return `${ctx.raw.label} (X: Rp ${totalPengeluaran}, Y: ${totalTransaksi})`;
                                }
                            }
                        }
                    },

                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Total Pengeluaran'
                            },
                            beginAtZero: true
                        },

                        y: {
                            title: {
                                display: true,
                                text: 'Total Transaksi'
                            },
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-filament-panels::page>