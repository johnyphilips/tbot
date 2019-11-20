<?php
/*
 * File:        imgresize.php
 * Modified by: Paul A.
 * E-mail:      root4root@gmail.com
 * Objectives:  +Add sharpen feature and multiple resize (by storing original).
 *              +You can use resize functions many times and don't lose the quality.
 *              +Rotate image based on EXIF Orientation.
 *              +Fit rectangle method (to height or width which bigger)
 *              +Watermarks
 *              +joinHorizontal
 *              +joinVertical
 * Date:        01/11/15

 * Author: Simon Jarvis
 * Copyright: 2006 Simon Jarvis
 * Date: 08/11/06
 * Link: http://www.white-hat-web-design.co.uk/articles/php-image-resizing.php
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details:
 * http://www.gnu.org/licenses/gpl.html
 *
*/
 
class imgresize_class
{
    public $nativeimg;
    public $image;
    public $image_type;
    public $water;
    public $filename;
    
     
    public function load($filename) 
    {
        $image_info = getimagesize($filename);
        $this->image_type = $image_info[2];
        
        $this->filename = $filename;
        
        if( $this->image_type == IMAGETYPE_JPEG ) 
        {
            $this->nativeimg = imagecreatefromjpeg($filename);
            if($this->getHeight() < $this->getWidth())
                $this->exifrotate($filename);
            
            return true;
        } 
        elseif( $this->image_type == IMAGETYPE_GIF ) 
        {
            $this->nativeimg = imagecreatefromgif($filename);
            return true;
        } 
        elseif( $this->image_type == IMAGETYPE_PNG ) 
        {
            $this->nativeimg = imagecreatefrompng($filename);
           
            
            return true;
        }
        else
            return false;
      
    }
   
    public function save($filename, $image_type=IMAGETYPE_JPEG, $compression=95, $owner=null, $permissions=null) 
    {
        if(!is_resource($this->image))
        {
            $image = $this->nativeimg;
        }
        else 
        {
            $image = $this->image;
        }
        
        if( $image_type == IMAGETYPE_JPEG ) 
        {
            
            if(!empty($this->image_type) && $this->image_type == IMAGETYPE_PNG)
            {
                $image = $this->_prepareBackground($image);
            }
            
            imagejpeg($image,$filename,$compression);
        } 
        elseif( $image_type == IMAGETYPE_GIF ) 
        {
            imagegif($image,$filename);
        } 
        elseif( $image_type == IMAGETYPE_PNG ) 
        {
            imagepng($image,$filename);
        }
 
        if( $owner != null) 
        {
            chown($filename, $owner);
        }
        
        if( $permissions != null) 
        {
            chmod($filename, $permissions);
        }
        
    }
    
    public function output($image_type=IMAGETYPE_JPEG) 
    {
        if(!is_resource($this->image))
        {
            $image = $this->nativeimg;
        }
        else
        {
            $image = $this->image;
        }
        
        if( $image_type == IMAGETYPE_JPEG ) 
        {
            if(!empty($this->image_type) && $this->image_type == IMAGETYPE_PNG)
            {
                $image = $this->_prepareBackground($image);
            }
            
            imagejpeg($image);
        } 
        elseif( $image_type == IMAGETYPE_GIF ) 
        {
            imagegif($image);
        } 
        elseif( $image_type == IMAGETYPE_PNG ) 
        {
            imagepng($image);
        }
    }
    
    private function _prepareBackground($resource)
    {
        $width = imagesx($resource);
        $height = imagesy($resource);
        
        $temp = imagecreatetruecolor($width, $height);
        $bg = imagecolorallocate($temp, 255, 255, 255);
        imagefill($temp, 0, 0, $bg);
        
        imagecopy($temp, $resource, 0, 0, 0, 0, $width, $height);
        
        return $temp;
    }
    
    public function getWidth() 
    {
        return imagesx($this->nativeimg);
    }
    
    public function getHeight() 
    {
        return imagesy($this->nativeimg);
    }
   
    public function getResource()
    {
        if(is_resource($this->image))
        {
            return $this->image;
        }
        else
        {
            return $this->nativeimg;
        }
    }
    
