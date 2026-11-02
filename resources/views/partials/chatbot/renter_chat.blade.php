{{-- resources/views/partials/chatbot/renter_chat.blade.php --}}
<div id="solar-renter-chatbot" class="solar-chatbot-container">
  <!-- Floating Launcher Button -->
  <button type="button" id="solar-chat-toggle-btn" class="solar-chat-toggle-btn" aria-label="Ouvrir l'assistant SolarBot Emprunteur" title="Assistant Emprunteur SolarBot">
    <div class="solar-chat-pulse-ring"></div>
    <div class="solar-chat-btn-inner">
      <!-- Chat Icon -->
      <svg class="solar-chat-icon-open" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
        <circle cx="12" cy="10" r="1" fill="currentColor"></circle>
        <circle cx="8" cy="10" r="1" fill="currentColor"></circle>
        <circle cx="16" cy="10" r="1" fill="currentColor"></circle>
      </svg>
      <!-- Close Icon -->
      <svg class="solar-chat-icon-close d-none" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <line x1="18" y1="6" x2="6" y2="18"></line>
        <line x1="6" y1="6" x2="18" y2="18"></line>
      </svg>
    </div>
    <span class="solar-chat-badge">IA</span>
  </button>

  <!-- Chat Window Panel -->
  <div id="solar-chat-panel" class="solar-chat-panel d-none" role="dialog" aria-labelledby="solar-chat-title">
    <!-- Header -->
    <div class="solar-chat-header">
      <div class="solar-chat-header-info">
        <div class="solar-chat-avatar">
          <span>☀️</span>
          <span class="solar-chat-status-dot" title="En ligne"></span>
        </div>
        <div>
          <h3 id="solar-chat-title" class="solar-chat-title">SolarBot</h3>
          <p class="solar-chat-subtitle">Assistant Emprunteur • Gemini IA</p>
        </div>
      </div>
      <div class="solar-chat-header-actions">
        <button type="button" id="solar-chat-minimize-btn" class="solar-chat-header-btn" aria-label="Fermer le chat">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        </button>
      </div>
    </div>

    <!-- Messages Container -->
    <div id="solar-chat-messages" class="solar-chat-messages">
      <!-- Welcome Message -->
      <div class="solar-msg solar-msg-bot">
        <div class="solar-msg-avatar">☀️</div>
        <div class="solar-msg-content">
          <p>Bonjour ! Je suis <strong>SolarBot</strong>, votre assistant officiel dédié exclusivement à l'espace <strong>Emprunteur</strong> de SolarShare. ⚡</p>
          <p>Je peux vous guider pour :</p>
          <ul>
            <li>🔍 Trouver du matériel sur la <strong>Marketplace</strong></li>
            <li>📅 Créer et gérer vos <strong>réservations &amp; cautions</strong></li>
            <li>📦 Réserver des <strong>Packs Solaires</strong> tout-en-un</li>
            <li>📍 Trouver un <strong>Point Relais / Hub</strong> pour le retrait</li>
            <li>📝 Réaliser vos <strong>états des lieux</strong> (check-in / check-out)</li>
          </ul>
        </div>
      </div>

      <!-- Quick Suggestion Chips -->
      <div id="solar-chat-suggestions" class="solar-chat-suggestions">
        <button type="button" class="solar-chip-btn" data-query="Comment réserver un équipement solaire ?">
          ⚡ Comment réserver un équipement ?
        </button>
        <button type="button" class="solar-chip-btn" data-query="Comment fonctionne l'état des lieux (check-in / check-out) ?">
          📝 Comment fonctionne l'état des lieux ?
        </button>
        <button type="button" class="solar-chip-btn" data-query="Qu'est-ce qu'un pack solaire clé-en-main ?">
          📦 Qu'est-ce qu'un pack solaire ?
        </button>
        <button type="button" class="solar-chip-btn" data-query="Comment choisir un hub de collecte pour le retrait ?">
          📍 Choisir un point relais ?
        </button>
      </div>
    </div>

    <!-- Typing Loader (hidden by default) -->
    <div id="solar-chat-typing" class="solar-chat-typing d-none">
      <div class="solar-msg-avatar">☀️</div>
      <div class="solar-typing-indicator">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>

    <!-- Footer Input Bar -->
    <div class="solar-chat-footer">
      <form id="solar-chat-form" class="solar-chat-form" autocomplete="off">
        <input 
          type="text" 
          id="solar-chat-input" 
          class="solar-chat-input" 
          placeholder="Posez votre question sur vos locations..." 
          maxlength="1000"
          required
        >
        <button type="submit" id="solar-chat-send-btn" class="solar-chat-send-btn" aria-label="Envoyer le message">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="22" y1="2" x2="11" y2="13"></line>
            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
          </svg>
        </button>
      </form>
      <div class="solar-chat-disclaimer">
        <span>Assistant IA réservé aux fonctionnalités Emprunteur • SolarShare</span>
      </div>
    </div>
  </div>
