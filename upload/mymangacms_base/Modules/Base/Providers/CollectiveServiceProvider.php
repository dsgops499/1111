<?php 

namespace Modules\Base\Providers;

use Illuminate\Support\ServiceProvider;

use Form;

class CollectiveServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        $this->registerFormComponents();
        //$this->registerHtmlComponents();
    }

    protected function registerFormComponents()
    {
        /**
         * Custom checkbox
         * Every checkbox will not have the same name
         */
        Form::component('customCheckbox', 'base::admin._components.custom-checkbox', [
            /**
             * @var array $values
             * @template: [
             *      [string $name, string $value, string $label, bool $selected, bool $disabled],
             *      [string $name, string $value, string $label, bool $selected, bool $disabled],
             *      [string $name, string $value, string $label, bool $selected, bool $disabled],
             * ]
             */
            'values',
        ]);
    }

    protected function registerHtmlComponents()
    {
    }
}
