<?php

namespace App\Exports;

use App\Models\Tagihan;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class PenagihanExport implements FromCollection
{
    public $cari, $status, $pembaca, $tahun, $bulan;

    public function __construct($cari, $status, $pembaca, $tahun, $bulan)
    {
        $this->cari = $cari;
        $this->status = $status;
        $this->pembaca = $pembaca;
        $this->tahun = $tahun;
        $this->bulan = $bulan;
    }

    public function collection()
    {
        return Tagihan::where(fn($q) => $q->where('no_langganan', 'like', '%' . $this->cari . '%')->orWhere('nama', 'like', '%' . $this->cari . '%'))->select('no_langganan', 'nama', 'alamat', 'jumlah', 'periode', DB::raw('if(date(DATE_ADD(NOW(), INTERVAL 1 HOUR)) > concat(SUBSTR(periode, 1, 8), "25"), 5000, 0) denda'), 'id')->when($this->status == 1, fn($q) => $q->whereNotNull('tanggal_tagih')->where('periode', $this->tahun . '-' . $this->bulan . '-01'))->when($this->status == 0, fn($q) => $q->whereNull('tanggal_tagih'))->when($this->pembaca, fn($q) => $q->where('pembaca_kode', $this->pembaca))->get();
    }
    public function map($data): array
    {
        return [
            $data->no_langganan,
            $data->nama,
            $data->alamat,
            $data->periode,
            $data->jumlah,
            $data->denda,
            $data->tanggal_tagih,
            $data->petugas ? $data->petugas->nama : '',
        ];
    }

    public function headings(): array
    {
        return [
            [
                'NO. LANGGANAN',
                'NAMA',
                'ALAMAT',
                'PERIODE',
                'JUMLAH',
                'DENDA',
                'TGL. TAGIH',
                'PETUGAS',
            ]];
    }
}