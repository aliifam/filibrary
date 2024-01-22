<?php

namespace App\Filament\Pages\Auth\PasswordReset;

use Filament\Forms\Form;
use Filament\Pages\Auth\PasswordReset\ResetPassword as BaseResetPassword;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Rawilk\FilamentPasswordInput\Password;

class ResetPassword extends BaseResetPassword
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getEmailFormComponent(),
                Password::make('password')
                    ->label(__('filament-panels::pages/auth/password-reset/reset-password.form.password.label'))
                    ->required()
                    ->copyable()
                    ->copyMessage('Password telah disalin ke clipboard')
                    ->copyMessageDuration(3000)
                    ->copyTooltip('Salin password')
                    ->regeneratePassword()
                    ->rule(PasswordRule::default())
                    ->same('passwordConfirmation')
                    ->validationAttribute(__('filament-panels::pages/auth/password-reset/reset-password.form.password.validation_attribute')),
                Password::make('passwordConfirmation')
                    ->label(__('filament-panels::pages/auth/password-reset/reset-password.form.password_confirmation.label'))
                    ->required()
                    ->dehydrated(false),
            ]);
    }
}
