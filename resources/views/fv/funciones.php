<?php

use App\Models\tipo;

class misFunciones
{
    /**
     * Esta función elimina cualquier extensión de archivo (como .jpg o .png) al final de una URL.
     * Devuelve la URL limpia sin la extensión.
     */
    public static function limpiarURL($url)
    {
        $url_limpia = preg_replace('/\.(jpg|png).*$/i', '.$1', $url);

        return $url_limpia;
    }

/**
 * Esta función verifica si un array dado es un array no asociativo, es decir, si sus claves son enteros consecutivos.
 * Devuelve true si el array es no asociativo, de lo contrario, devuelve false.
 */
    public static function esArrayNoAsociativo($array)
    {
        if (!is_array($array)) {
            return false;
        }

        $keys = array_keys($array);

        return array_keys($keys) === $keys;
    }

/**
 * Esta función convierte un array asociativo en una lista, manteniendo solo las claves que tienen valores verdaderos.
 * Devuelve la lista resultante.
 */
    public static function convertirArrayAsociativoALista($arrayAsociativo)
    {
        if (is_array($arrayAsociativo)) {
            return $arrayAsociativo;
        }

        $lista = [];
        foreach ($arrayAsociativo as $clave => $valor) {
            if ($valor) {
                $lista[] = $clave;
            }
        }
        return $lista;
    }

/**
 * Esta función convierte una cadena separada por un separador dado en un array.
 * Si el valor de entrada es numérico, lo convierte en una cadena y lo devuelve en un array.
 * Si ya es un array, simplemente lo devuelve.
 * Devuelve el array resultante.
 */
    public static function stringToArray($valor, $separator = ',')
    {
        $aux = null;

        if ($valor !== null && is_string($valor)) {
            $aux = explode($separator, $valor);

            if ($aux !== null) {
                if (is_string($aux)) {
                    $aux = [$aux];
                }
            }
        } else {
            if (is_numeric($valor)) {
                $aux = [strval($valor)];
            } else {
                $aux = [$valor];
            }
        }

        return $aux;
    }

    /**
     * Esta función convierte una fecha dada en un formato específico (DD/MM/YYYY o YYYY-MM-DD) al otro formato.
     * Acepta entrada de tipo string, array o objeto.
     * Devuelve la fecha convertida al formato deseado.
     */
    public static function convertirFormatoFecha($entrada)
    {
        if (is_string($entrada)) {
            return misFunciones::convertirFechaAux($entrada);
        }

        if (is_array($entrada)) {
            foreach ($entrada as $clave => $valor) {
                $entrada[$clave] = misFunciones::convertirFormatoFecha($valor);
            }
            return $entrada;
        }

        if (is_object($entrada)) {
            foreach ($entrada as $clave => $valor) {
                $entrada->$clave = misFunciones::convertirFormatoFecha($valor);
            }
            return $entrada;
        }

        return $entrada;
    }

/**
 * Esta función convierte una fecha dada en el formato incorrecto (DD/MM/YYYY o YYYY-MM-DD) al formato correcto (DD/MM/YYYY).
 * Devuelve la fecha convertida al formato correcto.
 */
    public static function convertirFechaAux($fecha)
    {
        if (preg_match('/^\d{2}\-\d{2}\-\d{4}$/', $fecha)) {
            return $fecha;
        }

        $partes = explode('/', $fecha);
        $partes2 = explode('-', $fecha);
        $parte = '/';
        if (count($partes2) === 3) {
            $partes = $partes2;
            $parte = '-';
        }

        if (count($partes) !== 3 && count($partes2) !== 3) {
            return $fecha;
        }

        $esFormatoYMD = strpos($fecha, $parte) === 4;

        if ($esFormatoYMD) {
            $fecha = $partes[2] . '-' . $partes[1] . '-' . $partes[0];
        } else {
            $fecha = $partes[0] . '-' . $partes[1] . '-' . $partes[2];
        }

        return $fecha;
    }

    /**
     * Esta función realiza la paginación de un conjunto de eventos.
     * Ordena los eventos según el criterio especificado y los divide en páginas.
     * Devuelve un array con los eventos de la página actual, el número total de páginas y un indicador de estado.
     */
    public static function paginacion($eventos, $page, $per_page, $order, $orderby)
    {
        // Aplicar ordenamiento
        $sortedEventos = collect($eventos);

        // Verificar si el campo de orden es una fecha
        if (misFunciones::esFecha(misFunciones::convertirFechaAux($sortedEventos->first()[$orderby]))) {
            $sortedEventos = ($order == 'asc') ?
            $sortedEventos->sortBy(function ($evento) use ($orderby) {
                // Convertir la fecha al formato Y-m-d para asegurar una ordenación correcta
                return strtotime(misFunciones::convertirFechaAux($evento[$orderby]));
            })->values() :
            $sortedEventos->sortByDesc(function ($evento) use ($orderby) {
                // Convertir la fecha al formato Y-m-d para asegurar una ordenación correcta
                return strtotime(misFunciones::convertirFechaAux($evento[$orderby]));
            })->values();
        } else {
            // Ordenar según el campo de orden tal cual
            $sortedEventos = ($order == 'asc') ?
            $sortedEventos->sortBy($orderby)->values() :
            $sortedEventos->sortByDesc($orderby)->values();
        }

        // Calcular el índice de inicio y fin para la paginación
        $startIndex = ($page - 1) * $per_page;
        $slicedEventos = $sortedEventos->slice($startIndex, $per_page);
        $slicedEventos = $slicedEventos->values();

        // Calcular el número total de páginas
        $totalPages = ceil(count($sortedEventos) / $per_page);

        // Devolver los eventos filtrados, ordenados y la información de paginación
        return [
            'eventos' => $slicedEventos,
            'totalPages' => $totalPages,
            'status' => true,
        ];
    }

