<?php

use Illuminate\Database\Seeder;

class TranslationTableDataSeeder extends Seeder
{
    private $trans_data = [];

    function __construct()
    {
        $this->trans_data = [
            'validation' => [
                'name.required' => [
                    'en' => 'Please enter your name.',
                    'hi' => 'अपना नाम दर्ज करें।'
                ],
                'password.required' => [
                    'en' => 'Please enter your password.',
                    'hi' => 'कृपया अपना पासवर्ड दर्ज करें।'
                ],
                'password.min_length' => [
                    'en' => 'Password length should be more than 6 chars.',
                    'hi' => 'पासवर्ड की लंबाई 6 वर्ण से अधिक होनी चाहिए।'
                ],
                'mobile.required' => [
                    'en' => 'Please enter your mobile number.',
                    'hi' => 'अपना मोबाइल नंबर दर्ज करें।'
                ],
                'mobile.min_length' => [
                    'en' => 'Invalid mobile number.',
                    'hi' => 'अमान्य मोबाइल नंबर।'
                ],
                'mobile.max_length' => [
                    'en' => 'Invalid mobile number.',
                    'hi' => 'अमान्य मोबाइल नंबर।'
                ],
                'mobile.duplicate' => [
                    'en' => 'The entered mobile number has been registered already.',
                    'hi' => 'दर्ज मोबाइल नंबर पहले ही पंजीकृत हो चुका है।'
                ],
                'mobile.not_exits' => [
                    'en' => 'The entered mobile number does not register with us. Please check it.',
                    'hi' => 'दर्ज मोबाइल नंबर हमारे साथ पंजीकृत नहीं है। कृपया यह जाँचें।'
                ],
                'mobile.otp' => [
                    'en' => 'Please check the entered OTP.',
                    'hi' => 'कृपया दर्ज किए गए OTP की जांच करें।'
                ],
                'mobile.reset_token' => [
                    'en' => 'Invalid OTP verification request. Please retry at your end.',
                    'hi' => 'अमान्य ओटीपी सत्यापन अनुरोध। कृपया अपने अंत में पुनः प्रयास करें।'
                ],
                'old_password.required' => [
                    'en' => 'Please enter your old password.',
                    'hi' => 'कृपया अपना पुराना पासवर्ड डालें।'
                ],
                'old_password.min_length' => [
                    'en' => 'Password length should be more than 6 chars.',
                    'hi' => 'पासवर्ड की लंबाई 6 वर्ण से अधिक होनी चाहिए।'
                ],
                'new_password.required' => [
                    'en' => 'Please enter your password.',
                    'hi' => 'कृपया अपना पासवर्ड दर्ज करें।'
                ],
                'new_password.min_length' => [
                    'en' => 'Password length should be more than 6 chars.',
                    'hi' => 'पासवर्ड की लंबाई 6 वर्ण से अधिक होनी चाहिए।'
                ],
                'confirm_password.required' => [
                    'en' => 'Please enter your password.',
                    'hi' => 'कृपया अपना पासवर्ड दर्ज करें।'
                ],
                'confirm_password.min_length' => [
                    'en' => 'Password length should be more than 6 chars.',
                    'hi' => 'पासवर्ड की लंबाई 6 वर्ण से अधिक होनी चाहिए।'
                ],
                'confirm_password.same' => [
                    'en' => 'Please enter the confirm password the same as the entered password above.',
                    'hi' => 'कृपया पुष्टि पासवर्ड दर्ज करें जैसा कि ऊपर दर्ज पासवर्ड है।'
                ],
                'verification_value.required' => [
                    'en' => 'Please check the entered value.',
                    'hi' => 'कृपया दर्ज मूल्य की जाँच करें।'
                ],
                'verification_code.required' => [
                    'en' => 'Please check the entered OTP.',
                    'hi' => 'कृपया ओटीपी दर्ज करें।'
                ],
                'email.required' => [
                    'en' => 'Please enter a valid email address.',
                    'hi' => 'कृपया एक वैध ईमेल एड्रेस डालें।'
                ],
                'email.duplicate' => [
                    'en' => 'The entered email has been registered already.',
                    'hi' => 'दर्ज ईमेल पहले ही पंजीकृत हो चुका है।'
                ],
            ],
            'content' => [
                'forgot_pass_otp_sms' => [
                    'en' => 'Your forgot password OTP is :otp',
                    'hi' => 'आपका भूल गया पासवर्ड OTP है - :otp'
                ]
            ],
            'message' => [
                'txt_you_can_select_only_one_image' => [
                    'en' => 'You can select only one image',
                    'hi' => 'आप केवल एक छवि का चयन कर सकते हैं'
                ],
                'txt_you_can_select_only_one_category' => [
                    'en' => 'You can select only one category',
                    'hi' => 'आप केवल एक श्रेणी का चयन कर सकते हैं'
                ],
                'success.student_create' => [
                    'en' => 'Your account has been created successfully.',
                    'hi' => 'आपका खाता सफलतापूर्वक बनाया गया है।'
                ],
                'success.forgot_pass_otp_sent' => [
                    'en' => 'Please check your mobile. We have sent reset OTP to your entered mobile number.',
                    'hi' => 'कृपया अपना मोबाइल जांचें। हमने आपके दर्ज मोबाइल नंबर पर रीसेट ओटीपी भेज दिया है।'
                ],
                'success.phone_verification_sent' => [
                    'en' => 'Please check your mobile. We have sent OTP to your entered mobile number.',
                    'hi' => 'कृपया अपना मोबाइल जांचें। हमने आपके दर्ज मोबाइल नंबर पर ओटीपी भेज दिया है।'
                ],
                'success.email_verification_sent' => [
                    'en' => 'Please check your email inbox. We have sent verification code to the mentioned email address.',
                    'hi' => 'कृपया अपना ईमेल इनबॉक्स देखें। हमने उल्लिखित ईमेल पते पर सत्यापन कोड भेज दिया है।'
                ],
                'success.password_reset' => [
                    'en' => 'We have updated your password successfully.',
                    'hi' => 'हमने आपका पासवर्ड सफलतापूर्वक अपडेट कर दिया है।'
                ],
                'success.exam_registered' => [
                    'en' => 'We have registered you for this exam successfully.',
                    'hi' => 'हमने आपको इस परीक्षा के लिए सफलतापूर्वक पंजीकृत किया है।'
                ],
                'error.exam_register_already' => [
                    'en' => 'You have already registered for the exam.',
                    'hi' => 'आपने परीक्षा के लिए पहले ही पंजीकरण करवा लिया है।'
                ],
                'error.exam_register_failed' => [
                    'en' => 'We are facing an issue with exam registration.',
                    'hi' => 'हम परीक्षा पंजीकरण के साथ एक समस्या का सामना कर रहे हैं।'
                ],
                'success.exam_started' => [
                    'en' => 'We have registered you for this exam successfully.',
                    'hi' => 'हमने आपको इस परीक्षा के लिए सफलतापूर्वक पंजीकृत किया है।'
                ],
                'error.exam_start_failed' => [
                    'en' => 'We are facing an issue with exam start.',
                    'hi' => 'हम परीक्षा पंजीकरण के साथ एक समस्या का सामना कर रहे हैं।'
                ],
                'error.invalid_old_password' => [
                    'en' => 'Please check your entered old password.',
                    'hi' => 'कृपया अपना दर्ज किया गया पुराना पासवर्ड जांचें।'
                ],
                'success.mobile_update' => [
                    'en' => 'Your mobile number has been updated successfully.',
                    'hi' => 'आपका मोबाइल नंबर सफलतापूर्वक अपडेट कर दिया गया है।'
                ],
                'success.email_update' => [
                    'en' => 'Your email address has been updated successfully.',
                    'hi' => 'आपका ईमेल पता सफलतापूर्वक अपडेट कर दिया गया है।'
                ],
                'success.photo_update' => [
                    'en' => 'Photo has been updated successfully.',
                    'hi' => 'फ़ोटो को सफलतापूर्वक अपडेट कर दिया गया है।'
                ],
                'success.profile_update' => [
                    'en' => 'Your profile has been updated successfully.',
                    'hi' => 'आपकी प्रोफ़ाइल सफलतापूर्वक अद्यतन किया गया है।'
                ],
                'success.student_deactivate' => [
                    'en' => 'Your account has been deactivated successfully.',
                    'hi' => 'आपका खाता सफलतापूर्वक निष्क्रिय कर दिया गया है।'
                ],
                'error.student_duplicate' => [
                    'en' => 'We are having your account already with us.',
                    'hi' => 'हम आपका खाता हमारे पास पहले से ही रख रहे हैं।'
                ],
                'error.student_bad_credentials' => [
                    'en' => 'Please check your mobile number or password.',
                    'hi' => 'कृपया अपना मोबाइल नंबर या पासवर्ड जांचें।'
                ],
                'error.user_deleted' => [
                    'en' => 'Your account has been suspended or deleted.',
                    'hi' => 'आपका खाता निलंबित या हटा दिया गया है।'
                ],
                'error.sms_otp_failed' => [
                    'en' => 'We are facing issue with OTP sending. Please try after some time.',
                    'hi' => 'हम OTP भेजने के साथ समस्या का सामना कर रहे हैं। कृपया कुछ देर बाद प्रयास करें।'
                ],
                'error.invalid_user' => [
                    'en' => 'Invalid request. Please try again.',
                    'hi' => 'अमान्य अनुरोध। कृपया पुन: प्रयास करें।'
                ],
                'error.invalid_otp' => [
                    'en' => 'Invalid OTP. Please check and enter again.',
                    'hi' => 'अमान्य ओटीपी। कृपया जाँच करें और फिर से दर्ज करें।'
                ],
                'error.invalid_request' => [
                    'en' => 'Invalid request. Please try again.',
                    'hi' => 'अमान्य अनुरोध। कृपया पुन: प्रयास करें।'
                ],
                'error.already_blog_saved' => [
                    'en' => 'The blog has been saved to your account already.',
                    'hi' => 'ब्लॉग पहले ही आपके खाते में सहेज लिया गया है।'
                ],
                'success.lang_changed' => [
                    'en' => 'Language has been changed.',
                    'hi' => 'भाषा बदल दी गई है।'
                ],
                'success.sub_level_updated' => [
                    'en' => 'Your sub level has been updated.',
                    'hi' => 'आपका उपस्तर अपडेट कर दिया गया है।'
                ],
                'success.level_updated' => [
                    'en' => 'Your level has been updated.',
                    'hi' => 'आपका स्तर अपडेट कर दिया गया है।'
                ],
                'success.user_blog_saved' => [
                    'en' => 'The blog has been saved successfully.',
                    'hi' => 'ब्लॉग को सफलतापूर्वक सहेज लिया गया है।'
                ],
                'success.user_blog_deleted' => [
                    'en' => 'The blog has been deleted successfully.',
                    'hi' => 'ब्लॉग को सफलतापूर्वक हटा दिया गया है।'
                ],
                'success.user_verification' => [
                    'en' => 'Your verification has been completed.',
                    'hi' => 'आपका सत्यापन पूरा हो गया है।'
                ],
                'success.live_test_1' => [
                    'en' => 'Your Live Test has been submitted successfully.',
                    'hi' => 'आपका लाइव टेस्ट सफलतापूर्वक सबमिट किया गया है।',
                ],
                'success.live_test_2' => [
                    'en' => 'The result will be declared on the {0}',
                    'hi' => '{0} परिणाम घोषित किया जाएगा',
                ],
                'success.live_test_3' => [
                    'en' => 'When we declare the result you will get notified.',
                    'hi' => 'जब हम परिणाम घोषित करेंगे तो आपको सूचित किया जाएगा।',
                ],
                'advice.click_solution' => [
                    'en' => 'Click on the question for solution',
                    'hi' => 'समाधान के लिए प्रश्न पर क्लिक करें',
                ],
                'success.exacellent_performance' => [
                    'en' => 'HURRAY! Your performance is Excellent!',
                    'hi' => 'हुर्रे! आपका प्रदर्शन उत्कृष्ट है!',
                ],
                'success.average_performance' => [
                    'en' => 'You still need IMPROVEMENT!',
                    'hi' => 'आपको अभी भी सुधार की आवश्यकता है!',
                ],
                'success.low_performance' => [
                    'en' => 'Sorry, Better LUCK next time.',
                    'hi' => 'क्षमा करें, अगली बार बेहतर किस्मत।'
                ],
                'alert.resume_exam' => [
                    'en' => 'Are you sure you want to resume this exam?',
                    'hi' => 'क्या आप वाकई इस परीक्षा को फिर से शुरू करना चाहते हैं?'
                ],
                'alert.submit_exam' => [
                    'en' => 'Do you want to submit the Exam?',
                    'hi' => 'क्या आप परीक्षा प्रस्तुत करना चाहते हैं?',
                ],
                'alert.exam_timeout' => [
                    'en' => 'Your exam time has been exceeded.',
                    'hi' => 'आपकी परीक्षा का समय पार हो चुका है।'
                ],
                'error.no_exam_found' => [
                    'en' => 'No Exams Found.',
                    'hi' => 'कोई परीक्षा नहीं मिली।'
                ],
                'advice.result_declare' => [
                    'en' => 'The result will be declared on {0} at {1}.',
                    'hi' => 'परिणाम {0} पर {1} पर घोषित किया जाएगा।'
                ],
                'success.notification_clear_all' => [
                    'en' => 'Notifications have been cleared.',
                    'hi' => 'सूचनाएं साफ कर दी गई हैं।'
                ],
                'success.notification_clear' => [
                    'en' => 'The notification has been cleared.',
                    'hi' => 'अधिसूचना हटा दी गई है।'
                ],
                'error.no_notification_found' => [
                    'en' => 'No notification is available.',
                    'hi' => 'कोई अधिसूचना उपलब्ध नहीं है।',
                ],
                'success.article_saved' => [
                    'en' => 'The article has been saved successfully.',
                    'hi' => 'लेख को सफलतापूर्वक सहेज लिया गया है।'
                ],
                'success.article_unsaved' => [
                    'en' => 'The article has been removed successfully.',
                    'hi' => 'लेख को सफलतापूर्वक हटा दिया गया है।'
                ],
                'error.article_alredy_saved' => [
                    'en' => 'The article has been saved already.',
                    'hi' => 'लेख पहले ही सहेजा जा चुका है।'
                ],
                'success.challenge_sent' => [
                    'en' => 'The challenge request has been sent successfully.',
                    'hi' => 'चुनौती अनुरोध सफलतापूर्वक भेजा गया है।'
                ],
                'alert.start_challenge' => [
                    'en' => 'Do you want a challenge to someone for this test?',
                    'hi' => 'क्या आप इस परीक्षा के लिए किसी को चुनौती देना चाहते हैं?',
                ],
                'error.no_saved_item_found' => [
                    'en' => 'No saved items available.',
                    'hi' => 'कोई सहेजा गया आइटम उपलब्ध नहीं है।',
                ],
                'error.no_appread_exam_found' => [
                    'en' => 'No saved items available.',
                    'hi' => 'उपलब्ध परीक्षा नहीं दिखाई दी.',
                ],
                'success.doubt_created' => [
                    'en' => 'Doubt has been created successfully.',
                    'hi' => 'संदेह सफलतापूर्वक बनाया गया है।'
                ],
                'success.doubt_tag_updated' => [
                    'en' => 'The Doubt Tag has been updated successfully.',
                    'hi' => 'संदेह टैग को सफलतापूर्वक अपडेट किया गया है।'
                ],
                'success.doubt_image_updated' => [
                    'en' => 'The Doubt Image has been updated successfully.',
                    'hi' => 'संदेह छवि को सफलतापूर्वक अपडेट किया गया है।'
                ],
                'success.doubt_attachment_updated' => [
                    'en' => 'The Doubt Attachment has been updated successfully.',
                    'hi' => 'संदेह अनुलग्नक को सफलतापूर्वक अपडेट किया गया है।'
                ],
                'success.doubt_ans_created' => [
                    'en' => 'The Doubt Answer has been created successfully.',
                    'hi' => 'संदेह उत्तर सफलतापूर्वक बनाया गया है।'
                ],
                'success.doubt_reply_created' => [
                    'en' => 'The Doubt Reply has been created successfully.',
                    'hi' => 'संदेह उत्तर सफलतापूर्वक बनाया गया है।'
                ],
                'success.doubt_deleted' => [
                    'en' => 'The Doubt has been deleted successfully.',
                    'hi' => 'संदेह को सफलतापूर्वक हटा दिया गया है।'
                ],
                'success.doubt_ans_deleted' => [
                    'en' => 'The Doubt Answer has been deleted successfully.',
                    'hi' => 'संदेह उत्तर को सफलतापूर्वक हटा दिया गया है।'
                ],
                'success.my_doubt_saved' => [
                    'en' => 'The Doubt has been saved successfully.',
                    'hi' => 'संदेह को सफलतापूर्वक सहेज लिया गया है।'
                ],
                'success.doubt_upvoted' => [
                    'en' => 'The Doubt has been upvoted successfully.',
                    'hi' => 'संदेह को सफलतापूर्वक उत्कीर्ण किया गया है।'
                ],
                'success.doubt_downvoted' => [
                    'en' => 'The Doubt has been downvoted successfully.',
                    'hi' => 'संदेह को सफलतापूर्वक समाप्त कर दिया गया है।'
                ],
                'error.no_keywords' => [
                    'en' => 'Please enter keywords.',
                    'hi' => 'कृपया कीवर्ड दर्ज करें।'
                ],
                'error.techical_error' => [
                    'en' => 'Sorry! We are facing some technical glitch. Try after sometime.',
                    'hi' => 'माफ़ करना! हम कुछ तकनीकी गड़बड़ का सामना कर रहे हैं। कुछ देर बाद कोशिश करें।'
                ],
                'alert.accept_challenge' => [
                    'en' => 'Do you want to accept the challenge?',
                    'hi' => 'क्या आप चुनौती स्वीकार करना चाहते हैं?',
                ],
                'error.challenge_already_accepted' => [
                    'en' => 'You have been accepted this challenge already.',
                    'hi' => 'इस चुनौती को आप पहले ही स्वीकार कर चुके हैं।',
                ],
                'error.challenge_already_rejected' => [
                    'en' => 'You have been rejected by this challenge already.',
                    'hi' => 'आप इस चुनौती को पहले ही अस्वीकार कर चुके हैं।',
                ],
                'success.doubt_ans_upvoted' => [
                    'en' => 'The Doubt answer has been upvoted successfully.',
                    'hi' => 'संदेह उत्तर को सफलतापूर्वक उत्कीर्ण किया गया है।'
                ],
                'success.doubt_ans_downvoted' => [
                    'en' => 'The Doubt answer has been downvoted successfully.',
                    'hi' => 'संदेह उत्तर को सफलतापूर्वक समाप्त कर दिया गया है।'
                ],
                'success.doubt_ans_image_updated' => [
                    'en' => 'The Doubt Answer Image has been updated successfully.',
                    'hi' => 'संदेह उत्तर छवि को सफलतापूर्वक अपडेट किया गया है।'
                ],
                'success.doubt_reply_image_updated' => [
                    'en' => 'The Doubt Reply Image has been updated successfully.',
                    'hi' => 'संदेह उत्तर छवि को सफलतापूर्वक अपडेट किया गया है।'
                ],
                'success.user_question_saved' => [
                    'en' => 'The Question has been saved to your saved items successfully.',
                    'hi' => 'संदेह उत्तर छवि को सफलतापूर्वक अपडेट किया गया है।'
                ],
                'success.user_question_deleted' => [
                    'en' => 'The Question has been deleted from your saved items successfully.',
                    'hi' => 'संदेह उत्तर छवि को सफलतापूर्वक अपडेट किया गया है।'
                ],
                'alert.completed_questions' => [
                    'en' => 'You have completed all the questions.',
                    'hi' => 'आपने सभी प्रश्न पूरे कर लिए हैं।'
                ],
                'error.no_result_found' => [
                    'en' => 'No result found.',
                    'hi' => 'कोई परिणाम नहीं मिला।',
                ],
                'error.no_blog_found' => [
                    'en' => 'No blog found.',
                    'hi' => 'कोई ब्लॉग नहीं मिला।',
                ],
                'error.no_doubt_found' => [
                    'en' => 'No doubt found.',
                    'hi' => 'कोई संदेह नहीं मिला।',
                ],
                'error.no_answer_found' => [
                    'en' => 'No answer found.',
                    'hi' => 'कोई उत्तर नहीं मिला।',
                ],
                'error.no_comment_found' => [
                    'en' => 'No comment found.',
                    'hi' => 'कोई जवाब नहीं मिला।',
                ],
                'error.no_article_found' => [
                    'en' => 'No comment found.',
                    'hi' => 'कोई सामग्री नहीं मिला।',
                ],
                'error.no_challenge_found' => [
                    'en' => 'No challenge found.',
                    'hi' => 'कोई चुनौती नहीं मिला।',
                ],
                'share.live_test_summary' => [
                    'en' => "Hey,\n I just attempted a exam on DigiKoach on Ranked {0} from {1}.\n Come join Digikoach and show your talent.\nhttp://digikoach.com\nThanks",
                    'hi' => "अरे,\nमैंने सिर्फ {0} से {1} के रैंक पर डिजीकॉच पर एक परीक्षा का प्रयास किया।\nडिगिकोच से जुड़ें और अपनी प्रतिभा दिखाएं।\nhttp://digikoach.com\nधन्यवाद",
                ],
                'share.quizzes_summary' => [
                    'en' => "Hey,\n I just attempted a exam on DigiKoach on Ranked {0} from {1}.\n Come join Digikoach and show your talent.\nhttp://digikoach.com\nThanks",
                    'hi' => "अरे,\nमैंने सिर्फ {0} से {1} के रैंक पर डिजीकॉच पर एक परीक्षा का प्रयास किया।\nडिगिकोच से जुड़ें और अपनी प्रतिभा दिखाएं।\nhttp://digikoach.com\nधन्यवाद",
                ],
                'share.gkca_quizzes_summary' => [
                    'en' => "Hey,\n I just attempted a exam on DigiKoach on Ranked {0} from {1}.\n Come join Digikoach and show your talent.\nhttp://digikoach.com\nThanks",
                    'hi' => "अरे,\nमैंने सिर्फ {0} से {1} के रैंक पर डिजीकॉच पर एक परीक्षा का प्रयास किया।\nडिगिकोच से जुड़ें और अपनी प्रतिभा दिखाएं।\nhttp://digikoach.com\nधन्यवाद",
                ],
                'success.compared_exacellent_performance' => [
                    'en' => 'Your performance is better than {0}.',
                    'hi' => 'आपका प्रदर्शन {0} से बेहतर है।'
                ],
                'success.compared_low_performance' => ['en' => '{0}\'s performance is better than you.', 'hi' => '{0} का प्रदर्शन आपसे बेहतर है।'],
            ],

            'common' => [
                'txt_pop_remove_doubt' => [
                    'en' => 'Are you sure you want to remove this doubt?',
                    'hi' => 'क्या आप वाकई इस संदेह को दूर करना चाहते हैं?'
                ],
                'txt_second' => [
                    'en' => 'Seconds',
                    'hi' => 'सेकंड'
                ],
                'txt_seconds' => [
                    'en' => 'Seconds',
                    'hi' => 'सेकंड'
                ],
                'txt_minutes' => [
                    'en' => 'Minutes',
                    'hi' => 'मिनट'
                ],
                'txt_minute' => [
                    'en' => 'Minute',
                    'hi' => 'मिनट'
                ],
                'txt_hour' => [
                    'en' => 'Hour',
                    'hi' => 'घंटा'
                ],
                'txt_hours' => [
                    'en' => 'Hours',
                    'hi' => 'घंटे'
                ],
                'txt_day' => [
                    'en' => 'Day',
                    'hi' => 'दिन'
                ],
                'txt_days' => [
                    'en' => 'Days',
                    'hi' => 'दिन'
                ],
                'txt_reply_to_this_answer' => [
                    'en' => 'Reply to this answer',
                    'hi' => 'इस उत्तर का उत्तर दें'
                ],
                'txt_answers' => [
                    'en' => 'Answers',
                    'hi' => 'उत्तर'
                ],
                'txt_comments' => [
                    'en' => 'Comments',
                    'hi' => 'टिप्पणियाँ'
                ],
                'txt_you_can_select_only_one_image' => [
                    'en' => 'You can select only one image',
                    'hi' => 'आप केवल एक छवि का चयन कर सकते हैं'
                ],
                'txt_news' => [
                    'en' => 'News',
                    'hi' => 'समाचार'
                ],
                'txt_ask_your_doubts' => [
                    'en' => 'Ask your Doubts',
                    'hi' => 'अपने संदेह पूछता है'
                ],
                'txt_next' => [
                    'en' => 'Next',
                    'hi' => 'आगे'
                ],
                'txt_upvote' => [
                    'en' => 'Upvote',
                    'hi' => 'वोट दें'
                ],
                'txt_answer' => [
                    'en' => 'Answer',
                    'hi' => 'उत्तर'
                ],
                'txt_reply' => [
                    'en' => 'Reply',
                    'hi' => 'जवाब दो'
                ],
                'txt_add_to_my_doubts' => [
                    'en' => 'Add to My Doubts',
                    'hi' => 'मेरे संदेह में जोड़ें'
                ],
                'txt_remove' => [
                    'en' => 'Remove',
                    'hi' => 'हटाना'
                ],
                'txt_answer_doubts' => [
                    'en' => 'Answer Doubt',
                    'hi' => 'उत्तर संदेह'
                ],
                'txt_process_loast_question' => [
                    'en' => 'All written progress will be lost.',
                    'hi' => 'सभी लिखित प्रक्रिया खो जाएगी'
                ],
                'txt_exit_question' => [
                    'en' => 'Are you sure you want to exit?',
                    'hi' => 'क्या आप निश्चित हैं आपकी बाहर निकलने की इच्छा है?'
                ],
                'txt_select_relevant_tags' => [
                    'en' => 'Select Relevant Tags',
                    'hi' => 'प्रासंगिक टैग का चयन करें'
                ],
                'txt_suggested_subjects' => [
                    'en' => 'Suggested Subjects',
                    'hi' => 'सुझाए गए विषय'
                ],
                'txt_submit' => [
                    'en' => 'Submit',
                    'hi' => 'प्रस्तुत'
                ],
                'txt_verify' => [
                    'en' => 'Verify',
                    'hi' => 'सत्यापित करें'
                ],
                'txt_change' => [
                    'en' => 'Change',
                    'hi' => 'परिवर्तन'
                ],
                'txt_next' => [
                    'en' => 'Next',
                    'hi' => 'आगे'
                ],
                'txt_add' => [
                    'en' => 'Add',
                    'hi' => 'जोड़ना'
                ],
                'txt_resend' => [
                    'en' => 'Resend',
                    'hi' => 'पुनः भेजें'
                ],
                'txt_select_level' => [
                    'en' => 'Select Your Level',
                    'hi' => 'अपने स्तर का चयन करें'
                ],
                'txt_select_sub_level' => [
                    'en' => 'Select Your Sub Level',
                    'hi' => 'अपनी उप-स्तरीय खोज का चयन करें'
                ],
                'txt_yes' => [
                    'en' => 'Yes',
                    'hi' => 'हाँ'
                ],
                'txt_no' => [
                    'en' => 'No',
                    'hi' => 'नहीं'
                ],
                'txt_save' => [
                    'en' => 'Save',
                    'hi' => 'सहेजें'
                ],
                'txt_saved' => [
                    'en' => 'Saved',
                    'hi' => 'बचाया'
                ],
                'txt_share' => [
                    'en' => 'Share',
                    'hi' => 'शेयर'
                ],
                'txt_blogs' => [
                    'en' => 'Blogs',
                    'hi' => 'ब्लॉग'
                ],
                'txt_articles' => [
                    'en' => 'Articles',
                    'hi' => 'सामग्री'
                ],
                'txt_questions' => [
                    'en' => 'Questions',
                    'hi' => 'प्रशन'
                ],
                'txt_live_test' => [
                    'en' => 'Live Test',
                    'hi' => 'लाइव टेस्ट'
                ],
                'txt_quizzes' => [
                    'en' => 'Quizzes',
                    'hi' => 'क्विज़'
                ],
                'txt_gk_quiz' => [
                    'en' => 'Gk Quiz',
                    'hi' => 'Gk क्विज़'
                ],
                'txt_practice' => [
                    'en' => 'Practice',
                    'hi' => 'अभ्यास'
                ],
                'txt_gk_ca' => [
                    'en' => 'Gk & CA',
                    'hi' => 'Gk & CA'
                ],
                'txt_upcoming_test' => [
                    'en' => 'Upcoming Live Test',
                    'hi' => 'आगामी लाइव टेस्ट'
                ],
                'txt_current_affairs' => [
                    'en' => 'Current Affairs',
                    'hi' => 'सामयिकी'
                ],
                'txt_register' => [
                    'en' => 'Register',
                    'hi' => 'रजिस्टर करें'
                ],
                'txt_view' => [
                    'en' => 'View',
                    'hi' => 'राय'
                ],
                'txt_delete' => [
                    'en' => 'Delete',
                    'hi' => 'हटाएं'
                ],
                'txt_ongoing' => [
                    'en' => 'Ongoing',
                    'hi' => 'चल रही'
                ],
                'txt_attempted' => [
                    'en' => 'Attempted',
                    'hi' => 'प्रयास किया गया'
                ],
                'txt_unattempted' => [
                    'en' => 'Unattempted',
                    'hi' => 'प्रयास नहीं'
                ],
                'txt_short_ques' => [
                    'en' => 'Qs.',
                    'hi' => 'प्रश्न'
                ],
                'txt_short_mins' => [
                    'en' => 'Mins.',
                    'hi' => 'मि.'
                ],
                'txt_to' => [
                    'en' => 'TO',
                    'hi' => 'से'
                ],
                'txt_marks' => [
                    'en' => 'Marks',
                    'hi' => 'अंक'
                ],
                'txt_start_now' => [
                    'en' => 'Start Now',
                    'hi' => 'शुरू करें'
                ],
                'txt_registered' => [
                    'en' => 'Registered',
                    'hi' => 'पंजीकृत'
                ],
                'txt_check_status' => [
                    'en' => 'Check Status',
                    'hi' => 'अवस्था जांच'
                ],
                'txt_duration' => [
                    'en' => 'Duration',
                    'hi' => 'समयांतराल'
                ],
                'txt_max_marks' => [
                    'en' => 'Max Marks',
                    'hi' => 'अधिकतम अंक'
                ],
                'txt_agree_continue' => [
                    'en' => 'Agree & Continue',
                    'hi' => 'सहमत और जारी रखें'
                ],
                'txt_skip' => [
                    'en' => 'Skip',
                    'hi' => 'छोड़ें'
                ],
                'txt_submit_test' => [
                    'en' => 'Submit Test',
                    'hi' => 'टेस्ट जमा करें'
                ],
                'txt_thank_you' => [
                    'en' => 'Thank you!',
                    'hi' => 'धन्यवाद!'
                ],
                'txt_resume' => [
                    'en' => 'Resume',
                    'hi' => 'दुबारा आरम्भ करना'
                ],
                'txt_home' => [
                    'en' => 'Home',
                    'hi' => 'घर'
                ],
                'txt_result_waiting' => [
                    'en' => 'Waiting for result',
                    'hi' => 'परिणाम की प्रतीक्षा है'
                ],
                'txt_solution' => [
                    'en' => 'Solution',
                    'hi' => 'उपाय'
                ],
                'txt_solutions' => [
                    'en' => 'Solutions',
                    'hi' => 'समाधान'
                ],
                'txt_warning' => [
                    'en' => 'Warning',
                    'hi' => 'चेतावनी'
                ],
                'txt_unseen' => [
                    'en' => 'Unseen',
                    'hi' => 'अपठित'
                ],
                'txt_ok' => [
                    'en' => 'OK',
                    'hi' => 'ठीक है'
                ],
                'txt_result_on' => [
                    'en' => 'Result On',
                    'hi' => 'परिणाम जारी'
                ],
                'txt_rank' => [
                    'en' => 'Rank',
                    'hi' => 'पद'
                ],
                'txt_time' => [
                    'en' => 'Time',
                    'hi' => 'समय'
                ],
                'txt_correct' => [
                    'en' => 'Correct',
                    'hi' => 'सही'
                ],
                'txt_incorrect' => [
                    'en' => 'Incorrect',
                    'hi' => 'ग़लत'
                ],
                'txt_unattempted' => [
                    'en' => 'Unattempted',
                    'hi' => 'प्रयास नहीं'
                ],
                'txt_last_quiz' => [
                    'en' => 'Last Quiz',
                    'hi' => 'अंतिम प्रश्नोत्तरी',
                ],
                'txt_start_quiz' => [
                    'en' => 'Start Quiz',
                    'hi' => 'शुरू करें',
                ],
                'txt_resume_quiz' => [
                    'en' => 'Resume Quiz',
                    'hi' => 'फिर से शुरू करें',
                ],
                'txt_send_challenge' => [
                    'en' => 'Send Challenge & Start Test',
                    'hi' => 'चुनौती भेजें और परीक्षण शुरू करें',
                ],
                'txt_wrong' => [
                    'en' => 'Wrong',
                    'hi' => 'गलत',
                ],
                'txt_skipped' => [
                    'en' => 'Skipped',
                    'hi' => 'छोड़ा गया',
                ],
                'txt_your_answer' => [
                    'en' => 'Your Answer',
                    'hi' => 'आपका उत्तर',
                ],
                'txt_clear_all' => [
                    'en' => 'Clear All',
                    'hi' => 'सभी साफ करें',
                ],
                'txt_view_challenged_result' => [
                    'en' => 'View Challenged User Result',
                    'hi' => 'चुनौतीपूर्ण उपयोगकर्ता परिणाम देखें'
                ],
                'txt_choose_category' => [
                    'en' => 'Choose Category',
                    'hi' => 'वर्ग चुने'
                ],
                'txt_last_atttempt' => [
                    'en' => 'Last Attempt',
                    'hi' => 'अंतिम प्रयास',
                ],
                'txt_doubts' => [
                    'en' => 'Doubts',
                    'hi' => 'संदेह'
                ],
                'txt_just_now' => [
                    'en' => 'Just Now',
                    'hi' => 'अभी'
                ],
                'txt_ago' => [
                    'en' => 'ago',
                    'hi' => 'पहले'
                ],
                'txt_see_all' => [
                    'en' => 'See All',
                    'hi' => 'सभी देखें'
                ],
                'txt_start_test' => [
                    'en' => 'Start Test',
                    'hi' => 'टेस्ट शुरू करें'
                ],
            ],

            'tab' => [
                'label_all_doubts' => [
                    'en' => 'All Doubts',
                    'hi' => 'सभी संदेह'
                ],
                'label_my_doubts' => [
                    'en' => 'My Doubts',
                    'hi' => 'मेरी शंका'
                ],
                'label_my_answer' => [
                    'en' => 'My Answer',
                    'hi' => 'मेरी शंका'
                ],
                'label_exit' => [
                    'en' => 'EXIT',
                    'hi' => 'बाहर जाएं'
                ],
                'label_cancel' => [
                    'en' => 'CANCEL',
                    'hi' => 'रद्द करना'
                ],
                'label_post' => [
                    'en' => 'Post',
                    'hi' => 'पद'
                ],
                'label_home' => [
                    'en' => 'Home',
                    'hi' => 'घर'
                ],
                'label_blogs' => [
                    'en' => 'Blogs',
                    'hi' => 'ब्लॉग'
                ],
                'label_doubts' => [
                    'en' => 'Doubts',
                    'hi' => 'संदेह'
                ],
                'label_menu' => [
                    'en' => 'Menu',
                    'hi' => 'मेन्यू'
                ],
            ],

            'auth' => [
                'txt_login' => [
                    'en' => 'Login',
                    'hi' => 'लॉग इन'
                ],
                'txt_forgot_password' => [
                    'en' => 'Forgot Password?',
                    'hi' => 'पासवर्ड भूल गए?'
                ],
                'txt_dont_account' => [
                    'en' => 'Don\'t have account?',
                    'hi' => 'खाता नहीं है?'
                ],
                'txt_have_account' => [
                    'en' => 'Already have account?',
                    'hi' => 'पहले से खाता है?'
                ],
                'txt_remember_password' => [
                    'en' => 'Remember Password?',
                    'hi' => 'पासवर्ड याद रखें?'
                ],
                'txt_sign_up' => [
                    'en' => 'Sign Up',
                    'hi' => 'साइन अप करें'
                ],
                'txt_instruction_otp' => [
                    'en' => 'Please Enter the recieved OTP on your entered phone',
                    'hi' => 'कृपया अपने दर्ज किए गए फोन पर प्राप्त ओटीपी दर्ज करें।'
                ],
            ],

            'title' => [
                'txt_comment' => [
                    'en' => 'Comment',
                    'hi' => 'टिप्पणी'
                ],
                'txt_tell_doubt' => [
                    'en' => 'Tell us about your doubts',
                    'hi' => 'हमें अपने संदेह के बारे में बताएं'
                ],
                'txt_doubt_discussions' => [
                    'en' => 'Doubts & Discussions',
                    'hi' => 'संदेह और चर्चा'
                ],
                'txt_answer_to_this_doubt' => [
                    'en' => 'Answer to this Doubt',
                    'hi' => 'इस संदेह का जवाब'
                ],
                'txt_blogs' => [
                    'en' => 'Blogs',
                    'hi' => 'ब्लॉग'
                ],
                'txt_blog_detail' => [
                    'en' => 'Blog Detail',
                    'hi' => 'ब्लॉग विस्तार'
                ],
                'txt_blog_categories' => [
                    'en' => 'Blog Categories',
                    'hi' => 'ब्लॉग श्रेणियाँ'
                ],
                'txt_doubts' => [
                    'en' => 'Doubts',
                    'hi' => 'संदेह'
                ],
                'txt_profile_setup' => [
                    'en' => 'Profile Setup',
                    'hi' => 'प्रोफ़ाइल सेटअप',
                ],
                'txt_choose_level' => [
                    'en' => 'Choose Level',
                    'hi' => 'स्तर चुनें',
                ],
                'txt_choose_sub_level' => [
                    'en' => 'Choose Sub Level',
                    'hi' => 'सब लेवल चुनें',
                ],
                'txt_about_us' => [
                    'en' => 'About Us',
                    'hi' => 'हमारे बारे में',
                ],
                'txt_terms_conditions' => [
                    'en' => 'Terms & Conditions',
                    'hi' => 'नियम और शर्तें',
                ],
                'txt_privacy' => [
                    'en' => 'Privacy Policy',
                    'hi' => 'गोपनीयता नीति',
                ],
                'txt_change_password' => [
                    'en' => 'Change Password',
                    'hi' => 'पासवर्ड बदलें',
                ],
                'txt_settings' => [
                    'en' => 'Settings',
                    'hi' => 'समायोजन',
                ],
                'txt_my_profile' => [
                    'en' => 'My Profile',
                    'hi' => 'मेरी प्रोफाइल',
                ],
                'txt_saved_items' => [
                    'en' => 'Saved',
                    'hi' => 'सहेजें',
                ],
                'txt_live_test' => [
                    'en' => 'Live Test',
                    'hi' => 'लाइव टेस्ट'
                ],
                'txt_quizzes' => [
                    'en' => 'Quizzes',
                    'hi' => 'क्विज़'
                ],
                'txt_practice' => [
                    'en' => 'Practice',
                    'hi' => 'अभ्यास'
                ],
                'txt_gk_ca' => [
                    'en' => 'Gk & CA',
                    'hi' => 'सहेजें'
                ],
                'txt_instructions' => [
                    'en' => 'Instructions',
                    'hi' => 'निर्देश'
                ],
                'txt_confirmation' => [
                    'en' => 'Confirmation',
                    'hi' => 'पुष्टीकरण'
                ],
                'txt_summary' => [
                    'en' => 'Summary',
                    'hi' => 'सारांश'
                ],
                'txt_notifications' => [
                    'en' => 'Notifications',
                    'hi' => 'सूचनाएं'
                ],
                'txt_appeared_exams' => [
                    'en' => 'Your Appeared Exams',
                    'hi' => 'आपका प्रकट परीक्षा'
                ],
                'txt_performance' => [
                    'en' => 'Performance',
                    'hi' => 'प्रदर्शन'
                ],
                'txt_solutions' => [
                    'en' => 'Solutions',
                    'hi' => 'समाधान'
                ],
                'txt_live_test_result' => ['en' => 'Live Test Result', 'hi' => 'लाइव टेस्ट का परिणाम'],
                'txt_quizzes_result' => ['en' => 'Quizzes Result', 'hi' => 'क्विज़ का परिणाम'],
                'txt_practice_result' => ['en' => 'Practice Result', 'hi' => 'अभ्यास का परिणाम'],
                'txt_gkca_result' => ['en' => 'GKCA Result', 'hi' => 'GKCA का परिणाम'],
                'txt_live_test_solution' => ['en' => 'Live Test Solution', 'hi' => 'लाइव टेस्ट के उपाय'],
                'txt_quizzes_solution' => ['en' => 'Quizzes Solution', 'hi' => 'क्विज़ के उपाय'],
                'txt_practice_solution' => ['en' => 'Practice Solution', 'hi' => 'अभ्यास के उपाय'],
                'txt_gkca_solution' => ['en' => 'GKCA Solution', 'hi' => 'GKCA के उपाय'],
            ],

            'settings' => [
                'txt_about_us' => [
                    'en' => 'About Us',
                    'hi' => 'हमारे बारे में',
                ],
                'txt_terms_conditions' => [
                    'en' => 'Terms & Conditions',
                    'hi' => 'नियम और शर्तें',
                ],
                'txt_privacy' => [
                    'en' => 'Privacy Policy',
                    'hi' => 'गोपनीयता नीति',
                ],
                'txt_change_pass' => [
                    'en' => 'Change Password',
                    'hi' => 'पासवर्ड बदलें',
                ],
                'txt_deact_account' => [
                    'en' => 'Deactivate Account',
                    'hi' => 'खाता निष्क्रिय करें',
                ],
                'msg_deact_account' => [
                    'en' => 'Do you want to deactivate your account?',
                    'hi' => 'क्या आप अपने खाते को निष्क्रिय करना चाहते हैं?',
                ],
            ],

            'label' => [
                'otp' => [
                    'en' => 'OTP',
                    'hi' => 'OTP'
                ],
                'phone' => [
                    'en' => 'Phone',
                    'hi' => 'फ़ोन',
                ],
                'password' => [
                    'en' => 'Password',
                    'hi' => 'पास वर्ड',
                ],
                'new_password' => [
                    'en' => 'New Password',
                    'hi' => 'नया पासवर्ड',
                ],
                'confirm_password' => [
                    'en' => 'Confirm Password',
                    'hi' => 'पासवर्ड की पुष्टि कीजिये',
                ],
                'old_password' => [
                    'en' => 'Old Password',
                    'hi' => 'पुराना पासवर्ड',
                ],
                'email' => [
                    'en' => 'Email',
                    'hi' => 'ईमेल',
                ],
                'name' => [
                    'en' => 'Name',
                    'hi' => 'नाम'
                ],
            ],

            'placeholder' => [
                'search_subject_or_chapter' => [
                    'en' => 'Search for subject or chapter',
                    'hi' => 'विषय या अध्याय के लिए खोजें'
                ],
                'enter_phone' => [
                    'en' => 'Enter number',
                    'hi' => 'नंबर डालें',
                ],
                'enter_password' => [
                    'en' => 'Enter password',
                    'hi' => 'पास वर्ड दर्ज करें',
                ],
                'enter_otp' => [
                    'en' => 'Enter OTP',
                    'hi' => 'OTP दर्ज करें',
                ],
                'enter_new_pass' => [
                    'en' => 'Enter new password',
                    'hi' => 'नया पासवर्ड दर्ज करें',
                ],
                'enter_confirm_pass' => [
                    'en' => 'Enter confirm password',
                    'hi' => 'पासवर्ड की पुष्टि करें',
                ],
                'enter_old_pass' => [
                    'en' => 'Enter old password',
                    'hi' => 'पुराना पासवर्ड दर्ज करें',
                ],
                'enter_name' => [
                    'en' => 'Enter Name',
                    'hi' => 'नाम दर्ज करें',
                ],
                'enter_email' => [
                    'en' => 'Enter Email',
                    'hi' => 'ईमेल दर्ज करें',
                ],
                'search' => [
                    'en' => 'Search',
                    'hi' => 'खोज',
                ],
            ],
            'drawer' => [
                'txt_my_profile' => [
                    'en' => 'My Profile',
                    'hi' => 'मेरी प्रोफाइल',
                ],
                'txt_appread_exams' => [
                    'en' => 'Your Appeared Exams',
                    'hi' => 'आपका प्रकट परीक्षा',
                ],
                'txt_saved' => [
                    'en' => 'Saved',
                    'hi' => 'सहेजें',
                ],
                'txt_performance' => [
                    'en' => 'Performance',
                    'hi' => 'प्रदर्शन',
                ],
                'txt_settings' => [
                    'en' => 'Settings',
                    'hi' => 'समायोजन',
                ],
                'txt_feedback' => [
                    'en' => 'Feedback',
                    'hi' => 'प्रतिपुष्टि',
                ],
                'txt_logout' => [
                    'en' => 'Logout',
                    'hi' => 'लॉग आउट',
                ],
            ],
            'notification_message' => [
                'live_test_started_msg' => [
                    'en' => 'Your Live Test named TEST_NAME is going to start',
                    'hi' => 'TEST_NAME नाम से आपका लाइव टेस्ट शुरू होने जा रहा है|'
                ],
                'live_test_before_1h_msg' => [
                    'en' => 'Your Live Test named TEST_NAME is going to start in one hour',
                    'hi' => 'TEST_NAME नाम से आपका लाइव टेस्ट एक घंटे में शुरू होने जा रहा है'
                ],
                'new_blog_add_msg' => [
                    'en' => 'New Blog Added',
                    'hi' => 'नया ब्लॉग जोड़ा गया'
                ],
                'article_news_add_msg' => [
                    'en' => 'New News And Article Added',
                    'hi' => 'नई खबर और अनुच्छेद जोड़ा गया'
                ],
                'practice_test_add_msg' => [
                    'en' => 'A practice test named TEST_NAME is added',
                    'hi' => 'TEST_NAME नाम का एक अभ्यास परीक्षण जोड़ा गया है'
                ],
                'gk_ca_test_add_msg' => [
                    'en' => 'A gk ca quiz test named TEST_NAME is added',
                    'hi' => 'TEST_NAME नाम का एक gk ca प्रश्नोत्तरी परीक्षण जोड़ा गया है'
                ],
                'quiz_test_add_msg' => [
                    'en' => 'A quiz test named TEST_NAME is added',
                    'hi' => 'TEST_NAME नाम का एक प्रश्नोत्तरी परीक्षण जोड़ा गया है'
                ],
                'live_test_add_msg' => [
                    'en' => 'A live test named TEST_NAME is added',
                    'hi' => 'TEST_NAME नाम का एक लाइव परीक्षण जोड़ा गया है'
                ],
                'live_test_result_announce_msg' => [
                    'en' => 'Result for your live test named TEST_NAME is announced.',
                    'hi' => 'TEST_NAME नाम की आपकी लाइव परीक्षा का परिणाम घोषित किया गया है।'
                ],
                'exam_challenge' => [
                    'en' => ':name invited you for challenge.',
                    'hi' => ':name ने आपको चुनौती के लिए आमंत्रित किया।'
                ],
                'doubt_answered' => [
                    'en' => ' answered your doubt.',
                    'hi' => ' ने आपकी शंका का जवाब दिया।'
                ],
                'doubt_replied' => [
                    'en' => 'replied to your answer.',
                    'hi' => 'ने तुम्हारे शंका का उत्तर दिया।'
                ]
            ],
            'notification_title' => [
                'live_test_started_tile' => [
                    'en' => 'Your Live Test is going to start',
                    'hi' => 'आपका लाइव टेस्ट शुरू होने जा रहा है|'
                ],
                'live_test_before_1h_title' => [
                    'en' => 'Your Live Test is going to start soon',
                    'hi' => 'आपका लाइव टेस्ट जल्द ही शुरू होने वाला है'
                ],
                'new_blog_add_title' => [
                    'en' => 'New Blog Added',
                    'hi' => 'नया ब्लॉग जोड़ा गया'
                ],
                'article_news_add_title' => [
                    'en' => 'New News And Article Added',
                    'hi' => 'नई खबर और अनुच्छेद जोड़ा गया'
                ],
                'practice_test_add_title' => [
                    'en' => 'A new practice test is added',
                    'hi' => 'नई प्रैक्टिस टेस्ट जोड़ा गया'
                ],
                'gk_ca_test_add_title' => [
                    'en' => 'A new gk ca quiz test is added',
                    'hi' => 'एक नया जीके सीए क्विज़ टेस्ट जोड़ा गया है'
                ],
                'quiz_test_add_title' => [
                    'en' => 'A new quiz test is added',
                    'hi' => 'एक नया प्रश्नोत्तरी परीक्षण जोड़ा गया है'
                ],
                'live_test_add_title' => [
                    'en' => 'A new live test is added',
                    'hi' => 'एक नया लाइव परीक्षण जोड़ा गया है'
                ],
                'live_test_result_announce_title' => [
                    'en' => 'Your Live Test Result Has Been Announced',
                    'hi' => 'आपका लाइव टेस्ट रिजल्ट घोषित हो गया है'
                ],
                'exam_challenge' => [
                    'en' => 'New Exam Challenge',
                    'hi' => 'नई परीक्षा चुनौती'
                ],
                'doubt_answered' => [
                    'en' => 'Doubt Answered',
                    'hi' => 'शंका का उत्तर दिया'
                ],
                'doubt_replied' => [
                    'en' => 'Doubt Replied',
                    'hi' => 'संदेह किया'
                ]
            ]
        ];
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->trans_data as $group => $group_values) {
            foreach ($group_values as $key => $text_array) {
                $checkTrans = DB::table('translations')->where('group', $group)->where('key', $key)->first();
                if ($checkTrans) continue;

                DB::table('translations')->insert([
                    'group' => $group,
                    'key' => $key,
                    'text' => json_encode($text_array),
                    'created_at' => DB::raw('now()')
                ]);
            }
        }
    }
}
