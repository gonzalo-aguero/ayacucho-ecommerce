<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    /**
     * Indica que la clave primaria no es auto-incremental
     */
    public $incrementing = false;

    /**
     * El tipo de la clave primaria
     */
    protected $keyType = 'string';

    /**
     * Los atributos que se pueden asignar masivamente
     */
    protected $fillable = [
        'customer_name',
        'customer_dni',
        'customer_email',
        'customer_phone',
        'customer_city',
        'customer_address',
        'shipping_zone',
        'payment_method',
        'customer_note',
        'cart_items',
        'cart_total',
        'shipping_cost',
        'order_total',
        'company_phone_used',
        'whatsapp_url',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos
     */
    protected $casts = [
        'cart_items' => 'array',
        'cart_total' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'order_total' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Generar UUID automáticamente al crear un registro
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Str::uuid();
            }
        });
    }

    /**
     * Mutators para asegurar que los valores monetarios sean numéricos
     * (El controlador se encarga de la conversión principal)
     */
    public function setCartTotalAttribute($value)
    {
        $this->attributes['cart_total'] = is_numeric($value) ? $value : 0;
    }

    public function setShippingCostAttribute($value)
    {
        $this->attributes['shipping_cost'] = is_numeric($value) ? $value : 0;
    }

    public function setOrderTotalAttribute($value)
    {
        $this->attributes['order_total'] = is_numeric($value) ? $value : 0;
    }

    /**
     * Accessors para formatear valores monetarios al formato argentino
     */
    public function getCartTotalFormattedAttribute()
    {
        return '$' . number_format((float) $this->cart_total, 2, ',', '.');
    }

    public function getShippingCostFormattedAttribute()
    {
        return '$' . number_format((float) $this->shipping_cost, 2, ',', '.');
    }

    public function getOrderTotalFormattedAttribute()
    {
        return '$' . number_format((float) $this->order_total, 2, ',', '.');
    }

    /**
     * Scope para filtrar por cliente (DNI o teléfono)
     */
    public function scopeByCustomer($query, $identifier)
    {
        return $query->where('customer_dni', $identifier)
                    ->orWhere('customer_phone', $identifier);
    }

    /**
     * Scope para filtrar por rango de fechas
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope para filtrar por método de pago
     */
    public function scopeByPaymentMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    /**
     * Scope para filtrar por zona de envío
     */
    public function scopeByShippingZone($query, $zone)
    {
        return $query->where('shipping_zone', $zone);
    }
}
