<?php

namespace App\Helpers;

use App\Models\User;
use View;
use Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Schema;
use PHPMailer;
use DateTime;

class FrontHelper {

   public static function generatePassword($length = 8, $add_dashes = false, $available_sets = 'luds') {
      $sets = array();
      if (strpos($available_sets, 'l') !== false)
         $sets[] = 'abcdefghjkmnpqrstuvwxyz';
      if (strpos($available_sets, 'u') !== false)
         $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
      if (strpos($available_sets, 'd') !== false)
         $sets[] = '23456789';
      if (strpos($available_sets, 's') !== false)
         $sets[] = '!@#$%&*?';
      $all = '';
      $password = '';
      foreach ($sets as $set) {
         $password .= $set[array_rand(str_split($set))];
         $all .= $set;
      }
      $all = str_split($all);
      for ($i = 0; $i < $length - count($sets); $i++)
         $password .= $all[array_rand($all)];
      $password = str_shuffle($password);
      if (!$add_dashes)
         return $password;
      $dash_len = floor(sqrt($length));
      $dash_str = '';
      while (strlen($password) > $dash_len) {
         $dash_str .= substr($password, 0, $dash_len) . '-';
         $password = substr($password, $dash_len);
      }
      $dash_str .= $password;
      return $dash_str;
   }

  //Function for build tree structure
  public static function buildtree($src_arr, $parent_id = 0, $tree = array())
  {
      foreach($src_arr as $idx => $row)
      {
          if($row['parent_category'] == $parent_id)
          {
              foreach($row as $k => $v)
              $tree[$row['category_id']][$k] = $v;
              unset($src_arr[$idx]);
              $tree[$row['category_id']]['children'] = FrontHelper::buildtree($src_arr, $row['category_id']);
          }
      }
      ksort($tree);
      return $tree;
  }

  //GET ALL CATEGORY ID With CHILDREN ID
  public static function getAllChilCategory($category,$cateId){
    $cate = [];
    if (!empty($cateId)) {
      
      foreach ($cateId as $key => $value) {
        
        foreach ($category as $ckey => $cvalue){
          if($value==$cvalue['category_id']){
            if ($cvalue['category_id']==$value) {
                $cate[] = $cvalue['category_id'];
            }
                  
            if(!empty($cvalue['children'])){
            foreach ($cvalue['children'] as $key1 => $value1){
                if ($value1['parent_category']==$value) {
                    $cate[] = $value1['category_id'];
                }
                    if(!empty($value1['children'])){
                    foreach ($value1['children'] as $key2 => $value2) {
                        if ($value2['parent_category']==$value1['category_id']) {
                            $cate[] = $value2['category_id'];
                        }
                        if(!empty($value2['children'])){
                        foreach ($value2['children'] as $key3 => $value3){
                            if ($value3['parent_category'] == $value2['category_id']) {
                                $cate[] = $value3['category_id'];
                            }

                            if(!empty($value3['children'])){
                            foreach ($value3['children'] as $key4 => $value4){
                                if ($value4['parent_category']==$value3['category_id']) {
                                    $cate[] = $value4['category_id'];
                                }

                                if(!empty($value4['children'])){
                                foreach ($value4['children'] as $key5 => $value5){
                                    if ($value5['parent_category']==$value4['category_id']) {
                                        $cate[] = $value5['category_id'];
                                    }
                                }
                                }
                            }
                            }

                        }
                        }

                    }
                    }
                
                }
                }
            }
        }

      }

    }
    return $cate;

  }




