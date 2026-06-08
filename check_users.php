<?php
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

// List all users
$users = User::all(['id', 'name', 'email', 'password']);
echo $users->count() . " users found\n";
foreach ($users as $u) {
    echo $u->id . ' | ' . $u->email . ' | roles: ' . $u->getRoleNames()->join(', ') . "\n";
}

// Check hash validity for guru@lms.com
$guru = User::where('email', 'guru@lms.com')->first();
if ($guru) {
    $valid = Hash::check('password', $guru->password);
    echo "\nguru@lms.com hash check ('password'): " . ($valid ? 'VALID' : 'INVALID') . "\n";
    echo "Hash stored: " . substr($guru->password, 0, 20) . "...\n";
} else {
    echo "\nguru@lms.com: NOT FOUND\n";
}
