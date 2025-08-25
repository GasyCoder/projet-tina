<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
    <!-- Bloc Installation PWA (Android / iOS) -->
<div class="mt-8">
  <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4 sm:p-5">
    <div class="flex items-start gap-4">
      <div class="shrink-0 w-12 h-12 rounded-2xl bg-gradient-to-tr from-green-600/10 via-green-600/5 to-transparent border border-gray-200 dark:border-gray-700 flex items-center justify-center">
        <svg class="w-6 h-6 text-green-600 dark:text-green-500" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
          <path d="M12 3a9 9 0 100 18 9 9 0 000-18zM8 11h1v4H8v-4zm7 0h1v4h-1v-4zM9 8a1 1 0 100-2 1 1 0 000 2zm6 0a1 1 0 100-2 1 1 0 000 2z"/>
        </svg>
      </div>

      <div class="flex-1">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Installer l’application</h3>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
          Accès rapide, plein écran et mode hors ligne.
        </p>

        <div class="mt-4 flex flex-wrap items-center gap-3">
          <!-- Android: visible quand beforeinstallprompt est prêt -->
          <button id="btnInstallAndroid"
                  type="button"
                  class="hidden inline-flex items-center gap-2 px-4 py-2 rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M17.6 9.48l1.3-2.25a.5.5 0 10-.87-.5l-1.33 2.3a7.03 7.03 0 00-8.4 0L7 6.73a.5.5 0 10-.87.5l1.3 2.25A6.98 6.98 0 006 13v5a1 1 0 001 1h1v2a1 1 0 001 1h1a1 1 0 001-1v-2h2v2a1 1 0 001 1h1a1 1 0 001-1v-2h1a1 1 0 001-1v-5a6.98 6.98 0 00-2.4-3.52zM8 11h1v4H8v-4zm7 0h1v4h-1v-4z"/></svg>
            Installer sur Android
          </button>

          <!-- iOS: guide (pas d’API d’invite) -->
          <button id="btnInstallIOS"
                  type="button"
                  class="hidden inline-flex items-center gap-2 px-4 py-2 rounded-lg text-gray-800 dark:text-gray-100 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M16.365 1.43a4.4 4.4 0 01-1.02 3.18 3.73 3.73 0 01-2.9 1.44 3.9 3.9 0 011.01-3.04A4.24 4.24 0 0116.365 1.43zM20.5 17.08c-.43.98-.94 1.86-1.53 2.62-.8 1.02-1.62 1.53-2.47 1.53-.58 0-1.3-.17-2.16-.52-.86-.35-1.65-.52-2.36-.52-.75 0-1.56.17-2.44.52-.88.35-1.55.53-2 .53-.9 0-1.76-.5-2.6-1.5-.84-1-1.54-2.19-2.1-3.57-.58-1.43-.87-2.83-.87-4.2 0-1.56.34-2.9 1.02-4 .68-1.1 1.58-1.65 2.71-1.65.53 0 1.22.16 2.06.48.84.32 1.55.48 2.12.48.52 0 1.21-.17 2.06-.51.86-.34 1.58-.51 2.15-.51 1.33 0 2.37.63 3.12 1.88-.84.51-1.47 1.11-1.87 1.82-.4.7-.6 1.52-.6 2.45 0 1 .24 1.88.73 2.61.49.73 1.15 1.23 1.97 1.5.49.17 1.15.27 1.98.3z"/></svg>
            Ajouter sur iPhone/iPad
          </button>

          <!-- Aide (desktop / fallback) -->
          <button id="btnInstallHelp"
                  type="button"
                  class="hidden text-sm text-gray-600 hover:text-gray-800 dark:text-gray-300 dark:hover:text-white underline underline-offset-4">
            Comment installer ?
          </button>
        </div>

        <p id="pwaInstalledNote" class="mt-3 hidden text-sm text-green-700 dark:text-green-400">
          L’application est déjà installée sur cet appareil ✅
        </p>
      </div>
    </div>
  </div>
</div>

