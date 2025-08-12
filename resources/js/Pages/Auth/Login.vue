<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
  <Head title="Log in" />
  <div class="login-page">
    <div class="login-bg"></div>
    <div class="login-overlay"></div>
    <div class="login-foreground">
      <div class="login-wrapper">
        <div class="login-card">
          <img class="login-logo" src="/logo.png" alt="Wazaelimu" />
          <h2 class="login-title">Sign in to your account</h2>

          <form class="space-y-6" @submit.prevent="submit">
            <div class="login-field">
              <label for="email" class="login-label">Email address</label>
              <input v-model="form.email" type="email" name="email" id="email" autocomplete="email" required class="login-input" placeholder="you@example.com" />
              <p v-if="form.errors.email" class="mt-2 text-sm" style="color:#fca5a5">{{ form.errors.email }}</p>
            </div>

            <div class="login-field">
              <div style="display:flex;align-items:center;justify-content:space-between;gap:.5rem;">
                <label for="password" class="login-label">Password</label>
                <div class="text-sm">
                  <Link v-if="canResetPassword" :href="route('password.request')" class="font-semibold" style="color:#818cf8">Forgot password?</Link>
                </div>
              </div>
              <input v-model="form.password" type="password" name="password" id="password" autocomplete="current-password" required class="login-input" placeholder="••••••••" />
              <p v-if="form.errors.password" class="mt-2 text-sm" style="color:#fca5a5">{{ form.errors.password }}</p>
            </div>

            <div class="login-actions">
              <button type="submit" :disabled="form.processing" class="login-submit">Sign in</button>
            </div>
          </form>

          <p class="login-help">
            Not a member?
            {{ ' ' }}
            <Link :href="route('register')">Start a 14 day free trial</Link>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>
