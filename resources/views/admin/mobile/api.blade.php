<x-admin-layout>
    <div class="py-4">
        <div class="w-full">
            <h1 class="text-xl font-semibold text-gray-900">Wazaelimu Mobile API Documentation</h1>
            <p class="text-sm text-gray-500">Reference for public endpoints consumed by the mobile app.</p>
            <div class="mt-2 font-mono text-[11px] tracking-widest text-gray-400 select-none" aria-hidden="true">- - - - - - - - - - - - - - - - - - - - - - - - -</div>
        </div>

        <div class="mt-4 grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Overview -->
            <div class="lg:col-span-2 bg-white border rounded-lg p-4">
                <h2 class="font-semibold text-gray-900">Overview</h2>
                <p class="text-sm text-gray-600 mt-1">All endpoints are JSON and read-only unless specified. Base URL depends on your environment.</p>

                <div class="mt-3">
                    <div class="mockup-code w-full">
                        <pre data-prefix="$" class="text-xs"><code>BASE_URL={{ config('app.url') }}</code></pre>
                    </div>
                </div>

                <div class="mt-4">
                    <h3 class="font-medium text-gray-900">Authentication</h3>
                    <p class="text-sm text-gray-600">Current public endpoints do not require authentication. Future endpoints may require tokens.</p>
                </div>
            </div>

            <!-- Legend / Status -->
            <div class="bg-white border rounded-lg p-4">
                <h3 class="font-semibold text-gray-900">Legend</h3>
                <ul class="text-sm text-gray-700 mt-2 space-y-1">
                    <li><span class="inline-flex h-2 w-2 bg-green-500 rounded-full mr-2"></span>Live</li>
                    <li><span class="inline-flex h-2 w-2 bg-amber-500 rounded-full mr-2"></span>Planned</li>
                </ul>
            </div>
        </div>

        <!-- Live Endpoints -->
        <div class="mt-6 bg-white border rounded-lg p-4">
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-gray-900">Endpoints</h2>
                <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-700">Live</span>
            </div>
            <div class="mt-3 space-y-6">
                <!-- GET /api/mobile/settings -->
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded bg-gray-800 text-white">GET</span>
                        <code class="text-sm">/api/mobile/settings</code>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">Returns app branding, update policy, notification visibility, OAuth callback, premium options, and Selcom config.</p>

                    <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs uppercase tracking-wider text-gray-500">Request</label>
                            <div class="mockup-code w-full">
                                <pre data-prefix="$" class="text-xs"><code>curl -s {{ rtrim(config('app.url'), '/') }}/api/mobile/settings</code></pre>
                                <pre data-prefix="$" class="text-xs"><code># JS fetch</code></pre>
                                <pre data-prefix=">" class="text-xs"><code>fetch(`${BASE_URL}/api/mobile/settings`).then(r =&gt; r.json())</code></pre>
                            </div>
                        </div>
                        <div>
                            <label class="text-xs uppercase tracking-wider text-gray-500">Response (200)</label>
                            <div class="mockup-code w-full overflow-auto">
<pre data-prefix=">" class="text-[11px]"><code>{
  "branding": {
    "appIconUrl": "/storage/app/icon.png",
    "splashImageUrl": "/storage/app/splash.jpg",
    "splashHeadline": "Welcome to Wazaelimu",
    "splashSubtext": "Learn smarter, not harder"
  },
  "notifications": { "show": true },
  "update": {
    "required": false,
    "version": "1.2.3",
    "notes": "Bug fixes and improvements",
    "forceAfter": null
  },
  "oauth": { "googleCallbackUrl": "{{ rtrim(config('app.url'), '/') }}/oauth/google/callback" },
  "premium": {
    "enabled": true,
    "provider": "selcom",
    "price": 4.99,
    "currency": "TZS",
    "features": ["Download Materials","Ad-free Experience"],
    "selcom": {
      "merchantId": "...",
      "env": "sandbox",
      "callbackUrl": "https://example.com/selcom/callback"
    }
  },
  "meta": {}
}</code></pre>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="text-xs uppercase tracking-wider text-gray-500">Fields</label>
                        <ul class="mt-1 text-sm text-gray-700 space-y-1">
                            <li><b>branding</b>: icon/splash URLs and texts.</li>
                            <li><b>notifications.show</b>: whether to show in-app notifications.</li>
                            <li><b>update.required</b>: if true, app should prompt for update.</li>
                            <li><b>update.forceAfter</b>: ISO timestamp after which update is mandatory.</li>
                            <li><b>oauth.googleCallbackUrl</b>: OAuth redirect for Google.</li>
                            <li><b>premium.*</b>: premium availability, pricing, features, and Selcom config.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- More Live Endpoints: Content -->
        <div class="mt-6 bg-white border rounded-lg p-4">
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-gray-900">Content Endpoints</h2>
                <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-700">Live</span>
            </div>
            <p class="text-sm text-gray-600 mt-1">Access levels, subjects, classes and notes. Downloads are premium-gated.</p>

            <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="font-medium text-gray-900">Premium gating</h4>
                    <p class="text-sm text-gray-600">Temporary mechanism while payment integration is finalized:</p>
                    <ul class="text-sm text-gray-700 list-disc ml-5 mt-1">
                        <li>Send <code>X-Premium: true</code> request header, or</li>
                        <li>Append query <code>?premium=1</code></li>
                    </ul>
                    <p class="text-xs text-gray-500 mt-1">If not premium, user sees preview URLs only; downloads return 403.</p>
                </div>
                <div>
                    <div class="mockup-code w-full">
                        <pre data-prefix="$" class="text-xs"><code># Example premium header</code></pre>
                        <pre data-prefix="$" class="text-xs"><code>curl -H "X-Premium: true" {{ rtrim(config('app.url'), '/') }}/api/mobile/content/notes</code></pre>
                        <pre data-prefix="$" class="text-xs"><code># Or query flag</code></pre>
                        <pre data-prefix="$" class="text-xs"><code>curl {{ rtrim(config('app.url'), '/') }}/api/mobile/content/notes?premium=1</code></pre>
                    </div>
                </div>
            </div>

            <!-- Levels -->
            <div class="mt-5">
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-semibold px-2 py-0.5 rounded bg-gray-800 text-white">GET</span>
                    <code class="text-sm">/api/mobile/content/levels</code>
                </div>
                <p class="text-sm text-gray-600 mt-1">Returns list of education levels.</p>
            </div>

            <!-- Subjects -->
            <div class="mt-4">
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-semibold px-2 py-0.5 rounded bg-gray-800 text-white">GET</span>
                    <code class="text-sm">/api/mobile/content/subjects</code>
                </div>
                <p class="text-sm text-gray-600 mt-1">Query params: <code>q</code> (search), pagination standard.</p>
            </div>

            <!-- Classes -->
            <div class="mt-4">
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-semibold px-2 py-0.5 rounded bg-gray-800 text-white">GET</span>
                    <code class="text-sm">/api/mobile/content/classes</code>
                </div>
                <p class="text-sm text-gray-600 mt-1">Query params: <code>subject_id</code>, <code>level_id</code>, pagination standard.</p>
            </div>

            <!-- Notes list -->
            <div class="mt-4">
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-semibold px-2 py-0.5 rounded bg-gray-800 text-white">GET</span>
                    <code class="text-sm">/api/mobile/content/notes</code>
                </div>
                <p class="text-sm text-gray-600 mt-1">Query params: <code>subject_id</code>, <code>level_id</code>, <code>class_id</code>, <code>q</code>, pagination standard.</p>
                <div class="mockup-code w-full mt-2">
