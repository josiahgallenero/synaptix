async function sendMessage() {
    const messageInput = document.getElementById('message');
    const message = messageInput.value.trim();
    if (!message) return;

    const chat = document.getElementById('chat');
    chat.innerHTML += `<div class="chat-message user-message"><strong>You:</strong> ${message}</div>`;

    const loadingIndicator = document.getElementById('loading');
    loadingIndicator.style.display = 'block';

    try {
        const response = await fetch('https://api.cohere.ai/generate', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer DgvzJhcAm5YFe3tmrJGl3bJQmidhk7wSfxnSvIiw',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                model: 'command-xlarge',
                prompt: message,
                max_tokens: 300,
                temperature: 0.7
            })
        });

        const data = await response.json();
        loadingIndicator.style.display = 'none';

        const formattedResponse = data.text
            ? data.text.replace(/\n/g, '<br>') // Convert new lines to <br> tags
            : 'Sorry, there was an error.';

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