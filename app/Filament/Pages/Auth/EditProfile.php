<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as RulesPassword;
use Rawilk\FilamentPasswordInput\Password;

class EditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                Password::make('password')
                    ->label(__('filament-panels::pages/auth/edit-profile.form.password.label'))
                    ->password()
                    ->copyable()
                    ->copyMessage('Password telah disalin ke clipboard')
                    ->copyMessageDuration(3000)
                    ->copyTooltip('Salin password')
                    ->regeneratePassword()
                    ->rule(RulesPassword::default())
                    ->autocomplete('new-password')
                    ->dehydrated(fn ($state): bool => filled($state))
                    ->dehydrateStateUsing(fn ($state): string => Hash::make($state))
                    ->live(debounce: 500)
                    ->same('passwordConfirmation'),
                Password::make('passwordConfirmation')
                    ->label(__('filament-panels::pages/auth/edit-profile.form.password_confirmation.label'))
                    ->password()
                    ->required()
                    ->visible(fn (Get $get): bool => filled($get('password')))
                    ->dehydrated(false)
            ]);
    }
}
