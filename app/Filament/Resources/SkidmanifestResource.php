<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Batch;
use App\Models\Booking;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Skidmanifest;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
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
                Forms\Components\TextInput::make('skidno'),
                Forms\Components\TextInput::make('virtual_invoice'),
                Forms\Components\Select::make('batch_id')
                ->options(Batch::query()->where('is_lock', false)->pluck('batchno', 'id'))
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
                       $compresult = Booking::where(['id' => $record->booking_id,'batch_id' => Batch::Currentbatch()])
                       ->exists();
                       return $compresult;
                       
                    }),
                    Tables\Columns\IconColumn::make('is_encode')
                    ->label('Encoded')
                    ->boolean(),
                
                    Tables\Columns\TextColumn::make('created_at'),
                    Tables\Columns\TextColumn::make('updated_at')
               
                
            ])
            ->filters([
                SelectFilter::make('batch_id')
                ->multiple()
                ->options(Batch::query()->where('is_lock', false)->pluck('batchno', 'id'))
                ->default(array('Select Batch Number')),
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
