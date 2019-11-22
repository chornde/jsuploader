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
    public $mime2in = ['image/jpeg' => 'imagecreatefromjpeg', 'image/png' => 'imagecreatefrompng'];
    public $mime2out = ['image/jpeg' => 'imagejpeg', 'image/png' => 'imagepng'];
    public $maxSize = 8000000;
    public $thumbMaxSize = 200;
    public $thumbQuality = 75;

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
            $image += ['original' => $this->path.$image['id']];
            $this->storage[$slot] = $image;
            move_uploaded_file($image['tmp_name'], $image['original']);
            list($width, $height) = getimagesize($image['original']);
            $ratio = $width / $height;
            $src = ($this->mime2in[$image['type']])($image['original']);
            if ($ratio > 1) {
                $width_t = $this->thumbMaxSize;
                $height_t = $this->thumbMaxSize / $ratio;
            } else {
                $width_t = $this->thumbMaxSize * $ratio;
                $height_t = $this->thumbMaxSize;
            }
            $dest = imagecreatetruecolor($width_t, $height_t);
            imagecopyresampled($dest, $src, 0, 0, 0, 0, $width_t, $height_t, $width, $height);
            imagejpeg($dest, $image['original'].'.jpg', $this->thumbQuality);
            $this->storage[$slot]['preview'] = $image['original'].'.jpg';
        }
    }

    public function reset(int $slot = null) : bool {
        array_walk($this->storage, function($image, $key) use ($slot){
            if(isset($slot) && $key !== $slot) return;
            if(is_file($image['original'])) unlink($image['original']);
            if(is_file($image['preview'])) unlink($image['preview']);
            unset($this->storage[$key]);
        });
        return true;
    }

    public function rotate(int $slot) : bool  {
        if(!empty($this->storage[$slot])){
            $original = ($this->mime2in[$this->storage[$slot]['type']])($this->storage[$slot]['original']);
            $original_r = imagerotate($original, 90, 0);
            ($this->mime2out[$this->storage[$slot]['type']])($original_r, $this->storage[$slot]['original']);

            $thumb = imagecreatefromjpeg($this->storage[$slot]['preview']);
            $thumb_r = imagerotate($thumb, 90, 0);
            imagejpeg($thumb_r, $this->storage[$slot]['preview']);
        }
        return true;
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




if(($_POST['action'] ?? '') == 'add'){
    $slot = (int)$_POST['slot'] ?? 0;
    $files = new FilesNormalizer;
    $file = $files->first('images');
    $us->add($slot, $file);
}
if(($_GET['action'] ?? '') == 'rotate'){
    $id = $_GET['id'] ?? null;
    $us->rotate($id);
}
if(($_POST['action'] ?? '') == 'setstart'){
    //$us->attribute($key, $value);
}
if(($_GET['action'] ?? '') == 'reset'){
    $id = $_GET['id'] ?? null;
    $us->reset($id);
}

file_put_contents('test.log', print_r([$_GET, $_POST, $_FILES, $files, $file], true));
