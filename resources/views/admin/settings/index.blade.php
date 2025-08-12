<x-admin-layout>
    <div class="py-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Admin Settings</h1>
                <p class="text-sm text-gray-500">Configure site branding and general options.</p>
            </div>
        </div>

        <div class="mt-2 font-mono text-[11px] tracking-widest text-gray-400 select-none" aria-hidden="true">- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</div>

        @if (session('status'))
            <div class="mt-3 border border-green-200 bg-green-50 text-green-800 px-3 py-2 rounded-md text-sm">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="mt-3 border border-red-200 bg-red-50 text-red-800 px-3 py-2 rounded-md text-sm">
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="mt-4" action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <div class="lg:col-span-2 bg-white border rounded-lg p-4">
                    <h2 class="font-semibold text-gray-900">General</h2>
                    <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-700">Site Name</label>
                            <input type="text" name="site_name" value="{{ old('site_name', $settings->site_name ?? '') }}" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="wazaelimu" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700">Site URL</label>
                            <input type="url" name="site_url" value="{{ old('site_url', $settings->site_url ?? config('app.url')) }}" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="https://example.com" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700">Contact Email</label>
                            <input type="email" name="contact_email" value="{{ old('contact_email', $settings->contact_email ?? '') }}" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="support@example.com" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700">Footer Text</label>
                            <input type="text" name="footer_text" value="{{ old('footer_text', $settings->footer_text ?? '© ' . date('Y') . ' wazaelimu') }}" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>
                    </div>
                </div>

                <div class="bg-white border rounded-lg p-4">
                    <h2 class="font-semibold text-gray-900">Branding</h2>
                    <div class="mt-3">
                        <label class="block text-sm text-gray-700">Site Icon (PNG/SVG)</label>
                        <input type="file" name="site_icon" id="site_icon_input" accept="image/*" class="mt-1 w-full border rounded-lg px-3 py-2" />
                        <div class="mt-2 flex items-center gap-3">
                            <div class="w-12 h-12 border rounded overflow-hidden bg-gray-50 flex items-center justify-center">
                                @php $icon = isset($settings) && $settings->site_icon_path ? Storage::url($settings->site_icon_path) : null; @endphp
                                @if($icon)
                                    <img id="site_icon_preview" src="{{ $icon }}" alt="icon" class="w-full h-full object-cover" />
                                @else
                                    <span class="text-xs text-gray-400">No icon</span>
                                @endif
                            </div>
                            <span class="text-xs text-gray-500">Recommended: square, 256x256+</span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm text-gray-700">Favicon (ICO/PNG)</label>
                        <input type="file" name="favicon" id="favicon_input" accept="image/*" class="mt-1 w-full border rounded-lg px-3 py-2" />
                        <div class="mt-2 flex items-center gap-3">
                            <div class="w-8 h-8 border rounded overflow-hidden bg-gray-50 flex items-center justify-center">
                                @php $fav = isset($settings) && $settings->favicon_path ? Storage::url($settings->favicon_path) : null; @endphp
                                @if($fav)
                                    <img id="favicon_preview" src="{{ $fav }}" alt="favicon" class="w-full h-full object-cover" />
                                @else
                                    <span class="text-[10px] text-gray-400">No favicon</span>
                                @endif
                            </div>
                            <span class="text-xs text-gray-500">Recommended: 32x32 / 48x48</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mail Settings -->
            <div class="mt-4 grid grid-cols-1 lg:grid-cols-3 gap-4">
                <div class="lg:col-span-3 bg-white border rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <h2 class="font-semibold text-gray-900">Mail Settings (SMTP)</h2>
                        <div class="text-xs text-gray-500">Used for notifications and password reset emails.</div>
                    </div>
                    <div class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm text-gray-700">Host</label>
                            <input type="text" name="mail_host" value="{{ old('mail_host', $settings->mail_host ?? '') }}" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="smtp.mailprovider.com" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700">Port</label>
                            <input type="number" name="mail_port" value="{{ old('mail_port', $settings->mail_port ?? '') }}" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="587" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700">Encryption</label>
                            <select name="mail_encryption" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                @php($enc = old('mail_encryption', $settings->mail_encryption ?? 'tls'))
                                <option value="none" {{ $enc ? '' : 'selected' }}>None</option>
                                <option value="tls" {{ $enc==='tls'?'selected':'' }}>TLS</option>
                                <option value="ssl" {{ $enc==='ssl'?'selected':'' }}>SSL</option>
                                <option value="starttls" {{ $enc==='starttls'?'selected':'' }}>STARTTLS</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700">Username</label>
                            <input type="text" name="mail_username" value="{{ old('mail_username', $settings->mail_username ?? '') }}" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700">Password</label>
                            <input type="password" name="mail_password" value="" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="••••••••" />
                            <p class="text-[11px] text-gray-500 mt-1">Leave blank to keep existing.</p>
                        </div>
                        <div></div>
                        <div>
                            <label class="block text-sm text-gray-700">From Address</label>
                            <input type="email" name="mail_from_address" value="{{ old('mail_from_address', $settings->mail_from_address ?? '') }}" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="no-reply@domain.com" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700">From Name</label>
                            <input type="text" name="mail_from_name" value="{{ old('mail_from_name', $settings->mail_from_name ?? ($settings->site_name ?? 'wazaelimu')) }}" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>
                        <div class="flex items-end">
                            <div>
                                <label class="block text-sm text-gray-700">Send Test To</label>
                                <div class="flex gap-2 mt-1">
                                    <input type="email" id="test_mail_to" class="flex-1 border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="you@example.com" />
                                    <button type="button" id="btnSendTestMail" class="px-3 py-2 rounded-md bg-gray-900 text-white hover:bg-black text-sm">Send Test</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5 grid grid-cols-1 lg:grid-cols-3 gap-4 items-start">
                <div class="lg:col-span-2"></div>
                <div class="bg-white border rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-gray-900">Backup</h3>
                        <a href="{{ route('settings.backup') }}" class="inline-flex items-center px-3 py-1.5 rounded-md bg-gray-900 text-white hover:bg-black text-sm">Download ZIP</a>
                    </div>
                    <p class="text-xs text-gray-600 mt-1">Downloads a ZIP archive of all posted documents (Notes and Materials).</p>
                </div>
            </div>

            <div class="mt-4 flex items-center justify-end gap-3">
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">Save Settings</button>
            </div>
        </form>
    </div>
    <div id="settingsPageOverlay" class="fixed inset-0 z-[9998] grid place-items-center bg-white/70 backdrop-blur-sm hidden">
        <div class="text-gray-700">
            <div class="lds-ripple"><div></div><div></div></div>
            <div class="text-xs text-center mt-2">Loading Settings...</div>
        </div>
    </div>

    <script>
    (function(){
        const iconInput = document.getElementById('site_icon_input');
        const iconPreview = document.getElementById('site_icon_preview');
        const favInput = document.getElementById('favicon_input');
        const favPreview = document.getElementById('favicon_preview');
        const overlay = document.getElementById('settingsPageOverlay');
        const btnTest = document.getElementById('btnSendTestMail');
        const testTo = document.getElementById('test_mail_to');
        function applyPreview(input, img){
            if (!input || !img) return;
            input.addEventListener('change', function(){
                const f = this.files && this.files[0];
                if (!f) return;
                const url = URL.createObjectURL(f);
                img.src = url;
            });
        }
        applyPreview(iconInput, iconPreview);
        applyPreview(favInput, favPreview);

        // Show overlay briefly until page is ready
        function showOverlay(){ overlay?.classList.remove('hidden'); }
        function hideOverlay(){ overlay?.classList.add('hidden'); }
        showOverlay();
        window.addEventListener('load', function(){ setTimeout(hideOverlay, 250); });

        // Test mail
        if (btnTest && testTo) {
            btnTest.addEventListener('click', function(){
                const email = testTo.value.trim();
                if (!email) return alert('Enter a test email');
                showOverlay();
                fetch('{{ route('settings.test_mail') }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'text/html' },
                    body: new URLSearchParams({ to: email })
                }).then(()=>{ hideOverlay(); window.location.reload(); }).catch(()=>{ hideOverlay(); alert('Failed to send test email'); });
            });
        }
    })();
    </script>
</x-admin-layout>
