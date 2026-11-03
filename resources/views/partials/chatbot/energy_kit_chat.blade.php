{{-- resources/views/partials/chatbot/energy_kit_chat.blade.php --}}
@php
    $packBotLocale = app()->getLocale();
    $packBotIsRtl = $packBotLocale === 'ar';
    $currentPackObj = (isset($energyKit) && $energyKit instanceof \App\Models\EnergyKit && $energyKit->exists) ? $energyKit : null;
    $speechLang = match ($packBotLocale) {
        'en' => 'en-US',
        'ar' => 'ar-TN',
        default => 'fr-FR',
    };
@endphp

<div id="solar-packbot-widget" class="packbot-container" dir="{{ $packBotIsRtl ? 'rtl' : 'ltr' }}">
    <!-- Floating Launcher Button -->
    <button
        type="button"
        id="packbot-toggle-btn"
        class="packbot-toggle-btn"
        aria-label="{{ __('messages.pack_ai.toggle_aria') }}"
        title="{{ __('messages.pack_ai.toggle_aria') }}"
    >
        <div class="packbot-pulse-ring"></div>
        <div class="packbot-btn-inner">
            <span class="packbot-icon-open" aria-hidden="true" style="font-size: 1.55rem; line-height: 1;">📦</span>
            <svg class="packbot-icon-close d-none" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </div>
        <span class="packbot-badge">{{ __('messages.pack_ai.badge') }}</span>
    </button>

    <!-- Chat Window Panel -->
    <div id="packbot-panel" class="packbot-panel d-none" role="dialog" aria-labelledby="packbot-title">
        <!-- Header -->
        <div class="packbot-header">
            <div class="packbot-header-info">
                <div class="packbot-avatar">
                    <span>📦</span>
                    <span class="packbot-status-dot" title="{{ __('messages.pack_ai.online') }}"></span>
                </div>
                <div>
                    <h3 id="packbot-title" class="packbot-title">{{ __('messages.pack_ai.title') }}</h3>
                    <p class="packbot-subtitle">{{ __('messages.pack_ai.subtitle') }}</p>
                </div>
            </div>
            <div class="packbot-header-actions">
                <button type="button" id="packbot-close-btn" class="packbot-header-btn" aria-label="{{ __('messages.pack_ai.close_aria') }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Messages Container -->
        <div id="packbot-messages" class="packbot-messages">
            <!-- Welcome Message -->
            <div class="packbot-msg packbot-msg-bot">
                <div class="packbot-msg-avatar">📦</div>
                <div class="packbot-msg-content">
                    <p class="mb-2">{!! __('messages.pack_ai.welcome_intro') !!}</p>
                    @if($currentPackObj)
                        <p class="mb-0">{!! __('messages.pack_ai.welcome_current_pack', ['title' => e($currentPackObj->title)]) !!}</p>
                    @else
                        <p class="mb-0">{{ __('messages.pack_ai.welcome_general') }}</p>
                    @endif
                </div>
            </div>

            <!-- Quick Suggestion Chips -->
            <div id="packbot-suggestions" class="packbot-suggestions">
                @if($currentPackObj)
                    <button type="button" class="packbot-chip-btn" data-query="{{ __('messages.pack_ai.chip_current_analyze_query') }}">
                        {{ __('messages.pack_ai.chip_current_analyze') }}
                    </button>
                    <button type="button" class="packbot-chip-btn" data-query="{{ __('messages.pack_ai.chip_current_savings_query') }}">
                        {{ __('messages.pack_ai.chip_current_savings') }}
                    </button>
                    <button type="button" class="packbot-chip-btn" data-query="{{ __('messages.pack_ai.chip_recommend_query') }}">
                        {{ __('messages.pack_ai.chip_recommend') }}
                    </button>
                @else
                    <button type="button" class="packbot-chip-btn" data-query="{{ __('messages.pack_ai.chip_recommend_query') }}">
                        {{ __('messages.pack_ai.chip_recommend') }}
                    </button>
                    <button type="button" class="packbot-chip-btn" data-query="{{ __('messages.pack_ai.chip_discounts_query') }}">
                        {{ __('messages.pack_ai.chip_discounts') }}
                    </button>
                    <button type="button" class="packbot-chip-btn" data-query="{{ __('messages.pack_ai.chip_compose_query') }}">
                        {{ __('messages.pack_ai.chip_compose') }}
                    </button>
                @endif
            </div>
        </div>

        <!-- Typing Indicator -->
        <div id="packbot-typing" class="packbot-typing d-none">
            <div class="packbot-msg-avatar">📦</div>
            <div class="packbot-typing-dots">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>

        <!-- Footer Input -->
        <div class="packbot-footer">
            <div id="packbot-voice-status" class="packbot-voice-status d-none">
                <span class="packbot-voice-pulse"></span>
                <span>{{ __('messages.pack_ai.listening') }}</span>
                <button type="button" id="packbot-voice-cancel" class="packbot-voice-cancel" aria-label="{{ __('messages.pack_ai.stop_listening') }}">✕</button>
            </div>

            <form id="packbot-form" class="packbot-form" autocomplete="off">
                <input
                    type="text"
                    id="packbot-input"
                    class="packbot-input"
                    placeholder="{{ __('messages.pack_ai.input_placeholder') }}"
                    maxlength="1200"
                    required
                >
                <button type="button" id="packbot-mic-btn" class="packbot-mic-btn" aria-label="{{ __('messages.pack_ai.mic_title') }}" title="{{ __('messages.pack_ai.mic_title') }}">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path>
                        <path d="M19 10v2a7 7 0 0 1-14 0v-2"></path>
                        <line x1="12" y1="19" x2="12" y2="23"></line>
                        <line x1="8" y1="23" x2="16" y2="23"></line>
                    </svg>
                </button>
                <button type="submit" id="packbot-send-btn" class="packbot-send-btn" aria-label="{{ __('messages.pack_ai.send_aria') }}">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" style="{{ $packBotIsRtl ? 'transform: scaleX(-1);' : '' }}">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                </button>
            </form>
            <div class="packbot-disclaimer">
                <span>{{ __('messages.pack_ai.disclaimer') }}</span>
            </div>
        </div>
    </div>
