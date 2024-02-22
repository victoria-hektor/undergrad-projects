<?php

/**
 * creates class SoapWrapper, public functions __construct(), __destruct()
 *
 * @createSoapClient, returns $soap_client_handle
 * @performSoapCall, returns $soap_call_result
 *
 **/

class SoapWrapper
{
    public function __construct(){}
    public function __destruct(){}

    public function createSoapClient()
    {
        $soap_client_handle = false;
        $soap_client_parameters = [];
        $exception = '';


        $soap_client_parameters = ['trace' => true, 'exceptions' => true];

        try
        {
            $soap_client_handle = new SoapClient(WDSL, $soap_client_parameters);
        }
        catch (SoapFault $exception)
        {
            $soap_client_handle = 'Ooops - something went wrong when connecting to the data supplier.  Please try again later';
            echo $exception;
        }

        return $soap_client_handle;

    }

    public function performSoapCall($soap_client_handle, $webservice_function, $webservice_call_parameters)
    {
        $soap_call_result = null;

        if ($soap_client_handle)
        {
            try
            {
                $webservice_call_result = $soap_client_handle->__soapCall($webservice_function, $webservice_call_parameters);
                #var_dump($webservice_call_result);
                $soap_call_result = $webservice_call_result;
            }
            catch (SoapFault $exception)
            {
                $soap_call_result = $exception;

            }
        }
        return $soap_call_result;

    }
}
