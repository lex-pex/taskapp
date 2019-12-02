<?php require_once ROOT . '/view/layers/header.php' ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-4 mc-cabinet">
                    <div class="card-body">
                        <h5><?php echo $item->user_name ?></h5>
                        <p>Задача: # <?php echo $item->id ?></p>
                        <p>Email:</p>
                        <p class="mc-alert"><?php echo $item->email ?></p>

                        <p>Text:</p>
                        <div class="col-12 mc-alert" style="height:350px;overflow-y:scroll;">
                            <pre style="white-space:pre-wrap"><?php echo $item->text ?></pre>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <div class="row">
                            <div class="col-6">
                                <div class="row alert <?php echo $item->status ? 'alert-success' : 'alert-danger' ?> m-0">
                                    <?php echo $item->status ? 'Выполнена' : 'Не выполнена' ?>
                                </div>
                            </div>
                            <div class="col-6">
                                <small>
                                    Задача Добавлена: <?php echo ($item->created_at) ? date('d.m.y H:i', strtotime($item->created_at)) : '--:--' ?>
                                    <br/>
                                    Задача Обновлена: <?php echo ($item->updated_at) ? date('d.m.y H:i', strtotime($item->updated_at)) : '--:--' ?>
                                </small>
                            </div>
                        </div>

                    </div>
                </div>
                <?php if(auth()) { ?>
                    <?php if(admin() || user()->id === $item->user_id) { ?>
                    <!-- If can edit current task -->
                    <div class="alert alert-info text-right">
                        <a class="btn btn-outline-info" href="<?php route('task/' . $item->id . '/edit') ?>"> Редактировать </a>
                    </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
<?php require_once ROOT . '/view/layers/footer.php' ?>
