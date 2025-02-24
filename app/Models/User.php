<?php

namespace App\Models;

use App\Casts\Hash;
use App\Classes\Common;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Trebol\Entrust\Traits\EntrustUserTrait;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash as FacadesHash;
use Illuminate\Database\Eloquent\Builder;

class User extends BaseModel implements AuthenticatableContract, JWTSubject
{
    use Notifiable, EntrustUserTrait, Authenticatable, HasFactory;

    protected $default = ["xid", "name", "profile_image","adhaar","pan_card","check_copy"];

    protected $guarded = ['id', 'company_id', 'created_at', 'updated_at'];

    protected $dates = ['last_active_on'];

    protected $hidden = ['id', 'role_id', 'password', 'remember_token'];

    protected $appends = ['xid', 'x_company_id', 'x_role_id', 'profile_image_url','adhaar_url','pan_card_url','check_copy_url'];

    protected $filterable = ['name', 'user_type', 'email', 'status', 'phone','branch_id','adhaar', 'pan_card', 'check_copy'];

    protected $hashableGetterFunctions = [
        'getXCompanyIdAttribute' => 'company_id',
        'getXRoleIdAttribute' => 'role_id',
    ];

    protected $casts = [
        'company_id' => Hash::class . ':hash',
        'role_id' => Hash::class . ':hash',
        'login_enabled' => 'integer',
        'is_superadmin' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        // static::addGlobalScope('type', function (Builder $builder) {
        //     $builder->where('users.user_type', '=', 'staff_members');
        // });
    }

    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = FacadesHash::make($value);
        }
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function setUserTypeAttribute($value)
    {
        $this->attributes['user_type'] = 'staff_members';
    }

    public function getProfileImageUrlAttribute()
    {
        $userImagePath = Common::getFolderPath('userImagePath');

        return $this->profile_image == null ? asset('images/user.png') : Common::getFileUrl($userImagePath, $this->profile_image);
    }

    public function getAdhaarUrlAttribute()
    {
        $adhaarPath = Common::getFolderPath('userImagePath'); // Define the folder path in Common
        return $this->adhaar_card == null
            ? null
            : Common::getFileUrl($adhaarPath, $this->adhaar_card);
    }

    public function getPanCardUrlAttribute()
    {
        $panCardPath = Common::getFolderPath('userImagePath'); // Define the folder path in Common
        return $this->pan_card == null
            ? null
            : Common::getFileUrl($panCardPath, $this->pan_card);
    }

    public function getCheckCopyUrlAttribute()
    {
        $checkCopyPath = Common::getFolderPath('userImagePath'); // Define the folder path in Common
        return $this->check_copy == null
            ? null
            : Common::getFileUrl($checkCopyPath, $this->check_copy);
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
