<?php
    namespace App\Common;

    use DB;
    use Cache;

    class Settings
    {
        public function resolveCache() {
            return Cache::rememberForever('settings', function () {
                return DB::table('settings')->pluck('text_value', 'text_key')->toArray();
            });
        }

        public function get($setting_key = NULL)
        {
            $settings = $this->resolveCache();

            // no key passed, assuming get all settings
            if ($setting_key == NULL) {
                return $settings;
            }
            
            // array of keys passed, return those settings only
            if (is_array($setting_key)) {
                foreach ($setting_key as $key) {
                    $result[] = $settings[$key];
                }
                return $result;
            }

            // single key passed, return that setting only
            if (array_key_exists($setting_key, $settings)) {
                return $settings[$setting_key]; 
            }

            return false;
            
        }
        

        public function has($setting_key)
        {
            $settings = $this->resolveCache();
            return array_key_exists($setting_key, $settings);
        }

        public function set($changes, bool $force = false)
        {
            if ($force) {
                foreach ($changes as $key => $value) {
                    DB::table('settings')->where('text_key', '=', $key)->delete();    
                    DB::table('settings')->insert(['text_key' => $key, 'text_value'=>$value]); 
                }
            } else {
                $settings = $this->resolveCache();

                foreach (array_only($changes, array_keys($settings)) as $key => $value) {
                    DB::table('settings')->where('text_key', $key)->update(['text_value'=>$value]); 
                }
            }
            Cache::forget('settings');
            return true;
        }
    }