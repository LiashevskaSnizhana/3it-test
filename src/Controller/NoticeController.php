<?php
declare(strict_types=1);


namespace App\Controller;

use App\Entity\Notice;
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
        $xmlUploadForm = $this->createFormBuilder()
            ->add('xmlFile', FileType::class,
                [
                    'label' => 'Notices (XML file)',
                    'mapped' => false,
                    'required' => true,

                    'constraints' => [
                        new File([
                            'maxSize' => '1024k',
                            'mimeTypes' => ['text/xml', 'application/xml'],
                            'mimeTypesMessage' => 'Please upload a valid XML document',
                        ])
                    ],
            ])
            ->add('save', SubmitType::class, ['label' => 'Upload'])
            ->getForm();

        $xmlUploadForm->handleRequest($request);
        if ($xmlUploadForm->isSubmitted() && $xmlUploadForm->isValid()) {
            $xmlFile = $xmlUploadForm->get('xmlFile')->getData();
            $xmlFileName = 'xml';

            if ($xmlFile) {
                try {
                    $xmlFile->move(
                       './',
                        $xmlFileName
                    );
                } catch (FileException $e) {
                    throw new \Exception("File couldn't be move.");
                }
            }

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