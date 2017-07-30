__Bugs\_BunnyCTF-2017__

__Category__ - Web | __Points__ - 5

__Description:__

Nothing here !  
http://52.53.151.123/web/web5.php  
Author : Sold1er  

__Solution:__

Fairly basic and simple challenge (as to be expected by the low points). After visiting the website, there isn't much there visually so I checked the source.
```
<!DOCTYPE html>
<html lang="EN">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Title Page</title>

		<!-- Bootstrap CSS -->
		<link href="//netdna.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	
	<body>
		<h1 class="text-center">Nothing here !!</h1>

		<!-- jQuery -->
		<script src="//code.jquery.com/jquery.js"></script>
		<!-- Bootstrap JavaScript -->
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
	</body>
	<!-- QnVnc19CdW5ueXs1MjljNDI5YWJkZTIxNzFkMGEyNTU4NDQ3MmFmODIxN30K -->
</html>
```
Note the ``<!-- QnVnc19CdW5ueXs1MjljNDI5YWJkZTIxNzFkMGEyNTU4NDQ3MmFmODIxN30K -->`` at the bottom, the string in the comment appears to be a Base64 encoded string.  
Running this through a standard Base64-Decoder will give you the flag: ``Bugs_Bunny{529c429abde2171d0a25584472af8217}``




