<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider; 
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
     public function boot(): void
    {
        Validator::extend('safaricom_phone', function ($attribute, $value, $parameters, $validator) {
            // Check if the input consists of digits and is exactly 10 characters long
            if (ctype_digit($value) && strlen($value) === 10) {
                $validPrefixes = ['070', '071', '072', '0740', '0741', '0742', '0743', '0745', '0746', '0748', '0757', '0758', '0759', '0768', '0769', '079', '0110', '0111', '0112', '0113', '0114', '0115'];
        
                foreach ($validPrefixes as $prefix) {
                    if (strpos($value, $prefix) === 0) {
                        return true;
                    }
                }
            }
        
            return false;
        });

        
    }
    }

