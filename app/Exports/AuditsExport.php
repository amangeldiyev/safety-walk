<?php

namespace App\Exports;

use App\Enums\AuditMode;
use App\Models\Audit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AuditsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Audit::with('site', 'user', 'contactUser')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Submitted On',
            'Submitted By',
            'User Email',
            'Site',
            'Safety Walk Mode',
            'Good Practice',
            'Points of Improvement',
            'Overall Comments',
        ];
    }

    /**
    * @param Audit $audit
    */
    public function map($audit): array
    {
        return [
            $audit->id,
            $audit->date,
            $audit->user->name,
            $audit->user->email,
            $audit->site->name,
            $audit->mode === AuditMode::CONVERSATION ? 'Conversation' : 'Guided',
            $audit->good_practice ? '1' : '0',
            $audit->point_of_improvement ? '1' : '0',
            $audit->comment
        ];
    }

}
