<!doctype html>

<html lang="en">
<head>
	<meta charset="utf-8">

	<title>Slider from PDF to slider generator</title>
	<meta name="description" content="Slider from PDF to slider generator">

  	<!-- jQuery library (served from Google) -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

	<!-- bxSlider CSS file -->
	<link href="libs/bxslider/jquery.bxslider.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="css/styles.css?v=1.0">

	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<style>
		.slider_container {
			max-width: 1200px;
    		margin: 0 auto;
		}
	</style>
</head>

<body>
	<div class="slider_container">
		<ul class="bxslider">
		  	@foreach($images as $image)
            	<li><img src="img/{{$image}}"/></li>
        	@endforeach
		</ul>
	</div>
	<!-- bxSlider Javascript file -->
	<script src="libs/bxslider/jquery.bxslider.min.js"></script>
	<script src="assets/js/scripts.js"></script>
</body>
</html>