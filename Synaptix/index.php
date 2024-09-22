<?php
session_start();

$cohereApiKey = 'DgvzJhcAm5YFe3tmrJGl3bJQmidhk7wSfxnSvIiw';

if (isset($_POST['message'])) {
    $userMessage = $_POST['message'];

    $data = [
        'model' => 'command-xlarge',
        'prompt' => $userMessage,
        'max_tokens' => 300,
        'temperature' => 0.7,
    ];

    $options = [
        'http' => [
            'header' => [
                "Authorization: Bearer " . trim($cohereApiKey),
                "Content-Type: application/json"
            ],
            'method' => 'POST',
            'content' => json_encode($data),
        ],
    ];

    $context = stream_context_create($options);
    $response = file_get_contents('https://api.cohere.ai/generate', false, $context);

    if ($response === FALSE) {
        $answer = "Error: Unable to connect to the API.";
    } else {
        $result = json_decode($response, true);
        $answer = $result['text'] ?? 'Error: Unexpected response structure.';
    }

    echo json_encode(['response' => $answer]);
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SynaptixChat Pro</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/google-font.css">
    <link rel="stylesheet" href="assets/css/sweetalert2.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" href="assets/images/SynaptixChat Pro.png">
</head>
<body>
    <div id="sidebar" class="sidebar collapse show d-flex flex-column">
        <h5 style="color: white;" class="text-center mb-4">SynaptixChat Pro</h5>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="#">
                    <i class="bi bi-house-door"></i> Home
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#helpModal">
                    <i class="bi bi-question-circle"></i> Help
                </a>
            </li>
        </ul>
        <div class="sidebar-footer mt-auto text-center" style="padding: 10px; color: white;">
            <p>&copy; 2024 Josiah AI</p>
            <a href="#" class="text-white" style="text-decoration: none;" data-bs-toggle="modal" data-bs-target="#privacyModal">Privacy Policy</a> |
            <a href="#" class="text-white" style="text-decoration: none;" data-bs-toggle="modal" data-bs-target="#termsModal">Terms of Service</a>
        </div>
    </div>

    <div class="content">
        <button button id="toggleSidebar" class="btn btn-secondary mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar" aria-expanded="false" aria-controls="sidebar">
            <i class="bi bi-list"></i>
        </button>

        <h2 id="synaptixChatPro">SynaptixChat Pro</h2>
        <div id="loading" style="display: none;" class="text-center mt-3 mb-3">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div style="color:white;">Please wait while we process your message...</div>
        </div>
        <div id="chat" class="chat-window"></div>
        <div class="input-group mb-3">
            <textarea id="message" class="form-control" placeholder="Message SynaptixChat Pro" aria-label="User message" onkeypress="checkEnter(event)" rows="3"></textarea>
            <button class="btn btn-primary" onclick="sendMessage()">Send</button>
        </div>
        <div class="feedback-section text-center mt-4 mb-5">
            <h6 style="color:white;">Was this response helpful?</h6>
            <button class="btn btn-success" onclick="sendFeedback(true)">Yes</button>
            <button class="btn btn-danger" onclick="sendFeedback(false)">No</button>
        </div>
        <div class="sidebar-footer mt-auto text-center" style="padding: 10px; color: white;">
            <p>&copy; 2024 Josiah AI</p>
            <a href="#" class="text-white" style="text-decoration: none;" data-bs-toggle="modal" data-bs-target="#privacyModal">Privacy Policy</a> |
            <a href="#" class="text-white" style="text-decoration: none;" data-bs-toggle="modal" data-bs-target="#termsModal">Terms of Service</a>
        </div>
    </div>

    <div class="modal fade" id="helpModal" tabindex="-1" aria-  labelledby="helpModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="helpModalLabel">Help Center</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>Getting Started</h6>
                    <p>Welcome to SynaptixChat Pro! Here are some tips to help you navigate:</p>
                    <ul>
                        <li>To ask a question, type in the input field and click "Send" or press "Enter."</li>
                        <li>For best results, try to be specific in your questions.</li>
                        <li>If you need clarification, feel free to ask follow-up questions.</li>
                        <li>You can view past messages in the chat window above.</li>
                        <li>In case of issues, check your internet connection and try again.</li>
                        <li>Explore the Privacy Policy and Terms of Service for more information on data usage.</li>
                    </ul>
                    <h6>Tips for Using SynaptixChat Pro</h6>
                    <ul>
                        <li>Use simple language for better understanding.</li>
                        <li>If you encounter any errors, note the error message for troubleshooting.</li>
                        <li>Feel free to provide feedback on responses to help improve the service.</li>
                    </ul>
                    <h6>Contact Us</h6>
                    <p>If you need further assistance, please reach out to our support team at <a href="mailto:josiahdanielle09gallenero@gmail.com">josiahdanielle09gallenero@gmail.com</a>.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Privacy Policy Modal -->
    <div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="privacyModalLabel">Privacy Policy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Your privacy policy content goes here. This should include information about what data you collect, how you use it, and how you protect it.</p>
                    <ul>
                        <li><strong>Data Collection:</strong> We collect personal information that you provide to us.</li>
                        <li><strong>Use of Data:</strong> We use your data to improve our services.</li>
                        <li><strong>Security:</strong> We take reasonable steps to protect your information.</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms of Service Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Terms of Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Your terms of service content goes here. This should outline the rules and guidelines for using our services.</p>
                    <ul>
                        <li><strong>Acceptance:</strong> By using our service, you agree to these terms.</li>
                        <li><strong>Changes:</strong> We may update these terms from time to time.</li>
                        <li><strong>Limitation of Liability:</strong> We are not liable for any damages arising from your use of our service.</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script src="assets/js/sweetalert2.min.js"></script>
    
</body>
</html>
