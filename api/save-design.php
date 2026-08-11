<?php
/**
 * POST /api/save-design.php
 *
 * Persists a customizer design. Core PHP — no framework required, so it
 * drops into any existing PHP environment (plain PHP, CodeIgniter, custom CMS).
 *
 * Expected JSON body (produced by the Fabric.js front end):
 * {
 *   "product":  "classic-tee",
 *   "color":    { "name": "Navy", "hex": "#1f3354" },
 *   "quantity": 25,
 *   "sides":    { "front": <fabric JSON|null>, "back": <fabric JSON|null> }
 * }
 *
 * Response: { "ok": true, "design_id": "d_66b2...", "preview": "/storage/designs/d_66b2.../front.png" }
 *
 * Production notes:
 *  - Uploaded artwork is sent separately to upload-artwork.php (multipart),
 *    which validates MIME/size, re-encodes the image, and returns a hosted URL.
 *    The Fabric JSON then references that URL instead of a base64 data URL,
 *    keeping design payloads small.
 *  - Design rows live in MySQL (designs table: id, session_id, product_id,
 *    color, qty, json_front, json_back, created_at). File storage below is
 *    only to keep this sample dependency-free.
 *  - Server-side print-ready rendering (300 DPI PNG/PDF for the print shop)
 *    runs the same Fabric JSON through node-canvas or Imagick in a queue job.
 */

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'POST only']);
    exit;
}

$raw = file_get_contents('php://input');
if (strlen($raw) > 2 * 1024 * 1024) { // 2 MB cap on design JSON
    http_response_code(413);
    echo json_encode(['ok' => false, 'error' => 'Payload too large']);
    exit;
}

$data = json_decode($raw, true);
if (
    !is_array($data)
    || empty($data['product'])
    || empty($data['color']['hex'])
    || !preg_match('/^#[0-9a-f]{6}$/i', $data['color']['hex'])
    || !isset($data['sides'])
) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'error' => 'Invalid design payload']);
    exit;
}

$designId = 'd_' . bin2hex(random_bytes(8));
$dir = __DIR__ . '/../storage/designs/' . $designId;

if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Storage unavailable']);
    exit;
}

$record = [
    'id'       => $designId,
    'product'  => preg_replace('/[^a-z0-9\-]/', '', strtolower($data['product'])),
    'color'    => [
        'name' => mb_substr(strip_tags($data['color']['name'] ?? ''), 0, 60),
        'hex'  => strtolower($data['color']['hex']),
    ],
    'quantity' => max(1, (int) ($data['quantity'] ?? 1)),
    'sides'    => [
        'front' => $data['sides']['front'] ?? null,
        'back'  => $data['sides']['back'] ?? null,
    ],
    'created_at' => gmdate('c'),
];

file_put_contents($dir . '/design.json', json_encode($record, JSON_PRETTY_PRINT));

echo json_encode(['ok' => true, 'design_id' => $designId]);
