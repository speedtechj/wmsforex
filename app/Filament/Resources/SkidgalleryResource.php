<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Batch;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Skidgallery;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SkidgalleryResource\Pages;
use App\Filament\Resources\SkidgalleryResource\RelationManagers;
use phpDocumentor\Reflection\PseudoTypes\True_;

class SkidgalleryResource extends Resource
{
    protected static ?string $model = Skidgallery::class;

    protected static ?string $navigationLabel = 'Skidding Gallery';
    public static ?string $label = 'Skidding Galleries';
    protected static ?string $navigationIcon = 'heroicon-o-photo';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Skidding Gallery Information')
                    ->schema([
                        Forms\Components\FileUpload::make('gallery')
                            ->label('Gallery')
                            ->multiple()
                            ->panelLayout('grid')
                            ->uploadingMessage('Uploading attachment...')
                            ->image()
                            ->openable()
                            ->disk('public')
                            ->directory('skidgallery')
                            ->visibility('private')
                            ->required()
                            ->removeUploadedFileButtonPosition('right')
                            ->minFiles(4),
                    ])



            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->paginationPageOptions([10, 25])
            ->columns([
                Tables\Columns\TextColumn::make('skidid')
                    ->searchable()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('batch.batchno')
                    ->label('Batch Number')
                    ->sortable()
                    ->formatStateUsing(function ( $record) {
                        return $record->batch->batchno.' - '.$record->batch->batch_year;
                    }),
                // Tables\Columns\ImageColumn::make('gallery')
                //     ->circular()
                //     ->stacked()
                //     ->ring(8)
                //     ->overlap(0),
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
                SelectFilter::make('batch_id')
                    ->label('Batch Number')
                    ->options(Batch::where('is_lock', false)->pluck('batchno', 'id'))
                    ->default(Batch::currentbatch())
                   
                    
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
            'index' => Pages\ListSkidgalleries::route('/'),
            'create' => Pages\CreateSkidgallery::route('/create'),
            'edit' => Pages\EditSkidgallery::route('/{record}/edit'),
        ];
    }
}
