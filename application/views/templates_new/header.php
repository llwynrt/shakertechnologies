<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Marie-Lyse Briffaud">
    <title>Shaker Technologies</title>



    <!-- Custom CSS -->
    <link href="/assets/css/style.css" rel="stylesheet">
    <?php 
    	if(isset($css)){
    		echo '<link href="/assets/css/'.$css.'" rel="stylesheet">';
    	}
    ?>
    <link rel="icon" type="image/png" href="/favicon.png" />

    <link href='http://fonts.googleapis.com/css?family=Comfortaa' rel='stylesheet' type='text/css'>
</head>
<body class="HolyGrail">
	<header>
		<a href="/">
			<img src="/assets/img/<?php echo $logo; ?>" height="100" width="363" alt="logo shaker technologies : forges logicielles sur mesure"/>
		</a>
	</header>
	<div class="HolyGrail-body">
		<div class="HolyGrail-content">
