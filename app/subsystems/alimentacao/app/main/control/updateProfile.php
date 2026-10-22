<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['error' => 'Usuário não autenticado']);
    exit();
}

// Database connection
try {
    $pdo = new PDO("mysql:host=localhost;dbname=gestaoalimentarescolar;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erro de conexão com o banco de dados']);
    exit();
}

// Handle file upload
if (isset($_FILES['profilePhoto'])) {
    $file = $_FILES['profilePhoto'];
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxSize = 2 * 1024 * 1024; // 2MB

    // Validate file type
    if (!in_array($file['type'], $allowedTypes)) {
        echo json_encode(['error' => 'Tipo de arquivo não permitido (apenas JPEG, PNG, GIF)']);
        exit();
    }

    // Validate file size
    if ($file['size'] > $maxSize) {
        echo json_encode(['error' => 'Arquivo muito grande (máximo 2MB)']);
        exit();
    }

    $userId = $_SESSION['usuario']['id'];
    $uploadDir = '../assets/img/profiles/';
    
    // Create directory if it doesn't exist
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Generate a unique file name
    $fileName = $userId . '_' . time() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
    $uploadPath = $uploadDir . $fileName;

    // Move the uploaded file
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        // Update database
        $stmt = $pdo->prepare("UPDATE usuario SET profile_photo = :photo WHERE id = :id");
        $stmt->execute(['photo' => $fileName, 'id' => $userId]);

        // Update session
        $_SESSION['usuario']['profile_photo'] = $fileName;

        echo json_encode(['success' => 'Foto atualizada com sucesso']);
    } else {
        echo json_encode(['error' => 'Erro ao fazer upload do arquivo']);
    }
} else {
    echo json_encode(['error' => 'Nenhum arquivo enviado']);
}
?>