  //GET ALL CATEGORY WITH SUB CATEGORY IN HERARCY MODE
  public static function getCategoryWithSubCat($category,$parent_id){
    $acteval = [];
    if (empty($parent_id) && Auth::user()->user_type==0) {
        foreach ($category as $key => $value){
              $acteval[] = ['cat'=>$value['category_id'],'name'=> $value['category_desc'][0]['name']];
              
              if(!empty($value['children'])){
              foreach ($value['children'] as $key1 => $value1) {
                  
                  $acteval[] = ['cat'=>$value1['category_id'], 'name'=>$value['category_desc'][0]['name'] . ' > ' . $value1['category_desc'][0]['name']];

                  if(!empty($value1['children'])){
                  foreach ($value1['children'] as $key2 => $value2) {
                    
                    $acteval[] =  ['cat'=>$value2['category_id'],'name'=> $value['category_desc'][0]['name'] . ' > ' . $value1['category_desc'][0]['name'] . ' > ' . $value2['category_desc'][0]['name']];

                      if(!empty($value2['children'])){
                      foreach ($value2['children'] as $key3 => $value3){
                        
                        $acteval[] = ['cat'=>$value3['category_id'],'name'=> $value['category_desc'][0]['name'] . ' > ' . $value1['category_desc'][0]['name'] . ' > ' . $value2['category_desc'][0]['name'] . ' > ' . $value3['category_desc'][0]['name']];
                          
                          if(!empty($value3['children'])){
                          foreach ($value3['children'] as $key4 => $value4){
                            
                            $acteval[] = ['cat'=>$value4['category_id'],'name'=> $value['category_desc'][0]['name'] . ' > ' . $value1['category_desc'][0]['name'] . ' > ' . $value2['category_desc'][0]['name']  . ' > ' . $value3['category_desc'][0]['name'] . ' > ' . $value4['category_desc'][0]['name']];

                              if(!empty($value4['children'])){
                              foreach ($value4['children'] as $key5 => $value5){
                                
                                $acteval[] = ['cat'=>$value5['category_id'],'name'=> $value['category_desc'][0]['name'] . ' > ' . $value1['category_desc'][0]['name'] . ' > ' . $value2['category_desc'][0]['name']  . ' > ' . $value3['category_desc'][0]['name'] . ' > ' . $value4['category_desc'][0]['name']  . ' > ' . $value5['category_desc'][0]['name']];
                              }
                              }

                          }
                          }

                      }
                      }

                  }
                  }

              }
              }

        }

    }else{
      foreach ($parent_id as $pivalue) {
        foreach ($category as $key => $value){
          if (!empty($parent_id) && $value['category_id']==$pivalue) {
              
              $acteval[] = ['cat'=>$value['category_id'],'name'=> $value['category_desc'][0]['name']];
              
              if(!empty($value['children'])){
              foreach ($value['children'] as $key1 => $value1) {
                  
                  $acteval[] = ['cat'=>$value1['category_id'], 'name'=>$value['category_desc'][0]['name'] . ' > ' . $value1['category_desc'][0]['name']];

                  if(!empty($value1['children'])){
                  foreach ($value1['children'] as $key2 => $value2) {
                    
                    $acteval[] =  ['cat'=>$value2['category_id'],'name'=> $value['category_desc'][0]['name'] . ' > ' . $value1['category_desc'][0]['name'] . ' > ' . $value2['category_desc'][0]['name']];

                      if(!empty($value2['children'])){
                      foreach ($value2['children'] as $key3 => $value3){
                        
                        $acteval[] = ['cat'=>$value3['category_id'],'name'=> $value['category_desc'][0]['name'] . ' > ' . $value1['category_desc'][0]['name'] . ' > ' . $value2['category_desc'][0]['name'] . ' > ' . $value3['category_desc'][0]['name']];
                          
                          if(!empty($value3['children'])){
                          foreach ($value3['children'] as $key4 => $value4){
                            
                            $acteval[] = ['cat'=>$value4['category_id'],'name'=> $value['category_desc'][0]['name'] . ' > ' . $value1['category_desc'][0]['name'] . ' > ' . $value2['category_desc'][0]['name']  . ' > ' . $value3['category_desc'][0]['name'] . ' > ' . $value4['category_desc'][0]['name']];

                              if(!empty($value4['children'])){
                              foreach ($value4['children'] as $key5 => $value5){
                                
                                $acteval[] = ['cat'=>$value5['category_id'],'name'=> $value['category_desc'][0]['name'] . ' > ' . $value1['category_desc'][0]['name'] . ' > ' . $value2['category_desc'][0]['name']  . ' > ' . $value3['category_desc'][0]['name'] . ' > ' . $value4['category_desc'][0]['name']  . ' > ' . $value5['category_desc'][0]['name']];
                              }
                              }

                          }
                          }

                      }
                      }

                  }
                  }

              }
              }

          }
        }
      }
    }
    //END FORLOOP
    return $acteval;

  }




