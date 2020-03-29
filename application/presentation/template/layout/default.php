<!doctype html>
<html>
<head>
	<title>Contresort le framework sans magie</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">



	<link rel="icon" href="resource/favicon.png"/>
	<link rel="stylesheet" href="resource/css/global.css"/>
<style>
			#forkongithub a{
				background:#000;
				text-decoration:none;
				font-family:arial,sans-serif;
				text-align:center;
				font-weight:bold;
				padding:5px 40px;
				font-size:2rem;
				line-height:4rem;
				position:relative;
				transition:0.5s;
			}
			#forkongithub a:hover{
				background:#26437C;color:#5FC6F5;
			}
			#forkongithub a::before,#forkongithub a::after{
				content:"";
				width:100%;
				display:block;
				position:absolute;
				top:1px;left:0;
				height:1px;
				background:#fff;
			}
			#forkongithub a::after{
				bottom:1px;
				top:auto;
			}
			@media screen and (min-width:800px) {
				#forkongithub{
					position:fixed;display:block;
					top:0;
					right:0;
					width:280px;
					overflow:hidden;
					height:200px;
					z-index:9999;
				}
				#forkongithub a{
					width:280px;position:absolute;
					top:55px;
					right:-60px;
					transform:rotate(45deg);-webkit-transform:rotate(45deg);-ms-transform:rotate(45deg);-moz-transform:rotate(45deg);-o-transform:rotate(45deg);
					/*box-shadow:2px 2px 5px rgba(0,0,0,0.3);*/
				}
			}
</style>

</head>
<body>
<span id="forkongithub"><a href="https://github.com/deljdlx/archived-contresort">Fork me on GitHub</a></span>

<?=$navigationBar;?>



<div style="margin-top: 50px;">
<?=$content;?>
</div>

<?=$footer;?>


</body>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

</html>