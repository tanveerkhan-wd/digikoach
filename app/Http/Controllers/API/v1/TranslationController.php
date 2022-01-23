<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Translation;

class TranslationController extends Controller
{    
    /**
     * Get Translation JSON array - language code wise
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTranslations(){
        $fmt_translations = [
            'en' => [],
            'hi' => []
        ];
        $translations = Translation::get();
        foreach($translations as $translation){
            //$trans_text_array = json_decode($translation->text);
            foreach($translation->text as $lang => $trans_text){
                //$fmt_translations[$lang][$translation->group][$translation->key] = $trans_text;
                $fmt_translations[$lang][$translation->group . '.' . $translation->key] = $trans_text;
            }
        }

        return response()->json($fmt_translations, 200, [], JSON_INVALID_UTF8_IGNORE);
    }
}
