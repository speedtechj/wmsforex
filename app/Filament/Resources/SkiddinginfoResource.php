<?php

namespace App\Filament\Resources;

use App\Models\Bookdata;
use Filament\Forms;
use Filament\Tables;
use App\Models\Batch;
use App\Models\Booking;
use Filament\Forms\Get;
use Filament\Forms\Form;
use App\Models\Skidweight;
use Filament\Tables\Table;
use App\Models\Skiddinginfo;
use App\Models\Warehouseuser;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Grouping\Group;
use App\Exports\SkiddinginfosExport;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Range;
use Filament\Tables\Columns\Summarizers\Average;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SkiddinginfoResource\Pages;
use App\Filament\Resources\SkiddinginfoResource\RelationManagers;

class SkiddinginfoResource extends Resource
{
    protected static ?string $model = Skiddinginfo::class;
    protected static ?string $navigationLabel = 'Skidding Info';
    public static ?string $label = 'Skidding Info';
    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->paginationPageOptions([10, 25, 50, 100, 200, 400, 500])
            ->groups([
                Group::make('skidno')
                    ->collapsible()
                    ->getDescriptionFromRecordUsing(function (Model $record) {
                        $total_box = Skiddinginfo::where('skidno', $record->skidno)->where('batch_id', $record->batch_id)->count();
                        $total_cbm = Skiddinginfo::where('skidno', $record->skidno)->where('batch_id', $record->batch_id)->sum('cbm');
                        return "Total Box: " . $total_box . '   ' . "Total Cbm: " . $total_cbm;
                    })

            ])
            ->defaultGroup('skidno')
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10, 25])
            // ->modifyQueryUsing(function ($query) {
            //     return $query->with(['booking','user']);
            // })
            // ->query(Skiddinginfo::query()->where('batch_id', Batch::currentbatch()))
            ->columns([
                Tables\Columns\TextColumn::make('batch.batchno')
                    ->label('Batch Number')
                    ->sortable(),
                Tables\Columns\TextColumn::make('skidno')
                    ->label('Skid Number')
                    ->searchable(isIndividual: true)
                    ->sortable(),
                    Tables\Columns\TextColumn::make('booking.boxtype.description')
                    ->sortable(),
                // Tables\Columns\TextColumn::make('boxtype')
                //     ->label('Box Type')
                //     ->sortable()
                //     ->formatStateUsing(function (Model $record) {
                //         return $record->boxtype->description;
                //     }),
                Tables\Columns\TextColumn::make('virtual_invoice')
                    ->label('Invoice')
                    ->sortable()
                    ->searchable(isIndividual: true),
                Tables\Columns\TextColumn::make('cbm')
                    ->label('Cbm'),

                Tables\Columns\IconColumn::make('is_encode')
                    ->label('Verified')
                    ->sortable()
                    ->boolean(),
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
            ])->searchOnBlur()
           
            ->filters([

                // ->query(fn (Builder $query): Builder => $query->where('batch_id', Batch::currentbatch()))->default(),
                Filter::make('is_encode')->label('Not Encoded')
                    ->query(fn(Builder $query): Builder => $query->where('is_encode', false)),
                SelectFilter::make('batch_id')
                    ->options(Batch::query()->where('is_lock', false)->pluck('batchno', 'id'))
                    ->default(Batch::currentbatch()),


            ])
            ->actions([

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\DeleteAction::make()
                        ->visible(function (Model $record) {
                            return !$record->is_encode ?? true;
                        }),
                    Tables\Actions\Action::make('Pull Out')
                        ->icon('heroicon-o-archive-box-x-mark')
                        ->color('primary')
                        ->requiresConfirmation()
                        ->action(function (Model $record, Get $get, array $data) {
                          $booking_data = Bookdata::where('booking_invoice', $record->virtual_invoice)->orWhere('manual_invoice', $record->virtual_invoice)->first();
                       
                          $booking_data->update(['batch_id' => 23]);
                            $record->delete();
                        }),
                    Tables\Actions\Action::make('Move')
                        ->slideOver()
                        ->modalWidth(MaxWidth::Small)
                        ->icon('heroicon-o-arrow-right-start-on-rectangle')
                        ->color('info')
                        ->form([
                            Select::make('batch_id')
                                ->live()
                                ->label('Batch Number')
                                ->options(Batch::query()->where('is_lock', false)->pluck('batchno', 'id'))
                                ->required(),
                            Select::make('skidno')
                                ->label('Skid Number')
                                ->options(fn(Get $get): Collection => Skidweight::query()
                                    ->where('batch_id', $get('batch_id'))
                                    ->pluck('skid_no', 'skid_no'))
                        ])
                        ->action(function (Model $record, Get $get, array $data) {
                            $record->update([
                                'batch_id' => $data['batch_id'],
                                'skidno' => $data['skidno'],
                            ]);
                            if ($record->booking != null) {
                                $record->booking->update(['batch_id' => $data['batch_id']]);
                            }

                        }),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('xls')->label('Export to Excel')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(fn(Collection $records) => (new SkiddinginfosExport($records))->download('skidding.xlsx')),
                    Tables\Actions\BulkAction::make('Move')
                        ->icon('heroicon-o-arrow-right-start-on-rectangle')
                        ->color('primary')
                        ->form([
                            Section::make()
                                ->schema([
                                    Forms\Components\Select::make('batch_id')
                                        ->live()
                                        ->label('Select Batch Number')
                                        ->options(Batch::query()->where('is_lock', false)->pluck('batchno', 'id'))
                                        ->required(),
                                    Forms\Components\Select::make('skidno')
                                        ->required()
                                        ->label('Skid Number')
                                        ->options(fn(Get $get): Collection => Skidweight::query()
                                            ->where('batch_id', $get('batch_id'))
                                            ->pluck('skid_no', 'skid_no'))
                                ])

                        ])->action(function (Collection $records, array $data): void {
                            foreach ($records as $record) {
                                $record->update([
                                    'batch_id' => $data['batch_id'],
                                    'skidno' => $data['skidno'],
                                ]);

                                if ($record->booking != null) {
                                    $record->booking->update(['batch_id' => $data['batch_id']]);
                                }
                            }
                            Notification::make()
                                ->title('Invoice Moved successfully')
                                ->success()
                                ->send();
                        })

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
            'index' => Pages\ListSkiddinginfos::route('/'),
            'create' => Pages\CreateSkiddinginfo::route('/create'),
            // 'edit' => Pages\EditSkiddinginfo::route('/{record}/edit'),
        ];
    }
}
