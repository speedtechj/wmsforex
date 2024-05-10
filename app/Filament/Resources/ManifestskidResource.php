<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Batch;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Manifestskid;
use App\Models\Skiddinginfo;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ManifestskidResource\Pages;
use App\Filament\Resources\ManifestskidResource\RelationManagers;

class ManifestskidResource extends Resource
{
    protected static ?string $model = Manifestskid::class;

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
                Tables\Columns\TextColumn::make('booking_invoice')
                    ->searchable(),
                Tables\Columns\TextColumn::make('manual_invoice')
                    ->searchable(),
                    Tables\Columns\IconColumn::make('match')
                    ->boolean()
                    ->getStateUsing(function (Model $record): bool {
                        $compresult = Skiddinginfo::where(['booking_id' => $record->id,'batch_id' => Batch::Currentbatch()])
                        ->exists();
                        return $compresult;
                        
                     }),
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
            'index' => Pages\ListManifestskids::route('/'),
            'create' => Pages\CreateManifestskid::route('/create'),
           
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('batch_id', Batch::Currentbatch());
    }
}
