<?php require_once ROOT . '/view/layers/header.php' ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-sm-10">
                <?php $num = 1; foreach($list as $user): ?>
                <div class="mc-list">
                    <div class="card col-12 mt-4 p-4">
                        <h6 class="d-inline"><?php echo '# ' . $user->id . ', Login: ' . $user->login ?></h6>
                        <p class="mc-alert<?php echo $user->role=='admin'?'-yellow':'' ?>">User Role: <span class="mc-mark"><?php echo $user->role ?></span></p>
                        <p class="mc-alert">User Email:  <span class="mc-mark"><?php echo $user->email ?></span></p>
                        <p class="mc-alert">Registration:  <span class="mc-mark"><?php echo ($user->created_at) ? date('d.m.y H:i', strtotime($user->created_at)) : '--:--' ?></span>
                        Updated at: <span class="mc-mark"><?php echo ($user->updated_at) ? date('d.m.y H:i', strtotime($user->updated_at)) : '--:--' ?></span></p>
                        <?php if(auth()) { ?>
                            <?php if(admin() || user()->id === $user->id) { ?>
                        <div class="mc-alert-ok text-right">
                            <a href="<?php route('user/' . $user->id . '/edit') ?>">Edit Profile</a>
                        </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
                <?php  $num++; endforeach; ?>
            </div>
        </div>
    </div>
<?php require_once ROOT . '/view/layers/footer.php' ?>
