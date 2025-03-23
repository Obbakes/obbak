<?php
	if ( ! defined('BASEPATH'))
		exit('No direct script access allowed');
        
        

	class subida extends CI_Upload{
            
                
		public function __construct() {
			ini_set('post_max_size', '800M');
			ini_set('upload_max_filesize', '800M');
			ini_set('memory_limit', '300M');
			ini_set('max_input_time', '600M');

			parent::__construct();
                        
		}
                
                // fake "extends C" using magic function
                public function __call($method, $args)
                {
                  $this->image_lib->$method($args[0]);
                }

		/********************************************/
		/* uploadVideo - Sube un video al servidor  */
		/*     en la carpeta indicada convirtiendo- */
		/*     lo al formato y tamano adecuado.     */
		/*                                          */
		/*     Parametros:                          */
		/*        archivo - nombre del campo del    */
		/*            formulario que contiene el    */
		/*            video.                        */
		/*        directorio - ruta de la carpeta   */
		/*            donde guardar el video.       */
		/*        nombre - titulo que tendra el     */
		/*            video subido.                 */
		/*        extenxi�n - extension que tiene */
		/*            el video subido.              */
		/*                                          */
		/*     Retorno:                             */
		/*        nombre - nombre del video subido. */
		/*        -1 - el archivo seleccionado no   */
		/*            tiene un formato soportado.   */
		/*        -2 - el archivo no pudo subirse   */
		/*            correctamente.                */
		/*        -3 - el directorio no esxiste o   */
		/*            no es escribible.             */
		/********************************************/
		function uploadVideo($archivo, $directorio, $nombre, $extension) {
			if(!is_writable($directorio)){ //el directorio no existe o no es escribible //TODO no se crea el directorio si no existe
				return -3;
			}

			$config['upload_path'] = $directorio;
			$config['allowed_types'] = 'mp4|flv|3gp|wmv|avi|m4v|mov';
			$allowed_types = array('mp4', 'flv', '3gp', 'wmv', 'avi', 'm4v', 'mov', 'MP4', 'FLV', '3GP', 'WMV', 'AVI', 'M4V', 'MOV');
			$nombre_fichero = '';
			$miniatura = '';

			if(!in_array($extension, $allowed_types))
				return -1;

			if(empty($nombre)){ //Si el nombre está vacío, generamos un nuevo nombre para el fichero
				$nombre_fichero = 'video_' . mt_rand(1, 1000000000);

				while(file_exists($directorio . '/' . $nombre_fichero . '.' . $extension) || file_exists($directorio . '/' . $nombre_fichero . '.jpg')){
					$nombre_fichero = 'video_' . mt_rand(1, 1000000000);
				}
			}
			else{ //Si el archivo a subir debe sustituir a otro archivo
				$file_info = pathinfo($nombre);

				//Comprobamos que coinciden en extension el nuevo archivo y el antiguo
				//, en este caso el nombre será el mismo
				if(isset($file_info['extension']) && $file_info['extension'] == $extension)
					$nombre_fichero = $file_info['basename'];
				//Si no, comprobamos que no existe otro archivo (que no debe sustituirse)
				//con ese nombre y la nueva extension
				else if(file_exists($directorio . '/' . $file_info['filename'] . '.' . $extension)){
					//Si es asi, generamos un nuevo nombre para el nuevo archivo
					srand (time());
					$nombre_fichero = 'video_' . mt_rand(1, 1000000000);

					while(file_exists($directorio . '/' . $nombre_fichero . '.' . $extension) || file_exists($directorio . '/' . $nombre_fichero . '.jpg')){
						$nombre_fichero = 'video_' . mt_rand(1, 1000000000);
					}
				}
				else{ //Si no el nombre del nuevo archivo sera el antiguo con la nueva extension
					$nombre_fichero = $file_info['filename'];
				}

				@unlink($nombre);
			}
				
			$miniatura = 'thumb_' . mt_rand(1, 1000000000);
			
			while(file_exists($directorio . '/thumbs/' . $miniatura . '.' . $extension) || file_exists($directorio . '/thumbs/' . $miniatura . '.jpg')){
				$miniatura = 'thumb_' . mt_rand(1, 1000000000);
			}

			$config['file_name'] = $nombre_fichero . 'prueba.' . $extension;

			$this->initialize($config);

			if ( $this->do_upload($archivo)){
				//Codificamos el video a mp4 con ancho 640 y manteniendo el ratio anterior
				$ffmpeg = 'ffmpeg';

				$video_source_path = $directorio . '/' . $nombre_fichero . 'prueba.' . $extension;
				$iReturn = 0; //codigo de retorno de los comandos; 0 = ejecucion correcta

				//procesando el video
				$image_cmd = ' -vcodec libx264 -acodec libmp3lame -ac 2 -vf "scale=640:trunc(ow/a/2)*2" -r 24 ';
				$dest_image_path = $directorio . '/' . $nombre_fichero . '.mp4';
				$str_command = $ffmpeg . ' -strict experimental -i ' . $video_source_path . $image_cmd . $dest_image_path;
				//shell_exec($str_command);
				exec($str_command, $aOutput, $iReturn);

				if(empty($iReturn)){ //se ha ejecutado ffmpeg correctamente
					$extension = '.mp4';
					//creando el thumbnail
					$thumbnail = ' -ss 1 -i ' . $video_source_path . ' -vframes 1 ' . $directorio . '/thumbs/' . $miniatura . '.jpg';
					//shell_exec($ffmpeg . $thumbnail);
					exec($ffmpeg . $thumbnail, $aOutput, $iReturn);

					unlink($video_source_path);
				}
				else{
					$extension = '.' . $extension;
					rename($video_source_path, $directorio . '/' . $nombre_fichero . $extension);
					copy('contenidos/video_captura.jpg', $directorio . '/thumbs/' . $miniatura . '.jpg');
				}

				//$data = array('upload_data' => $this->data());

				/*
				//Creamos la miniatura del video
				$ffmpeg ="ffmpeg.exe";
				$image_source_path = "./bandas/".$id_banda."/videos/" . $file_name.".mp4";
				$image_cmd = " -r 1 -ss 00:00:10 -t 00:00:01 -s 200x200 -f image2 " ;
				$dest_image_path = "./bandas/".$id_banda."/videos/thumb/thumbnail" . $numero_aleatorio."logo-".$id_banda.".jpg";
				$str_command= $ffmpeg  ." -i " . $image_source_path . $image_cmd .$dest_image_path;
				shell_exec($str_command);
				*/

				return array('archivo' => $directorio . '/' . $nombre_fichero . $extension, 'miniatura' => $directorio . '/thumbs/' . $miniatura . '.jpg');
			}

			return -2;
		}

		/********************************************/
		/* uploadAudio - Sube un audio al servidor  */
		/*     en la carpeta indicada convirtiendo- */
		/*     lo al formato y tamano adecuado.     */
		/*                                          */
		/*     Parametros:                          */
		/*        archivo - nombre del campo del    */
		/*            formulario que contiene el    */
		/*            audio.                        */
		/*        directorio - ruta de la carpeta   */
		/*            donde guardar el audio.       */
		/*        nombre - titulo que tendra el     */
		/*            audio subido.                 */
		/*        extenxion - extension que tiene   */
		/*            el audio subido.              */
		/*                                          */
		/*     Retorno:                             */
		/*        nombre - nombre del audio subido. */
		/*        -1 - el archivo seleccionado no   */
		/*            tiene un formato soportado.   */
		/*        -2 - el archivo no pudo subirse   */
		/*            correctamente.                */
		/*        -3 - el directorio no esxiste o   */
		/*            no es escribible.             */
		/********************************************/
		function uploadAudio($archivo, $directorio, $nombre, $extension) {
			if(!is_writable($directorio)){ //el directorio no existe o no es escribible //TODO no se crea el directorio si no existe
				return -3;
			}

			$config['upload_path'] = $directorio;
			$config['allowed_types'] = '*';
			$allowed_types = array('mp3', 'wma', 'MP3', 'WMA');
			$nombre_fichero = '';

			if(!in_array($extension, $allowed_types))
				return -1;

			if(empty($nombre)){ //Si el nombre está vacío, generamos un nuevo nombre para el fichero
				$nombre_fichero = 'audio_' . mt_rand(1, 1000000000);

				while(file_exists($directorio . '/' . $nombre_fichero . '.' . $extension) || file_exists($directorio . '/' . $nombre_fichero . '.jpg')){
					$nombre_fichero = 'audio_' . mt_rand(1, 1000000000);
				}
			}
			else{ //Si el archivo a subir debe sustituir a otro archivo
				$file_info = pathinfo($nombre);

				//Comprobamos que coinciden en extension el nuevo archivo y el antiguo
				//, en este caso el nombre será el mismo
				if(isset($file_info['extension']) && $file_info['extension'] == $extension)
					$nombre_fichero = $file_info['basename'];
				//Si no, comprobamos que no existe otro archivo (que no debe sustituirse)
				//con ese nombre y la nueva extension
				else if(file_exists($directorio . '/' . $file_info['filename'] . '.' . $extension) || file_exists($directorio . '/' . $nombre_fichero . '.jpg')){
					//Si es asi, generamos un nuevo nombre para el nuevo archivo
					srand (time());
					$nombre_fichero = 'audio_' . mt_rand(1, 1000000000);

					while(file_exists($directorio . '/' . $nombre_fichero . '.' . $extension)){
						$nombre_fichero = 'audio_' . mt_rand(1, 1000000000);
					}
				}
				else{ //Si no el nombre del nuevo archivo sera el antiguo con la nueva extension
					$nombre_fichero = $file_info['filename'];
				}

				@unlink($nombre);
			}

			$config['file_name'] = $nombre_fichero . 'prueba.' . $extension;

			$this->initialize($config);

			if($this->do_upload($archivo)){
				$iReturn = 0; //codigo de retorno de los comandos; 0 = ejecucion correcta

				//Codificamos el audio a mp3
				$ffmpeg = 'ffmpeg';
				$audio_source_path = $directorio . '/' . $nombre_fichero . 'prueba.' . $extension;
				$dest_audio_path = $directorio . '/' . $nombre_fichero . '.mp3';
				$str_command= $ffmpeg  . ' -strict experimental -i ' . $audio_source_path . ' -acodec libmp3lame ' . $dest_audio_path;
				shell_exec($str_command);
				exec($str_command, $aOutput, $iReturn);

				if(!$iReturn){ //se ha ejecutado ffmpeg correctamente
					unlink($audio_source_path);
				}
				else{
					rename($audio_source_path, $directorio . '/' . $nombre_fichero . '.' . $extension);
				}

				return array('archivo' => $directorio . '/' . $nombre_fichero . '.mp3', 'miniatura' => '');
			}

			return -2;
		}

		/********************************************/
		/* uploadImagen - Sube una imagen al servi- */
		/*     dor en la carpeta indicada.          */
		/*                                          */
		/*     Parametros:                          */
		/*        archivo - nombre del campo del    */
		/*            formulario que contiene la    */
		/*            imagen.                       */
		/*        directorio - ruta de la carpeta   */
		/*            donde guardar la imagen.      */
		/*        nombre - nombre del fichero al    */
		/*            que sustituirá.              */
		/*        extenxion - extension que tiene   */
		/*            la imagen subido.             */
		/*                                          */
		/*     Retorno:                             */
		/*        nombre - nombre de la imagen su-  */
		/*            bida.                         */
		/*        -1 - el archivo seleccionado no   */
		/*            tiene un formato soportado.   */
		/*        -2 - el archivo no pudo subirse   */
		/*            correctamente.                */
		/*        -3 - el directorio no esxiste o   */
		/*            no es escribible.             */
		/********************************************/
		function uploadImagen($archivo, $directorio, $nombre, $extension, $thumb = false, $new_format = '') {

			if(!is_writable(realpath($directorio))){ //el directorio no existe o no es escribible //TODO no se crea el directorio si no existe
				return -3;
			}

			$config['upload_path'] = $directorio;
			$config['allowed_types'] = 'gif|jpg|png';
			$allowed_types = array('jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG');
			$nombre_fichero = '';
            $config['maintain_ratio'] = TRUE;
			$prefijo = 'imagen_';
			
			if(!in_array($extension, $allowed_types))
				return -1;

            if ($new_format!=''){
                $extension = $new_format;
            }
                        
			if(empty($nombre)){ //Si el nombre está vacío, generamos un nuevo nombre para el fichero
				$nombre_fichero = $prefijo . mt_rand(1, 1000000000) . '.' . $extension;

				while(file_exists($directorio . '/' . $nombre_fichero)){
					$nombre_fichero = $prefijo . mt_rand(1, 1000000000) . '.' . $extension;
				}
			} else { //Si el archivo a subir debe sustituir a otro archivo
				$file_info = pathinfo($nombre);

				//Comprobamos que coinciden en extension el nuevo archivo y el antiguo
				//, en este caso el nombre será el mismo
				if(isset($file_info['extension']) && $file_info['extension'] == $extension){
					$nombre_fichero = $file_info['basename'];
				}
				//Si no, comprobamos que no existe otro archivo (que no debe sustituirse)
				//con ese nombre y la nueva extension
				else if(file_exists($directorio . '/' . $file_info['filename'] . '.' . $extension)){
					//Si es asi, generamos un nuevo nombre para el nuevo archivo

				    $nombre_fichero = $file_info['basename'] . '_' . mt_rand(1, 1000) . '.' . $extension;

					while(file_exists($directorio . '/' . $nombre_fichero)){
					    $nombre_fichero = $file_info['basename'] . '_' . mt_rand(1, 1000) . '.' . $extension;
					}
				}
				else{ //Si no el nombre del nuevo archivo sera el antiguo con la nueva extension
					$nombre_fichero = $file_info['filename'] . '.' . $extension;
				}

				@unlink($nombre);
			}

			$config['file_name'] = $nombre_fichero;
                        //$config['new_image']=$directorio . '/' . $nombre_fichero;

			$this->initialize($config);

			if ($this->do_upload($archivo)){
			    
			    
	                            $CI =& get_instance();
								$CI->load->library('image_lib');
                                $CI->image_lib->clear();
                                //Reducimos la calidad de la imagen
                                $config['image_library'] = 'gd2';
                                //CARPETA EN LA QUE ESTÁ LA IMAGEN A REDIMENSIONAR
                                $config['source_image'] = $directorio . '/' . $nombre_fichero;
                                $config['quality'] = '60';
                                $config['maintain_ratio'] = TRUE;
                                $config['width'] = 600;
                                $config['height'] = 800;
                                $config['master_dim'] = 'width';
                                

                                $CI->image_lib->initialize($config); 
                                $CI->image_lib->resize();
                
			    
                                if($thumb){
                                  
                                    $CI->image_lib->clear();
                                    $config['image_library']  = 'gd2';
                                    $config['source_image']   = $directorio . '/' . $nombre_fichero;
                                    $config['maintain_ratio'] = TRUE;
                                    $config['new_image']= $directorio . '/thumb_' . $nombre_fichero;
                                    //$config['width'] = 290;
                                    $config['quality'] = '60';
                                    $config['width'] = 300;
                                    $config['height'] = 400;
                                    
                                    
                                    //Creamos el thumbnail simplemente
                                    $CI->image_lib->initialize($config); 
                                    $CI->image_lib->resize();
                                    
                                    list($img_w, $img_h) = getimagesize($directorio . '/' . $nombre_fichero);
                                    
                                    if ($img_w > $img_h) {
                                            $config['width']      = 290;
                                            $config['height']     = 160;
                                            $config['y_axis']     = 0;
                                    }

                                    if ($img_h > $img_w) {
                                            $config['width']      = $img_w;
                                            $config['height']     = $img_h / 2;
                                            $config['y_axis']     = $img_h / 4;
                                    }

                                    if ($img_w == $img_h) {
                                            $config['width']      = 290;
                                            $config['height']     = 160;
                                    }
                                    
                                    $this->image_lib->initialize($config);

                                    if (!$this->image_lib->crop()) {
                                            echo $this->image_lib->display_errors();
                                    } else {
                                            $this->image_lib->clear();
                                    }
                                    
                                    $config['image_library'] = 'gd2';
                                    //CARPETA EN LA QUE ESTÁ LA IMAGEN A REDIMENSIONAR
                                    $config['source_image'] = $directorio . '/thumb_' . $nombre_fichero;
                                    $config['create_thumb'] = TRUE;
                                    $config['maintain_ratio'] = TRUE;
                                    //CARPETA EN LA QUE GUARDAMOS LA MINIATURA
                                    
                                    $config['width'] = 400;
                                    $config['height'] = 160;
                                    $this->image_lib->initialize($config); 
                                    $this->image_lib->resize();
									

                                }
                            
				return array('archivo' => $directorio . '/' . $nombre_fichero, 'miniatura' => '');
			}      
			return -2;
		}
                
                
	}
