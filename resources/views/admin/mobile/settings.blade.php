<x-admin-layout>
    <div class="py-4">
        <div class="flex items-center justify-between">
            <div class="w-full">
                <h1 class="text-xl font-semibold text-gray-900">Mobile App Settings</h1>
                <p class="text-sm text-gray-500">Manage branding, splash content, app updates, notifications, Google callback, and premium features.</p>
                <div class="mt-2 font-mono text-[11px] tracking-widest text-gray-400 select-none whitespace-nowrap overflow-hidden" aria-hidden="true">- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</div>
            </div>
        </div>
        <div class="border-t border-dashed mt-2 mb-4"></div>

        @if (session('status'))
            <div class="mb-4"><x-alert type="success">{{ session('status') }}</x-alert></div>
        @endif
        @if ($errors->any())
            <div class="mb-4">
                <x-alert type="error">
                    <ul class="list-disc pl-4 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-alert>
            </div>
        @endif

        <form method="POST" action="{{ route('mobile.settings.update') }}" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            @csrf

            <!-- Branding Card -->
            <div class="bg-white border rounded-lg p-4">
                <h3 class="font-semibold text-gray-900">Branding</h3>
                <p class="text-sm text-gray-500">Upload your app icon and splash image.</p>
                <div class="mt-3 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">App Icon (PNG/ICO)</label>
                        <input type="file" name="app_icon" accept=".png,.ico" class="mt-1 w-full" onchange="previewImage(event, 'appIconPreview')" />
                        <div class="mt-2 flex items-center gap-3">
                            <img id="appIconPreview" src="{{ $settings->app_icon_path ?? '' }}" alt="App Icon" class="h-12 w-12 rounded border {{ ($settings->app_icon_path ?? null) ? '' : 'hidden' }}" />
                            <span class="text-xs text-gray-500">Preview updates instantly after selecting a file.</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Splash Image</label>
                        <input type="file" name="splash_image" accept="image/*" class="mt-1 w-full" onchange="previewImage(event, 'splashPreview')" />
                        <div class="mt-2">
                            <img id="splashPreview" src="{{ $settings->splash_image_path ?? '' }}" alt="Splash" class="h-20 rounded border {{ ($settings->splash_image_path ?? null) ? '' : 'hidden' }}" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Splash Headline</label>
                        <input type="text" name="splash_headline" value="{{ old('splash_headline', $settings->splash_headline ?? '') }}" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Splash Subtext</label>
                        <input type="text" name="splash_subtext" value="{{ old('splash_subtext', $settings->splash_subtext ?? '') }}" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                    </div>
                </div>
            </div>

            <!-- Updates & Notifications Card -->
            <div class="bg-white border rounded-lg p-4">
                <h3 class="font-semibold text-gray-900">Updates & Notifications</h3>
                <p class="text-sm text-gray-500">Control app update prompts and visibility of notifications.</p>
                <div class="mt-3 space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-medium text-gray-700">Require App Update</label>
                        <input type="checkbox" name="app_update_required" value="1" {{ old('app_update_required', $settings->app_update_required ?? false) ? 'checked' : '' }} />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Required Version</label>
                        <input type="text" name="app_update_version" value="{{ old('app_update_version', $settings->app_update_version ?? '') }}" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Update Notes</label>
                        <textarea name="app_update_notes" rows="4" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="What’s new or required changes?">{{ old('app_update_notes', $settings->app_update_notes ?? '') }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Grace Period (minutes)</label>
                            <input type="number" name="app_update_grace_minutes" min="0" step="1" value="{{ old('app_update_grace_minutes') }}" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g., 1440 for 24h" />
                            <p class="text-xs text-gray-500 mt-1">After this time, the app will force update.</p>
                        </div>
                        <div class="flex items-end">
                            <label class="inline-flex items-center gap-2 text-sm font-medium text-gray-700">
                                <input type="checkbox" name="notify_users_now" value="1" class="mr-2" /> Send update notification now
                            </label>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-medium text-gray-700">Show Notifications in App</label>
                        <input type="checkbox" name="show_notifications" value="1" {{ old('show_notifications', $settings->show_notifications ?? true) ? 'checked' : '' }} />
                    </div>
                </div>
            </div>

            <!-- OAuth & Premium Card -->
            <div class="bg-white border rounded-lg p-4">
                <h3 class="font-semibold text-gray-900">OAuth & Premium</h3>
                <p class="text-sm text-gray-500">Auto-generated Google callback URL and premium features.</p>
                <div class="mt-3 space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Google Callback URL</label>
                        <input type="text" readonly value="{{ $googleCallback }}" class="mt-1 w-full border rounded-lg px-3 py-2 bg-gray-50 text-gray-600" />
                        <p class="text-xs text-gray-500 mt-1">This is generated automatically from your APP_URL.</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-medium text-gray-700">Enable Premium</label>
                        <input type="checkbox" name="premium_enabled" value="1" {{ old('premium_enabled', $settings->premium_enabled ?? false) ? 'checked' : '' }} />
                    </div>
                    <input type="hidden" name="premium_provider" value="selcom" />
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Provider</label>
                            <input type="text" value="Selcom" disabled class="mt-1 w-full border rounded-lg px-3 py-2 bg-gray-50 text-gray-600" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Price</label>
                            <input type="number" step="0.01" name="premium_price" value="{{ old('premium_price', $settings->premium_price ?? '') }}" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Currency</label>
                            <input type="text" name="premium_currency" value="{{ old('premium_currency', $settings->premium_currency ?? '') }}" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="USD, TZS, …" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Premium Features</label>
                        @php
                            $preset = ['Download Materials','Ad-free Experience','Exclusive Notes','Early Access'];
                            $chosen = old('premium_features', $settings->premium_features ?? []);
                            $chosen = is_array($chosen) ? $chosen : [];
                        @endphp
                        <div class="mt-2 grid grid-cols-1 gap-2">
                            @foreach($preset as $f)
                                <label class="inline-flex items-center gap-2 text-sm">
                                    <input type="checkbox" name="premium_features[]" value="{{ $f }}" {{ in_array($f, $chosen) ? 'checked' : '' }} />
                                    <span>{{ $f }}</span>
                                </label>
                            @endforeach
                        </div>
                        <label class="block text-xs text-gray-600 mt-3">Custom features (one per line)</label>
                        <textarea name="premium_features_custom" rows="3" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g.\nPriority Support\nOffline Access">{{ old('premium_features_custom') }}</textarea>
                    </div>

                    <div class="pt-2 border-t">
                        <h4 class="font-medium text-gray-900">Selcom Configuration</h4>
                        <div class="grid grid-cols-1 gap-3 mt-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Merchant ID</label>
                                <input type="text" name="selcom_merchant_id" value="{{ old('selcom_merchant_id', $settings->selcom_merchant_id ?? '') }}" class="mt-1 w-full border rounded-lg px-3 py-2" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">API Key</label>
                                <input type="text" name="selcom_api_key" value="{{ old('selcom_api_key', $settings->selcom_api_key ?? '') }}" class="mt-1 w-full border rounded-lg px-3 py-2" />
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Environment</label>
                                    <select name="selcom_env" class="mt-1 w-full border rounded-lg px-3 py-2">
                                        <option value="sandbox" {{ old('selcom_env', $settings->selcom_env ?? 'sandbox') === 'sandbox' ? 'selected' : '' }}>Sandbox</option>
                                        <option value="production" {{ old('selcom_env', $settings->selcom_env ?? '') === 'production' ? 'selected' : '' }}>Production</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Callback URL</label>
                                    <input type="text" name="selcom_callback_url" value="{{ old('selcom_callback_url', $settings->selcom_callback_url ?? '') }}" class="mt-1 w-full border rounded-lg px-3 py-2" placeholder="https://your-domain.com/selcom/callback" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-3 flex justify-end">
                <button class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Save Settings
                </button>
            </div>
        </form>
    </div>
    <script>
        function previewImage(event, id) {
            const output = document.getElementById(id);
            const file = event.target.files?.[0];
            if (!output) return;
            if (file) {
                const url = URL.createObjectURL(file);
                output.src = url;
                output.classList.remove('hidden');
            }
        }
    </script>
</x-admin-layout>
