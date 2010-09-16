<?php use_helper('Date') ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>AppFlower - seedControl</title>
</head>

<body style="background: #FFFFFF; height: 100%;">

<table cellpadding="0" cellspacing="0" align="center" style="border: 1px solid #7c7c7c; border-width: 1px 1px 1px 1px; width:600px; background: #EFEFEF;">
  <tr>
    <td>
      <table width="98%" cellpadding="0" cellspacing="0" align="center" style=" margin-left: 10px; margin-right: 10px;">
        <tr>  
          <td style="padding-bottom: 10px; padding-top: 10px; font-family: Verdana, Arial; color: #666666; font-size: 10px;">
            
                <h2>Bug Report</h2>

				Subject: <?php echo $report_subject ?> <br/>
				Comment: <br/>
				<?php echo $report_comment ?> <br/>
				Email: <?php echo $report_email ?> <br/><br/>
			
			<p><i>Powered by AppFlower&reg; - &copy; <?php echo date("Y"); ?>.</i></p>
      
    </td>
  </tr>
  </table>
</body>
</html>
