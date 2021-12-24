<?php
/**
 * User: zhangjinyu
 * Date: 2021/12/20 14:37
 * Email: zhangjinyu@pzoom.com
 * Action: 用途
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;

    protected static $logName = 'records';

    protected static $logFillable = true;

    protected static $logUnguarded = true;

    protected $table = 'records';

    protected $primaryKey = 'id';

    protected $fillable = [
        'customer_id', 'phone', 'produce', 'basic_information', 'tracker', 'clinical_relationship', 'bill', 'channel_business', 'record', 'remark', 'update_at', 'is_delete'];

}
