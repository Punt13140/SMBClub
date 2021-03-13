<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Category;
use App\Entity\Topic;
use App\Entity\User;
use App\Form\AnswerType;
use App\Form\TopicType;
use App\Repository\TopicRepository;
use App\Repository\UserRepository;
use App\Service\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/topic")
 */
class TopicController extends AbstractController
{

}
