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
                        <form class="mc-form" method="post" action="<?php route('task/update') ?>">
                            <div class="form-group">
                                <label for="user_name"> Имя Пользователя: </label>
                                <input type="text" name="user_name" class="form-control" id="user_name" value="<?php echo old('user_name') ? old('user_name') : $item->user_name ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="description"> Текст Задачи # <?php echo $item->id ?>:</label>
                                <textarea type="text" class="form-control mc-text-area" rows="10" style="overflow-y:scroll"
                                          name="text" id="description"><?php echo old('text') ? old('text') : $item->text ?></textarea>
                            </div>
                            <?php if(admin()): ?>
                            <hr/>
                            <div class="alert alert-danger">
                                <p> Опции Администратора: </p>
                                <div class="form-group">
                                    <label for="email"> Почта: </label>
                                    <input type="email" name="email" class="form-control" id="email" value="<?php echo old('email') ? old('email') : $item->email ?>" required>
                                </div>
                                <div class="from-group text-center mc-alert mb-2">
                                    <label for="status"> Статус: </label>
                                    <input type="checkbox" name="status" id="status" <?php echo $item->status ? ' checked' : '' ?>/>
                                </div>
                            </div>
                            <hr/>
                            <?php endif ?>
                            <button class="btn btn-outline-danger"
                                    onclick="event.preventDefault(); if(confirm('Вы уверены, что хотите удалить?')) document.getElementById('delete-form').submit();">Delete</button>
                            <button type="submit" class="btn btn-outline-primary float-right">Submit</button>
                            <input type="hidden" name="id" value="<?php echo $item->id ?>">
                            <!-- Security field -->
                            <input type="hidden" name="csrf_token" value="<?php echo token() ?>" />
                        </form>

                        <!-- Hidden Form for Delete (submitted by button in form above) -->
                        <form  style="display:none;" id="delete-form" action="<?php route('task/destroy') ?>" method="post">
                            <!-- Security field -->
                            <input type="hidden" name="csrf_token" value="<?php echo token() ?>" />
                            <input type="hidden" name="_method" value="delete" />
                            <input type="hidden" name="id" value="<?php echo $item->id ?>">
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php require_once ROOT . '/view/layers/footer.php' ?>
