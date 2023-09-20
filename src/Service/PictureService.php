<?php

namespace App\Service;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class PictureService
{
    public function __construct(
        private ParameterBagInterface $params,
        private SluggerInterface $slugger
    ){}

    public function addFeaturedImage(UploadedFile $featuredImage, ?string $folder = '')
    {
        $originalFileName = pathinfo($featuredImage->getClientOriginalName(),PATHINFO_FILENAME);
        $safeFileName = $this->slugger->slug($originalFileName);
        $newFileName = $safeFileName.'-'.uniqid().'.'.$featuredImage->guessExtension();
        $path = $this->params->get('images_directory') . $folder;
        $featuredImage->move(
            $path, $newFileName
        );
        return $newFileName;
    }

    public function addImages(UploadedFile $picture, ?string $folder = '', ?int $width = 1024, ?int $height = 576)
    {
        //on donne un nouveau nom à l'image
        $fichier = md5(uniqid(rand(), true)) . '.webp';

        //on recupère les imfos de l'image
        $picture_infos = getimagesize($picture);

        if($picture_infos === false){
            throw new Exception('Format d\'image incorrect');
        }

        //on verifie le format de l'image
        switch($picture_infos['mime']){
            case 'image/png':
                $picture_source = imagecreatefrompng($picture);
                break;
            case 'image/jpeg':
                $picture_source = imagecreatefromjpeg($picture);
                break;
            case 'image/webp':
                $picture_source = imagecreatefromwebp($picture);
                break;
            default:
                throw new Exception('Format d\'image incorrect');
        }

        //on recadre l'image
        $imageWidth = $picture_infos[0];
        $imageHeight = $picture_infos[1];
        $src_x = ($imageWidth/2)-($width/2);
        $src_y = ($imageHeight/2)-($height/2);

        //on verifie l'orientation de l'image
        // switch($imageWidth <=> $imageHeight){
        //     case -1 : //portrait
        //         $src_x = ($imageWidth/2)-($width/2);
        //         $src_y = ($imageHeight/2)-($height/2);
        //         break;
        //     case 0 : //carré
        //         $src_x = ($imageWidth/2)-($width/2);
        //         $src_y = ($imageHeight/2)-($height/2);
        //         break;
        //     case 1 : //paysage
        //         $src_x = ($imageWidth/2)-($width/2);
        //         $src_y = ($imageHeight/2)-($height/2);
        //         break;
        // }

        //on cree une nouvelle image 'vierge'
        $resized_picture = imagecreatetruecolor($width, $height);
        imagecopyresampled($resized_picture, $picture_source, 0, 0, $src_x, $src_y, $width, $height, $width, $height);

        $path = $this->params->get('images_directory') . $folder;

        //on cree le dossier de destination s'il n'existe pas
        if (!file_exists($path . '/diapo/')) {
            mkdir($path . '/diapo/', 0755, true);
        }

        //on stock l'image recadrée
        imagewebp($resized_picture, $path . '/diapo/' . $fichier);

        // $picture->move($path . '/', $fichier);
        
        return $fichier;
    }

    public function delete(string $fichier, ?string $folder = '', ?int $width = 250, ?int $height = 250)
    {
        if($fichier !== 'default.webp'){
            $success = false;
            $path = $this->params->get('images_directory') . $folder;

            $mini = $path . '/diapo/' . $fichier;

            if(file_exists($mini)){
                unlink($mini);
                $success = true;
            }

            $original = $path . '/' . $fichier;

            if(file_exists($original)){
                unlink($original);
                $success = true;
            }
            return $success;
        }
        return false;
    }
}

