<?php
	ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Order Cancelled</title>
    <style>


		 @import url(https://fonts.googleapis.com/css?family=Roboto:300); /*Calling our web font*/

		/* Some resets and issue fixes */
        #outlook a { padding:0; }
		body{ width:100% !important; -webkit-text; size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0; }     
        .ReadMsgBody { width: 100%; }
        .ExternalClass {width:100%;} 
        .backgroundTable {margin:0 auto; padding:0; width:100%;!important;} 
        table td {border-collapse: collapse;}
        .ExternalClass * {line-height: 115%;}	
        
        /* End reset */
		
		
		/* These are our tablet/medium screen media queries */
        @media screen and (max-width: 630px){
                
				
			/* Display block allows us to stack elements */                      
            *[class="mobile-column"] {display: block;} 
			
			/* Some more stacking elements */
            *[class="mob-column"] {float: none !important;width: 100% !important;}     
			     
			/* Hide stuff */
            *[class="hide"] {display:none !important;}          
            
			/* This sets elements to 100% width and fixes the height issues too, a god send */
			*[class="100p"] {width:100% !important; height:auto !important;}			        
				
			/* For the 2x2 stack */			
			*[class="condensed"] {padding-bottom:40px !important; display: block;}
			
			/* Centers content on mobile */
			*[class="center"] {text-align:center !important; width:100% !important; height:auto !important;}            
			
			/* 100percent width section with 20px padding */
			*[class="100pad"] {width:100% !important; padding:20px;} 
			
			/* 100percent width section with 20px padding left & right */
			*[class="100padleftright"] {width:100% !important; padding:0 20px 0 20px;} 
			
			/* 100percent width section with 20px padding top & bottom */
			*[class="100padtopbottom"] {width:100% !important; padding:20px 0px 20px 0px;} 
			
		
        }
			
        
    </style>
</head>
<body style="padding:0; margin:0" bgcolor="#fff">
<table border="0" cellpadding="0" cellspacing="0" style="margin: 0; padding: 0" width="100%">
    <tr>
        <td align="center" valign="top">
        
            <table width="640" border="0" cellspacing="0" cellpadding="0" class="hide">
                <tr>
                    <td height="20"></td>
                </tr>
            </table>
            
            <table width="640" cellspacing="0" cellpadding="0" bgcolor="#" class="100p">
                <tr>
                    <td background="#fff" bgcolor="#fff" width="640" valign="top" class="100p">
						<div>
							<table width="640" border="0" cellspacing="0" cellpadding="20" class="100p">
								<tr>
									<td valign="top">
										<table border="0" cellspacing="0" cellpadding="0" width="600" class="100p">
											<tr>
												<td align="center" width="100%" class="100p"><a href="<?php echo BASE_URL; ?>" target="_blank" ><img src="<?php echo BASE_URL; ?>/img/new-logos.png"  border="0" style="display:block" /></a></td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</div>
                    </td>
                </tr>
            </table>
            <table width="640" border="0" cellspacing="0" cellpadding="0" bgcolor="#ddd" class="100p" height="1">
                <tr>
                    <td width="20" bgcolor="#FFFFFF"></td>
                    <td align="center" height="1" style="line-height:0px; font-size:1px;">&nbsp;</td>
                    <td width="20" bgcolor="#ffffff"></td>
                </tr>
            </table>
            <table width="640" border="0" cellspacing="0" cellpadding="20" bgcolor="#ffffff" class="100p">
                <tr>
                    <td align="center" style="font-size:16px; color:#848484;"><font face="'Roboto', Arial, sans-serif">
						<span style="color:#444; font-size:16px;">Dear <?php echo $customerDetails['first_name']; ?>,</span><br /><br />
                        <span style="font-size:14px;">We are unable to proceed your order due to stock unavailabilty for below product.</span><br/><br/>
						<br /><br />
						<strong><?php echo $cartProductDetail['product_name'] ?></strong>
						
						<br />
						<?php /* style="font-size:14px; background:#444; padding:8px 20px; text-decoration:none;color:#fff;display:inline-block;" */?>
						</font>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
    
</body>
</html>

<?php 
	$emailMsg = ob_get_contents();
	ob_end_clean();
?>