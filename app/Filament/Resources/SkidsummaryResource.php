<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Batch;
use Filament\Forms\Form;
use App\Models\Skidweight;
use Filament\Tables\Table;
use App\Models\Skidsummary;

use App\Models\Skiddinginfo;
use Filament\Resources\Resource;
use Illuminate\Database\Query\Builder;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SkidsummaryResource\Pages;
use App\Filament\Resources\SkidsummaryResource\RelationManagers;

class SkidsummaryResource extends Resource
{
    protected static ?string $model = Skidsummary::class;
    protected static ?string $navigationLabel = 'Skidding Summary';
    public static ?string $label = 'Skidding Sumarries';

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
          
            ->columns([
                Tables\Columns\TextColumn::make('batch.batchno')
                    ->numeric(),
                Tables\Columns\TextColumn::make('skid_no'),
                Tables\Columns\TextColumn::make('totalbox')
                    ->label('Total Box')
                    ->getStateUsing(function ($record) {
                        $totalbox = Skiddinginfo::where('skidno', $record->skid_no)->where('batch_id', $record->batch_id)->count();
                        return $totalbox;
                    }),
                    
                        
                Tables\Columns\TextColumn::make('weight'),
                
            ])
            ->filters([
                SelectFilter::make('batch_id')
                ->options(Batch::query()->where('is_lock', false)->pluck('batchno', 'id'))
                ->default(Batch::Currentbatch()),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSkidsummaries::route('/'),
            'create' => Pages\CreateSkidsummary::route('/create'),
            // 'edit' => Pages\EditSkidsummary::route( '/{record}/edit' ),
        ];
    }
}
