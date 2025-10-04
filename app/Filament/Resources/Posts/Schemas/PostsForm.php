<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Models\Posts;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

// use Illuminate\Support\Set;

class PostsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
           
            ->schema([
                Group::make()->schema([
                    Section::make()->schema([
                        TextInput::make('user_id')
                            ->required()
                            ->numeric(),
                        TextInput::make('external_id'),
                    ])->columns(2),
                    Section::make()->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                        TextInput::make('slug')
                            ->maxLength(255)
                            ->disabled()
                            ->required()
                            ->dehydrated()
                            ->unique(Posts::class, 'slug', ignoreRecord: true),
                        MarkdownEditor::make('body')
                            ->columnSpanFull()
                            ->fileAttachmentsDirectory('posts'),
                    ])->columns(1),

                ])->columnSpan(2),
                Group::make()->schema([
                    Toggle::make('is_published')
                        ->label('Published')
                        ->default(true)
                        ->live()
                        ->afterStateUpdated(function ($state, $set) {
                            if ($state) {
                                $set('is_draft', false);
                            }
                        }),
                    Toggle::make('is_draft')
                        ->label('Draft')
                        ->live()
                        ->afterStateUpdated(function ($state, $set) {
                            if ($state) {
                                $set('is_published', false);
                            }
                        }),
                    // Select::make('user_id')
                    //     ->label('User')
                    //     ->relationship('user', 'name')
                    //     ->searchable()
                    //     ->preload(),
                ])->columnSpan(1),
            ])->columns(3);
    }
}