</div>

<style>
.packbot-container {
    position: fixed;
    bottom: 92px;
    inset-inline-end: 24px;
    z-index: 99999;
}

.packbot-toggle-btn {
    position: relative;
    width: 62px;
    height: 62px;
    border-radius: 50%;
    border: 2px solid rgba(255, 176, 32, 0.75);
    background: linear-gradient(135deg, #0F5C6B 0%, #083E49 100%);
    color: #FFFFFF;
    cursor: pointer;
    box-shadow: 0 10px 28px rgba(8, 62, 73, 0.45);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s ease;
}

.packbot-toggle-btn:hover {
    transform: translateY(-3px) scale(1.04);
    box-shadow: 0 14px 34px rgba(15, 92, 107, 0.55);
}

.packbot-btn-inner {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: center;
}

.packbot-pulse-ring {
    position: absolute;
    inset: -4px;
    border-radius: 50%;
    border: 2px solid rgba(255, 176, 32, 0.55);
    animation: packbot-pulse 2.4s infinite ease-out;
    pointer-events: none;
}

@keyframes packbot-pulse {
    0% { transform: scale(0.95); opacity: 0.85; }
    100% { transform: scale(1.32); opacity: 0; }
}

.packbot-badge {
    position: absolute;
    top: -4px;
    inset-inline-end: -4px;
    background: linear-gradient(135deg, #FFB020, #FF6B3D);
    color: #071520;
    font-size: 0.68rem;
    font-weight: 800;
    padding: 2px 7px;
    border-radius: 9999px;
    border: 2px solid #FFFFFF;
    z-index: 3;
}

.packbot-panel {
    position: absolute;
    bottom: 76px;
    inset-inline-end: 0;
    width: 390px;
    max-width: calc(100vw - 32px);
    height: 550px;
    max-height: calc(100vh - 180px);
    background: #FFFFFF;
    border-radius: 20px;
    box-shadow: 0 22px 55px rgba(16, 32, 43, 0.24);
    border: 1px solid rgba(15, 92, 107, 0.14);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    animation: packbot-slide-up 0.24s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes packbot-slide-up {
    from { opacity: 0; transform: translateY(14px) scale(0.97); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}

.packbot-header {
    background: linear-gradient(135deg, #083E49 0%, #0F5C6B 65%, #136F82 100%);
    color: #FFFFFF;
    padding: 0.95rem 1.15rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 2px solid rgba(255, 176, 32, 0.45);
}

.packbot-header-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.packbot-avatar {
    position: relative;
    width: 42px;
    height: 42px;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.14);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    flex-shrink: 0;
}

.packbot-status-dot {
    position: absolute;
    bottom: -2px;
    inset-inline-end: -2px;
    width: 11px;
    height: 11px;
    border-radius: 50%;
    background: #22C55E;
    border: 2px solid #083E49;
}

.packbot-title {
    color: #FFFFFF !important;
    font-size: 1.02rem;
    font-weight: 800;
    margin: 0;
    line-height: 1.2;
}

.packbot-subtitle {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.74rem;
    margin: 2px 0 0 0;
}

.packbot-header-btn {
    background: rgba(255, 255, 255, 0.12);
    border: none;
    color: #FFFFFF;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.2s ease;
}

.packbot-header-btn:hover {
    background: rgba(255, 255, 255, 0.24);
}

.packbot-messages {
    flex: 1;
    overflow-y: auto;
    padding: 1rem;
    background: #F8FAFC;
    display: flex;
    flex-direction: column;
    gap: 0.85rem;
}

.packbot-msg {
    display: flex;
    align-items: flex-start;
    gap: 0.55rem;
    max-width: 92%;
}

.packbot-msg-bot {
    align-self: flex-start;
}

.packbot-msg-user {
    align-self: flex-end;
    flex-direction: row-reverse;
}

.packbot-msg-avatar {
    width: 30px;
    height: 30px;
    border-radius: 10px;
    background: linear-gradient(135deg, #0F5C6B, #083E49);
    color: #FFFFFF;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.92rem;
    flex-shrink: 0;
    margin-top: 2px;
}

.packbot-msg-content {
    background: #FFFFFF;
    color: #1E293B;
    border: 1px solid #E2E8F0;
    border-radius: 14px;
    padding: 0.72rem 0.92rem;
    font-size: 0.86rem;
    line-height: 1.55;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
    word-break: break-word;
}

.packbot-msg-user .packbot-msg-content {
    background: linear-gradient(135deg, #0F5C6B, #083E49);
    color: #FFFFFF;
    border-color: transparent;
}

.packbot-msg-content ul {
    margin: 0.4rem 0;
    padding-inline-start: 1.15rem;
}

.packbot-msg-content li {
    margin-bottom: 0.25rem;
}

.packbot-inline-link {
    color: #0F5C6B;
    font-weight: 700;
    text-decoration: underline;
}

.packbot-nav-card {
    margin-top: 0.65rem;
    padding-top: 0.55rem;
    border-top: 1px dashed #CBD5E1;
}

.packbot-nav-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    background: linear-gradient(135deg, #FFB020, #FF6B3D);
    color: #071520 !important;
    font-size: 0.79rem;
    font-weight: 700;
    padding: 0.42rem 0.85rem;
    border-radius: 9999px;
    text-decoration: none !important;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.packbot-nav-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(255, 107, 61, 0.35);
}

.packbot-suggestions {
    display: flex;
    flex-direction: column;
    gap: 0.45rem;
    margin-top: 0.15rem;
}

.packbot-chip-btn {
    text-align: start;
    background: #FFFFFF;
    border: 1px solid rgba(15, 92, 107, 0.22);
    color: #0F5C6B;
    font-size: 0.79rem;
    font-weight: 600;
    padding: 0.5rem 0.8rem;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.18s ease;
}

.packbot-chip-btn:hover {
    background: #0F5C6B;
    color: #FFFFFF;
    border-color: #0F5C6B;
}

.packbot-typing {
    display: flex;
    align-items: center;
    gap: 0.55rem;
    padding: 0.45rem 1rem 0.65rem 1rem;
    background: #F8FAFC;
}

.packbot-typing-dots {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    padding: 0.5rem 0.75rem;
    border-radius: 12px;
}

.packbot-typing-dots span {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #0F5C6B;
    animation: packbot-bounce 1.2s infinite ease-in-out;
}

.packbot-typing-dots span:nth-child(2) { animation-delay: 0.15s; }
.packbot-typing-dots span:nth-child(3) { animation-delay: 0.3s; }

@keyframes packbot-bounce {
    0%, 80%, 100% { transform: scale(0.65); opacity: 0.45; }
    40% { transform: scale(1.1); opacity: 1; }
}

.packbot-footer {
    background: #FFFFFF;
    border-top: 1px solid #E2E8F0;
    padding: 0.7rem 0.85rem 0.55rem 0.85rem;
}

.packbot-voice-status {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem;
    background: #FEF2F2;
    color: #B91C1C;
    border: 1px solid #FECACA;
    border-radius: 10px;
    padding: 0.35rem 0.65rem;
    font-size: 0.76rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.packbot-voice-pulse {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #EF4444;
    animation: packbot-pulse 1.1s infinite;
}

.packbot-voice-cancel {
    background: transparent;
    border: none;
    color: #B91C1C;
    font-weight: 800;
    cursor: pointer;
}

.packbot-form {
    display: flex;
    align-items: center;
    gap: 0.45rem;
}

.packbot-input {
    flex: 1;
    border: 1px solid #CBD5E1;
    border-radius: 9999px;
    padding: 0.58rem 0.95rem;
    font-size: 0.85rem;
    outline: none;
    background: #F8FAFC;
    color: #10202B;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.packbot-input:focus {
    border-color: #0F5C6B;
    background: #FFFFFF;
    box-shadow: 0 0 0 3px rgba(15, 92, 107, 0.14);
}

.packbot-mic-btn,
.packbot-send-btn {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
    transition: all 0.2s ease;
}

.packbot-mic-btn {
    background: #F1F5F9;
    color: #475569;
    border: 1px solid #CBD5E1;
}

.packbot-mic-btn:hover {
    background: #FFF7ED;
    color: #C2410C;
    border-color: #FFB020;
}

.packbot-mic-btn.is-recording {
    background: #EF4444 !important;
    color: #FFFFFF !important;
    border-color: #DC2626 !important;
}

.packbot-send-btn {
    background: linear-gradient(135deg, #FFB020, #FF6B3D);
    color: #071520;
    border: none;
}

.packbot-send-btn:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(255, 107, 61, 0.35);
}

.packbot-send-btn:disabled {
    opacity: 0.55;
    cursor: not-allowed;
    transform: none;
}

.packbot-disclaimer {
    text-align: center;
    font-size: 0.67rem;
    color: #94A3B8;
    margin-top: 5px;
}

@media (max-width: 576px) {
    .packbot-container {
        bottom: 82px;
        inset-inline-end: 16px;
    }
    .packbot-panel {
        width: calc(100vw - 32px);
        height: 72vh;
        max-height: 72vh;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.getElementById('packbot-toggle-btn');
    const panel = document.getElementById('packbot-panel');
    const closeBtn = document.getElementById('packbot-close-btn');
    const form = document.getElementById('packbot-form');
    const input = document.getElementById('packbot-input');
    const messagesBox = document.getElementById('packbot-messages');
    const typingBox = document.getElementById('packbot-typing');
    const sendBtn = document.getElementById('packbot-send-btn');
    const micBtn = document.getElementById('packbot-mic-btn');
    const voiceStatus = document.getElementById('packbot-voice-status');
    const voiceCancel = document.getElementById('packbot-voice-cancel');
    const iconOpen = toggleBtn?.querySelector('.packbot-icon-open');
    const iconClose = toggleBtn?.querySelector('.packbot-icon-close');

    if (!toggleBtn || !panel || !form) return;

    const currentKitId = @json($currentPackObj?->id);
    const chatEndpoint = @json(route('energy-kits.chat'));
    const speechLang = @json($speechLang);
    const i18n = {
        goTo: @json(__('messages.pack_ai.go_to_btn')),
        errGeneric: @json(__('messages.pack_ai.error_generic')),
        errNetwork: @json(__('messages.pack_ai.error_network')),
        voiceUnsupported: @json(__('messages.pack_ai.voice_unsupported')),
        isRtl: @json($packBotIsRtl),
    };

    let history = [];
    let recognition = null;
    let isListening = false;

    function openPanel() {
        if (panel.classList.contains('d-none')) {
            panel.classList.remove('d-none');
            iconOpen?.classList.add('d-none');
            iconClose?.classList.remove('d-none');
        }
        setTimeout(() => input.focus(), 60);
    }

    function closePanel() {
        panel.classList.add('d-none');
        iconOpen?.classList.remove('d-none');
        iconClose?.classList.add('d-none');
        stopVoice();
    }

    function togglePanel() {
        if (panel.classList.contains('d-none')) {
            openPanel();
        } else {
            closePanel();
        }
    }

    // Global helper so inline buttons on Solar Packs pages can open PackBot directly
    window.openPackBot = function (prefillQuery = null) {
        openPanel();
        if (prefillQuery && typeof prefillQuery === 'string' && prefillQuery.trim() !== '') {
            input.value = prefillQuery.trim();
            form.dispatchEvent(new Event('submit', { cancelable: true }));
        }
    };

    toggleBtn.addEventListener('click', togglePanel);
    closeBtn?.addEventListener('click', closePanel);

    document.querySelectorAll('.packbot-chip-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
            const q = btn.getAttribute('data-query');
            if (!q) return;
            input.value = q;
            const chips = document.getElementById('packbot-suggestions');
            if (chips) chips.style.display = 'none';
            form.dispatchEvent(new Event('submit', { cancelable: true }));
        });
    });

    function escapeHtml(str) {
        return String(str || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function formatMarkdown(text) {
        let html = escapeHtml(text);

        // Links [label](url) - allow relative paths or http(s)
        html = html.replace(/\[([^\]]+)\]\(((?:\/|https?:\/\/)[^\s)]+)\)/g, '<a href="$2" class="packbot-inline-link">$1 ↗</a>');

        // Inline code `code`
        html = html.replace(/`([^`]+)`/g, '<code style="background:#F1F5F9;padding:1px 5px;border-radius:4px;font-size:0.82em;">$1</code>');

        // Headings ### Title
        html = html.replace(/(?:^|\n)#{1,4}\s+(.+?)(?=\n|$)/g, '<strong style="display:block;margin-top:0.45rem;color:#083E49;">$1</strong>');

        // Bold **text**
        html = html.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>');

        // Bullet lists
        html = html.replace(/(?:^|\n)[*-]\s+(.+?)(?=\n|$)/g, '<li>$1</li>');
        html = html.replace(/(<li>[\s\S]*?<\/li>)/g, '<ul>$1</ul>');
        html = html.replace(/<\/ul>\s*<ul>/g, '');

        // Paragraphs / line breaks
        html = html.replace(/\n\n/g, '<br><br>').replace(/\n/g, '<br>');

        return html;
    }

    function appendMessage(role, text, navigation = null) {
        const wrapper = document.createElement('div');
        wrapper.className = `packbot-msg packbot-msg-${role === 'user' ? 'user' : 'bot'}`;

        if (role === 'bot') {
            let navHtml = '';
            if (navigation && navigation.url) {
                const arrow = i18n.isRtl ? '&larr;' : '&rarr;';
                navHtml = `
                    <div class="packbot-nav-card">
                        <a href="${escapeHtml(navigation.url)}" class="packbot-nav-btn">
                            <span>📦</span>
                            <span>${escapeHtml(i18n.goTo)} <strong>${escapeHtml(navigation.label || '')}</strong></span>
                            <span>${arrow}</span>
                        </a>
                    </div>
                `;
            }

            wrapper.innerHTML = `
                <div class="packbot-msg-avatar">📦</div>
                <div class="packbot-msg-content">
                    ${formatMarkdown(text)}
                    ${navHtml}
                </div>
            `;
        } else {
            wrapper.innerHTML = `<div class="packbot-msg-content">${escapeHtml(text)}</div>`;
        }

        messagesBox.appendChild(wrapper);
        messagesBox.scrollTop = messagesBox.scrollHeight;
    }

    // Optional Speech-to-Text
    const SpeechRec = window.SpeechRecognition || window.webkitSpeechRecognition;

    function initSpeech() {
        if (!SpeechRec) return null;
        const sr = new SpeechRec();
        sr.lang = speechLang;
        sr.continuous = false;
        sr.interimResults = true;

        sr.onstart = () => {
            isListening = true;
            micBtn?.classList.add('is-recording');
            voiceStatus?.classList.remove('d-none');
        };

        sr.onresult = (event) => {
            let transcript = '';
            for (let i = event.resultIndex; i < event.results.length; i++) {
                transcript += event.results[i][0].transcript;
            }
            input.value = transcript;
        };

        sr.onerror = () => stopVoice();

        sr.onend = () => {
            stopVoice();
            if (input.value.trim().length > 0) {
                form.dispatchEvent(new Event('submit', { cancelable: true }));
            }
        };

        return sr;
    }

    function stopVoice() {
        isListening = false;
        micBtn?.classList.remove('is-recording');
        voiceStatus?.classList.add('d-none');
        if (recognition) {
            try { recognition.stop(); } catch (e) {}
        }
    }

    micBtn?.addEventListener('click', () => {
        if (isListening) {
            stopVoice();
            return;
        }
        if (!recognition) recognition = initSpeech();
        if (!recognition) {
            alert(i18n.voiceUnsupported);
            return;
        }
        try {
            input.value = '';
            recognition.start();
        } catch (e) {
            stopVoice();
        }
    });

    voiceCancel?.addEventListener('click', stopVoice);

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const message = input.value.trim();
        if (!message) return;

        if (isListening) stopVoice();

        appendMessage('user', message);
        input.value = '';
        sendBtn.disabled = true;
        typingBox.classList.remove('d-none');
        messagesBox.scrollTop = messagesBox.scrollHeight;

        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        try {
            const response = await fetch(chatEndpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token || '',
                },
                body: JSON.stringify({
                    message: message,
                    history: history.slice(-8),
                    kit_id: currentKitId,
                }),
            });

            const data = await response.json();
            typingBox.classList.add('d-none');

            if (response.ok && data.status === 'success') {
                appendMessage('bot', data.reply, data.navigation);
                history.push({ role: 'user', text: message });
                history.push({ role: 'model', text: data.reply });
            } else {
                appendMessage('bot', data.message || i18n.errGeneric);
            }
        } catch (err) {
            typingBox.classList.add('d-none');
            appendMessage('bot', i18n.errNetwork);
        } finally {
            sendBtn.disabled = false;
            input.focus();
        }
    });
});
</script>
