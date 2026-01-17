<?php
// Script untuk update password user
require_once 'index.php';

// Load CodeIgniter
$CI =& get_instance();
$CI->load->database();

// Hash password
$password_user = password_hash('user', PASSWORD_DEFAULT);
$password_users = password_hash('users', PASSWORD_DEFAULT);

// Update user 'user'
$CI->db->where('username', 'user');
$CI->db->update('user', ['password' => $password_user]);
echo "User 'user' updated. Password length: " . strlen($password_user) . "\n";

// Update user 'users'
$CI->db->where('username', 'users');
$CI->db->update('user', ['password' => $password_users]);
echo "User 'users' updated. Password length: " . strlen($password_users) . "\n";

echo "\nSelesai! Sekarang coba login dengan:\n";
echo "Username: user | Password: user\n";
echo "Username: users | Password: users\n";
?>
