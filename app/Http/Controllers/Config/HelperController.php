<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\ImageManagerStatic as Image;

class HelperController extends Controller
{
    /**
     * Controller criado para me auxiliar em funções que podem ser
     * utilizadas em outros lugares.
     * Criada por mim, Lucas Mota.
     */

    public function moveImg(string $moveTo, UploadedFile $imgToMove): JsonResponse
    {
        try {
            // Define variaveis iniciais
            $originalName = $imgToMove->getClientOriginalName();
            $newName = $originalName . date('d-m-YH-i-s');
            $newName = str_replace(".", "", $newName);
            // Constrói a imagem
            $image = Image::make($imgToMove);
            // Verifica MIME da imagem
            switch ($image->mime()) {
                case 'image/jpeg':
                    $image->extension = 'jpeg';
                    break;
                case 'image/png':
                    $image->extension = 'png';
                    break;
            }
            //Adiciona a nova extensão
            $newName .= "." . $image->extension;
            // Salva a imagem
            if ($image->save("$moveTo/$newName")) {
                // retorna json
                return response()->json(
                    [
                        'type' => 'success',
                        'msg' => 'Upload realizado com sucesso.',
                        'localImg' => "$newName"
                    ]
                );
            } else {
                // retorna json
                return response()->json(
                    [
                        'type' => 'error',
                        'msg' => 'Upload realizado não realizado.',
                    ]
                );
            }

        } catch (NotReadableException $e) {
            return response()->json(
                [
                    'type' => 'error',
                    'msg' => 'Upload realizado não realizado.',
                ]
            );
        }

    }

    public function formatDate(string $start_date = null, string $end_date = null): array {
        if (!is_null($start_date)) {
            // pegar o dia, mes e ano.
            $parts = explode('/', $start_date);

            // Formatar
            $dates['start_date'] = date('Y-m-d', strtotime(Carbon::createFromDate($parts[2], $parts[1], $parts[0])));
        }

        if (!is_null($end_date)) {
            // pegar o dia, mes e ano.
            $parts = explode('/', $end_date);

            // Formatar
            $dates['end_date'] = date('Y-m-d', strtotime(Carbon::createFromDate($parts[2], $parts[1], $parts[0])));
        }

        return $dates;
    }

    public function linkIsValid(string $link) {
        if (str_contains($link, ['https://', 'www.', 'http://'])) {
            return true;
        } else {
            return false;
        }
    }
}
