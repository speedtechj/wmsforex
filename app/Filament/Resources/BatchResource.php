<?php

namespace App\Filament\Resources;

use livewire;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Batch;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\BatchResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BatchResource\RelationManagers;

class BatchResource extends Resource
{
    protected static ?string $model = Batch::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('batchno')
                    ->label('Batch No')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('batch_year')
                    ->label('Year')
                    ->default(now()->year)
                    ->required(),
                Forms\Components\Toggle::make('is_current')
                ->label('Current Batch')
                    ->required()
                    ->default(true),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
              ->query(Batch::query()->where('is_lock', false))
            ->columns([
                Tables\Columns\TextColumn::make('batchno')
                    ->label('Batch No')
                    ->searchable(),
                Tables\Columns\TextColumn::make('batch_year')
                ->label('Year')
                ->searchable(),
                Tables\Columns\ToggleColumn::make('is_lock')
                ->label('Lock Batch')
                ->sortable(),
                Tables\Columns\ToggleColumn::make('is_current')
                ->afterStateUpdated(function ($record, $state) {
                    $get_last_batch = Batch::all()->last();
                   
                    if($get_last_batch->id != $record->id){
                        return $record->update(['is_current' => false]);

                    }
                    
                })
                ->label('Open Batch'),
                Tables\Columns\TextColumn::make('user.full_name')
                    ->label('Created By')
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
                //
            ])
            ->actions([
              
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    
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
            'index' => Pages\ListBatches::route('/'),
            'create' => Pages\CreateBatch::route('/create'),
            'edit' => Pages\EditBatch::route('/{record}/edit'),
        ];
    }
}
