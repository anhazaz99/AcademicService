<?php
require_once 'vendor/autoload.php';

$baseUrl = 'http://localhost:8000/api';
$username = 'testuser';
$password = 'Test123!';

echo "=== TESTING MONHOC API ===\n\n";

// 1. Login để lấy token
echo "1. Login...\n";
$loginData = ['username' => $username, 'password' => $password];
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($loginData));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$loginResult = json_decode($response, true);
$token = $loginResult['access_token'] ?? null;

if (!$token) {
    echo "Login failed: $response\n";
    exit;
}
echo "Login success! Token: " . substr($token, 0, 30) . "...\n\n";

// 2. Test lấy danh sách môn học
echo "2. Get all monhocs...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/monhocs');
curl_setopt($ch, CURLOPT_HTTPGET, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $token]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "Response (HTTP $httpCode): " . substr($response, 0, 200) . "...\n\n";

// 3. Test tìm kiếm môn học
echo "3. Search monhocs...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/monhocs-search?keyword=Lập');
curl_setopt($ch, CURLOPT_HTTPGET, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $token]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "Response (HTTP $httpCode): " . substr($response, 0, 200) . "...\n\n";

// 4. Test lấy môn học theo ID
echo "4. Get monhoc by ID...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/monhocs/1');
curl_setopt($ch, CURLOPT_HTTPGET, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $token]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "Response (HTTP $httpCode): " . substr($response, 0, 200) . "...\n\n";

// 5. Test lấy môn học theo khoa
echo "5. Get monhocs by khoa...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/monhocs-khoa/1');
curl_setopt($ch, CURLOPT_HTTPGET, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $token]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "Response (HTTP $httpCode): " . substr($response, 0, 200) . "...\n\n";

// 6. Test lấy môn học theo giáo viên
echo "6. Get monhocs by giaovien...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/monhocs-giaovien/1');
curl_setopt($ch, CURLOPT_HTTPGET, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $token]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "Response (HTTP $httpCode): " . substr($response, 0, 200) . "...\n\n";

// 7. Test tạo môn học mới
echo "7. Create new monhoc...\n";
$newMonHocData = [
    'ten_mon' => 'Lập trình Python Test',
    'so_tin_chi' => 3,
    'khoa_id' => 1,
    'giao_vien_id' => 1
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/monhocs');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($newMonHocData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $token,
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "Response (HTTP $httpCode): " . substr($response, 0, 200) . "...\n\n";

$createResult = json_decode($response, true);
$newMonHocId = $createResult['data']['id'] ?? null;

if ($newMonHocId) {
    // 8. Test cập nhật môn học
    echo "8. Update monhoc...\n";
    $updateData = [
        'ten_mon' => 'Lập trình Python Test Updated',
        'so_tin_chi' => 4,
        'khoa_id' => 1,
        'giao_vien_id' => 1
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $baseUrl . '/monhocs/' . $newMonHocId);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($updateData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo "Response (HTTP $httpCode): " . substr($response, 0, 200) . "...\n\n";

    // 9. Test xóa môn học
    echo "9. Delete monhoc...\n";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $baseUrl . '/monhocs/' . $newMonHocId);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $token]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo "Response (HTTP $httpCode): " . substr($response, 0, 200) . "...\n\n";
}

echo "=== TEST COMPLETED ===\n";
