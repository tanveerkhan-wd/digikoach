
const firebaseConfig = {
    apiKey: "AIzaSyA-7U6wAmJ_OztAJ3kdETYN2Ft9D6mCrHY",
    authDomain: "digikoach-f4c10.firebaseapp.com",
    databaseURL: "https://digikoach-f4c10.firebaseio.com",
    projectId: "digikoach-f4c10",
    storageBucket: "digikoach-f4c10.appspot.com",
    messagingSenderId: "351651372884",
    appId: "1:351651372884:web:47026a90c69d5fc8c072c1",
    measurementId: "G-7F2Q4GK7S1"
};
// Initialize Firebase
firebase.initializeApp(firebaseConfig);
//firebase.analytics();
const messaging = firebase.messaging();
// Add the public key generated from the console here.
//messaging.usePublicVapidKey("AAAAUeAOH1Q:APA91bGyBMQYi1FbZANrnFhoMcC1oba_urCqJBoWdQ8l14PpdFrKx-BjmJYUf7JzWTF57ji7ijrKuRzUm_ZTK2VSUKd_9uP6OqBRkmRPrK5LdyhCsv9I9l1_RCoam2uaml0lVLJyvTuw");
messaging.usePublicVapidKey("BMYRDLnFJSRm0l8R4r-swrOeY5UyDu3h8W8m6aFrHtrl6XYzM9-zjmLpvbQyA9MduJGdcA_CBItt3Xx6Bm4dwOM");

messaging
    .requestPermission()
    .then(function () {
        // MsgElem.innerHTML = "Notification permission granted." 
        console.log("Notification permission granted.");
        // get the token in the form of promise
        return messaging.getToken()
    })
    .then(function(token) {
        if(token != null && token != '')
        {
            $.ajax({
                 type: 'post',
                 url: base_url+'/admin/token-save',
                 headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                 data: {token:token,"_token": $('meta[name="csrf-token"]').attr('content')},
                 success: function (response) {
                   console.log(response);
                 },
                 error: function (response) {
                  //alert("Error");
                 }
            });
        }
        // TokenElem.innerHTML = "token is : " + token
    })
    .catch(function (err) {
        // ErrElem.innerHTML =  ErrElem.innerHTML + "; " + err
        console.log("Unable to get permission to notify.", err);
    });

    messaging.onMessage(function(payload) {
    console.log("Message received. ", payload);
    // NotisElem.innerHTML = NotisElem.innerHTML + JSON.stringify(payload) 
});
/*messaging.setBackgroundMessageHandler(function(payload) {
    const notificationTitle = payload.title;
    const notificationOptions = {
        body: payload.body,
        icon: 'alarm.png'
    };

    return self.registration.showNotification(notificationTitle, notificationOptions);
});*/