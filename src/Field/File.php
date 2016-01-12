<?php

namespace TotalFlex\Field;

use TotalFlex\Field\Field;

class File extends Field {

	/**
	 * @var string, is the folder name where to save the files, if empty, means that user want to save binary file on database
	 */
	protected $_targetFolder = null ;

	/**
	 * @var string path relative to web
	 */
	protected $_webFolder = "";

	/**
	 * @var array, allowed file types, if empty, it will allow all file types
	 */
	protected $_allowedTypes = array ();

	/**
	 * @var string max file size accepted
	 */
	protected $_maxFileSize = null ;

	/**
	 * @var string max file uploads, in case of multiple uploads accepted
	 */
	protected $_maxFileUploads = null ;

	/**
	 * @var boolean true = rename file to a hash, false = keep original file name but strip out spaces, and special chars
	 */
	// protected $_rebuildFileName = true ;

	/**
	 * @TODO this is not used yet
	 * @var boolean enable multiple uploads
	 */
	protected $_multiple = false ;

	/**
	 * @var integer size of database field to limit file name
	 */
	protected $_fileNameLimit = 0 ;

	protected $_template = "\t<input type=\"file\" name=\"__name__\" id=\"__id__\" value=\"__value__\"/><br>\n\n";

	const TYPE_IMAGE     = 0b0001 ;
	const TYPE_WEB_IMAGE = 0b0010 ;
	const TYPE_VIDEO     = 0b0100 ;
	const TYPE_WEB_VIDEO = 0b1000 ;

	/**
	 * known file types to use as default, if user specify the types he want with type constants
	 * @var array
	 */
	protected $_knownTypes = array (
		// TYPE_IMAGE
		'1' => array ( '.jpg' , '.jpeg' , '.png' , '.gif' , '.bmp' , '.psd' ) , // ...
		// TYPE_WEB_IMAGE
		'2' => array ( '.jpg' , '.jpeg' , '.png' , '.gif' ) , // ...
		// TYPE_VIDEO
		'4' => array ( '.mp4' , '.mpg' , '.wmv' , '.avi' , '.mov' , '.rmvb' ) , // ...
		// TYPE_WEB_VIDEO
		'8' => array ( '.mp4' , '.mpg' , '.wmv' ) , // ...
	);

	/**
	 * instanciate and return instance reference
	 * @param [type] $column        db field name
	 * @param [type] $label         label to be shown on html output
	 * @param [type] $folderToSave  where we want to save our uploaded files
	 * @param [type] $webFolder     how we'll reference this file when accessing it from public web path, relative to public root "/"
	 * @param [type] $fileNameLimit max characters allowed on database to file name
	 * @param array  $allowedTypes  this can be an array of extensions eg.: array('.gif','.png'...) or a group of constants as: File::TYPE_IMAGE|File::TYPE_WEB_IMAGE|File::TYPE_WEB_VIDEO
	 */
	public static function getInstance ( $column , $label , $folderToSave , $webFolder , $fileNameLimit , $allowedTypes = array ( ) ) {
		return new self ( $column , $label , $folderToSave , $webFolder , $fileNameLimit , $allowedTypes ) ;
	}

	/**
	 * [__construct description]
	 * @param [type] $column        db field name
	 * @param [type] $label         label to be shown on html output
	 * @param [type] $folderToSave  where we want to save our uploaded files
	 * @param [type] $webFolder     how we'll reference this file when accessing it from public web path, relative to public root "/"
	 * @param [type] $fileNameLimit max characters allowed on database to file name
	 * @param array  $allowedTypes  this can be an array of extensions eg.: array('.gif','.png'...) or a group of constants as: File::TYPE_IMAGE|File::TYPE_WEB_IMAGE|File::TYPE_WEB_VIDEO
	 */
	public function __construct ( $column , $label , $folderToSave , $webFolder , $fileNameLimit , $allowedTypes = array ( ) ) {

		parent::__construct ( $column , $label );

		if ( !$folderToSave = realpath ( $folderToSave ) ) throw new \TotalFlex\Exception\InvalidPath ( "Diretório para salvar inválido ( $folderToSave ) " );
		$this->_targetFolder   = $folderToSave ;
		$this->_webFolder      = $webFolder ;

		if ( !empty ( $allowedTypes ) ) $this->setAllowedTypes ( $allowedTypes ) ;

		$this->_maxFileSize    = ini_get("upload_max_filesize");
		$this->_maxFileUploads = ini_get("max_file_uploads");

		$this->_fileNameLimit = $fileNameLimit ;

	}

