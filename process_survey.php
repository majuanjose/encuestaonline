<?php
/**
 * Antigravity Survey Proxy
 * Securely handles data submission to Airtable and n8n without exposing keys to the frontend.
 */

// 1. Security Headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // In production, replace * with your domain
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// 2. Configuration (SECURED ON SERVER)
define('N8N_URL', 'https://majuanjose.app.n8n.cloud/webhook/d88ac411-1559-4ac3-be00-2152250c30d8');

// 3. Receive and Validate Input
$inputJSON = file_get_contents('php://input');
$data = json_decode($inputJSON, true);

if (!$data || !isset($data['id_estudiante'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input data']);
    exit;
}

// Basic Sanitization
$fields = [
    "IDEstudiante" => htmlspecialchars($data['id_estudiante']),
    "NivelSatisfaccion" => (int)$data['nivel_satisfaccion'],
    "ClaridadContenido" => (int)$data['claridad_contenido'],
    "AplicabilidadPractica" => (int)$data['aplicabilidad_practica'],
    "ComentariosAdicionales" => htmlspecialchars($data['comentarios_adicionales'] ?? "")
];

// 4. Send to n8n (n8n will now handle Airtable and Email)
$chN8n = curl_init(N8N_URL);
curl_setopt($chN8n, CURLOPT_RETURNTRANSFER, true);
curl_setopt($chN8n, CURLOPT_POST, true);
curl_setopt($chN8n, CURLOPT_POSTFIELDS, json_encode($fields));
curl_setopt($chN8n, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$n8nResponse = curl_exec($chN8n);
$n8nCode = curl_getinfo($chN8n, CURLINFO_HTTP_CODE);
curl_close($chN8n);

// 5. Respond to Frontend
if ($n8nCode >= 200 && $n8nCode < 300) {
    echo json_encode(['status' => 'success', 'message' => 'Data sent to automation flow']);
} else {
    http_response_code(500);
    echo json_encode([
        'status' => 'error', 
        'message' => 'Automation trigger failed', 
        'code' => $n8nCode,
        'n8n_response' => $n8nResponse
    ]);
}
?>