    public function resizeToHeight($height) 
    {
        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize($width,$height);
        
        return imagesx($this->image);
    }
 
    public function resizeToWidth($width) 
    {
        $ratio = $width / $this->getWidth();
        $height = $this->getheight() * $ratio;
        $this->resize($width,$height);
        
        return imagesy($this->image);
    }
   
    public function resampleToHeight($height) 
    {
        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resample($width,$height);
        
        return imagesx($this->image);
    }
 
    public function resampleToWidth($width) 
    {
        $ratio = $width / $this->getWidth();
        $height = $this->getheight() * $ratio;
        $this->resample($width,$height);
        
        return imagesy($this->image);
    }
    

    /*This method resize image to fit rectangle (resize to height or width)
     * 
     * @param   int       $width      width of rectangle
     * @param   int       $height     height of rectangle
     * @param   boolean   $ifsmaller  resize if source image smaller than rectangle
     * 
     * @return  boolean   status      true - resized/false - not resized.
     */
    public function resampleToRectangle($width, $height, $ifsmaller = false)
    {
        $sourcewidth = $this->getWidth();
        $sourceheight= $this->getHeight();
        $sourceratio = $sourcewidth/$sourceheight;
        $destinationratio = $width/$height;
        
        if($sourcewidth>$width or $sourceheight>$height or $ifsmaller === true)
        {
            if($sourceratio > $destinationratio)
            {
                $this->resampleToWidth($width);
            }
            else
            {
                $this->resampleToHeight($height);
            }
            return true;
        }

        $this->_check();
        return true;
    }
    
    public function scale($scale) 
    {
        $width = $this->getWidth() * $scale/100;
        $height = $this->getheight() * $scale/100;
        $this->resize($width,$height);
    }
 
