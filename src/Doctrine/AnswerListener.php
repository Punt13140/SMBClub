<?php


namespace App\Doctrine;


use App\Entity\Answer;
use Symfony\Component\Security\Core\Security;

class AnswerListener
{
    /**
     * @var Security
     */
    private $security;

    /**
     * AnswerListener constructor.
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    public function prePersist(Answer $answer)
    {
        $answer->setPostedAt(new \DateTime());
        if ($answer->getPostedBy()) {
            return;
        }
        if ($this->security->getUser()) {
            $answer->setPostedBy($this->security->getUser());
        }
    }

    public function preUpdate(Answer $answer)
    {
        $answer->setEditedAt(new \DateTime());
    }


}