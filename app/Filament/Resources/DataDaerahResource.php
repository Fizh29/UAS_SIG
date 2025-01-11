<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DataDaerahResource\Pages;
use App\Filament\Resources\DataDaerahResource\RelationManagers;
use App\Models\DataDaerah;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DataDaerahResource extends Resource
{
    protected static ?string $model = DataDaerah::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kota')->required(),
                TextInput::make('lat')->numeric()->required(),
                TextInput::make('long')->numeric()->required(),
                TextInput::make('umur_harapan_hidup')->numeric()->required(),
                TextInput::make('tingkat_partisipasi_angkatan_kerja')->numeric()->required(),
                TextInput::make('tingkat_pengangguran_terbuka')->numeric()->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kota'),
                TextColumn::make('lat'),
                TextColumn::make('long'),
                TextColumn::make('umur_harapan_hidup'),
                TextColumn::make('tingkat_partisipasi_angkatan_kerja'),
                TextColumn::make('tingkat_pengangguran_terbuka'),
            ]);
    }


    // public static function form(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             //
    //         ]);
    // }

    // public static function table(Table $table): Table
    // {
    //     return $table
    //         ->columns([
    //             //
    //         ])
    //         ->filters([
    //             //
    //         ])
    //         ->actions([
    //             Tables\Actions\EditAction::make(),
    //         ])
    //         ->bulkActions([
    //             Tables\Actions\BulkActionGroup::make([
    //                 Tables\Actions\DeleteBulkAction::make(),
    //             ]),
    //         ]);
    // }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDataDaerahs::route('/'),
            'create' => Pages\CreateDataDaerah::route('/create'),
            'edit' => Pages\EditDataDaerah::route('/{record}/edit'),
        ];
    }
}
