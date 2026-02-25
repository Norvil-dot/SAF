<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        $plan = $request->input('plan', 1);

        $rules = [
            'nombre' => 'required|string',
            'email' => 'required|email',
            'plan' => 'required|integer',
        ];

        if (!Auth::check()) {
            $rules['email'] .= '|unique:users,email';
            $rules['password'] = 'required|min:6';
        }

        if ($plan != 0) {
            $rules['stripeToken'] = 'required';
        }

        $request->validate($rules, [
            'email.unique' => 'El email ya está registrado. Por favor, inicia sesión o usa otro.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.'
        ]);

        // Simulación: Configurar Stripe (en un entorno real usarías env('STRIPE_SECRET'))
        // Desactivamos verificación SSL localmente para evitar errores cURL 60 en XAMPP/Windows
        \Stripe\Stripe::$verifySslCerts = false;
        Stripe::setApiKey(env('STRIPE_SECRET', 'sk_test_dummy'));

        try {
            // Determinar monto según el plan seleccionado
            $plan = $request->input('plan', 1);
            $amount = 21240; // Plan 1 = 212.40 (en centavos para Stripe son 21240)
            if ($plan == 2) {
                $amount = 70564; // Plan 2 = 705.64 => 70564 centavos
            } elseif ($plan == 3) {
                $amount = 101244; // Plan 3 = 1012.44 => 101244 centavos
            }

            // ======= SIMULACIÓN =======
            // Dado que la llave de prueba "sk_test_..." está expirada, saltamos la validación
            // real con la API de Stripe y simulamos que el cobro se hizo correctamente 
            // para que no detenga el flujo.
            
            /*
            // Crear el cliente en Stripe
            $customer = Customer::create(array(
                'email' => $request->email,
                'source'  => $request->stripeToken
            ));

            // Realizar el cargo
            $charge = Charge::create(array(
                'customer' => $customer->id,
                'amount'   => $amount, // En centavos
                'currency' => 'pen', // Soles peruanos
                'description' => 'Suscripción LocalesYa - Plan ' . $plan
            ));
            */

            // Crear el Tenant para el usuario nuevo o actualizar el existente
            $planName = 'Básico';
            if ($plan == 0) $planName = 'Gratuito';
            elseif ($plan == 2) $planName = 'Pro';
            elseif ($plan == 3) $planName = 'Elite';

            if (Auth::check()) {
                $user = Auth::user();
                if ($user->tenant) {
                    $user->tenant->update(['plan' => $planName]);
                } else {
                    $tenant = \App\Models\Tenant::create([
                        'nombre' => 'Inmobiliaria de ' . $user->name,
                        'plan' => $planName,
                    ]);
                    $user->tenant_id = $tenant->id;
                    $user->save();
                }
            } else {
                // ======= REGISTRO DE USUARIO =======
                $user = User::create([
                    'name' => $request->nombre,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);

                // Auto log-in
                Auth::login($user);

                $tenant = \App\Models\Tenant::create([
                    'nombre' => 'Inmobiliaria de ' . $user->name,
                    'plan' => $planName,
                ]);
                $user->tenant_id = $tenant->id;
                $user->save();
            }

            if ($plan == 0) {
                return redirect()->route('dashboard')->with('success', '¡Cuenta gratuita creada exitosamente! Ya puedes publicar tu primera propiedad.');
            }

            // Redireccionar a la vista de éxito de 3 segundos para planes de pago
            return redirect('/checkout/success');

        } catch (\Exception $e) {
            // Manejo de errores de tarjeta o Stripe
            return back()->withErrors(['stripe_error' => $e->getMessage()]);
        }
    }
}