	public function setAllowedTypes ( $allowedTypes ) {
		
		$this->_allowedTypes = $allowedTypes ;
		$allowedTypesArray   = array ();

		if ( gettype ( $allowedTypes ) !== "array" ) {

			foreach ( $this->_knownTypes as $key => $typesArray ) {
				if ( ( $key & $allowedTypes ) !== 0 ) {
					foreach ( $typesArray as $ta ) $allowedTypesArray[] = $ta ;
				}
			}

			$this->_allowedTypes = $allowedTypesArray ;

		}

	}

	public function skipOnUpdate ( ) {

		$file = array (
			'name'     => "" ,
			'type'     => "" ,
			'tmp_name' => "" ,
			'error'    => "" ,
			'size'     => ""
		);

		if ( !isset ( $_FILES["TFFields"]['name'][$this->getView()->getName()]['4']['fields'][$this->getColumn()] ) ) {
			return true ;
		}

		foreach ( $file as $key => &$value ) 
			$value = $_FILES["TFFields"][$key][$this->getView()->getName()]['4']['fields'][$this->getColumn()];

		if ( !is_uploaded_file ( $file['tmp_name'] ) ) {
			return true ;
		}

		return false;

	}

	/**
	 * process something we need to process before update
	 * @return [type] [description]
	 */
	public function processUpdate ( ) {

		$file = array (
			'name'     => "" ,
			'type'     => "" ,
			'tmp_name' => "" ,
			'error'    => "" ,
			'size'     => ""
		);

		if ( !isset ( $_FILES["TFFields"]['name'][$this->getView()->getName()]['4']['fields'][$this->getColumn()] ) ) {
			return false ;
		}

		foreach ( $file as $key => &$value ) 
			$value = $_FILES["TFFields"][$key][$this->getView()->getName()]['4']['fields'][$this->getColumn()];

		if ( !is_uploaded_file ( $file['tmp_name'] ) ) {
			prd("NOT UPLOAD")	;
			throw new Exception ( $this->getUploadErrorMessage ( $file['error'] ) );
		}

		
		if ( !in_array ( \TotalFlex\MimeType::extractExt ( $file['name'] ) , $this->_allowedTypes ) ) 
			throw new \TotalFlex\Exception\InvalidFileType ( "Not allowed file type" );
		
		if ( !\TotalFlex\MimeType::extensionMatchMimeType ( \TotalFlex\MimeType::extractExt ( $file['name'] ) , $file['type'] ) ) 
			throw new \TotalFlex\Exception\InvalidFileType ( "Invalid file" );

		$file['newFileName'] = $this->getNewFileName ( $file['name'] , $this->_targetFolder ) ;

		if ( move_uploaded_file ( $file['tmp_name'] , $this->_targetFolder . DIRECTORY_SEPARATOR . $file['newFileName'] ) ) {
			$this->setValue ( $file['newFileName'] );
		} else {
			$this->setValue ( null );
			throw new Exception ( "Não foi possível salvar o arquivo" );
			
			return false;
		}

		return true ;

	}

