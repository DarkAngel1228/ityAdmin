<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Ramsey\Uuid\Type\Integer;

/**
 * App\Models\Customer
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer query()
 * @mixin \Eloquent
 */
class Customer extends Model
{
    use HasFactory;

    protected static $logName = 'customers';

    protected static $logFillable = true;

    protected static $logUnguarded = true;

    const CREATED_AT = null;
    const UPDATED_AT = null;

    protected $table = 'customers';

    protected $primaryKey = 'id';

    protected $fillable = [
        'city', 'county', 'hospital', 'department', 'customer_name', 'phone', 'information', 'demand', 'visit', 'channel_business'
    ];

    public function getList(array $validated): array
    {
        if ($validated['offset']) {
            $validated['offset'] = ($validated['offset'] - 1) * $validated['limit'];
        }
        $list = Customer::where('is_delete', 0)
            ->when($validated['search_data'] ?? null, function ($query) use ($validated) {
                return $query->whereRaw("concat(IFNULL(city,''),IFNULL(county,''),IFNULL(hospital,''),IFNULL(department,''),IFNULL(customer_name,'')) like ?",["%" . $validated['search_data'] . "%"]);
            })
            ->limit($validated['limit'])
            ->offset($validated['offset'])
            ->get();

        $total = self::getTotal($validated);
        return [
            'customers' => $list,
            'total' => $total
        ];
    }

    public function getTotal(array $validated)
    {
        return Customer::where('is_delete', 0)
            ->when($validated['search_data'] ?? null, function ($query) use ($validated) {
                return $query->whereRaw("concat(IFNULL(city,''),IFNULL(county,''),IFNULL(hospital,''),IFNULL(department,''),IFNULL(customer_name,'')) like ?",["%" . $validated['search_data'] . "%"]);
            })
            ->count();
    }

    public function getCityList()
    {
        return Customer::where('is_delete', 0)
            ->groupBy("city")
            ->select(['city'])
            ->get();
    }

    public function getCountyList()
    {
        return Customer::where('is_delete', 0)
            ->groupBy("county")
            ->select(['county'])
            ->get();
    }

    public function getHospitalList()
    {
        return Customer::where('is_delete', 0)
            ->groupBy("hospital")
            ->select(['hospital'])
            ->get();
    }

    public function getDepartmentList()
    {
        return Customer::where('is_delete', 0)
            ->groupBy("department")
            ->select(['department'])
            ->get();
    }

    public function getCustomerNameList()
    {
        return Customer::where('is_delete', 0)
            ->groupBy("customer_name")
            ->select(['customer_name'])
            ->get();
    }

    public function createCustomer(array $attributes = [])
    {
        $customerModel = new Customer($attributes);
        $customerModel->save();
        return [];
    }

    public function updateCustomer(array $attributes = [])
    {
        $editArray = [$attributes['column'] => $attributes['edit_value']];
        $customerInfo = Customer::where('id', $attributes['id'])->first();
        $customerInfo->fill($editArray);
        $customerInfo->save();
        return [];
    }
    public function deleteCustomer(int $id)
    {
        $customerInfo = Customer::where('id', $id)->first();
        $customerInfo->is_delete = 1;
        $customerInfo->save();
        return [];
    }



}
