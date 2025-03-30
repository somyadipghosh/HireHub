document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggleBtn');
    const chatBox = document.getElementById('chatBox');
    const userInput = document.getElementById('userInput');
    const sendBtn = document.getElementById('sendBtn');
    const chatHistory = document.getElementById('chatHistory');
    const newChatBtn = document.getElementById('newChatBtn');
    const deleteModal = document.getElementById('deleteModal');
    const modalClose = document.getElementById('modalClose');
    const cancelDelete = document.getElementById('cancelDelete');
    const confirmDelete = document.getElementById('confirmDelete');

    // App State
    let chatSessions = [];
    let currentSessionId = null;
    let sessionToDelete = null;

    // Initialize
    initApp();

    // Event Listeners
    toggleBtn.addEventListener('click', function(e) {
        e.stopPropagation(); // Prevent the document click event from firing
        toggleSidebar();
    });

    sendBtn.addEventListener('click', sendMessage);
    userInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });

    newChatBtn.addEventListener('click', startNewChat);

    // Modal events
    modalClose.addEventListener('click', closeModal);
    cancelDelete.addEventListener('click', closeModal);
    confirmDelete.addEventListener('click', confirmDeleteChat);

    // Close sidebar when clicking outside
    document.addEventListener('click', function(event) {
        // Only apply this behavior on mobile screens
        if (window.innerWidth <= 768) {
            // Check if sidebar is active and the click is outside both the sidebar and toggle button
            if (sidebar.classList.contains('active') &&
                !sidebar.contains(event.target) &&
                !toggleBtn.contains(event.target)) {

                // Close the sidebar
                sidebar.classList.remove('active');

                // Update the icon
                const openIcon = document.querySelector('.open-icon');
                const closeIcon = document.querySelector('.close-icon');

                if (openIcon && closeIcon) {
                    openIcon.style.display = 'block';
                    closeIcon.style.display = 'none';
                }

                // Remove the body class
                document.body.classList.remove('sidebar-open');
            }
        }
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        // Reset sidebar state when transitioning to desktop view
        if (window.innerWidth > 768) {
            sidebar.classList.remove('active');
            document.body.classList.remove('sidebar-open');

            // Reset icon
            const openIcon = document.querySelector('.open-icon');
            const closeIcon = document.querySelector('.close-icon');

            if (openIcon && closeIcon) {
                openIcon.style.display = 'block';
                closeIcon.style.display = 'none';
            }
        }
    });

    // Functions
    function initApp() {
        // Check for saved chats in localStorage
        const savedChats = localStorage.getItem('chatSessions');
        if (savedChats) {
            chatSessions = JSON.parse(savedChats);
            renderChatHistory();
        }

        // Start a new chat if none exists
        if (chatSessions.length === 0) {
            startNewChat();
        } else {
            loadChatSession(chatSessions.length - 1);
        }

        // Focus on input
        userInput.focus();
    }

    function toggleSidebar() {
        sidebar.classList.toggle('active');

        // Toggle the icon
        const openIcon = document.querySelector('.open-icon');
        const closeIcon = document.querySelector('.close-icon');

        if (sidebar.classList.contains('active')) {
            openIcon.style.display = 'none';
            closeIcon.style.display = 'block';
            document.body.classList.add('sidebar-open');
        } else {
            openIcon.style.display = 'block';
            closeIcon.style.display = 'none';
            document.body.classList.remove('sidebar-open');
        }
    }

    function getCurrentTime() {
        const now = new Date();
        return now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    function sendMessage() {
        const message = userInput.value.trim();
        if (!message) return;

        // Add user message
        addMessageToChat('user', message);

        // Clear input
        userInput.value = '';

        // Simulate bot response (with typing indicator)
        simulateBotResponse(message);

        // Save the updated chat
        saveChatSessions();
    }

    function addMessageToChat(sender, text) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${sender}-message`;
        messageDiv.innerHTML = `
            ${text}
            <div class="message-time">${getCurrentTime()}</div>
        `;
        chatBox.appendChild(messageDiv);

        // Save to current session
        if (currentSessionId !== null) {
            if (!chatSessions[currentSessionId].messages) {
                chatSessions[currentSessionId].messages = [];
            }

            chatSessions[currentSessionId].messages.push({
                sender: sender,
                text: text,
                time: getCurrentTime()
            });

            // Update title with first user message if it's the first one
            if (sender === 'user' && chatSessions[currentSessionId].messages.filter(m => m.sender === 'user').length === 1) {
                const title = text.substring(0, 20) + (text.length > 20 ? '...' : '');
                chatSessions[currentSessionId].title = title;
                renderChatHistory();
            }
        }

        // Scroll to bottom
        scrollToBottom();
    }

    function simulateBotResponse(userMessage) {
        // Show typing indicator
        const typingDiv = document.createElement('div');
        typingDiv.className = 'message bot-message';
        typingDiv.innerHTML = 'Typing...';
        chatBox.appendChild(typingDiv);
        scrollToBottom();

        // Get conversation history for context (last 6 messages maximum)
        let conversationHistory = [];
        if (currentSessionId !== null && chatSessions[currentSessionId].messages) {
            // Get the last 6 messages (3 exchanges) excluding the most recent user message
            const recentMessages = chatSessions[currentSessionId].messages.slice(-7, -1);
            conversationHistory = recentMessages.map(msg => ({
                role: msg.sender === 'user' ? 'user' : 'assistant',
                content: msg.text
            }));
        }

        // Use absolute path for reliability
        const apiUrl = '/binaryhackathon/chatbot/openai-handler.php';
        
        fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ 
                message: userMessage,
                history: conversationHistory
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Server responded with status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            // Remove typing indicator
            chatBox.removeChild(typingDiv);
            
            if (data.error) {
                // Handle error
                addMessageToChat('bot', `${data.error}`);
            } else {
                // Format and display the response
                const formattedResponse = formatBotResponse(data.response);
                addMessageToChat('bot', formattedResponse);
            }
        })
        .catch(error => {
            // Remove typing indicator
            chatBox.removeChild(typingDiv);
            
            // Show user-friendly error message
            addMessageToChat('bot', 'Sorry, I\'m having trouble connecting right now. Please try again in a moment.');
            
            // Log the error for debugging
            console.error('Error:', error);
        });
    }

    // Enhanced formatting for better interview content display
    function formatBotResponse(text) {
        // Convert markdown-like syntax to HTML
        
        // Bold text: **text** -> <strong>text</strong>
        text = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        
        // Italic text: *text* -> <em>text</em>
        text = text.replace(/\*(.*?)\*/g, '<em>$1</em>');
        
        // Code blocks: ```...``` -> <pre><code>...</code></pre>
        text = text.replace(/```([\s\S]*?)```/g, '<pre><code>$1</code></pre>');
        
        // Inline code: `code` -> <code>code</code>
        text = text.replace(/`([^`]+)`/g, '<code>$1</code>');
        
        // Numbered lists: 1. text -> <ol><li>text</li></ol>
        let listMatch = text.match(/^\d+\.\s+.*$/gm);
        if (listMatch) {
            let listItems = listMatch.map(item => item.replace(/^\d+\.\s+(.*)$/, '<li>$1</li>'));
            text = text.replace(/(?:^\d+\.\s+.*$\n?)+/gm, '<ol>' + listItems.join('') + '</ol>');
        }
        
        // Bullet lists: - text -> <ul><li>text</li></ul>
        let bulletMatch = text.match(/^[*-]\s+.*$/gm);
        if (bulletMatch) {
            let bulletItems = bulletMatch.map(item => item.replace(/^[*-]\s+(.*)$/, '<li>$1</li>'));
            text = text.replace(/(?:^[*-]\s+.*$\n?)+/gm, '<ul>' + bulletItems.join('') + '</ul>');
        }
        
        // Convert line breaks to <br>
        text = text.replace(/\n/g, '<br>');
        
        return text;
    }

    function startNewChat() {
        // Create new chat session
        const sessionId = chatSessions.length;
        const newSession = {
            id: sessionId,
            title: `New Chat`,
            timestamp: new Date().toISOString(),
            messages: []
        };

        chatSessions.push(newSession);
        currentSessionId = sessionId;

        // Clear current chat
        chatBox.innerHTML = '';

        // Update chat history in sidebar
        renderChatHistory();

        // Save to localStorage
        saveChatSessions();

        // Focus on input
        userInput.focus();
    }

    function loadChatSession(sessionId) {
        if (sessionId >= 0 && sessionId < chatSessions.length) {
            currentSessionId = sessionId;
            const session = chatSessions[sessionId];

            // Clear current chat
            chatBox.innerHTML = '';

            // Render messages
            if (session.messages && session.messages.length) {
                session.messages.forEach(msg => {
                    const messageDiv = document.createElement('div');
                    messageDiv.className = `message ${msg.sender}-message`;
                    messageDiv.innerHTML = `
                        ${msg.text}
                        <div class="message-time">${msg.time || 'Unknown'}</div>
                    `;
                    chatBox.appendChild(messageDiv);
                });
            } else {
                // Add welcome message for empty chats
                addMessageToChat('bot', 'Hello! How can I assist you today?');
            }

            // Scroll to bottom
            scrollToBottom();

            // Update active state in sidebar
            highlightActiveChat(sessionId);
        }
    }

    function renderChatHistory() {
        chatHistory.innerHTML = '';

        if (chatSessions.length === 0) {
            const emptyState = document.createElement('div');
            emptyState.className = 'empty-state';
            emptyState.innerHTML = `
                <div class="empty-state-icon">💬</div>
                <div class="empty-state-title">No chats yet</div>
                <div class="empty-state-text">Start a new conversation by clicking the "New Chat" button above.</div>
            `;
            chatHistory.appendChild(emptyState);
            return;
        }

        chatSessions.forEach((session, index) => {
            const chatItem = document.createElement('li');
            chatItem.setAttribute('data-session-id', index);

            chatItem.innerHTML = `
                <div class="chat-item-content">
                    <span class="chat-icon">💬</span>
                    ${session.title}
                </div>
                <button class="delete-chat-btn" data-session-id="${index}">×</button>
            `;

            // Chat item click event
            chatItem.querySelector('.chat-item-content').addEventListener('click', () => {
                loadChatSession(index);
            });

            // Delete button click event
            chatItem.querySelector('.delete-chat-btn').addEventListener('click', (e) => {
                e.stopPropagation(); // Prevent triggering the parent click event
                showDeleteModal(index);
            });

            if (index === currentSessionId) {
                chatItem.style.backgroundColor = 'rgba(88, 101, 242, 0.2)';
            }

            chatHistory.appendChild(chatItem);
        });
    }

    function highlightActiveChat(sessionId) {
        const chatItems = chatHistory.querySelectorAll('li');
        chatItems.forEach(item => {
            if (parseInt(item.getAttribute('data-session-id')) === sessionId) {
                item.style.backgroundColor = 'rgba(88, 101, 242, 0.2)';
            } else {
                item.style.backgroundColor = '';
            }
        });
    }

    function showDeleteModal(sessionId) {
        sessionToDelete = sessionId;
        deleteModal.style.display = 'flex';
    }

    function closeModal() {
        deleteModal.style.display = 'none';
        sessionToDelete = null;
    }

    function confirmDeleteChat() {
        if (sessionToDelete !== null) {
            // Remove the chat session
            chatSessions.splice(sessionToDelete, 1);

            // Update IDs for remaining sessions
            chatSessions.forEach((session, index) => {
                session.id = index;
            });

            // If the deleted session was the current one, load another session
            if (sessionToDelete === currentSessionId) {
                if (chatSessions.length > 0) {
                    // Load the previous chat or the first one if the deleted was the first
                    const newIndex = Math.min(sessionToDelete, chatSessions.length - 1);
                    loadChatSession(newIndex);
                } else {
                    // Start a new chat if no chats remain
                    startNewChat();
                }
            } else if (currentSessionId > sessionToDelete) {
                // If current session was after the deleted one, adjust its index
                currentSessionId--;
            }

            // Save and update UI
            saveChatSessions();
            renderChatHistory();
            closeModal();
        }
    }

    function saveChatSessions() {
        localStorage.setItem('chatSessions', JSON.stringify(chatSessions));
    }

    function scrollToBottom() {
        chatBox.scrollTop = chatBox.scrollHeight;
    }
});