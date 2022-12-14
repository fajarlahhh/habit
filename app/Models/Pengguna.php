<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pengguna extends Authenticatable
{
  use SoftDeletes, Notifiable;

  protected $table = 'pengguna';
  protected $rememberTokenName = 'remember_token';

  protected $fillable = [
    'uid', 'kata_sandi',
  ];

  protected $hidden = [
    'kata_sandi',
    'remember_token',
  ];

  public function getAuthPassword()
  {
    return $this->kata_sandi;
  }
}
