<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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

    protected $table = 'customers';

    protected $primaryKey = 'id';

    protected $fillable = [
        'city', 'county', 'hospital', 'department', 'customer_name'
    ];

    public function getList(array $validated): array
    {
        $list = Customer::where('is_delete', 0)
            ->when($validated['customer_name'] ?? null, function ($query) use ($validated) {
                return $query->where('customer_name', 'like', '%' . $validated['customer_name'] . '%');
            })
            ->when($validated['hospital'] ?? null, function ($query) use ($validated) {
                return $query->where('hospital', 'like', '%' . $validated['hospital'] . '%');
            })
            ->limit($validated['limit'])
            ->offset($validated['offset'])
            ->get()->toArray();
        $idData = array_column($list, 'id');
        $record = Record::where('is_delete', 0)->whereIn('customer_id', $idData)->get()->toArray();

        foreach ($list as $key => $info) {
            foreach ($record as $a) {
                if ($info['id'] == $a['customer_id']) {
                    $list[$key]['child'][] = $a;
                }
            }
        }
        $total = count($list);
        return [
            'customers' => $list,
            'total' => $total
        ];
    }

    public function getHospitalList()
    {
        return Customer::where('is_delete', 0)
            ->groupBy("hospital")
            ->select(['hospital'])
            ->get();
    }
    public function getCustomerNameList()
    {
        return Customer::where('is_delete', 0)
            ->groupBy("customer_name")
            ->select(['customer_name'])
            ->get();
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
    public function getDepartmentList()
    {
        return Customer::where('is_delete', 0)
            ->groupBy("department")
            ->select(['department'])
            ->get();
    }

    public function getPhoneList()
    {
        return Record::where('is_delete', 0)
            ->groupBy("phone")
            ->select(['phone'])
            ->get();
    }
    public function getProduceList()
    {
        return Record::where('is_delete', 0)
            ->groupBy("produce")
            ->select(['produce'])
            ->get();
    }
    public function getTrackerList()
    {
        return Record::where('is_delete', 0)
            ->groupBy("tracker")
            ->select(['tracker'])
            ->get();
    }
    public function getBillList()
    {
        return Record::where('is_delete', 0)
            ->groupBy("bill")
            ->select(['bill'])
            ->get();
    }
    public function getChannelBusinessList()
    {
        return Record::where('is_delete', 0)
            ->groupBy("channel_business")
            ->select(['channel_business'])
            ->get();
    }

    public function getRecordList()
    {
        return Record::where('is_delete', 0)
            ->groupBy("record")
            ->select(['record'])
            ->get();
    }
}
