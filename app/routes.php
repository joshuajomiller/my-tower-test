<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//route to show view
$app->get('/', function (Request $request, Response $response) {
    return $this->view->render($response, 'callouts.html');
});

//routes to access controller
$app->get('/callouts', 'CalloutController:getCallouts');
$app->post('/callouts', 'CalloutController:addCallout');
$app->delete('/callouts/{calloutId}', 'CalloutController:deleteCallout');
$app->put('/callouts', 'CalloutController:updateCallout');

