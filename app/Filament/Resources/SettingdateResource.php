<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingdateResource\Pages;
use App\Filament\Resources\SettingdateResource\RelationManagers;
use App\Models\Settingdate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SettingdateResource extends Resource
{
    protected static ?string $model = Settingdate::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('setting_day')
                    ->label('Number of Days')
                    ->required()
                    ->numeric(),
                Forms\Components\Toggle::make('is_default')
                    ->label('Default Days')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('setting_day')
                    ->label('Number of Days')
                    ->numeric()
                    ->sortable(),
                    Tables\Columns\ToggleColumn::make('is_default')
                    ->label('Default Days')
                    ->afterStateUpdated(function ($record, $state) {

                        
                        if($state == true){
                            $last_current_days = Settingdate::where('is_default', true)->count();
                            // dd($last_current_days);
                            if($last_current_days > 1){
                              
                                return $record->update(['is_default' => false]);
                            }else {
                                return $record->update(['is_default' => true]);
                            }
                        }
                       

                          
                        
                        // $record->update(['is_default' => true]);
                        // if($last_current_days->id != $record->id){
                            // return $record->update(['is_default' => false]);
                        // }
                        // $get_last_batch = Batch::all()->last();
                       
                        // if($get_last_batch->id != $record->id){
                        //     return $record->update(['is_current' => false]);
    
                        // }
                        
                    }),
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
                // Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListSettingdates::route('/'),
            'create' => Pages\CreateSettingdate::route('/create'),
            'edit' => Pages\EditSettingdate::route('/{record}/edit'),
        ];
    }
}
