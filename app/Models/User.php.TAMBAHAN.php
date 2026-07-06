<?php
/**
 * File ini BUKAN untuk menimpa app/Models/User.php bawaan Laravel.
 * Cukup salin bagian-bagian di bawah ini ke dalam file User.php yang sudah ada.
 */

// 1) Tambahkan ke $fillable:
// 'role', 'jabatan', 'division_id'

// 2) Tambahkan cast (opsional, biar $user->role selalu string enum yang rapi):
// protected $casts = [
//     'email_verified_at' => 'datetime',
//     'password' => 'hashed',
// ];

// 3) Tambahkan relasi & helper role di dalam class User:

public function division()
{
    return $this->belongsTo(Division::class);
}

public function actionPlans()
{
    return $this->hasMany(ActionPlan::class, 'pic_user_id');
}

public function isAdmin(): bool
{
    return $this->role === 'admin';
}

public function isPic(): bool
{
    return $this->role === 'pic';
}

public function isViewer(): bool
{
    return $this->role === 'viewer';
}
