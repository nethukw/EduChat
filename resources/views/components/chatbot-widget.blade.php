<!-- EduBot AI Chat Widget - Enhanced for General Knowledge & History -->
<div id="edubot-container" class="fixed bottom-6 right-6 z-50">
    <!-- Chat Toggle Button (Robot Icon) -->
    <button id="edubot-toggle" class="bg-indigo-600 hover:bg-indigo-700 text-white w-14 h-14 rounded-full shadow-2xl flex items-center justify-center transition-transform hover:scale-110 active:scale-95 border-4 border-white dark:border-slate-800">
        <i class="fas fa-robot text-2xl"></i>
    </button>

    <!-- Chat Window Interface -->
    <div id="edubot-window" class="hidden absolute bottom-20 right-0 w-80 md:w-96 bg-white dark:bg-slate-800 rounded-[2rem] shadow-2xl border border-slate-100 dark:border-slate-700 overflow-hidden flex flex-col animate-fade-in">
        
        <!-- Header Section -->
        <div class="bg-indigo-600 p-6 text-white flex justify-between items-center shadow-lg">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-brain"></i>
                </div>
                <div>
                    <h4 class="font-black uppercase tracking-tighter leading-none">EduBot AI</h4>
                    <span class="text-[10px] font-bold opacity-75 uppercase tracking-widest">Global Support Active</span>
                </div>
            </div>
            <button id="edubot-close" class="text-white/50 hover:text-white transition">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Messages Area (Scrollable) -->
        <div id="edubot-messages" class="h-80 overflow-y-auto p-6 space-y-4 bg-slate-50 dark:bg-slate-900/50 flex flex-col no-scrollbar">
            <!-- Welcome Message -->
            <div class="flex justify-start">
                <div class="max-w-[85%] p-4 rounded-2xl rounded-tl-none shadow-sm border border-slate-100 dark:border-slate-700 text-sm bg-white dark:bg-slate-800 dark:text-gray-200">
                    Hello! I'm EduBot. You can ask me anything about the platform or any general educational questions. How can I help you today? 😊
                </div>
            </div>
        </div>

        <!-- User Input Area -->
        <div class="p-4 bg-white dark:bg-slate-800 border-t dark:border-slate-700">
            <form id="edubot-form" class="flex gap-2">
                <input type="text" id="edubot-input" placeholder="Ask me anything..." 
                       class="flex-1 bg-slate-100 dark:bg-slate-900 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 dark:text-white placeholder:text-gray-400">
                <button type="submit" class="bg-indigo-600 text-white w-12 rounded-xl hover:bg-indigo-700 transition flex items-center justify-center">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    const edubotToggle = document.getElementById('edubot-toggle');
    const edubotWindow = document.getElementById('edubot-window');
    const edubotClose = document.getElementById('edubot-close');
    const edubotForm = document.getElementById('edubot-form');
    const edubotInput = document.getElementById('edubot-input');
    const edubotMessages = document.getElementById('edubot-messages');

    // UI State Management
    edubotToggle.onclick = () => edubotWindow.classList.toggle('hidden');
    edubotClose.onclick = () => edubotWindow.classList.add('hidden');

    // Chat History to maintain context
    let chatHistory = [];

    // Gemini API Setup

const apiKey = "YOUR_EXPOSED_API_KEY";

    edubotForm.onsubmit = async (e) => {
        e.preventDefault();
        const message = edubotInput.value.trim();
        if (!message) return;

        // Display user message in UI
        appendMessage('User', message);
        edubotInput.value = '';

        // Add user message to history
        chatHistory.push({ role: "user", parts: [{ text: message }] });

        // Show loading state
        const loadingId = appendMessage('EduBot', '<i class="fas fa-spinner fa-spin mr-2"></i> Thinking...');

        try {
            // Fetch initial instructions from backend (context)
            const res = await fetch("{{ route('chatbot.ask') }}", {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: message })
            });
            
            const configData = await res.json();
            
            // Call Gemini API with history and broad instructions
            const geminiRes = await fetch(`https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-preview-09-2025:generateContent?key=${apiKey}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    contents: chatHistory,
                    systemInstruction: { 
                        parts: [{ 
                            text: configData.system_instruction + " You are now updated to answer any question across any domain (educational, technical, or general) while maintaining your identity as EduBot. Always respond in English." 
                        }] 
                    }
                })
            });

            const result = await geminiRes.json();
            const botResponse = result.candidates?.[0]?.content?.parts?.[0]?.text || "I'm sorry, I'm having trouble understanding that right now. Could you rephrase?";
            
            // Add bot response to history
            chatHistory.push({ role: "model", parts: [{ text: botResponse }] });

            // Limit history size to keep performance stable
            if (chatHistory.length > 10) chatHistory.shift();

            // Update UI with actual response
            updateMessage(loadingId, botResponse);
        } catch (error) {
            updateMessage(loadingId, "I encountered a connection error. Please try again later.");
            console.error("EduBot AI Error:", error);
        }
    };

    /**
     * පණිවිඩයක් චැට් වින්ඩෝ එකට එක් කිරීම (Adds message bubble to UI)
     */
    function appendMessage(sender, text) {
        const id = Date.now();
        const isBot = sender === 'EduBot';
        const html = `
            <div id="msg-${id}" class="flex ${isBot ? 'justify-start' : 'justify-end'} animate-slide-up">
                <div class="max-w-[85%] p-4 rounded-2xl shadow-sm text-sm ${
                    isBot 
                    ? 'bg-white dark:bg-slate-800 rounded-tl-none border border-slate-100 dark:border-slate-700 dark:text-gray-200' 
                    : 'bg-indigo-600 text-white rounded-tr-none'
                }">
                    ${text}
                </div>
            </div>
        `;
        edubotMessages.insertAdjacentHTML('beforeend', html);
        edubotMessages.scrollTop = edubotMessages.scrollHeight;
        return id;
    }

    /**
     * දැනට පවතින පණිවිඩයක් යාවත්කාලීන කිරීම (Updates existing loading bubble)
     */
    function updateMessage(id, text) {
        const msgDiv = document.querySelector(`#msg-${id} div`);
        if (msgDiv) msgDiv.innerHTML = text;
        edubotMessages.scrollTop = edubotMessages.scrollHeight;
    }
</script>

<style>
    /* සුමට සජීවීකරණ (Smooth transition animations) */
    @keyframes fade-in { 
        from { opacity: 0; transform: translateY(20px); } 
        to { opacity: 1; transform: translateY(0); } 
    }
    @keyframes slide-up { 
        from { opacity: 0; transform: translateY(10px); } 
        to { opacity: 1; transform: translateY(0); } 
    }
    .animate-fade-in { animation: fade-in 0.4s ease-out forwards; }
    .animate-slide-up { animation: slide-up 0.3s ease-out forwards; }

    /* Scrollbar එක සැඟවීම (Hiding scrollbar) */
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>