<?php require_once ROOT . '/view/layers/header.php' ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="row">
                    <!-- Input Form -->
                    <div class="col-md-6 small alert-<?php echo isset($_SESSION['errors']) ? 'danger' : 'info' ?>">
                        <div class="row">
                            <div class="col-12">
                                <?php if(isset($_SESSION['errors'])): ?>
                                    <?php foreach ($_SESSION['errors'] as $e): ?>
                                        <div class="alert alert-danger mt-4"><?php echo $e ?></div>
                                    <?php endforeach; unset($_SESSION['errors']) ?>
                                <?php endif ?>
                            </div>
                        </div>
                        <form class="mc-form" action="task/store" method="post">
                            <div class="card my-4">
                                <div class="card-header bg-white">
                                    <div class="row">
                                        <div class="col-6">
                                            <input type="text" name="user_name" placeholder="Ваше Имя..."
                                                value="<?php echo old('user_name') ? old('user_name') : (auth() ? user()->login : '') ?>" />
                                        </div>
                                        <div class="col-6">
                                            <input type="email" name="email" placeholder="Ваша Почта..."
                                                value="<?php echo old('email') ? old('email') : (auth() ? user()->email : '') ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 p-0 m-0" style="min-height:120px">
                                            <textarea name="text" id="text" rows="8" style="overflow-y:scroll"
                                                      class="form-control mc-text-area"><?php echo old('text') ? old('text') : '' ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer p-2">
                                    <div class="row">
                                        <div class="col-12 text-right">
                                            <button type="submit" class="btn btn-sm btn-outline-success">Добавить</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php foreach($items as $i): ?>
                    <div class="col-md-6 small">
                        <div class="card my-4">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-2">
                                        # <?php echo $i->id ?>
                                    </div>
                                    <div class="col-5 text-danger">
                                        <?php echo $i->user_name ?>
                                    </div>
                                    <div class="col-5 text-success text-right">
                                        <?php echo $i->email ?>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12" style="height:150px;overflow-y:scroll;">
                                        <pre style="white-space:pre-wrap"><?php echo $i->text ?></pre>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer p-1">
                                <div class="row alert <?php echo $i->status ? 'alert-success' : 'alert-danger' ?> m-0">
                                    <div class="col-4">
                                        <a href="/task/<?php echo $i->id ?>">Посмотреть &raquo;</a>
                                    </div>
                                    <div class="col-4">
                                        <?php if(auth()) { ?>
                                            <?php if(admin() || user()->id === $i->user_id) { ?>
                                            <a href="<?php route('task/' . $i->id . '/edit') ?>" class="btn btn-outline-dark"> Edit </a>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                    <div class="col-4">
                                        <?php echo $i->status ? 'Задача Выполнена' : 'Задача Не Выполнена' ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
        <?php if(isset($pager)): ?> <hr/>
        <div class="row justify-content-center"> <!-- Pagination -->
            <div class="col-lg-3 col-md-5 col-sm-7">
                <ul class="pagination" role="navigation">
                    <?php foreach($pager as $page): ?>
                        <li class="page-item <?php echo $page['class'] ?>"><a class="page-link" href="/<?php echo $page['urn'] ?>"><?php echo $page['label'] ?></a></li>
                    <?php endforeach ?>
                </ul>
            </div>
        </div> <?php endif ?>
    </div>
<?php require_once ROOT . '/view/layers/footer.php' ?>























