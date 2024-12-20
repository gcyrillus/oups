<?php
	header('HTTP/1.1 429 OUPS!');
?><!DOCTYPE html>
<html lang="fr">
	<head><meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
		<style>
			html {
				display: grid;
				min-height: 100vh;
				place-content: center;
				background:#afcfff;
				animation:bgchange infinite 15s;
			}
			
			body {
				display: grid;
				place-content: center;
				width:max-content;
			}
			h1,
			img {
				aspect-ratio:1;
				grid-row: 1;
				grid-column: 1;
				margin:auto;
				color:gray;
			}
			img {
				mix-blend-mode: color-burn;
			}
			@keyframes bgchange{
				50% {background:#345}
			}
			
		</style>
	</head>
	<body>
		<img src="/plugins/oups/img/tearedpaper.png">
		<h1>Oups !</h1>
	</body>
</html>