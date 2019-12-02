<?php
/* ----------------------------------------------------
 *  .    .... .   .     ... .... .   .
 *  .    ..     .   ..  ... ...    .
 *  .... .... .   .     .   .... .   .
 *
 *  ___   https://git-hub/lex-pex   ___
 *
 * Here are the routs of the Web-App
 * Routes assigned to Controllers actions
 * Actions are called after the slash
 * Parameters that need pass the action have
 * to be include in curly brackets {param}
 *
 * To quick start launch Migrations and Seeds
 * Init the Data Scaffolding Run on request: start
 */

return [

    '/' => 'IndexController/index',                // Main Page (Paginated Tasks list)
    'page/{n}' => 'IndexController/index',         // Paginated Tasks list (Index)
    'criteria' => 'IndexController/sort_criteria', // post | Criteria for Main Page feed line

    /* * * ________________ Migration Route _______________ * * */

    '/start_application' => 'MigrationsSeedsController/start',  // get | Admin

    /* * * ________________ Task Routes _______________ * * */

    // manage methods
    'task/store' => 'TaskController/store',        // post   | Anybody
    'task/{id}/edit' => 'TaskController/edit',     // post   | Admin / Author
    'task/update' => 'TaskController/update',      // post   | Admin / Author
    'task/destroy' => 'TaskController/destroy',    // delete | Admin

    // browse methods
    'task/index' => 'TaskController/index',          // get

    // Following route has to be LAST cause token parameter takes away any other URI segments
    'task/{id}' => 'TaskController/show',          // get

    /* __________ User Routes ( login / register ) __________  */

    'register' => 'UserController/create',              // get
    'login' => 'UserController/login',                  // get - post
    'logout' => 'UserController/logout',                // post

    'cabinet' => 'UserController/cabinet',              // get | show
    'user/create' => 'UserController/create',           // get | register
    'user/store' => 'UserController/store',             // post

    'user/{id}/edit' => 'UserController/edit',          // get
    'user/update' => 'UserController/update',           // post
    'user/destroy' => 'UserController/destroy',         // delete

    // List of All Users
    'user/index' => 'UserController/index',

    /* * * _____________ Admin Route _____________ * * */

    'admin' => 'AdminController/panel',           // get panel

];










