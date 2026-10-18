@php
    $normal = (float)($data['normal'] ?? 0);
    $ringan = (float)($data['ringan'] ?? 0);
    $sedang = (float)($data['sedang'] ?? 0);
    $berat  = (float)($data['berat'] ?? 0);
    $sangat = (float)($data['sangat_berat'] ?? 0);
    $total  = $normal + $ringan + $sedang + $berat + $sangat;

    if ($total <= 0) {
        $normal = 55;
        $ringan = 20;
        $sedang = 10;
        $berat  = 10;
        $sangat = 5;
    }
@endphp

<div class="dass-row">
    <div class="dass-row__labels">
        <div class="dass-col-label dass-col-label--normal" style="width: {{ $normal }}%;">
            <span class="dass-label-title">{{ $label }}</span>
            <span class="dass-label-stat">{{ $normal }}% Normal</span>
        </div>
        <div class="dass-col-label dass-col-label--ringan" style="width: {{ $ringan }}%;">
            <span class="dass-label-stat">{{ $ringan }}% Ringan</span>
        </div>
        <div class="dass-col-label dass-col-label--sedang" style="width: {{ $sedang }}%;">
            <span class="dass-label-stat">{{ $sedang }}% Sedang</span>
        </div>
        <div class="dass-col-label dass-col-label--berat" style="width: {{ $berat }}%;">
            <span class="dass-label-stat">{{ $berat }}% Berat</span>
        </div>
        <div class="dass-col-label dass-col-label--sangat-berat" style="flex: 1; min-width: {{ $sangat }}%;">
            <span class="dass-label-stat">{{ $sangat }}% Sangat Berat</span>
        </div>
    </div>
    <div class="stacked-bar">
        <div class="stacked-bar__seg seg--normal" style="width: {{ $normal }}%;"></div>
        <div class="stacked-bar__seg seg--ringan" style="width: {{ $ringan }}%;"></div>
        <div class="stacked-bar__seg seg--sedang" style="width: {{ $sedang }}%;"></div>
        <div class="stacked-bar__seg seg--berat"  style="width: {{ $berat }}%;"></div>
        <div class="stacked-bar__seg seg--sangat-berat" style="flex: 1; min-width: {{ $sangat }}%;"></div>
    </div>
</div>
