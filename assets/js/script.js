async function sendMessage() {
    const messageInput = document.getElementById('message');
    const message = messageInput.value.trim();
    if (!message) return;

    const chat = document.getElementById('chat');
    chat.innerHTML += `<div class="chat-message user-message"><strong>You:</strong> ${message}</div>`;

    const loadingIndicator = document.getElementById('loading');
    loadingIndicator.style.display = 'block';

    try {
        const response = await fetch('', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({ message })
        });

        const data = await response.json();
        loadingIndicator.style.display = 'none';

        const formattedResponse = data.response.split(/```([a-zA-Z]*)\s*([\s\S]*?)```/).map((part, index) => {
            if (index % 3 === 0) {
                return part.replace(/\n/g, '<br>');
            } else if (index % 3 === 1) {
                const language = part.charAt(0).toUpperCase() + part.slice(1);
                return `<div class="code-card"><div class="card-header">${language} Code</div>`;
            } else {
                const code = part.trim();
                const uniqueId = `code-${index}`;

                return `<div class="card-body"><pre id="${uniqueId}"><code>${code}</code></pre></div>
                            <button class="copy-btn" onclick="copyToClipboard('${uniqueId}')">Copy Code</button>
                        </div>`;
            }
        }).join('');

        chat.innerHTML += `<div class="chat-message bot-message"><strong>Bot:</strong> <span>${formattedResponse}</span></div>`;
    } catch (error) {
        console.error('Error:', error);
        loadingIndicator.style.display = 'none';
        chat.innerHTML += `<div class="chat-message bot-message"><strong>Bot:</strong> Sorry, there was an error.</div>`;
    } finally {
        messageInput.value = '';
        chat.scrollTop = chat.scrollHeight;
    }
}

function copyToClipboard(elementId) {
    const codeElement = document.getElementById(elementId);
    const codeText = codeElement.textContent; // Get raw code text
    navigator.clipboard.writeText(codeText).then(() => {
        alert('Code copied to clipboard!');
    }).catch(err => {
        alert('Could not copy text: ', err);
    });
}



function checkEnter(event) {
    if (event.key === 'Enter') {
        sendMessage();
    }
}
document.addEventListener('DOMContentLoaded', function() {
    const content = document.querySelector('.content');
    const sidebar = document.getElementById('sidebar');
    const toggleButton = document.getElementById('toggleSidebar');

    toggleButton.addEventListener('click', function() {
        sidebar.classList.toggle('hidden');

        if (sidebar.classList.contains('hidden')) {
            content.style.marginLeft = '0';
        } else {
            content.style.marginLeft = '250px';
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const content = document.querySelector('.content');
    const sidebar = document.getElementById('sidebar');
    
    function handleResize() {
        if (window.innerWidth <= 768) {
            sidebar.classList.remove('show');
            content.style.marginLeft = '0';
        } else {
            sidebar.classList.add('show');
            content.style.marginLeft = '250px';
        }
    }

    window.addEventListener('resize', handleResize);
    handleResize();
});


function sendFeedback(isHelpful) {
    const feedbackMessage = isHelpful
        ? "Thanks for your feedback!"
        : "Sorry to hear that!";

    Swal.fire({
        icon: isHelpful ? 'success' : 'info',
        title: feedbackMessage,
        showConfirmButton: false,
        timer: 1500
    });

    document.querySelector('.feedback-section').style.display = 'none';
}

function showFeedbackSection() {
    document.querySelector('.feedback-section').style.display = 'block';
}
