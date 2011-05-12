<?php
/**
* Creates the sprite images from a set of images
* also generates the css with background position
*
* @author: Prakash Paudel
*/
require_once('/usr/www/manager/batch/init_symfony.inc.php');
class Sprite{
	//Please change the path of your web dir
	public $webDir = "/usr/www/manager/web";
	public $imageList = "batch/sprite/images.txt";
	public $images = array();		
	public $imageGap = 0;
	public $spriteImage = "sprite";
	public $spriteCss = "sprite";
	public $preCss ;
	public $css = "body{font-size:10px;font-family:verdana;}\n";
	public $html = '
		<link type="text/css" rel="stylesheet" href="batch/sprite/sprite.css"/>
	';
	public $lookCss = array();
	function add($image){
		if(!file_exists($image)) return;
		$this->handleImageType($image);
	}
	private function findAlreadyUsedName(){	
		foreach($this->lookCss as $tl){
			$content = file_get_contents($tl);
			preg_match_all('/\.([a-zA-Z0-9-_]+)(.*){[^}]+}/',$content,$matches);
			
			$count = 0;
			if(isset($matches[0]) &&is_array($matches[0]))
			foreach($matches[0] as $m){				
				if(preg_match_all('/url\((.*)\)/',$m,$ms)){	
					$ms[1] = str_replace("'","",$ms[1]);
					$ms[1] = str_replace('"',"",$ms[1]);					
					$key = trim($ms[1][0]);
					$this->preCss["$key"] = trim($matches[1][$count])." ".trim($matches[2][$count]); 
				}
				$count++;
			}			
		}
	}
	private function findMaxWidthAndTotalHeight($type){
		$max = 0;
		$tHeight = 0;
		foreach($this->images[$type] as $image){
			if(imagesx($image[0]) > $max) $max = imagesx($image[0]);
			$tHeight+=imagesy($image[0]);
		}
		return array($max,$tHeight);
	}
	private function handleImageType($img){
		if(preg_match('/.png$/',$img)){
			$this->images['png'][] = array(imagecreatefrompng($img),$img);
		}
		if(preg_match('/.gif$/',$img)){
			$this->images['gif'][] = array(imagecreatefromgif($img),$img);
		}
		if(preg_match('/.jpeg$/',$img) || preg_match('/.jpg$/',$img)){
			$this->images['jpeg'][] = array(imagecreatefromjpeg($img),$img);
		}		
	}
	public function setLookCss($css){
		$this->lookCss = $css;
	}
	public function setImageGap($h){
		$this->imageGap = $h; return $this;
	}
	public function setSpriteImageName($name){
		$this->spriteImage = $name; return $this;
	}
	public function setSpriteCssName($name){
		$this->spriteCss = $name; return $this;
	}
	public function createXXX($type="png"){
		$name = $this->spriteImage.".".$type;	
		if(!isset($this->images[$type]) || !count($this->images[$type])) return;
		$dim = $this->findMaxWidthAndTotalHeight($type);
		$canvas = imagecreatetruecolor($dim[0],($dim[1]+$this->imageGap*count($this->images[$type])));
		
		if($type !== "png"){
			imagefill($canvas, 0, 0, imagecolorallocate($canvas, 255, 255, 255));   
		}else{
			imagealphablending($canvas, false);
			imagefill($canvas, 0, 0, imagecolorallocatealpha($canvas, 0, 0, 0, 127));   
			imagesavealpha($canvas, true);
		}
		$dstY = 0;
		foreach($this->images[$type] as $image){
			$temp = $image[0];				
			imagecopyresampled($canvas, $temp, 0, $dstY, 0, 0, imagesx($temp),imagesy($temp), imagesx($temp),imagesy($temp));
			$this->createCss($image[1],$type,array(0,$dstY,imagesx($temp),imagesy($temp)));
			$dstY+=imagesy($temp)+$this->imageGap;
		}		
		imagecolorallocatealpha($canvas, 0, 0, 0, 127);
		call_user_func('image'.$type,$canvas,$name);		
	}
	public function create(){
		$this->findAlreadyUsedName();					
		$images = file_get_contents($this->imageList);
		$imageArray = split("\n",$images);
		foreach($imageArray as $key){
			$this->add($this->webDir.trim($key));
		}		
		$this->createXXX("png");
		$this->createXXX("gif");
		$this->createXXX("jpeg");		
		file_put_contents($this->spriteCss.".css",$this->css);
		print "Sprite image and css created successfully.....\n";
		
		file_put_contents("demo-sprite.html",$this->html);
		print "Sprite image demo html created successfully.....\n";
	}
	public function createCss($img,$type,$dim){
		$name = $img;
		if(($pos = strrpos($name,"/")) !== false){
			$name = substr($name,$pos+1,strlen($name));
		}		
		$name = substr($name,0,strrpos($name,"."));
		$name = preg_replace('/[^a-zA-Z0-9]/',"-",$name);
		$k = str_replace($this->webDir,"",$img);
		$className = isset($this->preCss[$k])?$this->preCss[$k]:"icon-".$name;
		$this->html .= '<img src="web/extjs-3/resources/images/default/s.gif" class="'.$className.'" width="'.$dim[2].'" height="'.$dim[3].'"/> '."\t".$dim[0].'px -'.$dim[1].'px '."\t | class:".$className."\t | file:".$img."<hr>";
		$this->css .= ".".$className.' {background:url("../../'.$this->spriteImage.'.'.$type.'") no-repeat '.$dim[0].' -'.$dim[1].'px !important;}
';		
	}
	public function addFromDir($dir){
		$items = scandir($dir);
		foreach($items as $item){
			$this->add($dir."/".$item);
		}
	}
}
$sp = new Sprite();

//Specify the path where the sprite should created
$sp->setSpriteImageName("web/images/sprite");
//Specify the path where css file should be created
$sp->setSpriteCssName("batch/sprite/sprite");
//$sp->addFromDir("W:\manager\plugins\appFlowerPlugin\web\images");
$sp->create();
?>