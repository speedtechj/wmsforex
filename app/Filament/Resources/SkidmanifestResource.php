<?php

namespace App\Filament\Resources;

use App\Models\Dataall;
use Filament\Forms;
use Filament\Tables;
use App\Models\Batch;
use App\Models\Booking;
use Filament\Forms\Form;
use App\Models\Bookingall;
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
    protected static ?string $navigationLabel = 'Skid VS Manifest';
    public static ?string $label = 'Skid VS Manifest';

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
                    ->sortable()
                    ->label('Invoice')
                    ->trigger('hover')
                    ->placement('bottom') // for more: https://alpinejs.dev/plugins/anchor#positioning
                        ->icon('heroicon-o-chevron-down') // show custom icon
                        ->offset(5) // int px, for more: https://alpinejs.dev/plugins/anchor#offset
                        ->popOverMaxWidth('1000')
                        ->content(function (Model $record){
                            
                            $data_record = booking::where('id',$record->booking_id)->first();
                         
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
                    Tables\Columns\IconColumn::make('is_checked')
                    ->label('Checked')
                    ->boolean(),
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
                Tables\Actions\Action::make('Check')
                ->icon('heroicon-o-check')
                ->iconButton()
                ->visible(fn (Model $record) => $record->is_encode)
                ->action(function (Model $record) {
                    $record->update(['is_checked' => true]);
                }),
                Tables\Actions\Action::make('Uncheck')
                ->icon('heroicon-o-x-mark')
                ->color('danger')
                ->iconButton()
                ->visible(fn (Model $record) => $record->is_checked)
                ->action(function (Model $record) {
                    $record->update(['is_checked' => false]);
                })
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
