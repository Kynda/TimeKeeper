<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <link href="/img/favicon.ico" rel="icon" type="image/x-icon" />
        
	<title><?= $pagetitle ?></title>

        <?php foreach( $this->styles as $style ): ?>
            <link rel="stylesheet" type="text/css" href="<?= $style ?>" media="all" />
        <?php endforeach; ?>
            
        <?php foreach( $this->headJavascript as $javascript ): ?>
            <script type="text/javascript" src="<?= $javascript ?>" ></script> 
        <?php endforeach; ?>
</head>