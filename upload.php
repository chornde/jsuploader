<?php
file_put_contents('test.log', print_r([$_GET,$_POST,$_FILES], true));