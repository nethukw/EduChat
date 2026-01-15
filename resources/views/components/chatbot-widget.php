<button onclick="toggleChat()" 
        class="fixed bottom-6 right-6 bg-indigo-600 text-white w-16 h-16 rounded-full shadow-2xl hover:bg-indigo-700 transition transform hover:scale-110 z-50 flex items-center justify-center border-4 border-white dark:border-slate-700">
    <i class="fas fa-robot text-2xl"></i>
    <span class="absolute top-1 right-1 bg-green-500 w-4 h-4 rounded-full border-2 border-white"></span>
</button>

<div id="chat-container" 
     class="fixed bottom-24 right-6 w-[350px] md:w-96 bg-white dark:bg-slate-800 shadow-2xl rounded-[2.5rem] overflow-hidden hidden z-50 border border-gray-100 dark:border-slate-700 transition-all duration-300 transform scale-95 origin-bottom-right">
    
    <div class="bg-indigo-600 p-5 text-white flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-brain text-xl"></i>
            </div>
            <div>
                <h4 class="font-black text-sm uppercase tracking-tighter">EduBot AI 2.0</h4>
                <p class="text-[10px] opacity-80 uppercase font-bold tracking-widest">Always Online</p>
            </div>
        </div>
        <button onclick="toggleChat()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/10 transition">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <div id="chat-box-display" class="h-96 overflow-y-auto p-5 space-y-4 bg-gray-50 dark:bg-slate-900 scrollbar-hide">
        <div class="flex justify-start">
            <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl rounded-tl-none text-sm text-gray-600 dark:text-gray-300 shadow-sm border border-gray-100 dark:border-slate-700 max-w-[85%]">
                <p class="font-bold text-indigo-600 text-[10px] uppercase mb-1">EduBot</p>
                Hello! I'm your AI study partner. How can I help you today?
            </div>
        </div>
    </div>

    <div class="p-4 bg-white dark:bg-slate-800 border-t dark:border-slate-700">
        <div class="relative flex items-center gap-2">
            <input type="text" id="bot-user-input" onkeypress="handleBotKey(event)" 
                   placeholder="Ask me anything..." 
                   class="w-full pl-4 pr-12 py-3 bg-gray-100 dark:bg-slate-900 border-none rounded-2xl dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 shadow-inner">
            
            <button onclick="sendBotMessage()" 
                    class="absolute right-2 bg-indigo-600 text-white w-10 h-10 rounded-xl flex items-center justify-center hover:bg-indigo-700 transition active:scale-90 shadow-lg">
                <i class="fas fa-paper-plane text-sm"></i>
            </button>
        </div>
    </div>
</div>



<script>
    function toggleChat() {
        const container = document.getElementById('chat-container');
        if (container.classList.contains('hidden')) {
            container.classList.remove('hidden');
            setTimeout(() => {
                container.classList.remove('scale-95', 'opacity-0');
                container.classList.add('scale-100', 'opacity-100');
            }, 10);
            document.getElementById('bot-user-input').focus();
        } else {
            container.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                container.classList.add('hidden');
            }, 300);
        }
    }

    function handleBotKey(event) {
        if (event.key === 'Enter') {
            sendBotMessage();
        }
    }

    async function sendBotMessage() {
        const input = document.getElementById('bot-user-input');
        const chatBox = document.getElementById('chat-box-display');
        const message = input.value.trim();

        if (!message) return;

        chatBox.innerHTML += `
            <div class="flex justify-end animate-fade-in">
                <div class="bg-indigo-600 text-white px-4 py-3 rounded-2xl rounded-tr-none text-sm max-w-[85%] shadow-md">
                    ${message}
                </div>
            </div>`;
        
        input.value = '';
        chatBox.scrollTop = chatBox.scrollHeight;

        const loadingId = "bot-loading-" + Date.now();
        chatBox.innerHTML += `
            <div id="${loadingId}" class="flex justify-start animate-pulse">
                <div class="bg-gray-200 dark:bg-slate-700 text-gray-500 px-4 py-2 rounded-2xl rounded-tl-none text-xs font-bold uppercase tracking-widest border border-gray-100">
                    Bot is thinking...
                </div>
            </div>`;
        chatBox.scrollTop = chatBox.scrollHeight;

        try {
            const response = await fetch("{{ route('chatbot.ask') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ message: message })
            });

            const data = await response.json();
            document.getElementById(loadingId).remove();

            chatBox.innerHTML += `
                <div class="flex justify-start animate-fade-in">
                    <div class="bg-white dark:bg-slate-800 dark:text-gray-200 px-4 py-3 rounded-2xl rounded-tl-none text-sm max-w-[85%] shadow-sm border border-gray-100 dark:border-slate-700">
                        <p class="font-bold text-indigo-600 text-[10px] uppercase mb-1">EduBot</p>
                        ${data.answer}
                    </div>
                </div>`;
        } catch (error) {
            document.getElementById(loadingId).remove();
            chatBox.innerHTML += `<div class="text-center p-2 text-red-500 text-[10px] font-bold">Connection Error</div>`;
        }
        
        chatBox.scrollTop = chatBox.scrollHeight;
    }
</script>

<style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fade-in 0.3s ease-out forwards; }
</style>