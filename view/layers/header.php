<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Add-on Web-App">
    <title> Task App </title>
    <link href="/img/favicon.png" rel="icon">
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-md navbar-light bg-light shadow-sm">
    <div class="container">
        <img src="/img/favicon.png" width="30px">
        <a class="navbar-brand px-3" href="/"> the Task App </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto"></ul><!-- Left Side Of Navbar -->
            <ul class="navbar-nav ml-auto"><!-- Right Side -->
                <?php if(guest()): ?><!-- Auth Links -->
                <li class="nav-item">
                    <a class="nav-link" href="<?php route('login') ?>">Login</a>
                </li>
                <li class="nav-item" style="display: <?php echo (route_exists('register') ? 'inline' : 'none') ?>">
                    <a class="nav-link" href="<?php route('register') ?>">Register</a>
                </li>
                <?php else: ?>
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        <?php echo user() ? user()->login : 'Login' ?> <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?php route('cabinet') ?>">Cabinet</a>
                        <?php if(admin()):?><a class="dropdown-item" href="<?php route('admin') ?>">Admin Panel</a><?php endif ?>
                        <a class="dropdown-item" href="<?php route('logout') ?>"
                           onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" action="<?php route('logout') ?>" method="post" style="display:none;">
                            <input type="hidden" name="csrf_token" value="<?php token() ?>"/>
                        </form>
                    </div>
                </li>
                <?php endif ?>
            </ul>
        </div>
    </div>
</nav>
<main role="main">
    <div class="welcome-area">
        <div class="container">
            <div class="row pb-3">
                <div class="col-md-4">
                    <div class="mc-logo">
                        <img src="/img/favicon.png" width="55px">
                        <a href="/" style="text-shadow:2px 2px 2px silver"> t ASK a PP </a>
                        <hr/>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <a class="btn btn-link" href="/user/index" role="button">Участники Проекта &raquo;</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 small" style="max-width:700px;">

                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-info">
                                We bring to Your attention an absolutely
                                brand new task management application.
                                Has everything You can imagine and beyond!
                                Let us introduce our Mind Crushing Solution!
                            </div>
                        </div>
                    </div>

                    <div class="row mx-0">
                        <div class="alert alert-info text-right" style="width:100%;">
                            <form method="post" action="/criteria" class="form-inline d-inline">
                                <div class="col-md-2 col-sm-12 m-0 p-0 d-inline">
                                    Сортировка:
                                </div>
                                <?php
                                    if(isset($_SESSION['sort_criteria'])) {
                                        $lifo = $_SESSION['sort_criteria']['order'] === 'descending' ? true : false;
                                        $sort_by = $_SESSION['sort_criteria']['sort_by'];
                                    }
                                ?>
                                <div class="col-md-4 col-sm-12 m-0 p-0 d-inline">
                                    <select name="order" class="custom-select col-6">
                                        <option <?php echo isset($lifo) ? ($lifo ? 'selected' : '') : '' ?> value="ascending"> Возрастанию </option>
                                        <option <?php echo isset($lifo) ? ($lifo ? 'selected' : '') : '' ?> value="descending"> Убыванию </option>
                                    </select>
                                </div>
                                <div class="col-md-4 col-sm-12 m-0 p-0 d-inline">
                                    <select name="sort_by" class="custom-select col-6">
                                        <option <?php echo isset($sort_by) ? ($sort_by == 'created_at' ? 'selected' : '') : '' ?> value="created_at"> Последние добавленые </option>
                                        <option <?php echo isset($sort_by) ? ($sort_by == 'updated_at' ? 'selected' : '') : '' ?> value="updated_at"> Последние исправления </option>
                                        <option <?php echo isset($sort_by) ? ($sort_by == 'user_name' ? 'selected' : '') : '' ?> value="user_name"> Имени пользователя </option>
                                        <option <?php echo isset($sort_by) ? ($sort_by == 'email' ? 'selected' : '') : '' ?> value="email"> Email Почте </option>
                                        <option <?php echo isset($sort_by) ? ($sort_by == 'status' ? 'selected' : '') : '' ?> value="status"> Статусу Готовности </option>
                                    </select>
                                </div>
                                <div class="col-md-2 col-sm-12 m-0 p-0 d-inline">
                                    <input type="submit" class="btn btn-sm btn-outline-dark" href="/task/sort" role="button" value="Искать" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
