<?php

namespace DoctrineORMModule\Proxy\__CG__\Olcs\Db\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class User extends \Olcs\Db\Entity\User implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Common\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array properties to be lazy loaded, with keys being the property
     *            names and values being their default values
     *
     * @see \Doctrine\Common\Persistence\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = array();



    /**
     * @param \Closure $initializer
     * @param \Closure $cloner
     */
    public function __construct($initializer = null, $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }







    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return array('__isInitialized__', 'partnerContactDetails', 'pid', 'accountDisabled', 'id', 'transportManager', 'contactDetails', 'lastModifiedBy', 'localAuthority', 'createdBy', 'team', 'emailAddress', 'name', 'deletedDate', 'createdOn', 'lastModifiedOn', 'version');
        }

        return array('__isInitialized__', 'partnerContactDetails', 'pid', 'accountDisabled', 'id', 'transportManager', 'contactDetails', 'lastModifiedBy', 'localAuthority', 'createdBy', 'team', 'emailAddress', 'name', 'deletedDate', 'createdOn', 'lastModifiedOn', 'version');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (User $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy->__getLazyProperties() as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', array());
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', array());
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function getIdentifier()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getIdentifier', array());

        return parent::getIdentifier();
    }

    /**
     * {@inheritDoc}
     */
    public function setPartnerContactDetails($partnerContactDetails)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPartnerContactDetails', array($partnerContactDetails));

        return parent::setPartnerContactDetails($partnerContactDetails);
    }

    /**
     * {@inheritDoc}
     */
    public function getPartnerContactDetails()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPartnerContactDetails', array());

        return parent::getPartnerContactDetails();
    }

    /**
     * {@inheritDoc}
     */
    public function setPid($pid)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPid', array($pid));

        return parent::setPid($pid);
    }

    /**
     * {@inheritDoc}
     */
    public function getPid()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPid', array());

        return parent::getPid();
    }

    /**
     * {@inheritDoc}
     */
    public function setAccountDisabled($accountDisabled)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setAccountDisabled', array($accountDisabled));

        return parent::setAccountDisabled($accountDisabled);
    }

    /**
     * {@inheritDoc}
     */
    public function getAccountDisabled()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAccountDisabled', array());

        return parent::getAccountDisabled();
    }

    /**
     * {@inheritDoc}
     */
    public function clearProperties($properties = array (
))
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'clearProperties', array($properties));

        return parent::clearProperties($properties);
    }

    /**
     * {@inheritDoc}
     */
    public function setId($id)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setId', array($id));

        return parent::setId($id);
    }

    /**
     * {@inheritDoc}
     */
    public function getId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getId', array());

        return parent::getId();
    }

    /**
     * {@inheritDoc}
     */
    public function setTransportManager($transportManager)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTransportManager', array($transportManager));

        return parent::setTransportManager($transportManager);
    }

    /**
     * {@inheritDoc}
     */
    public function getTransportManager()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTransportManager', array());

        return parent::getTransportManager();
    }

    /**
     * {@inheritDoc}
     */
    public function setContactDetails($contactDetails)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setContactDetails', array($contactDetails));

        return parent::setContactDetails($contactDetails);
    }

    /**
     * {@inheritDoc}
     */
    public function getContactDetails()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getContactDetails', array());

        return parent::getContactDetails();
    }

    /**
     * {@inheritDoc}
     */
    public function setLastModifiedBy($lastModifiedBy)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLastModifiedBy', array($lastModifiedBy));

        return parent::setLastModifiedBy($lastModifiedBy);
    }

    /**
     * {@inheritDoc}
     */
    public function getLastModifiedBy()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLastModifiedBy', array());

        return parent::getLastModifiedBy();
    }

    /**
     * {@inheritDoc}
     */
    public function setLocalAuthority($localAuthority)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLocalAuthority', array($localAuthority));

        return parent::setLocalAuthority($localAuthority);
    }

    /**
     * {@inheritDoc}
     */
    public function getLocalAuthority()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLocalAuthority', array());

        return parent::getLocalAuthority();
    }

    /**
     * {@inheritDoc}
     */
    public function setCreatedBy($createdBy)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCreatedBy', array($createdBy));

        return parent::setCreatedBy($createdBy);
    }

    /**
     * {@inheritDoc}
     */
    public function getCreatedBy()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCreatedBy', array());

        return parent::getCreatedBy();
    }

    /**
     * {@inheritDoc}
     */
    public function setTeam($team)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTeam', array($team));

        return parent::setTeam($team);
    }

    /**
     * {@inheritDoc}
     */
    public function getTeam()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTeam', array());

        return parent::getTeam();
    }

    /**
     * {@inheritDoc}
     */
    public function setEmailAddress($emailAddress)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEmailAddress', array($emailAddress));

        return parent::setEmailAddress($emailAddress);
    }

    /**
     * {@inheritDoc}
     */
    public function getEmailAddress()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEmailAddress', array());

        return parent::getEmailAddress();
    }

    /**
     * {@inheritDoc}
     */
    public function setName($name)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setName', array($name));

        return parent::setName($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getName', array());

        return parent::getName();
    }

    /**
     * {@inheritDoc}
     */
    public function setDeletedDate($deletedDate)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDeletedDate', array($deletedDate));

        return parent::setDeletedDate($deletedDate);
    }

    /**
     * {@inheritDoc}
     */
    public function getDeletedDate()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDeletedDate', array());

        return parent::getDeletedDate();
    }

    /**
     * {@inheritDoc}
     */
    public function isDeleted()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isDeleted', array());

        return parent::isDeleted();
    }

    /**
     * {@inheritDoc}
     */
    public function setCreatedOn($createdOn)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCreatedOn', array($createdOn));

        return parent::setCreatedOn($createdOn);
    }

    /**
     * {@inheritDoc}
     */
    public function getCreatedOn()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCreatedOn', array());

        return parent::getCreatedOn();
    }

    /**
     * {@inheritDoc}
     */
    public function setCreatedOnBeforePersist()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCreatedOnBeforePersist', array());

        return parent::setCreatedOnBeforePersist();
    }

    /**
     * {@inheritDoc}
     */
    public function setLastModifiedOn($lastModifiedOn)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLastModifiedOn', array($lastModifiedOn));

        return parent::setLastModifiedOn($lastModifiedOn);
    }

    /**
     * {@inheritDoc}
     */
    public function getLastModifiedOn()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLastModifiedOn', array());

        return parent::getLastModifiedOn();
    }

    /**
     * {@inheritDoc}
     */
    public function setLastModifiedOnBeforeUpdate()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLastModifiedOnBeforeUpdate', array());

        return parent::setLastModifiedOnBeforeUpdate();
    }

    /**
     * {@inheritDoc}
     */
    public function setVersion($version)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setVersion', array($version));

        return parent::setVersion($version);
    }

    /**
     * {@inheritDoc}
     */
    public function getVersion()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getVersion', array());

        return parent::getVersion();
    }

    /**
     * {@inheritDoc}
     */
    public function setVersionBeforePersist()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setVersionBeforePersist', array());

        return parent::setVersionBeforePersist();
    }

}
