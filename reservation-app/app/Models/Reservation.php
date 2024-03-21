<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    protected $guarded = [];

            /**
     * The bed_types that belong to the Room
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function services()
    {
        return $this->belongsToMany(Service::class, 'reservation_services', 'reservation_id', 'service_id')->withPivot('id','description','price');
    }


            /**
     * The bed_types that belong to the Room
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function reservation_details()
    {
        return $this->belongsToMany(Room::class, 'reservation_details', 'reservation_id', 'room_id')->withPivot('id','additional_request');
    }

        /**
     * Get all of the other_images for the Room
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payment_details()
    {
        return $this->hasMany(ReservationPaymentDetail::class, 'reservation_id');
    }

        /**
     * Get the room_type that owns the Room
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
