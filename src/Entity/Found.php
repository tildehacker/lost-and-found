<?php

namespace App\Entity;

use Captcha\Bundle\CaptchaBundle\Validator\Constraints as CaptchaAssert;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="App\Repository\FoundRepository")
 */
class Found
{
    /**
     * @CaptchaAssert\ValidCaptcha(
     *      message = "CAPTCHA validation failed, try again."
     * )
     */
    protected $captchaCode;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;
    /**
     * @ORM\Column(type="date")
     */
    private $when_found;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;
    /**
     * @ORM\Column(type="decimal", precision=11, scale=8)
     */
    private $where_longitude;
    /**
     * @ORM\Column(type="decimal", precision=10, scale=8)
     */
    private $where_latitude;

    public function getCaptchaCode()
    {
        return $this->captchaCode;
    }

    public function setCaptchaCode($captchaCode)
    {
        $this->captchaCode = $captchaCode;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getWhenFound(): ?\DateTimeInterface
    {
        return $this->when_found;
    }

    public function setWhenFound(\DateTimeInterface $when_found): self
    {
        $this->when_found = $when_found;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getWhereLongitude()
    {
        return $this->where_longitude;
    }

    public function setWhereLongitude($where_longitude): self
    {
        $this->where_longitude = $where_longitude;

        return $this;
    }

    public function getWhereLatitude()
    {
        return $this->where_latitude;
    }

    public function setWhereLatitude($where_latitude): self
    {
        $this->where_latitude = $where_latitude;

        return $this;
    }
}
