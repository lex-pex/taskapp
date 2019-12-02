<?php require_once ROOT . '/view/layers/header.php' ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-sm-10 pt-4">
                <?php foreach($list as $item): ?>
                    <div class="alert alert-<?php echo $item->status ? 'info' : 'danger' ?>">
                        <div class="row">
                            <div class="col-6">
                                # <?php echo $item->id . ': <mark>' . ($item->status ? 'Решена' : 'В работе') . '</mark>' ?>
                            </div>
                            <div class="col-6 text-right">
                                <h6><?php echo $item->user_name ?></h6>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-12">
                                <p><?php echo $item->text ?></p>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-6 text-left">
                                <p><a href="/taks/<?php echo $item->id ?>/edit"> Редактировать </a></p>
                            </div>
                            <div class="col-6 text-right">
                                <p>
                                    <a href="mailto:<?php echo $item->email ?>?subject=Вопрос по задаче Task_App"><?php echo $item->email ?></a>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
<?php require_once ROOT . '/view/layers/footer.php' ?>
