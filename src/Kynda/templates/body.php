<body>

    <div class="container-fluid"> 

        <?= $panel ?>

    </div>

    <div class="alert alert-danger fade in" role="alert" id="del-alert">
        <button type="button" class="close" id="close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
        </button>

        <h4>Are you sure you want to delete this item?</h4>
        <p>
            <a class="btn btn-danger" id="delete_btn" href="#">Delete</a>
        </p>
    </div>


    <?php foreach( $this->postJavascript as $javascript ): ?>
        <script type="text/javascript" src="<?= $javascript ?>" ></script>
    <?php endforeach; ?>

</body>

</html>
