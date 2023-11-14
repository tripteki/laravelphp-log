<?php

namespace App\Exports\Logs;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class LogExport implements FromCollection, ShouldAutoSize, WithStyles, WithHeadings
{
    /**
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [

            1 => [ "font" => [ "bold" => true, ], ],
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [

            "ID",
            "Causer Type",
            "Causer ID",
            "Subject Type",
            "Subject ID",
            "Log Name",
            "Properties",
            "Event",
            "Created At",
            "Updated At",
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function collection()
    {
        return \Spatie\Activitylog\ActivitylogServiceProvider::getActivityModelInstance()->all([

            "id",
            "causer_type",
            "causer_id",
            "subject_type",
            "subject_id",
            "log_name",
            "properties",
            "event",
            "created_at",
            "updated_at",
        ]);
    }
};
