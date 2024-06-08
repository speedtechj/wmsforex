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
use App\Exports\SkidweightsExport;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\SkidweightExporter;
use Illuminate\Database\Eloquent\Collection;
use Filament\Tables\Actions\ExportBulkAction;
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
                Forms\Components\TextInput::make('weight')
                    ->label('Weight')
                    ->required()
                    ->numeric()

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('batch.batchno')
                    ->sortable(),
                Tables\Columns\TextColumn::make('skid_no'),
                Tables\Columns\TextColumn::make('skidno')
                    ->label('Total Boxes')
                    ->getStateUsing(function (Model $record) {
                        return Skiddinginfo::query()->where('skidno', $record->skid_no)
                            ->where('batch_id', $record->batch_id)->count();
                    }),
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
                    ->options(Batch::query()->where('is_lock', false)->pluck('batchno', 'id'))
                    ->default(Batch::Currentbatch()),
            ])
            ->headerActions([
                Tables\Actions\Action::make('New Skid')
                    ->requiresConfirmation()
                    ->slideOver()
                    ->label('Create New Skid')
                    ->form([
                        Section::make()
                            ->schema([
                                Select::make('batch_id')
                                    ->label('Select Batch')
                                    ->options(Batch::query()->where('is_lock', false)->pluck('batchno', 'id'))
                                    ->searchable()
                            ])

                    ])
                    ->action(function (array $data) {

                        $last_skid = Skidweight::where('batch_id', $data['batch_id'])->latest()->first();

                        Skidweight::create([
                            'batch_id' => $data['batch_id'],
                            'skid_no' => $last_skid->skid_no + 1,
                            'weight' => 0,
                            'user_id' => auth()->id(),
                        ]);

                        Notification::make()
                            ->title('Saved successfully')
                            ->success()
                            ->send();

                    }),

            ])
            ->actions([
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->visible(function (Model $record) {
                        $skidcount = Skiddinginfo::query()->where('skidno', $record->skid_no)
                            ->where('batch_id', $record->batch_id)->count();
                        if(!$skidcount){
                            return true;
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('xls')->label('Export to Excel')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(fn(Collection $records) => (new SkidweightsExport($records))->download('collection.xlsx')),
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
