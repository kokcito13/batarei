<?php

/**
 * This file is part of the <name> project.
 *
 * (c) <yourname> <youremail>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\UserBundle\Entity;

use Sonata\UserBundle\Entity\BaseUser as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * This file has been generated by the Sonata EasyExtends bundle ( http://sonata-project.org/bundles/easy-extends )
 *
 * References :
 *   working with object : http://www.doctrine-project.org/projects/orm/2.0/docs/reference/working-with-objects/en
 *
 * @author <yourname> <youremail>
 */
class User extends BaseUser
{
    /**
     * @var integer $id
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar_name", type="string", length=255)
     */
    protected $avatar_name;

    protected $file;

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set avatar_name
     *
     * @param string $avatar_name
     * @return User
     */
    public function setAvatarName($avatar_name)
    {
        $this->file = $avatar_name;

        return $this;
    }

    /**
     * Get avatar_name
     *
     * @return string
     */
    public function getAvatarName()
    {
        return $this->avatar_name;
    }


    public function getAbsolutePath()
    {
        return null === $this->avatar_name ? null : $this->getUploadRootDir('') . '/' . $this->avatar_name;
    }

    public function getWebPath()
    {
        return null === $this->avatar_name ? null : $this->getUploadDir() . '/' . $this->avatar_name;
    }

    protected function getUploadRootDir($basepath)
    {
        return $basepath . $this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return 'uploads/users/'.$this->getId();
    }

    public function upload($basepath)
    {
        if (null === $this->file) {
            return;
        }

        if (null === $basepath) {
            return;
        }

        $this->file->move($this->getUploadRootDir($basepath), $this->file->getClientOriginalName());
        $this->setImageName($this->file->getClientOriginalName());
        $this->file = null;
    }

    public function setImageName($image_name)
    {
        $this->avatar_name = $image_name;

        return $this;
    }

    public function setImageFromFile()
    {
        if (null === $this->file) {
            return;
        }
        $this->setImageName($this->file->getClientOriginalName());
    }

}