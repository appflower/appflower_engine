<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<script type="text/javascript">
	var engine = 'older';
	var ua = navigator.userAgent;
		
	if(ua.match('MSIE')) engine = 'msie';
	else if(ua.match('KHTML')) engine = 'khtml'; 
	else if(ua.match('Opera')) engine = 'opera'; 
	else if(ua.match('Gecko')) engine = 'gecko';

	document.write('<scr'+'ipt type="text/javascript" src="<?php echo $codepress_path; ?>engines/'+engine+'.js?ts=<?php echo time(); ?>"></scr'+'ipt>');
	</script>
	
	<link type="text/css" href="<?php echo $codepress_path; ?>codepress.css?ts=<?php echo time(); ?>" rel="stylesheet" />
	<link type="text/css" href="<?php echo $codepress_path; ?>languages/<?php echo $language; ?>.css?ts=<?php echo time(); ?>" rel="stylesheet" id="cp-lang-style" />	
	<script type="text/javascript" src="<?php echo $codepress_path; ?>languages/<?php echo $language; ?>.js?ts=<?php echo time(); ?>"></script>

</head>

<script type="text/javascript">
if(engine == "msie" || engine == "gecko") document.write('<body><pre> </pre></body>');
else if(engine == "opera") document.write('<body></body>');
// else if(engine == "khtml") document.write('<body> </body>');
</script>

</html>