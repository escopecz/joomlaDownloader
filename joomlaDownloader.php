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
				$this->clean();
				$this->redirect();
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
		$res = $zip->open($this->filePath);
		
		if ($res === TRUE)
		{
			$extracted = $zip->extractTo($this->dirPath);
			$zip->close();

			if($extracted)
			{
				echo 'Joomla extracted!';
				return true;
			}
			else
			{
				echo 'Zip extraction failed.'.$this->getZipError($res);
				return false;
			}
			
		}
		else
		{
			echo 'Extraction error: '.$this->getZipError($res);
			return false;
		}
	}

	public function clean()
	{
		unlink(__FILE__);
		unlink($this->localFile);
	}

	public function redirect()
	{
		echo '<script>window.location = "http://'.$_SERVER['HTTP_HOST'].'/installation/index.php";</script>';
		die('redirect');
	}

	public function getZipError($errorNumber)
	{
		$errors = array();
		$errors[0] = 'No error';
		$errors[1] = 'Multi-disk zip archives not supported';
		$errors[2] = 'Renaming temporary file failed';
		$errors[3] = 'Closing zip archive failed';
		$errors[4] = 'S Seek error';
		$errors[5] = 'Read error';
		$errors[6] = 'Write error';
		$errors[7] = 'CRC error';
		$errors[8] = 'Containing zip archive was closed';
		$errors[9] = 'No such file';
		$errors[10] = 'File already exists';
		$errors[11] = 'Can\'t open file';
		$errors[12] = 'Failure to create temporary file';
		$errors[13] = 'Zlib error';
		$errors[14] = 'Malloc failure';
		$errors[15] = 'Entry has been changed';
		$errors[16] = 'Compression method not supported';
		$errors[17] = 'Premature EOF';
		$errors[18] = 'Invalid argument';
		$errors[19] = 'Not a zip archive';
		$errors[20] = 'Internal error';
		$errors[21] = 'Zip archive inconsistent';
		$errors[22] = 'Can\'t remove file';
		$errors[23] = 'Entry has been deleted';

		if(isset($errors[$errorNumber]))
		{
			return $errors[$errorNumber];
		}
		else
		{
			return 'Unknown error';
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
// $joomlaZipUrl = 'http://git.easysoftware.cz/webs/j2.5/easyjoomla/snapshot/easyjoomla-5dbddef5777209f4c5a57adac5d7114d97bc1144.zip';
$joomlaZipUrl = 'http://joomlacode.org/gf/download/frsrelease/19007/134333/Joomla_3.2.1-Stable-Full_Package.zip';
$downloader = new JoomlaDownloader($joomlaZipUrl, $localFile);


?>