<!-- Modal d’aide -->
<div id="installModal" class="hidden fixed inset-0 z-50">
  <div class="absolute inset-0 bg-black/50"></div>
  <div class="relative mx-auto mt-24 w-[92%] max-w-md rounded-2xl bg-white dark:bg-gray-800 shadow-xl border border-gray-200 dark:border-gray-700 p-5">
    <div class="flex items-start gap-3">
      <div class="shrink-0 w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
        <svg class="w-5 h-5 text-gray-700 dark:text-gray-200" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 100 20 10 10 0 000-20zM11 7h2v6h-2V7zm0 8h2v2h-2v-2z"/></svg>
      </div>
      <div class="flex-1">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Installer sur votre appareil</h3>
        <div class="mt-2 space-y-2 text-sm text-gray-700 dark:text-gray-200">
          <div id="stepsIOS" class="hidden">
            <p class="font-medium">iPhone / iPad (Safari) :</p>
            <ol class="list-decimal list-inside space-y-1">
              <li>Appuyez sur <span class="font-semibold">Partager</span> (carré + flèche).</li>
              <li>Choisissez <span class="font-semibold">“Sur l’écran d’accueil”</span>.</li>
              <li>Validez avec <span class="font-semibold">Ajouter</span>.</li>
            </ol>
          </div>
          <div id="stepsAndroid" class="hidden">
            <p class="font-medium">Android (Chrome) :</p>
            <ol class="list-decimal list-inside space-y-1">
              <li>Menu ⋮ en haut à droite.</li>
              <li><span class="font-semibold">“Installer l’app”</span> / <span class="font-semibold">“Ajouter à l’écran d’accueil”</span>.</li>
            </ol>
          </div>
          <div id="stepsDesktop" class="hidden">
            <p class="font-medium">Ordinateur (Chrome/Edge) :</p>
            <ol class="list-decimal list-inside space-y-1">
              <li>Cliquez sur l’icône <span class="font-semibold">Installer</span> dans la barre d’adresse.</li>
              <li>Confirmez l’installation.</li>
            </ol>
          </div>
        </div>
        <div class="mt-4 flex justify-end gap-2">
          <button id="modalClose" type="button" class="px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600">Fermer</button>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
(function () {
  const isStandalone = window.__isStandalone === true;
  const ua = navigator.userAgent || '';
  const isAndroid = /Android/i.test(ua);
  const isIOS = /iPhone|iPad|iPod/i.test(ua);
  const isSafari = /^((?!chrome|android).)*safari/i.test(ua);

  const btnAndroid = document.getElementById('btnInstallAndroid');
  const btnIOS     = document.getElementById('btnInstallIOS');
  const btnHelp    = document.getElementById('btnInstallHelp');
  const noteInstalled = document.getElementById('pwaInstalledNote');

  const installModal = document.getElementById('installModal');
  const stepsIOS     = document.getElementById('stepsIOS');
  const stepsAndroid = document.getElementById('stepsAndroid');
  const stepsDesktop = document.getElementById('stepsDesktop');
  const modalClose   = document.getElementById('modalClose');

  // déjà installé ?
  if (isStandalone) {
    noteInstalled?.classList.remove('hidden');
    return;
  }

  // Android: afficher si l’invite PWA est prête
  function showAndroidBtnIfReady() {
    if (isAndroid && window.__deferredPrompt) {
      btnAndroid?.classList.remove('hidden');
    }
  }
  showAndroidBtnIfReady();
  window.addEventListener('pwa:can-install', showAndroidBtnIfReady);

  // iOS (Safari): bouton guide
  if (isIOS && isSafari) {
    btnIOS?.classList.remove('hidden');
  }

  // Desktop / autres: aide
  if (!isAndroid && !(isIOS && isSafari)) {
    btnHelp?.classList.remove('hidden');
  }

  // Actions
  btnAndroid?.addEventListener('click', async () => {
    const prompt = window.__deferredPrompt;
    if (!prompt) return;
    btnAndroid.disabled = true;
    try {
      prompt.prompt();
      await prompt.userChoice; // { outcome }
    } catch (e) {
      console.error(e);
    } finally {
      window.__deferredPrompt = null;
      btnAndroid.classList.add('hidden');
    }
  });

  function openModal(mode) {
    stepsIOS.classList.add('hidden');
    stepsAndroid.classList.add('hidden');
    stepsDesktop.classList.add('hidden');
    if (mode === 'ios') stepsIOS.classList.remove('hidden');
    else if (mode === 'android') stepsAndroid.classList.remove('hidden');
    else stepsDesktop.classList.remove('hidden');
    installModal.classList.remove('hidden');
  }

  btnIOS?.addEventListener('click', () => openModal('ios'));
  btnHelp?.addEventListener('click', () => { openModal(isAndroid ? 'android' : 'desktop'); });
  modalClose?.addEventListener('click', () => installModal.classList.add('hidden'));
  installModal?.addEventListener('click', (e) => { if (e.target === installModal) installModal.classList.add('hidden'); });

  // Si tu n'utilises PAS vite-plugin-pwa, garde l'enregistrement ci-dessous et fournis /sw.js
  // Avec vite-plugin-pwa + registerSW, enlève cette partie.
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
      // navigator.serviceWorker.register('/sw.js').catch(console.error);
    });
  }
})();
</script>
@endpush

</x-guest-layout>
