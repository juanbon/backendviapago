<?php

namespace App\Http\Controllers;

use App\Models\WebView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class WebViewRenderController extends Controller
{
    /**
     * Renderiza una WebView en formato HTML o PDF.
     *
     * @param int $typeId
     * @param int $subtypeId
     * @return \Illuminate\Http\Response
     */
    public function render($typeId, $subtypeId = 1)
    {
        $webview = WebView::where('idType', $typeId)
            ->where('idSubtype', $subtypeId)
            ->where('validSince', '<=', now())
            ->orderBy('validSince', 'desc')
            ->with(['webviewType', 'webviewSubtype'])
            ->first();

        if (!$webview) {
            return response('WebView no encontrado', 404);
        }

        if ($webview->extension === 'HTML') {
            return response()->make("<!DOCTYPE html>
                <html>
                    <head>
                        <title>{$webview->webviewType->description} - {$webview->webviewSubtype->description}</title>
                        <style>#pdf-viewer { width: 100%; height: 100vh; }</style>
                    </head>
                    <body>{$webview->text}</body>
                </html>", 200, ['Content-Type' => 'text/html']);
        }

        if ($webview->extension === 'PDF') {
            return response(base64_decode($webview->file), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="archivo.pdf"',
            ]);
        }

        return response('Formato desconocido', 415);
    }
}
