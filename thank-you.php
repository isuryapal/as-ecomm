<?php
	ob_start();
?>

<!DOCTYPE html>

<html>

<head>

	<meta charset="utf-8"> <!-- utf-8 works for most cases -->

	<meta name="viewport" content="width=device-width"> <!-- Forcing initial-scale shouldn't be necessary -->

	<meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Use the latest (edge) version of IE rendering engine -->

    <meta name="x-apple-disable-message-reformatting">  <!-- Disable auto-scale in iOS 10 Mail entirely -->

	<title>Welcome to Arvind Sanitary</title>

    <link rel="icon" type="image/ico" href="../../images/favicon(1).png">



	<!-- Web Font / @font-face : BEGIN -->

	<!-- NOTE: If web fonts are not required, lines 10 - 27 can be safely removed. -->

	

	<!-- Desktop Outlook chokes on web font references and defaults to Times New Roman, so we force a safe fallback font. -->

	<!--[if mso]>

		<style>

			* {

				font-family: sans-serif !important;

			}

		</style>

	<![endif]-->

	

	<!-- All other clients get the webfont reference; some will render the font and others will silently fail to the fallbacks. More on that here: http://stylecampaign.com/blog/2015/02/webfont-support-in-email/ -->

	<!--[if !mso]><!-->

		<!-- insert web font reference, eg: <link href='https://fonts.googleapis.com/css?family=Roboto:400,700' rel='stylesheet' type='text/css'> -->

	<!--<![endif]-->



	<!-- Web Font / @font-face : END -->

	

	<!-- CSS Reset -->

    <style>



		/* What it does: Remove spaces around the email design added by some email clients. */

		/* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */

        html,

        body {

	        margin: 0 auto !important;

            padding: 0 !important;

            height: 100% !important;

            width: 100% !important;

        }

        

        /* What it does: Stops email clients resizing small text. */

        * {

            -ms-text-size-adjust: 100%;

            -webkit-text-size-adjust: 100%;

        }

        

        /* What is does: Centers email on Android 4.4 */

        div[style*="margin: 16px 0"] {

            margin:0 !important;

        }

        

        /* What it does: Stops Outlook from adding extra spacing to tables. */

        table,

        td {

            mso-table-lspace: 0pt !important;

            mso-table-rspace: 0pt !important;

        }

                

        /* What it does: Fixes webkit padding issue. Fix for Yahoo mail table alignment bug. Applies table-layout to the first 2 tables then removes for anything nested deeper. */

        table {

            border-spacing: 0 !important;

            border-collapse: collapse !important;

            table-layout: fixed !important;

            margin: 0 auto !important;

        }

        table table table {

            table-layout: auto; 

        }

        

        /* What it does: Uses a better rendering method when resizing images in IE. */

        img {

            -ms-interpolation-mode:bicubic;

        }

        

        /* What it does: A work-around for iOS meddling in triggered links. */

        .mobile-link--footer a,

        a[x-apple-data-detectors] {

            color:inherit !important;

            text-decoration: underline !important;

        }



        /* What it does: Prevents underlining the button text in Windows 10 */

        .button-link {

            text-decoration: none !important;

        }

        

        .mail-body{

             background-image: url("../../images/gift-bg.png");

             background-size: 100%;

        }

    </style>

    

    <!-- Progressive Enhancements -->

    <style>

        

        /* What it does: Hover styles for buttons */

        .button-td,

        .button-a {

            transition: all 100ms ease-in;

        }

        .button-td:hover,

        .button-a:hover {

            background: #555555 !important;

            border-color: #555555 !important;

        }



        /* Media Queries */

        @media screen and (max-width: 600px) {



            .email-container {

                width: 100% !important;

                margin: auto !important;

            }



            /* What it does: Forces elements to resize to the full width of their container. Useful for resizing images beyond their max-width. */

            .fluid {

                max-width: 100% !important;

                height: auto !important;

                margin-left: auto !important;

                margin-right: auto !important;

            }



            /* What it does: Forces table cells into full-width rows. */

            .stack-column,

            .stack-column-center {

                display: block !important;

                width: 100% !important;

                max-width: 100% !important;

                direction: ltr !important;

            }

            /* And center justify these ones. */

            .stack-column-center {

                text-align: center !important;

            }

        

            /* What it does: Generic utility class for centering. Useful for images, buttons, and nested tables. */

            .center-on-narrow {

                text-align: center !important;

                display: block !important;

                margin-left: auto !important;

                margin-right: auto !important;

                float: none !important;

            }

            table.center-on-narrow {

                display: inline-block !important;

            }

                

        }



    </style>



</head>

<body width="100%" bgcolor="#f2f2f2" style="margin: 0; mso-line-height-rule: exactly;">

    <center style="width: 100%; background: #f2f2f2;">        

        <table cellspacing="0" cellpadding="0" border="0" align="center" width="600" style="margin:auto;background:#ffffff" class="m_4983910951660327106m_602368811542098306email-container">

			<tbody>

				<tr>

					<td style="padding:20px 0;text-align:center">

						<a href="<?php echo BASE_URL; ?>" width="200" height="50" alt="<?php echo SITE_NAME; ?>" border="0" style="height:auto;background:#ffffff;font-family:sans-serif;font-size:15px;line-height:20px;color:#555555" class="CToWUd">

							<img src="<?php echo BASE_URL; ?>/images/logo.png" style="width: 181px;">

						</a>

					</td>

				</tr>

	        </tbody>

	    </table>

	    <table cellspacing="0" cellpadding="0" border="0" align="center" width="600" style="margin:auto;background:#f5ae1a" class="m_4983910951660327106m_602368811542098306email-container">

			<tbody>

				<tr>

					<td style="padding:15px 0;text-align:center;font-family:sans-serif;color:#3a3a3a;font-size:20px">

						<?php echo $name; ?>

					</td>

				</tr>

	        </tbody>

	    </table>	        

	    <table cellspacing="0" cellpadding="0" border="0" align="center" width="600" style="margin:auto" class="m_4983910951660327106m_602368811542098306email-container">

	        <tbody class="mail-body">

	        	<tr>

	                <td style="padding:10px 40px;text-align:left;font-family:sans-serif;font-size:15px;line-height:20px;color:#555555">

						<h4 style="color:#673b95;font-size:20px;font-weight:normal"><?php echo $h4heading;  ?></h4>

						<?php echo $content; ?> 

						<br>

						<?php if(!empty($button)){ ?>

							<table cellspacing="0" cellpadding="0" border="0" align="left" style="margin:auto">

								<tbody>

									<tr>

										<td style="border-radius:3px;background:#ed8d29;text-align:center" class="m_4983910951660327106m_602368811542098306button-td">

											 <?php echo $button; ?>

										</td>

									</tr>

								</tbody>

							</table>

						<?php } ?>

						<table style="width:100%" cellpadding="0" cellspacing="0">

							<tbody>

								<tr>

									<td>

										<br>

										<p>Warm Regards,</p>

										<p>Team <?php echo SITE_NAME; ?></p>

										<?php if(isset($footer) && !empty($footer)){ echo $footer;  } ?>

										

										<center><small>Copyright © 2018 <?php echo SITE_NAME; ?>. All rights reserved</small></center>

										<br>

									</td>

								</tr>

							</tbody>

						</table>

	                </td>

	            </tr>

	        </tbody>

	    </table>

    </center>

</body>

</html>

<?php 

	//EXIT;

	$emailMsg = ob_get_contents();

	ob_end_clean();

?>



