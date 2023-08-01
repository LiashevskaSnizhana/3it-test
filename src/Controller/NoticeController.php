<?php
declare(strict_types=1);


namespace App\Controller;

use App\Entity\Notice;
use App\Form\XmlUploadForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Validator\Constraints\File;

class NoticeController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{

    /**
     * @\Symfony\Component\Routing\Annotation\Route("/", name="app_notice_index")
     */
    public function indexAction(
        \Symfony\Component\HttpFoundation\Request $request,
        EntityManagerInterface $entityManager
    ): \Symfony\Component\HttpFoundation\Response
    {
        $xmlUploadForm = $this->createForm( XmlUploadForm::class);

        $xmlUploadForm->handleRequest($request);
        if ($xmlUploadForm->isSubmitted() && $xmlUploadForm->isValid()) {
            $xmlFile = $xmlUploadForm->get('xmlFile')->getData();
            $xmlFileName = 'xml';
            file_put_contents($xmlFileName, file_get_contents($xmlFile));

            if (\file_exists($xmlFileName)) {
                $xmlFile = \simplexml_load_file($xmlFileName);
                foreach ($xmlFile as $noticeXml) {
                    $noticeXml = (array) $noticeXml;
                    $date = \DateTimeImmutable::createFromFormat('Y-m-d', $noticeXml['DATE']);
                    $notice = new Notice($noticeXml['JMENO'], $noticeXml['PRIJMENI'], $date);
                    $entityManager->persist($notice);
                }
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_notice_index');
        }

        return $this->render('notice/index.html.twig', [
            'xmlUploadForm' => $xmlUploadForm->createView(),
            'notices' => $entityManager->getRepository(Notice::class)->findAllFilteredByDate(),
        ]);
    }

}