	/**
	 * process something we need to process before create
	 * @return [type] [description]
	 */
	public function processCreate ( ) {

		$file = array (
			'name'     => "" ,
			'type'     => "" ,
			'tmp_name' => "" ,
			'error'    => "" ,
			'size'     => ""
		);

		if ( !isset ( $_FILES["TFFields"]['name'][$this->getView()->getName()]['1']['fields'][$this->getColumn()] ) ) {
			return false ;
		}

		foreach ( $file as $key => &$value ) 
			$value = $_FILES["TFFields"][$key][$this->getView()->getName()]['1']['fields'][$this->getColumn()];
		
		if ( !is_uploaded_file ( $file['tmp_name'] ) ) 
			throw new Exception ( $this->getUploadErrorMessage ( $file['error'] ) );

		if ( !in_array ( \TotalFlex\MimeType::extractExt ( $file['name'] ) , $this->_allowedTypes ) ) 
			throw new \TotalFlex\Exception\InvalidFileType ( "Not allowed file type" );
		
		if ( !\TotalFlex\MimeType::extensionMatchMimeType ( \TotalFlex\MimeType::extractExt ( $file['name'] ) , $file['type'] ) ) 
			throw new \TotalFlex\Exception\InvalidFileType ( "Invalid file" );

		$file['newFileName'] = $this->getNewFileName ( $file['name'] , $this->_targetFolder ) ;

		if ( move_uploaded_file ( $file['tmp_name'] , $this->_targetFolder . DIRECTORY_SEPARATOR . $file['newFileName'] ) ) {
			$this->setValue ( $file['newFileName'] );
		} else {
			$this->setValue ( null );
			throw new Exception ( "Não foi possível salvar o arquivo" );
			
			return false;
		}

		return true ;

	}

	private function getNewFileName ( $fileName , $folderToSave ) {

		$folderToSave = preg_replace ( "/\/$/" , "" , $folderToSave );

		$fileName = $this->stripIllegalChars( $fileName );

		$finalName = $this->getUniqueFileName ( $folderToSave , $fileName );

		return $finalName ;

	}

	/**
	 * create a new name to the file and check if filename already exists
	 * if exists, create another name until it's unique
	 * @param  [type] $folderToSave [description]
	 * @param  [type] $fileName     [description]
	 * @return [type]               [description]
	 */
	private function getUniqueFileName ( $folderToSave , $fileName ) {

		$rand       = substr(md5(mt_rand()),0,10) ;
		$ext        = preg_replace ( "/^(.*)(\.\w+)$/" , "$2" , $fileName );
		$withoutExt = preg_replace ( "/^(.*)(\.\w+)$/" , "$1" , $fileName );
		$outputFile = strtolower ( $withoutExt . "-" . $rand . $ext );
		$outputFile = substr ( $outputFile , $this->_fileNameLimit*-1 ) ;

		if ( file_exists ( $folderToSave . "/" . $outputFile ) ) {
			return $this->getUniqueFileName ( $folderToSave , $fileName );
		} else {
			return $outputFile ;
		}

	}

	private function stripIllegalChars ( $fileName ) {

		$fileName = trim($fileName);
		$fileName = preg_replace ( '/\s/' , "_" , $fileName );
		$fileName = preg_replace ( '/[^\.a-zA-Z0-9_-]+/' , "" , $fileName );

		if ( strlen ( $fileName ) < 10 ) $fileName = substr(md5(mt_rand()),0,10) . $fileName ;
		return $fileName ;

	}

	private function getUploadErrorMessage ( $errorNumber ) {
        switch ( $errorNumber ) {
            case UPLOAD_ERR_OK          : return "" ; // 0
            case UPLOAD_ERR_INI_SIZE    : return "Tamanho de arquivo ultrapassa o limite de " . ini_get( 'upload_max_filesize' ) ; // 1
            case UPLOAD_ERR_FORM_SIZE   : return "Tamanho de arquivo ultrapassa o limite permitido" ; // 2
            case UPLOAD_ERR_PARTIAL     : return "O upload foi feito parcialmente" ; // 3
            case UPLOAD_ERR_NO_FILE     : return "Não foi feito o upload do arquivo, favor tentar novamente" ; //4
            case UPLOAD_ERR_NO_TMP_DIR  : return "O servidor não possui um diretório temporário" ; //5
            case UPLOAD_ERR_CANT_WRITE  : return "Erro ao escrever no disco" ; //6
            case UPLOAD_ERR_EXTENSION   : return "Uma extensão do PHP parou o upload do arquivo" ; //7
            default                     : return "Erro de upload não identificado" ; //8
        }
	}

	public function getType ( ) {
		return "file";
	}

}