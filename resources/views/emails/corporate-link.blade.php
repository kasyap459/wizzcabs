<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
      <title>{{ config('app.name', 'Unicotaxi') }}</title>
      
   </head>
   <body marginwidth="0" marginheight="0" style="margin-top: 0; margin-bottom: 0; padding-top: 0; padding-bottom: 0; width: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;" offset="0" topmargin="0" leftmargin="0">
      <table  width="70%" border="0" cellpadding="0" cellspacing="0" align="center" bgcolor="#ecf0f1" class="">
         <tbody>
            <tr>
               <td background="{{ asset('/asset/img/email/header-bg.jpg') }}" style="background-size:cover; height:auto; background-position:center;" bgcolor="#222328" height="300">
                  <table width="600" class="table600" border="0" align="center" cellpadding="0" cellspacing="0">
                     <tbody>
                        <tr>
                           <td height="40"></td>
                        </tr>
                        <!--logo-->             
                        <tr>
                           <td align="center" style="line-height: 0px;">                     
                              <img  style="display:block; font-size:0px; line-height:0px; border:0px;" src="{{ Setting::get('site_logo', asset('logo-black.png')) }}" width="213" height="70" alt="logo">                  </td>
                        </tr>
                        <!--end logo-->               
                        <tr>
                           <td height="30"></td>
                        </tr>
                        <!--slogan-->              
                        <tr>
                           <td align="center" valign="bottom" style="font-family: 'century Gothic', 'open sans', sans-serif; font-size:18px; color:#ffffff;line-height: 28px;">WELCOME</td>
                        </tr>
                        <!--end slogan-->             
                        <tr>
                           <td height="60"></td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>    
            <tr>
               <td align="center">
                  <table align="center" class="table600" width="600" border="0" cellspacing="0" cellpadding="0">
                     <tbody>
                        <tr>
                           <td align="center" valign="top" style="line-height: 0px;">                    <img style="display:block; font-size:0px; line-height:0px; border:0px;" class="img1" src="{{ asset('/asset/img/email/bar-shadow-bottom.png') }}" width="598" height="5" alt="shadow">                 </td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
         </tbody>
      </table>
      <table align="center" width="70%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ecf0f1" class="">
         <tbody>
            <tr>
               <td height="25"></td>
            </tr>
            <tr>
               <td align="center">
                  <table bgcolor="#ffffff" class="table600" width="600" border="0" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #d2d2d2;">
                     <tbody>
                        <tr>
                           <td align="center">
                              <table class="table-inner" width="540" border="0" align="center" cellpadding="0" cellspacing="0">
                                 <tbody>
                                    <tr>
                                       <td height="30" align="right" valign="top">
                                          <table bgcolor="#f95759" width="20" border="0" align="right" cellpadding="0" cellspacing="0">
                                             <tbody>
                                                <tr>
                                                   <td style="line-height: 0px;">      
                                                   </td>
                                                </tr>
                                             </tbody>
                                          </table>
                                       </td>
                                    </tr>
                                    <!--Title-->                        
                                    <tr>
                                       <td align="left" valign="top" style="font-family: 'Open Sans', Arial, sans-serif; font-size:20px; color:#444444; font-weight:bold;">{{ config('app.name', 'Unicotaxi') }}</td>
                                    </tr>
                                    <!--end title-->                       
                                    <tr>
                                       <td style="border-bottom:1px solid #bdc3c7;" height="10"></td>
                                    </tr>
                                    <tr>
                                       <td height="10"></td>
                                    </tr>
                                    <!--Content-->                      
                                    <tr>
                                       <td align="left" style="font-family: 'Open Sans', Arial, sans-serif; font-size:13px; color:#3e4242; line-height:28px;"> 
                                          <b>Hi {{ $user['emp_name'] }},</b><br> 
                                          Your corporate account is activated. Use our app and get corporate deliveries.<br><br>
                                          
                                          <b>Registered Number: </b>{{ $user['emp_phone'] }}<br>
                                          <br><br>
                                          Thanks,<br>
                                          {{ config('app.name', 'Unicotaxi') }} 
                                          </td>
                                    </tr>
                                    <!--End Content-->                        
                                    <tr>
                                       <td height="25"></td>
                                    </tr>
                                 </tbody>
                              </table>
                           </td>
                        </tr>
                        <tr>
                           <td height="5" bgcolor="#f4f4f4" style="line-height: 0px;font-size: 0px;"></td>
                        </tr>
                     </tbody>
                  </table>
                  <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="table600">
                     <tbody>
                        <tr>
                           <td align="center" valign="top" style="line-height: 0px;">                    <img style="display:block; font-size:0px; line-height:0px; border:0px; width:100%; height:auto;" class="img1" src="{{ asset('/asset/img/email/panel-shadow.png') }}" width="600" height="3" alt="shadow">                  </td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
         </tbody>
      </table>
      <div class="parentOfBg"></div>
      <div class="parentOfBg"></div>
      <table width="70%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#ecf0f1" class="">
         <tbody>
            <tr>
               <td height="25"></td>
            </tr>
            
            <tr>
               <td bgcolor="#444444" height="50" style="border-top:5px solid #57a7f9;">
                  <table class="table600" width="600" border="0" align="center" cellpadding="0" cellspacing="0">
                     <tbody>
                        <tr>
                           <td height="10"></td>
                        </tr>
                        <tr>
               <td height="50" align="center" bgcolor="#444444">
                  <table width="600" class="table600" align="center" border="0" cellspacing="0" cellpadding="0">
                     <tbody>
                        <tr>
                           <td align="center" class="">
                              <table class="table-inner" width="60%" border="0" cellspacing="0" cellpadding="0">
                                 <tbody>
                                    <tr>
                                       <td valign="middle" height="30" style="color: white;">Copyright © {{ config('app.name', 'Laravel') }} , All rights reserved</td>
                           
                                    </tr>
                                 </tbody>
                              </table>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
                        <tr>
                           <td height="27"></td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
         </tbody>
      </table>
   </body>
</html>