<body>
    
    <?= $panel ?>
    
    <?php foreach( $this->postJavascript as $javascript ): ?>
        <script type="text/javascript" src="<?= $javascript ?>" ></script>
    <?php endforeach; ?>
</body>

</html>