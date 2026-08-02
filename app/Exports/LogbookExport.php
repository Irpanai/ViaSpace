<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\Storage;

class LogbookExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $userId;
    protected $filters;

    public function __construct($userId, $filters = [])
    {
        $this->userId = $userId;
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Attendance::with('logbook')
            ->where('user_id', $this->userId);

        if (!empty($this->filters['filter_type'])) {
            $type = $this->filters['filter_type'];
            if ($type === 'this_week') {
                $query->whereBetween('date', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()]);
            } elseif ($type === 'this_month') {
                $query->whereMonth('date', \Carbon\Carbon::now()->month)
                      ->whereYear('date', \Carbon\Carbon::now()->year);
            } elseif ($type === 'custom' && !empty($this->filters['start_date']) && !empty($this->filters['end_date'])) {
                $query->whereBetween('date', [$this->filters['start_date'], $this->filters['end_date']]);
            }
        }

        return $query->orderBy('date', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Jam Masuk',
            'Jam Keluar',
            'Status',
            'Kategori Tugas',
            'Deskripsi Pekerjaan',
            'Link Hasil Kerja',
            'Link Bukti Foto'
        ];
    }

    public function map($row): array
    {
        $logbook = $row->logbook;
        $photoUrl = '';
        
        if ($logbook && $logbook->photo_path) {
            $photoUrl = url(Storage::url($logbook->photo_path));
        }

        return [
            \Carbon\Carbon::parse($row->date)->format('d M Y'),
            $row->check_in_time ? \Carbon\Carbon::parse($row->check_in_time)->format('H:i') : '-',
            $row->check_out_time ? \Carbon\Carbon::parse($row->check_out_time)->format('H:i') : '-',
            $this->mapStatus($row->status),
            $logbook ? $logbook->category : '-',
            $logbook ? $logbook->description : '-',
            $logbook ? $logbook->link : '-',
            $photoUrl
        ];
    }
    
    private function mapStatus($status) {
        return match($status) {
            'present' => 'Hadir',
            'sick' => 'Sakit',
            'permit' => 'Izin',
            default => 'Alpa'
        };
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']], 'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['argb' => 'FF374151']]],
        ];
    }
}
