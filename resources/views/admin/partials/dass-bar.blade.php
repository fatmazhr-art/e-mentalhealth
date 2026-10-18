{{--
    Partial: DASS-21 Stacked Bar Row
    ==================================
    Satu baris stacked bar chart untuk distribusi DASS-21.
    
    PARAMETERS:
    - $label (string) : Label kategori, misal "Depresi"
    - $data  (array)  : Array dengan key: normal, ringan, sedang, berat, sangat_berat (semua dalam %)
    
    CARA PAKAI:
    @include('admin.partials.dass-bar', [
        'label' => 'Depresi',
        'data'  => [
            'normal'       => 55,
            'ringan'       => 20,
            'sedang'       => 10,
            'berat'        => 15,
            'sangat_berat' => 5,
        ],
    ])
--}}

<div class="dass-row">
    <div class="dass-row__header">
        <span class="dass-row__label">{{ $label }}</span>
        <div class="dass-row__legend">
            <span>{{ $data['normal'] ?? 0 }}% Normal</span>
            <span>{{ $data['ringan'] ?? 0 }}% Ringan</span>
            <span>{{ $data['sedang'] ?? 0 }}% Sedang</span>
            <span>{{ $data['berat'] ?? 0 }}% Berat</span>
            <span>{{ $data['sangat_berat'] ?? 0 }}% Sangat Berat</span>
        </div>
    </div>
    <div class="stacked-bar">
        <div class="stacked-bar__seg seg--normal" style="width: {{ $data['normal'] ?? 0 }}%;"></div>
        <div class="stacked-bar__seg seg--ringan" style="width: {{ $data['ringan'] ?? 0 }}%;"></div>
        <div class="stacked-bar__seg seg--sedang" style="width: {{ $data['sedang'] ?? 0 }}%;"></div>
        <div class="stacked-bar__seg seg--berat"  style="width: {{ $data['berat'] ?? 0 }}%;"></div>
        <div class="stacked-bar__seg seg--sangat-berat" style="width: {{ $data['sangat_berat'] ?? 0 }}%;"></div>
    </div>
</div>
