<?php require_once ROOT . '/view/layers/header.php' ?>
    <div class="container">
        <div class="row justify-content-center pt-4">
            <div class="col-md-6">
                <div class="card mc-cabinet">
                    <div class="card-header">
                        <h6 class="text-muted text-right">Admin Panel</h6>
                    </div>
                    <div class="card-body">
                        <p class="mc-alert">
                            <a href="<?php route('user/index') ?>"> Список пользователей </a>
                            <a class="float-right" href="<?php route('task/index') ?>"> Список задач </a>
                        </p>
                        <p>
                            Инициация всех Таблиц и засев Сидов <br/> с Админом: (admin / 123, users / 321):
                        </p>
                        <p class="mc-alert">
                            <a class="btn btn-outline-success" href="/start" role="button">Запуск миграций приложения &raquo;</a>
                        </p>

                        <p class="mc-alert">
                            <a class="btn btn-outline-danger disabled" href="/erase" role="button">Стереть базу данных приложения &raquo;</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php require_once ROOT . '/view/layers/footer.php' ?>




