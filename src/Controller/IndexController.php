<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends Controller
{
    /**
     * @Route(path="/", name="index")
     * @param KernelInterface $kernel
     * @return Response
     */
    public function index(KernelInterface $kernel)
    {
        $indexPath = $kernel->getProjectDir() . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "html" . DIRECTORY_SEPARATOR . "index.html";
        $indexContent = file_get_contents($indexPath);

        return new Response($indexContent);
    }
}