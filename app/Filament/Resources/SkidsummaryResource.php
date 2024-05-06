<?php

namespace App\Filament\Resources;

use App\Models\Skidweight;
use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Batch;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Skidsummary;

use App\Models\Skiddinginfo;
use Filament\Resources\Resource;
use Illuminate\Database\Query\Builder;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SkidsummaryResource\Pages;
use App\Filament\Resources\SkidsummaryResource\RelationManagers;

class SkidsummaryResource extends Resource
{
    protected static ?string $model = Skidsummary::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Skidweight::query()->where('batch_id', Batch::currentbatch()))
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
                //
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
