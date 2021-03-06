<?php
declare(strict_types = 1);


namespace SmartWeb\Nats\Tests\Event\Serialization;

use SmartWeb\CloudEvents\Nats\Event\EventFields;

/**
 * Creates providers of sample data for CloudEvents event serialization tests.
 *
 * @author Nicolai Agersbæk <na@smartweb.dk>
 *
 * @internal
 */
final class EventProviderFactory
{
    
    /**
     * @var self
     */
    private static $instance;
    
    /**
     * @var array
     */
    private $completeEventContents;
    
    /**
     * @var EventProviderInterface[]
     */
    private $providers = [];
    
    /**
     * @var string
     */
    private $dateTimeFormat;
    
    private function __construct()
    {
        $this->dateTimeFormat = \DateTime::RFC3339;
    }
    
    /**
     * @return self
     */
    public static function create() : self
    {
        return self::$instance ?? self::$instance = new self();
    }
    
    /**
     * @return EventProviderInterface
     */
    public function complete() : EventProviderInterface
    {
        $eventContents = $this->getContents(EventFields::getSupportedFields());
        
        return $this->providers['complete'] ?? $this->providers['complete'] = new EventProvider($eventContents);
    }
    
    /**
     * @return EventProviderInterface
     */
    public function minimal() : EventProviderInterface
    {
        $eventContents = $this->getContents(EventFields::getRequiredFields());
        
        return $this->providers['minimal'] ?? $this->providers['minimal'] = new EventProvider($eventContents);
    }
    
    /**
     * @param string[] $includedFields
     *
     * @return EventProviderInterface
     */
    public function custom(array $includedFields) : EventProviderInterface
    {
        $eventContents = $this->getContents($includedFields);
        
        return new EventProvider($eventContents);
    }
    
    /**
     * @param string[] $includedFields
     *
     * @return array
     */
    private function getContents(array $includedFields) : array
    {
        $this->validateFieldListIsCorrect($includedFields);
        
        $data = $this->getCompleteEventContents();
        
        foreach ($data as $field => &$value) {
            $value = \in_array($field, $includedFields, true)
                ? $value
                : null;
        }
        
        return $data;
    }
    
    /**
     * @param array $fieldList
     */
    private function validateFieldListIsCorrect(array $fieldList) : void
    {
        if ($this->hasMissingFields($fieldList)) {
            $missingFields = \implode("', '", $this->getMissingFields($fieldList));
            throw new \LogicException("Missing fields: ['{$missingFields}']");
        }
        
        if ($this->hasExtraFields($fieldList)) {
            $extraFields = \implode("', '", $this->getExtraFields($fieldList));
            throw new \LogicException("Too many fields: ['{$extraFields}']");
        }
    }
    
    /**
     * @param array $fieldList
     *
     * @return bool
     */
    private function hasMissingFields(array $fieldList) : bool
    {
        return \count($this->getMissingFields($fieldList)) !== 0;
    }
    
    /**
     * @param array $fieldList
     *
     * @return array
     */
    private function getMissingFields(array $fieldList) : array
    {
        return \array_diff(EventFields::getRequiredFields(), $fieldList);
    }
    
    /**
     * @param array $fieldList
     *
     * @return bool
     */
    private function hasExtraFields(array $fieldList) : bool
    {
        return \count($this->getExtraFields($fieldList)) !== 0;
    }
    
    /**
     * @param array $fieldList
     *
     * @return array
     */
    private function getExtraFields(array $fieldList) : array
    {
        return \array_diff($fieldList, EventFields::getSupportedFields());
    }
    
    /**
     * @return array
     */
    private function getCompleteEventContents() : array
    {
        return $this->completeEventContents ?? $this->completeEventContents = $this->resolveCompleteEventContents();
    }
    
    /**
     * @return array
     */
    private function resolveCompleteEventContents() : array
    {
        return [
            EventFields::EVENT_TYPE           => 'some.event',
            EventFields::EVENT_TYPE_VERSION   => '1.0.0',
            EventFields::CLOUD_EVENTS_VERSION => '0.1.0',
            EventFields::SOURCE               => 'some.source',
            EventFields::EVENT_ID             => 'some.event.id',
            EventFields::EVENT_TIME           => $this->getFixedTime()->format($this->dateTimeFormat),
            EventFields::SCHEMA_URL           => 'https://www.some-schema.org/cloud-events/test.schema?version=2.3.4',
            EventFields::CONTENT_TYPE         => 'application/json',
            EventFields::EXTENSIONS           => [
                'com.foo.extension' => 'barExtension',
            ],
            EventFields::DATA                 => [
                'foo' => 'bar',
            ],
        ];
    }
    
    /**
     * @return \DateTimeInterface
     */
    private function getFixedTime() : \DateTimeInterface
    {
        $time = new \DateTime();
        $time->setDate(2000, 1, 2);
        $time->setTime(12, 34, 56);
        
        $timeZone = new \DateTimeZone('Europe/Copenhagen');
        $time->setTimezone($timeZone);
        
        return $time;
    }
}