</div>

<style>
/* ==========================================================================
   SolarShare Renter Chatbot Styles (Solartec Theme Harmonized)
   ========================================================================== */
.solar-chatbot-container {
  position: fixed;
  bottom: 24px;
  right: 24px;
  z-index: 99999;
  font-family: 'DM Sans', system-ui, -apple-system, sans-serif;
}

/* Floating Toggle Button */
.solar-chat-toggle-btn {
  position: relative;
  width: 64px;
  height: 64px;
  border-radius: 50%;
  border: none;
  background: linear-gradient(135deg, #FFB020 0%, #FF6B3D 100%);
  color: #ffffff;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  box-shadow: 0 10px 25px -5px rgba(255, 107, 61, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.2);
  transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease;
}

.solar-chat-toggle-btn:hover {
  transform: scale(1.08) translateY(-2px);
  box-shadow: 0 14px 30px -5px rgba(255, 107, 61, 0.65), 0 0 0 2px rgba(255, 176, 32, 0.4);
}

.solar-chat-btn-inner {
  display: flex;
  align-items: center;
  justify-content: center;
}

.solar-chat-badge {
  position: absolute;
  top: -4px;
  right: -4px;
  background: #0F5C6B;
  color: #ffffff;
  font-size: 10px;
  font-weight: 800;
  padding: 3px 6px;
  border-radius: 12px;
  border: 2px solid #ffffff;
  letter-spacing: 0.5px;
}

.solar-chat-pulse-ring {
  position: absolute;
  top: -6px;
  left: -6px;
  right: -6px;
  bottom: -6px;
  border-radius: 50%;
  border: 2px solid rgba(255, 176, 32, 0.6);
  animation: solar-chat-pulse 2.5s infinite;
  pointer-events: none;
}

@keyframes solar-chat-pulse {
  0% { transform: scale(0.95); opacity: 0.8; }
  50% { transform: scale(1.18); opacity: 0; }
  100% { transform: scale(0.95); opacity: 0; }
}

/* Chat Window Panel */
.solar-chat-panel {
  position: fixed;
  bottom: 100px;
  right: 24px;
  width: 400px;
  max-width: calc(100vw - 32px);
  height: 580px;
  max-height: calc(100vh - 120px);
  background: #ffffff;
  border-radius: 20px;
  box-shadow: 0 20px 50px -10px rgba(16, 32, 43, 0.25), 0 0 0 1px rgba(16, 32, 43, 0.08);
  display: flex;
  flex-direction: column;
  overflow: hidden;
  animation: solar-panel-slide-up 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
  z-index: 99999;
}

@keyframes solar-panel-slide-up {
  from { opacity: 0; transform: translateY(20px) scale(0.96); }
  to { opacity: 1; transform: translateY(0) scale(1); }
}

/* Header */
.solar-chat-header {
  background: linear-gradient(135deg, #0F5C6B 0%, #154350 100%);
  color: #ffffff;
  padding: 1rem 1.25rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-bottom: 2px solid #FFB020;
}

.solar-chat-header-info {
  display: flex;
  align-items: center;
  gap: 12px;
}

.solar-chat-avatar {
  width: 42px;
  height: 42px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.15);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  position: relative;
  box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.3);
}

.solar-chat-status-dot {
  position: absolute;
  bottom: 1px;
  right: 1px;
  width: 10px;
  height: 10px;
  background: #10B981;
  border: 2px solid #0F5C6B;
  border-radius: 50%;
}

.solar-chat-title {
  font-family: 'Sora', sans-serif;
  font-size: 1.05rem;
  font-weight: 700;
  color: #ffffff;
  margin: 0;
  line-height: 1.2;
}

.solar-chat-subtitle {
  font-size: 0.75rem;
  color: #A5D2DC;
  margin: 0;
}

.solar-chat-header-btn {
  background: rgba(255, 255, 255, 0.12);
  border: none;
  color: #ffffff;
  width: 32px;
  height: 32px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: background 0.2s ease;
}

.solar-chat-header-btn:hover {
  background: rgba(255, 255, 255, 0.25);
}

/* Messages Area */
.solar-chat-messages {
  flex: 1;
  padding: 1.25rem;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  gap: 1rem;
  background: #FFFDF9;
}

