<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ELMS-ATC') }} - Frequently Asked Questions (FAQ)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/custom.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
</head>
<body class="bg-white text-gray-900">
    @include('partials.header')

    <!-- Header with image + colorful breadcrumb -->
    <header class="relative overflow-hidden">
        <div class="absolute inset-0">
            <img src="{{ asset('group-african-kids-paying-attention-class_23-2148892516.jpg') }}" class="w-full h-full object-cover opacity-20" alt="faq bg" />
            <div class="absolute inset-0 bg-gradient-to-b from-white via-white/80 to-white"></div>
        </div>
        <div class="relative max-w-screen-xl mx-auto px-4 py-10">
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">Frequently Asked Questions (FAQ)</h1>
            <p class="mt-2 text-gray-600">Answers to common questions about using Wazaelimu.</p>
            <nav class="mt-4 text-sm" aria-label="Breadcrumb">
                <ol class="flex items-center gap-2 text-gray-700">
                    <li>
                        <a href="{{ url('/') }}" class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-blue-50 text-blue-700 hover:bg-blue-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3l8 7h-2v8h-5v-5H11v5H6v-8H4l8-7z"/></svg>
                            Home
                        </a>
                    </li>
                    <li class="text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M9 18l6-6-6-6"/></svg>
                    </li>
                    <li>
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-emerald-50 text-emerald-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 100 20 10 10 0 000-20zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                            FAQ
                        </span>
                    </li>
                </ol>
            </nav>
        </div>
    </header>

    <!-- FAQ Accordion -->
    <main class="max-w-screen-xl mx-auto px-4 py-10">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-xl sm:text-2xl font-semibold">Questions and Answers</h2>
            <p class="mt-2 text-gray-600">Click a question to expand and view the answer. Smooth animations make it pleasant to browse.</p>

            <div class="mt-6 divide-y rounded-xl border bg-white overflow-hidden">
                @php
                    $faqs = [
                        ['q' => 'What is Wazaelimu?', 'a' => 'Wazaelimu is a learning and content management platform built for students and teachers to access, organize, and share educational resources.'],
                        ['q' => 'How do I create an account?', 'a' => 'Click Get Started or Register on the home page and complete the simple sign-up form. You can log in immediately after verification.'],
                        ['q' => 'Where can I view available classes?', 'a' => 'Open the Classes page to browse all classes and related subjects. Each class card shows key details and subjects.'],
                        ['q' => 'How can I search for a class or subject?', 'a' => 'Use search and filters where available. You can also use the mobile app or admin panel to quickly find specific notes, materials, or classes.'],
                        ['q' => 'Can I download notes or materials?', 'a' => 'Yes. Many files support direct download or in-browser preview depending on permissions and file type.'],
                        ['q' => 'How do I get updates and announcements?', 'a' => 'We send notifications and also offer email updates via the Subscribe section on the home page.'],
                        ['q' => 'Is there a storage limit for uploads?', 'a' => 'Storage allowances depend on system settings. Typical learning files are supported and subject to fair-use limits.'],
                        ['q' => 'I cannot log in. What should I do?', 'a' => 'Double-check your credentials. If you forgot your password, use the “Forgot Password” link. If issues persist, contact support at info@wazaelimu.co.tz.'],
                        ['q' => 'Does the app work on Android?', 'a' => 'Yes. We provide an Android app optimized for performance and regular updates.'],
                        ['q' => 'How can I contact support?', 'a' => 'Use the Contact section on the website or email us at info@wazaelimu.co.tz. We usually reply within 1–2 business days.'],
                        ['q' => 'Can I share public content?', 'a' => 'Yes. Some pages feature public previews that make it easy to share selected materials with anyone.'],
                        ['q' => 'Are my personal details secure?', 'a' => 'Yes. We use modern security practices, authentication, and privacy policies to protect your data.'],
                        ['q' => 'Will you add new features?', 'a' => 'We continuously improve the platform based on user feedback and roadmap priorities.']
                    ];
                @endphp

                @foreach($faqs as $i => $item)
                    @php $panelId = 'faq-panel-' . $i; @endphp
                    <div class="group">
                        <button
                            class="w-full flex items-start justify-between gap-4 px-4 py-4 text-left hover:bg-gray-50 focus:outline-none"
                            aria-controls="{{ $panelId }}"
                            aria-expanded="false"
                            onclick="toggleFaq('{{ $panelId }}', this)">
                            <span class="flex-1 font-medium text-gray-900">{{ $item['q'] }}</span>
                            <span class="shrink-0 inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-100 text-red-700 transition-transform group-[aria-expanded=true]:rotate-45">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M11 11V5h2v6h6v2h-6v6h-2v-6H5v-2z"/></svg>
                            </span>
                        </button>
                        <div id="{{ $panelId }}" class="faq-panel px-4 pb-4 text-sm text-gray-700 overflow-hidden max-h-0" style="transition: max-height 300ms ease, opacity 300ms ease; opacity: 0;">
                            <p>{{ $item['a'] }}</p>
                        </div>
                        <div class="border-t"></div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>

    @include('partials.footer')

    <script>
        function toggleFaq(id, btn) {
            const panel = document.getElementById(id);
            const expanded = btn.getAttribute('aria-expanded') === 'true';
            btn.setAttribute('aria-expanded', String(!expanded));
            if (!expanded) {
                panel.style.maxHeight = panel.scrollHeight + 'px';
                panel.style.opacity = '1';
            } else {
                panel.style.maxHeight = '0px';
                panel.style.opacity = '0';
            }
        }
    </script>
</body>
</html>
