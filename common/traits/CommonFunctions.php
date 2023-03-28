<?php

namespace common\traits;
use Yii;

trait CommonFunctions
{
    public static function createThumbImage($file_type,$new_width,$base64_string){

        // Step-1: Convert base64 to Image
        // split the string on commas
        // $data[ 0 ] == "data:image/png;base64"
        // $data[ 1 ] == <actual base64 string>
        $data = explode( ',', $base64_string );

        $bin = base64_decode($data[1]);
        $size = getImageSizeFromString($bin);

        // Check the MIME type to be sure that the binary data is an image
        if (empty($size['mime']) || strpos($size['mime'], 'image/') !== 0) {
          return 'Base64 value is not a valid image';
        }

        $filename = md5(rand(1,100));
        // Specify the location where you want to save the image
        $img_file = "images/temp_files/$filename.{$file_type}";

        file_put_contents($img_file, $bin);

        // Step-2: Resize image
        // Get width and heights
        list($width, $height) = getimagesize($img_file);
        if ($width < $new_width) {
            return 'Image width is larger than original image width.';
        }

        $new_height = $height / $width * $new_width;

        error_reporting(E_ERROR | E_WARNING | E_PARSE); // to remove error reporting for imagecreatetruecolor function
        $thumb = imagecreatetruecolor($new_width, $new_height);

        if($file_type == 'jpg' || $file_type == 'jpeg'){
            $image = imagecreatefromjpeg($img_file);
        } elseif($file_type == 'png'){
            $image = imagecreatefrompng($img_file);
        }

        // Resize image
        imagecopyresized($thumb, $image, 0, 0, 0, 0, $new_width, $new_height, $width, 
$height);
        imagejpeg($thumb, $img_file, 100);

        // Step-3: Convert image to base64 
        $img = file_get_contents($img_file);
        $new_string = $data[ 0 ].",".base64_encode($img);
        unlink($img_file);

        return $new_string;
    }

    public static function getServiceName($name_application, $type_sub = 'programming'){

        switch ($name_application) {
            case 'Invention':
                $service_name = 'Изобретения';
                break;
            case 'Programming':
                if ($type_sub == 'programming') $service_name = 'Программы для ЭВМ';
                else $service_name = "База данных";
                break;
            case 'Trademark':
                $service_name = 'Товарные знаки';
                break;
            case 'UsefulModel':
                $service_name = 'Полезные модели';
                break;
            case 'Industry':
                $service_name = 'Промышленные образцы';
                break;
            case 'Selective':
                $service_name = 'Селекционные достижение';
                break;
            case 'Topology':
                $service_name = 'Топологии интегральных микросхем';
                break;
            case 'Nmpt':
                $service_name = 'Наименования мест происхождение товаров';
                break;
            case 'Geography':
                $service_name = 'Географические указание';
                break;
            case 'Attestationpatent':
                $service_name = 'Проведение аттестации патентных проверенных';
                break;
            case 'Attestationcandidatepatent':
                $service_name = 'Проведение аттестации кандидата в патентных проверенных';
                break;

            default:
                $service_name = null;
        }

        return $service_name;
    }
}