<!DOCTYPE html>
<html lang="en">
<head>
	<?php require_once __DIR__ . '/assets/php/main.php'; ?>
	<link rel="stylesheet" href="assets/css/main.css" />
	<title>Forbidden 403</title>
	<link href="https://fonts.googleapis.com/css?family=Montserrat:700,900" rel="stylesheet">
	<style>
		#notfound {
			position: relative;
			height: 100vh;
			background: #030005;
		}

		#notfound .notfound {
			position: absolute;
			left: 50%;
			top: 50%;
			-webkit-transform: translate(-50%, -50%);
				-ms-transform: translate(-50%, -50%);
					transform: translate(-50%, -50%);
		}

		.notfound {
			max-width: 767px;
			width: 100%;
			line-height: 1.4;
			text-align: center;
		}

		.notfound .notfound-403 {
			position: relative;
			height: 180px;
			margin-bottom: 20px;
			z-index: -1;
		}

		.notfound .notfound-403 h1 {
			font-family: 'Montserrat', sans-serif;
			position: absolute;
			left: 50%;
			top: 50%;
			-webkit-transform: translate(-50% , -50%);
				-ms-transform: translate(-50% , -50%);
					transform: translate(-50% , -50%);
			font-size: 224px;
			font-weight: 900;
			margin-top: 0;
			margin-bottom: 0;
			margin-left: -12px;
			background: linear-gradient(135deg, #0117ff, #8d07cc, #d42d2d);
			-webkit-background-clip: text;
			background-clip: none;
			-webkit-text-fill-color: transparent;
			text-transform: uppercase;
			letter-spacing: -20px;
		}

		.notfound .notfound-403 h2 {
			font-family: 'Montserrat', sans-serif;
			position: absolute;
			left: 0;
			right: 0;
			top: 110px;
			font-size: 42px;
			font-weight: 700;
			color: #fff;
			text-transform: uppercase;
			text-shadow: 0px 2px 0px #8d07cc;
			letter-spacing: 13px;
			margin: 0;
		}

		.notfound a {
			font-family: 'Montserrat', sans-serif;
			display: inline-block;
			text-transform: uppercase;
			color: #ff005a;
			text-decoration: none;
			border: 2px solid transparent;
			border-image: linear-gradient(135deg, #0117ff, #8d07cc, #d42d2d) 1;
			background: transparent;
			padding: 10px 40px;
			font-size: 14px;
			font-weight: 700;
			-webkit-transition: 0.2s all;
			transition: 0.2s all;
		}

		.notfound a:hover {
			background: transparent !important;
			-webkit-text-fill-color: #8400ff !important;
			transform: none !important;
			color: #8400ff !important;
		}

		@media only screen and (max-width: 767px) {
			.notfound .notfound-403 h2 {
				font-size: 24px;
			}
		}

		@media only screen and (max-width: 480px) {
			.notfound .notfound-403 h1 {
				font-size: 182px;
			}
		}
	</style>
</head>
<body>
	<?php require_once __DIR__ . '/assets/php/header.php'; ?>
	<div id="notfound">
		<div class="notfound">
			<div class="notfound-403">
				<h1>403</h1>
				<h2>Access Forbidden</h2>
			</div>
			<a id="rediHome" href="/">Homepage</a>
		</div>
	</div>
</body>
</html>