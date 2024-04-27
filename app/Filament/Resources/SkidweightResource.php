<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Batch;
use Filament\Forms\Form;
use App\Models\Skidweight;
use Filament\Tables\Table;
use App\Models\Skiddinginfo;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SkidweightResource\Pages;
use App\Filament\Resources\SkidweightResource\RelationManagers;

class SkidweightResource extends Resource
{
    protected static ?string $model = Skidweight::class;
    protected static ?string $navigationLabel = 'Skidding Weight';
    public static ?string $label = 'Skidding Weight';
    protected static ?string $navigationIcon = 'heroicon-o-scale';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
       
            ->columns([
                Tables\Columns\TextColumn::make('skid_no'),
                Tables\Columns\TextColumn::make('skidno')
                ->label('Total Boxes')
                ->getStateUsing(function (Model $record) {
                    return Skiddinginfo::query()->where('skidno', $record->skid_no)
                    ->where('batch_id',$record->batch_id)->count();
                }),
                Tables\Columns\TextColumn::make('batch.batchno')
                    ->sortable(),
                Tables\Columns\TextColumn::make('weight')
                    ->label('Total Weight/lbs')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.full_name')
                    ->label('Encoded By')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('batch_id')
                ->options(Batch::all()->pluck('batchno', 'id'))
                    ->searchable()
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
            'index' => Pages\ListSkidweights::route('/'),
            'create' => Pages\CreateSkidweight::route('/create'),
            'edit' => Pages\EditSkidweight::route('/{record}/edit'),
        ];
    }
}
