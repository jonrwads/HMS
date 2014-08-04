<?php

namespace Application\Model;

use Application\BaseClass\Entity    as BaseEntity;
use Doctrine\ORM\Mapping            as ORM;

/**
 * @ORM\Table(name="applicationuser")
 * @ORM\Entity
 */

class User extends BaseEntity {
    public $_generatorInstructions = [        
    ];
    
    /**
     *
     * @var uuid $id
     * @ORM\Column(type = "string", length = 40, nullable = false)
     * @ORM\Id
     */
    protected $id;
    
    /**
     *
     * @var string $username
     * @ORM\Column(type = "string", length = 40, nullable = false, unique = true)
     */
    protected $username;
    
    /**
     *
     * @var string $password
     * @ORM\Column(type = "string", length = 40, nullable = false)
     */
    protected $password;
    
    /**
     *
     * @var string $password
     * @ORM\Column(type = "string", length = 250, nullable = false, unique = true)
     */
    protected $name;
    
    /**
     *
     * @var User $createdBy
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable= true):
     */
    protected $createdBy;
    
    /**
     *
     * @var DateTime $createdOn
     * @ORM\Column(type = "datetime", nullable = false)
     */
    protected $createdOn;
    
    /**
     *
     * @var User $createdBy
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable= true):
     */
    protected $modifiedBy;
    
    /**
     *
     * @var DateTime $modifiedOn
     * @ORM\Column(type = "datetime", nullable = true)
     */
    protected $modifiedOn;
    
    /**
     *
     * @var boolean $enabled
     * @ORM\Column(type = "boolean", nullable = false)
     */
    protected $enabled = true;
    
    public function __construct(\Zend\ServiceManager\ServiceManager $serviceManager = null) {
        parent::__construct($serviceManager);
        $this->createdOn = new \DateTime();
        $this->id = uniqid('usr', true);
    }
    
    public function toIdentity() {
        return [
            'id'            => $this->id,
            'createdOn'     => $this->createdOn->format('c'),
            'modifiedOn'    => !is_null($this->modifiedOn)?$this->modifiedOn->format('c'):null,
            'username'      => $this->username,
            'name'          => $this->name,
            'enabled'       => $this->enabled
        ];
    }
}
