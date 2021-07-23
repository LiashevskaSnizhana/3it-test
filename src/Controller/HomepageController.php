<?php
declare(strict_types=1);


namespace App\Controller;


class HomepageController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{

    /**
     * @\Symfony\Component\Routing\Annotation\Route("/", name="app_homepage_index")
     */
    public function indexAction(
        \Symfony\Component\HttpFoundation\Request $request
    ): \Symfony\Component\HttpFoundation\Response
    {
        return $this->render('homepage/index.html.twig');
    }

}