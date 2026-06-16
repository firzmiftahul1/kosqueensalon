<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-o-sparkles class="w-6 h-6 text-primary-500" />
                <span>AI Market Trend Insights</span>
            </div>
        </x-slot>

        <div class="flex flex-col gap-4">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Gunakan integrasi kecerdasan buatan Gemini untuk menganalisis tren fasilitas kamar dan kebutuhan inventaris masa kini berdasarkan <strong>Master Data Barang</strong> Kos Queen Salon.
                </p>
                
                <x-filament::button wire:click="analyzeTrend" wire:loading.attr="disabled" icon="heroicon-m-cpu-chip" color="primary">
                    <span wire:loading.remove wire:target="analyzeTrend">Generate AI Trend</span>
                    <span wire:loading wire:target="analyzeTrend">Menganalisis Data...</span>
                </x-filament::button>
            </div>

            <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 min-h-[120px]">
                @if($aiResponse)
                    <div class="prose dark:prose-invert max-w-none text-sm text-gray-800 dark:text-gray-200">
                        {!! Illuminate\Support\Str::markdown($aiResponse) !!}
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center h-full space-y-2 text-gray-500 dark:text-gray-400 italic text-sm py-4">
                        <span wire:loading.remove wire:target="analyzeTrend">
                            Klik tombol di atas untuk mulai memunculkan rekomendasi tren aset properti.
                        </span>
                        
                        <div wire:loading wire:target="analyzeTrend" class="flex flex-col items-center gap-2">
                            <x-filament::loading-indicator class="h-6 w-6 text-primary-500" />
                            <span>AI merangkum wawasan tren kos & properti...</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>