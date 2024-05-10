<?php

namespace App\Filament\Resources\SkidsummaryResource\Widgets;

use Filament\Tables;
use App\Models\Batch;
use Filament\Tables\Table;
use App\Models\Skiddinginfo;
use Illuminate\Database\Query\Builder;
use Filament\Tables\Filters\SelectFilter;
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
           ->query(Skiddinginfo::query())
            ->filters([
                SelectFilter::make('batch_id')
                    ->multiple()
                    ->options(Batch::query()->where('is_lock', false)->pluck('batchno', 'id'))
                    ->default(array('Select Batch Number')),
            ])
            ->defaultGroup('booking.boxtype.description')
            ->groupsOnly()
            ->columns([

                Tables\Columns\TextColumn::make('booking.boxtype.description')
                    ->label('Box Type'),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('No of Boxes')
                    ->summarize(Summarizer::make()
                        ->using(fn(Builder $query): string => $query->sum('quantity'))),
                        Tables\Columns\TextColumn::make('cbm')
                        ->label('Total Cbm')
                        ->summarize(Summarizer::make()
                            ->using(fn(Builder $query): string => $query->sum('cbm')))
            ]);      
    }
    

}
