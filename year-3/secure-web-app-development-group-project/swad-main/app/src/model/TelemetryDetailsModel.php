<?php
/**
 * class TelemetryDetailsModel, 8 public functions (PF)
 * Meta data sending and receiving file, private keyword to restricts accessibility to these variables
 * @__construct(): called when the object of class is created, auto set to null
 * @__destruct(): auto called at end of script
 * @setSoapWrapper: assigns setSoapWrapper to $soap_wrapper
 * @setParameters: assigns setParameters to $cleaned_parameters and then $cleaned_parameters to id
 * @doSoapCall: function to carry out the soap call, sets doSoapCall as an array $soap_call_details
 *       autoset as null, creates soap client, returns $result
 * @getResult: public function to return result identified by $getResult
 * @peakMessageDetail: allows us to view messages without them being set as read messages. returns $select_detail
 * @sendMessageDetail: send message from M2M connect to our device. returns $send_message_detail
 */

class TelemetryDetailsModel
{
    private $id;
    private $result;
    private $soap_wrapper;

    public function __construct()
    {
        $this->soap_wrapper = null;
        $this->id = '';
        $this->result = [];
    }

    public function __destruct(){}

    public function setSoapWrapper($soap_wrapper)
    {
        $this->soap_wrapper = $soap_wrapper;
    }

    public function setParameters($cleaned_parameters)
    {
        $this->id = $cleaned_parameters['id'];
    }


    public function doSoapCall(array $soap_call_details)
    {
        $soapcall_result = null;

        $soap_client_handle = $this->soap_wrapper->createSoapClient();

        if ($soap_client_handle !== false)
        {
            $webservice_function = $soap_call_details['required_service'];
            $webservice_call_parameters = $soap_call_details['service_parameters'];
            $webservice_object_name = $soap_call_details['result_object'];

            $soapcall_result = $this->soap_wrapper->performSoapCall($soap_client_handle, $webservice_function, $webservice_call_parameters, $webservice_object_name);

            $this->result = $soapcall_result;

        }
    }

    public function getResult()
    {
        return $this->result;
    }

    public function peakMessageDetail()
    {
        $select_detail['required_service'] = 'peekMessages';

        $select_detail['service_parameters'] = [
            'username' => '22_2589411',
            'password' => '5nowLeopardUK007',
            'count' => '100',
            'deviceMsisdn' => '447817814149',
            'countryCode' => '44',
        ];

        $select_detail['result_object'] = 'peekMessagesResponse ';

        return $select_detail;

    }

    public function sendMessageDetail(string $message)
    {
        $send_message_detail['required_service'] = 'sendMessage';

        $send_message_detail['service_parameters'] = [
            'username' => '22_2589411',
            'password' => '5nowLeopardUK007',
            'deviceMsisdn' => '447817814149',
            'message' =>  $message,
            'deliveryReport' => true,
            'mtBearer' => 'SMS',
        ];

        $send_message_detail['result_object'] = 'sendMessageResponse';

        return $send_message_detail;
    }


}
