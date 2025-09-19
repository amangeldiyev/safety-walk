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
    public function __construct(public string $start, public string $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Audit::with('site', 'user', 'contactUser')->whereBetween('created_at', [$this->start, $this->end . ' 23:59:59'])->get();
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
            $audit->user ? $audit->user->name : '',
            $audit->user ? $audit->user->email : '',
            $audit->site ? $audit->site->name : '',
            $audit->mode === AuditMode::CONVERSATION ? 'Conversation' : 'Guided',
            $audit->good_practice ? '1' : '0',
            $audit->point_of_improvement ? '1' : '0',
            $audit->comment
        ];
    }

}
