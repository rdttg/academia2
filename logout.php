<?php


session_start(); //inicia a sessao
session_unset(); //apaga dados
session_destroy(); //encerra a sessao
header('location:/project/login.html');


?>