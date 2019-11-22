<?php

/*
 * todo: use absolute paths
 * */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

//set_error_handler(function($errno, $errstr, $errfile, $errline){
//    file_put_contents('error.log', print_r([$errno, $errstr, $errfile, $errline], true));
//});

class FilesNormalizer {

    public $files = [];

    public function __construct(){
        foreach($_FILES as $name => $files){
            foreach($files as $property => $file){
                foreach($file as $idx => $value){
                    $this->files[$name][$idx][$property] = $value;
                }
            }
        }
    }

    public function first(string $name) : array {
        return reset($this->files[$name]) ?: [];
    }

}

class Uploadstorage {

    public $DS = DIRECTORY_SEPARATOR;
    public $mimes = ['image/jpeg', 'image/png'];
    public $maxSize = 8000000;

    public function __construct(array &$storage, string $path, int $slots = 12, string $lifetime = '24 hours'){
        $this->storage = &$storage;
        $this->path = $this->normalize($path);
        $this->slots = $slots;
        $this->lifetime = $lifetime;
    }

    public function normalize(string $path) : string {
        return substr($path, -1) === $this->DS ? $path : $path.$this->DS;
    }

    public function getValidSlots() : array {
        return range(0, $this->slots - 1);
    }

    public function isValidSlot(int $slot) : bool {
        return in_array($slot, $this->getValidSlots());
    }

    public function getID() : string {
        $bytes = random_bytes(32);
        return bin2hex($bytes);
    }

    public function add(int $slot, array $image) : bool {
        if(in_array($image['type'], $this->mimes) && $image['size'] <= $this->maxSize && $this->isValidSlot($slot) && empty($this->storage[$slot])){
            $image += ['id' => $this->getID()];
            $this->storage[$slot] = $image;
            move_uploaded_file($image['tmp_name'], $this->path.$image['id']);
        }
    }

    public function reset(int $slot = null) : bool {
        array_walk($this->storage, function($image, $key) use ($slot){
            if(isset($slot) && $key !== $slot) return;
            if(is_file($this->path.$image['id'])) unlink($this->path.$image['id']);
            unset($this->storage[$key]);
        });
        return true;
    }

    public function rotate(int $slot) : bool
    {

    }

    public function attribute(string $key, string $value) : bool
    {

    }

    public function list() : array {
//        ksort($list); // may be uploaded in random order
    }

    public function listReady() : array {

    }

    public function cleanup() : bool {
    }

}


isset($_SESSION['Uploadstorage']) || $_SESSION['Uploadstorage'] = [];
$path = 'tmpimgs';
$us = new Uploadstorage($_SESSION['Uploadstorage'], $path);
//$us->cleanup();
//$us->reset();




if($_POST['action'] ?? '' == 'add'){
    $slot = (int)$_POST['slot'] ?? 0;
    $files = new FilesNormalizer;
    $file = $files->first('images');
    $us->add($slot, $file);
}
if($_POST['action'] ?? '' == 'rotate'){
    //$us->rotate($slot);
}
if($_POST['action'] ?? '' == 'setstart'){
    //$us->attribute($key, $value);
}
if($_GET['action'] ?? '' == 'reset'){
    $id = $_GET['id'] ?? null;
    $us->reset($id);
}

file_put_contents('test.log', print_r([$_GET, $_POST, $_FILES, $files, $file], true));
