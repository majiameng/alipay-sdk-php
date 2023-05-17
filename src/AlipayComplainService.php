<?php

namespace tinymeng\pay\Alipay;

/**
 * 支付宝交易投诉处理类
 * @see https://opendocs.alipay.com/open/02z18r
 */
class AlipayComplainService extends AlipayService
{
    public function __construct($config)
    {
        parent::__construct($config);
    }

    /**
     * 查询单条交易投诉详情
     * @param $complain_event_id 支付宝侧投诉单号
     * @return mixed {"complain_event_id":"支付宝侧投诉单号","status":"MERCHANT_PROCESSING","trade_no":"支付宝交易号","merchant_order_no":"商家订单号","gmt_create":"投诉单创建时间","gmt_modified":"投诉单修改时间","gmt_finished":"投诉单完结时间","leaf_category_name":"用户投诉诉求","complain_reason":"用户投诉原因","content":"用户投诉内容","images":[],"phone_no":"投诉人电话号码","trade_amount":"交易金额","reply_detail_infos":[]}
     */
    public function query($complain_event_id)
    {
        $apiName = 'alipay.merchant.tradecomplain.query';
        $bizContent = [
            'complain_event_id' => $complain_event_id,
        ];
        return $this->aopExecute($apiName, $bizContent);
    }

    /**
     * 查询交易投诉列表
     * @param $status 状态
     * @param $begin_time 查询开始时间
     * @param $end_time 查询结束时间
     * @param $page_num 当前页
     * @param $page_size 每页条数,最多支持20条
     * @return mixed {"page_size":10,"page_num":1,"total_page_num":5,"total_num":55,"trade_complain_infos":[]}
     */
    public function batchQuery($status = null, $begin_time = null, $end_time = null, $page_num = 1, $page_size = 10)
    {
        $apiName = 'alipay.merchant.tradecomplain.batchquery';
        $bizContent = [
            'page_num' => $page_num,
            'page_size' => $page_size,
        ];
        if ($status) $bizContent['status'] = $status;
        if ($begin_time) $bizContent['begin_time'] = $begin_time;
        if ($end_time) $bizContent['end_time'] = $end_time;
        return $this->aopExecute($apiName, $bizContent);
    }

    /**
     * 商户上传处理图片
     * @param $image_type 图片格式，支持格式：jpg、jpeg、png
     * @param $image_path 图片文件路径
     * @return string 图片资源标识
     */
    public function imageUpload($image_type, $image_path)
    {
        $apiName = 'alipay.merchant.image.upload';
        $bizContent = [
            'image_type' => $image_type,
            'image_content' => '@'.$image_path,
        ];
        $result = $this->aopExecute($apiName, $bizContent);
        return $result['image_id'];
    }

    /**
     * 商家处理交易投诉
     * @param $complain_event_id 投诉单号
     * @param $feedback_code 反馈类目ID
     * @param $feedback_content 反馈内容
     * @param $feedback_images 反馈图片id列表(多个用逗号隔开)
     * @return bool
     */
    public function feedbackSubmit($complain_event_id, $feedback_code, $feedback_content, $feedback_images = null)
    {
        $apiName = 'alipay.merchant.tradecomplain.feedback.submit';
        $bizContent = [
            'complain_event_id' => $complain_event_id,
            'feedback_code' => $feedback_code,
            'feedback_content' => $feedback_content,
        ];
        if ($feedback_images) $bizContent['feedback_images'] = $feedback_images;
        $this->aopExecute($apiName, $bizContent);
        return true;
    }

    /**
     * 商家留言回复
     * @param $complain_event_id 投诉单号
     * @param $reply_content 回复内容
     * @param $reply_images 回复图片(多个用逗号隔开)
     * @return bool
     */
    public function replySubmit($complain_event_id, $reply_content, $reply_images = null)
    {
        $apiName = 'alipay.merchant.tradecomplain.reply.submit';
        $bizContent = [
            'complain_event_id' => $complain_event_id,
            'reply_content' => $reply_content,
        ];
        if ($reply_images) $bizContent['reply_images'] = $reply_images;
        $this->aopExecute($apiName, $bizContent);
        return true;
    }

     /**
     * 商家补充凭证
     * @param $complain_event_id 投诉单号
     * @param $supplement_content 文字凭证
     * @param $supplement_images 图片凭证(多个用逗号隔开)
     * @return bool
     */
    public function supplementSubmit($complain_event_id, $supplement_content, $supplement_images = null)
    {
        $apiName = 'alipay.merchant.tradecomplain.supplement.submit';
        $bizContent = [
            'complain_event_id' => $complain_event_id,
            'supplement_content' => $supplement_content,
        ];
        if ($supplement_images) $bizContent['supplement_images'] = $supplement_images;
        $this->aopExecute($apiName, $bizContent);
        return true;
    }

}