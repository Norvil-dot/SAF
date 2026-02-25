<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Local;
use App\Models\Mensaje;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class MensajeController extends Controller
{
    public function store(Request $request, Local $local)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'mensaje' => 'required|string'
        ]);

        Mensaje::create([
            'local_id' => $local->id,
            'tenant_id' => $local->tenant_id,
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'mensaje' => $request->mensaje
        ]);

        // ==========================================
        // INTEGRACIÓN CON TWILIO WHATSAPP
        // ==========================================
        try {
            $twilioSid    = env('TWILIO_SID');
            $twilioToken  = env('TWILIO_TOKEN');
            $twilioNumber = env('TWILIO_WHATSAPP_NUMBER'); // ej: "whatsapp:+14155238886"

            // Formatear el número de destino
            // Asumiendo código peruano (+51) por defecto si se ingresa sin '+'
            $numeroDestino = $request->telefono;
            if (!str_starts_with($numeroDestino, '+')) {
                $numeroDestino = '+51' . $numeroDestino;
            }

            $precio = $local->precio_mensual ? 'S/ ' . number_format($local->precio_mensual, 2) : 'A consultar';
            $operacion = ucfirst($local->operacion ?? 'venta/alquiler');
            $tipoProp = ucfirst($local->tipo_propiedad ?? 'Inmueble');
            $ubicacion = clone $local;
            $distrito = $local->distrito ?? 'No especificado';
            $titulo = $local->titulo ?? 'Inmueble Destacado';

            $textoWhatsApp = "Hola *" . $request->nombre . "* 👋\n\n"
                           . "Hemos recibido tu solicitud de información para la siguiente propiedad:\n\n"
                           . "🏢 *Detalles del Inmueble:*\n"
                           . "▪️ *Categoría:* " . $tipoProp . " en " . $operacion . "\n"
                           . "▪️ *Ubicación:* " . $distrito . "\n"
                           . "▪️ *Precio referencial:* " . $precio . "\n\n"
                           . "👤 *Tus datos de contacto registrados:*\n"
                           . "▪️ *Nombre:* " . $request->nombre . "\n"
                           . "▪️ *Email:* " . $request->email . "\n"
                           . "▪️ *Teléfono:* " . $request->telefono . "\n\n"
                           . "Para iniciar el proceso de " . strtolower($operacion) . ", por favor tener listos los siguientes documentos:\n"
                           . "👉 *DNI* (Ambas caras)\n"
                           . "👉 *Recibo de luz o agua* (Reciente)\n\n"
                           . "En breve uno de nuestros agentes se pondrá en contacto contigo para el acuerdo de pago y la firma.\n\n"
                           . "¡Gracias por confiar en PropTech PEDIDODASYA!";

            // Solo intentar enviar si las credenciales están configuradas en el .env
            if ($twilioSid && $twilioToken && $twilioNumber) {
                $twilio = new Client($twilioSid, $twilioToken);
                $twilio->messages->create(
                    "whatsapp:" . $numeroDestino, // Destinatario
                    [
                        "from" => $twilioNumber, // Tu número Sandbox
                        "body" => $textoWhatsApp
                    ]
                );
            }
        } catch (\Exception $e) {
            // Si hay error (ej: el usuario no se ha unido al Sandbox), guardamos el error en el log pero no rompemos la app
            Log::error('Error al enviar WhatsApp: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', '¡Mensaje enviado y notificado por WhatsApp! El agente se pondrá en contacto pronto.');
    }

    public function toggleLeido(Mensaje $mensaje)
    {
        if ($mensaje->tenant_id != \Illuminate\Support\Facades\Auth::user()->tenant_id) abort(403);
        $mensaje->update(['leido' => !$mensaje->leido]);
        return redirect()->back();
    }

    public function destroy(Mensaje $mensaje)
    {
        if ($mensaje->tenant_id != \Illuminate\Support\Facades\Auth::user()->tenant_id) abort(403);
        $mensaje->delete();
        return redirect()->back();
    }
}
