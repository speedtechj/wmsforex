<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Booking;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Skidmanifest;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SkidmanifestResource\Pages;
use App\Filament\Resources\SkidmanifestResource\RelationManagers;

class SkidmanifestResource extends Resource
{
    protected static ?string $model = Skidmanifest::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                Tables\Columns\TextColumn::make('skidno')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('virtual_invoice')
                    ->label('Invoice')
                    ->searchable(),
                Tables\Columns\TextColumn::make('batch.batchno')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('booking.boxtype.description')
                    ->numeric()
                    ->sortable(),
                    Tables\Columns\IconColumn::make('match')
                    ->label('Match')
                    ->boolean()
                    ->getStateUsing(function (Model $record): bool {
                       $test = Booking::where(['id' => $record->booking_id,'batch_id' => $record->batch_id])
                       ->exists();
                       return $test;
                       
                    }),
                
               
               
                
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListSkidmanifests::route('/'),
            'create' => Pages\CreateSkidmanifest::route('/create'),
            'edit' => Pages\EditSkidmanifest::route('/{record}/edit'),
        ];
    }
}