.solar-msg {
  display: flex;
  gap: 10px;
  max-width: 90%;
  animation: solar-msg-fade 0.25s ease forwards;
}

@keyframes solar-msg-fade {
  from { opacity: 0; transform: translateY(6px); }
  to { opacity: 1; transform: translateY(0); }
}

.solar-msg-avatar {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: #FFE8CC;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 16px;
  flex-shrink: 0;
}

.solar-msg-content {
  padding: 0.85rem 1.1rem;
  border-radius: 16px;
  font-size: 0.88rem;
  line-height: 1.5;
}

.solar-msg-bot .solar-msg-content {
  background: #ffffff;
  color: #10202B;
  border: 1px solid rgba(16, 32, 43, 0.08);
  border-top-left-radius: 4px;
  box-shadow: 0 4px 12px rgba(16, 32, 43, 0.04);
}

.solar-msg-bot .solar-msg-content p:last-child {
  margin-bottom: 0;
}

.solar-msg-bot .solar-msg-content ul, 
.solar-msg-bot .solar-msg-content ol {
  margin-bottom: 0.5rem;
  padding-left: 1.25rem;
}

.solar-msg-bot .solar-msg-content strong {
  color: #0F5C6B;
}

.solar-msg-user {
  align-self: flex-end;
  flex-direction: row-reverse;
}

