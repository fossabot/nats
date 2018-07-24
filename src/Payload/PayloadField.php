<?php
declare(strict_types = 1);


namespace SmartWeb\Nats\Payload;

/**
 * Class PayloadField
 *
 * @api
 */
final class PayloadField
{
    
    /**
     * Key used for storing 'eventType' payload data.
     */
    public const EVENT_TYPE = 'eventType';
    
    /**
     * Key used for storing 'eventTypeVersion' payload data.
     */
    public const EVENT_TYPE_VERSION = 'eventTypeVersion';
    
    /**
     * Key used for storing 'cloudEventsVersion' payload data.
     */
    public const CLOUD_EVENTS_VERSION = 'cloudEventsVersion';
    
    /**
     * Key used for storing 'source' payload data.
     */
    public const SOURCE = 'source';
    
    /**
     * Key used for storing 'eventId' payload data.
     */
    public const EVENT_ID = 'eventId';
    
    /**
     * Key used for storing 'eventTime' payload data.
     */
    public const EVENT_TIME = 'eventTime';
    
    /**
     * Key used for storing 'schemaURL' payload data.
     */
    public const SCHEMA_URL = 'schemaURL';
    
    /**
     * Key used for storing 'contentType' payload data.
     */
    public const CONTENT_TYPE = 'contentType';
    
    /**
     * Key used for storing 'extensions' payload data.
     */
    public const EXTENSIONS = 'extensions';
    
    /**
     * Key used for storing 'data' payload data.
     */
    public const DATA = 'data';
    
    private function __construct()
    {
    }
}
