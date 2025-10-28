@extends('layouts.app')

@section('title', 'IA Gaúcha - Chat')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="bbq-gradient w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-robot text-white text-3xl"></i>
        </div>
        <h1 class="text-4xl font-bold bbq-text-gradient mb-4">IA Gaúcha</h1>
        <p class="text-lg text-gray-600">Converse com nossa especialista em churrasco que fala com sotaque gaúcho!</p>
    </div>

    <!-- Chat Container -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Chat Header -->
        <div class="bbq-gradient p-4 text-white">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-robot text-white"></i>
                </div>
                <div>
                    <h3 class="font-semibold">Especialista Gaúcha</h3>
                    <p class="text-sm text-yellow-200">Online • Pronta para ajudar</p>
                </div>
            </div>
        </div>

        <!-- Chat Messages -->
        <div id="chat-messages" class="h-96 overflow-y-auto p-6 space-y-4">
            @if($recentMessages->count() > 0)
                @foreach($recentMessages as $message)
                    @if($message->is_ai_message)
                        <!-- AI Message -->
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bbq-gradient rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-robot text-white text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="bg-gray-100 rounded-lg p-3">
                                    <div class="text-gray-800 whitespace-pre-line">{{ $message->response }}</div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">{{ $message->created_at->format('H:i') }}</p>
                            </div>
                        </div>
                    @else
                        <!-- User Message -->
                        <div class="flex items-start space-x-3 justify-end">
                            <div class="flex-1 max-w-xs">
                                <div class="bg-brown-600 text-white rounded-lg p-3">
                                    <p>{{ $message->message }}</p>
                                </div>
                                <p class="text-xs text-gray-500 mt-1 text-right">{{ $message->created_at->format('H:i') }}</p>
                            </div>
                            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user text-gray-600 text-sm"></i>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <!-- Welcome Message -->
                <div class="text-center py-8">
                    <div class="bbq-gradient w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-hand-wave text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Oi, tchê!</h3>
                    <p class="text-gray-600 mb-4">Sou sua especialista em churrasco gaúcho! Pode perguntar qualquer coisa sobre:</p>
                    <div class="grid grid-cols-2 gap-2 text-sm text-gray-600">
                        <div><i class="fas fa-drumstick-bite mr-2"></i>Cortes de carne</div>
                        <div><i class="fas fa-fire mr-2"></i>Temperos e marinadas</div>
                        <div><i class="fas fa-thermometer-half mr-2"></i>Temperaturas</div>
                        <div><i class="fas fa-clock mr-2"></i>Tempo de cocção</div>
                        <div><i class="fas fa-tools mr-2"></i>Equipamentos</div>
                        <div><i class="fas fa-lightbulb mr-2"></i>Dicas e truques</div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Chat Input -->
        <div class="border-t p-4">
            <form id="chat-form" class="flex space-x-3">
                @csrf
                <input type="text" id="message-input" 
                       placeholder="Digite sua pergunta sobre churrasco..." 
                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brown-500"
                       maxlength="1000" required>
                <button type="submit" id="send-button" 
                        class="bg-brown-600 text-white px-6 py-2 rounded-lg hover:bg-brown-700 transition-colors disabled:opacity-50">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
            <p class="text-xs text-gray-500 mt-2">
                <i class="fas fa-info-circle mr-1"></i>
                A IA pode demorar alguns segundos para responder
            </p>
        </div>
    </div>

    <!-- Quick Questions -->
    <div class="mt-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Perguntas Rápidas</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <button class="quick-question bg-white border border-gray-300 rounded-lg p-3 text-left hover:bg-gray-50 transition-colors" 
                    data-question="Como fazer uma picanha perfeita?">
                <i class="fas fa-question-circle text-brown-600 mr-2"></i>
                Como fazer uma picanha perfeita?
            </button>
            <button class="quick-question bg-white border border-gray-300 rounded-lg p-3 text-left hover:bg-gray-50 transition-colors" 
                    data-question="Qual o melhor tempero para costela?">
                <i class="fas fa-question-circle text-brown-600 mr-2"></i>
                Qual o melhor tempero para costela?
            </button>
            <button class="quick-question bg-white border border-gray-300 rounded-lg p-3 text-left hover:bg-gray-50 transition-colors" 
                    data-question="Como calcular a quantidade de carne por pessoa?">
                <i class="fas fa-question-circle text-brown-600 mr-2"></i>
                Como calcular a quantidade de carne por pessoa?
            </button>
            <button class="quick-question bg-white border border-gray-300 rounded-lg p-3 text-left hover:bg-gray-50 transition-colors" 
                    data-question="Qual a temperatura ideal para grelhar?">
                <i class="fas fa-question-circle text-brown-600 mr-2"></i>
                Qual a temperatura ideal para grelhar?
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatForm = document.getElementById('chat-form');
    const messageInput = document.getElementById('message-input');
    const sendButton = document.getElementById('send-button');
    const chatMessages = document.getElementById('chat-messages');
    const quickQuestions = document.querySelectorAll('.quick-question');

    // Auto-scroll to bottom
    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Add message to chat
    function addMessage(message, isAI = false, audioUrl = null) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `flex items-start space-x-3 ${isAI ? '' : 'justify-end'}`;
        
        const time = new Date().toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
        
        if (isAI) {
            let audioControls = '';
            if (audioUrl) {
                audioControls = `
                    <div class="mt-2">
                        <audio controls class="w-full h-8">
                            <source src="${audioUrl}" type="audio/mpeg">
                            Seu navegador não suporta o elemento de áudio.
                        </audio>
                    </div>
                `;
            }
            
            messageDiv.innerHTML = `
                <div class="w-8 h-8 bbq-gradient rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-robot text-white text-sm"></i>
                </div>
                <div class="flex-1">
                    <div class="bg-gray-100 rounded-lg p-3">
                        <div class="text-gray-800 whitespace-pre-line">${message}</div>
                        ${audioControls}
                    </div>
                    <p class="text-xs text-gray-500 mt-1">${time}</p>
                </div>
            `;
        } else {
            messageDiv.innerHTML = `
                <div class="flex-1 max-w-xs">
                    <div class="bg-brown-600 text-white rounded-lg p-3">
                        <p>${message}</p>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 text-right">${time}</p>
                </div>
                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user text-gray-600 text-sm"></i>
                </div>
            `;
        }
        
        chatMessages.appendChild(messageDiv);
        scrollToBottom();
    }

    // Send message
    function sendMessage(message) {
        if (!message.trim()) return;

        // Add user message
        addMessage(message, false);
        
        // Disable input
        messageInput.disabled = true;
        sendButton.disabled = true;
        sendButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        // Send to server
        fetch('{{ route("recipes.chat.message") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add AI response with audio
                addMessage(data.ai_response.response, true, data.ai_response.audio_url);
            } else {
                addMessage('Desculpa, deu um probleminha aqui. Tenta de novo!', true);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            addMessage('Eita, deu um erro aqui. Mas não desiste não, tenta de novo!', true);
        })
        .finally(() => {
            // Re-enable input
            messageInput.disabled = false;
            sendButton.disabled = false;
            sendButton.innerHTML = '<i class="fas fa-paper-plane"></i>';
            messageInput.value = '';
            messageInput.focus();
        });
    }

    // Handle form submission
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const message = messageInput.value.trim();
        if (message) {
            sendMessage(message);
        }
    });

    // Handle quick questions
    quickQuestions.forEach(button => {
        button.addEventListener('click', function() {
            const question = this.dataset.question;
            messageInput.value = question;
            sendMessage(question);
        });
    });

    // Focus input on load
    messageInput.focus();
    
    // Scroll to bottom on load
    scrollToBottom();
});
</script>
@endsection
