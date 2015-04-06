<?php

require(__DIR__.'/bootstrap.php');




?><html>

<head>

<title>Phe test console</title>



<script src="javascript/jquery-2.1.1.min.js"></script>

<link rel="stylesheet" href="resource/style.css"></link>


<style>

body {
	font-family: arial;
}

.test {
	border: solid 2px #999;
margin-bottom: 10px;
}

.className {
	font-weight: bold;
	background-color:#AAA;
	padding:3px;
}

.method {
	margin:3px;
	border: dashed 1px #999;
}


.test .methodName {
	padding:3px;
	background-color: #CCC;
}

.test table {
	width: 100%;
}

.test table td {
	border: solid 1px #CCC;
}

.test table td:first-child {
	width:200px;
}


.success {
	background-color:#DFD;
}

.fail {
	background-color:#FDD;
}






</style>

</head>


<body>
<?php


function file_get_php_classes($filepath) {
  $php_code = file_get_contents($filepath);
  $classes = get_php_classes($php_code);
  return $classes;
}

function get_php_classes($php_code) {
  $classes = array();
  $tokens = token_get_all($php_code);
  $count = count($tokens);
  for ($i = 2; $i < $count; $i++) {
    if (   $tokens[$i - 2][0] == T_CLASS
        && $tokens[$i - 1][0] == T_WHITESPACE
        && $tokens[$i][0] == T_STRING) {

        $class_name = $tokens[$i][1];
        $classes[] = $class_name;
    }
  }
  return $classes;
}








$dir=opendir('test');
while($f=readdir($dir)) {
	if(strpos($f, '.test.php')) {
		$testFiles[]='test/'.$f;
	}
}
closedir($dir);



foreach($testFiles as $file) {
	include($file);

	$className=str_replace('.test.php', '', basename($file)).'test';
	$test=new $className();
	$methods=$test->getTests();
	
	echo '<div class="test '.$className.'">';
		echo '<div class="className">'.$className.'</div>';
		foreach($methods as $method) {
			echo '<div class="method" data-method="'.$method->name.'" data-class="'.$className.'">';
				echo '<div class="methodName">'.$method->name.'</div>';
				echo '<div class="result"></div>';
			echo '</div>';
		}
	echo '</div>';
	
	//$classes=file_get_php_classes($f);
	

	echo '<div>';
		
	
	echo '</div>';
	
}

?>
</body>

<script>

jQuery(function() {


	jQuery('.method').each(function() {
		
		var methodName=jQuery(this).data('method');
		var className=jQuery(this).data('class');
		var node=this;

		jQuery.ajax({
			url: 'run.php?method',
			data: {
				method: methodName,
				className: className
			},
			success: function(result) {
				console.debug(result);

				if(result.result) {
					var cssClass="success";
				}
				else {
					var cssClass="fail";
				}

				jQuery(node).find('.result').html(
					'<table class="'+cssClass+'">'+
						'<tr><td>Result : </td><td>'+(result.result)+'</td></tr>'+
						'<tr><td>Duration : </td><td>'+(result.duration)+'</td></tr>'+
						'<tr><td>Memory : </td><td>'+(result.memory)+'</td></tr>'+
						'<tr><td>Output : </td><td>'+(result.output)+'</td></tr>'+
						'</tr>'+
					'</table>'
				);
			}
		});


	});
});



</script>


</html>














</html>