    // Función auxiliar para verificar si una cadena es una fecha
    private static function esFecha($cadena)
    {
        return (bool) strtotime($cadena);
    }

    /**
     * Esta función convierte una fecha en formato DD/MM/YYYY al formato DateTime y luego al formato deseado.
     * Si la fecha ya está en formato DateTime o no es válida, se devuelve tal cual.
     * Devuelve la fecha convertida al formato deseado.
     */
    private static function convertirAFechaDatetime($fecha)
    {
        if (count(explode('-', $fecha)) > 1) {
            return $fecha;
        }

        if (count(explode('/', $fecha)) <= 1) {
            return $fecha;
        }

        $aux = explode('/', $fecha);
        $fechaDatetime = new DateTime("$aux[2]-$aux[1]-$aux[0]");
        return $fechaDatetime->format('d-m-Y');
    }

/**
 * Esta función convierte una fecha dada en el formato deseado.
 * Soporta varios formatos de entrada y puede manejar formatos especiales como "Today" o "Hoy".
 * Devuelve la fecha convertida según el tipo de conversión especificado.
 */
    public static function convertirFecha($fecha, $tipoConversion)
    {
        $formatos = [
            1 => 'd/m/Y',
            2 => 'Y/m/d',
            3 => 'Y-m-d',
            4 => 'd-m-Y',
            5 => 'Ymd',
            // Agrega más formatos según tus necesidades
        ];

        if (stripos($fecha, 'Today') !== false || stripos($fecha, 'Hoy') !== false) {
            $fechaObj = new DateTime();
        } elseif (preg_match('/(?:Next|Final) Deadline: ([A-Za-z]+) (\d{1,2}), (\d{4})/', $fecha, $matches)) {
            $month = $matches[1];
            $day = $matches[2];
            $year = $matches[3];
            $fechaObj = new DateTime("$month $day, $year");
        } else {
            $fechaObj = new DateTime(misFunciones::convertirAFechaDatetime($fecha));
        }

        if (array_key_exists($tipoConversion, $formatos)) {
            $fechaFormateada = $fechaObj->format($formatos[$tipoConversion]);
            return $fechaFormateada;
        } else {
            return "Tipo de conversión no válido";
        }
    }

    /**
     * Esta función obtiene el nombre del mes a partir de su número o su representación en palabras.
     * Devuelve el nombre del mes en formato de dos dígitos (01-12).
     */
    private static function obtenerMesEnNumeros($numeroMes)
    {
        // Definir un array asociativo con los nombres de los meses
        $mesesEnPalabras = [
            'ene' => '01',
            'feb' => '02',
            'mar' => '03',
            'abr' => '04',
            'may' => '05',
            'jun' => '06',
            'jul' => '07',
            'ago' => '08',
            'sep' => '09',
            'oct' => '10',
            'nov' => '11',
            'dic' => '12',
            'January' => '01',
            'February' => '02',
            'March' => '03',
            'April' => '04',
            'May' => '05',
            'June' => '06',
            'July' => '07',
            'August' => '08',
            'September' => '09',
            'October' => '10',
            'November' => '11',
            'December' => '12',
        ];

        // Verificar si el número del mes ya está en formato de dos dígitos
        if (strlen($numeroMes) == 2 && ctype_digit($numeroMes)) {
            return $numeroMes;
        }

        // Obtener el número del mes a partir de su representación en palabras
        return $mesesEnPalabras[$numeroMes];
    }

/**
 * Esta función obtiene el nombre del mes a partir de su número.
 * Devuelve el nombre del mes en formato de tres letras (ene-dic).
 */
    private static function obtenerMesEnPalabras($numeroMes)
    {
        // Definir un array asociativo con los nombres de los meses
        $mesesEnPalabras = [
            '01' => 'ene',
            '02' => 'feb',
            '03' => 'mar',
            '04' => 'abr',
            '05' => 'may',
            '06' => 'jun',
            '07' => 'jul',
            '08' => 'ago',
            '09' => 'sep',
            '10' => 'oct',
            '11' => 'nov',
            '12' => 'dic',
        ];

        // Verificar si el número del mes está en el formato de tres letras
        if (strlen($numeroMes) == 3 && array_search($numeroMes, $mesesEnPalabras)) {
            return $numeroMes;
        }

        // Obtener el nombre del mes a partir del número
        return $mesesEnPalabras[$numeroMes];
    }

