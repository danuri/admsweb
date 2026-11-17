
// COPY INI KE app/Config/Routes.php kamu

$routes->group('iclock', function($routes){
    $routes->match(['get','post'], 'cdata', 'Iclock::cdata');
    $routes->match(['get','post'], 'getrequest', 'Iclock::getrequest');
});

$routes->group('admin', function($routes){
    $routes->get('/', 'Admin\Dashboard::index');
    $routes->post('addUser', 'Admin\Dashboard::addUser');
    $routes->get('deleteUser/(:any)', 'Admin\Dashboard::deleteUser/$1');
    $routes->get('reboot/(:any)', 'Admin\Dashboard::reboot/$1');
});