  //GET SIGLE CATEGORY  IN HERARCY MODE
  public static function getSingleHeararcyofCat($category,$category_id){
    $acteval = '';
    if (!empty($category_id)) {
        foreach ($category as $key => $value){
              if ($value['category_id']==$category_id) {
                $acteval = $value['category_desc'][0]['name'];
              }
              
              if(!empty($value['children'])){
              foreach ($value['children'] as $key1 => $value1) {
                  if ($value1['category_id']==$category_id) {
                    $acteval = $value['category_desc'][0]['name'] . ' > ' . $value1['category_desc'][0]['name'];
                  }

                  if(!empty($value1['children'])){
                  foreach ($value1['children'] as $key2 => $value2) {
                    if ($value2['category_id']==$category_id) {
                      $acteval =  $value['category_desc'][0]['name'] . ' > ' . $value1['category_desc'][0]['name'] . ' > ' . $value2['category_desc'][0]['name'];
                    }

                      if(!empty($value2['children'])){
                      foreach ($value2['children'] as $key3 => $value3){
                        if ($value3['category_id']==$category_id) {    
                          $acteval = $value['category_desc'][0]['name'] . ' > ' . $value1['category_desc'][0]['name'] . ' > ' . $value2['category_desc'][0]['name'] . ' > ' . $value3['category_desc'][0]['name'];
                        }

                          if(!empty($value3['children'])){
                          foreach ($value3['children'] as $key4 => $value4){
                            if ($value4['category_id']==$category_id) {    
                              $acteval = $value['category_desc'][0]['name'] . ' > ' . $value1['category_desc'][0]['name'] . ' > ' . $value2['category_desc'][0]['name']  . ' > ' . $value3['category_desc'][0]['name'] . ' > ' . $value4['category_desc'][0]['name'];
                            }
                              if(!empty($value4['children'])){
                              foreach ($value4['children'] as $key5 => $value5){
                                if ($value5['category_id']==$category_id) {   
                                  $acteval = $value['category_desc'][0]['name'] . ' > ' . $value1['category_desc'][0]['name'] . ' > ' . $value2['category_desc'][0]['name']  . ' > ' . $value3['category_desc'][0]['name'] . ' > ' . $value4['category_desc'][0]['name']  . ' > ' . $value5['category_desc'][0]['name'];
                                }
                              }
                              }

                          }
                          }

                      }
                      }

                  }
                  }

              }
              }

        }

    }
    //END FORLOOP
    return $acteval;

  }

    /*
    * SEND PUSH NOTIFICATIONS
    * 
    */
    public static function sendNotification($notification)
    {
          $tokenget = \App\Models\UsersLastLogin::where('user_id','=',$notification['to_user_id'])->first();
          if(!empty($tokenget) && $tokenget != null)
          {
              $token = $tokenget->device_token;
              // $token = 'cgx6xlryf4fnfdc5Zxy1Ma:APA91bERo9ia1kWWRAmY_3igZ242jrksQenSuggbNhiwT087RWIBBbZVOn4VKlKg2PCkQrgvDzv7rfRNU_6u90GZ81kK3JoMD5rONVoJiAWdI0YnnyOtAQ5ULiFD75L9T2NYVSYqoz5v';
              $API_ACCESS_KEY = 'AAAAz3fP_50:APA91bFCHnOs5qWoBT1zW5hLAs1qIzZoU6xI4OrMnbJMiuFJFJ1dOvN5ylFFof204NlqaBSpEfrqNtqbyIYn5wZU7zY8gSokU2LuBIP3wx7OEt1MUMr_sxcgP43CWZSdkYJnZnUckSQK';
              $url = "https://fcm.googleapis.com/fcm/send";
              // Server key from Firebase Console define( 'API_ACCESS_KEY', 'AAAA----FE6F' );
              if(isset($notification['user_type'])&& !empty($notification['user_type'])){
                  $user_type=$notification['user_type'];
              }else{
                  $user_type='';
              }
              $data = array("to" => $token, "notification" => array( "title" => 'Realtourhubs', "body" => $notification['notification_desc'],"icon" => "https://realtorhubs.com/public/uploads/common_settings/1599769765Add a heading (1).png", "click_action" => url('/'.$user_type.$notification['link'])));
              $data_string = json_encode($data);
             // echo "The Json Data : ".$data_string;
              $headers = array ( 'Authorization: key=' . $API_ACCESS_KEY, 'Content-Type: application/json' );
              $ch = curl_init(); curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
              curl_setopt( $ch,CURLOPT_POST, true );
              curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
              curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
              curl_setopt( $ch,CURLOPT_POSTFIELDS, $data_string);
              $result = curl_exec($ch);
              curl_close ($ch);
              //dd($result);
              //return 1;
              //echo "<p>&nbsp;</p>";
              //echo "The Result : ".$result;
          }
          unset($notification['user_type']);
          NotificationMaster::insert($notification);
    }

}

?>