<pre data-prefix=">" class="text-[11px]"><code>{
  "data": [
    {
      "id": 1,
      "title": "Algebra Basics",
      "mime_type": "application/pdf",
      "previewUrl": "/storage/notes/1.pdf",
      "canDownload": false,
      "downloadUrl": null
    }
  ],
  "pagination": { "current_page": 1, "last_page": 5, "per_page": 20, "total": 100 }
}</code></pre>
                </div>
            </div>

            <!-- Note details -->
            <div class="mt-4">
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-semibold px-2 py-0.5 rounded bg-gray-800 text-white">GET</span>
                    <code class="text-sm">/api/mobile/content/notes/{id}</code>
                </div>
                <p class="text-sm text-gray-600 mt-1">Returns one note with preview and premium-gated download link.</p>
            </div>

            <!-- Download (premium) -->
            <div class="mt-4">
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-semibold px-2 py-0.5 rounded bg-indigo-600 text-white">GET</span>
                    <code class="text-sm">/api/mobile/content/notes/{id}/download</code>
                </div>
                <p class="text-sm text-gray-600 mt-1">Requires premium. Without premium, returns 403.</p>
            </div>
        </div>

        <!-- Planned Endpoints -->
        <div class="mt-6 bg-white border rounded-lg p-4">
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-gray-900">Planned Endpoints</h2>
                <span class="text-xs px-2 py-1 rounded-full bg-amber-100 text-amber-700">Planned</span>
            </div>
            <p class="text-sm text-gray-600 mt-1">The following endpoints are planned and will be documented here once available.</p>
            <ul class="mt-2 text-sm text-gray-700 space-y-2">
                <li>
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded bg-gray-400 text-white">GET</span>
                        <code>/api/mobile/maintenance</code>
                    </div>
                    <p class="text-xs text-gray-500 ml-8">Fetch current maintenance status and message.</p>
                </li>
                <li>
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded bg-gray-400 text-white">GET</span>
                        <code>/api/mobile/notifications</code>
                    </div>
                    <p class="text-xs text-gray-500 ml-8">Fetch recent notifications for in-app display.</p>
                </li>
                <li>
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded bg-gray-400 text-white">GET</span>
                        <code>/api/mobile/content/materials</code>
                    </div>
                    <p class="text-xs text-gray-500 ml-8">List learning materials with pagination and filters.</p>
                </li>
                <li>
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded bg-gray-400 text-white">GET</span>
                        <code>/api/mobile/content/notes</code>
                    </div>
                    <p class="text-xs text-gray-500 ml-8">List notes across levels/subjects/classes.</p>
                </li>
            </ul>
        </div>

        <!-- Tips -->
        <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="bg-white border rounded-lg p-4">
                <h3 class="font-semibold text-gray-900">Client quick start</h3>
                <div class="mockup-code w-full mt-2">
                    <pre data-prefix="$" class="text-xs"><code># JavaScript</code></pre>
                    <pre data-prefix=">" class="text-xs"><code>const base = '{{ rtrim(config('app.url'), '/') }}';</code></pre>
                    <pre data-prefix=">" class="text-xs"><code>const settings = await fetch(`${base}/api/mobile/settings`).then(r =&gt; r.json());</code></pre>
                </div>
            </div>
            <div class="bg-white border rounded-lg p-4">
                <h3 class="font-semibold text-gray-900">Styling helper</h3>
                <p class="text-sm text-gray-600">You can mimic code blocks using a DaisyUI-like style:</p>
                <div class="mockup-code w-full mt-2">
                    <pre data-prefix="$" class="text-xs"><code>npm i daisyui</code></pre>
                </div>
                <p class="text-xs text-gray-500 mt-2">Note: This UI uses Tailwind base classes; DaisyUI is optional.</p>
            </div>
        </div>
    </div>
</x-admin-layout>
