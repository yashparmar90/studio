<?php
/**
 * ============================================
 * Contact Form Handler
 * MemoryWave Studio
 * ============================================
 * 
 * This file handles contact form submissions
 * and saves them to the database.
 */

header('Content-Type: application/json');

// Enable CORS for same-site requests
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

require_once __DIR__ . '/admin/includes/db.php';

// Get form data
$name = sanitize($_POST['name'] ?? '');
$email = sanitize($_POST['email'] ?? '');
$phone = sanitize($_POST['phone'] ?? '');
$service = sanitize($_POST['service'] ?? '');
$eventDate = sanitize($_POST['date'] ?? '');
$message = sanitize($_POST['message'] ?? '');

// Validate required fields
if (empty($name) || empty($email) || empty($message)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
}

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

try {
    $db = db();
    
    $sql = "INSERT INTO messages (name, email, phone, service, event_date, message) 
            VALUES (:name, :email, :phone, :service, :event_date, :message)";
    
    $db->query($sql, [
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'service' => $service,
        'event_date' => $eventDate,
        'message' => $message
    ]);
    
    echo json_encode([
        'success' => true, 
        'message' => 'Thank you for your message! We will get back to you soon.'
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Something went wrong. Please try again later.']);
}
?>
