<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'avatar', 'is_admin', 'nombre', 'apellido', 'numero', 'fecha_nacimiento'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'fecha_nacimiento' => 'date',
        ];
    }

    /**
     * Nombre completo para mostrar.
     */
    public function nombreCompleto(): string
    {
        return trim(($this->nombre ?? '').' '.($this->apellido ?? '')) ?: $this->name;
    }

    /**
     * URL del avatar del usuario, con respaldo al avatar por defecto.
     */
    public function avatarUrl(): string
    {
        $nombre = $this->avatar ?: 'default';

        foreach (['png', 'jpg', 'jpeg', 'webp', 'svg'] as $extension) {
            if (file_exists(public_path("avatars/{$nombre}.{$extension}"))) {
                return asset("avatars/{$nombre}.{$extension}");
            }
        }

        return asset('avatars/default.svg');
    }
}
