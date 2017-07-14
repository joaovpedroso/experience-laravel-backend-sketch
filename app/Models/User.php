<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use SoftDeletes;
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'status', 'photo'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @return mixed
     */
    public function hasModule($slug)
    {
       $permission = Permission::select('id')->where('name','like',"%$slug%")->pluck('id');

        return DB::table('user_has_permissions')->where('user_id', Auth::user()->id)
            ->whereIn('permission_id', $permission)->count();
    }

    // ESCOPES ====================================================

    public function scopeAllExceptFirst($query)
    {
        return $query->where('id', '<>', 1);
    }

    public function scopeName($query, $name = null)
    {
        if (!is_null($name)) {
            $name = str_replace(' ', '%', $name);

            return $query->where('name', 'like', "%$name%");
        }
    }

    public function scopeEmail($query, $email = null)
    {
        if (!is_null($email)) {
            $email = str_replace(' ', '%', $email);

           return $query->where('email', 'like', "%$email%");
        }
    }

    public function scopeStatus($query, $status = null)
    {
        if (!is_null($status)) {
           return $query->where('status', $status);
        }
    }

    public function hasSubmodule($submodule)
    {
        return $this->submodules->contains($submodule);
    }

    // Configurações ===============================================

    /**
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        return "Usuários";
    }
}
