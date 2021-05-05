<?php

namespace App\DTO;

use Symfony\Component\Ldap\Entry;

/**
 * LDAP Entry Data Transfer Object used for serialization (API, CLI, ...).
 */
class LdapEntryDTO
{
    /**
     * @var string the entry's DN.
     */
    private $fullDn;
    /**
     * @var array the entry's complete list of attributes.
     */
    private $attributes;

    public function __construct(string $fullDn = null, array $attributes = [])
    {
        $this->fullDn = $fullDn;
        $this->attributes = $attributes;
    }

    /**
     * Returns the entry's DN.
     *
     * @return string
     */
    public function getDn()
    {
        return $this->fullDn;
    }

    /**
     * Sets the entry's DN.
     *
     * @param string $fullDn The entry's DN.
     *
     * @return void
     */
    public function setDn($fullDn): void
    {
        $this->fullDn = $fullDn;
    }

    /**
     * Returns the complete list of attributes.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Sets the complete list of attributes.
     *
     * @param string $attributes The complete list of attributes.
     *
     * @return void
     */
    public function setAttributes($attributes): void
    {
        $this->attributes = $attributes;
    }

    /**
     * Returns whether an attribute exists.
     *
     * @param string $name The name of the attribute
     *
     * @return bool
     */
    public function hasAttribute($name)
    {
        return isset($this->attributes[$name]);
    }

    /**
     * Returns a specific attribute's value.
     *
     * As LDAP can return multiple values for a single attribute,
     * this value is returned as an array.
     *
     * @param string $name The name of the attribute
     *
     * @return array|null
     */
    public function getAttribute($name)
    {
        return $this->attributes[$name] ?? null;
    }

    /**
     * Sets a value for the given attribute.
     *
     * @param string $name attribute name.
     *
     * @return void
     */
    public function setAttribute($name, array $value): void
    {
        $this->attributes[$name] = $value;
    }

    /**
     * Removes a given attribute.
     *
     * @param string $name attribute name.
     *
     * @return void
     */
    public function removeAttribute($name): void
    {
        unset($this->attributes[$name]);
    }

    /**
     * @param string $format Serialization format. Can be either 'json' or 'ldif'.
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @return string Serialized LDAP entry.
     */
    public function serialize(string $format)
    {
        return LdapEntrySerializer::serializeEntry($this, $format);
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @return Entry Convert LDAP entry DTO into full fledged LDAP Entry.
     */
    public function toEntry(): Entry
    {
        $entry = new Entry($this->fullDn, $this->attributes);
        // Automatically decode all base64 fields
        LdapEntrySerializer::fromBase64($entry);
        return $entry;
    }

    /**
     * @param Entry $entry LDAP Entry to convert into a LDAP entry DTO.
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * @return LdapEntryDTO LDAP entry DTO.
     */
    public static function fromEntry(Entry $entry): LdapEntryDTO
    {
        $entry = new LdapEntryDTO($entry->getDn(), $entry->getAttributes());
        // Automatically convert binary fields into base64
        LdapEntrySerializer::binaryToBase64($entry);
        return $entry;
    }
}
