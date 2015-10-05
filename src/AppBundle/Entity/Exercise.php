<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Exercise
 *
 * @ORM\Table(name="exercises")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ExerciseRepository")
 */
class Exercise
{
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=80)
     * @Assert\NotBlank(message="You must enter a Tag Name!")
     * @Assert\Length(
     *      min = 5,
     *      max = 80,
     *      minMessage = "The exercise name must be at least 5 letters.",
     *      maxMessage = "The exercise name can only be 80 characters long"
     * )
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="muscleGroup", type="string", length=10)
     * @Assert\NotBlank(message="You must enter a Muscle Group!")
     * @Assert\Length(
     *      min = 3,
     *      max = 10,
     *      minMessage = "The muscle group must be at least 3 letters.",
     *      maxMessage = "The muscle  can only be 10 characters long"
     * )
     */
    private $muscleGroup;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Assert\NotBlank(message="You must enter a Muscle Group!")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="youtubeUrl", type="string", length=80)
     * @Assert\NotBlank(message="Please enter a Youtube URL")
     */
    private $youtubeUrl;

    /**
     * @var float
     * @ORM\Column(name="rating", type="float")
     */
    private $rating;


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
     * Set name
     *
     * @param string $name
     *
     * @return Exercise
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set muscleGroup
     *
     * @param string $muscleGroup
     *
     * @return Exercise
     */
    public function setMuscleGroup($muscleGroup)
    {
        $this->muscleGroup = $muscleGroup;

        return $this;
    }

    /**
     * Get muscleGroup
     *
     * @return string
     */
    public function getMuscleGroup()
    {
        return $this->muscleGroup;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Exercise
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set youtubeUrl
     *
     * @param string $youtubeUrl
     *
     * @return Exercise
     */
    public function setYoutubeUrl($youtubeUrl)
    {
        $this->youtubeUrl = $youtubeUrl;

        return $this;
    }

    /**
     * Get youtubeUrl
     *
     * @return string
     */
    public function getYoutubeUrl()
    {
        return $this->youtubeUrl;
    }
    
    /**
     * Set rating
     *
     * @param float $rating
     *
     * @return Exercise
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return float
     */
    public function getRating()
    {
        return $this->rating;
    }
}
