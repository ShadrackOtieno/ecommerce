<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\RelationManagers;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make([

                    Section::make([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set)=>$operation==='create'?$set('slug',\Illuminate\Support\Str::slug($state)):null),

                        Forms\Components\TextInput::make('slug')
                            ->maxLength(255)
                            ->disabled()
                            ->dehydrated()
                            ->unique(Product::class, 'slug', ignoreRecord: true)
                            ,

                        Forms\Components\MarkdownEditor::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull()
                            ->fileAttachmentsDirectory('products'),

                  



                      
                    ])->columns(2), 

                    Section::make('Product Images')
                        ->schema([
                            FileUpload::make('images')
                                ->multiple()
                                ->directory('products')                               
                                ->reorderable(),                              

                
                ]),

                ])->columnSpan(2),

                Group::make()->schema([

                    Section::make('price')->schema([

                        TextInput::make('price')
                           ->required()->numeric()->prefix('Ksh.')

                    ]),

                    Section::make('Association')->schema([

                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                         Select::make('brand_id')
                            ->relationship('brand', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),    

                    ]),

                    Section::make('Status')->schema([

                        Toggle::make('in stock')
                            ->label('In Stock')
                            ->onColor('success')
                            ->offColor('danger')
                            ->default(true)
                            ->required(),

                         Toggle::make('is_active')
                            ->onColor('success')
                            ->offColor('danger')
                            ->default(true)
                            ->required(),    

                       

                        Toggle::make('is_featured')
                            ->onColor('success')
                            ->offColor('danger')
                            ->default(true)
                            ->required(),    

                        Toggle::make('on_sale')
                    ->onColor('success')
                    ->offColor('danger')
                    ->default(true)
                    ->required(),  

                    ]),

                ])->columnSpan(1)
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('category.name')->label('Category')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('brand.name')->label('Brand')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('price')->prefix('Ksh.')->sortable(),
                Tables\Columns\IconColumn::make('in_stock')
                    ->boolean()
                    ->label('In Stock')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean()
                    ->label('Featured')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),
                Tables\Columns\IconColumn::make('on_sale')
                    ->boolean()
                    ->label('On Sale')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Created')->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->label('Last Modified')->sortable(),  
            ])
            ->filters([
                SelectFilter::make('category')->relationship('category', 'name'),
                SelectFilter::make('brand')->relationship('brand', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