    /**
     * Esta función obtiene la extensión de archivo de una imagen.
     * Devuelve la extensión de archivo.
     */
    public static function getMime($image)
    {
        return $image->getClientOriginalExtension();
    }

/**
 * Esta función convierte una imagen en un archivo base64.
 * Devuelve la representación base64 de la imagen.
 */
    public static function getBase64($image)
    {
        $flujo = fopen($image->getRealPath(), 'r');
        $enbase64 = base64_encode(fread($flujo, filesize($image->getRealPath())));
        fclose($flujo);
        return $enbase64;
    }

/**
 * Esta función verifica el ancho de una imagen.
 * Devuelve un booleano
 */
    public static function imagen_width($valor, $width)
    {
        if (is_string($valor)) {
            $binary = base64_decode(explode(',', $valor)[1]);
            $valor = getimagesizefromstring($binary);
        } else {
            $valor = getimagesize($valor['tmp_name']);
        }
        $imagewidth = explode(',', $width);
        if ($valor[0] < $imagewidth[0] || $valor[0] > $imagewidth[1]) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Esta función verifica la altura de una imagen.
     * Devuelve true si la altura de la imagen está dentro del rango especificado, de lo contrario, devuelve false.
     */
    public static function imagen_height($valor, $width)
    {
        if (is_string($valor)) {
            $binary = base64_decode(explode(',', $valor)[1]);
            $valor = getimagesizefromstring($binary);
        } else {
            $valor = getimagesize($valor['tmp_name']);
        }
        $imageheight = explode(',', $width);
        if ($valor[1] < $imageheight[0] || $valor[1] > $imageheight[1]) {
            return false;
        }
        return true;
    }

/**
 * Esta función verifica la extensión de un archivo de imagen.
 * Devuelve el nombre de la extensión si es válida, de lo contrario, devuelve false.
 */
    public static function imagen_extencion($valor, $extensiones)
    {
        if (is_string($valor)) {
            if (misFunciones::vbase64($valor, true)) {
                $extencion = explode('/', mime_content_type($valor))[1];
            } else {
                $extencion = explode('.', $valor);
                $extencion = $extencion[count($extencion) - 1];
            }
        } else {
            $valor = $valor['name'];
            $extencion = explode('.', $valor);
        }

        $extencionescontador = 0;
        $extensiones = explode(',', $extensiones);

        for ($x = 0; $x < count($extensiones); $x++) {
            if ($extencion !== $extensiones[$x]) {
                $extencionescontador++;
            } else {
                $returname = $extensiones[$x];
            }
        }

        if ($extencionescontador === count($extensiones)) {
            return false;
        }
        return $returname;
    }

    /**
     * Esta función ajusta la calidad de una imagen codificada en base64.
     * Devuelve una nueva representación base64 de la imagen con la calidad especificada.
     */
    public static function qualityBase64img($base64img, $mimeimg, $quality)
    {
        ob_start();
        $im = imagecreatefromstring(base64_decode($base64img));

        switch ($mimeimg) {
            case 'png':
            case 'image/png':
                imagepng($im, null, $quality);
                break;
            case 'jpg':
            case 'image/jpg':
            case 'jpeg':
            case 'image/jpeg':
                imagejpeg($im, null, $quality);
                break;
            case 'image/gif':
            case 'gif':
                imagegif($im, null, $quality);
        }

        $stream = ob_get_clean();
        $newB64 = base64_encode($stream);
        imagedestroy($im);
        return $newB64;
    }

/**
 * Esta función redimensiona una imagen codificada en base64 a las dimensiones especificadas.
 * Devuelve una nueva representación base64 de la imagen redimensionada.
 */
    public static function resizeBase64img($base64img, $mimeimg, $newwidth, $newheight)
    {
        // Obtener las dimensiones originales de la imagen
        list($width, $height) = getimagesizefromstring(base64_decode($base64img));

        ob_start();
        $temp_thumb = imagecreatetruecolor($newwidth, $newheight);
        imagealphablending($temp_thumb, false);
        imagesavealpha($temp_thumb, true);

        $source = imagecreatefromstring(base64_decode($base64img));

        // Redimensionar la imagen
        imagecopyresized($temp_thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        // Crear una nueva imagen con las dimensiones especificadas
        switch ($mimeimg) {
            case 'png':
            case 'image/png':
                imagepng($temp_thumb, null);
                break;
            case 'jpg':
            case 'image/jpg':
            case 'jpeg':
            case 'image/jpeg':
                imagejpeg($temp_thumb, null);
                break;
            case 'image/gif':
            case 'gif':
                imagegif($temp_thumb, null);
        }

        $stream = ob_get_clean();
        $newB64 = base64_encode($stream);
        imagedestroy($temp_thumb);
        imagedestroy($source);
        return $newB64;
    }

    /**
     * Esta función redimensiona una imagen codificada en base64 y escala su ancho proporcionalemente.
     * Devuelve una nueva representación base64 de la imagen redimensionada y escalada.
     */
    public static function resizeBase64andScaleWidth($base64img, $mimeimg, $newheight)
    {
        // Obtener las dimensiones originales de la imagen
        list($width, $height) = getimagesizefromstring(base64_decode($base64img));

        // Calcular el nuevo ancho con la misma pérdida o ganancia proporcional de la altura (escalar)
        $porNewHeight = ($newheight * 100) / $height;
        $newwidth = (int) ($width * ($porNewHeight / 100));

        ob_start();
        $temp_thumb = imagecreatetruecolor($newwidth, $newheight);
        imagealphablending($temp_thumb, false);
        imagesavealpha($temp_thumb, true);

        $source = imagecreatefromstring(base64_decode($base64img));

        // Redimensionar
        imagecopyresized($temp_thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        // Crear una nueva imagen con las dimensiones especificadas
        switch ($mimeimg) {
            case 'png':
            case 'image/png':
                imagepng($temp_thumb, null);
                break;
            case 'jpg':
            case 'image/jpg':
            case 'jpeg':
            case 'image/jpeg':
                imagejpeg($temp_thumb, null);
                break;
            case 'image/gif':
            case 'gif':
                imagegif($temp_thumb, null);
        }

        $stream = ob_get_clean();
        $newB64 = base64_encode($stream);

        imagedestroy($temp_thumb);
        imagedestroy($source);

        return $newB64;
    }

/**
 * Esta función redimensiona una imagen codificada en base64 y escala su alto proporcionalmente.
 * Devuelve una nueva representación base64 de la imagen redimensionada y escalada.
 */
    public static function resizeBase64andScaleHeight($base64img, $mimeimg, $newwidth)
    {
        // Obtener las dimensiones originales de la imagen
        list($width, $height) = getimagesizefromstring(base64_decode($base64img));

        // Calcular el nuevo alto con la misma pérdida o ganancia proporcional del ancho (escalar)
        $porNewWidth = ($newwidth * 100) / $width;
        $newHeight = (int) ($height * ($porNewWidth / 100));

        ob_start();
        $temp_thumb = imagecreatetruecolor($newwidth, $newHeight);
        imagealphablending($temp_thumb, false);
        imagesavealpha($temp_thumb, true);

        $source = imagecreatefromstring(base64_decode($base64img));

        // Redimensionar
        imagecopyresized($temp_thumb, $source, 0, 0, 0, 0, $newwidth, $newHeight, $width, $height);

        // Crear una nueva imagen con las dimensiones especificadas
        switch ($mimeimg) {
            case 'png':
            case 'image/png':
                imagepng($temp_thumb, null);
                break;
            case 'jpg':
            case 'image/jpg':
            case 'jpeg':
            case 'image/jpeg':
                imagejpeg($temp_thumb, null);
                break;
            case 'image/gif':
            case 'gif':
                imagegif($temp_thumb, null);
        }

        $stream = ob_get_clean();
        $newB64 = base64_encode($stream);

        imagedestroy($temp_thumb);
        imagedestroy($source);

        return $newB64;
    }

    // public static function log($e, $type)
    // {
    //     $result = 'none';

    //     if ($type === 'PHP') {

    //         $linea = $e->getLine();
    //         $errorTitle = 'PHP:' . explode('(', $e->getMessage())[0];
    //         $archivoC = $e->getFile();
    //         $mensaje = $e->getMessage();
    //     } else {
    //         $linea = $e->linea;
    //         $errorTitle = $e->error;
    //         $archivoC = $e->archivo;
    //         $mensaje = $e->errorLong;
    //     }
    //     $log = new App\Models\log();
    //     $log->linea = $linea;
    //     $log->error = $errorTitle;
    //     $log->archivo = $archivoC;
    //     $log->save();
    //     $micarpeta = '../log';
    //     if (!file_exists($micarpeta)) {
    //         if (!mkdir($micarpeta, 0777, true)) {
    //             $result = 'Fallo al crear las carpetas...';
    //         }
    //     }
    //     $fecha = explode(" ", (string) $log->created_at);
    //     $micarpeta = '../log/' . $fecha[0];
    //     if (!file_exists($micarpeta)) {
    //         if (!mkdir($micarpeta, 0777, true)) {
    //             $result = 'Fallo al crear las carpetas...';
    //         }
    //     }
    //     $archivo = fopen("../log/" . $fecha[0] . '/log.txt', "a");
    //     fwrite($archivo, "\n-.-:v" . $log->id . "-.-:v----------------------INI(" . $fecha[1] . ")------------------------------------------------------------\n\n");
    //     fwrite($archivo, $mensaje . '');
    //     fwrite($archivo, "\n\n-.-:v" . $log->id . "-.-:v----------------------FIN(" . $fecha[1] . ")-----------------------------------------------------------\n\n");
    //     if ($archivo == false) {
    //         $result = "Error al crear el archivo";
    //     } else {
    //         $result = "El archivo ha sido creado";
    //     }

    //     fclose($archivo);
    //     return $result;
    // }

    /**
     * Esta función devuelve la dirección IP real del cliente.
     * Devuelve la dirección IP real del cliente.
     */
    public static function getRealIP()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        return $_SERVER['REMOTE_ADDR'];
    }
    public static function validarCiudadVenezuela($estado, $valor)
    {
        switch ($estado) {
            case "Amazonas":
                misFunciones::palabras_clave_unica($valor, 'Puerto Ayacucho');
                break;
            case "Anzoategui":
                misFunciones::palabras_clave_unica($valor, 'Anaco,Barcelona,Clarines,El Tigre,Guanta,Pariguan,Puerto La Cruz,Puerto Piritu');
                break;
            case "Apure":

                misFunciones::palabras_clave_unica($valor, 'Achaguas,Biruaca,Guasdualito,San Fernando De Apure');
                break;
            case "Aragua":

                misFunciones::palabras_clave_unica($valor, 'Cagua,La Victoria (aragua),Maracay,Palo Negro,Santa Cruz De Aragua,Santa Rita (aragua),Turmero,Villa De Cura');
                break;
            case "Barinas":

                misFunciones::palabras_clave_unica($valor, 'Barinas,Barinitas,Ciudad Bolivia,Sabaneta (barinas),Santa Barbara (barinas),Socopo');
                break;
            case "Bolivar":

                misFunciones::palabras_clave_unica($valor, 'Ciudad Bolivar,Puerto Ordaz,San Felix (bolivar),Upata');
                break;
            case "Cojedes":

                misFunciones::palabras_clave_unica($valor, 'San Carlos (cojedes),Tinaquillo');
                break;
            case "Carabobo":

                misFunciones::palabras_clave_unica($valor, 'Bejuma,Guacara,Guigue,Los Guayos,Mariara,Moron,Naguanagua,Puerto Cabello,San Joaquin (carabobo),Valencia');
                break;
            case "Delta Amacuro":

                misFunciones::palabras_clave_unica($valor, 'Tucupita');
                break;
            case "Distrito Capital":

                misFunciones::palabras_clave_unica($valor, 'Caraballeda Edo.vargas,Caracas,Catia La Mar Edo.vargas,El Junquito Dtto.capital,Maiquetia Edo.vargas');
                break;
            case "Falcon":

                misFunciones::palabras_clave_unica($valor, 'Carirubana,Chichiriviche,Coro,Dabajuro,La Vela De Coro,Puerto Cumarebo,Punto Fijo,Tucacas');
                break;
            case "Guarico":

                misFunciones::palabras_clave_unica($valor, 'Altagracia De Orituco,Calabozo,Camaguan,San Juan De Los Morros,Valle De La Pascua,Zaraza');
                break;
            case "Lara":

                misFunciones::palabras_clave_unica($valor, 'Barquisimeto,Cabudare,Carora,Duaca,El Tocuyo,Quibor');
                break;
            case "Merida":

                misFunciones::palabras_clave_unica($valor, 'Ejido,El Vigia,La Azulita,Lagunillas (merida),MeridaTovar (merida)');
                break;
            case "Miranda":

                misFunciones::palabras_clave_unica($valor, 'Carrizal,Caucagua,Charallave,CuaGuarenas,Guatire,Higuerote,Los Teques (miranda),Ocumare Del Tuy,Rio Chico,San Antonio De Los Altos,Santa Teresa Del Tuy');
                break;
            case "Monagas":

                misFunciones::palabras_clave_unica($valor, 'Caripe,Maturin,Punta De Mata,Temblador');
                break;
            case "Nueva Esparta":

                misFunciones::palabras_clave_unica($valor, 'El Valle Del Espiritu Santo.La Asuncion,Pampatar,Porlamar,San Juan Bautista');
                break;
            case "Portuguesa":

                misFunciones::palabras_clave_unica($valor, 'Acarigua,Araure,Biscucuy,Guanare,Guanarito,Turen');
                break;
            case "Sucre":

                misFunciones::palabras_clave_unica($valor, 'Cariaco,Carupano,Casanay,Cumana,Guiria');
                break;
            case "Tachira":

                misFunciones::palabras_clave_unica($valor, 'Capacho,Coloncito,El PiÑal,La Fria,La Grita,Michelena,Palmira (tachira),Rubio,San Antonio Del Tachira,San Cristobal (tachira),San Juan De Colon,Santa Ana (tachira),Tariba,UreÑa');
                break;
            case "Trujillo":

                misFunciones::palabras_clave_unica($valor, 'Bocono,La Hoyada,Monay,Sabana De Mendoza,Trujillo,Valera');
                break;
            case "Yaracuy":

                misFunciones::palabras_clave_unica($valor, 'Chivacoa,Nirgua,San Felipe,Yaritagua');
                break;
            case "Zulia":

                misFunciones::palabras_clave_unica($valor, 'Bachaquero,Cabimas,Caja Seca,Ciudad Ojeda,Encontrados,Machiques,Maracaibo,Mene Grande,San Rafael De Mojan (zulia),Santa Barbara Del Zulia,Santa Rita (zulia),Villa Del Rosario');
                break;
        }
    }
    public static function validarEstado($valor)
    {
        misFunciones::palabras_clave_unica($valor, 'Amazonas,Anzoategui,Apure,Aragua,Barinas,Bolivar,Carabobo,Cojedes,Delta Amacuro,Distrito Capital,Falcon,Guárico,Lara,Merida,Miranda,Monagas,Nueva Esparta,Portuguesa,Sucre,Tachira,Trujillo,Yaracuy,Zulia');
    }
    public static function validarBanco($valor)
    {
        misFunciones::palabras_clave_unica($valor, '0156,0196,0172,0171,0166,0175,0128,0164,0102,0114,0149,0163,0176,0115,0003,0173,0105,0191,0116,0138,0108,0104,0168,0134,0177,0146,0174,0190,0121,0157,0151,0601,0169,0137');
    }
    public static function convertirBancoaString($valor)
    {
        switch ($valor) {
            case "0156":
                $valor = "100%BANCO";
                break;
            case "0196":
                $valor = "ABN AMRO BANK";
                break;
            case "0172":
                $valor = "BANCAMIGA BANCO MICROFINANCIERO";
                break;
            case "0171":
                $valor = "BANCO ACTIVO BANCO COMERCIAL";
                break;
            case "0166":
                $valor = "BANCO AGRICOLA";
                break;
            case "0175":
                $valor = "BANCO BICENTENARIO";
                break;
            case "0128":
                $valor = "BANCO CARONI  BANCO UNIVERSAL";
                break;
            case "0164":
                $valor = "BANCO DE DESARROLLO DEL MICROEMPRESARIO";
                break;
            case "0102":
                $valor = "BANCO DE VENEZUELA";
                break;
            case "0114":
                $valor = "BANCO DEL CARIBE";
                break;
            case "0149":
                $valor = "BANCO DEL PUEBLO SOBERANO";
                break;
            case "0163":
                $valor = "BANCO DEL TESORO";
                break;
            case "0176":
                $valor = "BANCO ESPIRITO SANTO";
                break;
            case "0115":
                $valor = "BANCO EXTERIOR";
                break;
            case "0003":
                $valor = "BANCO INDUSTRIAL DE VENEZUELA";
                break;
            case "0173":
                $valor = "BANCO INTERNACIONAL DE DESARROLLO";
                break;
            case "0105":
                $valor = "BANCO MERCANTIL ";
                break;
            case "0191":
                $valor = "BANCO NACIONAL DE CREDITO";
                break;
            case "0116":
                $valor = "BANCO OCCIDENTAL DE DESCUENTO";
                break;
            case "0138":
                $valor = "BANCO PLAZA";
                break;
            case "0108":
                $valor = "BANCO PROVINCIAL BBVA";
                break;
            case "0104":
                $valor = "BANCO VENEZOLANO DE CREDITO ";
                break;
            case "0168":
                $valor = "BANCRECER BANCO DE DESARROLLO";
                break;
            case "0134":
                $valor = "BANESCO BANCO UNIVERSAL";
                break;
            case "0177":
                $valor = "BANFANB";
                break;
            case "0146":
                $valor = "BANGENTE";
                break;
            case "0174":
                $valor = "BANPLUS BANCO COMERCIAL";
                break;
            case "0190":
                $valor = "CITIBANK";
                break;
            case "0121":
                $valor = "CORP BANCA";
                break;
            case "0157":
                $valor = "DELSUR BANCO UNIVERSAL";
                break;
            case "0151":
                $valor = "FONDO COMUN";
                break;
            case "0601":
                $valor = "INSTITUTO MUNICIPAL DE CRÉDITO POPULAR";
                break;
            case "0169":
                $valor = "MIBANCO BANCO DE DESARROLLO";
                break;
            case "0137":
                $valor = "SOFITASA";
                break;
        }
        return $valor;
    }
    public static function validarCedula($valor)
    {

        $valor = explode('-', $valor);
        if ($valor[0] !== 'J') {
            $contante = 2;
        } else {
            $contante = 3;
        }
        if (count($valor) !== $contante) {
            misFunciones::$aprobado = false;
        } else {
            misFunciones::palabras_clave_unica($valor[0], 'V,J,E');
            misFunciones::min_length($valor[1], 6);
            misFunciones::max_length($valor[1], 12);
            misFunciones::validarBase64($valor[1], 'numerico');
            if ($valor[0] === 'J') {
                misFunciones::min_length($valor[2], 1);
                misFunciones::max_length($valor[2], 1);
                misFunciones::validarBase64($valor[2], 'numerico');
            }
        }
    }
    public static function telefonoMovilVenezolano($valor)
    {

        $valor = explode('-', $valor);
        if (count($valor) !== 2) {
            misFunciones::$aprobado = false;
        } else {
            misFunciones::palabras_clave_unica($valor[0], '0426,0416,0424,0414,0412');
            misFunciones::min_length($valor[1], 7);
            misFunciones::max_length($valor[1], 7);
            misFunciones::validarBase64($valor[1], 'numerico');
        }
    }
    public static function telefonoGeneralVenezolano($valor)
    {

        $valor = explode('-', $valor);
        if (count($valor) !== 2) {
            misFunciones::$aprobado = false;
        } else {
            misFunciones::palabras_clave_unica($valor[0], '0426,0416,0424,0414,0412,0248,0281,0282,0283,0240,0244,0273,0284,0285,0286,0288,0289,0241,0242,0243,0245,0249,0258,0259,0268,0269,0279,0235,0238,0246,0247,0252,0274,0234,0239,0287,0291,0292,0295,0255,0256,0257,0293,0294,0276,0277,0278,0272,0212,0251,0253,0254,0261,0262,0263,0264,0265,0266,0267,0271,0275');
            misFunciones::min_length($valor[1], 7);
            misFunciones::max_length($valor[1], 7);
            misFunciones::validarBase64($valor[1], 'numerico');
        }
    }

    public static function telefonoCasaVenezolano($valor)
    {

        $valor = explode('-', $valor);
        if (count($valor) !== 2) {
            misFunciones::$aprobado = false;
        } else {
            misFunciones::palabras_clave_unica($valor[0], '0248,0281,0282,0283,0240,0244,0273,0284,0285,0286,0288,0289,0241,0242,0243,0245,0249,0258,0259,0268,0269,0279,0235,0238,0246,0247,0252,0274,0234,0239,0287,0291,0292,0295,0255,0256,0257,0293,0294,0276,0277,0278,0272,0212,0251,0253,0254,0261,0262,0263,0264,0265,0266,0267,0271,0275');
            misFunciones::min_length($valor[1], 7);
            misFunciones::max_length($valor[1], 7);
            misFunciones::validarBase64($valor[1], 'numerico');
        }
    }

    /**
     * Esta función verifica si el valor proporcionado es un booleano válido.
     * No devuelve nada, pero establece misFunciones::$aprobado en false si el valor no es un booleano válido.
     */
    public static function booleano($valor)
    {
        if ($valor !== 0 && $valor !== 1 && $valor !== "0" && $valor !== "1" && $valor !== true && $valor !== false) {
            return false;
        } else {
            return true;
        }
    }

/**
 * Esta función comprueba si un valor es un color hexadecimal válido.
 * Devuelve true si el valor es un color hexadecimal válido, de lo contrario devuelve false.
 */
    public static function comprobarHex($valor)
    {
        if ($valor[0] === '#' && strlen($valor) === 7) {
            $valor2 = explode('#', $valor);

            if (ctype_xdigit($valor2[1])) {
                return true;
            } else {
                return false;
            }
        }
    }

/**
 * Esta función corrige el carácter 'ñ' mal codificado.
 * Devuelve el valor con el carácter 'ñ' corregido.
 */
    public static function laEnie($valor)
    {
        $valor = str_replace("Ã±", "ñ", $valor);
        return ($valor);
    }

/**
 * Esta función convierte caracteres mal codificados en UTF-8.
 * Devuelve el valor con los caracteres mal codificados corregidos.
 */
    public static function conversorUFT($valor)
    {
        $pos = strpos($valor, "Ã“");

        if ($pos !== false) {
            $valor = str_replace("Ã“", "Ó", $valor);
        }
        return ($valor);
    }

/**
 * Esta función quita los guiones bajos y los reemplaza por espacios en el valor proporcionado.
 * Devuelve el valor con los guiones bajos reemplazados por espacios.
 */
    public static function quitarPisos($valor)
    {
        $valor = str_replace("_", " ", $valor);
        return ($valor);
    }

/**
 * Esta función pone guiones bajos en lugar de espacios en el valor proporcionado.
 * Devuelve el valor con los espacios reemplazados por guiones bajos.
 */
    public static function ponerPisos($valor)
    {
        $valor = str_replace(" ", "_", $valor);
        return ($valor);
    }

    public static function permitir_vacio($valor)
    {

        if (isset($valor[0])) {
            if ($valor === "") {
                return false;
            } else {
                return true;
            }
        }
    }
    /**
     * Verifica si una dirección de correo electrónico tiene una sintaxis válida.
     * Devuelve true si la dirección de correo electrónico es válida, de lo contrario devuelve false.
     */
    public static function validarEmail($direccion)
    {
        $patron = '/^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$/';
        return (bool) preg_match($patron, $direccion);
    }

/**
 * Verifica si la longitud de un valor es mayor o igual que un valor mínimo.
 * Devuelve true si la longitud del valor es mayor o igual que el valor mínimo, de lo contrario devuelve false.
 */
    public static function longitudMinima($valor, $minLength)
    {
        return strlen($valor) >= $minLength;
    }

/**
 * Verifica si la longitud de un valor es menor o igual que un valor máximo.
 * Devuelve true si la longitud del valor es menor o igual que el valor máximo, de lo contrario devuelve false.
 */
    public static function longitudMaxima($valor, $maxLength)
    {
        return strlen($valor) <= $maxLength;
    }

/**
 * Verifica si una palabra clave es única dentro de un conjunto.
 * Devuelve true si la palabra clave es única, de lo contrario devuelve false.
 */
    public static function palabraClaveUnica($valor, $palabrasClave)
    {
        $palabras = explode(',', $palabrasClave);
        return count(array_unique($palabras)) === count($palabras);
    }

/**
 * Convierte una contraseña en una cadena de 32 caracteres en formato MD5.
 * Devuelve la contraseña cifrada en formato MD5.
 */
    public static function cifrarMD5($valor)
    {
        return md5($valor . '!#and#!');
    }

/**
 * Verifica si una cadena de texto contiene varias palabras clave especificadas.
 * Devuelve true si todas las palabras clave están presentes en la cadena de texto, de lo contrario devuelve false.
 */
    public static function contienePalabrasClave($valor, $palabrasClave)
    {
        $palabras = explode(',', $palabrasClave);
        foreach ($palabras as $palabra) {
            if (strpos($valor, $palabra) === false) {
                return false;
            }
        }
        return true;
    }

/**
 * Verifica si dos valores son iguales.
 * Devuelve true si los valores son iguales, de lo contrario devuelve false.
 */
    public static function sonIguales($valor, $valor2)
    {
        return $valor === $valor2;
    }

/**
 * Valida una contraseña según criterios de seguridad establecidos.
 * Devuelve true si la contraseña cumple con los criterios de seguridad, de lo contrario devuelve false.
 */
    public static function validarPassword($valor)
    {
        // Código de la función...
    }

/**
 * Valida un valor MD5 comprobando su longitud y tipo de caracteres.
 * Esta función no devuelve nada directamente, pero utiliza otras funciones de validación internamente.
 */
    public static function validarMD5($valor)
    {
        return self::longitudMinima($valor, 32) && self::longitudMaxima($valor, 32);
    }

    /**
     * Verifica si una cadena base64 es válida.
     * Devuelve true si la cadena base64 es válida, de lo contrario devuelve false.
     */
    public static function validarBase64($s)
    {
        $s = explode('base64,', $s);
        $s = isset($s[1]) ? $s[1] : $s[0];

        if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $s)) {
            return false;
        }

        $decoded = base64_decode($s, true);
        if (false === $decoded) {
            return false;
        }

        if (base64_encode($decoded) != $s) {
            return false;
        }

        return true;
    }

/**
 * Normaliza una cadena de texto eliminando acentos y convirtiéndola a minúsculas.
 * Devuelve la cadena de texto normalizada.
 */
    public static function normalizarTexto($cadena)
    {
        $originales = 'ÁÉÍÓÚáéíóúÜüÑñ';
        $modificadas = 'AEIOUaeiouUuNn';
        $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
        return strtolower($cadena);
    }

/**
 * Convierte un array en una cadena de texto separada por un delimitador.
 * Devuelve la cadena de texto resultante.
 */
    public static function arrayToString($valor, $property = false, $separator = ',')
    {
        if (is_string($valor)) {
            return $valor;
        }

        $string = "";
        $count = count($valor);
        if ($count > 0) {
            foreach ($valor as $key => $vi) {
                if ($property !== false && is_object($vi)) {
                    $vi = object_get($vi, $property);
                }

                $string .= $vi;
                if ($key < $count - 1) {
                    $string .= $separator;
                }
            }
        }
        return $string;
    }

/**
 * Valida un valor según un tipo de valor especificado.
 * No devuelve nada, pero establece una variable de clase $aprobado en caso de error.
 */
    public static function validarTipoValor($valor, $tipovalor)
    {
        $valor = self::normalizarTexto($valor);

        $permitidos = [
            'alfa' => 'qwertyuiopasdfghjklzxcvbñnm',
            'alfanumerico' => 'qwertyuiopasdfghjkñlzxcvbnm1234567890',
            'alfanumericoparentesis' => 'qwertyuiopasdfghjklzxcvbñnm1234567890()',
            'alfanumericosimbolos' => 'qwertyuiopasdfghjklzxcñvbnm1234567890!#&/()"=-%_¡¿|°;.,',
            'numerico' => '',
        ];

        if (isset($permitidos[$tipovalor])) {
            $permitidos = $permitidos[$tipovalor];
            if (empty($valor) || !strpbrk($valor, $permitidos)) {
                self::$aprobado = false;
            }
        } elseif ($tipovalor === 'decimal') {
            $valor = explode('.', $valor);
            if (count($valor) > 2) {
                self::$aprobado = false;
            } else {
                foreach ($valor as $parte) {
                    if (!is_numeric($parte)) {
                        self::$aprobado = false;
                        break;
                    }
                }
            }
        }
    }

    public static function rango_numero($valor, $min, $max)
    {

        if (!is_numeric($valor) || intval($valor) < intval($min) || intval($valor) > intval($max)) {
            misFunciones::$aprobado = false;
        }
    }
    /**
     * Verifica si un valor numérico está dentro de un rango especificado.
     * No devuelve nada, pero establece la variable de clase $aprobado en caso de error.
     */
    public static function verificarRangoNumerico($valor, $min, $max)
    {
        if (!is_numeric($valor) || intval($valor) < intval($min) || intval($valor) > intval($max)) {
            self::$aprobado = false;
        }
    }

}