.solar-msg-user .solar-msg-content {
  background: linear-gradient(135deg, #0F5C6B 0%, #154350 100%);
  color: #ffffff;
  border-top-right-radius: 4px;
  box-shadow: 0 4px 14px rgba(15, 92, 107, 0.25);
}

/* Quick Suggestion Chips */
.solar-chat-suggestions {
  display: flex;
  flex-direction: column;
  gap: 6px;
  margin-top: 0.25rem;
  padding-left: 42px;
}

.solar-chip-btn {
  background: #ffffff;
  border: 1px solid rgba(255, 176, 32, 0.4);
  color: #0F5C6B;
  font-size: 0.8rem;
  font-weight: 600;
  padding: 6px 12px;
  border-radius: 20px;
  text-align: left;
  cursor: pointer;
  transition: all 0.2s ease;
  box-shadow: 0 2px 6px rgba(255, 176, 32, 0.08);
}

.solar-chip-btn:hover {
  background: #FFF8EB;
  border-color: #FFB020;
  transform: translateX(3px);
  color: #C2410C;
}

/* Typing Indicator */
.solar-chat-typing {
  display: flex;
  gap: 10px;
  padding: 0 1.25rem 0.75rem 1.25rem;
}

.solar-typing-indicator {
  display: flex;
  align-items: center;
  gap: 4px;
  padding: 8px 14px;
  background: #ffffff;
  border: 1px solid rgba(16, 32, 43, 0.08);
  border-radius: 16px;
  box-shadow: 0 4px 12px rgba(16, 32, 43, 0.04);
}

.solar-typing-indicator span {
  width: 6px;
  height: 6px;
  background: #FFB020;
  border-radius: 50%;
  animation: solar-typing 1.4s infinite ease-in-out both;
}

.solar-typing-indicator span:nth-child(1) { animation-delay: -0.32s; }
.solar-typing-indicator span:nth-child(2) { animation-delay: -0.16s; }

@keyframes solar-typing {
  0%, 80%, 100% { transform: scale(0); opacity: 0.3; }
  40% { transform: scale(1); opacity: 1; }
}

/* Footer & Form */
.solar-chat-footer {
  padding: 0.85rem 1.25rem;
  background: #ffffff;
  border-top: 1px solid rgba(16, 32, 43, 0.08);
}

.solar-chat-form {
  display: flex;
  gap: 8px;
}

.solar-chat-input {
  flex: 1;
  border: 1px solid rgba(16, 32, 43, 0.15);
  border-radius: 25px;
  padding: 0.65rem 1.1rem;
  font-size: 0.88rem;
  outline: none;
  transition: border-color 0.2s ease, box-shadow 0.2s ease;
  background: #F8FAFC;
}

.solar-chat-input:focus {
  border-color: #FFB020;
  background: #ffffff;
  box-shadow: 0 0 0 3px rgba(255, 176, 32, 0.2);
}

.solar-chat-send-btn {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: linear-gradient(135deg, #FFB020 0%, #FF6B3D 100%);
  color: #ffffff;
  border: none;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  flex-shrink: 0;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.solar-chat-send-btn:hover {
  transform: scale(1.06);
  box-shadow: 0 4px 12px rgba(255, 107, 61, 0.4);
}

.solar-chat-send-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  transform: none;
}

.solar-chat-disclaimer {
  text-align: center;
  font-size: 0.68rem;
  color: #94A3B8;
  margin-top: 6px;
}

/* Mobile Optimizations */
@media (max-width: 576px) {
  .solar-chat-panel {
    bottom: 0;
    right: 0;
    left: 0;
    width: 100%;
    max-width: 100%;
    height: 100vh;
    max-height: 100vh;
    border-radius: 0;
  }
  .solar-chatbot-container {
    bottom: 16px;
    right: 16px;
  }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const toggleBtn = document.getElementById('solar-chat-toggle-btn');
  const panel = document.getElementById('solar-chat-panel');
  const minimizeBtn = document.getElementById('solar-chat-minimize-btn');
  const form = document.getElementById('solar-chat-form');
  const input = document.getElementById('solar-chat-input');
  const messagesBox = document.getElementById('solar-chat-messages');
  const typingIndicator = document.getElementById('solar-chat-typing');
  const sendBtn = document.getElementById('solar-chat-send-btn');
  const iconOpen = toggleBtn.querySelector('.solar-chat-icon-open');
  const iconClose = toggleBtn.querySelector('.solar-chat-icon-close');

  let history = [];

  // Toggle open/close
  function toggleChat() {
    const isClosed = panel.classList.contains('d-none');
    if (isClosed) {
      panel.classList.remove('d-none');
      iconOpen.classList.add('d-none');
      iconClose.classList.remove('d-none');
      input.focus();
    } else {
      panel.classList.add('d-none');
      iconOpen.classList.remove('d-none');
      iconClose.classList.add('d-none');
    }
  }

  toggleBtn.addEventListener('click', toggleChat);
  minimizeBtn.addEventListener('click', toggleChat);

  // Quick Chips
  document.querySelectorAll('.solar-chip-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const query = btn.dataset.query;
      input.value = query;
      form.dispatchEvent(new Event('submit', { cancelable: true }));
      // Hide chips after first use
      const chipsContainer = document.getElementById('solar-chat-suggestions');
      if (chipsContainer) chipsContainer.style.display = 'none';
    });
  });

  // Simple Markdown parser for bold, bullets, links
  function formatReply(text) {
    if (!text) return '';
    let escaped = text
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;');

    // Bold **text**
    escaped = escaped.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
    
    // Lists * item
    escaped = escaped.replace(/(?:^|\n)\* (.*?)(?=\n|$)/g, '<li>$1</li>');
    escaped = escaped.replace(/(?:^|\n)\- (.*?)(?=\n|$)/g, '<li>$1</li>');
    escaped = escaped.replace(/(<li>.*?<\/li>)/g, '<ul>$1</ul>');
    escaped = escaped.replace(/<\/ul>\s*<ul>/g, '');

    // Line breaks
    escaped = escaped.replace(/\n\n/g, '<br><br>').replace(/\n/g, '<br>');

    return escaped;
  }

  function appendMessage(role, text) {
    const msgDiv = document.createElement('div');
    msgDiv.className = `solar-msg solar-msg-${role === 'user' ? 'user' : 'bot'}`;

    if (role === 'bot') {
      msgDiv.innerHTML = `
        <div class="solar-msg-avatar">☀️</div>
        <div class="solar-msg-content">${formatReply(text)}</div>
      `;
    } else {
      msgDiv.innerHTML = `
        <div class="solar-msg-content">${text.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')}</div>
      `;
    }

    messagesBox.appendChild(msgDiv);
    messagesBox.scrollTop = messagesBox.scrollHeight;
  }

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const message = input.value.trim();
    if (!message) return;

    // Append user message
    appendMessage('user', message);
    input.value = '';
    sendBtn.disabled = true;

    // Show typing
    typingIndicator.classList.remove('d-none');
    messagesBox.scrollTop = messagesBox.scrollHeight;

    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    try {
      const response = await fetch("{{ route('renter.chat') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': token || ''
        },
        body: JSON.stringify({
          message: message,
          history: history.slice(-6) // keep recent turns
        })
      });

      const data = await response.json();
      typingIndicator.classList.add('d-none');

      if (response.ok && data.status === 'success') {
        appendMessage('bot', data.reply);
        history.push({ role: 'user', text: message });
        history.push({ role: 'model', text: data.reply });
      } else {
        appendMessage('bot', data.message || "Désolé, je rencontre une petite difficulté pour répondre. Veuillez réessayer.");
      }
    } catch (err) {
      typingIndicator.classList.add('d-none');
      appendMessage('bot', "Désolé, une erreur réseau est survenue. Veuillez vérifier votre connexion.");
    } finally {
      sendBtn.disabled = false;
      input.focus();
    }
  });
});
</script>
