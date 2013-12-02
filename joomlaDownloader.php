<?php

error_reporting(E_ALL);

class JoomlaDownloader
{
	private $joomlaZipUrl;
	private $localFile;
	private $dirPath;
	private $filePath;

	public function JoomlaDownloader($joomlaZipUrl, $localFile)
	{
		$this->joomlaZipUrl = $joomlaZipUrl;
		$this->localFile = $localFile;
		$this->dirPath = getcwd();
		$this->filePath = $this->dirPath.'/'.$this->localFile;

		if ($this->downloadFile())
		{
			if($this->unzipFile())
			{
				// $source = $this->dirPath.'/joomla-cms-master';
				// $destination = $this->filePath;
				// $this->moveDirContent($source, $destination);
			}
		}
	}

	public function downloadFile()
	{
		if(file_exists($this->filePath))
		{
			echo $this->filePath.' already exists.';
			return true;
		}

		if(!$this->fileExistsOnUrl($this->joomlaZipUrl))
		{
			echo 'There is no file at '.$this->joomlaZipUrl;
			return false;
		}
		try
		{
			$data = file_get_contents($this->joomlaZipUrl);
			$handle = fopen($this->localFile, "w");
			fwrite($handle, $data);
			fclose($handle);
			echo 'Download from '.$this->joomlaZipUrl.' web to '.$this->localFile.' succeeded.';
			return true;	
		}
		catch (Exception $e)
		{
	    		echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
		echo 'Download errror';
		return false;
	}

	public function unzipFile()
	{
		$zip = new ZipArchive;
		$res = $zip->open($this->localFile);

		if ($res === TRUE)
		{
		  $zip->extractTo($this->dirPath);
		  $zip->close();

		  echo 'Joomla extracted!';
		  return true;
		}
		else
		{
		  echo 'Extraction error!';
		  return false;
		}
	}

	public function fileExistsOnUrl($url)
	{
		$file_headers = @get_headers($url);
		if($file_headers[0] == 'HTTP/1.1 404 Not Found')
		{
		    return false;
		}
		else
		{
		    return true;
		}
	}

	public function moveDirContent($src, $dst)
	{
		$dir = opendir($src); 
	    @mkdir($dst); 
	    while(false !== ( $file = readdir($dir)) ) { 
	        if (( $file != '.' ) && ( $file != '..' )) { 

	            if ( is_dir($src . '/' . $file) ) { 
	                $this->moveDirContent($src . '/' . $file,$dst . '/' . $file); 
	            } 
	            else { 
	            	die(var_dump(copy($src . '/' . $file, $dst . '/' . $file)));
	                copy($src . '/' . $file, $dst . '/' . $file); 
	            } 
	        } 
	    } 
	    closedir($dir);
	}

}

$localFile = 'joomla.zip';
$joomlaZipUrl = 'https://github.com/joomla/joomla-cms/archive/master.zip';
$downloader = new JoomlaDownloader($joomlaZipUrl, $localFile);


?>