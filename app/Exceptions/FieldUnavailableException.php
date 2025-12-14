<?php

namespace App\Exceptions;

use Exception;

/**
 * Class FieldUnavailableException
 * 
 * Exception yang dilempar ketika lapangan tidak tersedia
 * untuk waktu yang diminta (double booking prevention).
 */
class FieldUnavailableException extends Exception
{
    protected $fieldName;
    protected $requestedTime;

    /**
     * Constructor
     * 
     * @param string $fieldName Nama lapangan yang tidak tersedia
     * @param string $requestedTime Waktu yang diminta
     * @param string $message Pesan error custom
     */
    public function __construct(string $fieldName = '', string $requestedTime = '', string $message = '')
    {
        $this->fieldName = $fieldName;
        $this->requestedTime = $requestedTime;
        
        if (empty($message)) {
            $message = "Lapangan '{$fieldName}' tidak tersedia pada waktu {$requestedTime}. Sudah dibooking oleh member lain.";
        }
        
        parent::__construct($message);
    }

    /**
     * Mendapatkan nama lapangan
     * 
     * @return string
     */
    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    /**
     * Mendapatkan waktu yang diminta
     * 
     * @return string
     */
    public function getRequestedTime(): string
    {
        return $this->requestedTime;
    }

    /**
     * Render exception sebagai HTTP response
     * 
     * @return \Illuminate\Http\Response
     */
    public function render()
    {
        return response()->json([
            'error' => 'Field Unavailable',
            'message' => $this->getMessage(),
            'field' => $this->fieldName,
            'requested_time' => $this->requestedTime
        ], 409); // 409 Conflict
    }
}
