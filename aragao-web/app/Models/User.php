<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'type',
        'password_user_set',
        'engineer_location',
        'engineer_admin',
        'notification_token_android',
        'notification_token_ios'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'password_user_set' => 'boolean',
        'engineer_location' => 'boolean',
        'engineer_admin' => 'boolean'
    ];

    protected $appends = [
        'name_sigla',
        'unviewed_messages'
    ];

    public function getNameSiglaAttribute() {
        $name = explode(' ', $this->name);
        return strtoupper(substr($name[0] ?? '', 0, 1).substr($name[1] ?? '', 0, 1));
    }

    public function getUnviewedMessagesAttribute() {
        $authUserId = Auth::user()->id;
        $idsChats = ChatUsuario::where('id_usuario', $authUserId)->get('id_chat');

        $chats = Auth::user()->type !== 'admin'
            ? Chat::withCount('unviewedMessages')->whereIn('id', $idsChats)->get()
            : Chat::withCount('unviewedMessages')->whereIn('id', $idsChats)->orWhere('tipo', 'group')->get();

        return $chats->sum('unviewed_messages_count');
    }
}
