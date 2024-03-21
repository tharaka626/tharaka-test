<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * The bed_types that belong to the Room
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function bed_types()
    {
        return $this->belongsToMany(BedType::class, 'room_bed_types', 'room_id', 'bed_type_id');
    }

        /**
     * The bed_types that belong to the Room
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function services()
    {
        return $this->belongsToMany(Service::class, 'room_services', 'room_id', 'service_id')->withPivot('id','description','price');
    }

    /**
     * Get all of the other_images for the Room
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function other_images()
    {
        return $this->hasMany(RoomOtherImage::class, 'room_id');
    }

    /**
     * Get the room_type that owns the Room
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function room_type()
    {
        return $this->belongsTo(RoomType::class);
    }

}
