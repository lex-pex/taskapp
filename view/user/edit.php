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
                        <form class="mc-form" method="post" action="<?php route('user/update') ?>">
                            <div class="form-group">
                                <label for="name">Name (login):</label>
                                <input type="text" name="login" class="form-control" id="name" value="<?php echo old('login') ? old('login') : $user->login ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email">E-mail Address:</label>
                                <input type="text" name="email" class="form-control" id="email" value="<?php echo old('email') ? old('email') : $user->email ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Password:</label>
                                <input type="password" name="password" class="form-control" id="password">
                            </div>
                            <button class="btn btn-outline-danger"
                                    onclick="event.preventDefault();document.getElementById('delete-form').submit();">Delete</button>
                            <button type="submit" class="btn btn-outline-primary float-right">Submit</button>
                            <input type="hidden" name="id" value="<?php echo $user->id ?>" />
                            <input type="hidden" name="csrf_token" value="<?php echo csrf_token() ?>" />
                        </form>
                        <form id="delete-form" action="<?php route('user/destroy') ?>" method="post" style="display:none;">
                            <input type="hidden" name="csrf_token" value="<?php echo csrf_token() ?>" />
                            <input type="hidden" name="_method" value="delete" />
                            <input type="hidden" name="id" value="<?php echo $user->id ?>">
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php require_once ROOT . '/view/layers/footer.php' ?>
