<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends \Spatie\TranslationLoader\LanguageLine
{
    protected $primaryKey = 'translation_id';

    public $translatable = ['text'];
    public $guarded = ['translation_id'];
}
