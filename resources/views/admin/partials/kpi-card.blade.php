{{--
    Partial: KPI Card
    ==================
    Kartu ringkasan KPI yang digunakan di dashboard.
    
    PARAMETERS:
    - $title   (string) : Judul kartu, misal "TOTAL PENGGUNA"
    - $value   (string) : Nilai utama, misal "19 JUTA"
    - $subtext (string) : Keterangan tambahan, misal "5% Mode anonim"
    
    CARA PAKAI:
    @include('admin.partials.kpi-card', [
        'title'   => 'TOTAL PENGGUNA',
        'value'   => '19 JUTA',
        'subtext' => '5% Mode anonim',
    ])
--}}

<div class="kpi-card">
    <div class="kpi-card__title">{{ $title }}</div>
    <div class="kpi-card__value">{{ $value }}</div>
    <div class="kpi-card__subtext">{{ $subtext }}</div>
</div>
