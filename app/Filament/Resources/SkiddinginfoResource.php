<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Batch;
use App\Models\Booking;
use Filament\Forms\Get;
use Filament\Forms\Form;
use App\Models\Skidweight;
use Filament\Tables\Table;
use App\Models\Skiddinginfo;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Grouping\Group;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
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
        ->filtersLayout(FiltersLayout::AboveContentCollapsible)
            ->groups([
                
                Group::make('skidno')
                    ->collapsible()
                    ->getDescriptionFromRecordUsing(fn(Model $record): string => "Total" . " - " . Skiddinginfo::query()->where('skidno', $record->skidno)
                        ->where('batch_id', Batch::currentbatch())->count())
            ])
            ->defaultGroup('skidno')

            // ->query(Skiddinginfo::query()->where('batch_id', Batch::currentbatch()))
            ->columns([
                Tables\Columns\TextColumn::make('skidno')
                    ->label('Skid Number')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('virtual_invoice')
                    ->label('Invoice')
                    ->searchable(),
                Tables\Columns\TextColumn::make('booking.boxtype.description')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_encode')
                    ->label('Verified')
                    ->boolean(),
                Tables\Columns\TextColumn::make('batch.batchno')
                    ->label('Batch Number')
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
               
                // ->query(fn (Builder $query): Builder => $query->where('batch_id', Batch::currentbatch()))->default(),
                Filter::make('is_encode')->label('Not Encoded')
                    ->query(fn(Builder $query): Builder => $query->where('is_encode', false)),
                    SelectFilter::make('batch_id')
                    ->multiple()
                    ->options(Batch::all()->pluck('batchno', 'id'))
                    ->searchable()
                    ->default(array(Batch::currentbatch())),


            ])
            ->actions([
                // Tables\Actions\EditAction::make()
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('Pull Out')
                        ->icon('heroicon-o-archive-box-x-mark')
                        ->color('primary')
                        ->requiresConfirmation()
                        ->action(function (Model $record) {
                            $record->booking->update(['batch_id' => 23]);
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
                                ->createOptionForm([
                                    Forms\Components\TextInput::make('new_skidno')
                                        ->required()
                                        ->maxLength(255),
                                ])->createOptionUsing(function (array $data, Get $get) {
                                    $skidweight_skidno = Skidweight::query()
                                        ->where('batch_id', $get('batch_id'))
                                        ->where('skid_no', $data['new_skidno'])->count();
                                    if ($skidweight_skidno == 0) {
                                        Skidweight::create([
                                            'skid_no' => $data['new_skidno'],
                                            'batch_id' => $get('batch_id'),
                                            'user_id' => auth()->id(),
                                            'weight' => 0,

                                        ]);
                                        Notification::make()
                                            ->title('New Skid Saved successfully')
                                            ->success()
                                            ->send();
                                    }else {
                                        Notification::make()
                                            ->title('skid Number already Exist')
                                            ->success()
                                            ->send();
                                    };
                                  
                        
                                })->createOptionModalHeading('Create New Skid Number'),
                        ])
                        ->action(function (Model $record, Get $get, array $data) {
                            $record->update([
                                'batch_id' => $data['batch_id'],
                                'skidno' => $data['skidno'],
                            ]);
                            $record->booking->update(['batch_id' => $data['batch_id']]);
                        }),
                ])->visible(fn(Model $record): bool => $record->is_encode == true),

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
            'index' => Pages\ListSkiddinginfos::route('/'),
            'create' => Pages\CreateSkiddinginfo::route('/create'),
            // 'edit' => Pages\EditSkiddinginfo::route('/{record}/edit'),
        ];
    }
}
