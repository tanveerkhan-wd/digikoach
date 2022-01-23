<?php

namespace App\Common;

use Exception;
use Illuminate\Support\Facades\Storage;
use Image;
use File;

class Utilities
{

    public function getThumbImage($image_name, $image_width = 0, $image_height = 0, $crop_image = false)
    {
        ini_set('memory_limit', '-1');

        $THUMB_JPEG_QUALITY = 99;
        $default_image = '/images/no-image.png';

        if ($image_name === '' || $image_name == null) {
            $image_name = 'no-image.png';
        }

        try {

            if (isset($image_name) && trim($image_name) !== '') {
                $check = Storage::disk('public')->exists($image_name);
                if (!$check) {
                    $image_name = 'no-image.png';
                }

                $image_exists = Storage::disk('public')->exists($image_name);

                if ($image_exists) {
                    if ($image_width > 0 && $image_height > 0) {
                        if (!Storage::disk('public')->exists('thumb')) {
                            $thumb_path = public_path(Storage::url('thumb'));
                            File::makeDirectory($thumb_path);
                        }

                        $org_image_name = basename($image_name);

                        $new_thumb_name = 'thumb/' . $image_width . '_' . $image_height . '_' . $org_image_name;
                        $thumb_exists = Storage::disk('public')->exists($new_thumb_name);

                        if (!$thumb_exists) {
                            try {
                                $objImg = Image::make(public_path(Storage::url($image_name)));
                            } catch (Exception $e) {
                                return null;
                                /* echo $e->getMessage();
                                exit; */
                            }

                            if ($objImg) {
                                if ($crop_image) {
                                    $objImg->fit($image_width, $image_height);
                                } else {
                                    $objImg->resize($image_width, $image_height, function ($constraint) {
                                        $constraint->aspectRatio();
                                    });
                                }

                                $thumb_save_path = public_path(Storage::url($new_thumb_name));

                                $thumb_saved = $objImg->save($thumb_save_path, $THUMB_JPEG_QUALITY);
                            }
                        }

                        $default_image = Storage::url($new_thumb_name);
                    } else {
                        $default_image = Storage::url($image_name);
                    }
                }
            }

            return url('public' . $default_image);
        } catch (Exception $e) {
            return null;
            /* echo $image_name . '<br/>';
            dd($e); */
        }
    }

    function checkImageExists($image_path, $image)
    {
        try {
            if ($image != '' && $image != null) {
                $check = Storage::disk('public')->exists($image_path . '/' . $image);
                if ($check) {
                    return url('public' . Storage::url($image_path . '/' . $image));
                }
            }
        } catch (Exception $e) {
        }

        return url('public/images/no-image.png');
    }

    function getTimeAgo($ptime)
    {
        $etime = time() - strtotime($ptime);

        if ($etime < 1) {
            return trans('common.txt_just_now');
        }

        $a = [
            24 * 60 * 60 =>  trans('common.txt_days'),
            60 * 60 =>  trans('common.txt_hours'),
            60 =>  trans('common.txt_minutes'),
            1 =>  trans('common.txt_seconds')
        ];

        foreach ($a as $secs => $str) {
            $d = $etime / $secs;

            if ($d >= 1) {
                $r = round($d);
                return $r . ' ' . $str . ' ' . trans('common.txt_ago');
            }
        }
    }
}
