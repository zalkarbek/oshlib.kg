@extends('layouts.auth.default')
@section('content')
    <div class="card-body login-card-body">
        <p class="login-box-msg">{{__('auth.register_new_member')}}</p>

        <form>
            <label>Phone Number:</label>

            <input type="text" id="number" class="form-control" placeholder="+996 ********">

            <div id="recaptcha-container"></div>

            <button type="button" id="send-button" class="btn btn-primary mt-3">Send OTP</button>
        </form>


        <div class="mb-5 mt-5">
            <h3>Add verification code</h3>

            <div class="alert alert-success" id="successOtpAuth" style="display: none;"></div>

            <form>
                <input type="text" id="verification" class="form-control" placeholder="Verification code">
                <button type="button" class="btn btn-danger mt-3" onclick="verify()">Verify code</button>
            </form>
        </div>

        <p class="mb-1 text-center">
            <a href="{{ url('/login') }}">{{__('auth.already_member')}}</a>
        </p>
    </div>
    <!-- /.login-card-body -->
@endsection
@section('scripts')
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/9.2.0/firebase-app.js";
        import { getAuth, RecaptchaVerifier, signInWithPhoneNumber } from "https://www.gstatic.com/firebasejs/9.2.0/firebase-auth.js";
        // Your web app's Firebase configuration
        const firebaseConfig = {
            apiKey: "AIzaSyDkmQT8WyNFRwTSzvBYlB_xUNDqQS_UMjk",
            authDomain: "luckychat-286b1.firebaseapp.com",
            databaseURL: "https://luckychat-286b1.firebaseio.com",
            projectId: "luckychat-286b1",
            storageBucket: "luckychat-286b1.appspot.com",
            messagingSenderId: "74950354939",
            appId: "1:74950354939:web:6e54a8159ed01be3eecd5e"
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const auth = getAuth();

        const appVerifier = window.recaptchaVerifier;

        window.onload = function() {
            window.recaptchaVerifier = new RecaptchaVerifier('recaptcha-container', {
                'size': 'invisible',
                'callback': (response) => {
                    // reCAPTCHA solved, allow signInWithPhoneNumber.
                    console.log('send');
                    sendOTP();
                }
            }, auth);
            recaptchaVerifier.render();
            $("#send-button").on('click', function() {
                sendOTP();
            });
        };

        function sendOTP() {
            var number = $("#number").val();
            signInWithPhoneNumber(auth, number, window.recaptchaVerifier)
                    .then((confirmationResult) => {
                        // SMS sent. Prompt user to type the code from the message, then sign the
                        // user in with confirmationResult.confirm(code).
                        window.confirmationResult = confirmationResult;
                        console.log(confirmationResult);
                        // ...
                    }).catch((error) => {
                // Error; SMS not sent
                // ...
                console.log('error');
                console.log(error);
            });
        }

    </script>
    <script type="text/javascript">

        function verify() {
            var code = $("#verification").val();
            coderesult.confirm(code).then(function (result) {
                var user = result.user;
                console.log(user);
                $("#successOtpAuth").text("Auth is successful");
                $("#successOtpAuth").show();
            }).catch(function (error) {
                $("#error").text(error.message);
                $("#error").show();
            });
        }
    </script>
@endsection