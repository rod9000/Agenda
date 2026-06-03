<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmação de Presença</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    brand: { 50: '#fdf4f0', 100: '#fae6dc', 200: '#f5ccb9', 300: '#edab8d', 400: '#e58560', 500: '#dd6540', 600: '#c94f2e', 700: '#a83d24', 800: '#8a3421', 900: '#712e1f' },
                },
                fontFamily: { sans: ['Inter', 'sans-serif'] },
                animation: { 'bounce-in': 'bounceIn 0.5s ease-out', 'fade-in': 'fadeIn 0.3s ease-out' },
                keyframes: {
                    bounceIn: { '0%': { opacity: '0', transform: 'scale(0.3)' }, '50%': { transform: 'scale(1.05)' }, '70%': { transform: 'scale(0.9)' }, '100%': { opacity: '1', transform: 'scale(1)' } },
                    fadeIn: { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
                }
            }
        }
    }
    </script>
</head>
<body class="bg-gradient-to-br from-stone-50 via-orange-50 to-stone-100 min-h-screen font-sans text-stone-800 flex items-center justify-center p-4">

    <div class="max-w-md w-full animate-fade-in">
        @if ($success)
            <div class="bg-white rounded-3xl shadow-2xl p-8 text-center animate-bounce-in">
                <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-stone-800 mb-2">Presença Confirmada! ✔️</h2>
                <p class="text-stone-500 mb-6">{{ $message }}</p>

                @if(isset($appointment))
                <div class="bg-stone-50 rounded-2xl p-4 text-left space-y-2 text-sm mb-6">
                    <div class="flex justify-between">
                        <span class="text-stone-400">Cliente:</span>
                        <span class="font-medium text-stone-800">{{ $appointment->customer->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-stone-400">Data:</span>
                        <span class="font-medium text-stone-800">{{ $appointment->start->format('d/m/Y H:i') }}</span>
                    </div>
                    @if($appointment->services->count() > 0)
                    <div class="flex justify-between">
                        <span class="text-stone-400">Serviços:</span>
                        <span class="font-medium text-stone-800 text-right">{{ $appointment->services->pluck('name')->implode(', ') }}</span>
                    </div>
                    @endif
                </div>
                @endif

                <a href="{{ url('/agendar') }}" class="inline-block w-full bg-gradient-to-r from-brand-400 to-brand-600 text-white font-semibold rounded-xl py-3 hover:from-brand-500 hover:to-brand-700 transition-all duration-200 shadow-md text-center">
                    Fazer Novo Agendamento
                </a>
            </div>
        @else
            <div class="bg-white rounded-3xl shadow-2xl p-8 text-center animate-bounce-in">
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-stone-800 mb-2">Link Inválido</h2>
                <p class="text-stone-500 mb-6">{{ $message }}</p>
                <a href="{{ url('/agendar') }}" class="inline-block w-full bg-gradient-to-r from-brand-400 to-brand-600 text-white font-semibold rounded-xl py-3 hover:from-brand-500 hover:to-brand-700 transition-all duration-200 shadow-md text-center">
                    Voltar ao Agendamento
                </a>
            </div>
        @endif
    </div>

</body>
</html>
