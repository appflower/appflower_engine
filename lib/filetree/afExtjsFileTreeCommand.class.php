<?php
/**
 * extJs file tree panel Command
 *
 */
class afExtjsFileTreeCommand
{
	public $afExtjs=null;	
	public $request=null,$result=null,$realRoot=null,$files=null;
							
	public function __construct($realRoot)
	{		
		$this->afExtjs=afExtjs::getInstance();
		$this->request=sfContext::getInstance()->getRequest();
		
		$this->realRoot=$realRoot;
		$this->files=$_FILES;
		
		$this->start();
	}
	
	public function start()
	{
		$cmd = $this->request->getParameterHolder()->has('cmd')?$this->request->getParameterHolder()->get('cmd'):null;
		
		switch ($cmd)
		{
			case "get":
				$path=$this->request->getParameterHolder()->has('path')?str_replace('root',$this->realRoot,$this->request->getParameterHolder()->get('path')):null;
				$files = sfFinder::type('any')->ignore_version_control()->maxdepth(0)->in($path);
				 				
				if(count($files)>0)
				{
					foreach ($files as $file)
					{
						$this->result[]=array('text'=>basename($file),'leaf'=>(is_file($file)?true:false));
					}
				}
				else
				$this->result = array('success' => true);
				break;
			case "newdir":
				$dir=$this->request->getParameterHolder()->has('dir')?str_replace('root',$this->realRoot,$this->request->getParameterHolder()->get('dir')):null;
				
				if(Util::makeDirectory($dir))
				{
					$this->result = array('success' => true);
				}
				else
				$this->result = array('success' => false,'error'=>'Cannot create directory '.$this->request->getParameterHolder()->get('dir'));
				break;
			case "newfile":
				$file=$this->request->getParameterHolder()->has('file')?str_replace('root',$this->realRoot,$this->request->getParameterHolder()->get('file')):null;
				
				if(Util::makeFile($file))
				{
					$this->result = array('success' => true);
				}
				else
				$this->result = array('success' => false,'error'=>'Cannot create file '.$this->request->getParameterHolder()->get('file'));
				break;
			case "delete":
				$file=$this->request->getParameterHolder()->has('file')?str_replace('root',$this->realRoot,$this->request->getParameterHolder()->get('file')):null;
				
				if(Util::removeResource($file))
				{			
					$this->result = array('success' => true);
				}
				else
				$this->result = array('success' => false,'error'=>'Cannot delete '.(is_file($file)?'file':'directory').' '.$this->request->getParameterHolder()->get('file'));
				break;
			case "rename":
				$new=$this->request->getParameterHolder()->has('newname')?str_replace('root',$this->realRoot,$this->request->getParameterHolder()->get('newname')):null;
				$old=$this->request->getParameterHolder()->has('oldname')?str_replace('root',$this->realRoot,$this->request->getParameterHolder()->get('oldname')):null;
				
				if(Util::renameResource($old,$new))
				{			
					$this->result = array('success' => true);
				}
				else
				$this->result = array('success' => false,'error'=>'Cannot rename '.(is_file($old)?'file':'directory').' '.$this->request->getParameterHolder()->get('oldname'));
				break;
			case "upload":
				$path=$this->request->getParameterHolder()->has('path')?str_replace('root',$this->realRoot,$this->request->getParameterHolder()->get('path')):null;
				
				if($this->request->hasFiles())
				{
					foreach ($this->files as $file=>$params)
					{
						if($params['size']>0)
						{
							$extension = substr($params['name'],strrpos($params['name'], '.')+1);
							
							$fileName=Util::stripText(substr($params['name'],0,(strlen($params['name'])-strlen($extension)-1))).'.'.$extension;
				  			
				  			if(!$this->request->moveFile($file, $path.'/'.$fileName,0777))
				  			{
				  				$errors[$file]='File upload error';
				  			}
						} 		
					}
				}
				
				if(!isset($errors))
				{			
					$this->result = array('success' => true);
				}
				else
				$this->result = array('success' => false,'errors'=>$errors);
				break;
			default:
				$this->result = array('success' => true);
				break;
		}
	}
	
	public function end()
	{		
		$this->result=json_encode($this->result);
		
		return $this->result;
	}
}
?>