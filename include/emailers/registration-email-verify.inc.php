<?php
   ob_start();
?>
<!DOCTYPE html>
<html>
<head>
   <title>Welcome to <?php echo SITE_NAME; ?></title>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <style type="text/css">
      /* CLIENT-SPECIFIC STYLES */
      body, table, td, a{-webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;} /* Prevent WebKit and Windows mobile changing default text sizes */
      table, td{mso-table-lspace: 0pt; mso-table-rspace: 0pt;} /* Remove spacing between tables in Outlook 2007 and up */
      img{-ms-interpolation-mode: bicubic;} /* Allow smoother rendering of resized image in Internet Explorer */

      /* RESET STYLES */
      img{border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none;}
      table{border-collapse: collapse !important;}
      body{height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important;}

      /* iOS BLUE LINKS */
      a[x-apple-data-detectors] {
         color: inherit !important;
         text-decoration: none !important;
         font-size: inherit !important;
         font-family: inherit !important;
         font-weight: inherit !important;
         line-height: inherit !important;
      }

      /* MOBILE STYLES */
      @media screen and (max-width: 525px) {

         /* ALLOWS FOR FLUID TABLES */
         .wrapper {
           width: 100% !important;
            max-width: 100% !important;
         }

         /* ADJUSTS LAYOUT OF LOGO IMAGE */
         .logo img {
           margin: 0 auto !important;
         }

         /* USE THESE CLASSES TO HIDE CONTENT ON MOBILE */
         .mobile-hide {
           display: none !important;
         }

         .img-max {
           max-width: 100% !important;
           width: 100% !important;
           height: auto !important;
         }

         /* FULL-WIDTH TABLES */
         .responsive-table {
           width: 100% !important;
         }

         /* UTILITY CLASSES FOR ADJUSTING PADDING ON MOBILE */
         .padding {
           padding: 10px 5% 15px 5% !important;
         }

         .padding-meta {
           padding: 30px 5% 0px 5% !important;
           text-align: center;
         }

         .padding-copy {
             padding: 10px 5% 10px 5% !important;
           text-align: center;
         }

         .no-padding {
           padding: 0 !important;
         }

         .section-padding {
           padding: 50px 15px 50px 15px !important;
         }

         /* ADJUST BUTTONS ON MOBILE */
         .mobile-button-container {
            margin: 0 auto;
            width: 100% !important;
         }

         .mobile-button {
            padding: 15px !important;
            border: 0 !important;
            font-size: 16px !important;
            display: block !important;
         }

      }

      /* ANDROID CENTER FIX */
      div[style*="margin: 16px 0;"] { margin: 0 !important; }
   </style>
</head>
<body style="margin: 0 !important; padding: 0 !important;">

<!-- ONE COLUMN SECTION -->
<table border="0" cellpadding="0" cellspacing="0" width="100%">
   <tr>
        <td bgcolor="#ffffff" align="center">
            <!--[if (gte mso 9)|(IE)]>
            <table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
            <tr>
            <td align="center" valign="top" width="500">
            <![endif]-->
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 500px;" class="wrapper">
                <tr>
                    <td align="center" valign="top" style="padding: 15px 0;" class="logo">
                        <a href="<?php echo BASE_URL; ?>" target="_blank">
                            <img alt="<?php echo SITE_NAME; ?>" src="<?php echo LOGO; ?>" width="200" style="display: block; font-family: Helvetica, Arial, sans-serif; color: #ffffff; font-size: 16px;" border="0">
                        </a>
                    </td>
                </tr>
            </table>
            <!--[if (gte mso 9)|(IE)]>
            </td>
            </tr>
            </table>
            <![endif]-->
        </td>
    </tr>
	<tr>
        <td bgcolor="#2a1966" align="center" style="padding: 15px;" class="section-padding">
            <!--[if (gte mso 9)|(IE)]>
            <table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
            <tr>
            <td align="center" valign="top" width="500">
            <![endif]-->
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 500px;" class="responsive-table">
                <tr>
                    <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td align="center" style="padding: 10px 0 10px 0;font-weight:bold; font-size: 30px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #ffffff;" class="padding-copy">Welcome To <?php echo SITE_NAME; ?></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <!--[if (gte mso 9)|(IE)]>
            </td>
            </tr>
            </table>
            <![endif]-->
        </td>
    </tr>
    <tr>
        <td bgcolor="#ffffff" align="center" style="padding: 15px;" class="section-padding">
            <!--[if (gte mso 9)|(IE)]>
            <table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
            <tr>
            <td align="center" valign="top" width="500">
            <![endif]-->
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 500px;" class="responsive-table">
                <tr>
                    <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td align="left" style="padding: 20px 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding-copy">Hi <?php echo $newCustomerDetails['name']; ?>,</td>
                                        </tr>
                                        <tr>
                                            <td align="left" style="padding: 20px 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding-copy">Welcome to <?php echo SITE_NAME; ?>. We are very excited to have you on board.</td>
                                        </tr>

                                        <?php if(isset($newCustomerDetails['email_verification_token']) && !empty($newCustomerDetails['email_verification_token'])){ ?>

                                                <tr>
                                                    <td align="left" style="padding: 20px 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding-copy">Please click on the link below to verify your e-mail address and activate your account.<br/><br/>

                                       <a href="<?php echo BASE_URL."/verify-customer-email.php?v=".$newCustomerDetails['email_verification_token']; ?>" style="font-size:14px; color:#2a1966;">Verify my email address</a>

                                       </td>
                                                </tr>
                                        <?php   } ?>

                              <tr>
                                            <td align="left" style="padding: 20px 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding-copy">
                                 <br/>Warm Regards<br/>
                                 Team <?php echo SITE_NAME; ?>
                                 </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <!--[if (gte mso 9)|(IE)]>
            </td>
            </tr>
            </table>
            <![endif]-->
        </td>
    </tr>
    <tr>
        <td bgcolor="#ffffff" align="center" style="padding: 20px 0px;">
            <!--[if (gte mso 9)|(IE)]>
            <table align="center" border="0" cellspacing="0" cellpadding="0" width="500">
            <tr>
            <td align="center" valign="top" width="500">
            <![endif]-->
            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="max-width: 500px;" class="responsive-table">
                <tr>
                    <td align="left" style="font-size: 12px; line-height: 18px; font-family: Helvetica, Arial, sans-serif; color:#666666;">
                  Copyright &copy; 2000-<?php echo date("Y"); ?> <?php echo SITE_NAME; ?>. All rights reserved. 
                    </td>
                </tr>
            </table>
            <!--[if (gte mso 9)|(IE)]>
            </td>
            </tr>
            </table>
            <![endif]-->
        </td>
    </tr>
</table>

</body>
</html>
<?php 
   $emailMsg = ob_get_contents();
   ob_end_clean();

?>