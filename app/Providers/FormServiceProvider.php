<?php

namespace App\Providers;

use App\View\Components\CheckboxField;
use App\View\Components\FieldWrapper;
use App\View\Components\Label;
use App\View\Components\SelectField;
use App\View\Components\SubmitButton;
use App\View\Components\TextField;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class FormServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::component(TextField::class, 'template.text-field');
        // Blade::component(TextareaField::class, 'x-template.textarea-field');
        // Blade::component(FileField::class, 'x-template.file-field');
        Blade::component(SelectField::class, 'template.select-field');
        Blade::component(CheckboxField::class, 'template.checkbox-field');
        Blade::component(Label::class, 'template.label');
        Blade::component(SubmitButton::class, 'template.submit-button');
        Blade::component(FieldWrapper::class, 'template.field-wrapper');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
