<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Customer
 *
 * @mixin \Eloquent
 */
class ChannelBusiness extends Model
{
    use HasFactory;

    protected static $logName = 'channel_business';

    protected static $logFillable = true;

    protected static $logUnguarded = true;

    const CREATED_AT = null;
    const UPDATED_AT = null;

    protected $table = 'channel_business';

    protected $primaryKey = 'id';

    protected $fillable = [
        'hospital', 'channel_business', 'phone', 'company', 'produce', 'money', 'business_time'
    ];
    public function getList(array $validated): array
    {
        if ($validated['offset']) {
            $validated['offset'] = ($validated['offset'] - 1) * $validated['limit'];
        }
        $list = ChannelBusiness::where('is_delete', 0)
            ->when($validated['search_data'] ?? null, function ($query) use ($validated) {
                return $query->whereRaw("concat(IFNULL(hospital,''),IFNULL(channel_business,''),IFNULL(company,''),IFNULL(produce,'')) like ?",["%" . $validated['search_data'] . "%"]);
            })
            ->limit($validated['limit'])
            ->offset($validated['offset'])
            ->get();

        $total = self::getTotal($validated);
        return [
            'channel_business' => $list,
            'total' => $total
        ];
    }

    public function getTotal(array $validated)
    {
        return ChannelBusiness::where('is_delete', 0)
            ->when($validated['search_data'] ?? null, function ($query) use ($validated) {
                return $query->whereRaw("concat(IFNULL(hospital,''),IFNULL(channel_business,''),IFNULL(company,''),IFNULL(produce,'')) like ?",["%" . $validated['search_data'] . "%"]);
            })
            ->count();
    }

    public function getCompanyList()
    {
        return ChannelBusiness::where('is_delete', 0)
            ->select(['company'])
            ->distinct()
            ->get();
    }

    public function getProduceList()
    {
        return ChannelBusiness::where('is_delete', 0)
            ->select(['produce'])
            ->distinct()
            ->get();
    }

    public function getChannelBusinessList()
    {
        return ChannelBusiness::where('is_delete', 0)
            ->select(['channel_business'])
            ->distinct()
            ->get();
    }

    public function createChannelBusiness(array $attributes = [])
    {
        $customerModel = new ChannelBusiness($attributes);
        $customerModel->save();
        return [];
    }

    public function updateChannelBusiness(array $attributes = [])
    {
        $editArray = [$attributes['column'] => $attributes['edit_value']];
        $customerInfo = ChannelBusiness::where('id', $attributes['id'])->first();
        $customerInfo->fill($editArray);
        $customerInfo->save();
        return [];
    }

    public function deleteChannelBusiness(int $id)
    {
        $customerInfo = ChannelBusiness::where('id', $id)->first();
        $customerInfo->is_delete = 1;
        $customerInfo->save();
        return [];
    }

}
