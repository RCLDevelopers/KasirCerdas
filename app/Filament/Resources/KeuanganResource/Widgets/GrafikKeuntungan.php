<?php

namespace App\Filament\Resources\KeuanganResource\Widgets;

use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use App\Models\DataTransaksi;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Facades\Auth;

class GrafikKeuntungan extends ChartWidget
{   
    protected static string $color = 'info';
    protected static ?string $heading = 'Grafik Keuntungan';

    protected function getData(): array 
    {
        $data = Trend::query(
            DataTransaksi::where([
                ['bisnis_id', '=', Auth::user()->bisnis_id],
                ['cabangs_id', '=', Auth::user()->cabangs_id],
            ])
        )
        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->perMonth() 
        ->sum('keuntungan');

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Keuntungan',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate), 
                    'backgroundColor' => '#36C2EB',
                    'borderColor' => '#9BD0F5',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'line'; 
    }
}
