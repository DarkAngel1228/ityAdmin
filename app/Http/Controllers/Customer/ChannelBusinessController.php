<?php
/**
 * User: zhangjinyu
 * Date: 2021/12/20 11:08
 * Email: zhangjinyu@pzoom.com
 * Action: 用途
 */

namespace App\Http\Controllers\Customer;


use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Http\Requests\Customer\GetListRequest;
use App\Http\Requests\Customer\CreateCustomerAndRecordRequest;
use App\Http\Response\ApiCode;
use App\Models\ChannelBusiness;
use App\Models\Customer;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Symfony\Component\HttpFoundation\Response;

class ChannelBusinessController extends Controller
{
    /**
     * 客户列表
     *
     * @param GetListRequest $request
     * @return Response
     */
    public function channelBusiness(GetListRequest $request): Response
    {
        $validated = $request->validated();
        return ResponseBuilder::asSuccess(ApiCode::HTTP_OK)
            ->withHttpCode(ApiCode::HTTP_OK)
            ->withData(ChannelBusiness::getList($validated))
            ->withMessage(__('message.common.search.success'))
            ->build();
    }

    public function companyList(): Response
    {
        return ResponseBuilder::asSuccess(ApiCode::HTTP_OK)
            ->withHttpCode(ApiCode::HTTP_OK)
            ->withData(ChannelBusiness::getCompanyList())
            ->withMessage(__('message.common.search.success'))
            ->build();
    }
    public function produceList(): Response
    {
        return ResponseBuilder::asSuccess(ApiCode::HTTP_OK)
            ->withHttpCode(ApiCode::HTTP_OK)
            ->withData(ChannelBusiness::getProduceList())
            ->withMessage(__('message.common.search.success'))
            ->build();
    }

    public function channelBusinessList(): Response
    {
        return ResponseBuilder::asSuccess(ApiCode::HTTP_OK)
            ->withHttpCode(ApiCode::HTTP_OK)
            ->withData(ChannelBusiness::getChannelBusinessList())
            ->withMessage(__('message.common.search.success'))
            ->build();
    }

    public function createChannelBusiness(CreateCustomerAndRecordRequest $request): Response
    {
        $validated = $request->validated();
        return ResponseBuilder::asSuccess(ApiCode::HTTP_OK)
            ->withHttpCode(ApiCode::HTTP_OK)
            ->withData(ChannelBusiness::createChannelBusiness($validated))
            ->withMessage(__('message.common.create.success'))
            ->build();
    }
    public function updateChannelBusiness(UpdateCustomerRequest $request): Response
    {
        $validated = $request->validated();
        return ResponseBuilder::asSuccess(ApiCode::HTTP_OK)
            ->withHttpCode(ApiCode::HTTP_OK)
            ->withData(ChannelBusiness::updateChannelBusiness($validated))
            ->withMessage(__('message.common.update.success'))
            ->build();
    }
    public function deleteChannelBusiness($id): Response
    {
        return ResponseBuilder::asSuccess(ApiCode::HTTP_OK)
            ->withHttpCode(ApiCode::HTTP_OK)
            ->withData(ChannelBusiness::deleteChannelBusiness($id))
            ->withMessage(__('message.common.delete.success'))
            ->build();
    }

}
