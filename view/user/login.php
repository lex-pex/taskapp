<?php require_once ROOT . '/view/layers/header.php' ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 pt-4">
                <div class="card">
                    <div class="card-body">
                        <?php if(!empty($errors)): ?>
                            <?php foreach ($errors as $e): ?>
                                <div class="alert alert-danger"><?php echo $e ?></div>
                            <?php endforeach ?>
                        <?php endif ?>
                        <form class="mc-form" method="post" action="<?php route('login') ?>">
                            <div class="form-group">
                                <label for="login">Login:</label>
                                <input type="text" name="login" class="form-control" id="email" value="<?php old('login') ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Password:</label>
                                <input type="password" name="password" class="form-control" id="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php require_once ROOT . '/view/layers/footer.php' ?>















