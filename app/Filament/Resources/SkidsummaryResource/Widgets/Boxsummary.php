<?php

namespace App\Filament\Resources\SkidsummaryResource\Widgets;

use Filament\Tables;
use App\Models\Batch;
use Filament\Tables\Table;
use App\Models\Skiddinginfo;
use Illuminate\Database\Query\Builder;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use App\Filament\Resources\SkidsummaryResource\Pages\ListSkidsummaries;


class Boxsummary extends BaseWidget
{
    
    protected int|string|array $columnSpan = 'full';
    public function table(Table $table): Table
    {
        return $table
            ->heading('Box Type Summary')
            ->query(Skiddinginfo::query()->where('batch_id', Batch::currentbatch()))
            ->defaultGroup('booking.boxtype.description')
            ->groupsOnly()
            ->columns([

                Tables\Columns\TextColumn::make('booking.boxtype.description')
                    ->label('Box Type'),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Total')
                    ->summarize(Summarizer::make()
                        ->using(fn(Builder $query): string => $query->sum('quantity')))
            ]);
    }
}