    public function resize($width,$height) 
    {
        $new_image = $this->_createCanvas($width, $height);
        imagecopyresized($new_image, $this->nativeimg, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        $this->image = $new_image;
    }      
   
    public function resample($width,$height) 
    {
        $new_image = $this->_createCanvas($width, $height);
        
        imagecopyresampled($new_image, $this->nativeimg, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        $this->image = $new_image;
    }
    
    private function _createCanvas($width, $height)
    {
        $canvas = imagecreatetruecolor($width, $height);
        
        if( $this->image_type == IMAGETYPE_PNG ) 
        {
            $bg = imagecolorallocate($canvas, 0, 0, 0);
            imagecolortransparent($canvas, $bg);
            imagealphablending($canvas, false);
            imagesavealpha($canvas, true);
        } 
        
        if( $this->image_type == IMAGETYPE_GIF ) 
        {
            $bg = imagecolorallocate($canvas, 255, 255, 255);
            imagefill($canvas, 0, 0, $bg);
            imagecolortransparent($canvas, $bg);
        }
        
        return $canvas;
    }
 
    public function sharpen($strenght = 18)
    {
       if(!is_resource($this->image))
       {
           return false;
       }
        
        /*Less value in the middle means more sharpen*/
        $sharpen_matrix=array(  array(0.0, -1.0, 0.0),
                                array(-1.0, (float)$strenght, -1.0),
                                array(0.0, -1.0, 0.0));
       
        $divisor=array_sum(array_map('array_sum', $sharpen_matrix));
        imageconvolution($this->image, $sharpen_matrix, $divisor, 0);
   }
   
    public function exifrotate($path)
    {
        if(function_exists('exif_read_data'))
        {
            $exif = exif_read_data($path);
            if(!empty($exif['Orientation'])) 
            {
                switch($exif['Orientation']) 
                {
                    case 8:
                        $this->nativeimg = imagerotate($this->nativeimg, 90, 0);
                        break;
                    case 3:
                        $this->nativeimg = imagerotate($this->nativeimg, 180, 0);
                        break;
                    case 6:
                        $this->nativeimg = imagerotate($this->nativeimg, -90, 0);
                        break;
                }
            }
        }
    }
    
    /*$source = true - set watermark on source image*/
    public function watermark($file, $source=false)
    {
        if($source)
        {
            $this->image=&$this->nativeimg;
        }
        
        if(!is_resource($this->image) && $source === false)
        {
            $this->_check();
        }
                    
        
        $this->water=imagecreatefrompng($file);
        
        $water_width=imagesx($this->water);
        $water_height=imagesy($this->water);
        
            
        $img_width=imagesx($this->image);
        $img_height=imagesy($this->image);
        
        $dest_x = ($img_width-$water_width)/2;
        $dest_y = ($img_height-$water_height)/2;
        
        $cut = imagecreatetruecolor($water_width, $water_height);
        
        imagecopy($cut, $this->image, 0, 0, $dest_x, $dest_y, $water_width, $water_height);
        imagecopy($cut, $this->water, 0, 0, 0, 0, $water_width, $water_height);
        
        imagecopymerge($this->image, $cut, $dest_x, $dest_y, 0, 0, $water_width, $water_height, 100);

        return true;
    }
    
    public function joinHorizontal($objarr)
    {
        //Find minheight
        $minheight = $objarr[0]->getHeight();
        
        //Find smallest picture height
        foreach($objarr AS $key=>$obj)
        {
            if($key!=0 && $minheight>$obj->getHeight())
            {
                $minheight=$obj->getHeight();
            }
        }
        
        $widtharray = array();
        $overallwidth = 0;
        //All pictures to one tall
        foreach($objarr AS $key=>$obj)
        {
            if($obj->getHeight() > $minheight)
            {
                $widtharray[$key] = $obj->resampleToHeight($minheight);
                $obj->sharpen();
            }
            else
            {
                $widtharray[$key] = $obj->getWidth();
            }
            $overallwidth += $widtharray[$key];
        }

        $temp = $this->_createCanvas($overallwidth, $minheight);
        
        $dest_x = 0;
        
        foreach($objarr AS $key=>$obj)
        {
            imagecopy($temp, $obj->getResource(), $dest_x, 0, 0, 0, $widtharray[$key], $minheight);
            $dest_x += $widtharray[$key];
        }
        
        if(is_resource($this->image))
        {
            imagedestroy($this->image);
        }
        
        $this->nativeimg = $temp;
    }
    
    public function joinVertical($objarr)
    {
         //Find minwidth
        $minwidth = $objarr[0]->getWidth();
        
        //Find smallest picture width
        foreach($objarr AS $key=>$obj)
        {
            if($key!=0 && $minwidth>$obj->getWidth())
            {
                $minwidth=$obj->getWidth();
            }
        }
        
        $heightharray = array();
        $overallheight = 0;
        //All pictures to one wide
        foreach($objarr AS $key=>$obj)
        {
            if($obj->getWidth() > $minwidth)
            {
                $heightharray[$key] = $obj->resampleToWidth($minwidth);
                $obj->sharpen();
            }
            else
            {
                $heightharray[$key] = $obj->getHeight();
            }
            $overallheight += $heightharray[$key];
        }
        
        $temp = $this->_createCanvas($minwidth, $overallheight);
        
        $dest_y = 0;
        
        foreach($objarr AS $key=>$obj)
        {
            imagecopy($temp, $obj->getResource(), 0, $dest_y, 0, 0, $minwidth, $heightharray[$key]);
            $dest_y += $heightharray[$key];
        }
        
        if(is_resource($this->image))
        {
            imagedestroy($this->image);
        }
        
        $this->nativeimg = $temp;
    }
    
    
    public function map()
    {
        $this->image = &$this->nativeimg;
    }

    private function _check()
    {
        $width = $this->getWidth();
        $height = $this->getHeight();
        $this->image = imagecreatetruecolor($width, $height);
            
        imagecopy($this->image, $this->nativeimg, 0, 0, 0, 0, $width, $height);
    }
    
    public function __destruct()
    {
        if(is_resource($this->nativeimg))
        {
            imagedestroy($this->nativeimg);
        }
        if(is_resource($this->image))
        {
            imagedestroy($this->image);
        }
        if(is_resource($this->water))
        {
            imagedestroy($this->water);
        }
    }
}
