<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;
    
    protected $guard = 'admin';
    
    protected $fillable = ['name', 'email', 'password', 'role'];
    
    protected $hidden = ['password', 'remember_token'];
    
    // Method untuk cek role
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    
    public function isPendeta()
    {
        return $this->role === 'pendeta';
    }
}