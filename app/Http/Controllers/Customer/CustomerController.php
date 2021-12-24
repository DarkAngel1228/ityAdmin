<?php
/**
 * User: zhangjinyu
 * Date: 2021/12/20 11:08
 * Email: zhangjinyu@pzoom.com
 * Action: 用途
 */

namespace App\Http\Controllers\Customer;


use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\GetListRequest;
use App\Http\Response\ApiCode;
use App\Models\Customer;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
{
    /**
     * 管理员列表
     *
     * @param GetListRequest $request
     * @return Response
     */
    public function customers(GetListRequest $request): Response
    {
        $validated = $request->validated();
        return ResponseBuilder::asSuccess(ApiCode::HTTP_OK)
            ->withHttpCode(ApiCode::HTTP_OK)
            ->withData(Customer::getList($validated))
            ->withMessage(__('message.common.search.success'))
            ->build();
    }


    public function hospitals(): Response
    {
        return ResponseBuilder::asSuccess(ApiCode::HTTP_OK)
            ->withHttpCode(ApiCode::HTTP_OK)
            ->withData(Customer::getHospitalList())
            ->withMessage(__('message.common.search.success'))
            ->build();
    }
    public function customerNames(): Response
    {
        return ResponseBuilder::asSuccess(ApiCode::HTTP_OK)
            ->withHttpCode(ApiCode::HTTP_OK)
            ->withData(Customer::getCustomerNameList())
            ->withMessage(__('message.common.search.success'))
            ->build();
    }

    public function city(): Response
    {
        return ResponseBuilder::asSuccess(ApiCode::HTTP_OK)
            ->withHttpCode(ApiCode::HTTP_OK)
            ->withData(Customer::getCityList())
            ->withMessage(__('message.common.search.success'))
            ->build();
    }

    public function county(): Response
    {
        return ResponseBuilder::asSuccess(ApiCode::HTTP_OK)
            ->withHttpCode(ApiCode::HTTP_OK)
            ->withData(Customer::getCountyList())
            ->withMessage(__('message.common.search.success'))
            ->build();
    }
    public function phone(): Response
    {
        return ResponseBuilder::asSuccess(ApiCode::HTTP_OK)
            ->withHttpCode(ApiCode::HTTP_OK)
            ->withData(Customer::getPhoneList())
            ->withMessage(__('message.common.search.success'))
            ->build();
    }
    public function produce(): Response
    {
        return ResponseBuilder::asSuccess(ApiCode::HTTP_OK)
            ->withHttpCode(ApiCode::HTTP_OK)
            ->withData(Customer::getProduceList())
            ->withMessage(__('message.common.search.success'))
            ->build();
    }
    public function tracker(): Response
    {
        return ResponseBuilder::asSuccess(ApiCode::HTTP_OK)
            ->withHttpCode(ApiCode::HTTP_OK)
            ->withData(Customer::getTrackerList())
            ->withMessage(__('message.common.search.success'))
            ->build();
    }
    public function bill(): Response
    {
        return ResponseBuilder::asSuccess(ApiCode::HTTP_OK)
            ->withHttpCode(ApiCode::HTTP_OK)
            ->withData(Customer::getBillList())
            ->withMessage(__('message.common.search.success'))
            ->build();
    }
    public function channelBusiness(): Response
    {
        return ResponseBuilder::asSuccess(ApiCode::HTTP_OK)
            ->withHttpCode(ApiCode::HTTP_OK)
            ->withData(Customer::getChannelBusinessList())
            ->withMessage(__('message.common.search.success'))
            ->build();
    }

    public function record(): Response
    {
        return ResponseBuilder::asSuccess(ApiCode::HTTP_OK)
            ->withHttpCode(ApiCode::HTTP_OK)
            ->withData(Customer::getRecordList())
            ->withMessage(__('message.common.search.success'))
            ->build();
    }
}
