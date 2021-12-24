<?php

namespace App\Entity;

use App\Repository\RepLogRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RepLogRepository::class)
 */
class RepLog
{
    const WEIGHT_FAT_CAT = 18;

    private static $thingsYouCanLift = [
        'cat' => '9',
        'fat_cat' => self::WEIGHT_FAT_CAT,
        'laptop' => '4.5',
        'coffee_cup' => '.5',
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $reps;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $item;

    /**
     * @ORM\Column(type="float")
     */
    private $totalWeightLifted;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="repLogs")
     */
    private $author;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReps(): ?int
    {
        return $this->reps;
    }

    public function setReps(int $reps): self
    {
        $this->reps = $reps;

        return $this;
    }

    public function getItem(): ?string
    {
        return $this->item;
    }

    public function setItem(string $item): self
    {
        $this->item = $item;
        $this->calcTotalWeightLifted();

        return $this;
    }

    public function getTotalWeightLifted(): ?float
    {
        return $this->totalWeightLifted;
    }

    public function setTotalWeightLifted(float $totalWeightLifted): self
    {
        $this->totalWeightLifted = $totalWeightLifted;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getThingsYouCanLift()
    {
        return self::$thingsYouCanLift;
    }

    private function calcTotalWeightLifted()
    {
        $this->setTotalWeightLifted($this->reps * self::$thingsYouCanLift[$this->item]);
    }
}
