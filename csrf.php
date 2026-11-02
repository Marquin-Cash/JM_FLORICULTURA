<?php require_once __DIR__.'/../../app/bootstrap.php'; require_method('GET'); json_response(['csrf'=>csrf_token()]);
