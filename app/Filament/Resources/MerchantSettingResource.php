<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MerchantSettingResource\Pages;
use App\Models\MerchantSetting;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Tables;

class MerchantSettingResource extends Resource
{
    protected static ?string $model = MerchantSetting::class;
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationGroup = 'Store';
    protected static ?string $navigationLabel = 'Store Appearance';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'email')
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('store_name')->maxLength(100),
                Forms\Components\FileUpload::make('logo_path')
                    ->image()
                    ->directory('store-logos'),
                Forms\Components\ColorPicker::make('primary_color'),
                Forms\Components\ColorPicker::make('secondary_color'),
                Forms\Components\TextInput::make('font_family')->placeholder('Inter, Roboto, ...'),
                Forms\Components\KeyValue::make('theme')->keyLabel('token')->valueLabel('value'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.email')->label('Merchant'),
                Tables\Columns\TextColumn::make('store_name'),
                Tables\Columns\ImageColumn::make('logo_path'),
                Tables\Columns\TextColumn::make('primary_color'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageMerchantSettings::route('/'),
        ];
    }
}

namespace App\Filament\Resources\MerchantSettingResource\Pages;

use App\Filament\Resources\MerchantSettingResource;
use Filament\Resources\Pages\ManageRecords;

class ManageMerchantSettings extends ManageRecords
{
    protected static string $resource = MerchantSettingResource::class;
}


