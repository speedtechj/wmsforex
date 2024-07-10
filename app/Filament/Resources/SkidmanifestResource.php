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
use Illuminate\Support\HtmlString;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
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
                    \LaraZeus\Popover\Tables\PopoverColumn::make('virtual_invoice')
                    ->label('Invoice')
                    ->trigger('hover')
                    ->placement('right') // for more: https://alpinejs.dev/plugins/anchor#positioning
                        ->offset(2) // int px, for more: https://alpinejs.dev/plugins/anchor#offset
                        ->popOverMaxWidth('900')
                        ->content(function (Model $record){
                            
                            $data_record = Booking::where('id',$record->booking_id)->first();
                          
                            if($data_record) {
                                return  view('filament.invoice-card', ['record' => $record]);
                            }else {
                                return new HtmlString('<div class="p-4">Invoice Not Encoded</div>');
                            }
                          
                        })
                        // ->content(fn($record) => view('filament.invoice-card', ['record' => $record]))
                     
                    ->searchable(),
                Tables\Columns\TextColumn::make('batch.batchno')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('booking.boxtype.description')
                    ->numeric()
                    ->sortable(),
                    Tables\Columns\IconColumn::make('booking.is_paid')
                    ->label('Paid')
                    ->boolean(),
                    Tables\Columns\IconColumn::make('match')
                    ->label('Match')
                    ->boolean()
                    ->getStateUsing(function (Model $record): bool {
                        $compresult = Booking::where(['id' => $record->booking_id,'batch_id' => $record->batch_id])
                        ->exists();
                       return $compresult;
                       
                    }),
                    Tables\Columns\IconColumn::make('is_encode')
                    ->label('Encoded')
                    ->boolean(),
                    Tables\Columns\ToggleColumn::make('is_checked'),
                    Tables\Columns\TextColumn::make('created_at')->toggleable(isToggledHiddenByDefault: true),
                    Tables\Columns\TextColumn::make('updated_at')->toggleable(isToggledHiddenByDefault: true),
               
                
            ])
            ->filters([
                Filter::make('is_checked')
                ->label('Not Checked')
    ->query(fn (Builder $query): Builder => $query->where('is_checked', false))->default(),

                TernaryFilter::make('is_encode')
                ->label('Encoded')
                ->placeholder('All')
                ->trueLabel('Encoded')
                ->falseLabel('Not Encoded'),
                SelectFilter::make('batch_id')
                ->options(Batch::query()->where('is_lock', false)->pluck('batchno', 'id'))
                ->default(Batch::currentbatch()),
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
            'index' => Pages\ListSkidmanifests::route('/'),
            'create' => Pages\CreateSkidmanifest::route('/create'),
            // 'edit' => Pages\EditSkidmanifest::route('/{record}/edit'),
        ];
    }
}
