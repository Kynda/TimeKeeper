<body>

    <div class="container-fluid"> 

        <?= $panel ?>

    </div>


    <div class="alert alert-block alert-error hide" id="del-alert">
        <button type="button" class="close" id="close_delete">x</button>
        <h4 class="alert-heading">Are you sure you want to delete this item?</h4>
        <table class="table table-condensed task-table">
            <tr id="delete_row"></tr>
        </table>
        <p>
        <a class="btn btn-danger" id="delete_btn" href="#">Delete</a>
        </p>
    </div>


    <?php foreach( $this->postJavascript as $javascript ): ?>
        <script type="text/javascript" src="<?= $javascript ?>" ></script>
    <?php endforeach; ?>

</body>

</html>
