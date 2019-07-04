<?php 
namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class FileUploader
{
    private $targetDirectory;

    private $file;

    private $preview_file_name;

    public function __construct($targetDirectory)
    {
        $fileSystem = new Filesystem();

        try {
                $fileSystem->mkdir($targetDirectory);
        } catch (IOExceptionInterface $exception) {
                echo "An error occurred while creating your directory at ".$exception->getPath();
        }
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file, $targetDirectory=null)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        if(empty($targetDirectory)){
            $file->move($this->getTargetDirectory(), $fileName);
        }else{
            $file->move($targetDirectory, $fileName);
        }

        return $fileName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

    public function getFilePreview($file, $session_id, $res=213){
        $this->file = $file;
        return $this->getThumbNail($file->getRealPath(),$session_id, $res);
    }

    private function getThumbNail($image, $session_id, $res){
        $im = new \Imagick($image);
        $normalImage = $im->clone();
        $imageprops = $im->getImageGeometry();
        $width = $imageprops['width'];
        $height = $imageprops['height'];
        /*if($width > $height){
            $newHeight = 172;
            $newWidth = (172 / $height) * $width;
        }else{
            $newWidth = 172;
            $newHeight = (172 / $width) * $height;
        }*/
        
        //$im->resizeImage(213,$newHeight, $im::FILTER_LANCZOS, 0.9, true);
        //$im->cropImage (172,172,0,0);
        $im->scaleImage($res, 0);
       

        $fileSystem = new Filesystem();
        $small_file_dir = $this->targetDirectory.'/'.$session_id;
        try {
            $small_file_dir = $this->targetDirectory.'/'.$session_id;
            $fileSystem->mkdir($this->targetDirectory.'/'.$session_id);
        } catch (IOExceptionInterface $exception) {
               echo "An error occurred while creating your directory at ".$exception->getPath();
        }

        $preview_file_name = md5(uniqid()).'.'.$this->file->guessExtension();

        $small_file_path  = $small_file_dir.'/small_'.$preview_file_name;

        $im->writeImage($small_file_path);
        
        $normalImage->writeImage($small_file_dir.'/'.$preview_file_name);


        return $preview_file_name;
    }
}
