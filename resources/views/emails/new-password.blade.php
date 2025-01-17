<!DOCTYPE html>
<html lang="en">

<body>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<meta content="width=device-width" name="viewport">
<style type="text/css">
    @font-face {
        font-family: & #x27;
        Postmates Std & #x27;;
        font-weight: 600;
        font-style: normal;
        src: local(& #x27;
        Postmates Std Bold & #x27;), url(https://s3-us-west-1.amazonaws.com/buyer-static.postmates.com/assets/email/postmates-std-bold.woff) format(&#x27;
        woff & #x27;);
    }

    @font-face {
        font-family: & #x27;
        Postmates Std & #x27;;
        font-weight: 500;
        font-style: normal;
        src: local(& #x27;
        Postmates Std Medium & #x27;), url(https://s3-us-west-1.amazonaws.com/buyer-static.postmates.com/assets/email/postmates-std-medium.woff) format(&#x27;
        woff & #x27;);
    }

    @font-face {
        font-family: & #x27;
        Postmates Std & #x27;;
        font-weight: 400;
        font-style: normal;
        src: local(& #x27;
        Postmates Std Regular & #x27;), url(https://s3-us-west-1.amazonaws.com/buyer-static.postmates.com/assets/email/postmates-std-regular.woff) format(&#x27;
        woff & #x27;);
    }
</style>
<style media="screen and (max-width: 680px)">
    @media screen and (max-width: 680px) {
        .page-center {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .footer-center {
            padding-left: 20px !important;
            padding-right: 20px !important;
        }
    }
</style>
</head>
<body style="background-color: #f4f4f5;">
<table cellpadding="0" cellspacing="0"
       style="width: 100%; height: 100%; background-color: #f4f4f5; text-align: center;">
    <tbody>
    <tr>
        <td style="text-align: center;">
            <table align="center" cellpadding="0" cellspacing="0" id="body"
                   style="background-color: #fff; width: 100%; max-width: 680px; height: 100%;">
                <tbody>
                <tr>
                    <td>
                        <table align="center" cellpadding="0" cellspacing="0" class="page-center"
                               style="text-align: left; padding-bottom: 88px; width: 100%; padding-left: 120px; padding-right: 120px;">
                            <tbody>
                            <tr>
                                <td colspan="2"
                                    style="padding-top: 72px; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: 100%; color: #000000; font-family: 'Postmates Std', 'Helvetica', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif; font-size: 48px; font-smoothing: always; font-style: normal; font-weight: 600; letter-spacing: -2.6px; line-height: 52px; mso-line-height-rule: exactly; text-decoration: none;">
                                    GTFS Manager
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top: 48px; padding-bottom: 48px;">
                                    <table cellpadding="0" cellspacing="0" style="width: 100%">
                                        <tbody>
                                        <tr>
                                            <td style="width: 100%; height: 1px; max-height: 1px; background-color: #d9dbe0; opacity: 0.81"></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="-ms-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: 100%; color: #9095a2; font-family: 'Postmates Std', 'Helvetica', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif; font-size: 24px; font-smoothing: always; font-style: normal; font-weight: 400; letter-spacing: -0.18px; line-height: 24px; mso-line-height-rule: exactly; text-decoration: none; vertical-align: top; width: 100%;">
                                    Hello {{ $user->name }}!
                                    <br/>
                                    You forgot your credentials and a new password has been generated:
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"
                                    style="padding-top: 30px; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: 100%; color: #000000; font-family: 'Postmates Std', 'Helvetica', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif; font-size: 38px; font-smoothing: always; font-style: normal; font-weight: 600; letter-spacing: -2.6px; line-height: 52px; mso-line-height-rule: exactly; text-decoration: none;">
                                    {{ $password }}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top: 30px; -ms-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: 100%; color: #9095a2; font-family: 'Postmates Std', 'Helvetica', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif; font-size: 24px; font-smoothing: always; font-style: normal; font-weight: 400; letter-spacing: -0.18px; line-height: 24px; mso-line-height-rule: exactly; text-decoration: none; vertical-align: top; width: 100%;">
                                    To access, make a POST to <strong>"/api/login"</strong> stating your email and password.
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top: 30px; -ms-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: 100%; color: #9095a2; font-family: 'Postmates Std', 'Helvetica', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif; font-size: 24px; font-smoothing: always; font-style: normal; font-weight: 400; letter-spacing: -0.18px; line-height: 24px; mso-line-height-rule: exactly; text-decoration: none; vertical-align: top; width: 100%;">
                                    To change your password, check the documentation on the <strong>"api/change-password"</strong> route.
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top: 30px; -ms-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: 100%; color: #9095a2; font-family: 'Postmates Std', 'Helvetica', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif; font-size: 24px; font-smoothing: always; font-style: normal; font-weight: 400; letter-spacing: -0.18px; line-height: 24px; mso-line-height-rule: exactly; text-decoration: none; vertical-align: top; width: 100%;">
                                    For more route information, access the documentation on the project's root route.
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
            <table align="center" cellpadding="0" cellspacing="0" id="footer"
                   style="background-color: #000; width: 100%; max-width: 680px; height: 100%;">
                <tbody>
                <tr>
                    <td>
                        <table align="center" cellpadding="0" cellspacing="0" class="footer-center"
                               style="text-align: left; width: 100%; padding-left: 120px; padding-right: 120px;">
                            <tbody>
                            <tr>
                                <td style="padding-top: 72px; -ms-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: 100%; color: #9095A2; font-family: 'Postmates Std', 'Helvetica', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif; font-size: 15px; font-smoothing: always; font-style: normal; font-weight: 400; letter-spacing: 0; line-height: 24px; mso-line-height-rule: exactly; text-decoration: none; vertical-align: top; width: 100%;">
                                    Implemented for a graduate work in the Computer Science course.
                                </td>
                            </tr>
                            <tr>
                                <td style="height: 72px;"></td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>


</body>


</body>

</html>
