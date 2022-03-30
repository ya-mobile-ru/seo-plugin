<?php

\Event::listen('backend.page.beforeDisplay', function($controller, $action, $params) {
    $controller->addJs('/plugins/yamobile/seo/assets/meta_count.js');
});