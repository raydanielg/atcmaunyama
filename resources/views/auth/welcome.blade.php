<x-guest-layout>
    <div class="max-w-xl mx-auto text-center py-10">
        <h1 class="text-2xl font-semibold">Karibu WazaElimu!</h1>
        <p class="text-gray-600 mt-2">Umesajiliwa/Kuingia kwa mafanikio. Tutakuunganisha na App ya simu sasa.</p>

        <div class="mt-6 p-4 bg-white/60 rounded border">
            <p class="text-gray-700">Kusubiri... <span id="seconds" class="font-semibold">5</span>s</p>
            <p class="mt-1 text-sm text-gray-500">Kama app haifunguki, tutakupeleka Play Store.</p>
        </div>

        <div class="mt-6">
            <a href="{{ $playStoreUrl }}" class="text-indigo-600 underline" id="fallbackLink">Nenda Play Store sasa</a>
        </div>
    </div>

    <script>
        (function() {
            const secondsEl = document.getElementById('seconds');
            const fallbackLink = document.getElementById('fallbackLink');
            const playStoreUrl = @json($playStoreUrl);
            const pkg = @json($androidPackage);
            const scheme = @json($deeplinkScheme || 'app');
            let seconds = {{ $redirectSeconds ?? 5 }};

            function isAndroid() {
                return /Android/i.test(navigator.userAgent);
            }

            function tryOpenApp() {
                if (isAndroid()) {
                    // Try intent first (modern Android)
                    const intentUrl = `intent://open#Intent;scheme=${scheme};package=${pkg};end`;
                    const t = setTimeout(function() {
                        window.location.href = playStoreUrl;
                    }, 1500);
                    window.location.href = intentUrl;
                } else {
                    // Non-Android: just show fallback
                    window.location.href = playStoreUrl;
                }
            }

            const timer = setInterval(() => {
                seconds -= 1;
                secondsEl.textContent = seconds;
                if (seconds <= 0) {
                    clearInterval(timer);
                    tryOpenApp();
                }
            }, 1000);
        })();
    </script>
</x-guest-layout>
