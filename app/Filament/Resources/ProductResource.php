<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers\ProductImagesRelationManager;
use App\Models\Category;
use App\Models\MerchantAccount;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationGroup = 'Admin Management';
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationLabel = 'Products';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')->required()->maxLength(120),
                        Forms\Components\Select::make('merchant_account_id')
                            ->label('Merchant Account')
                            ->options(fn () => MerchantAccount::with('user')->get()->mapWithKeys(fn ($m) => [$m->id => ($m->user->name ?? ('Account #' . $m->id))]))
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('category_id')
                            ->label('Category')
                            ->options(Category::query()->pluck('name', 'id')->toArray())
                            ->searchable()
                            ->required(),
                        Forms\Components\TextInput::make('price')->numeric()->required(),
                        RichEditor::make('description')->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('merchantAccount.user.name')->label('Merchant')->sortable(),
                Tables\Columns\TextColumn::make('category.name')->label('Category')->sortable(),
                Tables\Columns\TextColumn::make('price')->money('GHS', true)->label('Price')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ProductImagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;
}

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;
}

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;
}

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Models\ProductImage;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables;

class ProductImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'productImages';
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('name')
                    ->label('Image')
                    ->image()
                    ->directory('product-images')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('name')->circular(),
                Tables\Columns\TextColumn::make('slug')->limit(30),
                Tables\Columns\TextColumn::make('created_at')->since(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}


